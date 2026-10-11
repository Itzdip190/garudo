<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SampleRequest;
use App\Services\VendorNotificationService;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FrontendSampleController extends Controller
{
    /**
     * Store a new sample request from the storefront product page
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'nullable|integer|min:1|max:10',
            'buyer_name'       => 'required|string|max:255',
            'buyer_email'      => 'required|email|max:255',
            'buyer_phone'      => 'required|string|max:30',
            'company_name'     => 'nullable|string|max:255',
            'delivery_address' => 'required|string|max:1000',
            'city'             => 'required|string|max:100',
            'state'            => 'required|string|max:100',
            'pincode'          => 'required|string|max:20',
            'project_type'     => 'nullable|string|max:150',
            'notes'            => 'nullable|string|max:1000',
            'payment_method'   => 'nullable|string|max:50',
        ]);

        $product = Product::with('vendor')->findOrFail($validated['product_id']);

        if (!$product->has_sample) {
            return back()->with('error', 'Sample ordering is not enabled for this product.');
        }

        $customer = Auth::guard('customer')->user();
        $customerId = $customer ? $customer->id : null;

        $maxQty = $product->sample_max_qty ?? 1;
        $qty = min($validated['quantity'] ?? 1, $maxQty);

        $sampleType = $product->sample_type ?? 'free';
        $samplePrice = ($sampleType === 'paid') ? floatval($product->sample_price) : 0.00;
        $sampleShipping = in_array($sampleType, ['paid', 'partial_paid']) ? floatval($product->sample_shipping_fee) : 0.00;
        $totalAmount = ($samplePrice * $qty) + $sampleShipping;

        $paymentStatus = ($sampleType === 'free') ? 'free' : 'paid'; // Free or Online Demo Payment
        $paymentMethod = $validated['payment_method'] ?? ($sampleType === 'free' ? 'free' : 'online_demo');

        // Generate unique request number
        $requestNumber = 'SMP-2026-' . rand(10000, 99999);
        while (SampleRequest::where('request_number', $requestNumber)->exists()) {
            $requestNumber = 'SMP-2026-' . rand(10000, 99999);
        }

        DB::beginTransaction();
        try {
            $sampleRequest = SampleRequest::create([
                'request_number'      => $requestNumber,
                'product_id'          => $product->id,
                'vendor_id'           => $product->vendor_id,
                'customer_id'         => $customerId,
                'sample_type'         => $sampleType,
                'quantity'            => $qty,
                'sample_price'        => $samplePrice,
                'sample_shipping_fee' => $sampleShipping,
                'total_amount'        => $totalAmount,
                'payment_status'      => $paymentStatus,
                'payment_method'      => $paymentMethod,
                'transaction_id'      => ($sampleType !== 'free') ? 'TXN-SMP-' . strtoupper(Str::random(10)) : null,
                'status'              => 'pending',
                'buyer_name'          => $validated['buyer_name'],
                'buyer_email'         => $validated['buyer_email'],
                'buyer_phone'         => $validated['buyer_phone'],
                'company_name'        => $validated['company_name'] ?? null,
                'delivery_address'    => $validated['delivery_address'],
                'city'                => $validated['city'],
                'state'               => $validated['state'],
                'pincode'             => $validated['pincode'],
                'project_type'        => $validated['project_type'] ?? null,
                'notes'               => $validated['notes'] ?? null,
            ]);

            DB::commit();

            // Send real-time notifications to Vendor & Admin
            VendorNotificationService::sendSampleRequestNotification($sampleRequest);
            AdminNotificationService::sendSampleRequestNotification($sampleRequest);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'        => true,
                    'request_number' => $requestNumber,
                    'message'        => "Sample request submitted successfully! Your tracking code is {$requestNumber}.",
                    'redirect'       => $customerId ? route('customer.samples.show', $sampleRequest->id) : null,
                ]);
            }

            if ($customerId) {
                return redirect()->route('customer.samples.show', $sampleRequest->id)
                    ->with('success', "Your sample request #{$requestNumber} has been received! The supplier will prepare and dispatch your material sample.");
            }

            return back()->with('sample_success', "Your sample request #{$requestNumber} has been successfully submitted to {$product->vendor->company_name}. You will receive SMS & email dispatch updates.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Unable to submit sample request: ' . $e->getMessage());
        }
    }
}
