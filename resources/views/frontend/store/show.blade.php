@extends('layouts.frontend')

@section('title', ($vendor->company_name ?? 'Supplier Store') . ' - Garudo B2B Marketplace')

@push('styles')
<style>
    .vendor-hero-banner {
        background: linear-gradient(135deg, #0B4D3C 0%, #062E24 100%);
        color: #FFFFFF;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 16px 16px;
    }
    .vendor-hero-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
        opacity: 0.15;
    }
    .store-logo-box {
        width: 110px;
        height: 110px;
        border-radius: 16px;
        background: #FFFFFF;
        border: 3px solid #FFFFFF;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        overflow: hidden;
        flex-shrink: 0;
    }
    .store-badge-pill {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .store-product-card {
        border: 1px solid var(--garudo-card-border);
        border-radius: 10px;
        background: #fff;
        transition: all 0.2s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .store-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .product-img-wrap {
        height: 180px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .product-img-wrap img {
        max-height: 100%;
        max-width: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .store-product-card:hover .product-img-wrap img {
        transform: scale(1.05);
    }
    .star-select-btn {
        font-size: 1.6rem;
        color: #cbd5e1;
        cursor: pointer;
        transition: color 0.15s ease;
    }
    .star-select-btn.active, .star-select-btn:hover {
        color: #f59e0b;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Storefront Hero Header Banner -->
    <div class="vendor-hero-banner py-5 px-3 px-md-5 mb-4 position-relative">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-4">
                        <!-- Store Logo -->
                        <div class="store-logo-box text-dark">
                            @if($vendor->company_logo)
                                <img src="{{ asset('storage/' . $vendor->company_logo) }}" alt="{{ $vendor->company_name }}" style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <i class="bi bi-shop text-success"></i>
                            @endif
                        </div>

                        <!-- Store Details -->
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="store-badge-pill bg-warning text-dark">
                                    <i class="bi bi-award-fill"></i> Verified Channel Partner
                                </span>
                                @if($has247Support)
                                    <span class="store-badge-pill bg-success text-white">
                                        <i class="bi bi-headset"></i> 24/7 Priority Supplier
                                    </span>
                                @endif
                                <span class="store-badge-pill bg-white bg-opacity-20 text-white border border-white border-opacity-25 text-uppercase">
                                    {{ $vendor->business_type ?? 'Manufacturer & Wholesaler' }}
                                </span>
                            </div>

                            <h2 class="fw-black text-white mb-1">{{ $vendor->company_name }}</h2>
                            <p class="text-white-50 small mb-2" style="max-width: 600px;">
                                {{ $vendor->company_description ?? 'Direct manufacturer and bulk distributor of verified building materials, cement, TMT steel, and hardware supplies.' }}
                            </p>

                            <div class="d-flex flex-wrap align-items-center gap-3 text-white small">
                                @if($vendor->primary_dispatch_state)
                                    <span><i class="bi bi-geo-alt-fill text-warning me-1"></i> Dispatch Hub: <strong>{{ $vendor->primary_dispatch_state }}</strong></span>
                                @endif
                                <span><i class="bi bi-box-seam text-warning me-1"></i> <strong>{{ $products->total() }}</strong> Products Listed</span>
                                <a href="#reviews" class="text-white text-decoration-none">
                                    <i class="bi bi-star-fill text-warning me-1"></i> <strong>{{ number_format($avgRating, 1) }}</strong> ({{ $totalReviews }} Reviews)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="d-inline-flex flex-column gap-2 w-100" style="max-width: 280px;">
                        <button type="button" class="btn btn-warning fw-bold py-2.5 rounded-3 text-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                            <i class="bi bi-chat-text-fill me-1"></i> Request Bulk Quote / RFQ
                        </button>
                        <button type="button" class="btn btn-outline-light py-2 rounded-3 small fw-bold" data-bs-toggle="modal" data-bs-target="#writeReviewModal" id="write-review">
                            <i class="bi bi-star-fill text-warning me-1"></i> Write a Supplier Review
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Catalog & Filters -->
    <div class="container mb-5">
        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Store Search & Filter Bar -->
        <div class="card border-0 shadow-sm rounded-3 p-3 mb-4 bg-white">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <form action="{{ route('store.show', $vendor->company_slug ?? $vendor->id) }}" method="GET" class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search products in this store..." value="{{ request('q') }}">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="col-md-7 d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                    <span class="text-muted small">Category Filter:</span>
                    <a href="{{ route('store.show', $vendor->company_slug ?? $vendor->id) }}" 
                       class="btn btn-sm {{ !request('category') ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3">
                        All ({{ $products->total() }})
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('store.show', ['slug' => $vendor->company_slug ?? $vendor->id, 'category' => $cat->slug]) }}" 
                           class="btn btn-sm {{ request('category') === $cat->slug ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row g-3 mb-5">
            @forelse($products as $product)
                @php
                    $img = $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=600&q=80';
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="store-product-card">
                        <div>
                            <div class="product-img-wrap">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ $img }}" alt="{{ $product->name }}">
                                </a>
                                @if($product->mrp && $product->mrp > $product->selling_price)
                                    @php
                                        $disc = round((($product->mrp - $product->selling_price) / $product->mrp) * 100);
                                    @endphp
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger fw-bold" style="font-size: 10px;">
                                        {{ $disc }}% OFF
                                    </span>
                                @endif
                            </div>

                            <div class="p-3">
                                <div class="text-muted small text-uppercase fw-semibold mb-1" style="font-size: 10px;">
                                    {{ $product->category->name ?? 'Materials' }}
                                </div>
                                <h6 class="fw-bold mb-1 text-truncate" title="{{ $product->name }}">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <div class="text-muted small mb-2" style="font-size: 11px;">
                                    MOQ: <strong>{{ $product->min_order_qty ?? 1 }} {{ $product->unit ?? 'Units' }}</strong>
                                </div>

                                <div class="d-flex align-items-baseline gap-2 mb-2">
                                    <span class="fs-5 fw-black text-dark">₹{{ number_format($product->selling_price, 2) }}</span>
                                    @if($product->mrp && $product->mrp > $product->selling_price)
                                        <span class="text-muted text-decoration-line-through small">₹{{ number_format($product->mrp, 2) }}</span>
                                    @endif
                                    <span class="text-muted small">/ {{ $product->unit ?? 'pc' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 pt-0 border-top mt-2">
                            <div class="d-flex gap-2 pt-2">
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-fill">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="{{ $product->min_order_qty ?? 1 }}">
                                    <button type="submit" class="btn btn-sm btn-outline-success w-100 fw-bold">
                                        <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-dark" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                        <i class="bi bi-box-seam text-muted display-4 mb-3"></i>
                        <h5 class="fw-bold text-dark">No Products Found in this Storefront</h5>
                        <p class="text-muted small mb-0">This seller has not listed products under this filter yet.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="mt-4 mb-5">
                {{ $products->links() }}
            </div>
        @endif

        <!-- ========================================================== -->
        <!-- STORE RATINGS & BUYER REVIEWS SECTION -->
        <!-- ========================================================== -->
        <div id="reviews" class="pt-4 border-top">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">
                        <i class="bi bi-star-fill text-warning me-2"></i> Verified Supplier Ratings & Reviews
                    </h4>
                    <p class="text-muted small mb-0">Genuine feedback from construction companies, contractors, and procurement heads.</p>
                </div>
                <button type="button" class="btn btn-warning fw-bold text-dark px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                    <i class="bi bi-pencil-square me-1"></i> Write a Review
                </button>
            </div>

            <div class="row g-4 mb-4">
                <!-- Overall Score Card -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100">
                        <div class="text-muted small fw-bold text-uppercase mb-2">Overall Store Rating</div>
                        <div class="display-3 fw-black text-dark">{{ number_format($avgRating, 1) }}</div>
                        <div class="text-warning fs-4 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted text-opacity-25' }}"></i>
                            @endfor
                        </div>
                        <span class="text-muted small d-block mb-3">Based on <strong>{{ $totalReviews }}</strong> Verified Buyer Ratings</span>

                        <!-- Rating Bars -->
                        <div class="text-start d-flex flex-column gap-1.5 pt-2 border-top">
                            @for($s = 5; $s >= 1; $s--)
                                <div class="d-flex align-items-center gap-2 small">
                                    <span style="width: 25px;">{{ $s }}★</span>
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $starPercents[$s] }}%"></div>
                                    </div>
                                    <span class="text-muted" style="width: 28px; text-align: right;">{{ $starCounts[$s] }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Sub-Ratings Overview -->
                <div class="col-lg-8">
                    <div class="row g-3 h-100">
                        <div class="col-sm-4">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-center text-center">
                                <div class="p-2 rounded-circle bg-success-subtle text-success d-inline-block mx-auto mb-2 fs-4" style="width: 46px; height: 46px;">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">{{ number_format($avgQuality, 1) }} / 5.0</h5>
                                <span class="small text-muted fw-semibold">Material Quality</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-center text-center">
                                <div class="p-2 rounded-circle bg-primary-subtle text-primary d-inline-block mx-auto mb-2 fs-4" style="width: 46px; height: 46px;">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">{{ number_format($avgDelivery, 1) }} / 5.0</h5>
                                <span class="small text-muted fw-semibold">Dispatch & Delivery</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-column justify-content-center text-center">
                                <div class="p-2 rounded-circle bg-warning-subtle text-warning d-inline-block mx-auto mb-2 fs-4" style="width: 46px; height: 46px;">
                                    <i class="bi bi-currency-rupee"></i>
                                </div>
                                <h5 class="fw-bold mb-0 text-dark">{{ number_format($avgPricing, 1) }} / 5.0</h5>
                                <span class="small text-muted fw-semibold">Pricing & Transparency</span>
                            </div>
                        </div>

                        <!-- Trust Badge Banner -->
                        <div class="col-12">
                            <div class="card border-0 rounded-4 p-3 bg-dark text-white d-flex flex-row align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-patch-check-fill text-warning fs-3"></i>
                                    <div>
                                        <div class="fw-bold">Garudo Verified Supplier Guarantee</div>
                                        <div class="text-white-50 small">All feedback is audited for genuine commercial transactions and verified dispatch notes.</div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-warning fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                                    Rate This Supplier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Reviews Feed List -->
            <div class="row g-3">
                @forelse($vendorReviews as $rev)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1rem;">
                                        {{ strtoupper(substr($rev->reviewer_name ?? 'B', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $rev->reviewer_name }}</h6>
                                            @if($rev->is_verified_buyer)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small">
                                                    <i class="bi bi-shield-check me-1"></i> Verified Buyer
                                                </span>
                                            @endif
                                        </div>
                                        @if($rev->reviewer_company)
                                            <div class="text-muted small">{{ $rev->reviewer_company }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning fs-6 mb-1">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="bi bi-star-fill {{ $s <= $rev->rating ? 'text-warning' : 'text-muted text-opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">{{ $rev->created_at->format('d M Y') }}</span>
                                </div>
                            </div>

                            @if($rev->title)
                                <h6 class="fw-bold text-dark mb-1">{{ $rev->title }}</h6>
                            @endif

                            <p class="text-secondary small mb-2 leading-relaxed">
                                {{ $rev->review }}
                            </p>

                            <div class="d-flex flex-wrap gap-3 pt-2 border-top small text-muted">
                                <span>Material: <strong class="text-dark">{{ $rev->material_quality_rating ?? 5 }}★</strong></span>
                                <span>Delivery: <strong class="text-dark">{{ $rev->delivery_rating ?? 5 }}★</strong></span>
                                <span>Pricing: <strong class="text-dark">{{ $rev->pricing_rating ?? 5 }}★</strong></span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="bi bi-chat-heart text-muted display-4 mb-2"></i>
                            <h5 class="fw-bold text-dark">Be the first to review {{ $vendor->company_name }}</h5>
                            <p class="text-muted small mb-3">Have you purchased cement, steel, or building supplies from this vendor? Share your experience.</p>
                            <div>
                                <button type="button" class="btn btn-warning fw-bold text-dark px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                                    <i class="bi bi-star-fill me-1"></i> Write a Review
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($vendorReviews->hasPages())
                <div class="mt-4">
                    {{ $vendorReviews->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ========================================================== -->
<!-- MODAL: WRITE A SUPPLIER REVIEW -->
<!-- ========================================================== -->
<div class="modal fade" id="writeReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('store.reviews.store', $vendor->company_slug ?? $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white px-4 py-3">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-star-fill text-warning me-2"></i> Rate & Review {{ $vendor->company_name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Star Rating Selector -->
                    <div class="text-center p-3 bg-light rounded-4 mb-4 border">
                        <label class="form-label fw-bold text-dark mb-1">Your Overall Rating <span class="text-danger">*</span></label>
                        <div class="d-flex justify-content-center gap-2 my-2" id="starRatingGroup">
                            <i class="bi bi-star-fill star-select-btn active" data-value="1"></i>
                            <i class="bi bi-star-fill star-select-btn active" data-value="2"></i>
                            <i class="bi bi-star-fill star-select-btn active" data-value="3"></i>
                            <i class="bi bi-star-fill star-select-btn active" data-value="4"></i>
                            <i class="bi bi-star-fill star-select-btn active" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="reviewRatingInput" value="5" required>
                        <span class="small text-muted fw-semibold" id="ratingTextLabel">5.0 - Excellent Service & Material</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Your Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="reviewer_name" class="form-control" value="{{ auth('customer')->user()?->name ?? '' }}" placeholder="e.g. Ramesh Kumar" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Company / Construction Firm Name</label>
                            <input type="text" name="reviewer_company" class="form-control" value="{{ auth('customer')->user()?->company_name ?? '' }}" placeholder="e.g. Apex Infra Projects Pvt Ltd">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone Number</label>
                            <input type="text" name="reviewer_phone" class="form-control" value="{{ auth('customer')->user()?->phone ?? '' }}" placeholder="e.g. 9876543210">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <input type="email" name="reviewer_email" class="form-control" value="{{ auth('customer')->user()?->email ?? '' }}" placeholder="e.g. procurement@apex.com">
                        </div>

                        <!-- Sub-Ratings -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Material Quality (1-5)</label>
                            <select name="material_quality_rating" class="form-select">
                                <option value="5" selected>5 ★★★★★ (Superior)</option>
                                <option value="4">4 ★★★★☆ (Good)</option>
                                <option value="3">3 ★★★☆☆ (Average)</option>
                                <option value="2">2 ★★☆☆☆ (Below Average)</option>
                                <option value="1">1 ★☆☆☆☆ (Poor)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Dispatch & Delivery (1-5)</label>
                            <select name="delivery_rating" class="form-select">
                                <option value="5" selected>5 ★★★★★ (On-Time)</option>
                                <option value="4">4 ★★★★☆ (Minor Delay)</option>
                                <option value="3">3 ★★★☆☆ (Moderate)</option>
                                <option value="2">2 ★★☆☆☆ (Delayed)</option>
                                <option value="1">1 ★☆☆☆☆ (Very Late)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Pricing & Rates (1-5)</label>
                            <select name="pricing_rating" class="form-select">
                                <option value="5" selected>5 ★★★★★ (Best Bulk Rate)</option>
                                <option value="4">4 ★★★★☆ (Competitive)</option>
                                <option value="3">3 ★★★☆☆ (Standard)</option>
                                <option value="2">2 ★★☆☆☆ (Expensive)</option>
                                <option value="1">1 ★☆☆☆☆ (Overpriced)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Review Headline / Summary</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Excellent TMT bar quality, fast crane unloading and prompt GST invoice">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Detailed Review & Experience <span class="text-danger">*</span></label>
                            <textarea name="review" class="form-control" rows="4" placeholder="Describe the material quality, packaging, delivery lead time, and overall vendor interaction..." required minlength="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                        <i class="bi bi-send-fill me-1"></i> Submit Verified Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Direct Store Inquiry Modal -->
<div class="modal fade" id="inquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white px-4 py-3 rounded-top-4">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-envelope-paper-fill me-2 text-warning"></i> Request Quotation from {{ $vendor->company_name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Material / Required Products <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="e.g. 500 Bags OPC 53 Cement + 10 Tons TMT 12mm" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Quantity</label>
                            <input type="number" class="form-control" placeholder="Quantity">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Delivery Pincode / City</label>
                            <input type="text" class="form-control" placeholder="e.g. 110001 New Delhi">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Additional Requirements / Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Specify test certificates, billing terms, unloading requirements..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 text-dark">
                        <i class="bi bi-send-fill me-1"></i> Send Instant RFQ to Supplier
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const starBtns = document.querySelectorAll('#starRatingGroup .star-select-btn');
        const ratingInput = document.getElementById('reviewRatingInput');
        const label = document.getElementById('ratingTextLabel');

        const ratingLabels = {
            1: '1.0 - Poor Experience',
            2: '2.0 - Below Average',
            3: '3.0 - Satisfactory / Standard',
            4: '4.0 - Very Good Service',
            5: '5.0 - Excellent Service & Material'
        };

        starBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const val = parseInt(this.getAttribute('data-value'));
                ratingInput.value = val;
                label.textContent = ratingLabels[val] || (val + '.0');

                starBtns.forEach(s => {
                    const sVal = parseInt(s.getAttribute('data-value'));
                    if (sVal <= val) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    });
</script>
@endpush
