@extends('layouts.frontend')

@section('title', $product->name . ' - ' . config('marketplace.name'))

@section('content')
<div class="container py-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3 small">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Image Gallery (Image 2 UX Reference) -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-3">
                <div class="text-center mb-3 border rounded p-2 bg-white">
                    <img id="mainProductImage" src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->file_path) : 'https://placehold.co/500x500?text='.$product->name }}" class="img-fluid" style="max-height: 400px; object-fit: contain;" alt="{{ $product->name }}">
                </div>
                <!-- Thumbnails -->
                <div class="d-flex gap-2 overflow-auto py-1">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/'.$img->file_path) }}" class="img-thumbnail thumb-select cursor-pointer" style="width: 70px; height: 70px; object-fit: cover;" onclick="$('#mainProductImage').attr('src', this.src);">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Main Info & Vendor Box -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-navy mb-2">{{ $product->category->name ?? 'Industrial' }}</span>
                        <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>
                        <p class="small text-muted mb-2">SKU: <span class="fw-medium text-dark">{{ $product->sku }}</span> | HSN: <span class="fw-medium text-dark">{{ $product->hsn_code ?? 'N/A' }}</span></p>
                    </div>
                </div>

                <!-- Rating -->
                <div class="d-flex align-items-center mb-3">
                    <div class="text-warning me-2 fs-6">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <span class="fw-bold fs-6 me-2">{{ $product->rating ?? '4.5' }}</span>
                    <span class="small text-muted">({{ $product->rating_count ?? 12 }} Reviews)</span>
                </div>

                <!-- Price Box -->
                <div class="bg-light p-3 rounded mb-4 border">
                    <div class="d-flex align-items-baseline gap-3 mb-1">
                        <span class="fs-2 fw-bold text-dark">₹{{ number_format($product->selling_price, 2) }}</span>
                        <span class="text-muted text-decoration-line-through fs-5">₹{{ number_format($product->mrp, 2) }}</span>
                        <span class="badge bg-success">Save {{ round((($product->mrp - $product->selling_price) / $product->mrp) * 100) }}%</span>
                    </div>
                    <span class="small text-muted">+ GST @ {{ $product->gst_rate }}% extra</span>
                </div>

                <!-- Vendor Highlights Box -->
                <div class="border rounded p-3 mb-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-patch-check-fill text-primary fs-4 me-2"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $product->vendor->company_name ?? 'Garudo Verified Supplier' }}</h6>
                                <span class="small text-muted">{{ $product->vendor->business_type ?? 'Manufacturer' }}</span>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-check-circle me-1"></i> Verified</span>
                    </div>
                    <div class="row text-center mt-3 pt-2 border-top g-2">
                        <div class="col-6"><button class="btn btn-outline-dark btn-sm w-100"><i class="bi bi-telephone me-1"></i> Call Vendor</button></div>
                        <div class="col-6"><button class="btn btn-success btn-sm w-100"><i class="bi bi-whatsapp me-1"></i> WhatsApp</button></div>
                    </div>
                </div>

                <!-- Quantity and Add to Cart Form -->
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="row align-items-center g-2 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label small fw-semibold text-muted mb-1">Order Quantity:</label>
                            <div class="input-group">
                                <input type="number" name="quantity" min="{{ $product->min_order_qty ?? 1 }}" value="{{ $product->min_order_qty ?? 1 }}" class="form-control text-center font-monospace fw-bold" required>
                                <span class="input-group-text bg-light small">{{ $product->unit ?? 'Units' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-8 pt-4">
                            <span class="small text-muted">
                                <i class="bi bi-info-circle me-1"></i> MOQ: <strong>{{ $product->min_order_qty ?? 1 }} {{ $product->unit ?? 'Units' }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-yellow btn-lg w-100 fw-bold py-2.5 shadow-sm">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                        </div>
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold py-2.5 shadow-sm" onclick="this.form.action='{{ route('cart.add') }}'">
                                <i class="bi bi-lightning-charge-fill me-1"></i> Buy Now
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger btn-lg w-100" onclick="document.getElementById('wishlistForm{{ $product->id }}').submit();" title="Add to Wishlist">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <form id="wishlistForm{{ $product->id }}" action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Product Description & Specifications Tabs -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
                <ul class="nav nav-tabs fw-bold" id="productTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active text-dark" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc">Product Description</button></li>
                    <li class="nav-item"><button class="nav-link text-dark" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs">Specifications</button></li>
                    <li class="nav-item">
                        <button class="nav-link text-dark" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy">
                            <i class="bi bi-shield-check me-1 text-success"></i> Policy & Terms
                        </button>
                    </li>
                </ul>
                <div class="tab-content pt-4" id="productTabContent">
                    <div class="tab-pane fade show active" id="desc">
                        <p>{!! nl2br(e($product->description ?? 'No description provided.')) !!}</p>
                    </div>
                    <div class="tab-pane fade" id="specs">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th class="bg-light w-25">Category</th><td>{{ $product->category->name ?? 'N/A' }}</td></tr>
                                <tr><th class="bg-light">Brand</th><td>{{ $product->brand->name ?? 'Generic' }}</td></tr>
                                <tr><th class="bg-light">Minimum Order Quantity</th><td>{{ $product->min_order_qty }} {{ $product->unit }}</td></tr>
                                @if($product->warehouse)
                                    <tr><th class="bg-light">Primary Dispatch State</th><td>{{ $product->warehouse->state }} ({{ $product->warehouse->city }})</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="policy">
                        <div class="p-3 bg-light rounded border mb-3">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                    {{ $product->policy_type === 'custom' ? 'Product-Specific Policy' : (($product->category->name ?? 'Category') . ' Standard Policy') }}
                                </span>
                                @if($product->warehouse)
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        <i class="bi bi-geo-alt me-1"></i> Dispatched From: {{ $product->warehouse->city }}, {{ $product->warehouse->state }}
                                    </span>
                                @endif
                            </div>
                            <p class="mb-0 text-dark" style="line-height: 1.6; font-size: 0.9rem;">
                                {!! nl2br(e($product->getEffectivePolicy())) !!}
                            </p>
                        </div>
                        <div class="row g-3 small text-muted">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-white">
                                    <i class="bi bi-patch-check-fill text-success fs-5"></i>
                                    <div>
                                        <strong class="text-dark d-block">Genuine Materials</strong>
                                        <span>Direct from certified B2B suppliers</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-white">
                                    <i class="bi bi-truck text-primary fs-5"></i>
                                    <div>
                                        <strong class="text-dark d-block">Transit Inspection</strong>
                                        <span>Verify before OTP handover</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-white">
                                    <i class="bi bi-receipt text-info fs-5"></i>
                                    <div>
                                        <strong class="text-dark d-block">GST Invoicing</strong>
                                        <span>100% Tax credit compliant</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
