@extends('layouts.vendor')

@section('title', 'Manufacturer Multi-Dealer Logistics & F.O.R. Policy Guide')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Manufacturer Multi-Dealer Delivery & F.O.R. Policy</h4>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">Supply Chain Framework</span>
        </div>
        <p class="text-muted mb-0 small">Standard operating procedure (SOP) and dispatch rules for manufacturers with multi-city dealer networks.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vendor.products.create') }}" class="btn btn-success btn-sm px-3" style="background-color: var(--garudo-primary); border: none; border-radius: 8px;">
            <i class="bi bi-plus-circle me-1"></i> Add Product with F.O.R.
        </a>
        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> Back to Catalog
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content Column -->
    <div class="col-lg-8">
        <!-- 1. Multi-City Delivery Logistics Architecture -->
        <div class="card-custom mb-4 p-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-diagram-3-fill text-success me-2"></i>1. Multi-City Dealer Fulfillment Architecture</h6>
                <span class="badge bg-light text-dark border small">Hybrid Model</span>
            </div>

            <p class="text-muted small mb-4">
                Jab ek manufacturer ke paas multiple cities me authorized dealers aur stock points hote hain, tab material fulfillment <strong>Hybrid 3-Tier Dispatch Model</strong> par work karta hai:
            </p>

            <div class="row g-3 mb-4">
                <!-- Tier 1: Factory Direct -->
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 h-100 bg-light" style="border-top: 3px solid var(--garudo-primary) !important;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-success text-white rounded-circle p-1" style="width: 24px; height: 24px;">1</span>
                            <h6 class="fw-bold mb-0 text-dark small">Factory Direct (FTL)</h6>
                        </div>
                        <p class="small text-muted mb-2"><strong>Capacity:</strong> ≥ 25–35 Tons (Full Truckload)</p>
                        <p class="small text-muted mb-0">Direct central plant se project site delivery with weighbridge slip and test certificate.</p>
                    </div>
                </div>

                <!-- Tier 2: Regional Depot STO -->
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 h-100 bg-light" style="border-top: 3px solid #0284C7 !important;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary text-white rounded-circle p-1" style="width: 24px; height: 24px;">2</span>
                            <h6 class="fw-bold mb-0 text-dark small">Depot / Mother Hub</h6>
                        </div>
                        <p class="small text-muted mb-2"><strong>Capacity:</strong> 10–25 Tons</p>
                        <p class="small text-muted mb-0">Manufacturer to regional warehouse primary freight (STO - Stock Transfer Order) buffer.</p>
                    </div>
                </div>

                <!-- Tier 3: Local Dealer Fulfillment -->
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 h-100 bg-light" style="border-top: 3px solid #F59E0B !important;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning text-dark rounded-circle p-1" style="width: 24px; height: 24px;">3</span>
                            <h6 class="fw-bold mb-0 text-dark small">Local City Dealer</h6>
                        </div>
                        <p class="small text-muted mb-2"><strong>Capacity:</strong> < 10 Tons (LTL / Part Load)</p>
                        <p class="small text-muted mb-0">Nearest city dealer local yard se same-day/next-day site dispatch with dealer margin.</p>
                    </div>
                </div>
            </div>

            <!-- PIN Code Geo-Routing Rule -->
            <div class="p-3 rounded-3 border bg-white">
                <h6 class="fw-bold small text-dark mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Automatic PIN-Code Dispatch Routing Workflow:</h6>
                <ol class="small text-muted ps-3 mb-0" style="line-height: 1.7;">
                    <li>Customer checkout par delivery PIN code enter karta hai.</li>
                    <li>Agar order <strong>Full Truckload (FTL ≥ 25MT)</strong> hai: Central Factory se direct truck book hoti hai (Lowest freight cost/Ton).</li>
                    <li>Agar order <strong>Part Load (&lt; 10MT)</strong> hai: System customer ke PIN code ke nearest (within 25–50km) authorized dealer ko order alert assign karta hai.</li>
                    <li>Local Dealer local transport ya tempo ke through material dispatch karta hai, aur manufacturer dealer ko commission credit transfer karta hai.</li>
                </ol>
            </div>
        </div>

        <!-- 2. Quantified F.O.R. Policy Formulation Matrix -->
        <div class="card-custom mb-4 p-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-check text-success me-2"></i>2. Quantified F.O.R. (Free On Road / Destination) Policy Matrix</h6>
                    <small class="text-muted">Standard building materials freight breakdown by Tonnage & Customer Tier</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle small mb-3">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Tier / Buyer Role</th>
                            <th>Minimum Order Qty (MOQ)</th>
                            <th>Minimum Order Value</th>
                            <th>F.O.R. Freight Policy</th>
                            <th>Transit Insurance & Unloading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-success">Tier 1: Distributor / Super-Stockist</strong>
                            </td>
                            <td>Full Truckload (FTL) <strong>25 – 35 Tons</strong> (500–700 Bags Cement)</td>
                            <td>₹5,00,000+</td>
                            <td><span class="badge bg-success">100% F.O.R. Destination</span> (Company pays complete freight)</td>
                            <td>Company covered; Consignee unloads</td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-primary">Tier 2: Authorized City Dealer</strong>
                            </td>
                            <td>Half Truckload (LTL) <strong>10 – 15 Tons</strong> (200–300 Bags)</td>
                            <td>₹2,00,000 – ₹4,99,999</td>
                            <td><span class="badge bg-primary">F.O.R. within 100 KM</span> (Flat surcharge beyond 100 KM)</td>
                            <td>Company covered</td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-warning text-dark">Tier 3: Project Contractor / Builder</strong>
                            </td>
                            <td>Medium Order <strong>5 – 9 Tons</strong> (100–180 Bags)</td>
                            <td>₹75,000 – ₹1,99,999</td>
                            <td><span class="badge bg-warning text-dark">50/50 Shared Freight</span> (Subsidized rate)</td>
                            <td>Standard transit coverage</td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Tier 4: Retail / Small Consumer</strong>
                            </td>
                            <td>Below MOQ <strong>&lt; 5 Tons</strong> (&lt; 100 Bags)</td>
                            <td>&lt; ₹75,000</td>
                            <td><span class="badge bg-secondary">Ex-Factory / Ex-Depot</span> (Buyer pays local freight)</td>
                            <td>Buyer arranges local transport</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-info p-3 small mb-0 rounded-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Definition of F.O.R. (Free On Road / Free On Rail):</strong>
                Material ki quoted price me factory se lekar buyer ke destination unloading gate tak ka transport freight aur transit insurance included hota hai. Unloading at site generally buyer/consignee ka scope hota hai unless crane unloading is pre-contracted.
            </div>
        </div>
    </div>

    <!-- Right Side Interactive Calculator -->
    <div class="col-lg-4">
        <div class="card-custom p-4 shadow-sm sticky-top" style="top: 80px;">
            <div class="border-bottom pb-2 mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calculator text-success me-2"></i>Live F.O.R. Eligibility Calculator</h6>
                <small class="text-muted">Simulate freight policy for any order size</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-dark">Material Category</label>
                <select id="calcCategory" class="form-select form-select-sm" onchange="calculateFor()">
                    <option value="cement">Cement (50kg Bags)</option>
                    <option value="steel">TMT Steel (Metric Tons)</option>
                    <option value="blocks">AAC Blocks / Bricks</option>
                    <option value="paints">Paints & Coatings</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-dark">Order Quantity</label>
                <div class="input-group input-group-sm">
                    <input type="number" id="calcQty" class="form-control" value="600" min="1" oninput="calculateFor()">
                    <span class="input-group-text bg-light" id="calcUnitLabel">Bags</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-dark">Destination Distance from Plant / Depot</label>
                <div class="input-group input-group-sm">
                    <input type="number" id="calcDistance" class="form-control" value="65" min="1" oninput="calculateFor()">
                    <span class="input-group-text bg-light">KM</span>
                </div>
            </div>

            <!-- Calculation Output Box -->
            <div class="p-3 bg-light rounded-3 border mb-3 text-center" id="calcResultBox">
                <div class="text-muted small mb-1">Calculated Weight: <strong id="resWeight">30.0 Metric Tons</strong></div>
                <div class="h5 fw-bold text-success mb-1" id="resStatus">100% F.O.R. DESTINATION (FREE FREIGHT)</div>
                <div class="small text-muted" id="resDispatchMode">Fulfillment: Direct Plant Dispatch (Full Truckload)</div>
            </div>

            <a href="{{ route('vendor.products.create') }}" class="btn btn-success w-100 btn-sm py-2 fw-bold" style="background-color: var(--garudo-primary); border: none;">
                Configure in Add Product Form
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function calculateFor() {
        const cat = document.getElementById('calcCategory').value;
        const qty = parseFloat(document.getElementById('calcQty').value) || 0;
        const distance = parseFloat(document.getElementById('calcDistance').value) || 0;
        const unitLabel = document.getElementById('calcUnitLabel');
        const resWeight = document.getElementById('resWeight');
        const resStatus = document.getElementById('resStatus');
        const resDispatch = document.getElementById('resDispatchMode');

        let weightInTons = 0;

        if (cat === 'cement') {
            unitLabel.innerText = 'Bags';
            weightInTons = (qty * 50) / 1000;
        } else if (cat === 'steel') {
            unitLabel.innerText = 'Tons';
            weightInTons = qty;
        } else if (cat === 'blocks') {
            unitLabel.innerText = 'Pieces';
            weightInTons = (qty * 16) / 1000;
        } else {
            unitLabel.innerText = 'Units';
            weightInTons = (qty * 20) / 1000;
        }

        resWeight.innerText = weightInTons.toFixed(1) + ' Metric Tons';

        if (weightInTons >= 25) {
            resStatus.className = 'h6 fw-bold text-success mb-1';
            resStatus.innerText = '100% F.O.R. DESTINATION (FREE FREIGHT)';
            resDispatch.innerText = 'Fulfillment: Factory Direct Dispatch (FTL Truck)';
        } else if (weightInTons >= 10) {
            if (distance <= 100) {
                resStatus.className = 'h6 fw-bold text-primary mb-1';
                resStatus.innerText = 'F.O.R. ELIGIBLE (WITHIN 100 KM RADIUS)';
            } else {
                resStatus.className = 'h6 fw-bold text-warning text-dark mb-1';
                resStatus.innerText = 'PARTIAL F.O.R. (+ ₹15/Ton/KM Beyond 100KM)';
            }
            resDispatch.innerText = 'Fulfillment: Regional Depot / Mother Warehouse';
        } else if (weightInTons >= 5) {
            resStatus.className = 'h6 fw-bold text-warning text-dark mb-1';
            resStatus.innerText = 'SHARED FREIGHT (50% COMPANY / 50% BUYER)';
            resDispatch.innerText = 'Fulfillment: Nearest Authorized City Dealer';
        } else {
            resStatus.className = 'h6 fw-bold text-secondary mb-1';
            resStatus.innerText = 'EX-DEPOT (FREIGHT PAYABLE BY BUYER)';
            resDispatch.innerText = 'Fulfillment: Local City Dealer Yard Pickup / Local Tempo';
        }
    }
</script>
@endpush
@endsection
