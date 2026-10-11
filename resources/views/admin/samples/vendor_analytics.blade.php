@extends('layouts.admin')

@section('title', 'Vendor Sample Performance & Conversion Analytics')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="page-header-title mb-0">Vendor Sample Performance & Conversion Scorecard</h4>
            <span class="badge bg-warning bg-opacity-25 text-dark rounded-pill px-3 py-1 small fw-bold">
                <i class="bi bi-graph-up-arrow me-1"></i>ROI & Conversions
            </span>
        </div>
        <p class="page-header-subtitle mb-0">Deep monitoring of all sellers: how many sample requests they receive, fulfillment speed, and conversion rate to commercial bulk purchase orders.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('admin.samples.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i> All Sample Requests Monitor
        </a>
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-garudo-primary btn-sm px-3 fw-bold" style="border-radius: 10px;">
            <i class="bi bi-shop-window me-1"></i> Manage Vendors
        </a>
    </div>
</div>

<!-- PLATFORM SUMMARY KPI METRICS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #0B4D3C;">
            <div class="kpi-label">Active Sampling Vendors</div>
            <div class="kpi-value text-dark">{{ $totalVendorsOfferingSamples }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Vendors with sample-enabled items</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #0284C7;">
            <div class="kpi-label text-primary">Total Sample Requests</div>
            <div class="kpi-value text-primary">{{ $totalMarketplaceSampleRequests }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Received across all sellers</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #7C3AED;">
            <div class="kpi-label" style="color: #7C3AED;">Bulk Orders Won</div>
            <div class="kpi-value" style="color: #6D28D9;">{{ $totalMarketplaceConversions }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Sample recipients who bought bulk</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card kpi-card" style="border-left: 4px solid #10B981;">
            <div class="kpi-label text-success">Global Conversion Rate</div>
            <div class="kpi-value text-success">{{ $globalConversionRate }}%</div>
            <small class="text-muted" style="font-size: 0.72rem;">Average Marketplace Conversion</small>
        </div>
    </div>
</div>

<!-- VENDOR PERFORMANCE TABLE -->
<div class="garudo-card">
    <div class="p-3 border-bottom">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <h6 class="fw-bold text-dark mb-0">
                <i class="bi bi-trophy-fill text-warning me-2"></i>Vendor-by-Vendor Sampling Scorecard
            </h6>

            <!-- Search & Sort Controls -->
            <form action="{{ route('admin.samples.vendor-analytics') }}" method="GET" class="d-flex flex-wrap gap-2">
                <div class="input-group input-group-sm" style="min-width: 240px;">
                    <input type="text" name="q" class="form-control" placeholder="Search Vendor Name, Email, Phone..." value="{{ $search }}">
                    <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
                    @if($search)
                        <a href="{{ route('admin.samples.vendor-analytics') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                    @endif
                </div>

                <select name="sort" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                    <option value="requests_desc" {{ $sortBy === 'requests_desc' ? 'selected' : '' }}>Sort: Most Sample Requests</option>
                    <option value="conversion_desc" {{ $sortBy === 'conversion_desc' ? 'selected' : '' }}>Sort: Highest Conversion Rate</option>
                    <option value="pending_desc" {{ $sortBy === 'pending_desc' ? 'selected' : '' }}>Sort: Most Pending Actions</option>
                    <option value="revenue_desc" {{ $sortBy === 'revenue_desc' ? 'selected' : '' }}>Sort: Highest Sample Revenue</option>
                </select>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3">Vendor / Supplier Identity</th>
                    <th class="text-center">Sample Catalog</th>
                    <th class="text-center">Requests Received</th>
                    <th class="text-center">Pending / In Transit</th>
                    <th class="text-center">Delivered</th>
                    <th class="text-center" style="min-width: 170px;">Conversion to Bulk Orders</th>
                    <th class="text-center">Paid Revenue</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $vendor)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 38px; height: 38px;">
                                    {{ strtoupper(substr($vendor->company_name ?? $vendor->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                        {{ $vendor->company_name ?? $vendor->name }}
                                    </a>
                                    <div class="text-muted small" style="font-size: 0.72rem;">
                                        <i class="bi bi-person me-1"></i>{{ $vendor->name }} | <i class="bi bi-telephone me-1 text-success"></i>{{ $vendor->phone }}
                                    </div>
                                    @if($vendor->is_verified || $vendor->approval_status === 'approved')
                                        <span class="badge bg-success-subtle text-success border border-success mt-1" style="font-size: 0.65rem;">
                                            <i class="bi bi-patch-check-fill me-1"></i>Verified Partner
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1 fw-bold fs-6">
                                {{ $vendor->sample_enabled_products }}
                            </span>
                            <span class="text-muted d-block small" style="font-size: 0.7rem;">Products</span>
                        </td>

                        <td class="text-center">
                            <strong class="fs-5 text-dark">{{ $vendor->total_samples_received }}</strong>
                            <span class="text-muted d-block small" style="font-size: 0.7rem;">Total Requests</span>
                        </td>

                        <td class="text-center">
                            @if($vendor->pending_samples > 0)
                                <span class="badge bg-warning text-dark">{{ $vendor->pending_samples }} Pending</span>
                            @else
                                <span class="text-muted small">0 Pending</span>
                            @endif
                            <div class="small text-primary mt-1" style="font-size: 0.72rem;">
                                {{ $vendor->dispatched_samples }} In Transit
                            </div>
                        </td>

                        <td class="text-center">
                            <strong class="text-success fs-6">{{ $vendor->delivered_samples }}</strong>
                            @if($vendor->rejected_samples > 0)
                                <span class="text-danger d-block small" style="font-size: 0.7rem;">{{ $vendor->rejected_samples }} Declined</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-dark fs-6">{{ $vendor->converted_samples }} Orders</span>
                                <span class="badge bg-success-subtle text-success border border-success fw-bold">{{ $vendor->conversion_rate }}% Rate</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($vendor->conversion_rate, 100) }}%;" aria-valuenow="{{ $vendor->conversion_rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>

                        <td class="text-center">
                            <strong class="text-dark">₹{{ number_format($vendor->paid_sample_revenue, 2) }}</strong>
                            <span class="text-muted d-block small" style="font-size: 0.7rem;">Sample Fees</span>
                        </td>

                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.samples.index', ['vendor_id' => $vendor->id]) }}" class="btn btn-outline-dark btn-sm px-2 py-1" title="View Vendor's Sample Requests">
                                    <i class="bi bi-box2-heart"></i> Samples
                                </a>
                                <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-light border btn-sm px-2 py-1" title="View Vendor Profile">
                                    <i class="bi bi-eye"></i> Profile
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-graph-down text-muted" style="font-size: 3rem;"></i>
                            <h6 class="fw-bold mt-2 text-dark">No Vendor Records Found</h6>
                            <p class="small mb-0">No vendors matching your search filter.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
