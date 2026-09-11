@extends('layouts.vendor')

@section('title', 'Orders Management')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Customer Orders</h4>
        <p class="text-muted mb-0 small">Process incoming orders, dispatch shipments, and print invoice manifests.</p>
    </div>
</div>

<!-- Filters -->
<div class="card-custom mb-3">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('vendor.orders.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-dark' : 'btn-light border' }}" style="border-radius: 20px;">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('vendor.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning' : 'btn-light border' }}" style="border-radius: 20px;">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('vendor.orders.index', ['status' => 'shipped']) }}" class="btn btn-sm {{ $status === 'shipped' ? 'btn-primary' : 'btn-light border' }}" style="border-radius: 20px;">
                Shipped ({{ $counts['shipped'] }})
            </a>
            <a href="{{ route('vendor.orders.index', ['status' => 'delivered']) }}" class="btn btn-sm {{ $status === 'delivered' ? 'btn-success' : 'btn-light border' }}" style="border-radius: 20px;">
                Delivered ({{ $counts['delivered'] }})
            </a>
            <a href="{{ route('vendor.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ $status === 'cancelled' ? 'btn-danger' : 'btn-light border' }}" style="border-radius: 20px;">
                Cancelled ({{ $counts['cancelled'] }})
            </a>
        </div>
        <form method="GET" action="{{ route('vendor.orders.index') }}" class="d-flex gap-2" style="max-width: 320px;">
            <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Order # or Product..." style="border-radius: 8px;">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">ORDER ID</th>
                    <th class="text-muted small">DATE</th>
                    <th class="text-muted small">CUSTOMER / BUYER</th>
                    <th class="text-muted small">PRODUCT</th>
                    <th class="text-muted small">QTY</th>
                    <th class="text-muted small">AMOUNT</th>
                    <th class="text-muted small">STATUS</th>
                    <th class="pe-3 text-end text-muted small">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">
                            #{{ $item->order->order_number ?? 'ORD-' . $item->order_id }}
                        </td>
                        <td class="small text-muted">{{ $item->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->order->customer->name ?? $item->order->shipping_name ?? 'Buyer' }}</div>
                            <div class="small text-muted">{{ $item->order->shipping_city ?? 'India' }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-truncate" style="max-width: 200px;">{{ $item->product_name }}</div>
                            <div class="small text-muted">SKU: {{ $item->product_sku }}</div>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td class="fw-bold text-dark">₹{{ number_format($item->total_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->item_status === 'delivered' ? 'success' : ($item->item_status === 'shipped' ? 'primary' : ($item->item_status === 'cancelled' ? 'danger' : 'warning')) }} bg-opacity-10 text-{{ $item->item_status === 'delivered' ? 'success' : ($item->item_status === 'shipped' ? 'primary' : ($item->item_status === 'cancelled' ? 'danger' : 'warning')) }}">
                                {{ ucfirst($item->item_status) }}
                            </span>
                        </td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('vendor.orders.show', $item->id) }}" class="btn btn-sm btn-outline-success">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Orders Found</h6>
                            <p class="small text-muted mb-0">Orders will show up here once placed by buyers.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="p-3 border-top">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection
