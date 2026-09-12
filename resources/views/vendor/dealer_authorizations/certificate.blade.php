@extends('layouts.vendor')

@section('title', 'Digital Certificate of Brand Authorization - ' . $authRecord->certificate_number)

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 d-print-none">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('vendor.dealer-authorizations.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0 text-dark">Brand Dealership Digital Certificate</h4>
            <p class="text-muted mb-0 small">Certificate ID: <strong>{{ $authRecord->certificate_number }}</strong></p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('certificate.dealer.verify', $authRecord->certificate_number) }}" target="_blank" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">
            <i class="bi bi-qr-code me-1"></i> Public Verification URL
        </a>
        <button onclick="window.print()" class="btn btn-success btn-sm px-3 rounded-pill fw-bold" style="background-color: var(--garudo-primary); border: none;">
            <i class="bi bi-printer-fill me-1"></i> Print / Save as PDF
        </button>
    </div>
</div>

<!-- ================= DIGITAL CERTIFICATE CANVAS ================= -->
<div class="card border-0 shadow-lg mx-auto my-3 p-4 p-md-5 position-relative" style="max-width: 920px; background: #FFFDF9; border: 12px double #0B4D3C !important; border-radius: 12px;">
    <!-- Decorative Watermark Background -->
    <div class="position-absolute top-50 start-50 translate-middle text-center opacity-25 pe-none" style="z-index: 0;">
        <i class="bi bi-shield-check" style="font-size: 26rem; color: #0B4D3C;"></i>
    </div>

    <div class="position-relative" style="z-index: 1;">
        <!-- Header: Garudo Crest & Brand Emblem -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-4 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 text-white text-center shadow-sm" style="background: linear-gradient(135deg, #0B4D3C 0%, #10B981 100%); width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-shield-shaded fs-2"></i>
                </div>
                <div>
                    <h3 class="fw-black mb-0 tracking-wide" style="color: #0B4D3C; font-family: serif; letter-spacing: 2px;">GARUDO</h3>
                    <small class="text-uppercase text-muted fw-bold" style="letter-spacing: 1.5px; font-size: 0.7rem;">Verified B2B Industrial Marketplace</small>
                </div>
            </div>

            <!-- Brand Logo -->
            <div class="text-end">
                @if($authRecord->brand->logo)
                    <img src="{{ asset('storage/' . $authRecord->brand->logo) }}" alt="{{ $authRecord->brand->name }}" class="p-1 rounded bg-white border" style="max-height: 55px; max-width: 140px; object-fit: contain;">
                @else
                    <h5 class="fw-bold text-dark mb-0">{{ $authRecord->brand->name }}</h5>
                @endif
                <div class="small fw-bold text-success mt-1"><i class="bi bi-patch-check-fill"></i> Verified OEM Partner</div>
            </div>
        </div>

        <!-- Certificate Title -->
        <div class="text-center my-4">
            <span class="badge px-3 py-1 rounded-pill text-uppercase fw-bold mb-2" style="background: #E8F7F0; color: #0B4D3C; letter-spacing: 2px; font-size: 0.75rem;">
                Official Accreditation
            </span>
            <h2 class="fw-bold text-dark mb-1" style="font-family: serif; letter-spacing: 1px;">CERTIFICATE OF AUTHORIZATION</h2>
            <p class="text-muted small mb-0">This document officially certifies authorized distribution and selling rights</p>
        </div>

        <!-- Main Body Text -->
        <div class="text-center px-md-4 my-4">
            <p class="fs-6 text-secondary mb-2">This is to accredit that</p>
            <h3 class="fw-bold mb-1" style="color: #0B4D3C;">{{ $authRecord->vendor->company_name }}</h3>
            <p class="text-muted small mb-3">GSTIN: <strong>{{ $authRecord->vendor->gstin ?? 'Verified B2B Entity' }}</strong> • PAN: <strong>{{ $authRecord->vendor->pan_number ?? 'Verified' }}</strong></p>

            <p class="text-secondary mb-3" style="line-height: 1.8;">
                has been officially accredited by <strong>{{ $authRecord->brand->name }}</strong> and <strong>Garudo B2B Marketplace</strong> as an
            </p>

            <div class="d-inline-block px-4 py-2 rounded-3 my-2" style="background: linear-gradient(135deg, #0B4D3C 0%, #166534 100%); color: #fff;">
                <h4 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">{{ $authRecord->formatted_type }}</h4>
            </div>

            <p class="text-secondary mt-3 mb-0" style="line-height: 1.7;">
                for the commercial fulfillment, multi-city dispatch, and supply of genuine building materials & industrial products across the authorized territory of:
            </p>
            <h5 class="fw-bold text-dark mt-2 mb-3">{{ $authRecord->formatted_territory }}</h5>
        </div>

        <!-- Certificate Metadata Grid -->
        <div class="p-3 rounded-3 my-4 border" style="background: #F8FAFC;">
            <div class="row text-center g-3">
                <div class="col-4">
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Certificate ID</div>
                    <div class="fw-bold text-dark small">{{ $authRecord->certificate_number }}</div>
                </div>
                <div class="col-4 border-start border-end">
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Effective Period</div>
                    <div class="fw-bold text-dark small">
                        {{ $authRecord->valid_from ? $authRecord->valid_from->format('d M Y') : 'Immediate' }} — 
                        {{ $authRecord->is_lifetime_valid ? 'Lifetime' : ($authRecord->valid_until ? $authRecord->valid_until->format('d M Y') : 'Active') }}
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.7rem;">Verification Status</div>
                    <div class="fw-bold text-success small"><i class="bi bi-shield-fill-check"></i> 100% Authenticated</div>
                </div>
            </div>
        </div>

        <!-- Footer Signatures & QR Code -->
        <div class="d-flex justify-content-between align-items-end pt-4 border-top mt-4">
            <!-- Digital Security Seal -->
            <div class="text-center" style="width: 140px;">
                <div class="rounded-circle border border-2 border-warning p-2 d-inline-flex align-items-center justify-content-center bg-white shadow-sm" style="width: 75px; height: 75px;">
                    <i class="bi bi-award-fill fs-1 text-warning"></i>
                </div>
                <div class="small fw-bold text-dark mt-1" style="font-size: 0.7rem;">GARUDO SEAL OF AUTHENTICITY</div>
            </div>

            <!-- Public Verification QR Box -->
            <div class="text-center border p-2 rounded bg-white shadow-sm" style="width: 130px;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(route('certificate.dealer.verify', $authRecord->certificate_number)) }}" alt="QR Code" style="width: 80px; height: 80px;">
                <div class="text-muted fw-bold mt-1" style="font-size: 0.65rem;">SCAN TO VERIFY</div>
            </div>

            <!-- Platform Signatory -->
            <div class="text-end" style="width: 180px;">
                <div class="fw-bold text-dark mb-1" style="font-family: cursive; font-size: 1.1rem; color: #0B4D3C;">Garudo Compliance</div>
                <div class="border-top pt-1 text-muted fw-semibold" style="font-size: 0.75rem;">
                    Platform Governance Authority<br>
                    Garudo Technologies Pvt Ltd
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
