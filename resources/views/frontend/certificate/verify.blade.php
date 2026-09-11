@extends('layouts.frontend')

@section('title', 'Accreditation Verification - GARUDO B2B Trust Portal')

@section('content')
<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            
            @if($certificate && $certificate->is_valid)
                <!-- Authenticated Partner Trust Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Top Status Banner -->
                    <div class="p-4 text-white text-center" style="background: linear-gradient(135deg, #0B4D3C 0%, #15803D 100%);">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white text-success rounded-circle shadow-sm mb-3" style="width: 72px; height: 72px;">
                            <i class="bi bi-patch-check-fill fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Official Verified Channel Partner</h3>
                        <p class="text-white-50 mb-0 small">This certificate credential is authenticated and active on the GARUDO National Trust Registry.</p>
                    </div>

                    <div class="card-body p-4 p-md-5 bg-white">
                        <!-- Key Certificate Credentials Grid -->
                        <div class="row g-4 mb-4 pb-4 border-bottom">
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Verified Business Entity</span>
                                <h4 class="fw-bold text-dark mb-1">{{ $certificate->vendor->company_name }}</h4>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                    <i class="bi bi-shield-check me-1"></i> Statutory KYC Verified
                                </span>
                            </div>

                            <div class="col-md-6 text-md-end">
                                <span class="text-muted small d-block">Certificate Reference</span>
                                <div class="fs-5 fw-bold font-monospace text-dark">{{ $certificate->certificate_number }}</div>
                                <span class="badge bg-light text-secondary border mt-1">Vendor ID: #GAR-V{{ str_pad($certificate->vendor_id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>

                        <!-- Statutory Parameters Table -->
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">GSTIN Number</span>
                                    <strong class="font-monospace text-dark fs-6">{{ $certificate->vendor->gstin ?? 'VERIFIED ACTIVE' }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">Business Classification</span>
                                    <strong class="text-dark fs-6">{{ ucfirst($certificate->vendor->business_type ?? 'Manufacturer') }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">Accreditation Issue Date</span>
                                    <strong class="text-dark">{{ $certificate->issue_date->format('d F, Y') }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded-3 bg-light border">
                                    <span class="text-muted small d-block">Valid Until</span>
                                    <strong class="text-success">{{ $certificate->expiry_date->format('d F, Y') }}</strong>
                                    <span class="badge bg-success ms-2 small">{{ $certificate->days_until_expiry }} Days Remaining</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cryptographic Seal Info -->
                        <div class="p-3 rounded-3 bg-light border mb-4">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-lock-fill text-success"></i>
                                <span class="fw-bold small text-dark">Cryptographic Digital Signature</span>
                            </div>
                            <div class="font-monospace small text-muted text-break">
                                SHA256: {{ $certificate->digital_signature_hash }}
                            </div>
                        </div>

                        <!-- CTA Bar -->
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3">
                            <div class="small text-muted">
                                <i class="bi bi-building-check text-success me-1"></i> Issued by GARUDO Directorate of Quality & Trust
                            </div>
                            <a href="{{ route('products.index', ['vendor_id' => $certificate->vendor_id]) }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" style="background-color: var(--garudo-primary); border:none;">
                                View Verified Seller Catalog <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

            @elseif($certificate && $certificate->is_expired)
                <!-- Expired Certificate Notice -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="p-4 bg-warning text-dark text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white text-warning rounded-circle shadow-sm mb-3" style="width: 72px; height: 72px;">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Accreditation Expired</h3>
                        <p class="text-dark mb-0 small">This certificate was issued to <strong>{{ $certificate->vendor->company_name }}</strong> but expired on {{ $certificate->expiry_date->format('d M, Y') }}.</p>
                    </div>
                </div>

            @else
                <!-- Invalid Certificate Search Error -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="p-4 bg-danger text-white text-center">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white text-danger rounded-circle shadow-sm mb-3" style="width: 72px; height: 72px;">
                            <i class="bi bi-shield-x fs-1"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Unverified or Invalid Certificate</h3>
                        <p class="text-white-50 mb-0 small">No active certificate was found for record number <code>{{ $searchedNumber }}</code>.</p>
                    </div>
                    <div class="card-body p-4 text-center">
                        <p class="text-muted">Please ensure you scanned the correct QR code or verify that the certificate reference ID was copied accurately.</p>
                        <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill px-4">
                            Return to Homepage
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
