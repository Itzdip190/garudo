<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SampleRequest;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSampleController extends Controller
{
    /**
     * Super Admin: Master Sample Requests Monitor
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $vendorId = $request->query('vendor_id');
        $sampleType = $request->query('sample_type');
        $search = $request->query('q');

        $query = SampleRequest::with(['vendor', 'product.primaryImage', 'customer', 'convertedOrder'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        if ($sampleType) {
            $query->where('sample_type', $sampleType);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('company_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        $samples = $query->paginate(20);
        $vendors = Vendor::where('approval_status', 'approved')->orderBy('company_name')->get();

        // Platform-wide KPI Metrics
        $totalRequests = SampleRequest::count();
        $pendingCount = SampleRequest::where('status', 'pending')->count();
        $dispatchedCount = SampleRequest::where('status', 'dispatched')->count();
        $deliveredCount = SampleRequest::where('status', 'delivered')->count();
        $convertedCount = SampleRequest::where('status', 'converted_to_order')->count();
        $sampleRevenue = SampleRequest::where('payment_status', 'paid')->sum('total_amount');

        $deliveredTotal = $deliveredCount + $convertedCount;
        $conversionRate = $deliveredTotal > 0 ? round(($convertedCount / $deliveredTotal) * 100, 1) : 0.0;

        $counts = [
            'all'         => $totalRequests,
            'pending'     => $pendingCount,
            'approved'    => SampleRequest::where('status', 'approved')->count(),
            'dispatched'  => $dispatchedCount,
            'delivered'   => $deliveredCount,
            'converted'   => $convertedCount,
            'rejected'    => SampleRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.samples.index', compact(
            'samples', 'vendors', 'counts', 'status', 'vendorId', 'sampleType', 'search',
            'totalRequests', 'pendingCount', 'dispatchedCount', 'deliveredCount',
            'convertedCount', 'conversionRate', 'sampleRevenue'
        ));
    }

    /**
     * Super Admin: View Full Sample Request & Customer Details
     */
    public function show($id)
    {
        $sample = SampleRequest::with(['vendor', 'product.primaryImage', 'product.category', 'customer', 'convertedOrder'])
            ->findOrFail($id);

        return view('admin.samples.show', compact('sample'));
    }

    /**
     * Super Admin: Update Sample Request Status / Administrative Override
     */
    public function updateStatus(Request $request, $id)
    {
        $sample = SampleRequest::findOrFail($id);

        $validated = $request->validate([
            'status'          => 'required|in:pending,approved,dispatched,delivered,rejected,converted_to_order',
            'courier_partner' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'vendor_notes'    => 'nullable|string|max:1000',
            'rejection_reason'=> 'nullable|string|max:500',
        ]);

        $updateData = [
            'status'          => $validated['status'],
            'courier_partner' => $validated['courier_partner'] ?? $sample->courier_partner,
            'tracking_number' => $validated['tracking_number'] ?? $sample->tracking_number,
            'vendor_notes'    => $validated['vendor_notes'] ?? $sample->vendor_notes,
            'rejection_reason'=> ($validated['status'] === 'rejected') ? ($validated['rejection_reason'] ?? 'Rejected by Administrator') : null,
        ];

        if ($validated['status'] === 'dispatched' && !$sample->dispatched_at) {
            $updateData['dispatched_at'] = now();
        } elseif ($validated['status'] === 'delivered' && !$sample->delivered_at) {
            $updateData['delivered_at'] = now();
        }

        $sample->update($updateData);

        return back()->with('success', "Sample Request #{$sample->request_number} updated to " . strtoupper(str_replace('_', ' ', $validated['status'])));
    }

    /**
     * Super Admin: Vendor-Wise Sample Performance & Conversion Analytics Scorecard
     */
    public function vendorAnalytics(Request $request)
    {
        $search = $request->query('q');
        $sortBy = $request->query('sort', 'requests_desc');

        $vendorsQuery = Vendor::withCount([
            'sampleRequests as total_samples_received',
            'sampleRequests as pending_samples' => function ($q) {
                $q->where('status', 'pending');
            },
            'sampleRequests as dispatched_samples' => function ($q) {
                $q->where('status', 'dispatched');
            },
            'sampleRequests as delivered_samples' => function ($q) {
                $q->where('status', 'delivered');
            },
            'sampleRequests as converted_samples' => function ($q) {
                $q->where('status', 'converted_to_order');
            },
            'sampleRequests as rejected_samples' => function ($q) {
                $q->where('status', 'rejected');
            },
            'products as sample_enabled_products' => function ($q) {
                $q->where('has_sample', true);
            },
        ]);

        if ($search) {
            $vendorsQuery->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $vendors = $vendorsQuery->get()->map(function ($vendor) {
            $deliveredTotal = $vendor->delivered_samples + $vendor->converted_samples;
            $vendor->conversion_rate = $deliveredTotal > 0 ? round(($vendor->converted_samples / $deliveredTotal) * 100, 1) : 0.0;
            $vendor->paid_sample_revenue = $vendor->sampleRequests()->where('payment_status', 'paid')->sum('total_amount');
            return $vendor;
        });

        if ($sortBy === 'requests_desc') {
            $vendors = $vendors->sortByDesc('total_samples_received');
        } elseif ($sortBy === 'conversion_desc') {
            $vendors = $vendors->sortByDesc('conversion_rate');
        } elseif ($sortBy === 'pending_desc') {
            $vendors = $vendors->sortByDesc('pending_samples');
        } elseif ($sortBy === 'revenue_desc') {
            $vendors = $vendors->sortByDesc('paid_sample_revenue');
        }

        // Global Totals for Summary Header
        $totalVendorsOfferingSamples = Vendor::whereHas('products', function($q) { $q->where('has_sample', true); })->count();
        $totalMarketplaceSampleRequests = SampleRequest::count();
        $totalMarketplaceConversions = SampleRequest::where('status', 'converted_to_order')->count();
        $globalDelivered = SampleRequest::whereIn('status', ['delivered', 'converted_to_order'])->count();
        $globalConversionRate = $globalDelivered > 0 ? round(($totalMarketplaceConversions / $globalDelivered) * 100, 1) : 0.0;

        return view('admin.samples.vendor_analytics', compact(
            'vendors', 'search', 'sortBy', 'totalVendorsOfferingSamples',
            'totalMarketplaceSampleRequests', 'totalMarketplaceConversions', 'globalConversionRate'
        ));
    }
}
