@extends('layouts.frontend')

@section('title', 'Brand Authorized Dealer Verification - GARUDO B2B Trust Portal')

@section('content')
<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            
            @if($isValid && $authRecord)
                <!-- Authenticated Dealer Trust Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Top Status Banner -->
                    <div class="p-4 text-white text-center" style="background: linear-gradient(135deg, #0B4D3C 0%, #15803D 100%);">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white text-success rounded-circle shadow-sm mb-3" style="width: 72px; height: 72px;">
                            <i class="bi bi-patch-check-fill fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Official Authorized Brand Partner</h3>
                        <p class="text-white-50 mb-0 small">This Brand Dealership accreditation is authenticated and active on the GARUDO National Trust Registry.</p>
                    </div>

                    <div class="card-body p-4 p-md-5 bg-white">
                        <!-- Key Brand & Dealer Credentials Grid -->
                        <div class="row g-4 mb-4 pb-4 border-bottom align-items-center">
                            <div class="col-md-7">
                                <span class="text-muted small d-block">Authorized Business Entity</span>
                                <h4 class="fw-bold text-dark mb-1">{{ $authRecord->vendor->company_name }}</h4>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                        <i class="bi bi-shield-check me-1"></i> Garudo Verified Dealer
                                    </span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                        {{ $authRecord->formatted_type }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-5 text-md-end">
                                <div class="p-2 border rounded bg-light d-inline-block text-center">
                                    @if($authRecord->brand->logo)
                                        <img src="{{ asset('storage/' . $authRecord->brand->logo) }}" alt="{{ $authRecord->brand->name }}" style="max-height: 45px; max-width: 120px; object-fit: contain;">
                                    @else
                                        <h5 class="fw-bold text-dark mb-0">{{ $authRecord->brand->name }}</h5>
                                    @endif
                                    <div class="small fw-bold text-muted mt-1">Brand OEM Partner</div>
                                </div>
                            </div>
                        </div>

                        <!-- Statutory & Dealership Parameters Grid -->
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">Certificate Reference Code</span>
                                    <strong class="font-monospace text-dark fs-6">{{ $authRecord->certificate_number }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">Authorized Territory Scope</span>
                                    <strong class="text-dark fs-6">{{ $authRecord->formatted_territory }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">GSTIN Number</span>
                                    <strong class="font-monospace text-dark">{{ $authRecord->vendor->gstin ?? 'VERIFIED ACTIVE' }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">Dealership Validity</span>
                                    @if($authRecord->is_lifetime_valid)
                                        <strong class="text-success"><i class="bi bi-infinity"></i> Lifetime Authorized</strong>
                                    @else
                                        <strong class="text-success">Valid till {{ $authRecord->valid_until?->format('d F, Y') ?? 'Active Contract' }}</strong>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Trust Seal Description -->
                        <div class="p-3 rounded-3 bg-light border mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="text-success fs-3"><i class="bi bi-shield-lock-fill"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Genuine Product & Warranty Assurance</h6>
                                    <small class="text-muted">Buyers purchasing <strong>{{ $authRecord->brand->name }}</strong> materials from <strong>{{ $authRecord->vendor->company_name }}</strong> are covered under official manufacturer warranty and Garudo Escrow Protection.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-2">
                            <a href="{{ route('store.show', $authRecord->vendor->company_slug ?? $authRecord->vendor->id) }}" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="bi bi-shop me-1"></i> View Dealer Storefront
                            </a>
                            <a href="{{ route('brands.show', $authRecord->brand->slug) }}" class="btn btn-success rounded-pill px-4" style="background-color: var(--garudo-primary); border: none;">
                                <i class="bi bi-box-seam me-1"></i> Browse {{ $authRecord->brand->name }} Catalog
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Unauthenticated / Expired / Invalid Alert -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="p-4 text-white text-center bg-danger">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white text-danger rounded-circle shadow-sm mb-3" style="width: 72px; height: 72px;">
                            <i class="bi bi-shield-x fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Dealership Credential Not Authenticated</h3>
                        <p class="text-white-50 mb-0 small">The certificate reference you scanned or entered could not be verified.</p>
                    </div>

                    <div class="card-body p-4 p-md-5 text-center bg-white">
                        <p class="text-muted mb-4">
                            {{ $errorMessage ?? 'This certificate is either expired, revoked by the brand owner, or not registered on Garudo B2B Trust Registry.' }}
                        </p>
                        <div class="p-3 bg-light rounded-3 font-monospace text-secondary mb-4 d-inline-block">
                            Searched Query: {{ $certificateNumber }}
                        </div>
                        <div>
                            <a href="{{ route('brands.index') }}" class="btn btn-dark rounded-pill px-4">
                                <i class="bi bi-arrow-left me-1"></i> Browse Verified Brand Directory
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
