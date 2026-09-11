@extends('layouts.admin')

@section('title', 'Product Inspection - ' . $product->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm rounded-pill px-3 mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back to Products Catalog
    </a>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'General' }}</span>
                <span class="pill-status pill-{{ strtolower($product->approval_status) }}">{{ ucfirst($product->approval_status) }}</span>
                <span class="pill-status pill-{{ strtolower($product->status) }}">{{ ucfirst($product->status) }}</span>
                @if($product->is_deal_of_the_day)
                    <span class="badge bg-danger"><i class="bi bi-fire me-1"></i> Deal of the Day</span>
                @endif
                @if($product->is_sponsored)
                    <span class="badge bg-primary"><i class="bi bi-award-fill me-1"></i> Sponsored Ad</span>
                @endif
                @if($product->is_featured)
                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Featured</span>
                @endif
            </div>
            <h1 class="page-header-title">{{ $product->name }}</h1>
            <div class="text-muted small">Seller: <strong>{{ $product->vendor->company_name ?? 'Unknown Vendor' }}</strong> • SKU: <code>{{ $product->sku ?? 'N/A' }}</code></div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @if($product->approval_status !== 'approved')
                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Approve & Publish
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectProductModal">
                    <i class="bi bi-x-circle-fill me-1"></i> Reject
                </button>
            @endif

            <!-- Deal of Day Button -->
            <form action="{{ route('admin.products.toggle_deal_of_day', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn {{ $product->is_deal_of_the_day ? 'btn-danger' : 'btn-outline-danger' }} btn-sm rounded-pill px-3">
                    <i class="bi bi-fire me-1"></i> {{ $product->is_deal_of_the_day ? 'Remove Deal of Day' : 'Promote to Deal of Day' }}
                </button>
            </form>

            <!-- Sponsored Button -->
            <form action="{{ route('admin.products.toggle_sponsored', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn {{ $product->is_sponsored ? 'btn-primary' : 'btn-outline-primary' }} btn-sm rounded-pill px-3">
                    <i class="bi bi-award-fill me-1"></i> {{ $product->is_sponsored ? 'Remove Sponsored' : 'Mark as Sponsored' }}
                </button>
            </form>

            <!-- Featured Button -->
            <form action="{{ route('admin.products.toggle_featured', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn {{ $product->is_featured ? 'btn-warning text-dark' : 'btn-outline-warning' }} btn-sm rounded-pill px-3">
                    <i class="bi bi-star me-1"></i> {{ $product->is_featured ? 'Remove Featured' : 'Feature Product' }}
                </button>
            </form>
        </div>
    </div>
</div>

@if($product->rejection_reason)
    <div class="alert alert-danger mb-4 rounded-3 border-0 shadow-sm">
        <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Rejection Reason Recorded:</h6>
        <div>{{ $product->rejection_reason }}</div>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Pricing & Commercials -->
    <div class="col-lg-4">
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Pricing & Commercials</h6>
            
            <div class="mb-3">
                <div class="text-muted small">B2B Selling Price</div>
                <div class="fw-bold fs-4 text-success">₹{{ number_format($product->selling_price, 2) }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Maximum Retail Price (MRP)</div>
                <div class="fw-semibold text-muted text-decoration-line-through">₹{{ number_format($product->mrp, 2) }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Minimum Order Quantity (MOQ)</div>
                <div class="fw-bold">{{ $product->min_order_qty ?? 1 }} {{ $product->unit ?? 'Units' }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Applicable GST Rate</div>
                <div class="fw-semibold text-dark">{{ $product->gst_rate ?? 18 }}% ({{ $product->gst_inclusive ? 'Inclusive' : 'Exclusive' }})</div>
            </div>
        </div>

        <div class="garudo-card p-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Seller Details</h6>
            
            <div class="mb-2">
                <div class="text-muted small">Company</div>
                <div class="fw-bold">{{ $product->vendor->company_name ?? 'N/A' }}</div>
            </div>

            <div class="mb-2">
                <div class="text-muted small">Email</div>
                <div class="fw-semibold text-dark">{{ $product->vendor->email ?? 'N/A' }}</div>
            </div>

            <div class="mb-2">
                <div class="text-muted small">GSTIN</div>
                <div class="fw-semibold text-dark"><code>{{ $product->vendor->gstin ?? 'N/A' }}</code></div>
            </div>
        </div>
    </div>

    <!-- Right Column: Description, Specs & Images -->
    <div class="col-lg-8">
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Product Description & Specs</h6>
            
            <div class="mb-4">
                <div class="text-muted small fw-bold text-uppercase mb-1">Short Description</div>
                <p class="text-dark">{{ $product->short_description ?? 'No short description provided.' }}</p>
            </div>

            @if($product->description)
                <div class="mb-4">
                    <div class="text-muted small fw-bold text-uppercase mb-1">Full Technical Description</div>
                    <div class="p-3 bg-light rounded-3 text-dark small" style="line-height: 1.6;">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            @endif

            @if($product->specifications && $product->specifications->count() > 0)
                <div class="mb-3">
                    <div class="text-muted small fw-bold text-uppercase mb-2">Technical Specifications</div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <tbody>
                                @foreach($product->specifications as $spec)
                                    <tr>
                                        <td class="bg-light fw-bold text-dark" style="width: 35%;">{{ $spec->spec_key ?? $spec->name }}</td>
                                        <td>{{ $spec->spec_value ?? $spec->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">Reject Product Listing</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Rejection:</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" placeholder="Explain what needs to be fixed (e.g. Non-compliant pricing, inaccurate technical specifications)..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-bold">Submit Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
