@extends('layouts.vendor')

@section('title', 'Sample Request #' . $sample->request_number)

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('vendor.samples.index') }}" class="btn btn-light border btn-sm p-1 px-2 text-muted" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Sample Request #{{ $sample->request_number }}</h4>
            {!! $sample->getStatusBadge() !!}
        </div>
        <p class="text-muted mb-0 small">Submitted on {{ $sample->created_at->format('d F Y, h:i A') }} ({{ $sample->created_at->diffForHumans() }})</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('vendor.samples.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> Back to Sample Desk
        </a>
        <form action="{{ route('vendor.samples.convert-quote', $sample->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm px-3 fw-bold" style="border-radius: 8px;">
                <i class="bi bi-file-earmark-text me-1"></i> Convert to Bulk RFQ Quotation
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Product & Sample Fulfillment Details -->
    <div class="col-lg-8">
        <!-- 1. Product & Specimen Card -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-3">
                <i class="bi bi-box-seam text-success me-2"></i>Sample Material & Specification
            </h6>
            <div class="row g-3 align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $sample->product->primaryImage ? asset('storage/'.$sample->product->primaryImage->file_path) : 'https://placehold.co/200x200?text=Material' }}" 
                         class="img-fluid rounded-3 border" style="max-height: 140px; object-fit: contain;" alt="{{ $sample->product->name }}">
                </div>
                <div class="col-md-9">
                    <span class="badge bg-light text-muted border small mb-1">{{ $sample->product->category->name ?? 'Industrial Material' }}</span>
                    <h5 class="fw-bold text-dark mb-1">
                        <a href="{{ route('products.show', $sample->product->slug ?? '#') }}" target="_blank" class="text-dark text-decoration-none">
                            {{ $sample->product->name }}
                        </a>
                    </h5>
                    <p class="small text-muted mb-2">SKU: <strong class="text-dark">{{ $sample->product->sku }}</strong> | HSN: <strong class="text-dark">{{ $sample->product->hsn_code ?? 'N/A' }}</strong></p>

                    <div class="p-3 bg-light rounded-3 border">
                        <div class="row g-2 small">
                            <div class="col-6">
                                <span class="text-muted d-block">Sample Type:</span>
                                <strong>{!! $sample->getSampleTypeBadge() !!}</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block">Requested Quantity:</span>
                                <strong class="text-dark fs-6">{{ $sample->quantity }} Unit(s)</strong>
                            </div>
                            <div class="col-12 mt-2 pt-2 border-top">
                                <span class="text-muted d-block">Specimen Size / Unit Description:</span>
                                <strong class="text-dark">{{ $sample->product->sample_unit_description ?? 'Standard Manufacturer Specimen Pouch / Cut Piece' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Commercial Pricing & Payment Summary -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-3">
                <i class="bi bi-receipt text-primary me-2"></i>Sample Cost & Payment Status
            </h6>
            <div class="table-responsive">
                <table class="table table-bordered small align-middle mb-3">
                    <thead class="bg-light">
                        <tr>
                            <th>Item Description</th>
                            <th class="text-center">Rate (₹)</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Material Sample Piece</strong> ({{ $sample->product->name }})
                                <div class="text-muted small" style="font-size: 0.72rem;">{{ $sample->sample_type === 'free' ? 'Sponsored (100% Free)' : 'Paid Unit' }}</div>
                            </td>
                            <td class="text-center">₹{{ number_format($sample->sample_price, 2) }}</td>
                            <td class="text-center">{{ $sample->quantity }}</td>
                            <td class="text-end fw-semibold">₹{{ number_format($sample->sample_price * $sample->quantity, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-semibold text-muted">Courier / Dispatch Handling Fee:</td>
                            <td class="text-end fw-semibold">₹{{ number_format($sample->sample_shipping_fee, 2) }}</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold text-dark fs-6">Grand Total:</td>
                            <td class="text-end fw-bold text-success fs-6">₹{{ number_format($sample->total_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 border">
                <div>
                    <span class="text-muted small d-block">Payment Method:</span>
                    <strong class="text-dark text-capitalize">{{ $sample->payment_method ?? 'Free Voucher' }}</strong>
                </div>
                <div>
                    <span class="text-muted small d-block">Payment Status:</span>
                    {!! $sample->getPaymentStatusBadge() !!}
                </div>
            </div>
        </div>

        <!-- 3. Logistics & Dispatch Tracking Card -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-truck text-success me-2"></i>Logistics & Dispatch Information
                </h6>
                @if(in_array($sample->status, ['pending', 'approved', 'dispatched']))
                    <button type="button" class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#dispatchDetailModal">
                        <i class="bi bi-pencil-square me-1"></i> {{ $sample->tracking_number ? 'Update Tracking' : 'Enter Dispatch Courier' }}
                    </button>
                @endif
            </div>

            @if($sample->tracking_number)
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Courier Partner:</span>
                            <strong class="text-dark fs-6">{{ $sample->courier_partner }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Tracking / AWB Number:</span>
                            <strong class="text-primary font-monospace fs-6">{{ $sample->tracking_number }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Dispatched On:</span>
                            <strong class="text-dark">{{ $sample->dispatched_at ? $sample->dispatched_at->format('d M Y, h:i A') : 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Delivered On:</span>
                            <strong class="text-dark">{{ $sample->delivered_at ? $sample->delivered_at->format('d M Y, h:i A') : 'In Transit' }}</strong>
                        </div>
                    </div>
                    @if($sample->vendor_notes)
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="text-muted small d-block">Vendor Dispatch Notes:</span>
                                <p class="mb-0 text-dark small">{!! nl2br(e($sample->vendor_notes)) !!}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-4 bg-light rounded-3 border">
                    <i class="bi bi-box-seam text-muted fs-2"></i>
                    <p class="text-muted small mt-2 mb-3">Sample is waiting to be dispatched. Package the specimen and add tracking information.</p>
                    <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#dispatchDetailModal">
                        <i class="bi bi-truck me-1"></i> Dispatch Sample Now
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Buyer Details & Actions -->
    <div class="col-lg-4">
        <!-- 1. Buyer & Project Profile Card -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-person-circle text-primary me-2"></i>Buyer Profile & Site Details
            </h6>

            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px;">
                    {{ strtoupper(substr($sample->buyer_name, 0, 1)) }}
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-0">{{ $sample->buyer_name }}</h6>
                    @if($sample->company_name)
                        <span class="text-muted small"><i class="bi bi-building me-1"></i>{{ $sample->company_name }}</span>
                    @else
                        <span class="badge bg-light text-muted border small">Individual Buyer</span>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <span class="text-muted small d-block mb-1"><i class="bi bi-telephone text-success me-1"></i> Phone:</span>
                <a href="tel:{{ $sample->buyer_phone }}" class="fw-bold text-dark text-decoration-none">{{ $sample->buyer_phone }}</a>
            </div>

            <div class="mb-3">
                <span class="text-muted small d-block mb-1"><i class="bi bi-envelope text-primary me-1"></i> Email:</span>
                <a href="mailto:{{ $sample->buyer_email }}" class="text-dark text-decoration-none small">{{ $sample->buyer_email }}</a>
            </div>

            <div class="mb-3">
                <span class="text-muted small d-block mb-1"><i class="bi bi-geo-alt text-danger me-1"></i> Delivery Address:</span>
                <p class="mb-0 text-dark small bg-light p-2.5 rounded border">
                    {{ $sample->delivery_address }}<br>
                    <strong>{{ $sample->city }}, {{ $sample->state }} - {{ $sample->pincode }}</strong>
                </p>
            </div>

            @if($sample->project_type)
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1"><i class="bi bi-tools text-warning me-1"></i> Project Type / Intended Use:</span>
                    <span class="badge bg-secondary-subtle text-dark border p-2 small w-100 text-start">{{ $sample->project_type }}</span>
                </div>
            @endif

            @if($sample->notes)
                <div class="mb-3">
                    <span class="text-muted small d-block mb-1"><i class="bi bi-chat-left-text text-info me-1"></i> Buyer Special Notes:</span>
                    <p class="mb-0 text-muted small bg-light p-2 rounded border">{!! nl2br(e($sample->notes)) !!}</p>
                </div>
            @endif

            <div class="row g-2 pt-2 border-top">
                <div class="col-6">
                    <a href="tel:{{ $sample->buyer_phone }}" class="btn btn-outline-dark btn-sm w-100 fw-semibold">
                        <i class="bi bi-telephone me-1"></i> Call Buyer
                    </a>
                </div>
                <div class="col-6">
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $sample->buyer_phone);
                        if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}?text=Hello%20{{ urlencode($sample->buyer_name) }},%20this%20is%20{{ urlencode($vendor->company_name) }}%20regarding%20your%20sample%20request%20#{{ $sample->request_number }}%20for%20{{ urlencode($sample->product->name) }}." target="_blank" class="btn btn-success btn-sm w-100 fw-semibold">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Action Controls Card -->
        <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-gear-fill text-dark me-2"></i>Sample Workflow Actions
            </h6>

            @if($sample->status === 'pending')
                <form action="{{ route('vendor.samples.approve', $sample->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm w-100 py-2 fw-bold mb-2">
                        <i class="bi bi-check-circle me-1"></i> Approve Sample Request
                    </button>
                </form>
            @endif

            @if(in_array($sample->status, ['pending', 'approved']))
                <button type="button" class="btn btn-primary btn-sm w-100 py-2 fw-bold mb-2" data-bs-toggle="modal" data-bs-target="#dispatchDetailModal">
                    <i class="bi bi-truck me-1"></i> Dispatch with Tracking #
                </button>
            @endif

            @if($sample->status === 'dispatched')
                <form action="{{ route('vendor.samples.deliver', $sample->id) }}" method="POST" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm w-100 py-2 fw-bold mb-2">
                        <i class="bi bi-box2-heart me-1"></i> Mark as Received / Delivered
                    </button>
                </form>
            @endif

            <form action="{{ route('vendor.samples.convert-quote', $sample->id) }}" method="POST" class="mb-2">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm w-100 py-2 fw-bold text-dark">
                    <i class="bi bi-file-earmark-text me-1"></i> Create Wholesale Quotation
                </button>
            </form>

            @if($sample->status === 'pending')
                <button type="button" class="btn btn-outline-danger btn-sm w-100 py-2 mt-2" data-bs-toggle="modal" data-bs-target="#rejectDetailModal">
                    <i class="bi bi-x-circle me-1"></i> Reject Request
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Dispatch Sample -->
<div class="modal fade" id="dispatchDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="bi bi-truck text-primary me-2"></i>Dispatch Sample #{{ $sample->request_number }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vendor.samples.dispatch', $sample->id) }}" method="POST">
                @csrf
                <div class="modal-body text-start p-4">
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="small text-muted">Recipient:</div>
                        <div class="fw-bold text-dark">{{ $sample->buyer_name }} ({{ $sample->buyer_phone }})</div>
                        <div class="small text-muted">{{ $sample->delivery_address }}, {{ $sample->city }}, {{ $sample->state }} - {{ $sample->pincode }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Courier / Logistics Partner <span class="text-danger">*</span></label>
                        <select name="courier_partner" class="form-select" required>
                            <option value="Delhivery Express" {{ $sample->courier_partner === 'Delhivery Express' ? 'selected' : '' }}>Delhivery Express</option>
                            <option value="BlueDart Air" {{ $sample->courier_partner === 'BlueDart Air' ? 'selected' : '' }}>BlueDart Air</option>
                            <option value="DTDC Courier" {{ $sample->courier_partner === 'DTDC Courier' ? 'selected' : '' }}>DTDC Courier</option>
                            <option value="Trackon Courier" {{ $sample->courier_partner === 'Trackon Courier' ? 'selected' : '' }}>Trackon Courier</option>
                            <option value="India Post Speed Post" {{ $sample->courier_partner === 'India Post Speed Post' ? 'selected' : '' }}>India Post Speed Post</option>
                            <option value="Self / Local Delivery Agent" {{ $sample->courier_partner === 'Self / Local Delivery Agent' ? 'selected' : '' }}>Self / Local Delivery Agent</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Tracking / AWB Number <span class="text-danger">*</span></label>
                        <input type="text" name="tracking_number" class="form-control" placeholder="e.g. BLUEDART-8492019" value="{{ $sample->tracking_number }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark">Dispatch / Packing Notes</label>
                        <textarea name="vendor_notes" rows="2" class="form-control" placeholder="e.g. Packaged in bubble test pouch. Lab test certificate included.">{{ $sample->vendor_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                        <i class="bi bi-send-check me-1"></i> Save & Confirm Dispatch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Reject Sample -->
@if($sample->status === 'pending')
    <div class="modal fade" id="rejectDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom">
                    <h6 class="modal-title fw-bold text-danger">
                        <i class="bi bi-x-circle me-2"></i>Reject Sample Request #{{ $sample->request_number }}
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('vendor.samples.reject', $sample->id) }}" method="POST">
                    @csrf
                    <div class="modal-body text-start p-4">
                        <p class="small text-muted mb-3">Please specify a clear reason for rejecting this sample request.</p>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Rejection Reason <span class="text-danger">*</span></label>
                            <select name="rejection_reason" class="form-select" required>
                                <option value="Sample stock currently exhausted. Contact for bulk direct delivery.">Sample stock currently exhausted</option>
                                <option value="Delivery address out of courier coverage area.">Address out of courier coverage</option>
                                <option value="Duplicate or unverified contact details.">Unverified contact / phone number</option>
                                <option value="Material requires site inspection before trial dispatch.">Site inspection required first</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light">
                        <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold">Confirm Rejection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
