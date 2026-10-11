<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SampleRequest;
use App\Models\Quotation;
use App\Models\Enquiry;
use App\Models\EnquiryReply;
use App\Services\VendorNotificationService;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorSampleController extends Controller
{
    protected function getVendor()
    {
        return Auth::guard('vendor')->user();
    }

    public function index(Request $request)
    {
        $vendor = $this->getVendor();
        $status = $request->query('status', 'all');
        $search = $request->query('q');

        $query = $vendor->sampleRequests()->with(['product.primaryImage', 'customer'])->latest();

        if ($status !== 'all') {
            if ($status === 'pending') {
                $query->where('status', 'pending');
            } elseif ($status === 'approved') {
                $query->where('status', 'approved');
            } elseif ($status === 'dispatched') {
                $query->where('status', 'dispatched');
            } elseif ($status === 'delivered') {
                $query->where('status', 'delivered');
            } elseif ($status === 'rejected') {
                $query->where('status', 'rejected');
            } elseif ($status === 'converted') {
                $query->where('status', 'converted_to_order');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        $samples = $query->paginate(15);

        // Calculate KPI Metrics
        $totalRequests = $vendor->sampleRequests()->count();
        $pendingCount = $vendor->sampleRequests()->where('status', 'pending')->count();
        $approvedCount = $vendor->sampleRequests()->where('status', 'approved')->count();
        $dispatchedCount = $vendor->sampleRequests()->where('status', 'dispatched')->count();
        $deliveredCount = $vendor->sampleRequests()->where('status', 'delivered')->count();
        $rejectedCount = $vendor->sampleRequests()->where('status', 'rejected')->count();
        $convertedCount = $vendor->sampleRequests()->where('status', 'converted_to_order')->count();
        $sampleRevenue = $vendor->sampleRequests()->where('payment_status', 'paid')->sum('total_amount');

        $deliveredTotal = $deliveredCount + $convertedCount;
        $conversionRate = $deliveredTotal > 0 ? round(($convertedCount / $deliveredTotal) * 100, 1) : 0.0;

        $counts = [
            'all'         => $totalRequests,
            'pending'     => $pendingCount,
            'approved'    => $approvedCount,
            'dispatched'  => $dispatchedCount,
            'delivered'   => $deliveredCount,
            'rejected'    => $rejectedCount,
            'converted'   => $convertedCount,
        ];

        return view('vendor.samples.index', compact(
            'vendor', 'samples', 'counts', 'status', 'search',
            'totalRequests', 'pendingCount', 'dispatchedCount',
            'deliveredCount', 'convertedCount', 'conversionRate', 'sampleRevenue'
        ));
    }

    public function show($id)
    {
        $vendor = $this->getVendor();
        $sample = $vendor->sampleRequests()
            ->with(['product.primaryImage', 'product.category', 'customer', 'convertedOrder'])
            ->findOrFail($id);

        return view('vendor.samples.show', compact('vendor', 'sample'));
    }

    public function approve(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $sample = $vendor->sampleRequests()->findOrFail($id);

        if ($sample->status !== 'pending') {
            return back()->with('error', 'Only pending sample requests can be approved.');
        }

        $sample->update([
            'status'       => 'approved',
            'vendor_notes' => $request->input('vendor_notes', 'Sample preparation in progress.'),
        ]);

        return back()->with('success', "Sample Request #{$sample->request_number} approved! Please prepare material and enter dispatch tracking.");
    }

    public function dispatch(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $sample = $vendor->sampleRequests()->findOrFail($id);

        $validated = $request->validate([
            'courier_partner' => 'required|string|max:100',
            'tracking_number' => 'required|string|max:100',
            'vendor_notes'    => 'nullable|string|max:1000',
        ]);

        $sample->update([
            'status'          => 'dispatched',
            'courier_partner' => $validated['courier_partner'],
            'tracking_number' => $validated['tracking_number'],
            'vendor_notes'    => $validated['vendor_notes'] ?? $sample->vendor_notes,
            'dispatched_at'   => now(),
        ]);

        return back()->with('success', "Sample Request #{$sample->request_number} marked as DISPATCHED via {$validated['courier_partner']} (Tracking: {$validated['tracking_number']}).");
    }

    public function deliver(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $sample = $vendor->sampleRequests()->findOrFail($id);

        $sample->update([
            'status'       => 'delivered',
            'delivered_at' => now(),
            'vendor_notes' => $request->input('vendor_notes', $sample->vendor_notes),
        ]);

        return back()->with('success', "Sample Request #{$sample->request_number} marked as DELIVERED to buyer. Follow up for bulk purchase!");
    }

    public function reject(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $sample = $vendor->sampleRequests()->findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $sample->update([
            'status'           => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('warning', "Sample Request #{$sample->request_number} rejected.");
    }

    public function convertToQuote(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $sample = $vendor->sampleRequests()->with('product')->findOrFail($id);

        // Check or create quotation / enquiry thread
        $enquiryNumber = 'RFQ-SMP-' . $sample->id . '-' . rand(100, 999);

        $enquiry = Enquiry::create([
            'enquiry_number' => $enquiryNumber,
            'customer_id'    => $sample->customer_id,
            'vendor_id'      => $vendor->id,
            'product_id'     => $sample->product_id,
            'name'           => $sample->buyer_name,
            'email'          => $sample->buyer_email,
            'phone'          => $sample->buyer_phone,
            'company_name'   => $sample->company_name ?? 'B2B Client',
            'message'        => "Follow-up Wholesale Inquiry for {$sample->product->name} (Sample Reference: #{$sample->request_number}). Project: " . ($sample->project_type ?? 'Construction/Commercial'),
            'status'         => 'in_progress',
        ]);

        $initialMessage = "Hello {$sample->buyer_name}, thank you for requesting sample #{$sample->request_number} for {$sample->product->name}. We are pleased to offer you special wholesale pricing for your project requirement.";

        EnquiryReply::create([
            'enquiry_id'  => $enquiry->id,
            'sender_type' => 'vendor',
            'sender_id'   => $vendor->id,
            'message'     => $initialMessage,
        ]);

        return redirect()->route('vendor.messages.show', $enquiry->id)->with('success', "Commercial Wholesale RFQ created for sample lead {$sample->buyer_name}! You can now send formal pricing quotes.");
    }
}
