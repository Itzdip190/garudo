@extends('layouts.vendor')

@section('title', 'Customer Reviews & Review QR Standee')

@push('styles')
<style>
    .rating-star-filled { color: #f59e0b; }
    .rating-star-empty { color: #e2e8f0; }
    .qr-standee-preview {
        background: linear-gradient(135deg, #09382B 0%, #06241C 100%);
        border: 4px solid #D97706;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        color: #FFFFFF;
        width: 100%;
        max-width: 380px;
        margin: 0 auto;
        padding: 24px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .qr-standee-preview::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 140px; height: 140px;
        background: rgba(217, 119, 6, 0.15);
        border-radius: 50%;
    }
    .qr-box-inner {
        background: #FFFFFF;
        padding: 14px;
        border-radius: 16px;
        display: inline-block;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    @media print {
        body * { visibility: hidden; }
        #printableStandeeSection, #printableStandeeSection * { visibility: visible; }
        #printableStandeeSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
        }
        .no-print { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">
                <i class="bi bi-star-fill text-warning me-2"></i> Customer Reviews & Review QR Standee
            </h4>
            <p class="text-muted small mb-0">Monitor verified buyer ratings, generate your physical QR review standee, and boost supplier credibility.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ $vendor->store_url }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-shop me-1"></i> View Public Storefront
            </a>
            <button type="button" class="btn btn-garudo-primary" onclick="navigator.clipboard.writeText('{{ $reviewUrl }}'); alert('Review Link copied to clipboard!');">
                <i class="bi bi-link-45deg me-1"></i> Copy Review Link
            </button>
        </div>
    </div>

    <!-- KYC Standee Status Notification Banner -->
    @if(!$isKycApproved)
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle p-2.5 bg-warning text-dark fs-4">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">KYC Verification in Progress</h6>
                    <span class="small text-muted">Complete your KYC verification to get the official <strong>Verified Channel Partner</strong> Gold Seal on your printable review QR standee.</span>
                </div>
            </div>
            <a href="{{ route('vendor.kyc.index') }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold">
                Complete KYC Now →
            </a>
        </div>
    @else
        <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle p-2.5 bg-success text-white fs-4">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">KYC Verified Channel Partner</h6>
                    <span class="small text-muted">Your official Review QR Standee is activated with verified trust credentials. Download or print desk tent cards below.</span>
                </div>
            </div>
            <span class="badge bg-success px-3 py-2 fs-7 fw-bold rounded-pill">
                <i class="bi bi-shield-check me-1"></i> Verified Supplier Badge Active
            </span>
        </div>
    @endif

    <!-- Rating Summary Statistics Row -->
    <div class="row g-3 mb-4">
        <!-- Main Rating Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white text-center d-flex flex-column justify-content-center">
                <div class="text-muted small fw-bold text-uppercase mb-1">Overall Store Rating</div>
                <div class="display-4 fw-black text-dark my-1">{{ number_format($avgRating, 1) }}</div>
                <div class="text-warning fs-5 mb-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star-fill {{ $i <= round($avgRating) ? 'text-warning' : 'rating-star-empty' }}"></i>
                    @endfor
                </div>
                <span class="small text-muted">Based on <strong>{{ $totalReviews }}</strong> Verified Reviews</span>
            </div>
        </div>

        <!-- Material Quality Rating -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Material Quality</span>
                    <div class="p-2 rounded-3 bg-success-subtle text-success fs-5">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($avgQuality, 1) }} <span class="fs-6 text-muted">/ 5.0</span></h3>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: {{ ($avgQuality / 5) * 100 }}%"></div>
                </div>
                <span class="small text-muted mt-2 d-block">Batch specs & test certificate compliance</span>
            </div>
        </div>

        <!-- Delivery Rating -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Dispatch & Delivery</span>
                    <div class="p-2 rounded-3 bg-primary-subtle text-primary fs-5">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($avgDelivery, 1) }} <span class="fs-6 text-muted">/ 5.0</span></h3>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: {{ ($avgDelivery / 5) * 100 }}%"></div>
                </div>
                <span class="small text-muted mt-2 d-block">On-time transit & packaging integrity</span>
            </div>
        </div>

        <!-- Pricing Transparency -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Pricing & Transparency</span>
                    <div class="p-2 rounded-3 bg-warning-subtle text-warning fs-5">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($avgPricing, 1) }} <span class="fs-6 text-muted">/ 5.0</span></h3>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: {{ ($avgPricing / 5) * 100 }}%"></div>
                </div>
                <span class="small text-muted mt-2 d-block">Competitive bulk rates & GST invoicing</span>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <ul class="nav nav-pills mb-4 gap-2" id="reviewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-bold" id="reviews-tab" data-bs-toggle="pill" data-bs-target="#reviews-list" type="button" role="tab">
                <i class="bi bi-chat-quote-fill me-2"></i> Buyer Reviews ({{ $totalReviews }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold" id="qr-tab" data-bs-toggle="pill" data-bs-target="#qr-standee" type="button" role="tab">
                <i class="bi bi-qr-code-scan me-2 text-warning"></i> Review QR Standee & Digital Card
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reviewTabsContent">
        <!-- TAB 1: Buyer Reviews List -->
        <div class="tab-pane fade show active" id="reviews-list" role="tabpanel">
            <!-- Filter Pills -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="text-muted small fw-semibold">Filter by Stars:</span>
                        <a href="{{ route('vendor.reviews', ['rating' => 'all']) }}" 
                           class="btn btn-sm {{ $ratingFilter === 'all' ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3">
                            All ({{ $totalReviews }})
                        </a>
                        @for($r = 5; $r >= 1; $r--)
                            <a href="{{ route('vendor.reviews', ['rating' => $r]) }}" 
                               class="btn btn-sm {{ (string)$ratingFilter === (string)$r ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill px-3">
                                {{ $r }} <i class="bi bi-star-fill text-warning"></i> ({{ $starCounts[$r] }})
                            </a>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Reviews Feed Cards -->
            <div class="row g-3">
                @forelse($reviews as $rev)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; font-size: 1.1rem;">
                                        {{ strtoupper(substr($rev->reviewer_name ?? 'B', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $rev->reviewer_name }}</h6>
                                            @if($rev->is_verified_buyer)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small">
                                                    <i class="bi bi-shield-check me-1"></i> Verified Buyer
                                                </span>
                                            @endif
                                        </div>
                                        @if($rev->reviewer_company)
                                            <div class="text-muted small">{{ $rev->reviewer_company }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning fs-6 mb-1">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="bi bi-star-fill {{ $s <= $rev->rating ? 'text-warning' : 'rating-star-empty' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">{{ $rev->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                            </div>

                            @if($rev->title)
                                <h6 class="fw-bold text-dark mb-2">{{ $rev->title }}</h6>
                            @endif

                            <p class="text-secondary small mb-3 leading-relaxed">
                                {{ $rev->review }}
                            </p>

                            <!-- Sub Ratings -->
                            <div class="d-flex flex-wrap gap-3 pt-2 border-top">
                                <span class="small text-muted">
                                    Quality: <strong class="text-dark">{{ $rev->material_quality_rating ?? 5 }}★</strong>
                                </span>
                                <span class="small text-muted">
                                    Delivery: <strong class="text-dark">{{ $rev->delivery_rating ?? 5 }}★</strong>
                                </span>
                                <span class="small text-muted">
                                    Pricing: <strong class="text-dark">{{ $rev->pricing_rating ?? 5 }}★</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="bi bi-star text-muted display-4 mb-3"></i>
                            <h5 class="fw-bold text-dark">No Reviews Received Yet</h5>
                            <p class="text-muted small mb-3">Share your Review QR Standee or link with clients to start receiving verified ratings on Garudo.</p>
                            <div>
                                <button type="button" class="btn btn-warning fw-bold text-dark px-4 rounded-pill" onclick="$('#qr-tab').tab('show');">
                                    <i class="bi bi-qr-code me-1"></i> Open Review QR Standee
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($reviews->hasPages())
                <div class="mt-4">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>

        <!-- TAB 2: Review QR Standee & Digital Card Generator -->
        <div class="tab-pane fade" id="qr-standee" role="tabpanel">
            <div class="row g-4 align-items-center">
                <!-- Left: Interactive Standee Card Preview -->
                <div class="col-lg-6">
                    <div id="printableStandeeSection">
                        <div class="qr-standee-preview" id="qrStandeeCard">
                            <!-- Garudo Header -->
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                <span class="bg-warning text-dark px-2 py-0.5 rounded fw-black fs-6" style="font-weight: 900;">GARUDO<span class="text-white">.IN</span></span>
                                <span class="small text-uppercase tracking-wider fw-bold text-warning" style="font-size: 10px; letter-spacing: 1px;">B2B MARKETPLACE</span>
                            </div>

                            <!-- Trust Seal Badge -->
                            <div class="my-2">
                                @if($isKycApproved)
                                    <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold shadow-sm">
                                        <i class="bi bi-patch-check-fill me-1"></i> VERIFIED CHANNEL PARTNER
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white px-3 py-1.5 rounded-pill fw-bold">
                                        <i class="bi bi-shop me-1"></i> REGISTERED SUPPLIER
                                    </span>
                                @endif
                            </div>

                            <!-- Vendor Company Name -->
                            <h4 class="fw-black text-white mb-1 mt-2">{{ $vendor->company_name }}</h4>
                            <p class="text-white-50 small mb-3" style="font-size: 11px;">
                                {{ $vendor->primary_dispatch_state ? 'Dispatch Hub: ' . $vendor->primary_dispatch_state : 'Verified Building Materials Supplier' }}
                            </p>

                            <!-- QR Code Box -->
                            <div class="qr-box-inner my-2">
                                <img id="standeeQrImg" src="{{ $qrCodeUrl }}" alt="Scan to Review" style="width: 170px; height: 170px; display: block;" crossOrigin="anonymous">
                            </div>

                            <!-- Call to Action -->
                            <div class="mt-3">
                                <div class="text-warning fs-5 mb-1">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <h6 class="fw-bold text-white mb-1">Rate & Review Us on Garudo</h6>
                                <p class="text-white-50 small mb-0" style="font-size: 10px;">Scan with your camera or QR scanner to share your material & delivery feedback.</p>
                            </div>

                            <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 small text-white-50" style="font-size: 9px;">
                                garudo.in/store/{{ $vendor->company_slug ?? $vendor->id }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Standee Actions & Instructions -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-printer-fill text-primary me-2"></i> Print & Download Standee
                        </h5>
                        <p class="text-muted small mb-4">
                            Place this QR standee on your billing desk, warehouse counter, or dispatch note so procurement managers and contractors can instantly rate your service.
                        </p>

                        <div class="d-grid gap-2 mb-4">
                            <button type="button" class="btn btn-warning fw-bold py-2.5 rounded-3 text-dark shadow-sm" onclick="window.print();">
                                <i class="bi bi-printer me-2"></i> Print Desk Standee (A5 / Tent Card)
                            </button>
                            
                            <a href="{{ $qrCodeUrl }}" download="Garudo-Review-QR-{{ $vendor->company_slug ?? 'supplier' }}.png" target="_blank" class="btn btn-outline-dark py-2.5 rounded-3 fw-bold">
                                <i class="bi bi-download me-2"></i> Download QR Code Image (300x300 PNG)
                            </a>

                            <button type="button" class="btn btn-outline-primary py-2.5 rounded-3 fw-bold" onclick="navigator.clipboard.writeText('{{ $reviewUrl }}'); alert('Direct Review URL copied to clipboard!');">
                                <i class="bi bi-clipboard-check me-2"></i> Copy Direct Review Link
                            </button>
                        </div>

                        <div class="border rounded-3 p-3 bg-light">
                            <h6 class="fw-bold text-dark small mb-2"><i class="bi bi-info-circle-fill text-primary me-1"></i> Direct Store Review Link:</h6>
                            <input type="text" class="form-control form-control-sm font-monospace bg-white" value="{{ $reviewUrl }}" readonly onclick="this.select();">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
