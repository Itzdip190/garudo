@extends('layouts.customer')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* Stat Cards */
    .stat-card {
        background: #FFFFFF;
        border: 1px solid var(--garudo-border);
        border-radius: var(--garudo-radius-lg);
        padding: 1.25rem;
        box-shadow: var(--garudo-shadow-card);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--garudo-shadow-hover);
    }

    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: var(--garudo-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        margin-bottom: 0.85rem;
    }

    .stat-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--garudo-text-muted);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--garudo-text-main);
        line-height: 1.2;
        margin-bottom: 0.75rem;
    }

    .stat-link {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--garudo-primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.15s;
    }

    .stat-link:hover {
        gap: 7px;
        color: var(--garudo-primary-hover);
    }

    /* Table Styles */
    .table-garudo {
        width: 100%;
        margin-bottom: 0;
        vertical-align: middle;
    }

    .table-garudo th {
        font-size: 0.76rem;
        font-weight: 600;
        color: #64748B;
        background-color: transparent;
        border-bottom: 1px solid var(--garudo-border);
        padding: 0.85rem 0.65rem;
        white-space: nowrap;
    }

    .table-garudo td {
        padding: 0.95rem 0.65rem;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.82rem;
    }

    .table-garudo tr:last-child td {
        border-bottom: none;
    }

    .order-item-thumb {
        width: 44px;
        height: 44px;
        border-radius: var(--garudo-radius-sm);
        object-fit: cover;
        border: 1px solid var(--garudo-border);
        background: #F8FAFC;
    }

    /* Promo Banner */
    .promo-banner-card {
        background: linear-gradient(135deg, #07382B 0%, #0B4D3C 60%, #0E5E4A 100%);
        border-radius: var(--garudo-radius-xl);
        padding: 2rem 2.2rem;
        color: #FFFFFF;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(11, 77, 60, 0.2);
    }

    .promo-title-small {
        font-size: 0.95rem;
        font-weight: 600;
        color: #A7F3D0;
        margin-bottom: 0.2rem;
    }

    .promo-title-large {
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        line-height: 1.1;
    }

    .promo-desc {
        font-size: 0.84rem;
        color: #E2E8F0;
        max-width: 380px;
        margin-bottom: 1.5rem;
        line-height: 1.45;
    }

    .btn-promo-shop {
        background-color: #FFFFFF;
        color: var(--garudo-primary);
        font-weight: 700;
        font-size: 0.84rem;
        padding: 0.6rem 1.6rem;
        border-radius: 9999px;
        border: none;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .btn-promo-shop:hover {
        transform: scale(1.03);
        background-color: #F8FAFC;
        color: var(--garudo-primary-hover);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Delivery Address Widget */
    .address-widget-item {
        position: relative;
        padding-left: 24px;
        margin-bottom: 1.25rem;
    }

    .address-widget-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid var(--garudo-primary);
        background: #FFFFFF;
    }

    .address-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--garudo-text-main);
        display: flex;
        align-items: center;
        margin-bottom: 0.25rem;
    }

    .address-text {
        font-size: 0.78rem;
        color: var(--garudo-text-muted);
        line-height: 1.35;
        margin-bottom: 0.2rem;
    }

    .address-phone {
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--garudo-text-main);
        margin: 0;
    }

    /* Quick Actions */
    .quick-action-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 0.75rem 0.5rem;
        border-radius: var(--garudo-radius-md);
        text-decoration: none;
        color: inherit;
        transition: background 0.15s, transform 0.15s;
    }

    .quick-action-item:hover {
        background-color: #F8FAFC;
        transform: translateX(3px);
    }

    .quick-action-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: #E8F7F0;
        color: var(--garudo-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .quick-action-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--garudo-text-main);
        margin: 0;
    }

    .quick-action-sub {
        font-size: 0.72rem;
        color: var(--garudo-text-muted);
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="customer-dashboard-container">

    <!-- ---------------- WELCOME HEADER ---------------- -->
    <div class="mb-4">
        <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
            Welcome back, {{ explode(' ', $customer->name ?? 'Customer')[0] }}! <span style="font-size: 1.6rem;">👋</span>
        </h1>
        <p class="text-muted mb-0" style="font-size: 0.875rem;">
            Manage your orders, track deliveries and build better with Garudo.
        </p>
    </div>

    <!-- ---------------- STATS ROW (4 CARDS) ---------------- -->
    <div class="row g-3 mb-4">
        
        <!-- Total Orders -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-icon-wrap" style="background-color: #E8F7F0; color: #0B7B58;">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
                </div>
                <a href="{{ route('customer.orders') }}" class="stat-link">
                    View all orders <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Orders in Progress -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-icon-wrap" style="background-color: #FEF3E2; color: #D97706;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div class="stat-label">Orders in Progress</div>
                    <div class="stat-value">{{ $stats['orders_in_progress'] ?? 0 }}</div>
                </div>
                <a href="{{ route('customer.orders', ['status' => 'in_progress']) }}" class="stat-link">
                    Track your orders <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Completed Orders -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-icon-wrap" style="background-color: #EBF5FF; color: #2563EB;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="stat-label">Completed Orders</div>
                    <div class="stat-value">{{ $stats['completed_orders'] ?? 0 }}</div>
                </div>
                <a href="{{ route('customer.orders', ['status' => 'delivered']) }}" class="stat-link">
                    View details <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="stat-icon-wrap" style="background-color: #F3E8FF; color: #7C3AED;">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="stat-label">Total Spent</div>
                    <div class="stat-value">₹{{ $stats['total_spent'] ?? '0.00' }}</div>
                </div>
                <a href="{{ route('customer.orders') }}" class="stat-link">
                    All time spending <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- ---------------- MAIN 2-COLUMN SECTION ---------------- -->
    <div class="row g-4">

        <!-- LEFT COLUMN: Recent Orders & Promotional Banner -->
        <div class="col-12 col-lg-8">
            
            <!-- Recent Orders Card -->
            <div class="card-custom">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-1">
                    <h2 class="card-title-lg">Recent Orders</h2>
                    <a href="{{ route('customer.orders') }}" class="view-all-link">
                        View All Orders <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-garudo align-middle">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Items</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            @php
                                $firstItem = $order->items->first();
                                $statusClass = match(strtolower($order->status)) {
                                    'delivered' => 'badge-delivered',
                                    'cancelled', 'refunded', 'returned' => 'badge-cancelled',
                                    default => 'badge-transit'
                                };
                            @endphp
                            <tr>
                                <!-- Order ID & Date -->
                                <td>
                                    <div class="fw-bold text-dark">{{ $order->order_number }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $order->created_at?->format('d M, Y') }}</div>
                                </td>

                                <!-- Items with Image -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($firstItem && $firstItem->product_image)
                                            <img src="{{ asset('storage/' . $firstItem->product_image) }}" alt="{{ $firstItem->product_name }}" class="order-item-thumb" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=120&auto=format&fit=crop&q=80'">
                                        @else
                                            <div class="order-item-thumb d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-box"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $firstItem ? $firstItem->product_name : 'Building Materials' }}</div>
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

                                <!-- Vendor & Location -->
                                <td>
                                    <div class="fw-medium text-dark">{{ $firstItem?->vendor?->shop_name ?? $firstItem?->vendor?->company_name ?? 'Verified Supplier' }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ $firstItem?->vendor?->city ?? 'India' }}</div>
                                </td>

                                <!-- Amount & Payment Status -->
                                <td>
                                    <div class="fw-bold text-dark">₹{{ number_format($order->grand_total, 2) }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ ucfirst($order->payment_status ?? 'Pending') }}</div>
                                </td>

                                <!-- Status Badge -->
                                <td>
                                    <span class="{{ $statusClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <!-- Action Button -->
                                <td class="text-end">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn-outline-action">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 px-3">
                    <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 54px; height: 54px; color: var(--garudo-primary);">
                        <i class="bi bi-cart-x fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">No Orders Placed Yet</h6>
                    <p class="text-muted mb-3" style="font-size: 0.8rem;">Browse verified vendors and place your first building materials order.</p>
                    <a href="{{ route('products.index') }}" class="btn-primary-action">
                        <i class="bi bi-cart-plus me-1"></i> Browse Materials
                    </a>
                </div>
                @endif
            </div>

            <!-- Promotional Banner Card -->
            <div class="promo-banner-card">
                <div class="row align-items-center">
                    <div class="col-12 col-md-7 z-1">
                        <div class="promo-title-small">Build Better with</div>
                        <h3 class="promo-title-large">GARUDO</h3>
                        <p class="promo-desc">
                            Connect with trusted sellers, get the best prices and Fast delivery all over India.
                        </p>
                        <a href="{{ route('products.index') }}" class="btn-promo-shop">
                            Shop Now
                        </a>
                    </div>
                    <div class="col-12 col-md-5 d-none d-md-flex justify-content-end align-items-center">
                        <svg width="260" height="150" viewBox="0 0 260 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M180 20 C200 25 230 40 235 60 C240 80 220 110 200 130 C185 115 170 90 175 60 Z" fill="rgba(255,255,255,0.06)"/>
                            <rect x="70" y="35" width="70" height="95" rx="3" fill="#D97706" fill-opacity="0.25"/>
                            <path d="M70 55 H140 M70 75 H140 M70 95 H140 M70 115 H140" stroke="#FBBF24" stroke-width="1.5" stroke-dasharray="2 2"/>
                            <path d="M85 35 V130 M105 35 V130 M125 35 V130" stroke="#FBBF24" stroke-width="1.5" stroke-dasharray="2 2"/>
                            <line x1="50" y1="15" x2="50" y2="135" stroke="#F59E0B" stroke-width="3"/>
                            <line x1="30" y1="18" x2="110" y2="18" stroke="#F59E0B" stroke-width="3"/>
                            <line x1="50" y1="18" x2="30" y2="35" stroke="#F59E0B" stroke-width="2"/>
                            <line x1="100" y1="18" x2="100" y2="45" stroke="#E2E8F0" stroke-width="1.5"/>
                            <rect x="96" y="45" width="8" height="8" fill="#F59E0B" rx="1"/>
                            <path d="M40 135 C60 110 90 110 110 135 Z" fill="#D97706"/>
                            <rect x="140" y="95" width="45" height="30" rx="3" fill="#F59E0B"/>
                            <rect x="175" y="102" width="22" height="23" rx="3" fill="#FBBF24"/>
                            <rect x="180" y="105" width="14" height="10" rx="1" fill="#1E293B"/>
                            <circle cx="152" cy="128" r="7" fill="#1E293B"/>
                            <circle cx="152" cy="128" r="3" fill="#94A3B8"/>
                            <circle cx="185" cy="128" r="7" fill="#1E293B"/>
                            <circle cx="185" cy="128" r="3" fill="#94A3B8"/>
                            <circle cx="205" cy="55" r="5" fill="#EF4444"/>
                            <circle cx="205" cy="55" r="10" stroke="#EF4444" stroke-width="1.5" stroke-opacity="0.5"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Delivery Addresses & Quick Actions -->
        <div class="col-12 col-lg-4">
            
            <!-- Delivery Addresses Card -->
            <div class="card-custom">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-1">
                    <h2 class="card-title-lg">Delivery Addresses</h2>
                    <a href="{{ route('customer.addresses') }}" class="view-all-link">
                        View All
                    </a>
                </div>

                @if($addresses->count() > 0)
                    @foreach($addresses as $addr)
                    <div class="address-widget-item">
                        <div class="address-title">
                            {{ $addr->label ?? 'Delivery Point' }}
                            @if($addr->is_default)
                            <span class="badge-default-tag">Default</span>
                            @endif
                        </div>
                        <div class="address-text">
                            {{ $addr->address_line1 }}
                            @if($addr->address_line2), {{ $addr->address_line2 }} @endif
                            <br>
                            {{ $addr->city }}, {{ $addr->state }} - {{ $addr->pincode }}
                        </div>
                        <div class="address-phone">{{ $addr->contact_phone }}</div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3 mb-2">
                        <p class="text-muted mb-0" style="font-size: 0.82rem;">No delivery addresses saved yet.</p>
                    </div>
                @endif

                <a href="{{ route('customer.addresses') }}" class="btn btn-outline-action w-100 py-2 mt-2">
                    <i class="bi bi-plus-lg me-1"></i> Add New Address
                </a>
            </div>

            <!-- Quick Actions Card -->
            <div class="card-custom">
                <h2 class="card-title-lg mb-3">Quick Actions</h2>

                <div class="d-flex flex-column gap-2">
                    <!-- Action 1: Request a Quote -->
                    <a href="{{ route('customer.quotes') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <h3 class="quick-action-title">Request a Quote</h3>
                            <p class="quick-action-sub">Get best prices from vendors</p>
                        </div>
                    </a>

                    <!-- Action 2: Bulk Order -->
                    <a href="{{ route('products.index') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h3 class="quick-action-title">Bulk Order</h3>
                            <p class="quick-action-sub">Order in bulk & save more</p>
                        </div>
                    </a>

                    <!-- Action 3: My Inquiries -->
                    <a href="{{ route('customer.inquiries') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <div>
                            <h3 class="quick-action-title">My Inquiries</h3>
                            <p class="quick-action-sub">View your inquiries</p>
                        </div>
                    </a>

                    <!-- Action 4: Become a Seller -->
                    <a href="{{ route('vendor.register') }}" class="quick-action-item">
                        <div class="quick-action-icon">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div>
                            <h3 class="quick-action-title">Become a Seller</h3>
                            <p class="quick-action-sub">Start selling on Garudo</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
