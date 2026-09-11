@extends('layouts.vendor')

@section('title', 'Marketing & Promotional Tools')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Marketing & Promotional Tools</h4>
        <p class="text-muted mb-0 small">Create coupons, volume discount tiers, and boost product visibility.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-success btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createCouponModal">
            <i class="bi bi-tag me-1"></i> New Coupon
        </button>
        <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#launchBoostModal">
            <i class="bi bi-rocket-takeoff me-1"></i> Launch Boost Campaign
        </button>
    </div>
</div>

<!-- Top Action Cards matching Image 5 -->
<div class="row g-4 mb-4">
    <!-- Coupon Codes Card -->
    <div class="col-md-6">
        <div class="card-custom h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 rounded bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-tag fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Coupon Codes</h6>
                        <p class="text-muted small mb-0">Create percentage or flat discount coupons for contractors.</p>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-success btn-sm w-100 fw-semibold mt-2" data-bs-toggle="modal" data-bs-target="#createCouponModal">
                Create New Coupon
            </button>
        </div>
    </div>

    <!-- Promotional Boost Campaigns Card -->
    <div class="col-md-6">
        <div class="card-custom h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 rounded bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-megaphone fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Promotional Boost Campaigns</h6>
                        <p class="text-muted small mb-0">Feature your products at the top of category search results.</p>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-semibold mt-2" data-bs-toggle="modal" data-bs-target="#launchBoostModal">
                Launch Campaign
            </button>
        </div>
    </div>
</div>

