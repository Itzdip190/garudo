@extends('layouts.frontend')

@section('title', $brand->name . ' - Verified Brand Catalog & Authorized Dealers - Garudo')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('brands.index') }}" class="text-muted text-decoration-none">Brands</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ $brand->name }}</li>
        </ol>
    </nav>

    <!-- Brand Header Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white position-relative overflow-hidden">
        @if($brand->banner)
            <div class="position-absolute top-0 start-0 w-100" style="height: 90px; background-image: url('{{ asset('storage/' . $brand->banner) }}'); background-size: cover; background-position: center; opacity: 0.15;"></div>
        @endif

        <div class="position-relative" style="z-index: 1;">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                <div class="d-flex align-items-center gap-4">
                    <div class="rounded-4 bg-white border shadow-sm p-2 d-flex align-items-center justify-content-center" style="width: 88px; height: 88px; min-width: 88px;">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        @else
                            <i class="bi bi-patch-check-fill text-warning fs-1"></i>
                        @endif
                    </div>
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="badge bg-success text-white px-2 py-1 rounded-pill small">
                                <i class="bi bi-patch-check-fill me-1"></i>Garudo Verified Brand
                            </span>
                            @if($brand->is_protected)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill small">
                                    <i class="bi bi-shield-lock-fill me-1"></i>Gated OEM Catalog
                                </span>
                            @endif
                            @if($brand->year_established)
                                <span class="badge bg-light text-secondary border rounded-pill small">Est. {{ $brand->year_established }}</span>
                            @endif
                        </div>
                        <h2 class="fw-bold mb-1 text-dark">{{ $brand->name }}</h2>
                        <p class="text-muted small mb-0">{{ $brand->description ?? $brand->story ?? 'Manufacturer direct building materials with wholesale pricing and guaranteed test certificates.' }}</p>
                    </div>
                </div>
                <div class="text-md-end">
                    <div class="d-flex gap-3 justify-content-md-end align-items-center">
                        <div>
                            <span class="fs-4 fw-bold text-dark d-block">{{ $products->total() }}</span>
                            <span class="text-muted small">Live Products</span>
                        </div>
                        <div class="border-start ps-3">
                            <span class="fs-4 fw-bold text-success d-block">{{ $dealers->count() }}</span>
                            <span class="text-muted small">Authorized Dealers</span>
                        </div>
                    </div>
                    @if($brand->website)
                        <a href="{{ $brand->website }}" target="_blank" class="small text-primary text-decoration-none mt-2 d-inline-block">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Official Website
                        </a>
                    @endif
                </div>
            </div>

            @if($brand->warranty_policy)
                <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 text-muted small">
                    <i class="bi bi-shield-check text-success fs-5"></i>
                    <span><strong>Manufacturer Warranty:</strong> {{ $brand->warranty_policy }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- TABS: Products vs Authorized Dealers -->
    <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="brandTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 py-2 fw-bold" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-content" type="button" role="tab">
                <i class="bi bi-box-seam me-1"></i> Brand Products ({{ $products->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 py-2 fw-bold" id="dealers-tab" data-bs-toggle="tab" data-bs-target="#dealers-content" type="button" role="tab">
                <i class="bi bi-diagram-3-fill me-1"></i> Authorized Dealer Network ({{ $dealers->count() }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="brandTabsContent">
        <!-- TAB 1: Products Grid -->
        <div class="tab-pane fade show active" id="products-content" role="tabpanel">
            <div class="row g-3">
                @forelse($products as $product)
                    @php
                        $img = $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=600&q=80';
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card-custom h-100 p-3 d-flex flex-column justify-content-between bg-white rounded-3 shadow-sm border">
                            <div>
                                <div style="height: 160px; background: #f8fafc; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <img src="{{ $img }}" alt="{{ $product->name }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                    </a>
                                </div>
                                <div class="text-muted small mb-1" style="font-size: 10px;">{{ $product->category->name ?? 'Materials' }}</div>
                                <h6 class="fw-bold mb-1 text-truncate" title="{{ $product->name }}">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <div class="text-muted small mb-2 d-flex align-items-center gap-1" style="font-size: 11px;">
                                    <span>By: <strong>{{ $product->vendor->company_name ?? 'Garudo Supplier' }}</strong></span>
                                    @if($brand->isOwnedBy($product->vendor))
                                        <span class="badge bg-success-subtle text-success p-1" title="Direct from Brand OEM"><i class="bi bi-patch-check-fill"></i> OEM</span>
                                    @elseif($product->vendor->isAuthorizedForBrand($brand))
                                        <span class="badge bg-primary-subtle text-primary p-1" title="Verified Authorized Dealer"><i class="bi bi-shield-check"></i> Auth Dealer</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-baseline gap-2 mb-2">
                                    <span class="fs-5 fw-bold text-dark">₹{{ number_format($product->selling_price, 2) }}</span>
                                    <span class="text-muted small">/ {{ $product->unit ?? 'pc' }}</span>
                                </div>
                            </div>

                            <div class="pt-2 border-top mt-2">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-yellow w-100 fw-bold">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="bi bi-box-seam text-muted display-4 mb-3"></i>
                            <h5 class="fw-bold text-dark">No Products Listed for {{ $brand->name }}</h5>
                            <p class="text-muted small mb-0">Check back soon as authorized suppliers update their stock.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        <!-- TAB 2: Authorized Dealers Network -->
        <div class="tab-pane fade" id="dealers-content" role="tabpanel">
            <!-- Filter Bar for Dealers -->
            <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
                <form action="{{ route('brands.show', $brand->slug) }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="dealers">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark mb-0">Locate Authorized Stockist & Dealers by State</label>
                    </div>
                    <div class="col-md-4">
                        <select name="dealer_state" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All States (Pan-India)</option>
                            @foreach($indianStates as $state)
                                <option value="{{ $state }}" {{ $dealerState === $state ? 'selected' : '' }}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        @if($dealerState)
                            <a href="{{ route('brands.show', $brand->slug) }}" class="btn btn-sm btn-outline-secondary w-100">Clear Filter</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Dealers Grid -->
            <div class="row g-3">
                @forelse($dealers as $dealerAuth)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-3 p-4 h-100 bg-white d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                        {{ $dealerAuth->formatted_type }}
                                    </span>
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-patch-check-fill me-1"></i>Verified
                                    </span>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">{{ $dealerAuth->vendor->company_name }}</h5>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>Territory: <strong>{{ $dealerAuth->formatted_territory }}</strong>
                                </p>
                                <div class="p-2 bg-light rounded small mb-3">
                                    <span class="text-muted d-block" style="font-size: 0.72rem;">Certificate ID</span>
                                    <code class="fw-bold text-dark">{{ $dealerAuth->certificate_number }}</code>
                                </div>
                            </div>

                            <div class="d-flex gap-2 pt-2 border-top">
                                <a href="{{ route('certificate.dealer.verify', $dealerAuth->certificate_number) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill w-50">
                                    <i class="bi bi-award me-1"></i>Certificate
                                </a>
                                <a href="{{ route('store.show', $dealerAuth->vendor->company_slug ?? $dealerAuth->vendor->id) }}" class="btn btn-sm btn-dark rounded-pill w-50">
                                    <i class="bi bi-shop me-1"></i>Storefront
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="bi bi-diagram-3 text-muted display-4 mb-3"></i>
                            <h5 class="fw-bold text-dark">No Authorized Dealers Found for this Region</h5>
                            <p class="text-muted small mb-0">Direct OEM factory dispatch is available nationwide.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
