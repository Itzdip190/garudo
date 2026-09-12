@extends('layouts.vendor')

@section('title', 'Brand Registry & OEM Management')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Brand Registry & OEM Verification</h4>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">
                <i class="bi bi-shield-check me-1"></i>Garudo Certified
            </span>
        </div>
        <p class="text-muted mb-0 small">Register your trademarked brands, protect intellectual property, manage authorized dealers, and earn the Verified OEM Badge.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('vendor.dealer-authorizations.index') }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-diagram-3 me-1"></i> My Dealership Authorizations
        </a>
        <a href="{{ route('vendor.brand-registry.create') }}" class="btn btn-success btn-sm px-3 fw-bold" style="background-color: var(--garudo-primary); border: none; border-radius: 8px;">
            <i class="bi bi-plus-circle me-1"></i> Register New Brand
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);">
            <div class="text-success small fw-bold text-uppercase">Authorized Brands</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['total_owned'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);">
            <div class="text-primary small fw-bold text-uppercase">Authorized Dealers</div>
            <div class="fs-4 fw-bold text-primary mt-1">{{ $stats['total_dealers'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);">
            <div class="text-warning small fw-bold text-uppercase">Pending Applications</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['pending_apps'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);">
            <div class="text-muted small fw-bold text-uppercase">Total Registered</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $applications->count() }}</div>
        </div>
    </div>
</div>

<!-- OWNED / VERIFIED BRANDS -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-award-fill text-warning me-2"></i>My Authorized Brands (Verified OEM)
        </h6>
        <span class="badge bg-light text-muted border">{{ $ownedBrands->count() }} Active</span>
    </div>
    <div class="card-body p-0">
        @if($ownedBrands->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">Logo</th>
                            <th>Brand Name</th>
                            <th>Trademark / Class</th>
                            <th>Catalog Policy</th>
                            <th>Dealers</th>
                            <th>Live Products</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ownedBrands as $brand)
                            <tr>
                                <td>
                                    @if($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="rounded border p-1 bg-white" style="width: 48px; height: 48px; object-fit: contain;">
                                    @else
                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 48px; height: 48px;">
                                            {{ substr($brand->name, 0, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                        {{ $brand->name }}
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0" style="font-size: 0.7rem;">
                                            <i class="bi bi-patch-check-fill"></i> Verified OEM
                                        </span>
                                    </div>
                                    <small class="text-muted">{{ $brand->country_of_origin ?? 'India' }} • Est. {{ $brand->year_established ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">TM: {{ $brand->trademark_number ?? 'Verified' }}</div>
                                    <small class="text-muted">Class {{ $brand->trademark_class ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($brand->is_protected)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-lock-fill me-1"></i>Protected (Gated)
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-unlock-fill me-1"></i>Open Catalog
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vendor.brand-registry.dealers', $brand->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-people-fill me-1"></i>{{ $brand->verified_dealer_authorizations_count ?? 0 }} Dealers
                                    </a>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $brand->products_count }}</span>
                                    <small class="text-muted d-block">Items Listed</small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('brands.show', $brand->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="View Public Brand Showcase">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>Showcase
                                        </a>
                                        <a href="{{ route('vendor.brand-registry.dealers', $brand->id) }}" class="btn btn-sm btn-dark rounded-pill px-3">
                                            Manage Network
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="display-6 text-muted mb-3"><i class="bi bi-award"></i></div>
                <h6 class="fw-bold text-dark">No Verified Brands Yet</h6>
                <p class="text-muted small mb-3">If you are an OEM, Manufacturer, or Trademark Holder, apply to register your brand on Garudo.</p>
                <a href="{{ route('vendor.brand-registry.create') }}" class="btn btn-success btn-sm px-4 rounded-pill fw-semibold" style="background-color: var(--garudo-primary); border: none;">
                    <i class="bi bi-plus-circle me-1"></i> Apply for Brand Registration
                </a>
            </div>
        @endif
    </div>
</div>

<!-- SUBMITTED APPLICATIONS & STATUS TRACKER -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Registration Application Tracker
        </h6>
        <span class="badge bg-light text-muted border">{{ $applications->count() }} Total</span>
    </div>
    <div class="card-body p-0">
        @if($applications->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Application ID</th>
                            <th>Brand Applied</th>
                            <th>Brand Type</th>
                            <th>Trademark No</th>
                            <th>Status</th>
                            <th>Submission Date</th>
                            <th class="text-end pe-4">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td>
                                    <code class="small text-dark fw-bold">#APP-BRD-{{ $app->id }}</code>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $app->brand_name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small">
                                        {{ ucwords(str_replace('_', ' ', $app->brand_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small fw-semibold text-dark">{{ $app->trademark_number ?? 'N/A' }}</span>
                                    <small class="text-muted d-block">Class {{ $app->trademark_class ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($app->status === 'approved')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Authorized</span>
                                    @elseif($app->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Under Review</span>
                                    @elseif($app->status === 'rejected')
                                        <span class="badge bg-danger"><i class="bi bi-x-circle-fill me-1"></i>Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($app->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $app->created_at->format('d M Y') }}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('vendor.brand-registry.show', $app->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i>Track Status
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 text-center text-muted small">
                No past brand registration applications submitted yet.
            </div>
        @endif
    </div>
</div>
@endsection
