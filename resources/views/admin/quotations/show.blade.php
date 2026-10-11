@extends('layouts.admin')

@section('title', 'Inspect Quotation #' . $quotation->quotation_number . ' - Admin Monitor')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('admin.quotations.index') }}" class="btn btn-light border btn-sm p-1 px-2 text-muted">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="page-header-title mb-0">Quotation #{{ $quotation->quotation_number }}</h4>
            {!! $quotation->getStatusBadge() !!}
        </div>
        <p class="page-header-subtitle mb-0">Platform Audit & Commercial Terms Verification Workbench</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.quotations.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i> Back to Monitor
        </a>
        <a href="{{ route('admin.quotations.vendor-analytics') }}" class="btn btn-warning btn-sm px-3 fw-bold text-dark" style="border-radius: 10px;">
            <i class="bi bi-bar-chart-line-fill me-1"></i> Vendor Scorecard
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div><strong>Success:</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Buyer & Material Details -->
    <div class="col-lg-7">
        <!-- Product & Requirement Specifications Card -->
        <div class="garudo-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam text-primary me-2"></i>Product & Requirement Details</h6>
                <span class="badge bg-light text-muted border small">{{ $quotation->urgency_level }}</span>
            </div>

            @if($quotation->product)
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-3">
                    <img src="{{ $quotation->product->primaryImage ? asset('storage/'.$quotation->product->primaryImage->file_path) : 'https://placehold.co/80x80?text=Item' }}" 
                         class="rounded-3 border bg-white" style="width: 65px; height: 65px; object-fit: cover;" alt="">
                    <div class="flex-grow-1">
                        <span class="badge bg-navy text-white px-2 py-0.5 mb-1" style="font-size: 0.72rem;">{{ $quotation->product->category->name ?? 'Material' }}</span>
                        <h6 class="fw-bold text-dark mb-1">
                            <a href="{{ route('admin.products.show', $quotation->product->id) }}" class="text-dark text-decoration-none">
                                {{ $quotation->product->name }} <i class="bi bi-box-arrow-up-right small text-muted"></i>
                            </a>
                        </h6>
                        <span class="small text-muted">SKU: <strong>{{ $quotation->product->sku }}</strong> | Catalog Price: <strong>₹{{ number_format($quotation->product->selling_price, 2) }}</strong></span>
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
                    <label class="form-label small text-muted mb-0">Target Rate Expected</label>
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
                    <label class="form-label small text-muted mb-0">Dispatch Urgency</label>
                    <div>{!! $quotation->getUrgencyBadge() !!}</div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Expected Delivery by Site</label>
                    <div class="fw-semibold text-dark small">
                        {{ $quotation->expected_delivery_date ? $quotation->expected_delivery_date->format('d M Y') : 'Flexible' }}
                    </div>
                </div>

                @if($quotation->buyer_notes)
                    <div class="col-12">
                        <label class="form-label small text-muted mb-1">Buyer Notes & Instructions</label>
                        <div class="p-3 rounded-3 bg-light border text-dark small">
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

        <!-- Buyer Contact & Delivery Destination Card -->
        <div class="garudo-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-geo-alt-fill text-success me-2"></i>Buyer Contact & Delivery Destination</h6>
                <span class="badge bg-light text-dark border small">{{ $quotation->project_type ?? 'Construction' }}</span>
            </div>

            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Contact Person</label>
                    <div class="fw-bold text-dark">{{ $quotation->buyer_name }}</div>
                </div>

                <div class="col-sm-6">
                    <label class="form-label small text-muted mb-0">Company / Organization</label>
                    <div class="fw-bold text-dark">{{ $quotation->company_name ?? 'Individual' }}</div>
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

    <!-- Right Column: Vendor Proposal & Administrative Controls -->
    <div class="col-lg-5">
        <!-- Supplier & Vendor Info Card -->
        <div class="garudo-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-shop text-primary me-2"></i>Assigned Supplier / Merchant</h6>
                @if($quotation->vendor)
                    <a href="{{ route('admin.vendors.show', $quotation->vendor->id) }}" class="btn btn-outline-primary btn-sm px-2 py-0.5">
                        Vendor Profile <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>

            @if($quotation->vendor)
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light p-2.5 rounded-3 border text-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-buildings-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $quotation->vendor->company_name }}</h6>
                        <span class="small text-muted">{{ $quotation->vendor->email }} • {{ $quotation->vendor->phone }}</span>
                        <div class="mt-1">
                            <span class="badge bg-success-subtle text-success border border-success px-2 py-0.5" style="font-size: 0.7rem;">
                                {{ ucfirst($quotation->vendor->approval_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <span class="text-muted small">No vendor assigned</span>
            @endif
        </div>

        <!-- Vendor Commercial Proposal Card -->
        <div class="garudo-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calculator-fill text-success me-2"></i>Vendor Commercial Quote</h6>
                    @if($quotation->quoted_at)
                        <small class="text-muted">Quoted on {{ $quotation->quoted_at->format('d M Y, h:i A') }}</small>
                    @endif
                </div>
                {!! $quotation->getStatusBadge() !!}
            </div>

            @if($quotation->total_amount > 0)
                <div class="p-3 rounded-3 border mb-3" style="background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);">
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Unit Rate:</span>
                        <strong class="text-dark">₹{{ number_format($quotation->unit_price, 2) }} / {{ $quotation->unit }}</strong>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Base Total:</span>
                        <span class="fw-semibold text-dark">₹{{ number_format($quotation->unit_price * $quotation->quantity, 2) }}</span>
                    </div>
                    @if($quotation->discount_amount > 0)
                        <div class="d-flex justify-content-between small text-danger mb-1">
                            <span>Discount:</span>
                            <span>-₹{{ number_format($quotation->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>GST @ {{ $quotation->gst_rate }}%:</span>
                        <span class="fw-semibold text-dark">₹{{ number_format($quotation->gst_amount, 2) }}</span>
                    </div>
                    @if($quotation->shipping_amount > 0)
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Freight / Dispatch:</span>
                            <span class="fw-semibold text-dark">₹{{ number_format($quotation->shipping_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                        <strong class="text-dark">Grand Quoted Total:</strong>
                        <strong class="text-success fs-4">₹{{ number_format($quotation->total_amount, 2) }}</strong>
                    </div>
                </div>

                <div class="small mb-3">
                    <div class="mb-1 text-muted">Payment Terms: <strong class="text-dark">{{ $quotation->payment_terms ?? 'Standard B2B Terms' }}</strong></div>
                    <div class="mb-1 text-muted">Delivery Terms: <strong class="text-dark">{{ $quotation->delivery_terms ?? 'F.O.R. Site' }}</strong></div>
                    <div class="mb-1 text-muted">Validity: <strong class="text-dark">{{ $quotation->validity_days }} Days</strong> (Valid until {{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : 'N/A' }})</div>
                </div>

                @if($quotation->vendor_proposal_notes)
                    <div class="p-2.5 rounded bg-light border small text-dark mb-3">
                        <strong class="d-block text-muted mb-1">Vendor Proposal Note:</strong>
                        {{ $quotation->vendor_proposal_notes }}
                    </div>
                @endif

                @if($quotation->vendor_attachment_path)
                    <div class="mb-3">
                        <a href="{{ asset('storage/' . $quotation->vendor_attachment_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Download Formal Vendor Proposal Document
                        </a>
                    </div>
                @endif
            @else
                <div class="p-3 bg-light rounded text-center text-muted small mb-3">
                    <i class="bi bi-hourglass-split fs-4 d-block mb-1 text-warning"></i>
                    Vendor has not submitted a formal price quote yet.
                </div>
            @endif

            @if($quotation->converted_order_id)
                <div class="p-3 bg-success bg-opacity-10 border border-success rounded-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-success small"><i class="bi bi-trophy-fill me-1"></i> Converted to B2B Order</strong>
                            <span class="text-muted" style="font-size: 0.72rem;">Deal closed & purchase order generated</span>
                        </div>
                        <a href="{{ route('admin.orders.show', $quotation->converted_order_id) }}" class="btn btn-success btn-sm px-3 fw-bold">
                            View Order
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Administrative Moderation & Status Override Card -->
        <div class="garudo-card p-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-shield-lock-fill text-warning me-2"></i>Super Admin Override & Moderation
            </h6>

            <form action="{{ route('admin.quotations.update_status', $quotation->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark mb-1">Quotation Status</label>
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="pending" {{ $quotation->status === 'pending' ? 'selected' : '' }}>Pending Vendor Quote</option>
                        <option value="sent" {{ in_array($quotation->status, ['sent', 'quoted']) ? 'selected' : '' }}>Proposal Sent / Quoted</option>
                        <option value="accepted" {{ $quotation->status === 'accepted' ? 'selected' : '' }}>Accepted by Buyer</option>
                        <option value="converted_to_order" {{ $quotation->status === 'converted_to_order' ? 'selected' : '' }}>Converted to Bulk Order</option>
                        <option value="rejected" {{ $quotation->status === 'rejected' ? 'selected' : '' }}>Rejected / Declined</option>
                        <option value="expired" {{ $quotation->status === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="closed" {{ $quotation->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark mb-1">Internal Admin Notes / Audit Log</label>
                    <textarea name="admin_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. Followed up with vendor over phone. Buyer confirmed payment terms.">{{ old('admin_notes', $quotation->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold py-2">
                    <i class="bi bi-check2-circle me-1"></i> Update Status & Save Notes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
