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

<!-- ---------------- STOREFRONT LAUNCH CONTROL & HEADER TAGLINE WIDGET ---------------- -->
<div class="garudo-card p-4 mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0b1120 0%, #1e1b4b 60%, #0f172a 100%); border-radius: 16px; position: relative; overflow: hidden; border: 1px solid rgba(245, 158, 11, 0.25) !important;">
    <!-- Subtle glow orb -->
    <div style="position: absolute; right: -30px; top: -30px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(245, 158, 11, 0.18) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 position-relative" style="z-index: 1;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; background: rgba(245, 158, 11, 0.15); border: 1.5px solid rgba(245, 158, 11, 0.45); box-shadow: 0 0 20px rgba(245, 158, 11, 0.2);">
                <i class="bi bi-rocket-takeoff-fill fs-3 text-warning"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <h5 class="fw-bold text-white mb-0">Storefront Launch Control & Header Tagline</h5>
                    <span id="launchStatusBadge" class="badge {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'bg-warning text-dark' : 'bg-success text-white' }} px-2.5 py-1 fw-bold rounded-pill" style="font-size: 11px;">
                        <i class="bi {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'bi-hourglass-split' : 'bi-check-circle-fill' }} me-1"></i>
                        {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'PRE-LAUNCH MODE ACTIVE' : 'LIVE MARKETPLACE (PURCHASING ACTIVE)' }}
                    </span>
                </div>
                <p id="launchStatusDescription" class="text-white-50 mb-0 small" style="max-width: 680px;">
                    @if(($launchSettings['launch_mode_active'] ?? '1') == '1')
                        Official Launching on <strong>{{ $launchSettings['launch_date'] ?? '20th October' }}</strong>. Launch banner strip is visible on website. Product purchases & checkout display launch notice.
                    @else
                        Storefront is officially LIVE! Launch announcement strip is hidden, and full product purchasing & checkout are enabled.
                    @endif
                </p>
                <div class="d-flex align-items-center gap-3 mt-2 text-white-50 small flex-wrap">
                    <span><i class="bi bi-tag-fill text-warning me-1"></i> Header Tagline: <strong class="text-white">{{ $launchSettings['header_tagline'] ?? "India's Leading B2B Commerce & Wholesale Network" }}</strong></span>
                    <span><i class="bi bi-calendar-event text-warning me-1"></i> Launch Date: <strong class="text-white">{{ $launchSettings['launch_date'] ?? '20th October' }}</strong></span>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2.5 flex-wrap">
            <!-- Toggle Switch Container -->
            <div class="d-flex align-items-center gap-2 bg-dark bg-opacity-60 px-3 py-2 rounded-3 border border-secondary border-opacity-30 shadow-sm">
                <span class="text-white small fw-bold">Pre-Launch Mode:</span>
                <div class="form-check form-switch m-0 d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" role="switch" id="dashboardLaunchToggle" style="width: 2.85em; height: 1.45em; cursor: pointer;" {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'checked' : '' }}>
                </div>
                <span id="toggleStateLabel" class="small fw-bold {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'text-warning' : 'text-success' }}" style="min-width: 28px;">
                    {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'ON' : 'OFF' }}
                </span>
            </div>

            <!-- Settings / Edit Modal Trigger -->
            <button type="button" class="btn btn-warning text-dark fw-bold btn-sm px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#editLaunchSettingsModal">
                <i class="bi bi-sliders"></i> Edit Date & Tagline
            </button>
        </div>
    </div>
</div>

