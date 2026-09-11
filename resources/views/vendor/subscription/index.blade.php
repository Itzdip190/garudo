@extends('layouts.vendor')

@section('title', 'Subscription & Tier Plans')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Subscription & Growth Tiers</h4>
                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis rounded-pill px-3 py-1 small fw-semibold">
                    <i class="bi bi-patch-check-fill me-1"></i> {{ $currentPlan->name ?? 'Free Starter' }}
                </span>
            </div>
            <p class="text-muted mb-0 small">Scale your B2B sales with 24/7 dedicated support, sponsored boost tokens, 0% commission tiers, and verified badges.</p>
        </div>
        <div>
            <a href="{{ route('vendor.support') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-3">
                <i class="bi bi-headset me-1"></i> Help Desk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 small mb-4 rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show p-3 small mb-4 rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Pending Upgrade Banner (if any) -->
    @if($pendingSubscription)
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-warning bg-opacity-10 border-start border-warning border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning text-white p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Upgrade Verification Pending: {{ $pendingSubscription->plan->name ?? $pendingSubscription->plan_name }}</h6>
                        <p class="text-muted small mb-0">
                            Transaction Reference / UTR: <strong class="text-dark font-monospace">{{ $pendingSubscription->payment_reference }}</strong> (₹{{ number_format($pendingSubscription->amount_paid, 2) }}). Our team is verifying your payment.
                        </p>
                    </div>
                </div>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Awaiting Admin Approval</span>
            </div>
        </div>
    @endif

    <!-- Current Active Plan Overview Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0B4D3C 0%, #062E24 100%); color: #FFFFFF;">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 text-white small fw-bold mb-3 border border-white border-opacity-20">
                        <i class="bi bi-award-fill text-warning"></i> ACTIVE MEMBERSHIP
                    </div>
                    <h3 class="fw-black mb-2 text-white">{{ $currentPlan->name ?? 'Free Starter Tier' }}</h3>
                    <p class="text-white-50 small mb-4">
                        {{ $currentPlan->description ?? 'Standard seller account with access to the Garudo B2B marketplace.' }}
                    </p>

                    <!-- Active Plan Perk Badges -->
                    <div class="d-flex flex-wrap gap-2.5">
                        <!-- 24/7 Support Status -->
                        @if($vendor->has247Support())
                            <div class="d-flex align-items-center gap-2.5 px-3 py-2 rounded-3" style="background: rgba(16, 185, 129, 0.3); border: 1px solid rgba(52, 211, 153, 0.5);">
                                <i class="bi bi-headset text-success fs-5"></i>
                                <div>
                                    <div class="fw-bold small" style="color: #FFFFFF !important;">24/7 Priority Support</div>
                                    <div style="font-size: 0.72rem; color: #D1FAE5 !important;">Dedicated WhatsApp & Phone desk active</div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-2.5 px-3 py-2 rounded-3" style="background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.25);">
                                <i class="bi bi-headset text-warning fs-5"></i>
                                <div>
                                    <div class="fw-bold small" style="color: #FFFFFF !important;">Standard Support</div>
                                    <div style="font-size: 0.72rem; color: #E2E8F0 !important;">Upgrade for 24/7 dedicated manager</div>
                                </div>
                            </div>
                        @endif

                        <!-- Sponsored Boost Tokens -->
                        <div class="d-flex align-items-center gap-2.5 px-3 py-2 rounded-3" style="background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.25);">
                            <i class="bi bi-rocket-takeoff-fill text-warning fs-5"></i>
                            <div>
                                <div class="fw-bold small" style="color: #FFFFFF !important;">{{ $vendor->sponsored_boost_tokens }} Boost Tokens Available</div>
                                <div style="font-size: 0.72rem; color: #E2E8F0 !important;">Free monthly sponsored products</div>
                            </div>
                        </div>

                        <!-- Commission Rate -->
                        <div class="d-flex align-items-center gap-2.5 px-3 py-2 rounded-3" style="background: rgba(0, 0, 0, 0.4); border: 1px solid rgba(255, 255, 255, 0.25);">
                            <i class="bi bi-percent text-info fs-5"></i>
                            <div>
                                <div class="fw-bold small" style="color: #FFFFFF !important;">{{ $currentPlan->commission_rate ?? '5.00' }}% Commission</div>
                                <div style="font-size: 0.72rem; color: #E2E8F0 !important;">Marketplace transaction fee</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 text-lg-end">
                    <div class="p-3 rounded-4 border d-inline-block text-start w-100" style="max-width: 320px; background: rgba(0, 0, 0, 0.45); border-color: rgba(255, 255, 255, 0.25) !important;">
                        <div class="d-flex justify-content-between small mb-2" style="color: #CBD5E1;">
                            <span>Plan Status:</span>
                            <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Active</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2" style="color: #CBD5E1;">
                            <span>Billing Cycle:</span>
                            <span class="fw-bold text-capitalize" style="color: #FFFFFF;">{{ $activeSubscription->billing_cycle ?? 'Lifetime' }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3" style="color: #CBD5E1;">
                            <span>Valid Until:</span>
                            <span class="fw-bold" style="color: #FFFFFF;">
                                {{ $activeSubscription && $activeSubscription->expires_at ? $activeSubscription->expires_at->format('d M, Y') : 'Lifetime Free' }}
                            </span>
                        </div>
                        <a href="#plansSection" class="btn btn-warning w-100 fw-bold rounded-3 text-dark shadow-sm">
                            <i class="bi bi-arrow-up-circle-fill me-1"></i> Upgrade / Change Plan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Tiers Section -->
    <div id="plansSection" class="mb-5">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-dark mb-1">Choose Your Growth Tier</h4>
            <p class="text-muted small mb-3">Unlock unlimited potential with sponsored listings, dedicated 24/7 account managers, and lower commission rates.</p>
            
            <!-- Billing Cycle Switcher -->
            <div class="d-inline-flex align-items-center p-1 bg-light border rounded-pill shadow-sm">
                <button type="button" class="btn btn-sm rounded-pill px-4 fw-bold billing-toggle-btn active" id="btnMonthly" onclick="toggleBillingCycle('monthly')">
                    Monthly Billing
                </button>
                <button type="button" class="btn btn-sm rounded-pill px-4 fw-bold billing-toggle-btn text-muted" id="btnYearly" onclick="toggleBillingCycle('yearly')">
                    Yearly Billing <span class="badge bg-success ms-1">Save up to 20%</span>
                </button>
            </div>
        </div>

        <!-- Plan Comparison Cards Grid -->
        <div class="row g-4 justify-content-center">
            @foreach($plans as $plan)
                @php
                    $isCurrent = ($currentPlan && $currentPlan->id === $plan->id);
                @endphp
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden transition-card" 
                         style="border: {{ $plan->is_featured ? '2px solid #10B981' : '1px solid var(--garudo-border)' }} !important; background: #FFFFFF;">
                        
                        @if($plan->is_featured)
                            <div class="position-absolute top-0 end-0 bg-success text-white px-3 py-1 fw-bold small rounded-bottom-start-3 shadow-sm">
                                <i class="bi bi-star-fill me-1"></i> {{ $plan->badge_title ?? 'MOST POPULAR' }}
                            </div>
                        @elseif($isCurrent)
                            <div class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 fw-bold small rounded-bottom-start-3 shadow-sm">
                                <i class="bi bi-check2-circle me-1"></i> YOUR CURRENT PLAN
                            </div>
                        @endif

                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="fw-bold text-dark mb-1">{{ $plan->name }}</h5>
                                <p class="text-muted small mb-4" style="min-height: 40px;">{{ $plan->description ?? 'Ideal for growing B2B merchants & suppliers.' }}</p>

                                <!-- Price Display -->
                                <div class="mb-4">
                                    <div class="price-box-monthly d-flex align-items-baseline gap-1">
                                        <span class="fs-2 fw-black text-dark">₹{{ number_format($plan->monthly_price, 0) }}</span>
                                        <span class="text-muted small">/ month</span>
                                    </div>
                                    <div class="price-box-yearly d-none align-items-baseline gap-1">
                                        <span class="fs-2 fw-black text-dark">₹{{ number_format($plan->yearly_price, 0) }}</span>
                                        <span class="text-muted small">/ year</span>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        @if($plan->monthly_price <= 0)
                                            <span class="badge bg-light text-secondary border">100% Free Forever</span>
                                        @else
                                            <span class="price-box-monthly text-muted">Billed monthly</span>
                                            <span class="price-box-yearly d-none text-success fw-semibold">
                                                <i class="bi bi-tag-fill me-1"></i> Discounted annual rate
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr class="my-3">

                                <!-- Core Benefits List -->
                                <div class="mb-4">
                                    <div class="fw-bold text-uppercase small text-muted mb-3" style="font-size: 0.72rem; letter-spacing: 0.5px;">Plan Benefits & Features</div>
                                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-2.5">
                                        <!-- 24/7 Dedicated Support -->
                                        <li class="d-flex align-items-center gap-2">
                                            @if($plan->has_24_7_support)
                                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                                <span class="fw-bold text-dark">24/7 Dedicated Support Desk</span>
                                            @else
                                                <i class="bi bi-x-circle text-muted fs-6"></i>
                                                <span class="text-muted">Standard Email Support (24-48 hrs)</span>
                                            @endif
                                        </li>

                                        <!-- Sponsored Boost Tokens -->
                                        <li class="d-flex align-items-center gap-2">
                                            @if($plan->sponsored_products_quota > 0)
                                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                                <span class="fw-bold text-dark">{{ $plan->sponsored_products_quota }} Sponsored Boost Tokens / mo</span>
                                            @else
                                                <i class="bi bi-dash-circle text-muted fs-6"></i>
                                                <span class="text-muted">Pay-per-boost advertising</span>
                                            @endif
                                        </li>

                                        <!-- Commission -->
                                        <li class="d-flex align-items-center gap-2">
                                            <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                            <span>
                                                @if($plan->commission_rate <= 0)
                                                    <strong class="text-success">0% Zero Commission</strong> on orders
                                                @else
                                                    <strong>{{ $plan->commission_rate }}% Platform Commission</strong>
                                                @endif
                                            </span>
                                        </li>

                                        <!-- Product Limits -->
                                        <li class="d-flex align-items-center gap-2">
                                            <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                            <span>
                                                {{ $plan->product_limit < 0 ? 'Unlimited Product Catalog' : 'Up to ' . $plan->product_limit . ' Listed Products' }}
                                            </span>
                                        </li>

                                        <!-- Verified Badge -->
                                        <li class="d-flex align-items-center gap-2">
                                            @if($plan->verified_badge_included)
                                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                                <span class="text-dark"><strong>Verified Partner Gold Badge</strong></span>
                                            @else
                                                <i class="bi bi-dash-circle text-muted fs-6"></i>
                                                <span class="text-muted">Standard Seller Badge</span>
                                            @endif
                                        </li>

                                        <!-- Custom F.O.R. Logistics -->
                                        <li class="d-flex align-items-center gap-2">
                                            @if($plan->custom_logistics_enabled)
                                                <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                                <span>Custom F.O.R. & Multi-City Logistics</span>
                                            @else
                                                <i class="bi bi-dash-circle text-muted fs-6"></i>
                                                <span class="text-muted">Standard Courier Shipping</span>
                                            @endif
                                        </li>

                                        <!-- Dynamic Features from JSON -->
                                        @if(!empty($plan->features))
                                            @foreach($plan->features as $feature)
                                                <li class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5"></i>
                                                    <span>{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Plan Action Button -->
                            <div class="pt-3 border-top">
                                @if($isCurrent)
                                    <button class="btn btn-light w-100 py-2.5 rounded-3 fw-bold text-muted border" disabled>
                                        <i class="bi bi-check2 me-1"></i> Current Plan
                                    </button>
                                @elseif($plan->monthly_price <= 0)
                                    <form action="{{ route('vendor.subscription.subscribe') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                        <input type="hidden" name="billing_cycle" value="monthly">
                                        <button type="submit" class="btn btn-outline-secondary w-100 py-2.5 rounded-3 fw-bold">
                                            Switch to Free Starter
                                        </button>
                                    </form>
                                @else
                                    <button type="button" 
                                            class="btn btn-garudo-primary w-100 py-2.5 rounded-3 fw-bold shadow-sm"
                                            onclick="openUpgradeModal({{ $plan->id }}, '{{ addslashes($plan->name) }}', {{ $plan->monthly_price }}, {{ $plan->yearly_price }})">
                                        <i class="bi bi-lightning-charge-fill me-1"></i> Upgrade to {{ $plan->name }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Subscription Invoices & History -->
    @if($subscriptionHistory->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-receipt-cutoff me-2 text-primary"></i> Subscription Billing History
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Plan Name</th>
                            <th>Billing Cycle</th>
                            <th>Amount</th>
                            <th>Payment Reference (UTR)</th>
                            <th>Status</th>
                            <th class="pe-4">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptionHistory as $sub)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $sub->plan->name ?? $sub->plan_name }}</td>
                                <td class="text-capitalize">{{ $sub->billing_cycle }}</td>
                                <td><strong>₹{{ number_format($sub->amount_paid, 2) }}</strong></td>
                                <td>
                                    <span class="font-monospace small">{{ $sub->payment_reference ?? 'Free Plan' }}</span>
                                </td>
                                <td>
                                    @if($sub->status === 'active')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">Active</span>
                                    @elseif($sub->status === 'pending_approval')
                                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1">Pending Approval</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1 text-capitalize">{{ $sub->status }}</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-muted small">{{ $sub->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Upgrade & Payment Checkout Modal -->
<div class="modal fade" id="upgradeCheckoutModal" tabindex="-1" aria-labelledby="upgradeCheckoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('vendor.subscription.subscribe') }}" method="POST" id="subscriptionCheckoutForm">
                @csrf
                <input type="hidden" name="plan_id" id="checkoutPlanId" value="">
                <input type="hidden" name="billing_cycle" id="checkoutBillingCycle" value="monthly">

                <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                    <h5 class="modal-title fw-bold" id="upgradeCheckoutModalLabel">
                        <i class="bi bi-shield-lock-fill text-warning me-2"></i> Subscribe to <span id="checkoutPlanNameDisplay">Tier</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Pricing Summary Box -->
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Subscription Plan:</span>
                            <span class="fw-bold text-dark" id="summaryPlanName">-</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Selected Billing Cycle:</span>
                            <span class="fw-bold text-dark text-capitalize" id="summaryCycle">-</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Total Amount Payable:</span>
                            <span class="fs-4 fw-black text-success" id="summaryAmount">₹0</span>
                        </div>
                    </div>

                    <!-- Payment Details (UPI QR + Bank Transfer) -->
                    <div class="text-center p-3 rounded-3 bg-white border mb-3">
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-qr-code-scan text-primary me-1"></i> Scan & Pay with Any UPI App</h6>
                        <p class="text-muted small mb-2">GPay, PhonePe, Paytm, or BHIM</p>
                        
                        <div class="d-flex justify-content-center my-2">
                            <img id="upiQrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=upi://pay?pa=pay@garudo.in&pn=Garudo%20Technologies&cu=INR" 
                                 alt="UPI QR Code" class="img-thumbnail p-2 shadow-sm rounded-3" style="width: 160px; height: 160px;">
                        </div>
                        <div class="fw-bold font-monospace text-primary small">UPI ID: pay@garudo.in</div>
                        
                        <div class="accordion mt-3" id="bankDetailsAccordion">
                            <div class="accordion-item border-0 bg-light rounded-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed py-2 px-3 bg-light text-muted small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBank">
                                        <i class="bi bi-bank me-2"></i> Or Pay via Bank IMPS / NEFT
                                    </button>
                                </h2>
                                <div id="collapseBank" class="accordion-collapse collapse" data-bs-parent="#bankDetailsAccordion">
                                    <div class="accordion-body p-3 text-start small border-top">
                                        <div class="mb-1"><strong>Account Name:</strong> Garudo B2B Marketplace Pvt Ltd</div>
                                        <div class="mb-1"><strong>Account Number:</strong> 50200088991122</div>
                                        <div class="mb-1"><strong>IFSC Code:</strong> HDFC0001234</div>
                                        <div><strong>Bank & Branch:</strong> HDFC Bank, Commercial Branch</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Reference / UTR Input -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">
                            UPI Ref ID / 12-Digit UTR Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="payment_reference" class="form-control form-control-lg font-monospace" placeholder="e.g. 425619882345 or UPI-123456" required>
                        <div class="form-text small">
                            Enter the 12-digit transaction UTR received after completing the payment.
                        </div>
                    </div>

                    <div class="alert alert-info py-2 small mb-0 rounded-3">
                        <i class="bi bi-shield-check me-1"></i> Once verified by the Admin team, your 24/7 dedicated support desk and sponsored boost tokens will activate automatically.
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold px-4 rounded-3">
                        <i class="bi bi-check2-circle me-1"></i> Submit Payment for Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentBillingCycle = 'monthly';

    function toggleBillingCycle(cycle) {
        currentBillingCycle = cycle;
        const btnMonthly = document.getElementById('btnMonthly');
        const btnYearly = document.getElementById('btnYearly');
        const monthlyBoxes = document.querySelectorAll('.price-box-monthly');
        const yearlyBoxes = document.querySelectorAll('.price-box-yearly');

        if (cycle === 'yearly') {
            btnYearly.classList.add('btn-dark', 'text-white');
            btnYearly.classList.remove('text-muted');
            btnMonthly.classList.remove('btn-dark', 'text-white');
            btnMonthly.classList.add('text-muted');

            monthlyBoxes.forEach(el => el.classList.add('d-none'));
            monthlyBoxes.forEach(el => el.classList.remove('d-flex'));
            yearlyBoxes.forEach(el => el.classList.remove('d-none'));
            yearlyBoxes.forEach(el => el.classList.add('d-flex'));
        } else {
            btnMonthly.classList.add('btn-dark', 'text-white');
            btnMonthly.classList.remove('text-muted');
            btnYearly.classList.remove('btn-dark', 'text-white');
            btnYearly.classList.add('text-muted');

            yearlyBoxes.forEach(el => el.classList.add('d-none'));
            yearlyBoxes.forEach(el => el.classList.remove('d-flex'));
            monthlyBoxes.forEach(el => el.classList.remove('d-none'));
            monthlyBoxes.forEach(el => el.classList.add('d-flex'));
        }
    }

    function openUpgradeModal(planId, planName, monthlyPrice, yearlyPrice) {
        document.getElementById('checkoutPlanId').value = planId;
        document.getElementById('checkoutBillingCycle').value = currentBillingCycle;
        document.getElementById('checkoutPlanNameDisplay').innerText = planName;
        document.getElementById('summaryPlanName').innerText = planName;
        document.getElementById('summaryCycle').innerText = (currentBillingCycle === 'yearly') ? 'Annual (12 Months)' : 'Monthly';

        const amount = (currentBillingCycle === 'yearly') ? yearlyPrice : monthlyPrice;
        document.getElementById('summaryAmount').innerText = '₹' + Number(amount).toLocaleString('en-IN', {minimumFractionDigits: 2});

        // Update QR code with amount
        const upiUrl = `upi://pay?pa=pay@garudo.in&pn=Garudo%20Technologies&am=${amount}&cu=INR&tn=Plan_${planId}_${currentBillingCycle}`;
        document.getElementById('upiQrImage').src = `https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${encodeURIComponent(upiUrl)}`;

        const modal = new bootstrap.Modal(document.getElementById('upgradeCheckoutModal'));
        modal.show();
    }
</script>
@endpush
@endsection
