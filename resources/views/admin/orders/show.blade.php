@extends('layouts.admin')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm rounded-pill px-3 mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Orders List
    </a>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="pill-status pill-{{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span>
                <span class="pill-status pill-{{ strtolower($order->payment_status) }}">Payment: {{ ucfirst($order->payment_status) }}</span>
                <span class="text-muted small">• Placed {{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <h1 class="page-header-title">Order #{{ $order->order_number }}</h1>
        </div>

        <div class="d-flex gap-2">
            <!-- Update Order Status Dropdown Form -->
            <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                @csrf
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>

            <!-- Update Payment Status Dropdown Form -->
            <form action="{{ route('admin.orders.update_payment', $order->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                @csrf
                <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Payment Pending</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Payment Paid</option>
                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Payment Failed</option>
                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Payment Refunded</option>
                </select>
            </form>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Shipping / Buyer Details -->
    <div class="col-md-6">
        <div class="garudo-card p-4 h-100">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Shipping & Consignee Info</h6>
            <div class="fw-bold fs-6">{{ $order->shipping_name ?? $order->customer->company_name ?? 'Buyer' }}</div>
            <div class="text-muted small mt-1">{{ $order->shipping_address }}</div>
            <div class="text-muted small">{{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}</div>
            <div class="fw-semibold text-dark mt-2"><i class="bi bi-telephone me-1 text-muted"></i>{{ $order->shipping_phone ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Billing & Tax Details -->
    <div class="col-md-6">
        <div class="garudo-card p-4 h-100">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Billing & GST Compliance</h6>
            <div class="fw-bold fs-6">{{ $order->billing_name ?? $order->shipping_name ?? 'Buyer' }}</div>
            <div class="text-muted small mt-1">{{ $order->billing_address ?? $order->shipping_address }}</div>
            <div class="mt-2">
                <span class="text-muted small">GSTIN:</span> 
                <code>{{ $order->billing_gstin ?? 'Not Provided' }}</code>
            </div>
            <div class="mt-1">
                <span class="text-muted small">Payment Method:</span>
                <span class="badge bg-light text-dark border">{{ strtoupper($order->payment_method ?? 'Online') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Order Items Table -->
<div class="garudo-card p-0 overflow-hidden mb-4">
    <div class="p-3 bg-light border-bottom fw-bold text-dark">
        Ordered Products & Line Items
    </div>
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Item & Specs</th>
                    <th>Seller / Vendor</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>GST Amount</th>
                    <th class="text-end">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->product_name ?? $item->product->name ?? 'Product' }}</div>
                            <div class="text-muted small">SKU: <code>{{ $item->product_sku ?? 'N/A' }}</code></div>
                            @if($item->is_custom_order && $item->customization_details)
                                <div class="text-primary small mt-1"><i class="bi bi-gear-fill me-1"></i>{{ $item->customization_details }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->vendor->company_name ?? 'Vendor' }}</div>
                        </td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="fw-bold">{{ $item->quantity }}</td>
                        <td class="text-muted">₹{{ number_format($item->gst_amount, 2) }}</td>
                        <td class="text-end fw-bold text-dark">₹{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Totals Summary -->
    <div class="p-4 bg-light border-top">
        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-semibold">₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax & GST:</span>
                    <span class="fw-semibold">₹{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Logistics & Shipping:</span>
                    <span class="fw-semibold">₹{{ number_format($order->shipping_amount, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5 fw-bold text-dark">
                    <span>Grand Total:</span>
                    <span class="text-success">₹{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
