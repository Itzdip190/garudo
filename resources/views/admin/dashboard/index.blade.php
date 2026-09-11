@extends('layouts.admin')

@section('title', 'Super Admin Command Center')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Garudo Command Center</h1>
        <p class="page-header-subtitle mb-0">Real-time marketplace oversight, verification queues & order management.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="btn btn-warning btn-sm text-dark fw-bold px-3 rounded-pill shadow-sm">
            <i class="bi bi-person-check-fill me-1"></i> Verify Vendors ({{ $stats['pending_vendors'] }})
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="btn btn-danger btn-sm text-white fw-bold px-3 rounded-pill shadow-sm">
            <i class="bi bi-shield-exclamation me-1"></i> Moderate Products ({{ $stats['pending_products'] }})
        </a>
    </div>
</div>

<!-- ---------------- KPI STATS GRID ---------------- -->
<div class="row g-3 mb-4">
    <!-- GMV -->
    <div class="col-sm-6 col-xl-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">Gross Merchandise Value</div>
                    <div class="kpi-value text-success">₹{{ number_format($stats['total_gmv'], 2) }}</div>
                    <div class="small text-muted mt-1"><i class="bi bi-arrow-up-right text-success me-1"></i>Total Settled B2B Sales</div>
                </div>
                <div class="kpi-icon-wrap bg-success bg-opacity-10 text-success">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendors -->
    <div class="col-sm-6 col-xl-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">Active Sellers / Vendors</div>
                    <div class="kpi-value">{{ $stats['active_vendors'] }} <span class="fs-6 text-muted fw-normal">/ {{ $stats['total_vendors'] }}</span></div>
                    <div class="small mt-1">
                        @if($stats['pending_vendors'] > 0)
                            <span class="text-warning fw-bold"><i class="bi bi-exclamation-circle-fill me-1"></i>{{ $stats['pending_vendors'] }} awaiting review</span>
                        @else
                            <span class="text-success"><i class="bi bi-check-all me-1"></i>All accounts approved</span>
                        @endif
                    </div>
                </div>
                <div class="kpi-icon-wrap bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Buyers -->
    <div class="col-sm-6 col-xl-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">B2B Verified Buyers</div>
                    <div class="kpi-value">{{ $stats['total_customers'] }}</div>
                    <div class="small text-muted mt-1"><i class="bi bi-building me-1"></i>Corporate Buyers & Contractors</div>
                </div>
                <div class="kpi-icon-wrap bg-info bg-opacity-10 text-info">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-sm-6 col-xl-3">
        <div class="garudo-card kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="kpi-label">Total B2B Orders</div>
                    <div class="kpi-value text-primary">{{ $stats['total_orders'] }}</div>
                    <div class="small text-muted mt-1"><i class="bi bi-check2-circle text-success me-1"></i>{{ $stats['completed_orders'] }} delivered orders</div>
                </div>
                <div class="kpi-icon-wrap bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ---------------- CHARTS & WORKFLOW SECTION ---------------- -->
<div class="row g-4 mb-4">
    <!-- Sales Chart -->
    <div class="col-lg-8">
        <div class="garudo-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Marketplace Trading & Revenue Velocity</h5>
                    <p class="text-muted small mb-0">Live volume generated across industrial categories.</p>
                </div>
                <span class="badge bg-light text-dark border"><i class="bi bi-circle-fill text-success me-1" style="font-size: 6px;"></i> Live Updates</span>
            </div>
            <div style="height: 260px;">
                <canvas id="marketplaceSalesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Platform Distribution Chart -->
    <div class="col-lg-4">
        <div class="garudo-card p-4 h-100">
            <h5 class="fw-bold text-dark mb-1">Platform Entities Breakdown</h5>
            <p class="text-muted small mb-3">Sellers, Buyers & Catalog status.</p>
            <div style="height: 220px;" class="d-flex align-items-center justify-content-center">
                <canvas id="entitiesPieChart"></canvas>
            </div>
            <div class="d-flex justify-content-around text-center mt-3 pt-2 border-top">
                <div>
                    <div class="fw-bold text-dark">{{ $stats['total_vendors'] }}</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Sellers</div>
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $stats['total_customers'] }}</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Buyers</div>
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $stats['total_products'] }}</div>
                    <div class="text-muted small" style="font-size: 0.7rem;">Products</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ---------------- URGENT ACTION QUEUES ---------------- -->
