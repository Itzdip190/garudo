@extends('layouts.vendor')

@section('title', 'Quotation #' . $quotation->quotation_number . ' - Proposal Workbench')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('vendor.quotations.index') }}" class="btn btn-light border btn-sm p-1 px-2 text-muted" title="Back to RFQ List">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="fw-bold mb-0 text-dark">Quotation #{{ $quotation->quotation_number }}</h4>
            {!! $quotation->getStatusBadge() !!}
        </div>
        <p class="text-muted mb-0 small">Received on {{ $quotation->created_at->format('d M Y, h:i A') }} • Monitored by Garudo Trade Operations</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vendor.quotations.index') }}" class="btn btn-light border btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> All Quotations
        </a>
        @if($quotation->converted_order_id)
            <a href="{{ route('vendor.orders.show', $quotation->converted_order_id) }}" class="btn btn-dark btn-sm px-3 fw-bold" style="border-radius: 8px;">
                <i class="bi bi-trophy-fill text-warning me-1"></i> View Converted Order
            </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><strong>Success:</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5 text-info"></i>
            <div>{{ session('info') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Buyer Requirement & Location Specs -->
    <div class="col-lg-7">
        <!-- 1. Product & Requirement Details Card -->
        <div class="card-custom mb-4 p-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam text-primary me-2"></i>Requested Material & Specs</h6>
                <span class="badge bg-light text-muted border small">{{ $quotation->urgency_level }}</span>
            </div>

            @if($quotation->product)
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-3">
                    <img src="{{ $quotation->product->primaryImage ? asset('storage/'.$quotation->product->primaryImage->file_path) : 'https://placehold.co/80x80?text=Product' }}" 
                         class="rounded-3 border bg-white" style="width: 70px; height: 70px; object-fit: cover;" alt="{{ $quotation->product->name }}">
                    <div class="flex-grow-1">
                        <span class="badge bg-navy text-white px-2 py-0.5 mb-1" style="font-size: 0.72rem;">{{ $quotation->product->category->name ?? 'Industrial' }}</span>
                        <h6 class="fw-bold text-dark mb-1">
                            <a href="{{ route('products.show', $quotation->product->slug) }}" target="_blank" class="text-dark text-decoration-none">
                                {{ $quotation->product->name }} <i class="bi bi-box-arrow-up-right small text-muted"></i>
                            </a>
                        </h6>
                        <div class="small text-muted">
                            SKU: <strong>{{ $quotation->product->sku }}</strong> | Catalog Price: <strong class="text-dark">₹{{ number_format($quotation->product->selling_price, 2) }}</strong>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Requested Quantity</label>
                    <div class="fs-5 fw-bold text-dark">
                        {{ number_format($quotation->quantity) }} <span class="fs-6 fw-normal text-muted">{{ $quotation->unit }}</span>
                    </div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Buyer Target Price</label>
                    <div class="fs-5 fw-bold {{ $quotation->target_price ? 'text-primary' : 'text-muted' }}">
                        {{ $quotation->target_price ? '₹' . number_format($quotation->target_price, 2) . ' / ' . $quotation->unit : 'Not specified' }}
                    </div>
                </div>

                @if($quotation->selected_variant)
                    <div class="col-12">
                        <label class="form-label small text-muted mb-0">Selected Grade / Variant</label>
                        <div class="p-2 rounded bg-light border fw-semibold text-dark small">
                            <i class="bi bi-tag-fill text-primary me-1"></i> {{ $quotation->selected_variant }}
                        </div>
                    </div>
                @endif

                @if(!empty($quotation->custom_variations))
                    <div class="col-12">
                        <label class="form-label small text-muted mb-1">Custom Variations & Specifications</label>
                        <div class="table-responsive rounded border">
                            <table class="table table-sm table-bordered mb-0 small">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Specification / Question</th>
                                        <th>Buyer Requirement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotation->custom_variations as $key => $val)
                                        <tr>
                                            <td class="bg-light text-muted w-50">{{ $key }}</td>
                                            <td class="fw-semibold text-dark">{{ $val ?: 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Urgency Level</label>
                    <div>{!! $quotation->getUrgencyBadge() !!}</div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Expected Delivery by Site</label>
                    <div class="fw-semibold text-dark small">
                        {{ $quotation->expected_delivery_date ? $quotation->expected_delivery_date->format('d M Y') : 'Flexible / As per schedule' }}
                    </div>
                </div>

                @if($quotation->buyer_notes)
                    <div class="col-12">
                        <label class="form-label small text-muted mb-1">Buyer Notes & Instructions</label>
                        <div class="p-3 rounded-3 bg-light border text-dark small" style="line-height: 1.5;">
                            {{ $quotation->buyer_notes }}
                        </div>
                    </div>
                @endif

                @if($quotation->attachment_path)
                    <div class="col-12">
                        <label class="form-label small text-muted mb-1">Attached Drawing / BOQ Specification Document</label>
                        <div class="p-2.5 rounded-3 bg-white border d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-3"></i>
                                <div>
                                    <strong class="d-block text-dark small">Buyer BOQ / Drawing Sheet</strong>
                                    <span class="text-muted" style="font-size: 0.72rem;">Uploaded with quotation request</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $quotation->attachment_path) }}" target="_blank" class="btn btn-outline-primary btn-sm px-3 fw-semibold">
                                <i class="bi bi-download me-1"></i> View / Download
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. Buyer Contact & Delivery Site Card -->
        <div class="card-custom mb-4 p-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-success me-2"></i>Buyer Contact & Delivery Destination</h6>
                <span class="badge bg-success bg-opacity-10 text-success small">Verified B2B Direct</span>
            </div>

            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Contact Person</label>
                    <div class="fw-bold text-dark">{{ $quotation->buyer_name }}</div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Company / Firm Name</label>
                    <div class="fw-bold text-dark">{{ $quotation->company_name ?? 'Individual / Contractor' }}</div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Mobile Phone</label>
                    <div>
                        <a href="tel:{{ $quotation->buyer_phone }}" class="text-decoration-none fw-semibold text-dark">
                            <i class="bi bi-telephone-fill text-success me-1"></i> {{ $quotation->buyer_phone }}
                        </a>
                    </div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Email Address</label>
                    <div>
                        <a href="mailto:{{ $quotation->buyer_email }}" class="text-decoration-none fw-semibold text-dark">
                            <i class="bi bi-envelope-fill text-primary me-1"></i> {{ $quotation->buyer_email }}
                        </a>
                    </div>
                </div>

                @if($quotation->gstin)
                    <div class="col-sm-6">
                        <label class="form-label small text-muted mb-0">GSTIN</label>
                        <div class="font-monospace fw-bold text-dark">{{ $quotation->gstin }}</div>
                    </div>
                @endif

                @if($quotation->project_type)
                    <div class="col-sm-6">
                        <label class="form-label small text-muted mb-0">Project Category</label>
                        <div class="fw-semibold text-dark">{{ $quotation->project_type }}</div>
                    </div>
                @endif

                <div class="col-12">
                    <label class="form-label small text-muted mb-0">Delivery Address (Site)</label>
                    <div class="p-2.5 bg-light rounded border text-dark small">
                        <i class="bi bi-truck text-muted me-1"></i>
                        {{ $quotation->delivery_address ? $quotation->delivery_address . ', ' : '' }}
                        <strong>{{ $quotation->delivery_city ?? 'City' }}</strong>, 
                        {{ $quotation->delivery_state ?? 'State' }} - {{ $quotation->delivery_pincode ?? 'Pincode' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Interactive Price Proposal Calculator & Actions -->
    <div class="col-lg-5">
        <div class="card-custom p-4 shadow-sm border border-primary border-opacity-25 sticky-top" style="top: 80px; z-index: 10; background: #FFFFFF;">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calculator text-primary me-2"></i>Price Proposal Workbench</h6>
                    <small class="text-muted">Calculate commercial terms and issue proposal</small>
                </div>
                {!! $quotation->getStatusBadge() !!}
            </div>

            <!-- If Accepted: Convert to Order Banner -->
            @if($quotation->isAccepted())
                <div class="p-3 mb-3 rounded-3 border border-success bg-success bg-opacity-10">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <strong class="d-block text-success"><i class="bi bi-check-circle-fill me-1"></i>Quote Accepted by Buyer!</strong>
                            <span class="small text-muted">Buyer accepted price of ₹{{ number_format($quotation->total_amount, 2) }}.</span>
                        </div>
                        <form action="{{ route('vendor.quotations.convert', $quotation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm fw-bold px-3 shadow-sm">
                                <i class="bi bi-trophy-fill me-1"></i> Convert to Order
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <form action="{{ route('vendor.quotations.send_proposal', $quotation->id) }}" method="POST" enctype="multipart/form-data" id="priceProposalForm">
                @csrf

                <!-- Pricing Calculation Inputs -->
                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-dark mb-1">Unit Price (Ex-Factory / Base Rate) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">₹</span>
                            <input type="number" step="0.01" name="unit_price" id="propUnitPrice" class="form-control form-control-lg fs-6 font-monospace fw-bold" 
                                   value="{{ old('unit_price', $quotation->unit_price > 0 ? $quotation->unit_price : ($quotation->product->wholesale_price ?: $quotation->product->selling_price)) }}" 
                                   placeholder="0.00" required oninput="calculateQuotationTotal()">
                            <span class="input-group-text bg-light small text-muted">/ {{ $quotation->unit }}</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark mb-1">GST Rate (%)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" step="0.01" name="gst_rate" id="propGstRate" class="form-control font-monospace" 
                                   value="{{ old('gst_rate', $quotation->gst_rate > 0 ? $quotation->gst_rate : ($quotation->product->gst_rate ?? 18)) }}" 
                                   oninput="calculateQuotationTotal()">
                            <span class="input-group-text bg-light">%</span>
                        </div>
                    </div>

                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark mb-1">Freight / Shipping (₹)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="shipping_amount" id="propShipping" class="form-control font-monospace" 
                                   value="{{ old('shipping_amount', $quotation->shipping_amount ?? 0) }}" 
                                   oninput="calculateQuotationTotal()">
                        </div>
                    </div>

                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark mb-1">Special Discount (₹)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">₹</span>
                            <input type="number" step="0.01" name="discount_amount" id="propDiscount" class="form-control font-monospace" 
                                   value="{{ old('discount_amount', $quotation->discount_amount ?? 0) }}" 
                                   oninput="calculateQuotationTotal()">
                        </div>
                    </div>

                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark mb-1">Validity (Days)</label>
                        <input type="number" name="validity_days" class="form-control form-control-sm" value="{{ old('validity_days', $quotation->validity_days ?? 15) }}" min="1" max="180">
                    </div>
                </div>

                <!-- Live Computed Summary Box -->
                <div class="p-3 rounded-3 border mb-3" style="background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Quantity:</span>
                        <strong class="text-dark">{{ number_format($quotation->quantity) }} {{ $quotation->unit }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Base Amount:</span>
                        <span class="fw-semibold text-dark" id="displayBaseTotal">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Estimated GST:</span>
                        <span class="fw-semibold text-dark" id="displayGstAmount">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Freight / Unloading:</span>
                        <span class="fw-semibold text-dark" id="displayShippingAmount">₹0.00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                        <strong class="text-dark">Net Quoted Total:</strong>
                        <strong class="text-success fs-4" id="displayGrandTotal">₹0.00</strong>
                    </div>
                </div>

                <!-- Terms & Conditions Inputs -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark mb-1">Payment Terms</label>
                    <input type="text" name="payment_terms" class="form-control form-control-sm" 
                           value="{{ old('payment_terms', $quotation->payment_terms ?? '100% Against Dispatch / Proforma') }}" 
                           placeholder="e.g. 50% Advance, 50% on Unloading">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark mb-1">Delivery Lead Time & F.O.R. Terms</label>
                    <input type="text" name="delivery_terms" class="form-control form-control-sm" 
                           value="{{ old('delivery_terms', $quotation->delivery_terms ?? 'F.O.R. Site Delivery within 3-5 working days') }}" 
                           placeholder="e.g. F.O.R. Site Delivery within 3 days">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark mb-1">Vendor Proposal Notes for Buyer</label>
                    <textarea name="vendor_proposal_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. Mill test certificate included. Unloading at site by buyer. Price valid for full lot.">{{ old('vendor_proposal_notes', $quotation->vendor_proposal_notes) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark mb-1">Attach Formal Quotation PDF / Sheet <span class="text-muted fw-normal">(Optional)</span></label>
                    <input type="file" name="vendor_attachment" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                    @if($quotation->vendor_attachment_path)
                        <div class="mt-1 small">
                            <a href="{{ asset('storage/' . $quotation->vendor_attachment_path) }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="bi bi-file-earmark-check me-1"></i> Current Proposal Document Attached
                            </a>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 py-2.5 fw-bold shadow-sm mb-2" style="background: #2563EB; border: none; border-radius: 10px;">
                    <i class="bi bi-send-check me-1"></i> {{ $quotation->isQuoted() ? 'Update & Re-send Proposal' : 'Send Quotation to Buyer' }}
                </button>
            </form>

            @if(!$quotation->isConverted())
                <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#rejectQuoteModal">
                    <i class="bi bi-x-circle me-1"></i> Decline / Reject RFQ
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Decline Quotation -->
<div class="modal fade" id="rejectQuoteModal" tabindex="-1" aria-labelledby="rejectQuoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom bg-danger text-white">
                <h6 class="modal-title fw-bold" id="rejectQuoteModalLabel"><i class="bi bi-x-circle me-2"></i>Decline Quotation Request</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vendor.quotations.reject', $quotation->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Please specify a reason for declining this request. The buyer and platform admin will be notified.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Reason for Declining <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" rows="3" class="form-control" placeholder="e.g. Current capacity fully booked / Requested custom spec not available / Delivery location outside operational zone." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold">
                        Confirm Decline
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const quotationQty = {{ $quotation->quantity }};

function calculateQuotationTotal() {
    const unitPrice = parseFloat(document.getElementById('propUnitPrice').value) || 0;
    const gstRate = parseFloat(document.getElementById('propGstRate').value) || 0;
    const shipping = parseFloat(document.getElementById('propShipping').value) || 0;
    const discount = parseFloat(document.getElementById('propDiscount').value) || 0;

    const baseAmount = unitPrice * quotationQty;
    const taxableAmount = Math.max(0, baseAmount - discount);
    const gstAmount = (taxableAmount * gstRate) / 100;
    const grandTotal = taxableAmount + gstAmount + shipping;

    document.getElementById('displayBaseTotal').innerText = '₹' + baseAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('displayGstAmount').innerText = '₹' + gstAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('displayShippingAmount').innerText = '₹' + shipping.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('displayGrandTotal').innerText = '₹' + grandTotal.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Initial calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateQuotationTotal();
});
</script>
@endsection
