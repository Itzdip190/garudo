@extends('layouts.customer')

@section('title', 'Sample Request #' . $sample->request_number)

@section('content')
<div class="samples-page-container">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('customer.samples.index') }}" class="btn btn-light border btn-sm p-1 px-2 text-muted">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h1 class="h3 fw-bold text-dark mb-0">Sample Request #{{ $sample->request_number }}</h1>
                {!! $sample->getStatusBadge() !!}
            </div>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Placed on {{ $sample->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.show', $sample->product->slug ?? '#') }}" class="btn btn-warning btn-sm px-3 fw-bold text-dark" style="border-radius: 8px;">
                <i class="bi bi-cart-plus-fill me-1"></i> Order Commercial Quantity
            </a>
            <a href="{{ route('customer.samples.index') }}" class="btn btn-light border btn-sm px-3 fw-semibold" style="border-radius: 8px;">
                <i class="bi bi-arrow-left me-1"></i> My Samples
            </a>
        </div>
    </div>

    <!-- PROGRESS TIMELINE TRACKER -->
    @php
        $step1 = true; // Submitted
        $step2 = in_array($sample->status, ['approved', 'dispatched', 'delivered', 'converted_to_order']);
        $step3 = in_array($sample->status, ['dispatched', 'delivered', 'converted_to_order']);
        $step4 = in_array($sample->status, ['delivered', 'converted_to_order']);
        $isRejected = $sample->status === 'rejected';
    @endphp

    <div class="card-custom p-4 mb-4">
        <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-geo-alt-fill text-success me-2"></i>Sample Fulfillment & Tracking Timeline</h6>

        @if($isRejected)
            <div class="alert alert-danger p-3 rounded-3 mb-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-x-circle-fill fs-4 text-danger"></i>
                    <div>
                        <strong class="d-block">Sample Request Was Declined</strong>
                        <span class="small">{{ $sample->rejection_reason ?? 'The supplier was unable to dispatch the sample at this time.' }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="row text-center g-2 position-relative">
                <div class="col-3">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white mb-2 shadow-sm"
                         style="width: 44px; height: 44px; background: #10B981;">
                        <i class="bi bi-check-lg fs-5"></i>
                    </div>
                    <strong class="small d-block text-dark">Request Placed</strong>
                    <span class="text-muted" style="font-size: 0.72rem;">{{ $sample->created_at->format('d M') }}</span>
                </div>

                <div class="col-3">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2 shadow-sm"
                         style="width: 44px; height: 44px; background: {{ $step2 ? '#10B981' : '#E2E8F0' }}; color: {{ $step2 ? '#FFFFFF' : '#64748B' }};">
                        <i class="bi bi-box-seam fs-5"></i>
                    </div>
                    <strong class="small d-block text-dark">Supplier Accepted</strong>
                    <span class="text-muted" style="font-size: 0.72rem;">{{ $step2 ? 'Ready for Dispatch' : 'In Review' }}</span>
                </div>

                <div class="col-3">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2 shadow-sm"
                         style="width: 44px; height: 44px; background: {{ $step3 ? '#10B981' : '#E2E8F0' }}; color: {{ $step3 ? '#FFFFFF' : '#64748B' }};">
                        <i class="bi bi-truck fs-5"></i>
                    </div>
                    <strong class="small d-block text-dark">In Transit</strong>
                    <span class="text-muted" style="font-size: 0.72rem;">{{ $sample->dispatched_at ? $sample->dispatched_at->format('d M') : 'Awaiting Courier' }}</span>
                </div>

                <div class="col-3">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2 shadow-sm"
                         style="width: 44px; height: 44px; background: {{ $step4 ? '#10B981' : '#E2E8F0' }}; color: {{ $step4 ? '#FFFFFF' : '#64748B' }};">
                        <i class="bi bi-box2-heart fs-5"></i>
                    </div>
                    <strong class="small d-block text-dark">Delivered & Tested</strong>
                    <span class="text-muted" style="font-size: 0.72rem;">{{ $sample->delivered_at ? $sample->delivered_at->format('d M') : 'Pending Handover' }}</span>
                </div>
            </div>
        @endif

        @if($sample->tracking_number)
            <div class="p-3 bg-light rounded-3 border mt-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                    <div>
                        <span class="text-muted small d-block">Logistics Partner:</span>
                        <strong class="text-dark">{{ $sample->courier_partner }}</strong>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Courier AWB / Tracking Code:</span>
                        <strong class="text-primary font-monospace fs-6">{{ $sample->tracking_number }}</strong>
                    </div>
                    @if($sample->vendor_notes)
                        <div class="small text-muted">
                            <i class="bi bi-chat-left-text me-1"></i>{{ $sample->vendor_notes }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Product & Specimen Details -->
        <div class="col-lg-7">
            <div class="card-custom p-4 mb-4">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-3">
                    <i class="bi bi-box-seam text-success me-2"></i>Material Sample Specimen
                </h6>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ $sample->product->primaryImage ? asset('storage/'.$sample->product->primaryImage->file_path) : 'https://placehold.co/150x150?text=Material' }}" 
                         class="rounded-3 border" style="width: 70px; height: 70px; object-fit: contain;" alt="{{ $sample->product->name }}">
                    <div>
                        <span class="badge bg-light text-muted border small mb-1">{{ $sample->product->category->name ?? 'Category' }}</span>
                        <h6 class="fw-bold text-dark mb-1">
                            <a href="{{ route('products.show', $sample->product->slug ?? '#') }}" class="text-dark text-decoration-none">
                                {{ $sample->product->name }}
                            </a>
                        </h6>
                        <span class="text-muted small">SKU: {{ $sample->product->sku }}</span>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="row g-2 small">
                        <div class="col-6">
                            <span class="text-muted d-block">Sample Quantity:</span>
                            <strong class="text-dark fs-6">{{ $sample->quantity }} Unit</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Sample Format:</span>
                            {!! $sample->getSampleTypeBadge() !!}
                        </div>
                        <div class="col-12 mt-2 pt-2 border-top">
                            <span class="text-muted d-block">Specimen Size Description:</span>
                            <strong class="text-dark">{{ $sample->product->sample_unit_description ?? 'Standard Manufacturer Test Pouch / Cut Piece' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border" style="background: #F8FAFC;">
                    <div>
                        <span class="text-muted small d-block">Total Paid:</span>
                        <strong class="text-success fs-5">{{ $sample->total_amount > 0 ? '₹' . number_format($sample->total_amount, 2) : '₹0.00 (Free)' }}</strong>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Payment Method:</span>
                        <span class="badge bg-light text-dark border text-capitalize">{{ $sample->payment_method ?? 'Free' }}</span>
                    </div>
                </div>
            </div>

            <!-- Ready for Commercial Batch CTA Card -->
            <div class="card-custom p-4 border border-warning border-opacity-50" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%);">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-stars text-warning fs-5"></i>
                            <h6 class="fw-bold text-dark mb-0">Satisfied with the Material Sample?</h6>
                        </div>
                        <p class="text-muted small mb-0">Proceed with placing commercial bulk wholesale orders directly with factory delivery.</p>
                    </div>
                    <div>
                        <a href="{{ route('products.show', $sample->product->slug ?? '#') }}" class="btn btn-warning btn-sm px-4 py-2 fw-bold text-nowrap text-dark shadow-sm" style="border-radius: 8px;">
                            <i class="bi bi-cart-check-fill me-1"></i> Order Full Quantity
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier & Delivery Info -->
        <div class="col-lg-5">
            <!-- Supplier Profile -->
            <div class="card-custom p-4 mb-4">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="bi bi-shop text-primary me-2"></i>Supplier Details
                </h6>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 46px; height: 46px;">
                        {{ strtoupper(substr($sample->vendor->company_name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $sample->vendor->company_name ?? 'Garudo Verified Supplier' }}</h6>
                        <span class="badge bg-success-subtle text-success border border-success small" style="font-size: 0.7rem;">
                            <i class="bi bi-patch-check-fill me-1"></i>Verified Supplier
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-muted small d-block mb-1">Contact Phone:</span>
                    <a href="tel:{{ $sample->vendor->phone ?? '' }}" class="fw-bold text-dark text-decoration-none small">
                        <i class="bi bi-telephone text-success me-1"></i>{{ $sample->vendor->phone ?? '+91 Support Desk' }}
                    </a>
                </div>

                <div class="row g-2 pt-2 border-top">
                    <div class="col-6">
                        <a href="tel:{{ $sample->vendor->phone ?? '' }}" class="btn btn-outline-dark btn-sm w-100 fw-semibold">
                            <i class="bi bi-telephone me-1"></i> Call Supplier
                        </a>
                    </div>
                    <div class="col-6">
                        @php
                            $cleanVendorPhone = preg_replace('/[^0-9]/', '', $sample->vendor->phone ?? '');
                            if (strlen($cleanVendorPhone) === 10) $cleanVendorPhone = '91' . $cleanVendorPhone;
                        @endphp
                        <a href="https://wa.me/{{ $cleanVendorPhone }}?text=Hello,%20I%20requested%20sample%20#{{ $sample->request_number }}%20on%20Garudo." target="_blank" class="btn btn-success btn-sm w-100 fw-semibold">
                            <i class="bi bi-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Delivery Site Address -->
            <div class="card-custom p-4">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">
                    <i class="bi bi-geo-alt text-danger me-2"></i>Destination Site Address
                </h6>

                <div class="mb-2">
                    <strong class="text-dark d-block">{{ $sample->buyer_name }}</strong>
                    @if($sample->company_name)
                        <span class="text-muted small d-block"><i class="bi bi-building me-1"></i>{{ $sample->company_name }}</span>
                    @endif
                    <p class="mb-0 text-dark small bg-light p-2.5 rounded border mt-2">
                        {{ $sample->delivery_address }}<br>
                        <strong>{{ $sample->city }}, {{ $sample->state }} - {{ $sample->pincode }}</strong>
                    </p>
                </div>
                <div class="small text-muted">
                    <i class="bi bi-telephone me-1"></i>Phone: {{ $sample->buyer_phone }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
