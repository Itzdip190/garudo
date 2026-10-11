@extends('layouts.admin')

@section('title', 'Inspect Sample Request #' . $sample->request_number)

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('admin.samples.index') }}" class="btn btn-light border btn-sm p-1 px-2 text-muted">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="page-header-title mb-0">Sample Request #{{ $sample->request_number }}</h4>
            {!! $sample->getStatusBadge() !!}
        </div>
        <p class="page-header-subtitle mb-0">Submitted on {{ $sample->created_at->format('d F Y, h:i A') }} ({{ $sample->created_at->diffForHumans() }})</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('admin.samples.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 10px;">
            <i class="bi bi-arrow-left me-1"></i> Back to Monitor
        </a>
        <a href="{{ route('admin.samples.vendor-analytics') }}" class="btn btn-warning btn-sm px-3 fw-bold text-dark" style="border-radius: 10px;">
            <i class="bi bi-graph-up-arrow me-1"></i> Vendor Analytics
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Product & Commercial Overview -->
    <div class="col-lg-8">
        <!-- 1. Product & Specimen Card -->
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-3">
                <i class="bi bi-box-seam text-success me-2"></i>Product Material & Specimen
            </h6>
            <div class="row g-3 align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $sample->product->primaryImage ? asset('storage/'.$sample->product->primaryImage->file_path) : 'https://placehold.co/200x200?text=Material' }}" 
                         class="img-fluid rounded-3 border" style="max-height: 140px; object-fit: contain;" alt="{{ $sample->product->name }}">
                </div>
                <div class="col-md-9">
                    <span class="badge bg-light text-muted border small mb-1">{{ $sample->product->category->name ?? 'Category' }}</span>
                    <h5 class="fw-bold text-dark mb-1">
                        <a href="{{ route('admin.products.show', $sample->product->id) }}" class="text-dark text-decoration-none">
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
                                <span class="text-muted d-block">Quantity Requested:</span>
                                <strong class="text-dark fs-6">{{ $sample->quantity }} Unit(s)</strong>
                            </div>
                            <div class="col-12 mt-2 pt-2 border-top">
                                <span class="text-muted d-block">Specimen Size / Description:</span>
                                <strong class="text-dark">{{ $sample->product->sample_unit_description ?? 'Standard Manufacturer Specimen' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Commercial Pricing & Logistics Summary -->
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-3">
                <i class="bi bi-currency-rupee text-primary me-2"></i>Commercial Pricing & Courier Tracking
            </h6>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="text-muted small d-block">Sample Unit Rate:</span>
                        <strong class="text-dark">₹{{ number_format($sample->sample_price, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="text-muted small d-block">Courier Handling Fee:</span>
                        <strong class="text-dark">₹{{ number_format($sample->sample_shipping_fee, 2) }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="text-muted small d-block">Total Amount:</span>
                        <strong class="text-success fs-6">₹{{ number_format($sample->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-light rounded-3 border">
                <div class="row g-2 small">
                    <div class="col-md-4">
                        <span class="text-muted d-block">Courier Partner:</span>
                        <strong>{{ $sample->courier_partner ?? 'Not Assigned' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted d-block">Tracking / AWB Number:</span>
                        <strong class="text-primary font-monospace">{{ $sample->tracking_number ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted d-block">Dispatched Date:</span>
                        <strong>{{ $sample->dispatched_at ? $sample->dispatched_at->format('d M Y, h:i A') : 'Pending' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Conversion Tracking (If Bulk Order Placed) -->
        @if($sample->status === 'converted_to_order' || $sample->converted_order_id)
            <div class="garudo-card p-4 mb-4 border border-success border-opacity-50" style="background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-trophy-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-success mb-1">Commercial Bulk Order Converted!</h6>
                        <p class="small text-muted mb-0">Buyer verified the sample and subsequently placed a full commercial order with this vendor.</p>
                        @if($sample->convertedOrder)
                            <a href="{{ route('admin.orders.show', $sample->convertedOrder->id) }}" class="btn btn-sm btn-dark mt-2 fw-bold">
                                View Converted Commercial Order #{{ $sample->convertedOrder->order_number }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Buyer, Vendor & Admin Moderation -->
    <div class="col-lg-4">
        <!-- 1. Customer Details Card -->
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-person-fill text-primary me-2"></i>Customer / Buyer Profile
            </h6>

            <div class="mb-2">
                <strong class="text-dark fs-6 d-block">{{ $sample->buyer_name }}</strong>
                @if($sample->company_name)
                    <span class="text-muted small d-block"><i class="bi bi-building me-1"></i>{{ $sample->company_name }}</span>
                @endif
            </div>

            <div class="mb-2 small">
                <i class="bi bi-telephone text-success me-1"></i> <a href="tel:{{ $sample->buyer_phone }}" class="text-dark fw-bold">{{ $sample->buyer_phone }}</a>
            </div>

            <div class="mb-3 small">
                <i class="bi bi-envelope text-primary me-1"></i> <a href="mailto:{{ $sample->buyer_email }}" class="text-dark">{{ $sample->buyer_email }}</a>
            </div>

            <div class="p-2.5 bg-light rounded border mb-3 small">
                <strong class="text-muted d-block mb-1">Delivery Address:</strong>
                {{ $sample->delivery_address }}<br>
                <strong>{{ $sample->city }}, {{ $sample->state }} - {{ $sample->pincode }}</strong>
            </div>

            @if($sample->project_type)
                <div class="mb-2 small">
                    <span class="text-muted d-block">Project Type:</span>
                    <span class="badge bg-secondary-subtle text-dark border">{{ $sample->project_type }}</span>
                </div>
            @endif

            @if($sample->notes)
                <div class="small">
                    <span class="text-muted d-block">Buyer Notes:</span>
                    <p class="mb-0 text-dark bg-light p-2 rounded border">{{ $sample->notes }}</p>
                </div>
            @endif
        </div>

        <!-- 2. Vendor / Supplier Profile -->
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-shop text-warning me-2"></i>Vendor / Supplier Details
            </h6>

            <div class="mb-2">
                <a href="{{ route('admin.vendors.show', $sample->vendor->id) }}" class="fw-bold text-dark text-decoration-none fs-6 d-block">
                    {{ $sample->vendor->company_name ?? $sample->vendor->name }}
                </a>
                <span class="badge bg-success-subtle text-success border border-success small" style="font-size: 0.7rem;">
                    <i class="bi bi-patch-check-fill me-1"></i>Verified Supplier
                </span>
            </div>

            <div class="small text-muted mb-2">
                <i class="bi bi-telephone me-1 text-success"></i>{{ $sample->vendor->phone ?? 'N/A' }} | <i class="bi bi-envelope me-1 text-primary"></i>{{ $sample->vendor->email }}
            </div>

            <a href="{{ route('admin.vendors.show', $sample->vendor->id) }}" class="btn btn-outline-dark btn-sm w-100 mt-2">
                Inspect Vendor Account & KYCs
            </a>
        </div>

        <!-- 3. Admin Moderation & Status Override -->
        <div class="garudo-card p-4">
            <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-shield-lock-fill text-danger me-2"></i>Administrative Moderation
            </h6>

            <form action="{{ route('admin.samples.update_status', $sample->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Override Status</label>
                    <select name="status" class="form-select form-select-sm" required>
                        <option value="pending" {{ $sample->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ $sample->status === 'approved' ? 'selected' : '' }}>Approved (In Preparation)</option>
                        <option value="dispatched" {{ $sample->status === 'dispatched' ? 'selected' : '' }}>Dispatched (In Transit)</option>
                        <option value="delivered" {{ $sample->status === 'delivered' ? 'selected' : '' }}>Delivered to Buyer</option>
                        <option value="converted_to_order" {{ $sample->status === 'converted_to_order' ? 'selected' : '' }}>Converted to Commercial Order</option>
                        <option value="rejected" {{ $sample->status === 'rejected' ? 'selected' : '' }}>Rejected / Cancelled</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Courier Partner</label>
                    <input type="text" name="courier_partner" class="form-control form-control-sm" value="{{ $sample->courier_partner }}" placeholder="e.g. Delhivery Express">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Tracking / AWB Number</label>
                    <input type="text" name="tracking_number" class="form-control form-control-sm" value="{{ $sample->tracking_number }}" placeholder="e.g. TRK-849201">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Administrative Notes</label>
                    <textarea name="vendor_notes" rows="2" class="form-control form-control-sm" placeholder="Internal or moderation note...">{{ $sample->vendor_notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-garudo-primary btn-sm w-100 fw-bold">
                    <i class="bi bi-save me-1"></i> Update Sample Record
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
