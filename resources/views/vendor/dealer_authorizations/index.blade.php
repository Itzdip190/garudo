@extends('layouts.vendor')

@section('title', 'My Brand Dealerships & Authorizations')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">My Brand Dealerships</h4>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">
                {{ $stats['verified'] }} Verified Brand Authorizations
            </span>
        </div>
        <p class="text-muted mb-0 small">Manage your official Brand Dealerships, download Garudo Authorized Dealer Digital Certificates, and gain verified selling rights.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('vendor.brand-registry.index') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-award me-1"></i> Register as Brand OEM
        </a>
        <a href="{{ route('vendor.dealer-authorizations.apply') }}" class="btn btn-success btn-sm px-3 fw-bold" style="background-color: var(--garudo-primary); border: none; border-radius: 8px;">
            <i class="bi bi-patch-plus me-1"></i> Apply for Brand Authorization
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- STATS -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);">
            <div class="text-success small fw-bold text-uppercase">Verified Dealerships</div>
            <div class="fs-4 fw-bold text-success mt-1">{{ $stats['verified'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);">
            <div class="text-warning small fw-bold text-uppercase">Pending Review</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);">
            <div class="text-danger small fw-bold text-uppercase">Rejected / Inactive</div>
            <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['rejected'] }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm p-3 rounded-3" style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);">
            <div class="text-muted small fw-bold text-uppercase">Total Applications</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
        </div>
    </div>
</div>

<!-- AUTHORIZATIONS LIST -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-shield-check text-success me-2"></i>My Authorized Brand Partnerships
        </h6>
        <span class="badge bg-light text-muted border">{{ $authorizations->total() }} Total</span>
    </div>
    <div class="card-body p-0">
        @if($authorizations->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">Brand</th>
                            <th>Brand Name</th>
                            <th>Dealership Tier</th>
                            <th>Territory Coverage</th>
                            <th>Agreement Proof</th>
                            <th>Status & Certificate</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authorizations as $auth)
                            <tr>
                                <td>
                                    @if($auth->brand->logo)
                                        <img src="{{ asset('storage/' . $auth->brand->logo) }}" alt="{{ $auth->brand->name }}" class="rounded border p-1 bg-white" style="width: 44px; height: 44px; object-fit: contain;">
                                    @else
                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 44px; height: 44px;">
                                            {{ substr($auth->brand->name, 0, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $auth->brand->name }}</div>
                                    <small class="text-muted">
                                        @if($auth->brand->ownerVendor)
                                            OEM: {{ $auth->brand->ownerVendor->company_name }}
                                        @else
                                            Platform Brand
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $auth->formatted_type }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $auth->formatted_territory }}</div>
                                    <small class="text-muted">
                                        {{ $auth->is_lifetime_valid ? 'Lifetime Validity' : ($auth->valid_until ? 'Valid till ' . $auth->valid_until->format('d M Y') : 'Standard') }}
                                    </small>
                                </td>
                                <td>
                                    @if($auth->authorization_doc_path)
                                        <a href="{{ asset('storage/' . $auth->authorization_doc_path) }}" target="_blank" class="small text-primary text-decoration-none">
                                            <i class="bi bi-file-earmark-pdf-fill me-1 text-danger"></i>View Letter
                                        </a>
                                    @else
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($auth->garudo_approval_status === 'verified')
                                        <span class="badge bg-success"><i class="bi bi-patch-check-fill me-1"></i>Verified Dealer</span>
                                        @if($auth->certificate_number)
                                            <code class="d-block small mt-1 text-primary fw-bold">{{ $auth->certificate_number }}</code>
                                        @endif
                                    @elseif($auth->garudo_approval_status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Verification in Progress</span>
                                    @elseif($auth->garudo_approval_status === 'suspended')
                                        <span class="badge bg-danger"><i class="bi bi-slash-circle me-1"></i>Suspended</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                                        @if($auth->rejection_reason)
                                            <small class="text-danger d-block mt-1" style="font-size: 0.72rem;">{{ Str::limit($auth->rejection_reason, 40) }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($auth->garudo_approval_status === 'verified')
                                        <a href="{{ route('vendor.dealer-authorizations.certificate', $auth->id) }}" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                                            <i class="bi bi-award-fill me-1"></i>Digital Certificate
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled>
                                            <i class="bi bi-clock me-1"></i>Pending
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="display-6 text-muted mb-3"><i class="bi bi-diagram-3"></i></div>
                <h6 class="fw-bold text-dark">No Brand Dealerships Yet</h6>
                <p class="text-muted small mb-3">Apply for brand authorization to sell certified industrial materials and display verified badges.</p>
                <a href="{{ route('vendor.dealer-authorizations.apply') }}" class="btn btn-success btn-sm px-4 rounded-pill fw-semibold" style="background-color: var(--garudo-primary); border: none;">
                    <i class="bi bi-patch-plus me-1"></i> Apply for Brand Authorization
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
