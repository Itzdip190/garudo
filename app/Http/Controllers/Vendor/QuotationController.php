<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    protected function getVendor()
    {
        return Auth::guard('vendor')->user();
    }

    /**
     * List all Quotation Requests (RFQs) received by this vendor
     */
    public function index(Request $request)
    {
        $vendor = $this->getVendor();
        $status = $request->query('status', 'all');
        $search = $request->query('q');
        $productId = $request->query('product_id');

        $query = $vendor->quotations()->with(['customer', 'product.primaryImage', 'convertedOrder'])->latest();

        if ($status === 'pending') {
            $query->whereIn('status', ['pending', 'new']);
        } elseif ($status === 'quoted') {
            $query->whereIn('status', ['sent', 'quoted', 'viewed']);
        } elseif ($status === 'accepted') {
            $query->where('status', 'accepted');
        } elseif ($status === 'converted') {
            $query->where('status', 'converted_to_order');
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        }

        if ($productId) {
            $query->where('product_id', $productId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        $quotations = $query->paginate(15);
        $products = $vendor->products()->where('allow_quotation', true)->orderBy('name')->get();

        // Vendor KPI Metrics
        $totalQuotations = $vendor->quotations()->count();
        $pendingCount = $vendor->quotations()->whereIn('status', ['pending', 'new'])->count();
        $quotedCount = $vendor->quotations()->whereIn('status', ['sent', 'quoted', 'viewed'])->count();
        $acceptedCount = $vendor->quotations()->where('status', 'accepted')->count();
        $convertedCount = $vendor->quotations()->where('status', 'converted_to_order')->count();
        $totalQuotedValue = $vendor->quotations()->whereIn('status', ['sent', 'quoted', 'viewed', 'accepted', 'converted_to_order'])->sum('total_amount');

        $counts = [
            'all'       => $totalQuotations,
            'pending'   => $pendingCount,
            'quoted'    => $quotedCount,
            'accepted'  => $acceptedCount,
            'converted' => $convertedCount,
            'rejected'  => $vendor->quotations()->where('status', 'rejected')->count(),
        ];

        return view('vendor.quotations.index', compact(
            'vendor', 'quotations', 'products', 'counts', 'status', 'search', 'productId',
            'totalQuotations', 'pendingCount', 'quotedCount', 'acceptedCount', 'convertedCount', 'totalQuotedValue'
        ));
    }

    /**
     * Show Quotation Details & Price Proposal Workbench
     */
    public function show($id)
    {
        $vendor = $this->getVendor();
        $quotation = $vendor->quotations()->with(['customer', 'product.primaryImage', 'product.variants', 'convertedOrder'])->findOrFail($id);

        return view('vendor.quotations.show', compact('vendor', 'quotation'));
    }

    /**
     * Send Official Price Proposal / Quotation to Buyer
     */
    public function sendProposal(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $quotation = $vendor->quotations()->findOrFail($id);

        $validated = $request->validate([
            'unit_price'            => 'required|numeric|min:0.01',
            'gst_rate'              => 'required|numeric|min:0|max:28',
            'shipping_amount'       => 'nullable|numeric|min:0',
            'discount_amount'       => 'nullable|numeric|min:0',
            'payment_terms'         => 'nullable|string|max:500',
            'delivery_terms'        => 'nullable|string|max:500',
            'validity_days'         => 'required|integer|min:1|max:180',
            'vendor_proposal_notes' => 'nullable|string|max:2000',
            'vendor_attachment'     => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        $unitPrice = floatval($validated['unit_price']);
        $qty = $quotation->quantity;
        $subtotal = $unitPrice * $qty;
        $discount = floatval($validated['discount_amount'] ?? 0);
        $taxable = max(0, $subtotal - $discount);
        $gstRate = floatval($validated['gst_rate']);
        $gstAmount = round(($taxable * $gstRate) / 100, 2);
        $shipping = floatval($validated['shipping_amount'] ?? 0);
        $totalAmount = $taxable + $gstAmount + $shipping;

        $validityDays = intval($validated['validity_days']);
        $validUntil = now()->addDays($validityDays)->toDateString();

        $vendorAttachmentPath = $quotation->vendor_attachment_path;
        if ($request->hasFile('vendor_attachment') && $request->file('vendor_attachment')->isValid()) {
            $file = $request->file('vendor_attachment');
            $filename = 'quote_doc_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $vendorAttachmentPath = $file->storeAs('quotations/vendor_proposals', $filename, 'public');
        }

        $quotation->update([
            'unit_price'             => $unitPrice,
            'gst_rate'               => $gstRate,
            'gst_amount'             => $gstAmount,
            'shipping_amount'        => $shipping,
            'discount_amount'        => $discount,
            'total_amount'           => $totalAmount,
            'payment_terms'          => $validated['payment_terms'] ?? '100% Against Proforma Invoice / Dispatch',
            'delivery_terms'         => $validated['delivery_terms'] ?? 'F.O.R. Site Delivery',
            'validity_days'          => $validityDays,
            'valid_until'            => $validUntil,
            'vendor_proposal_notes'  => $validated['vendor_proposal_notes'],
            'vendor_attachment_path' => $vendorAttachmentPath,
            'status'                 => 'sent',
            'quoted_at'              => now(),
        ]);

        // Send Alert to Super Admin
        AdminNotificationService::sendQuotationQuotedNotification($quotation);

        return redirect()->route('vendor.quotations.show', $quotation->id)->with('success', "Price Proposal for Quotation #{$quotation->quotation_number} (Total ₹" . number_format($totalAmount, 2) . ") sent successfully to {$quotation->buyer_name}!");
    }

    /**
     * Decline / Reject Quotation Request
     */
    public function reject(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $quotation = $vendor->quotations()->findOrFail($id);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $quotation->update([
            'status'           => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('vendor.quotations.show', $quotation->id)->with('info', "Quotation #{$quotation->quotation_number} has been marked as Rejected.");
    }

    /**
     * Convert Accepted Quotation into a B2B Purchase Order
     */
    public function convertToOrder(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $quotation = $vendor->quotations()->with(['product', 'customer'])->findOrFail($id);

        if ($quotation->converted_order_id) {
            return redirect()->route('vendor.orders.show', $quotation->converted_order_id)->with('info', 'This quotation has already been converted to an order.');
        }

        DB::beginTransaction();
        try {
            $year = date('Y');
            $random = mt_rand(10000, 99999);
            $orderNumber = "ORD-{$year}-{$random}";

            $order = Order::create([
                'order_number'     => $orderNumber,
                'customer_id'      => $quotation->customer_id,
                'subtotal'         => max(0, ($quotation->unit_price * $quotation->quantity) - $quotation->discount_amount),
                'tax_amount'       => $quotation->gst_amount,
                'shipping_amount'  => $quotation->shipping_amount,
                'discount_amount'  => $quotation->discount_amount,
                'grand_total'      => $quotation->total_amount,
                'payment_status'   => 'pending',
                'payment_method'   => 'b2b_proforma_invoice',
                'status'           => 'confirmed',
                'shipping_name'    => $quotation->buyer_name,
                'shipping_phone'   => $quotation->buyer_phone,
                'shipping_email'   => $quotation->buyer_email,
                'shipping_address' => $quotation->delivery_address ?? 'Direct Site Delivery',
                'shipping_city'    => $quotation->delivery_city ?? 'Mumbai',
                'shipping_state'   => $quotation->delivery_state ?? 'Maharashtra',
                'shipping_pincode' => $quotation->delivery_pincode ?? '400001',
                'notes'            => "Converted from Quotation #{$quotation->quotation_number}. " . ($quotation->vendor_proposal_notes ?? ''),
            ]);

            OrderItem::create([
                'order_id'       => $order->id,
                'vendor_id'      => $vendor->id,
                'product_id'     => $quotation->product_id,
                'product_name'   => $quotation->product ? $quotation->product->name : 'Material',
                'product_sku'    => $quotation->product ? $quotation->product->sku : 'SKU-CUSTOM',
                'quantity'       => $quotation->quantity,
                'unit_price'     => $quotation->unit_price,
                'tax_rate'       => $quotation->gst_rate,
                'tax_amount'     => $quotation->gst_amount,
                'total_price'    => $quotation->total_amount,
                'status'         => 'confirmed',
            ]);

            $quotation->update([
                'status'             => 'converted_to_order',
                'converted_order_id' => $order->id,
            ]);

            DB::commit();

            return redirect()->route('vendor.orders.show', $order->id)->with('success', "Quotation #{$quotation->quotation_number} converted into live B2B Order #{$order->order_number} successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to convert quotation: ' . $e->getMessage());
        }
    }
}