<!-- Section 1: Active Boost Campaigns & Promotional Visibility -->
<div class="card-custom p-0 overflow-hidden mb-4">
    <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-rocket-takeoff-fill text-primary me-2"></i>Promotional Boost Campaigns & Visibility Ranking
            </h6>
            <small class="text-muted">Boosted items receive top placement in catalog searches upon Admin payment confirmation</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#launchBoostModal">
            <i class="bi bi-plus-lg me-1"></i> Boost Another Product
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">CAMPAIGN #</th>
                    <th class="text-muted small">PLAN TIER</th>
                    <th class="text-muted small">FEE & UTR REF</th>
                    <th class="text-muted small">DURATION</th>
                    <th class="text-muted small">IMPRESSIONS / CLICKS</th>
                    <th class="pe-3 text-end text-muted small">ADMIN APPROVAL STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $camp)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">
                            <span class="badge bg-light text-dark border">#{{ $camp->campaign_code }}</span>
                            <div class="text-muted" style="font-size: 0.7rem;">{{ $camp->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $camp->plan_title }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                {{ count($camp->product_ids ?? []) }} Product(s) Targeted
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-success small">₹{{ number_format($camp->total_payable, 2) }}</div>
                            <div class="text-muted font-monospace" style="font-size: 0.72rem;">
                                UTR: {{ $camp->payment_reference_utr ?? 'Pending' }}
                            </div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $camp->duration_days }} Days</div>
                            <div class="text-muted" style="font-size: 0.72rem;">
                                @if($camp->status === 'active')
                                    <span class="text-success">{{ $camp->remaining_days }} days left</span>
                                @elseif($camp->status === 'pending_payment_approval')
                                    <span class="text-warning">Starts upon approval</span>
                                @else
                                    <span>{{ ucfirst($camp->status) }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="small text-dark">
                                <i class="bi bi-eye text-primary me-1"></i>{{ number_format($camp->impressions_count) }} views
                            </div>
                            <div class="small text-muted" style="font-size: 0.72rem;">
                                <i class="bi bi-cursor text-success me-1"></i>{{ number_format($camp->clicks_count) }} clicks
                            </div>
                        </td>
                        <td class="pe-3 text-end">
                            @if($camp->status === 'pending_payment_approval')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">
                                    <i class="bi bi-hourglass-split me-1"></i> Awaiting Admin Payment Verification
                                </span>
                            @elseif($camp->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Active & Boosted Live
                                </span>
                            @elseif($camp->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-x-circle-fill me-1"></i> Payment Rejected
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                    {{ ucfirst($camp->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-megaphone fs-1 d-block mb-2 text-primary opacity-50"></i>
                            <h6 class="fw-bold text-dark">No Boost Campaigns Launched Yet</h6>
                            <p class="small text-muted mb-3">Feature your building materials at the top of category searches across Garudo B2B.</p>
                            <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#launchBoostModal">
                                <i class="bi bi-rocket-takeoff me-1"></i> Launch Your First Boost Campaign
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Section 2: Active Store Discount Coupons -->
<div class="card-custom p-0 overflow-hidden">
    <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-tags-fill text-success me-2"></i>Store Coupon Codes
            </h6>
            <small class="text-muted">Direct discount vouchers applicable during checkout on your store items</small>
        </div>
        <button type="button" class="btn btn-success btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createCouponModal" style="background-color: var(--garudo-primary); border: none;">
            <i class="bi bi-plus-lg me-1"></i> Add Coupon
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small">COUPON CODE</th>
                    <th class="text-muted small">DISCOUNT</th>
                    <th class="text-muted small">MIN ORDER</th>
                    <th class="text-muted small">USAGE</th>
                    <th class="text-muted small">VALIDITY</th>
                    <th class="pe-3 text-end text-muted small">STATUS & ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-bold font-monospace fs-6 px-2 py-1">
                                    {{ $coupon->code }}
                                </span>
                                <button type="button" class="btn btn-sm btn-link p-0 text-muted" onclick="navigator.clipboard.writeText('{{ $coupon->code }}')" title="Copy Code">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <small class="text-muted">{{ $coupon->title }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">
                                @if($coupon->discount_type === 'percentage')
                                    {{ $coupon->discount_value }}% OFF
                                @else
                                    ₹{{ number_format($coupon->discount_value, 2) }} Flat OFF
                                @endif
                            </div>
                            @if($coupon->max_discount_amount)
                                <div class="text-muted" style="font-size: 0.7rem;">Max: ₹{{ number_format($coupon->max_discount_amount, 2) }}</div>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">₹{{ number_format($coupon->min_order_amount, 2) }}</div>
                        </td>
                        <td>
                            <div class="small text-dark">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }} used</div>
                        </td>
                        <td>
                            <div class="small text-muted">
                                {{ $coupon->end_date ? 'Expires: ' . $coupon->end_date->format('d M Y') : 'No Expiry' }}
                            </div>
                        </td>
                        <td class="pe-3 text-end">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <form action="{{ route('vendor.marketing.coupon.toggle', $coupon->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $coupon->status === 'active' ? 'btn-outline-success' : 'btn-outline-secondary' }}" style="font-size: 0.75rem;">
                                        {{ ucfirst($coupon->status) }}
                                    </button>
                                </form>
                                <form action="{{ route('vendor.marketing.coupon.delete', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon code?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2 border-0" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-tag fs-1 d-block mb-2 text-success opacity-50"></i>
                            <h6 class="fw-bold text-dark">No Active Coupons</h6>
                            <p class="small text-muted mb-3">Offer volume discount vouchers to encourage contractors to place larger bulk orders.</p>
                            <button type="button" class="btn btn-outline-success btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createCouponModal">
                                <i class="bi bi-plus-circle me-1"></i> Create Your First Coupon
                            </button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal 1: Create New Coupon -->
<div class="modal fade" id="createCouponModal" tabindex="-1" aria-labelledby="createCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('vendor.marketing.coupon.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom bg-light">
                    <h6 class="modal-title fw-bold text-dark" id="createCouponModalLabel">
                        <i class="bi bi-tag-fill text-success me-2"></i>Create New Discount Coupon
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold text-dark">Coupon Code <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="code" id="couponCodeInput" class="form-control font-monospace fw-bold text-uppercase" placeholder="e.g. BULKBUILD10" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generateRandomCode()">Auto Generate</button>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-dark">Discount Type</label>
                            <select name="discount_type" class="form-select form-select-sm" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Flat Amount (₹)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-dark">Coupon Title / Description <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. Monsoon Bulk Construction Discount" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" name="discount_value" class="form-control form-control-sm" min="1" step="0.5" placeholder="e.g. 10 (for 10% or ₹1000)" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Minimum Order Amount (₹)</label>
                            <input type="number" name="min_order_amount" class="form-control form-control-sm" min="0" step="500" value="25000" placeholder="e.g. 25000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Usage Limit (Contractors)</label>
                            <input type="number" name="usage_limit" class="form-control form-control-sm" min="1" placeholder="e.g. 50 (Leave blank for unlimited)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Expiry Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success fw-semibold px-3" style="background-color: var(--garudo-primary); border: none;">
                        <i class="bi bi-check2-circle me-1"></i> Publish Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Launch Promotional Boost Campaign (With Admin Payment & Approval Workflow) -->
<div class="modal fade" id="launchBoostModal" tabindex="-1" aria-labelledby="launchBoostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('vendor.marketing.boost.launch') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom bg-light">
                    <div>
                        <h6 class="modal-title fw-bold text-dark" id="launchBoostModalLabel">
                            <i class="bi bi-rocket-takeoff text-primary me-2"></i>Launch Promotional Product Boost Campaign
                        </h6>
                        <small class="text-muted">Feature your products at the top of category results. Payment confirmed by Admin prior to activation.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Step 1: Select Boost Tier -->
                    <label class="form-label small fw-bold text-dark mb-2">Step 1: Choose Promotional Visibility Plan Tier</label>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 bg-white h-100 cursor-pointer plan-card border-success bg-success bg-opacity-10" onclick="selectPlan('category_top', 1499, 'Category Top Placement (7 Days)')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plan_tier" id="planTier1" value="category_top" checked onchange="updatePayable(1499)">
                                    <label class="form-check-label fw-bold small text-dark cursor-pointer" for="planTier1">
                                        Category Top Spot
                                    </label>
                                </div>
                                <div class="fw-bold text-success fs-5 my-1">₹1,499 <span class="small fw-normal text-muted" style="font-size: 0.72rem;">+ GST</span></div>
                                <div class="small text-muted" style="font-size: 0.72rem;">7 Days Top Search Placement in your primary product category.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 bg-white h-100 cursor-pointer plan-card" onclick="selectPlan('homepage_spotlight', 2999, 'Homepage Spotlight & Showcase (14 Days)')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plan_tier" id="planTier2" value="homepage_spotlight" onchange="updatePayable(2999)">
                                    <label class="form-check-label fw-bold small text-dark cursor-pointer" for="planTier2">
                                        Homepage Spotlight
                                    </label>
                                </div>
                                <div class="fw-bold text-primary fs-5 my-1">₹2,999 <span class="small fw-normal text-muted" style="font-size: 0.72rem;">+ GST</span></div>
                                <div class="small text-muted" style="font-size: 0.72rem;">14 Days Featured banner + top category recommendations.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 border rounded-3 bg-white h-100 cursor-pointer plan-card" onclick="selectPlan('pan_india_boost', 4999, 'Pan-India High Priority Boost (30 Days)')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="plan_tier" id="planTier3" value="pan_india_boost" onchange="updatePayable(4999)">
                                    <label class="form-check-label fw-bold small text-dark cursor-pointer" for="planTier3">
                                        Pan-India Mega Boost
                                    </label>
                                </div>
                                <div class="fw-bold text-warning fs-5 my-1">₹4,999 <span class="small fw-normal text-muted" style="font-size: 0.72rem;">+ GST</span></div>
                                <div class="small text-muted" style="font-size: 0.72rem;">30 Days High Priority ranking across all search queries nationwide.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Select Products to Boost -->
                    <label class="form-label small fw-bold text-dark mb-2">Step 2: Select Product(s) to Boost <span class="text-danger">*</span></label>
                    <div class="border rounded-3 p-3 bg-light mb-4" style="max-height: 180px; overflow-y: auto;">
                        @forelse($products as $prod)
                            <div class="form-check py-1">
                                <input class="form-check-input" type="checkbox" name="product_ids[]" value="{{ $prod->id }}" id="prod_{{ $prod->id }}" checked>
                                <label class="form-check-label small text-dark" for="prod_{{ $prod->id }}">
                                    <strong>{{ $prod->name }}</strong> (SKU: {{ $prod->sku ?? 'GRD-' . $prod->id }}) - <span class="text-success fw-semibold">₹{{ number_format($prod->unit_price, 2) }}</span>
                                </label>
                            </div>
                        @empty
                            <div class="text-muted small">No active products found. Please add products first.</div>
                        @endforelse
                    </div>

                    <!-- Step 3: Payment Details & UTR Submission -->
                    <label class="form-label small fw-bold text-dark mb-2">Step 3: Payment Verification & Reference</label>
                    <div class="p-3 rounded-3 bg-light border">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5 border-end">
                                <div class="fw-bold small text-dark mb-1"><i class="bi bi-qr-code text-primary me-1"></i> Quick UPI / Bank Transfer</div>
                                <div class="small text-muted mb-2" style="font-size: 0.75rem;">Scan or pay to Garudo Marketplace Billing Desk:</div>
                                <div class="p-2 bg-white rounded border text-center mb-2">
                                    <div class="fw-bold text-success font-monospace" style="font-size: 0.85rem;">billing@garudo.in</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">HDFC Bank A/C: 50200084920194 | IFSC: HDFC0001234</div>
                                </div>
                                <div class="fw-bold text-dark small" id="payableSummary">Total Payable: ₹1,768.82 <span class="text-muted fw-normal" style="font-size: 0.7rem;">(incl. 18% GST)</span></div>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-2">
                                    <label class="form-label small fw-bold text-dark">Payment Mode</label>
                                    <select name="payment_mode" class="form-select form-select-sm">
                                        <option value="upi_qr">UPI / QR Code Transfer</option>
                                        <option value="bank_transfer">Direct Bank NEFT / RTGS</option>
                                        <option value="netbanking">Net Banking / Gateway</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label small fw-bold text-dark">Transaction Reference / UTR Number <span class="text-danger">*</span></label>
                                    <input type="text" name="payment_reference_utr" class="form-control form-control-sm font-monospace" placeholder="e.g. UTR-2026-9948271048" required>
                                    <div class="form-text text-muted" style="font-size: 0.72rem;">Enter your bank transaction ID / UPI UTR reference so Admin can verify and activate your boost.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-4">
                        <i class="bi bi-rocket-takeoff me-1"></i> Submit Boost Request to Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function generateRandomCode() {
        const words = ['GARUDO', 'BULK', 'BUILD', 'PROMO', 'MEGA', 'SAVE'];
        const randomWord = words[Math.floor(Math.random() * words.length)];
        const randomNum = Math.floor(10 + Math.random() * 90);
        document.getElementById('couponCodeInput').value = `${randomWord}${randomNum}`;
    }

    function selectPlan(tier, amt, title) {
        document.getElementById('planTier' + (tier === 'category_top' ? '1' : (tier === 'homepage_spotlight' ? '2' : '3'))).checked = true;
        updatePayable(amt);
    }

    function updatePayable(amt) {
        const gst = amt * 0.18;
        const total = amt + gst;
        document.getElementById('payableSummary').innerHTML = `Total Payable: <strong>₹${total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong> <span class="text-muted fw-normal" style="font-size: 0.7rem;">(incl. 18% GST)</span>`;
    }
</script>
@endpush
@endsection