<div class="row g-4 mb-4">
    <!-- Pending Vendors Queue -->
    <div class="col-lg-6">
        <div class="garudo-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Vendors Awaiting KYC & Approval</h6>
                        <small class="text-muted">Review business documents before allowing live sales</small>
                    </div>
                </div>
                <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="btn btn-light btn-sm text-primary fw-semibold">View All</a>
            </div>

            @if($pendingVendors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @foreach($pendingVendors as $vendor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($vendor->company_name ?? $vendor->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $vendor->company_name ?? $vendor->name }}</div>
                                                <div class="text-muted small">GSTIN: <code>{{ $vendor->gstin ?? 'Not Provided' }}</code></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Review</a>
                                            <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">Approve</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-patch-check-fill text-success fs-1 mb-2 d-block"></i>
                    <div class="fw-bold">No Pending Vendor Approvals</div>
                    <div class="small">All registered vendors are up to date.</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Pending Products Queue -->
    <div class="col-lg-6">
        <div class="garudo-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-danger bg-opacity-10 text-danger p-2 rounded-3">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Products Awaiting Catalog Review</h6>
                        <small class="text-muted">Verify specifications & compliance prior to publishing</small>
                    </div>
                </div>
                <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="btn btn-light btn-sm text-primary fw-semibold">View All</a>
            </div>

            @if($pendingProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <tbody>
                            @foreach($pendingProducts as $product)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ \Illuminate\Support\Str::limit($product->name, 35) }}</div>
                                        <div class="text-muted small">By {{ $product->vendor->company_name ?? 'Unknown Vendor' }} • ₹{{ number_format($product->selling_price, 2) }}</div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Inspect</a>
                                            <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Publish</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-2 d-block"></i>
                    <div class="fw-bold">No Products Pending Review</div>
                    <div class="small">The storefront catalog is completely moderated.</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ---------------- LIVE ACTIVITY & NOTIFICATION FEED ---------------- -->
<div class="garudo-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                <i class="bi bi-bell-fill fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Live Platform Activity & Alerts</h5>
                <p class="text-muted small mb-0">Instant notifications on buyer orders, seller updates and catalog changes.</p>
            </div>
        </div>
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            Notification Center <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    @if(isset($liveActivity) && count($liveActivity) > 0)
        <div class="row g-3">
            @foreach($liveActivity as $activity)
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 bg-light rounded-3 h-100 d-flex align-items-start gap-3 border">
                        <div class="bg-white border rounded-3 p-2 shadow-sm fs-5 flex-shrink-0">
                            <i class="bi {{ $activity->icon ?? 'bi-bell-fill text-primary' }}"></i>
                        </div>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="fw-bold text-dark text-truncate small">{{ $activity->title }}</div>
                            <div class="text-muted small text-truncate">{{ $activity->message }}</div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>{{ $activity->created_at->diffForHumans() }}</span>
                                @if($activity->link && $activity->link !== '#')
                                    <a href="{{ $activity->link }}" class="btn btn-link btn-sm p-0 text-primary text-decoration-none fw-semibold" style="font-size: 0.75rem;">
                                        Inspect <i class="bi bi-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-3 text-muted small">No new activity alerts.</div>
    @endif
</div>

<!-- ---------------- RECENT ORDERS OVERVIEW ---------------- -->
<div class="garudo-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-dark mb-1">Recent B2B Orders & Fulfillment</h5>
            <p class="text-muted small mb-0">Real-time purchase orders placed across all categories.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-garudo-primary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> Manage All Orders
        </a>
    </div>

    @if($recentOrders->count() > 0)
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Buyer Company</th>
                        <th>Date & Time</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Fulfillment</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
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
                            <td class="text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</td>
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
                                    <i class="bi bi-eye-fill me-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-4 text-muted">
            <i class="bi bi-inbox fs-1 mb-2 d-block"></i>
            <div>No orders recorded yet.</div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sales Line Chart
        const ctxSales = document.getElementById('marketplaceSalesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                datasets: [{
                    label: 'GMV (₹ Lakhs)',
                    data: [12.5, 18.2, 24.8, 32.1, 45.6, 58.4, 72.0],
                    borderColor: '#0B4D3C',
                    backgroundColor: 'rgba(16, 185, 129, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#10B981',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Entities Doughnut Chart
        const ctxEntities = document.getElementById('entitiesPieChart').getContext('2d');
        new Chart(ctxEntities, {
            type: 'doughnut',
            data: {
                labels: ['Sellers', 'Buyers', 'Products'],
                datasets: [{
                    data: [
                        {{ max($stats['total_vendors'], 1) }},
                        {{ max($stats['total_customers'], 1) }},
                        {{ max($stats['total_products'], 1) }}
                    ],
                    backgroundColor: ['#0B4D3C', '#0284C7', '#F59E0B'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