<!-- Modal: Edit Launch Settings & Header Taglines -->
<div class="modal fade" id="editLaunchSettingsModal" tabindex="-1" aria-labelledby="editLaunchSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('admin.launch-mode.update') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf
            <div class="modal-header bg-dark text-white border-0 py-3 px-4" style="background: linear-gradient(135deg, #0b1120 0%, #1e1b4b 100%) !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-rocket-takeoff-fill text-warning fs-5"></i>
                    <h5 class="modal-title fw-bold text-white mb-0" id="editLaunchSettingsModalLabel">Configure Storefront Launch & Header Tagline</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="bg-white p-3 rounded-3 border mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <label class="form-label fw-bold text-dark mb-0">Pre-Launch Mode Status</label>
                            <div class="text-muted small">When ON: strip is displayed & product purchases display launch notice. When OFF: strip is hidden & direct purchasing starts.</div>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" name="launch_mode_active" value="1" id="modalLaunchModeSwitch" style="width: 2.8em; height: 1.4em; cursor: pointer;" {{ ($launchSettings['launch_mode_active'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Official Launch Date</label>
                        <input type="text" name="launch_date" class="form-control" value="{{ old('launch_date', $launchSettings['launch_date'] ?? '20th October') }}" placeholder="e.g. 20th October 2026" required>
                        <small class="text-muted">Displayed across top strip and product launch notices.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-dark">Header Tagline (Desktop)</label>
                        <input type="text" name="header_tagline" class="form-control" value="{{ old('header_tagline', $launchSettings['header_tagline'] ?? "India's Leading B2B Commerce & Wholesale Network") }}" placeholder="Tagline without building material" required>
                        <small class="text-muted">Replaces old header tagline in top bar.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Header Tagline (Mobile)</label>
                    <input type="text" name="header_tagline_mobile" class="form-control" value="{{ old('header_tagline_mobile', $launchSettings['header_tagline_mobile'] ?? "India's #1 B2B Commerce & Wholesale Network") }}" placeholder="Compact mobile tagline" required>
                    <small class="text-muted">Displayed on mobile screens (&lt; 992px).</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">Top Strip Announcement Message</label>
                    <input type="text" name="launch_strip_text" class="form-control" value="{{ old('launch_strip_text', $launchSettings['launch_strip_text'] ?? 'Official Launching on 20th October | Pre-Launch Preview Active') }}" required>
                    <small class="text-muted">Text shown inside the top gradient banner ribbon.</small>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold text-dark">Pre-Launch Notice Message (When purchasing/buying)</label>
                    <textarea name="launch_notice_message" class="form-control" rows="2" required>{{ old('launch_notice_message', $launchSettings['launch_notice_message'] ?? 'Official Launching on 20th October. Direct order purchasing will start on our official launch day!') }}</textarea>
                    <small class="text-muted">Shown in modal when customer attempts to buy or checkout before official launch.</small>
                </div>
            </div>
            <div class="modal-footer bg-white border-top py-3 px-4">
                <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Save Launch & Tagline Settings
                </button>
            </div>
        </form>
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

        // Pre-Launch Mode Instant Switcher
        const launchToggle = document.getElementById('dashboardLaunchToggle');
        if (launchToggle) {
            launchToggle.addEventListener('change', function() {
                const isChecked = this.checked;
                const badge = document.getElementById('launchStatusBadge');
                const desc = document.getElementById('launchStatusDescription');
                const label = document.getElementById('toggleStateLabel');
                const modalSwitch = document.getElementById('modalLaunchModeSwitch');

                if (modalSwitch) modalSwitch.checked = isChecked;
                if (label) {
                    label.textContent = isChecked ? 'ON' : 'OFF';
                    label.className = 'small fw-bold ' + (isChecked ? 'text-warning' : 'text-success');
                }

                // Send AJAX toggle request
                fetch("{{ route('admin.launch-mode.toggle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ active: isChecked ? 1 : 0 })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        if (isChecked) {
                            if (badge) {
                                badge.className = 'badge bg-warning text-dark px-2.5 py-1 fw-bold rounded-pill';
                                badge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> PRE-LAUNCH MODE ACTIVE';
                            }
                            if (desc) {
                                desc.innerHTML = 'Official Launching on <strong>{{ $launchSettings['launch_date'] ?? '20th October' }}</strong>. Launch banner strip is visible on website. Product purchases & checkout display launch notice.';
                            }
                        } else {
                            if (badge) {
                                badge.className = 'badge bg-success text-white px-2.5 py-1 fw-bold rounded-pill';
                                badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> LIVE MARKETPLACE (PURCHASING ACTIVE)';
                            }
                            if (desc) {
                                desc.innerHTML = 'Storefront is officially LIVE! Launch announcement strip is hidden, and full product purchasing & checkout are enabled.';
                            }
                        }
                    } else {
                        alert(d.message || 'Failed to update launch mode.');
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Network error while toggling launch mode.');
                    location.reload();
                });
            });
        }
    });
</script>
@endpush
