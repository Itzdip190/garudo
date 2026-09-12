@extends('layouts.vendor')

@section('title', 'Apply for Brand Dealership Authorization')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('vendor.dealer-authorizations.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0 text-dark">Apply for Brand Dealership Authorization</h4>
            <p class="text-muted mb-0 small">Get officially authorized by Brand OEMs & Garudo to sell brand catalog products with verified badges.</p>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please resolve the following errors:</div>
        <ul class="mb-0 small">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('vendor.dealer-authorizations.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <!-- LEFT: Dealership Application Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-patch-check-fill text-primary me-2"></i>1. Brand Selection & Partnership Tier
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Select Registered Brand <span class="text-danger">*</span></label>
                        <select name="brand_id" class="form-select" required>
                            <option value="">-- Choose Brand --</option>
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }} ({{ $b->country_of_origin ?? 'India' }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Choose the verified brand you are authorized to distribute or sell.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Authorization Tier <span class="text-danger">*</span></label>
                        <select name="authorization_type" class="form-select" required>
                            <option value="authorized_dealer" {{ old('authorization_type') == 'authorized_dealer' ? 'selected' : '' }}>Authorized City Dealer</option>
                            <option value="authorized_distributor" {{ old('authorization_type') == 'authorized_distributor' ? 'selected' : '' }}>Authorized Regional Distributor</option>
                            <option value="exclusive_distributor" {{ old('authorization_type') == 'exclusive_distributor' ? 'selected' : '' }}>Exclusive National Distributor</option>
                            <option value="tier2_stockist" {{ old('authorization_type') == 'tier2_stockist' ? 'selected' : '' }}>Tier-2 Stockist / Wholesaler</option>
                            <option value="franchise_partner" {{ old('authorization_type') == 'franchise_partner' ? 'selected' : '' }}>Official Franchise Partner</option>
                        </select>
                    </div>
                </div>

                <hr class="text-muted my-4">

                <h5 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-geo-alt-fill text-success me-2"></i>2. Territory Jurisdiction & Scope
                </h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Territory Level <span class="text-danger">*</span></label>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="territory_type" id="t_national" value="national" {{ old('territory_type') == 'national' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="t_national">Pan-India (National Territory)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="territory_type" id="t_state" value="state" {{ old('territory_type', 'state') == 'state' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="t_state">State-Level Territory</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="territory_type" id="t_city" value="city" {{ old('territory_type') == 'city' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="t_city">City / District Level</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Allowed States (Select all applicable)</label>
                    <select name="territory_states[]" class="form-select" multiple style="height: 110px;">
                        @foreach($indianStates as $state)
                            <option value="{{ $state }}" {{ in_array($state, old('territory_states', [])) ? 'selected' : '' }}>{{ $state }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple states.</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Authorized Cities / Districts (Comma separated)</label>
                        <input type="text" name="territory_cities" class="form-control" placeholder="e.g. Mumbai, Pune, Nagpur" value="{{ old('territory_cities') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">PIN Codes / Yard Zones (Optional)</label>
                        <input type="text" name="territory_pincodes" class="form-control" placeholder="e.g. 400001, 411001" value="{{ old('territory_pincodes') }}">
                    </div>
                </div>

                <hr class="text-muted my-4">

                <h5 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-file-earmark-lock-fill text-danger me-2"></i>3. Dealership Proof & Validity
                </h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Upload Dealership Agreement / OEM Authorization Letter <span class="text-danger">*</span></label>
                    <input type="file" name="authorization_doc" class="form-control" accept=".pdf,image/*" required>
                    <small class="text-muted">Official dealership contract on OEM letterhead, authorized appointment letter, or recent direct wholesale invoices (PDF or Image max 10MB).</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Agreement Reference No</label>
                        <input type="text" name="agreement_ref_no" class="form-control" placeholder="e.g. TATA-DLR-2026-991" value="{{ old('agreement_ref_no') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Valid From Date</label>
                        <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Valid Until Date</label>
                        <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', date('Y-m-d', strtotime('+1 year'))) }}">
                    </div>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_lifetime_valid" id="is_lifetime_valid" value="1" {{ old('is_lifetime_valid') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold text-dark small" for="is_lifetime_valid">
                        Lifetime / Permanent Authorized Dealership
                    </label>
                </div>
            </div>
        </div>

        <!-- RIGHT: Summary & Submit -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark">
                    <i class="bi bi-shield-check text-success me-2"></i>Benefits of Verification
                </h5>

                <ul class="list-unstyled mb-4 small text-secondary" style="line-height: 1.8;">
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Official Digital Certificate:</strong> Receive a unique verification code and QR proof.</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Verified Dealer Trust Seal:</strong> Shown on product pages and your store.</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Catalog Rights:</strong> List and sell trademark-protected brand products.</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Multi-City Dispatch:</strong> Priority routing for regional buyers in your territory.</li>
                </ul>

                <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm" style="background-color: var(--garudo-primary); border: none;">
                    <i class="bi bi-shield-lock-fill me-1"></i> Submit Dealership for Verification
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
