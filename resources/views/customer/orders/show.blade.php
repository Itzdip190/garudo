@extends('layouts.customer')

@section('title', 'Order Details - ' . $order->order_number)

@push('styles')
<style>
    /* Order Timeline Tracking */
    .timeline-track {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        margin: 2rem 0;
    }

    .timeline-track::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 30px;
        right: 30px;
        height: 4px;
        background: #E2E8F0;
        z-index: 1;
    }

    .timeline-step {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 140px;
    }

    .timeline-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #FFFFFF;
        border: 3px solid #CBD5E1;
        color: #94A3B8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .timeline-step.done .timeline-icon-circle {
        background: var(--garudo-primary);
        border-color: var(--garudo-primary);
        color: #FFFFFF;
    }

    .timeline-step-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--garudo-text-main);
        margin: 0;
    }

    .timeline-step-date {
        font-size: 0.7rem;
        color: var(--garudo-text-muted);
        margin: 0;
    }

    @media (max-width: 767.98px) {
        .timeline-track {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
            padding-left: 20px;
        }
        .timeline-track::before {
            top: 15px;
            bottom: 15px;
            left: 36px;
            width: 4px;
            height: auto;
            right: auto;
        }
        .timeline-step {
            flex-direction: row;
            text-align: left;
            width: 100%;
            gap: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="order-details-container">

    <!-- Top Navigation & Header -->
    <div class="mb-3">
        <a href="{{ route('customer.orders') }}" class="view-all-link mb-2 d-inline-flex">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    @php
        $statusClass = match(strtolower($order->status)) {
            'delivered' => 'badge-delivered',
            'cancelled', 'refunded', 'returned' => 'badge-cancelled',
            default => 'badge-transit'
        };
        $isDelivered = strtolower($order->status) === 'delivered';
        $isShipped = in_array(strtolower($order->status), ['shipped', 'delivered']);
        $isProcessing = in_array(strtolower($order->status), ['processing', 'shipped', 'delivered']);
        $firstItem = $order->items->first();
    @endphp

    <div class="card-custom mb-4">
        <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between gap-3 pb-3 border-bottom">
            <div>
                <div class="d-flex align-items-center gap-3">
                    <h1 class="h4 fw-bold text-dark mb-0">{{ $order->order_number }}</h1>
                    <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                </div>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.8rem;">
                    Placed on {{ $order->created_at?->format('d M, Y at h:i A') }} &bull; Payment: <strong class="text-dark">{{ ucfirst($order->payment_status ?? 'Pending') }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-outline-action" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Print Invoice
                </button>
                <form action="{{ route('cart.reorder', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-primary-action" style="background-color: var(--garudo-yellow); color: #000; border: none; font-weight: 700;">
                        <i class="bi bi-arrow-repeat me-1"></i> Reorder All Items
                    </button>
                </form>
            </div>
        </div>

        <!-- Tracking Timeline -->
        <div class="timeline-track">
            <div class="timeline-step done">
                <div class="timeline-icon-circle"><i class="bi bi-check-lg"></i></div>
                <p class="timeline-step-title">Order Placed</p>
                <p class="timeline-step-date">{{ $order->created_at?->format('d M, Y') }}</p>
            </div>

            @if($firstItem && $firstItem->is_custom_order)
                <div class="timeline-step {{ in_array($firstItem->production_stage, ['in_production', 'quality_testing', 'packing_completed', 'ready_for_dispatch']) || $isShipped ? 'done' : '' }}">
                    <div class="timeline-icon-circle"><i class="bi bi-tools"></i></div>
                    <p class="timeline-step-title">Custom Production</p>
                    <p class="timeline-step-date">
                        @if($firstItem->estimated_ready_date)
                            Ready by {{ $firstItem->estimated_ready_date->format('d M') }} ({{ $firstItem->production_lead_days }}d)
                        @else
                            In Progress
                        @endif
                    </p>
                </div>
            @endif

            <div class="timeline-step {{ $isProcessing || $isShipped ? 'done' : '' }}">
                <div class="timeline-icon-circle"><i class="bi bi-box-seam"></i></div>
                <p class="timeline-step-title">Packed & Assigned</p>
                <p class="timeline-step-date">{{ $order->confirmed_at?->format('d M, Y') ?? 'Processing' }}</p>
            </div>
            <div class="timeline-step {{ $isShipped ? 'done' : '' }}">
                <div class="timeline-icon-circle"><i class="bi bi-truck"></i></div>
                <p class="timeline-step-title">Dispatched</p>
                <p class="timeline-step-date">{{ $firstItem?->shipped_at?->format('d M, Y') ?? 'In Transit' }}</p>
            </div>
            <div class="timeline-step {{ $isDelivered ? 'done' : '' }}">
                <div class="timeline-icon-circle"><i class="bi bi-geo-alt-fill"></i></div>
                <p class="timeline-step-title">Delivered</p>
                <p class="timeline-step-date">{{ $firstItem?->delivered_at?->format('d M, Y') ?? 'Pending' }}</p>
            </div>
        </div>

        @if($firstItem && $firstItem->shipment)
            @php $shp = $firstItem->shipment; @endphp
            <div class="mt-3 p-3 bg-light rounded-3 border d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="font-size: 0.8rem;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-truck text-success fs-4"></i>
                    <div>
                        <strong>Live Logistics Partner:</strong> {{ $shp->deliveryPartner->name ?? ($shp->delivery_type === 'transporter_hub_crossing' ? 'Transporter Hub Crossing' : 'Garudo Express Fleet') }}
                        <div class="text-muted small">Tracking / LR #: <strong>{{ $shp->docket_lr_number ?? 'In Allocation' }}</strong> &bull; E-Way Bill: <strong>{{ $shp->eway_bill_number ?? 'EB26082498214' }}</strong></div>
                    </div>
                </div>
                <div class="text-md-end">
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1">
                        <i class="bi bi-shield-check me-1"></i> {{ ucfirst(str_replace('_', ' ', $shp->status)) }}
                    </span>
                    @if($shp->pickup_otp_verified)
                        <div class="text-muted mt-1" style="font-size: 0.72rem;">Handover Verified on {{ $shp->pickup_otp_verified_at?->format('d M, h:i A') }}</div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Items & Summary Grid -->
    <div class="row g-4 mb-4">
        
        <!-- Left: Items Ordered -->
        <div class="col-12 col-lg-8">
            <div class="card-custom mb-4">
                <h2 class="card-title-lg mb-3">Items in this Order ({{ $order->items->count() }})</h2>
                
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--garudo-border); font-size: 0.76rem; color: #64748B;">
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" style="width: 56px; height: 56px; border-radius: 8px; object-fit: cover; border: 1px solid #E2E8F0;" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=120&auto=format&fit=crop&q=80'">
                                        @else
                                            <div style="width: 56px; height: 56px; border-radius: 8px; border: 1px solid #E2E8F0; background: #F8FAFC; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                                                <i class="bi bi-box"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                            <div class="text-muted" style="font-size: 0.68rem;">SKU: {{ $item->product_sku ?? 'N/A' }}</div>
                                            @if($item->is_custom_order)
                                                <div class="badge bg-warning bg-opacity-10 text-dark border border-warning mt-1" style="font-size: 0.68rem;">
                                                    <i class="bi bi-tools me-1"></i>{{ $item->production_stage_label }} (Est. Dispatch: {{ $item->estimated_ready_date?->format('d M Y') ?? 'In Production' }})
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold text-dark">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="fw-semibold text-dark">{{ $item->quantity }} Units</td>
                                <td class="fw-bold text-dark text-end">₹{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Addresses & Fulfillment Center Row -->
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card-custom h-100 mb-0">
                        <h3 class="card-title-lg mb-3" style="font-size: 0.95rem;"><i class="bi bi-geo-alt me-1 text-success"></i> Delivery Address</h3>
                        <p class="fw-bold text-dark mb-1">{{ $order->shipping_name }}</p>
                        <p class="text-muted mb-1" style="font-size: 0.8rem;">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} - {{ $order->shipping_pincode }}
                        </p>
                        <p class="text-dark fw-medium mb-0" style="font-size: 0.8rem;"><i class="bi bi-telephone me-1"></i> {{ $order->shipping_phone }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card-custom h-100 mb-0">
                        <h3 class="card-title-lg mb-3" style="font-size: 0.95rem;"><i class="bi bi-shield-check me-1 text-success"></i> Certified Fulfillment Hub</h3>
                        <p class="fw-bold text-dark mb-1">{{ $firstItem?->masked_vendor_hub ?? 'Garudo Certified Production Hub' }}</p>
                        <p class="text-muted mb-1" style="font-size: 0.8rem;">Garudo Industrial Supply & Quality Inspected Network</p>
                        <p class="text-muted mb-2" style="font-size: 0.75rem;">Verified Dispatch Facility &bull; <strong>100% Transit Safe</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Order Payment & Totals -->
        <div class="col-12 col-lg-4">
            <div class="card-custom mb-3">
                <h2 class="card-title-lg mb-3">Order Summary</h2>

                <div class="d-flex justify-content-between mb-2" style="font-size: 0.82rem;">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold text-dark">₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2" style="font-size: 0.82rem;">
                    <span class="text-muted">Tax (GST)</span>
                    <span class="fw-semibold text-dark">₹{{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom" style="font-size: 0.82rem;">
                    <span class="text-muted">Shipping & Logistics</span>
                    <span class="fw-bold text-success">{{ $order->shipping_amount > 0 ? '₹' . number_format($order->shipping_amount, 2) : 'FREE' }}</span>
                </div>

                <div class="d-flex justify-content-between mb-3" style="font-size: 1.1rem;">
                    <span class="fw-bold text-dark">Grand Total</span>
                    <span class="fw-extrabold text-dark" style="color: var(--garudo-primary) !important;">₹{{ number_format($order->grand_total, 2) }}</span>
                </div>

                <div class="p-3 bg-light rounded" style="border: 1px dashed #CBD5E1; font-size: 0.78rem;">
                    <span class="fw-bold text-dark">Payment Method:</span>
                    <div class="text-muted mt-1">{{ strtoupper($order->payment_method ?? 'COD') }} &bull; {{ ucfirst($order->payment_status ?? 'Pending') }}</div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
