@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')

@push('styles')
<style>
    .metric-card {
        background: #FFFFFF;
        border: 1px solid var(--garudo-border);
        border-radius: var(--garudo-radius-lg);
        padding: 1.15rem;
        box-shadow: var(--garudo-shadow-card);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.15s, box-shadow 0.15s;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--garudo-shadow-hover);
    }

    .metric-top-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 0.75rem;
    }

    .metric-icon-box {
        width: 44px;
        height: 44px;
        border-radius: var(--garudo-radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .metric-info {
        flex-grow: 1;
    }

    .metric-label {
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--garudo-text-muted);
        margin-bottom: 0.2rem;
    }

    .metric-value {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--garudo-text-main);
        line-height: 1.15;
    }

    .metric-trend {
        font-size: 0.72rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .trend-positive {
        color: #0B7B58;
    }

    .trend-neutral {
        color: #64748B;
    }

    .trend-negative {
        color: #EF4444;
    }

    .metric-action-link {
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--garudo-primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.15s;
    }

    .metric-action-link:hover {
        gap: 7px;
        color: var(--garudo-primary-hover);
    }

    .btn-date-filter {
        background: #FFFFFF;
        border: 1px solid var(--garudo-border);
        border-radius: var(--garudo-radius-md);
        padding: 0.45rem 0.95rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--garudo-text-main);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: var(--garudo-shadow-sm);
        transition: all 0.15s;
    }

    .btn-date-filter:hover {
        border-color: var(--garudo-primary);
        color: var(--garudo-primary);
    }

    .status-badge-pill {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        display: inline-block;
        white-space: nowrap;
    }

    .status-delivered {
        background-color: #E8F8F0;
        color: #0E7045;
    }

    .status-transit {
        background-color: #FEF3E2;
        color: #B45309;
    }

    .status-pending {
        background-color: #EFF6FF;
        color: #2563EB;
    }

    .status-cancelled {
        background-color: #FEECEB;
        color: #D92D20;
    }

    .recent-order-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--garudo-border-light);
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
    }

    .recent-order-row:last-child {
        border-bottom: none;
    }

    .recent-order-row:hover {
        background-color: #F8FAFC;
    }

    .order-prod-thumb {
        width: 40px;
        height: 40px;
        border-radius: var(--garudo-radius-sm);
        object-fit: cover;
        background: #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        border: 1px solid var(--garudo-border);
    }

    .table-top-products {
        width: 100%;
        vertical-align: middle;
        margin-bottom: 0;
    }

    .table-top-products th {
        font-size: 0.74rem;
        font-weight: 600;
        color: var(--garudo-text-muted);
        border-bottom: 1px solid var(--garudo-border);
        padding: 0.75rem 0.6rem;
        background: transparent;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-top-products td {
        padding: 0.85rem 0.6rem;
        border-bottom: 1px solid var(--garudo-border-light);
        font-size: 0.82rem;
    }

    .table-top-products tr:last-child td {
        border-bottom: none;
    }

    .prod-cell-thumb {
        width: 38px;
        height: 38px;
        border-radius: var(--garudo-radius-sm);
        object-fit: cover;
        background: #F8FAFC;
        border: 1px solid var(--garudo-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .perf-metric-tile {
        background: #F8FAFC;
        border: 1px solid var(--garudo-border);
        border-radius: var(--garudo-radius-md);
        padding: 0.85rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .perf-label {
        font-size: 0.74rem;
        font-weight: 600;
        color: var(--garudo-text-muted);
        margin-bottom: 0.25rem;
    }

    .perf-val-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: 0.4rem;
    }

    .perf-value {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--garudo-text-main);
    }

    .perf-trend {
        font-size: 0.7rem;
        font-weight: 700;
    }

    .quick-action-tile {
        background: #FFFFFF;
        border: 1px solid var(--garudo-border);
        border-radius: var(--garudo-radius-md);
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--garudo-text-main);
        text-decoration: none;
        box-shadow: var(--garudo-shadow-sm);
        transition: all 0.15s ease-in-out;
        flex: 1 1 calc(14.28% - 12px);
        min-width: 140px;
    }

    .quick-action-tile:hover {
        border-color: var(--garudo-primary);
        color: var(--garudo-primary);
        box-shadow: var(--garudo-shadow-card);
        transform: translateY(-2px);
    }

    .quick-action-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--garudo-primary-light);
        color: var(--garudo-primary-text);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .quick-action-text {
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.2;
    }

    .view-all-header-link {
        color: var(--garudo-primary);
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .view-all-header-link:hover {
        color: var(--garudo-primary-hover);
        text-decoration: underline;
    }

    .empty-state-box {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #64748B;
    }

    .empty-state-icon {
        font-size: 2.5rem;
        color: #94A3B8;
        margin-bottom: 0.75rem;
    }
</style>
@endpush

@section('content')

    <!-- Welcome & Header Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main); font-size: 1.45rem;">
                Welcome back, {{ $vendor->company_name ?? $vendor->name }}! 👋
            </h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">
                Here's what's happening with your business today.
            </p>
        </div>

        <!-- Date Range Filter Dropdown -->
        <div class="dropdown">
            <button class="btn-date-filter dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-calendar3 text-muted"></i>
                <span>Last 7 Days</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 12px; font-size: 0.82rem;">
                <li><a class="dropdown-item py-2 active bg-success" href="#">Last 7 Days</a></li>
                <li><a class="dropdown-item py-2" href="#">Last 30 Days</a></li>
                <li><a class="dropdown-item py-2" href="#">This Month</a></li>
                <li><a class="dropdown-item py-2" href="#">All Time</a></li>
            </ul>
        </div>
    </div>

    <!-- Account Verification Warning (If Applicable) -->
    @if(isset($vendor) && $vendor->approval_status !== 'approved')
        <div class="alert alert-warning d-flex align-items-center mb-4" style="border-radius: 12px; border: none; background: #FEF3E2; color: #B45309;">
            <i class="bi bi-shield-exclamation me-2 fs-5"></i>
            <div>
                <strong>Account Pending Verification:</strong> Your account is currently under review by our admin team. Once approved, your catalog will go live across India on the GARUDO B2B marketplace.
            </div>
        </div>
    @endif

    <!-- ---------------- 6 TOP METRIC CARDS ---------------- -->
    <div class="row g-3 mb-4">
        <!-- 1. Total Sales -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="metric-card">
                <div class="metric-top-row">
                    <div class="metric-icon-box" style="background-color: #E8F7F0; color: #0B7B58;">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Total Sales</div>
                        <div class="metric-value">₹{{ number_format($stats['total_sales'] ?? 0, 2) }}</div>
                    </div>
                </div>
                <div class="metric-trend {{ ($stats['total_sales'] ?? 0) > 0 ? 'trend-positive' : 'trend-neutral' }}">
                    <i class="bi bi-arrow-up-short fs-6"></i>
                    <span>{{ ($stats['total_sales'] ?? 0) > 0 ? 'Live Revenue' : 'No sales yet' }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Total Orders -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="metric-card">
                <div class="metric-top-row">
                    <div class="metric-icon-box" style="background-color: #EFF6FF; color: #2563EB;">
                        <i class="bi bi-cart3"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Total Orders</div>
                        <div class="metric-value">{{ $stats['total_orders'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="metric-trend {{ ($stats['total_orders'] ?? 0) > 0 ? 'trend-positive' : 'trend-neutral' }}">
                    <i class="bi bi-bag-check fs-6"></i>
                    <span>{{ ($stats['total_orders'] ?? 0) > 0 ? 'All Time Orders' : '0 Orders' }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Store Views -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="metric-card">
                <div class="metric-top-row">
                    <div class="metric-icon-box" style="background-color: #FFF7ED; color: #EA580C;">
                        <i class="bi bi-eye"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Store Views</div>
                        <div class="metric-value">{{ number_format($stats['store_views'] ?? 0) }}</div>
                    </div>
                </div>
                <div class="metric-trend {{ ($stats['store_views'] ?? 0) > 0 ? 'trend-positive' : 'trend-neutral' }}">
                    <i class="bi bi-activity fs-6"></i>
                    <span>Catalog visits</span>
                </div>
            </div>
        </div>

        <!-- 4. Active Products -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="metric-card">
                <div class="metric-top-row">
                    <div class="metric-icon-box" style="background-color: #FAF5FF; color: #9333EA;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Active Products</div>
                        <div class="metric-value">{{ $stats['active_products'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="metric-trend trend-neutral">
                    <span>{{ $stats['total_products'] ?? 0 }} Total Listed</span>
                </div>
            </div>
        </div>

        <!-- 5. Avg. Rating -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="metric-card">
                <div class="metric-top-row">
                    <div class="metric-icon-box" style="background-color: #FFF1F2; color: #E11D48;">
                        <i class="bi bi-star"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Avg. Rating</div>
                        <div class="metric-value">{{ ($stats['avg_rating'] ?? 0) > 0 ? number_format($stats['avg_rating'], 1) : '0.0' }} / 5</div>
                    </div>
                </div>
                <div class="metric-trend trend-neutral">
                    <span>Customer reviews</span>
                </div>
            </div>
        </div>

        <!-- 6. Wallet Balance -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="metric-card">
                <div class="metric-top-row">
                    <div class="metric-icon-box" style="background-color: #ECFEFF; color: #0891B2;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="metric-info">
                        <div class="metric-label">Wallet Balance</div>
                        <div class="metric-value">₹{{ number_format($stats['wallet_balance'] ?? 0, 2) }}</div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('vendor.earnings') }}" class="metric-action-link">
                        <span>View Payouts</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ---------------- MIDDLE ROW: CHARTS & RECENT ORDERS ---------------- -->
    <div class="row g-3 mb-4">
        
        <!-- Col 1: Sales Overview Area Chart -->
        <div class="col-12 col-lg-5">
            <div class="card-custom h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-title-header">Sales Overview</h6>
                    <span class="text-muted small">Last 7 Days</span>
                </div>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="salesOverviewChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Col 2: Orders Overview Donut Chart -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card-custom h-100 d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="card-title-header">Orders Overview</h6>
                </div>
                <div style="position: relative; height: 200px; width: 100%; display: flex; align-items: center; justify-content: center;">
                    <canvas id="ordersOverviewChart"></canvas>
                </div>
                <!-- Custom Legend -->
                <div class="mt-3 pt-2 border-top">
                    <div class="row g-2 text-muted" style="font-size: 0.75rem;">
                        <div class="col-6 d-flex align-items-center gap-2">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #10B981; display: inline-block;"></span>
                            <span>Delivered: <strong>{{ $orderCounts['delivered'] ?? 0 }}</strong></span>
                        </div>
                        <div class="col-6 d-flex align-items-center gap-2">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #F59E0B; display: inline-block;"></span>
                            <span>In Transit: <strong>{{ $orderCounts['in_transit'] ?? 0 }}</strong></span>
                        </div>
                        <div class="col-6 d-flex align-items-center gap-2">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #3B82F6; display: inline-block;"></span>
                            <span>Pending: <strong>{{ $orderCounts['pending'] ?? 0 }}</strong></span>
                        </div>
                        <div class="col-6 d-flex align-items-center gap-2">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #EF4444; display: inline-block;"></span>
                            <span>Cancelled: <strong>{{ $orderCounts['cancelled'] ?? 0 }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 3: Recent Orders List -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card-custom h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="card-title-header">Recent Orders</h6>
                        <a href="{{ route('vendor.orders.index') }}" class="view-all-header-link">View All →</a>
                    </div>

                    <!-- Order Items Loop -->
                    <div class="d-flex flex-column">
                        @forelse($recentOrders as $item)
                            <a href="{{ route('vendor.orders.show', $item->id) }}" class="recent-order-row">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="order-prod-thumb">
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}" alt="Product" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                        @else
                                            <i class="bi bi-box text-muted"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-truncate" style="max-width: 130px; font-size: 0.8rem; color: var(--garudo-text-main);">
                                            #{{ $item->order->order_number ?? 'ORD-' . $item->order_id }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $item->created_at->format('d M, Y') }}</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="status-badge-pill status-{{ $item->item_status === 'delivered' ? 'delivered' : ($item->item_status === 'shipped' ? 'transit' : ($item->item_status === 'cancelled' ? 'cancelled' : 'pending')) }}">
                                        {{ ucfirst($item->item_status) }}
                                    </span>
                                    <span class="fw-bold" style="font-size: 0.82rem;">₹{{ number_format($item->total_amount, 2) }}</span>
                                    <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                                </div>
                            </a>
                        @empty
                            <div class="empty-state-box py-4">
                                <i class="bi bi-bag-x empty-state-icon"></i>
                                <h6 class="fw-semibold text-dark mb-1">No Orders Yet</h6>
                                <p class="small text-muted mb-3">When buyers purchase your products, orders will appear here.</p>
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-sm btn-success" style="background-color: var(--garudo-primary); border-radius: 8px;">
                                    <i class="bi bi-plus-circle me-1"></i> Add Products
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($recentOrders->count() > 0)
                    <div class="text-center pt-2 mt-2 border-top">
                        <a href="{{ route('vendor.orders.index') }}" class="view-all-header-link" style="font-size: 0.8rem;">View All Orders →</a>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- ---------------- BOTTOM ROW: TOP SELLING PRODUCTS & STORE PERFORMANCE ---------------- -->
    <div class="row g-3 mb-4">
        
        <!-- Left: Top Selling Products Table -->
        <div class="col-12 col-lg-7">
            <div class="card-custom h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-title-header">Top Selling Products</h6>
                    <a href="{{ route('vendor.products.index') }}" class="view-all-header-link">View All Products →</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-top-products">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Sold</th>
                                <th>Revenue</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellingProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="prod-cell-thumb">
                                                @if($product->primaryImage)
                                                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="Product" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
                                                @else
                                                    📦
                                                @endif
                                            </div>
                                            <span class="fw-semibold text-truncate" style="max-width: 180px;">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td>₹{{ number_format($product->selling_price, 2) }} / {{ $product->unit ?? 'Unit' }}</td>
                                    <td class="fw-semibold">{{ $product->total_sold ?? 0 }}</td>
                                    <td class="fw-bold">₹{{ number_format(($product->total_sold ?? 0) * $product->selling_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($product->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-box-seam fs-3 d-block mb-2 text-muted"></i>
                                        <p class="mb-2">No products added to your catalog yet.</p>
                                        <a href="{{ route('vendor.products.create') }}" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-plus-lg me-1"></i> Add First Product
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Store Performance with Sparklines -->
        <div class="col-12 col-lg-5">
            <div class="card-custom h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-title-header">Store Performance</h6>
                    <a href="{{ route('vendor.analytics') }}" class="view-all-header-link">View Reports →</a>
                </div>

                <!-- 2x2 Performance Grid -->
                <div class="row g-3">
                    <!-- Metric 1: Conversion Rate -->
                    <div class="col-6">
                        <div class="perf-metric-tile">
                            <div>
                                <div class="perf-label">Conversion Rate</div>
                                <div class="perf-val-row">
                                    <span class="perf-value">
                                        {{ ($stats['store_views'] ?? 0) > 0 ? number_format((($stats['total_orders'] ?? 0) / $stats['store_views']) * 100, 2) . '%' : '0.00%' }}
                                    </span>
                                    <span class="perf-trend trend-positive">Live</span>
                                </div>
                            </div>
                            <!-- Green Sparkline SVG -->
                            <svg viewBox="0 0 100 25" style="width: 100%; height: 25px; overflow: visible;">
                                <path d="M0,20 Q15,10 30,18 T60,8 T85,15 T100,5" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="100" cy="5" r="2.5" fill="#10B981"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Metric 2: Return Rate -->
                    <div class="col-6">
                        <div class="perf-metric-tile">
                            <div>
                                <div class="perf-label">Return Rate</div>
                                <div class="perf-val-row">
                                    <span class="perf-value">
                                        {{ ($stats['total_orders'] ?? 0) > 0 ? number_format((($stats['returns_count'] ?? 0) / $stats['total_orders']) * 100, 2) . '%' : '0.00%' }}
                                    </span>
                                    <span class="perf-trend trend-neutral">0%</span>
                                </div>
                            </div>
                            <!-- Orange Sparkline SVG -->
                            <svg viewBox="0 0 100 25" style="width: 100%; height: 25px; overflow: visible;">
                                <path d="M0,15 Q20,18 40,8 T70,16 T85,10 T100,12" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="100" cy="12" r="2.5" fill="#F59E0B"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Metric 3: Fulfillment Rate -->
                    <div class="col-6">
                        <div class="perf-metric-tile">
                            <div>
                                <div class="perf-label">Fulfillment Rate</div>
                                <div class="perf-val-row">
                                    <span class="perf-value">
                                        {{ ($stats['total_orders'] ?? 0) > 0 ? number_format((($orderCounts['delivered'] ?? 0) / $stats['total_orders']) * 100, 1) . '%' : '100%' }}
                                    </span>
                                    <span class="perf-trend trend-positive">↑</span>
                                </div>
                            </div>
                            <!-- Blue Sparkline SVG -->
                            <svg viewBox="0 0 100 25" style="width: 100%; height: 25px; overflow: visible;">
                                <path d="M0,18 Q20,12 35,16 T65,6 T85,10 T100,4" fill="none" stroke="#3B82F6" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="100" cy="4" r="2.5" fill="#3B82F6"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Metric 4: Customer Satisfaction -->
                    <div class="col-6">
                        <div class="perf-metric-tile">
                            <div>
                                <div class="perf-label">Customer Rating</div>
                                <div class="perf-val-row">
                                    <span class="perf-value">{{ ($stats['avg_rating'] ?? 0) > 0 ? number_format($stats['avg_rating'], 1) : '0.0' }} / 5</span>
                                    <span class="perf-trend trend-positive">★</span>
                                </div>
                            </div>
                            <!-- Purple Sparkline SVG -->
                            <svg viewBox="0 0 100 25" style="width: 100%; height: 25px; overflow: visible;">
                                <path d="M0,15 Q25,8 45,18 T75,6 T90,12 T100,5" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="100" cy="5" r="2.5" fill="#8B5CF6"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ---------------- QUICK ACTIONS BAR ---------------- -->
    <div class="mb-3">
        <h6 class="card-title-header mb-3">Quick Actions</h6>
        <div class="d-flex flex-wrap gap-2">
            <!-- Action 1 -->
            <a href="{{ route('vendor.products.create') }}" class="quick-action-tile">
                <div class="quick-action-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div class="quick-action-text">
                    Add New<br>Product
                </div>
            </a>

            <!-- Action 2 -->
            <a href="{{ route('vendor.products.index') }}" class="quick-action-tile">
                <div class="quick-action-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="quick-action-text">
                    Manage<br>Products
                </div>
            </a>

            <!-- Action 3 -->
            <a href="{{ route('vendor.orders.index') }}" class="quick-action-tile">
                <div class="quick-action-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div class="quick-action-text">
                    Process<br>Orders
                </div>
            </a>

            <!-- Action 4 -->
            <a href="{{ route('vendor.quotations.index') }}" class="quick-action-tile">
                <div class="quick-action-icon">
                    <i class="bi bi-file-earmark-plus"></i>
                </div>
                <div class="quick-action-text">
                    Create<br>Quotation
                </div>
            </a>

            <!-- Action 5 -->
            <a href="{{ route('vendor.products.bulk') }}" class="quick-action-tile">
                <div class="quick-action-icon">
                    <i class="bi bi-cloud-arrow-up"></i>
                </div>
                <div class="quick-action-text">
                    Bulk<br>Upload
                </div>
            </a>

            <!-- Action 6 -->
            <a href="{{ route('vendor.marketing') }}" class="quick-action-tile">
                <div class="quick-action-icon">
                    <i class="bi bi-megaphone"></i>
                </div>
                <div class="quick-action-text">
                    Promotions
                </div>
            </a>

            <!-- Action 7 -->
            <a href="{{ route('vendor.store.profile') }}" class="quick-action-tile">
                <div class="quick-action-icon">
                    <i class="bi bi-gear"></i>
                </div>
                <div class="quick-action-text">
                    Store<br>Settings
                </div>
            </a>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ---------------- Real Sales Overview Spline Area Chart ----------------
    const ctxSales = document.getElementById('salesOverviewChart').getContext('2d');
    
    const gradientSales = ctxSales.createLinearGradient(0, 0, 0, 240);
    gradientSales.addColorStop(0, 'rgba(11, 123, 88, 0.22)');
    gradientSales.addColorStop(1, 'rgba(11, 123, 88, 0.00)');

    const chartDates = {!! json_encode($chartDates ?? []) !!};
    const chartSales = {!! json_encode($chartSales ?? []) !!};

    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: chartDates,
            datasets: [{
                label: 'Sales (₹)',
                data: chartSales,
                borderColor: '#0B7B58',
                borderWidth: 2.5,
                backgroundColor: gradientSales,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0B7B58',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { family: 'Inter', size: 12 },
                    bodyFont: { family: 'Inter', size: 12, weight: '600' },
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return ' Sales: ₹' + context.parsed.y.toLocaleString('en-IN');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        color: '#94A3B8'
                    }
                },
                y: {
                    min: 0,
                    ticks: {
                        font: { family: 'Inter', size: 11 },
                        color: '#94A3B8',
                        callback: function(value) {
                            if (value === 0) return '₹0';
                            return '₹' + (value >= 1000 ? (value / 1000) + 'K' : value);
                        }
                    },
                    grid: {
                        color: '#F1F5F9'
                    }
                }
            }
        }
    });

    // ---------------- Real Orders Overview Donut Chart ----------------
    const ctxOrders = document.getElementById('ordersOverviewChart').getContext('2d');
    
    const delivered = {{ (int) ($orderCounts['delivered'] ?? 0) }};
    const inTransit = {{ (int) ($orderCounts['in_transit'] ?? 0) }};
    const pending = {{ (int) ($orderCounts['pending'] ?? 0) }};
    const cancelled = {{ (int) ($orderCounts['cancelled'] ?? 0) }};
    const totalOrdersCount = {{ (int) ($stats['total_orders'] ?? 0) }};

    const donutData = totalOrdersCount > 0 ? [delivered, inTransit, pending, cancelled] : [1];
    const donutColors = totalOrdersCount > 0 ? ['#10B981', '#F59E0B', '#3B82F6', '#EF4444'] : ['#E2E8F0'];

    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw: function(chart) {
            const width = chart.width;
            const height = chart.height;
            const ctx = chart.ctx;
            ctx.restore();
            
            ctx.font = '500 11px Inter';
            ctx.fillStyle = '#64748B';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('Total Orders', width / 2, height / 2 - 8);

            ctx.font = '800 18px Inter';
            ctx.fillStyle = '#0F172A';
            ctx.fillText(totalOrdersCount.toString(), width / 2, height / 2 + 12);
            ctx.save();
        }
    };

    new Chart(ctxOrders, {
        type: 'doughnut',
        data: {
            labels: totalOrdersCount > 0 ? ['Delivered', 'In Transit', 'Pending', 'Cancelled'] : ['No Orders'],
            datasets: [{
                data: donutData,
                backgroundColor: donutColors,
                borderWidth: 2,
                borderColor: '#FFFFFF',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: totalOrdersCount > 0,
                    backgroundColor: '#0F172A',
                    padding: 8,
                    cornerRadius: 6,
                    bodyFont: { family: 'Inter', size: 12 }
                }
            }
        },
        plugins: [centerTextPlugin]
    });
});
</script>
@endpush
