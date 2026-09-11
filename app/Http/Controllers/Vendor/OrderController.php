<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\DeliveryShipment;
use App\Services\DeliveryQuoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected DeliveryQuoteService $quoteService;

    public function __construct(DeliveryQuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    protected function getVendor()
    {
        return Auth::guard('vendor')->user();
    }

    public function index(Request $request)
    {
        $vendor = $this->getVendor();
        $status = $request->query('status', 'all');
        $search = $request->query('q');

        $query = $vendor->orderItems()->with(['order.customer', 'product.primaryImage', 'shipment'])->latest();

        if ($status === 'pending') {
            $query->whereIn('item_status', ['pending', 'processing']);
        } elseif ($status === 'shipped') {
            $query->where('item_status', 'shipped');
        } elseif ($status === 'delivered') {
            $query->where('item_status', 'delivered');
        } elseif ($status === 'cancelled') {
            $query->where('item_status', 'cancelled');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function ($sub) use ($search) {
                    $sub->where('order_number', 'like', "%{$search}%");
                })->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(15);

        $counts = [
            'all' => $vendor->orderItems()->count(),
            'pending' => $vendor->orderItems()->whereIn('item_status', ['pending', 'processing'])->count(),
            'shipped' => $vendor->orderItems()->where('item_status', 'shipped')->count(),
            'delivered' => $vendor->orderItems()->where('item_status', 'delivered')->count(),
            'cancelled' => $vendor->orderItems()->where('item_status', 'cancelled')->count(),
        ];

        return view('vendor.orders.index', compact('vendor', 'items', 'counts', 'status', 'search'));
    }

    public function show($id)
    {
        $vendor = $this->getVendor();
        $item = $vendor->orderItems()->with([
            'order.customer',
            'product',
            'shipment.quotes.partner',
            'shipment.deliveryPartner',
            'shipment.dispatchWarehouse',
        ])->findOrFail($id);

        $shipment = $item->shipment;
        $quotes = $shipment ? $shipment->quotes()->with('partner')->orderByDesc('smart_score')->get() : collect();
        $warehouses = $vendor->warehouses()->active()->get();

        return view('vendor.orders.show', compact('vendor', 'item', 'shipment', 'quotes', 'warehouses'));
    }

    /**
     * Update Production Lead Time & Manufacturing Readiness for Special / Custom Orders
     */
    public function updateProductionLeadTime(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $item = $vendor->orderItems()->findOrFail($id);

        $validated = $request->validate([
            'is_custom_order' => 'nullable|boolean',
            'production_lead_days' => 'required|integer|min:0|max:180',
            'production_stage' => 'required|in:order_accepted,material_procurement,in_production,quality_testing,packing_completed,ready_for_dispatch',
            'production_notes' => 'nullable|string|max:1000',
        ]);

        $leadDays = (int) $validated['production_lead_days'];
        $estimatedDate = now()->addDays($leadDays)->toDateString();

        $item->update([
            'is_custom_order' => $request->boolean('is_custom_order', true),
            'production_lead_days' => $leadDays,
            'estimated_ready_date' => $estimatedDate,
            'production_stage' => $validated['production_stage'],
            'production_notes' => $validated['production_notes'] ?? null,
            'item_status' => 'processing',
        ]);

        return back()->with('success', "Production schedule updated! Customer will see estimated ready date as {$estimatedDate} (Vendor identity remains private).");
    }

    /**
     * Trigger Delivery System / Generate Logistics Quotes
     */
    public function triggerDelivery(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $item = $vendor->orderItems()->findOrFail($id);

        $validated = $request->validate([
            'package_weight_kg' => 'nullable|numeric|min:0.1',
            'package_dimensions' => 'nullable|string|max:100',
            'package_boxes_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $shipment = $this->quoteService->generateQuotesForOrderItem($item, [
            'weight_kg' => $validated['package_weight_kg'] ?? ($item->quantity * 5.0),
            'dimensions' => $validated['package_dimensions'] ?? '45x35x25 cm',
            'boxes_count' => $validated['package_boxes_count'] ?? 1,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Delivery system triggered! Multi-agency competitive quotes analyzed and generated successfully.');
    }

    /**
     * Confirm a Smart / Selected Agency Quote
     */
    public function confirmDeliveryQuote(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $item = $vendor->orderItems()->with('shipment')->findOrFail($id);

        if (!$item->shipment) {
            return back()->with('error', 'No active delivery shipment found. Please trigger delivery quotes first.');
        }

        $request->validate([
            'quote_id' => 'required|integer|exists:delivery_quotes,id',
            'dispatch_warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        $dispatchWhId = $request->dispatch_warehouse_id 
            ?: ($item->product->warehouse_id ?: $vendor->primary_dispatch_warehouse_id);

        if ($dispatchWhId) {
            $item->shipment->update(['dispatch_warehouse_id' => $dispatchWhId]);
        }

        $this->quoteService->confirmQuote($item->shipment, (int) $request->quote_id);

        \App\Services\AdminNotificationService::send(
            'logistics',
            'Order Delivery Scheduled: Item #' . $item->id,
            "Vendor '{$vendor->company_name}' scheduled delivery for Order #{$item->order->order_number}",
            route('admin.orders.show', $item->order_id),
            'bi-truck text-info'
        );

        return back()->with('success', 'Delivery partner confirmed! Pickup is scheduled and 6-Digit Handover OTP generated.');
    }

    /**
     * Vendor Self-Handled Transport or Drop-off at Hub Crossing
     */
    public function selfHandledDelivery(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $item = $vendor->orderItems()->with('shipment')->findOrFail($id);

        if (!$item->shipment) {
            $item->shipment = $this->quoteService->generateQuotesForOrderItem($item);
        }

        $validated = $request->validate([
            'delivery_type' => 'required|in:vendor_self_transport,transporter_hub_crossing',
            'dispatch_warehouse_id' => 'nullable|exists:warehouses,id',
            'docket_lr_number' => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:150',
            'driver_phone' => 'nullable|string|max:20',
            'transporter_hub_name' => 'nullable|string|max:250',
            'transporter_hub_crossing_address' => 'nullable|string|max:500',
            'estimated_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $dispatchWhId = $request->dispatch_warehouse_id 
            ?: ($item->product->warehouse_id ?: $vendor->primary_dispatch_warehouse_id);

        if ($dispatchWhId) {
            $item->shipment->update(['dispatch_warehouse_id' => $dispatchWhId]);
        }

        $this->quoteService->setSelfHandledTransport($item->shipment, $validated);

        \App\Services\AdminNotificationService::send(
            'logistics',
            'Order Dispatched: #' . $item->order->order_number,
            "Vendor '{$vendor->company_name}' dispatched consignment with Docket/LR: {$validated['docket_lr_number']}",
            route('admin.orders.show', $item->order_id),
            'bi-box-arrow-right text-primary'
        );

        return back()->with('success', 'Self-handled / Transporter Hub Crossing details saved successfully! Ready for handover verification.');
    }

    /**
     * Verify Handover OTP when goods are collected by transporter
     */
    public function verifyPickupOtp(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $item = $vendor->orderItems()->with('shipment')->findOrFail($id);

        if (!$item->shipment) {
            return back()->with('error', 'Shipment record not found.');
        }

        $request->validate([
            'pickup_otp' => 'required|string|size:6',
        ]);

        $result = $this->quoteService->verifyHandoverOtp($item->shipment, $request->pickup_otp);

        if ($result['success']) {
            \App\Services\AdminNotificationService::send(
                'logistics',
                'Consignment Handed Over: OTP Verified',
                "Order #{$item->order->order_number} goods verified and handed to carrier.",
                route('admin.orders.show', $item->order_id),
                'bi-check2-circle text-success'
            );

            return back()->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Print 3-Way Triplicate Tax Invoice & E-Way Bill (Transporter, Vendor, Client copies)
     */
    public function printInvoice(Request $request, $id)
    {
        $vendor = $this->getVendor();
        $item = $vendor->orderItems()->with([
            'order.customer',
            'product',
            'shipment.deliveryPartner'
        ])->findOrFail($id);

        $shipment = $item->shipment;

        return view('vendor.orders.invoice', compact('vendor', 'item', 'shipment'));
    }

    public function returns(Request $request)
    {
        $vendor = $this->getVendor();
        $returns = $vendor->orderItems()
            ->with(['order.customer', 'product'])
            ->whereIn('item_status', ['returned', 'cancelled'])
            ->latest()
            ->paginate(15);

        return view('vendor.orders.returns', compact('vendor', 'returns'));
    }

    public function bulkOrders(Request $request)
    {
        $vendor = $this->getVendor();
        $bulkInquiries = $vendor->enquiries()->with(['customer', 'product'])->latest()->paginate(15);

        return view('vendor.orders.bulk', compact('vendor', 'bulkInquiries'));
    }
}
