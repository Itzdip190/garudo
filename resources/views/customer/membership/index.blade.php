@extends('layouts.customer')

@section('title', 'Buyer Pro Membership & Plans')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Corporate Buyer Membership</h4>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-semibold">
                    <i class="bi bi-shield-check me-1"></i> {{ $currentPlan->name ?? 'Standard Buyer' }}
                </span>
            </div>
            <p class="text-muted mb-0 small">Access exclusive wholesale pricing tiers, credit terms, priority quotation turnaround, and a dedicated procurement manager.</p>
        </div>
        <div>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Active Membership Hero Banner -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); color: #FFFFFF;">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 text-white small fw-bold mb-3 border border-white border-opacity-20">
                        <i class="bi bi-patch-check-fill text-primary"></i> BUYER ACCOUNT STATUS
                    </div>
                    <h3 class="fw-black mb-2 text-white">{{ $currentPlan->name ?? 'Standard Buyer Account' }}</h3>
                    <p class="text-white-50 small mb-4">
                        {{ $currentPlan->description ?? 'Direct access to verified building materials suppliers, instant RFQs, and secure digital payments across India.' }}
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                            <i class="bi bi-truck text-success fs-5"></i>
                            <div>
                                <div class="fw-bold text-white small">Direct F.O.R. Site Delivery</div>
                                <div class="text-white-50" style="font-size: 0.72rem;">Pan-India logistics network</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                            <i class="bi bi-file-earmark-text text-warning fs-5"></i>
                            <div>
                                <div class="fw-bold text-white small">Instant Quotation Engine</div>
                                <div class="text-white-50" style="font-size: 0.72rem;">Multi-vendor competitive bidding</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-white bg-opacity-10 border border-white border-opacity-10">
                            <i class="bi bi-shield-lock-fill text-info fs-5"></i>
                            <div>
                                <div class="fw-bold text-white small">Verified Quality Guarantee</div>
                                <div class="text-white-50" style="font-size: 0.72rem;">ISI / ISO certified stock</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-20 d-inline-block text-start w-100" style="max-width: 300px;">
                        <div class="text-white-50 small mb-1">Corporate Account Tier</div>
                        <div class="fs-5 fw-bold text-white mb-2">{{ $currentPlan->name ?? 'Standard Buyer' }}</div>
                        <div class="text-white-50 small">Need customized procurement or credit terms?</div>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary btn-sm w-100 fw-bold mt-3 rounded-3">
                            <i class="bi bi-chat-text-fill me-1"></i> Contact Procurement Desk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Plans Grid -->
    <div class="row g-4 justify-content-center">
        @forelse($plans as $plan)
            <div class="col-xl-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden" 
                     style="border: {{ $plan->is_featured ? '2px solid #0284C7' : '1px solid var(--garudo-border)' }} !important; background: #FFFFFF;">
                    
                    @if($plan->is_featured)
                        <div class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 fw-bold small rounded-bottom-start-3 shadow-sm">
                            <i class="bi bi-star-fill me-1"></i> {{ $plan->badge_title ?? 'RECOMMENDED FOR BUILDERS' }}
                        </div>
                    @endif

                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $plan->name }}</h5>
                            <p class="text-muted small mb-4">{{ $plan->description ?? 'Tailored for contractors, architects, and corporate infrastructure buyers.' }}</p>

                            <!-- Price -->
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <div class="d-flex align-items-baseline gap-1">
                                    <span class="fs-3 fw-black text-dark">₹{{ number_format($plan->monthly_price, 0) }}</span>
                                    <span class="text-muted small">/ month</span>
                                </div>
                                <div class="text-muted small mt-1">
                                    Annual: <strong>₹{{ number_format($plan->yearly_price, 0) }}</strong> / year
                                </div>
                            </div>

                            <!-- Features -->
                            @if(!empty($plan->features))
                                <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                                    @foreach($plan->features as $feature)
                                        <li class="d-flex align-items-start gap-2">
                                            <i class="bi bi-check2-circle text-primary fs-6 mt-0.5"></i>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="pt-3 border-top mt-4">
                            @if($plan->monthly_price <= 0)
                                <button class="btn btn-light border w-100 py-2.5 rounded-3 fw-bold text-muted" disabled>
                                    <i class="bi bi-check2 me-1"></i> Standard Access Active
                                </button>
                            @else
                                <a href="mailto:support@garudo.in?subject=Buyer%20Pro%20Membership%20Inquiry%20{{ urlencode($plan->name) }}" class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm">
                                    <i class="bi bi-patch-plus-fill me-1"></i> Inquire for Corporate Upgrade
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Standard buyer membership is active on your account.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
