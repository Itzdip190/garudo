<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Quotation;
use App\Services\VendorNotificationService;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrontendQuotationController extends Controller
{
    /**
     * Store a new B2B Quotation Request (RFQ) from prospective buyer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'             => 'required|exists:products,id',
            'quantity'               => 'required|integer|min:1',
            'unit'                   => 'nullable|string|max:50',
            'selected_variant'       => 'nullable|string|max:255',
            'custom_variations'      => 'nullable|array',
            'target_price'           => 'nullable|numeric|min:0',
            'urgency_level'          => 'nullable|string|max:100',
            'expected_delivery_date' => 'nullable|date|after_or_equal:today',
            'buyer_name'             => 'required|string|max:200',
            'buyer_email'            => 'required|email|max:200',
            'buyer_phone'            => 'required|string|max:50',
            'company_name'           => 'nullable|string|max:300',
            'gstin'                  => 'nullable|string|max:50',
            'delivery_address'       => 'nullable|string|max:1000',
            'delivery_city'          => 'nullable|string|max:100',
            'delivery_state'         => 'nullable|string|max:100',
            'delivery_pincode'       => 'nullable|string|max:20',
            'project_type'           => 'nullable|string|max:150',
            'buyer_notes'            => 'nullable|string|max:3000',
            'attachment'             => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,dwg,jpg,jpeg,png,webp|max:10240',
        ]);

        $product = Product::with('vendor')->findOrFail($validated['product_id']);
        $customer = Auth::guard('customer')->user();

        // Handle drawing / BOQ attachment upload
        $attachmentPath = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file = $request->file('attachment');
            $filename = 'rfq_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $attachmentPath = $file->storeAs('quotations/attachments', $filename, 'public');
        }

        // Generate unique Quotation Number (e.g. RFQ-2026-89421)
        $year = date('Y');
        $random = mt_rand(10000, 99999);
        $quoteNumber = "RFQ-{$year}-{$random}";
        while (Quotation::where('quotation_number', $quoteNumber)->exists()) {
            $random = mt_rand(10000, 99999);
            $quoteNumber = "RFQ-{$year}-{$random}";
        }

        // Create Quotation Record
        $quotation = Quotation::create([
            'quotation_number'       => $quoteNumber,
            'vendor_id'              => $product->vendor_id,
            'product_id'             => $product->id,
            'customer_id'            => $customer ? $customer->id : null,
            'buyer_name'             => $validated['buyer_name'],
            'buyer_email'            => $validated['buyer_email'],
            'buyer_phone'            => $validated['buyer_phone'],
            'company_name'           => $validated['company_name'] ?? null,
            'gstin'                  => $validated['gstin'] ?? null,
            'delivery_address'       => $validated['delivery_address'] ?? null,
            'delivery_city'          => $validated['delivery_city'] ?? null,
            'delivery_state'         => $validated['delivery_state'] ?? null,
            'delivery_pincode'       => $validated['delivery_pincode'] ?? null,
            'project_type'           => $validated['project_type'] ?? null,
            'quantity'               => $validated['quantity'],
            'unit'                   => $validated['unit'] ?? ($product->unit ?? 'Units'),
            'selected_variant'       => $validated['selected_variant'] ?? null,
            'custom_variations'      => $validated['custom_variations'] ?? null,
            'target_price'           => $validated['target_price'] ?? null,
            'urgency_level'          => $validated['urgency_level'] ?? 'Standard (1-2 Weeks)',
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
            'buyer_notes'            => $validated['buyer_notes'] ?? null,
            'attachment_path'        => $attachmentPath,
            'status'                 => 'pending',
            'validity_days'          => 15,
        ]);

        // Send Real-Time Notifications to Vendor AND Admin
        VendorNotificationService::sendQuotationRequestNotification($quotation);
        AdminNotificationService::sendQuotationRequestNotification($quotation);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'          => true,
                'quotation_number' => $quotation->quotation_number,
                'message'          => "Quotation Request #{$quotation->quotation_number} submitted successfully! The verified vendor and Garudo Trade Desk have been notified.",
            ]);
        }

        return back()->with('quotation_success', "Quotation Request #{$quotation->quotation_number} submitted successfully! Vendor {$product->vendor->company_name} and Garudo Trade Desk have received your requirement.");
    }
}
