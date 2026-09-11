@extends('layouts.customer')

@section('title', 'My Orders')

@section('content')
<div class="orders-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">My Orders</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">View order status, invoices, and track live site deliveries.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-cart-plus"></i> Buy Materials
            </a>
        </div>
    </div>

    <!-- Filter Pills -->
    <div class="card-custom mb-4 py-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('customer.orders') }}" class="btn btn-sm {{ ($statusFilter ?? 'all') === 'all' ? 'btn-success text-white' : 'btn-light' }}" style="border-radius: 9999px; font-weight: 600; font-size: 0.8rem; padding: 0.4rem 1.1rem; {{ ($statusFilter ?? 'all') === 'all' ? 'background-color: var(--garudo-primary); border-color: var(--garudo-primary);' : '' }}">
                All Orders ({{ $counts['all'] ?? 0 }})
            </a>
            <a href="{{ route('customer.orders', ['status' => 'in_progress']) }}" class="btn btn-sm {{ ($statusFilter ?? '') === 'in_progress' ? 'btn-success text-white' : 'btn-light' }}" style="border-radius: 9999px; font-weight: 600; font-size: 0.8rem; padding: 0.4rem 1.1rem; {{ ($statusFilter ?? '') === 'in_progress' ? 'background-color: var(--garudo-primary); border-color: var(--garudo-primary);' : '' }}">
                In Progress ({{ $counts['in_progress'] ?? 0 }})
            </a>
            <a href="{{ route('customer.orders', ['status' => 'delivered']) }}" class="btn btn-sm {{ ($statusFilter ?? '') === 'delivered' ? 'btn-success text-white' : 'btn-light' }}" style="border-radius: 9999px; font-weight: 600; font-size: 0.8rem; padding: 0.4rem 1.1rem; {{ ($statusFilter ?? '') === 'delivered' ? 'background-color: var(--garudo-primary); border-color: var(--garudo-primary);' : '' }}">
                Delivered ({{ $counts['delivered'] ?? 0 }})
            </a>
            <a href="{{ route('customer.orders', ['status' => 'cancelled']) }}" class="btn btn-sm {{ ($statusFilter ?? '') === 'cancelled' ? 'btn-success text-white' : 'btn-light' }}" style="border-radius: 9999px; font-weight: 600; font-size: 0.8rem; padding: 0.4rem 1.1rem; {{ ($statusFilter ?? '') === 'cancelled' ? 'background-color: var(--garudo-primary); border-color: var(--garudo-primary);' : '' }}">
                Cancelled ({{ $counts['cancelled'] ?? 0 }})
            </a>
        </div>
    </div>

    <!-- Orders List Card -->
    <div class="card-custom">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle" style="margin-bottom: 0;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--garudo-border);">
                        <th style="font-size: 0.76rem; font-weight: 600; color: #64748B; padding: 0.85rem 0.65rem;">Order Details</th>
                        <th style="font-size: 0.76rem; font-weight: 600; color: #64748B; padding: 0.85rem 0.65rem;">Item Description</th>
                        <th style="font-size: 0.76rem; font-weight: 600; color: #64748B; padding: 0.85rem 0.65rem;">Vendor</th>
                        <th style="font-size: 0.76rem; font-weight: 600; color: #64748B; padding: 0.85rem 0.65rem;">Payment</th>
                        <th style="font-size: 0.76rem; font-weight: 600; color: #64748B; padding: 0.85rem 0.65rem;">Delivery Status</th>
                        <th style="font-size: 0.76rem; font-weight: 600; color: #64748B; padding: 0.85rem 0.65rem;" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php
                        $firstItem = $order->items->first();
                        $statusClass = match(strtolower($order->status)) {
                            'delivered' => 'badge-delivered',
                            'cancelled', 'refunded', 'returned' => 'badge-cancelled',
                            default => 'badge-transit'
                        };
                    @endphp
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <!-- Order ID & Date -->
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-bold text-dark">{{ $order->order_number }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $order->created_at?->format('d M, Y') }}</div>
                        </td>

                        <!-- Items with Image -->
                        <td style="padding: 1rem 0.65rem;">
                            <div class="d-flex align-items-center gap-2">
                                @if($firstItem && $firstItem->product_image)
                                    <img src="{{ asset('storage/' . $firstItem->product_image) }}" alt="{{ $firstItem->product_name }}" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid #E2E8F0;" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=120&auto=format&fit=crop&q=80'">
                                @else
                                    <div style="width: 48px; height: 48px; border-radius: 8px; border: 1px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                                        <i class="bi bi-box"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-dark">{{ $firstItem ? $firstItem->product_name : 'Materials Order' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        @if($order->items->count() > 1)
                                            + {{ $order->items->count() - 1 }} more item(s)
                                        @elseif($firstItem)
                                            Qty: {{ $firstItem->quantity }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Fulfillment Hub (Masked) & Production Schedule -->
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-medium text-dark"><i class="bi bi-shield-check text-success me-1"></i>{{ $firstItem?->masked_vendor_hub ?? 'Garudo Certified Hub' }}</div>
                            @if($firstItem && $firstItem->is_custom_order && $firstItem->estimated_ready_date)
                                <div class="badge bg-warning bg-opacity-10 text-dark border border-warning mt-1" style="font-size: 0.68rem;">
                                    <i class="bi bi-tools me-1"></i>Ready in {{ $firstItem->production_lead_days }} Days ({{ $firstItem->estimated_ready_date->format('d M') }})
                                </div>
                            @else
                                <div class="text-muted" style="font-size: 0.72rem;">Garudo Fulfillment Network</div>
                            @endif
                        </td>

                        <!-- Amount & Payment Method -->
                        <td style="padding: 1rem 0.65rem;">
                            <div class="fw-bold text-dark">₹{{ number_format($order->grand_total, 2) }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ strtoupper($order->payment_method ?? 'COD') }} ({{ ucfirst($order->payment_status ?? 'Pending') }})</div>
                        </td>

                        <!-- Status Badge & Delivery info -->
                        <td style="padding: 1rem 0.65rem;">
                            <span class="{{ $statusClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <!-- Action Button -->
                        <td style="padding: 1rem 0.65rem;" class="text-end">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <form action="{{ route('cart.reorder', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-yellow fw-bold" style="font-size: 0.75rem; padding: 0.35rem 0.75rem;" title="Reorder all materials into cart">
                                        <i class="bi bi-arrow-repeat me-1"></i> Reorder
                                    </button>
                                </form>
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-outline-action">
                                    Details
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
                <i class="bi bi-cart-x fs-3"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Orders Found</h5>
            <p class="text-muted mb-3" style="font-size: 0.85rem;">You have not placed any orders matching this filter.</p>
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-cart-plus me-1"></i> Browse Marketplace
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
