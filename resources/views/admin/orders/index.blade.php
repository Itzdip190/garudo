@extends('layouts.admin')

@section('title', 'B2B Purchase Orders')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">B2B Purchase Orders</h1>
        <p class="page-header-subtitle mb-0">Track commercial order fulfillment, invoice generation & dispatch logistics across India.</p>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ !request('status') ? 'border-primary' : '' }}">
                <div class="text-muted small fw-bold text-uppercase">Total Orders</div>
                <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('status') === 'processing' ? 'border-warning' : '' }}">
                <div class="text-warning small fw-bold text-uppercase">In Production / Processing</div>
                <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['processing'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('status') === 'shipped' ? 'border-info' : '' }}">
                <div class="text-info small fw-bold text-uppercase">In Transit / Shipped</div>
                <div class="fs-4 fw-bold text-info mt-1">{{ $stats['shipped'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('status') === 'delivered' ? 'border-success' : '' }}">
                <div class="text-success small fw-bold text-uppercase">Delivered & Settled</div>
                <div class="fs-4 fw-bold text-success mt-1">{{ $stats['delivered'] }}</div>
            </div>
        </a>
    </div>
</div>

<!-- ---------------- FILTERS & SEARCH ---------------- -->
<div class="garudo-card p-3 mb-4">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Order #, Buyer, Phone..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Fulfillment Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="payment_status" class="form-select">
                <option value="">All Payments</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Payment Pending</option>
                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-garudo-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
    </form>
</div>

<!-- ---------------- ORDERS TABLE ---------------- -->
<div class="garudo-card p-0 overflow-hidden">
    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Buyer / Consignee</th>
                        <th>Created At</th>
                        <th>Grand Total</th>
                        <th>Payment Status</th>
                        <th>Fulfillment</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->shipping_name ?? $order->customer->company_name ?? 'Buyer' }}</div>
                                <div class="text-muted small">{{ $order->shipping_city ?? 'India' }}, {{ $order->shipping_state ?? '' }}</div>
                            </td>
                            <td class="text-muted small">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td class="fw-bold text-dark">₹{{ number_format($order->grand_total, 2) }}</td>
                            <td>
                                <span class="pill-status pill-{{ strtolower($order->payment_status) }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="pill-status pill-{{ strtolower($order->status) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-light btn-sm rounded-pill px-3">
                                    <i class="bi bi-eye text-primary me-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-receipt fs-1 mb-2 d-block"></i>
            <h5>No Orders Found</h5>
            <p class="small">No orders matched your filter parameters.</p>
        </div>
    @endif
</div>
@endsection
