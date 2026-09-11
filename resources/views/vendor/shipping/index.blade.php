@extends('layouts.vendor')

@section('title', 'Shipping & Delivery Settings')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Shipping & Delivery Settings</h4>
        <p class="text-muted mb-0 small">Configure service pincodes, freight logistics, custom dispatch timelines, and F.O.R. delivery policies.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('vendor.warehouses.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-buildings me-1"></i> Manage Warehouses
        </a>
    </div>
</div>

<form action="{{ route('vendor.shipping.update') }}" method="POST">
    @csrf
    <div class="row g-4">
        <!-- Main Logistics & F.O.R. Card -->
        <div class="col-lg-8">
            <div class="card-custom">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-truck text-success me-2"></i>Logistics & Dispatch Configuration
                    </h6>
                    <span class="badge bg-success bg-opacity-10 text-success small">Active Shipping Profile</span>
                </div>

                <div class="row g-3">
                    <!-- Default Dispatch Time -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Default Dispatch Time</label>
                        <select name="dispatch_time_type" id="dispatchTimeSelect" class="form-select" onchange="toggleCustomDispatch(this.value)">
                            <option value="1-2" {{ old('dispatch_time_type', $vendor->dispatch_time_type ?? '2-4') == '1-2' ? 'selected' : '' }}>
                                Same Day / Next Day (1-2 Days)
                            </option>
                            <option value="2-4" {{ old('dispatch_time_type', $vendor->dispatch_time_type ?? '2-4') == '2-4' ? 'selected' : '' }}>
                                Standard (2-4 Days)
                            </option>
                            <option value="5-7" {{ old('dispatch_time_type', $vendor->dispatch_time_type ?? '2-4') == '5-7' ? 'selected' : '' }}>
                                Bulk Heavy Freight (5-7 Days)
                            </option>
                            <option value="custom" {{ old('dispatch_time_type', $vendor->dispatch_time_type ?? '2-4') == 'custom' ? 'selected' : '' }}>
                                ✏️ Custom Date Range / Custom Days
                            </option>
                        </select>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Estimated time required to prepare and dispatch materials from warehouse.</div>
                    </div>

                    <!-- Primary Dispatch Warehouse State -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Primary Dispatch Warehouse State</label>
                        <input type="text" name="primary_dispatch_state" class="form-control" value="{{ old('primary_dispatch_state', $vendor->primary_dispatch_state ?? ($primaryWarehouse->state ?? 'Maharashtra, India')) }}" placeholder="e.g. Maharashtra, India">
                        <div class="form-text text-muted" style="font-size: 0.75rem;">State from where primary shipments originate.</div>
                    </div>

                    <!-- Custom Dispatch Timeline Input Fields (Shown when Custom is chosen) -->
                    <div class="col-12 {{ old('dispatch_time_type', $vendor->dispatch_time_type ?? '2-4') == 'custom' ? '' : 'd-none' }}" id="customDispatchBox">
                        <div class="p-3 bg-light rounded-3 border border-success border-opacity-25">
                            <div class="fw-bold small text-success mb-2">
                                <i class="bi bi-calendar-range me-1"></i> Custom Dispatch Timeline & Date Range
                            </div>
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Min Dispatch Days</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="custom_dispatch_min_days" id="customMinDays" class="form-control" min="1" max="90" value="{{ old('custom_dispatch_min_days', $vendor->custom_dispatch_min_days ?? 7) }}" placeholder="Min Days" oninput="updateLivePreview()">
                                        <span class="input-group-text">Days</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Max Dispatch Days</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="custom_dispatch_max_days" id="customMaxDays" class="form-control" min="1" max="180" value="{{ old('custom_dispatch_max_days', $vendor->custom_dispatch_max_days ?? 15) }}" placeholder="Max Days" oninput="updateLivePreview()">
                                        <span class="input-group-text">Days</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label small fw-semibold text-muted">Custom Note / Timeline Label</label>
                                    <input type="text" name="custom_dispatch_notes" id="customNotes" class="form-control form-control-sm" value="{{ old('custom_dispatch_notes', $vendor->custom_dispatch_notes ?? 'Made-to-order manufacturing') }}" placeholder="e.g. Direct factory dispatch">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- F.O.R. (Freight on Road / Free Delivery) Section -->
                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="bi bi-box2-heart text-success me-1"></i> F.O.R. (Freight On Road) & Free Delivery Policy
                                </h6>
                                <small class="text-muted">Offer Free on Road delivery to boost conversions for bulk buyers and contractors.</small>
                            </div>
                            <div class="form-check form-switch fs-5 mb-0">
                                <input class="form-check-input cursor-pointer" type="checkbox" name="is_for_enabled" id="freeShippingSwitch" value="1" {{ old('is_for_enabled', $vendor->is_for_enabled ?? true) ? 'checked' : '' }} onchange="toggleForOptions(this.checked)">
                            </div>
                        </div>

                        <div id="forOptionsContainer" class="{{ old('is_for_enabled', $vendor->is_for_enabled ?? true) ? '' : 'd-none' }}">
                            <div class="bg-light p-3 rounded-3 border mb-3">
                                <label class="form-label small fw-bold text-dark mb-2">1. F.O.R. Threshold Condition Type</label>
                                <div class="d-flex flex-wrap gap-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="for_condition_type" id="forTypeAmount" value="amount" {{ old('for_condition_type', $vendor->for_condition_type ?? 'amount') == 'amount' ? 'checked' : '' }} onchange="toggleConditionInputs('amount')">
                                        <label class="form-check-label fw-semibold small text-dark" for="forTypeAmount">
                                            <i class="bi bi-currency-rupee text-success"></i> Based on Order Amount (₹)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="for_condition_type" id="forTypeQty" value="quantity" {{ old('for_condition_type', $vendor->for_condition_type ?? 'amount') == 'quantity' ? 'checked' : '' }} onchange="toggleConditionInputs('quantity')">
                                        <label class="form-check-label fw-semibold small text-dark" for="forTypeQty">
                                            <i class="bi bi-boxes text-primary"></i> Based on Order Quantity (Units / Tons / Bags)
                                        </label>
                                    </div>
                                </div>

                                <!-- Amount Threshold Input -->
                                <div id="amountInputGroup" class="{{ old('for_condition_type', $vendor->for_condition_type ?? 'amount') == 'amount' ? '' : 'd-none' }}">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">Minimum Order Value for Free F.O.R. (₹)</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white fw-bold">₹</span>
                                                <input type="number" name="for_min_amount" id="forMinAmount" class="form-control" step="500" value="{{ old('for_min_amount', $vendor->for_min_amount ?? 50000) }}" placeholder="e.g. 50000" oninput="updateLivePreview()">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block mt-3">Orders exceeding this amount receive 100% Free Doorstep Freight (F.O.R.).</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Threshold Input -->
                                <div id="qtyInputGroup" class="{{ old('for_condition_type', $vendor->for_condition_type ?? 'amount') == 'quantity' ? '' : 'd-none' }}">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold text-muted">Minimum Order Quantity</label>
                                            <div class="input-group">
                                                <input type="number" name="for_min_qty" id="forMinQty" class="form-control" min="1" value="{{ old('for_min_qty', $vendor->for_min_qty ?? 100) }}" placeholder="e.g. 100" oninput="updateLivePreview()">
                                                <select name="for_qty_unit" id="forQtyUnit" class="form-select" style="max-width: 140px;" onchange="updateLivePreview()">
                                                    <option value="Bags" {{ old('for_qty_unit', $vendor->for_qty_unit ?? 'Bags') == 'Bags' ? 'selected' : '' }}>Bags</option>
                                                    <option value="Tons" {{ old('for_qty_unit', $vendor->for_qty_unit ?? 'Bags') == 'Tons' ? 'selected' : '' }}>Tons / MT</option>
                                                    <option value="Units / Pieces" {{ old('for_qty_unit', $vendor->for_qty_unit ?? 'Bags') == 'Units / Pieces' ? 'selected' : '' }}>Units / Pieces</option>
                                                    <option value="Sq.Ft" {{ old('for_qty_unit', $vendor->for_qty_unit ?? 'Bags') == 'Sq.Ft' ? 'selected' : '' }}>Sq.Ft</option>
                                                    <option value="Bundles" {{ old('for_qty_unit', $vendor->for_qty_unit ?? 'Bags') == 'Bundles' ? 'selected' : '' }}>Bundles</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block mt-3">Orders meeting or exceeding this quantity qualify for Free Delivery.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Geographic Scope -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-2">2. F.O.R. Delivery Geographic Scope / Coverage Area</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 border rounded-3 bg-white h-100 cursor-pointer scope-card" onclick="selectScope('city')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="for_scope" id="scopeCity" value="city" {{ old('for_scope', $vendor->for_scope ?? 'pan_india') == 'city' ? 'checked' : '' }} onchange="updateLivePreview()">
                                                <label class="form-check-label fw-bold small text-dark cursor-pointer" for="scopeCity">
                                                    🏙️ City Level
                                                </label>
                                            </div>
                                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">Free F.O.R. applicable strictly within City limits (e.g. Mumbai, Pune).</div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="p-3 border rounded-3 bg-white h-100 cursor-pointer scope-card" onclick="selectScope('state')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="for_scope" id="scopeState" value="state" {{ old('for_scope', $vendor->for_scope ?? 'pan_india') == 'state' ? 'checked' : '' }} onchange="updateLivePreview()">
                                                <label class="form-check-label fw-bold small text-dark cursor-pointer" for="scopeState">
                                                    🗺️ State Level
                                                </label>
                                            </div>
                                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">Free F.O.R. valid across all districts within your registered State.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="p-3 border rounded-3 bg-white h-100 cursor-pointer scope-card border-success bg-success bg-opacity-10" onclick="selectScope('pan_india')">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="for_scope" id="scopePanIndia" value="pan_india" {{ old('for_scope', $vendor->for_scope ?? 'pan_india') == 'pan_india' ? 'checked' : '' }} onchange="updateLivePreview()">
                                                <label class="form-check-label fw-bold small text-success cursor-pointer" for="scopePanIndia">
                                                    🇮🇳 Pan-India
                                                </label>
                                            </div>
                                            <div class="small text-muted mt-1" style="font-size: 0.75rem;">Free F.O.R. applies to all reachable construction sites across India.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Scope Details / Buyer Terms -->
                            <div class="mb-2">
                                <label class="form-label small fw-semibold text-muted">Additional Delivery Terms / Pincode Notes (Optional)</label>
                                <input type="text" name="for_scope_details" class="form-control form-control-sm" value="{{ old('for_scope_details', $vendor->for_scope_details ?? 'Free unloading included at site accessible by 10-wheeler trucks.') }}" placeholder="e.g. Unloading at site included within 50km radius">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 mt-4 pt-2">
                        <button type="submit" class="btn btn-success px-4 py-2 fw-semibold" style="background-color: var(--garudo-primary); border: none;">
                            <i class="bi bi-check2-circle me-1"></i> Save Logistics Configuration
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Buyer View Preview Card -->
        <div class="col-lg-4">
            <div class="card-custom position-sticky" style="top: 80px;">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-eye text-primary me-2"></i>Buyer Product Page Preview</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary small">Live Preview</span>
                </div>

                <p class="small text-muted mb-3">Here is how your delivery timeline and F.O.R. badges will appear to contractors & buyers:</p>

                <div class="border rounded-3 p-3 bg-light shadow-sm">
                    <!-- Dispatch Timeline Badge -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="p-2 bg-success bg-opacity-10 rounded text-success">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold small text-dark" id="previewDispatchTitle">Standard Dispatch (2-4 Days)</div>
                            <div class="text-muted" style="font-size: 0.75rem;" id="previewDispatchSubtitle">Ships from {{ $vendor->primary_dispatch_state ?? 'Maharashtra, India' }}</div>
                        </div>
                    </div>

                    <!-- F.O.R. Free Delivery Badge -->
                    <div id="previewForBadgeBox" class="p-3 bg-white rounded border border-success">
                        <div class="d-flex align-items-center gap-2 text-success fw-bold small">
                            <i class="bi bi-shield-check fs-5"></i>
                            <span id="previewForHeadline">F.O.R. Free Delivery Available</span>
                        </div>
                        <div class="small text-dark mt-1" id="previewForConditionText">
                            Free Doorstep Freight for bulk orders over <strong>₹50,000</strong>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-2 pt-2 border-top">
                            <span class="badge bg-success bg-opacity-10 text-success" id="previewScopeBadge">
                                🇮🇳 Pan-India Scope
                            </span>
                            <span class="text-muted" style="font-size: 0.72rem;">Zero transit freight charge</span>
                        </div>
                    </div>
                </div>

                <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded border border-warning border-opacity-25 small text-dark">
                    <i class="bi bi-info-circle text-warning me-1"></i>
                    F.O.R. settings are calculated during customer checkout and quote generation.
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    function toggleCustomDispatch(val) {
        const box = document.getElementById('customDispatchBox');
        if (val === 'custom') {
            box.classList.remove('d-none');
        } else {
            box.classList.add('d-none');
        }
        updateLivePreview();
    }

    function toggleForOptions(checked) {
        const container = document.getElementById('forOptionsContainer');
        const previewBox = document.getElementById('previewForBadgeBox');
        if (checked) {
            container.classList.remove('d-none');
            previewBox.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
            previewBox.classList.add('d-none');
        }
        updateLivePreview();
    }

    function toggleConditionInputs(type) {
        const amountGroup = document.getElementById('amountInputGroup');
        const qtyGroup = document.getElementById('qtyInputGroup');
        if (type === 'amount') {
            amountGroup.classList.remove('d-none');
            qtyGroup.classList.add('d-none');
        } else {
            amountGroup.classList.add('d-none');
            qtyGroup.classList.remove('d-none');
        }
        updateLivePreview();
    }

    function selectScope(scope) {
        document.getElementById('scope' + (scope === 'city' ? 'City' : (scope === 'state' ? 'State' : 'PanIndia'))).checked = true;
        updateLivePreview();
    }

    function updateLivePreview() {
        // 1. Dispatch
        const dispatchSelect = document.getElementById('dispatchTimeSelect').value;
        const previewTitle = document.getElementById('previewDispatchTitle');
        if (dispatchSelect === '1-2') {
            previewTitle.innerText = 'Same Day / Next Day Dispatch (1-2 Days)';
        } else if (dispatchSelect === '2-4') {
            previewTitle.innerText = 'Standard Dispatch (2-4 Days)';
        } else if (dispatchSelect === '5-7') {
            previewTitle.innerText = 'Bulk Heavy Freight Dispatch (5-7 Days)';
        } else {
            const minDays = document.getElementById('customMinDays').value || '7';
            const maxDays = document.getElementById('customMaxDays').value || '15';
            previewTitle.innerText = `Custom Dispatch (${minDays}-${maxDays} Days)`;
        }

        // 2. FOR condition
        const isForEnabled = document.getElementById('freeShippingSwitch').checked;
        const previewForBox = document.getElementById('previewForBadgeBox');
        if (!isForEnabled) {
            previewForBox.classList.add('d-none');
            return;
        }
        previewForBox.classList.remove('d-none');

        const isAmount = document.getElementById('forTypeAmount').checked;
        const conditionText = document.getElementById('previewForConditionText');
        if (isAmount) {
            const minAmt = Number(document.getElementById('forMinAmount').value || 50000).toLocaleString('en-IN');
            conditionText.innerHTML = `Free Doorstep Freight for bulk orders over <strong>₹${minAmt}</strong>`;
        } else {
            const minQty = document.getElementById('forMinQty').value || '100';
            const unit = document.getElementById('forQtyUnit').value || 'Bags';
            conditionText.innerHTML = `Free Doorstep Freight for bulk orders over <strong>${minQty} ${unit}</strong>`;
        }

        // 3. Scope
        let scopeBadge = document.getElementById('previewScopeBadge');
        if (document.getElementById('scopeCity').checked) {
            scopeBadge.innerHTML = '🏙️ City Level Scope';
        } else if (document.getElementById('scopeState').checked) {
            scopeBadge.innerHTML = '🗺️ State Level Scope';
        } else {
            scopeBadge.innerHTML = '🇮🇳 Pan-India Scope';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateLivePreview();
    });
</script>
@endpush
@endsection
