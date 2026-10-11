@extends('layouts.frontend')

@section('title', 'Product Catalog - ' . config('marketplace.name', 'Garudo'))

@section('content')
<!-- Page Header & Filter Breadcrumbs -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="font-size: 12px;">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
                        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Products</li>
                        @if(request('category'))
                            <li class="breadcrumb-item active text-primary fw-semibold">{{ ucwords(str_replace('-', ' ', request('category'))) }}</li>
                        @endif
                    </ol>
                </nav>
                <div class="d-flex align-items-center gap-2">
                    <h2 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.4px;">Product Catalog</h2>
                    <span class="badge bg-white text-dark border px-2.5 py-1 rounded-pill fw-bold shadow-xs" style="font-size: 12px;">
                        {{ $products->total() }} Products
                    </span>
                </div>
            </div>
            
            <!-- Quick Active Filter Notification -->
            @if(request('search'))
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Search: <strong>"{{ request('search') }}"</strong></span>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" style="font-size: 11px;">Clear</a>
                </div>
            @endif
        </div>

        <!-- Category Quick Filter Chips -->
        @if(isset($categories) && $categories->isNotEmpty())
            <div class="d-flex gap-2 overflow-x-auto pt-3 pb-1" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
                <a href="{{ route('products.index') }}" 
                   class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap {{ !request('category') ? 'btn-dark text-white' : 'btn-white bg-white text-muted border shadow-xs' }}" 
                   style="font-size: 12px; transition: all 0.2s ease;">
                    All Categories
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
                       class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold text-nowrap {{ request('category') === $cat->slug ? 'btn-dark text-white' : 'btn-white bg-white text-muted border shadow-xs' }}" 
                       style="font-size: 12px; transition: all 0.2s ease;">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="container pb-5">
    <div class="row g-3 g-md-4">
        @forelse($products as $product)
            @php
                $discount = $product->discount_percent > 0 ? $product->discount_percent : ($product->mrp > $product->selling_price ? round((($product->mrp - $product->selling_price) / $product->mrp) * 100) : 0);
                $isWishlisted = \App\Services\WishlistService::has($product->id);
                $vendorName = $product->vendor->company_name ?? ($product->brand->name ?? 'Verified Seller');
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6 col-6 d-flex">
                <div class="card garudo-product-card h-100 w-100 position-relative border bg-white shadow-xs d-flex flex-column justify-content-between" 
                     style="padding: 13px 13px 12px 13px; border-color: #e2e8f0; border-radius: 16px !important; transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;">
                    
                    <!-- Discount Badge (Top Left) -->
                    @if($discount > 0)
                        <span class="badge position-absolute top-0 start-0 m-2.5 px-2.5 py-1" 
                              style="background: linear-gradient(135deg, #ef4444 0%, #ea580c 100%); color: #ffffff; font-size: 10.5px; font-weight: 800; border-radius: 6px; z-index: 3; box-shadow: 0 3px 8px -1px rgba(239, 68, 68, 0.4); letter-spacing: 0.3px;">
                            {{ $discount }}% OFF
                        </span>
                    @elseif($product->is_featured || $product->is_trending || $product->is_popular)
                        <span class="badge position-absolute top-0 start-0 m-2.5 px-2.5 py-1" 
                              style="background: linear-gradient(135deg, #0f172a 0%, #334155 100%); color: #ffffff; font-size: 10px; font-weight: 800; border-radius: 6px; z-index: 3; letter-spacing: 0.4px;">
                            POPULAR
                        </span>
                    @endif

                    <!-- Wishlist / Love Icon Button (Top Right) -->
                    <button type="button" 
                            class="btn-wishlist-toggle position-absolute top-0 end-0 m-2.5 {{ $isWishlisted ? 'active' : '' }}" 
                            data-id="{{ $product->id }}" 
                            onclick="event.preventDefault(); event.stopPropagation(); toggleWishlistCard(this, {{ $product->id }})" 
                            title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}" 
                            style="z-index: 4; width: 32px; height: 32px; border-radius: 50%; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(6px); border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08); display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
                        <i class="bi {{ $isWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }}" 
                           style="font-size: 13.5px; color: {{ $isWishlisted ? '#ef4444' : '#64748b' }}; transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), color 0.2s ease;"></i>
                    </button>

                    <div>
                        <!-- Product Image Showcase Canvas -->
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="d-block text-center rounded-3 mb-2.5 garudo-card-img-canvas" 
                           style="height: 155px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8fafc !important; border: 1px solid #f1f5f9; border-radius: 12px; position: relative;">
                            <img src="{{ $product->primary_image_url }}" 
                                 onerror="this.onerror=null; this.src='/images/product-placeholder.png';" 
                                 class="img-fluid garudo-card-img" 
                                 style="max-height: 135px; max-width: 90%; object-fit: contain; transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);" 
                                 alt="{{ $product->name }}" 
                                 loading="lazy">
                        </a>

                        <!-- Verified Vendor Line (✓ Vendor Name + Rating Pill) -->
                        <div class="d-flex align-items-center justify-content-between mb-1.5" style="font-size: 11px;">
                            <div class="d-flex align-items-center gap-1.5 text-truncate" style="max-width: 68%;">
                                <i class="bi bi-patch-check-fill" style="font-size: 12px; color: #2563eb; flex-shrink: 0;"></i>
                                <span class="text-truncate fw-bold" style="color: #2563eb; font-size: 12px; letter-spacing: -0.1px;">{{ $vendorName }}</span>
                            </div>
                            <div class="d-inline-flex align-items-center gap-1 flex-shrink-0" 
                                 style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 11px; font-weight: 700; padding: 2px 6.5px; border-radius: 6px;">
                                <i class="bi bi-star-fill text-warning" style="font-size: 9.5px;"></i>
                                <span>{{ number_format($product->rating ?: 4.8, 1) }}</span>
                            </div>
                        </div>

                        <!-- Product Title -->
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none d-block">
                            <div class="fw-bold garudo-card-title mb-2" 
                                 style="font-size: 13.5px; color: #0f172a; line-height: 1.38; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 37px; transition: color 0.18s ease;" 
                                 title="{{ $product->name }}">
                                {{ $product->name }}
                            </div>
                        </a>

                        <!-- Pricing & Unit -->
                        <div class="mb-2.5 d-flex align-items-baseline gap-1.5 flex-wrap">
                            <span style="font-size: 16.5px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px;">
                                ₹{{ number_format($product->selling_price) }}
                            </span>
                            <span style="font-size: 12.5px; font-weight: 600; color: #64748b;">
                                / {{ $product->unit ?: 'piece' }}
                            </span>
                            @if($product->mrp > $product->selling_price)
                                <del class="text-muted ms-auto" style="font-size: 11.5px; color: #94a3b8 !important; text-decoration: line-through; font-weight: 500;">
                                    ₹{{ number_format($product->mrp) }}
                                </del>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons: Quick View & Add to Cart -->
                    <div class="d-flex gap-2 pt-2 border-top mt-auto" style="border-color: #f1f5f9;">
                        <button type="button" class="btn btn-sm flex-fill fw-semibold btn-quick-view-modal garudo-btn-quickview"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="₹{{ number_format($product->selling_price) }}"
                            data-unit="{{ $product->unit ?: 'Piece' }}"
                            data-mrp="{{ $product->mrp > $product->selling_price ? '₹' . number_format($product->mrp) : '' }}"
                            data-image="{{ $product->primary_image_url }}"
                            data-vendor="{{ $vendorName }}"
                            data-rating="{{ number_format($product->rating ?: 4.7, 1) }}"
                            data-url="{{ route('products.show', $product->slug) }}"
                            style="font-size: 11.5px; padding: 7px 8px; background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                            <i class="bi bi-eye" style="font-size: 12px;"></i> Quick View
                        </button>
                        <button type="button" onclick="ajaxAddToCart({{ $product->id }}, 1)" 
                            class="btn btn-sm flex-fill fw-bold d-flex align-items-center justify-content-center garudo-btn-addcart" 
                            style="font-size: 11.5px; padding: 7px 8px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff; border: none; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; box-shadow: 0 3px 8px rgba(249, 115, 22, 0.28); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);"
                            title="Add to Cart">
                            <i class="bi bi-cart3" style="font-size: 12px;"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center">
                <div class="p-5 bg-white rounded-4 border shadow-xs" style="max-width: 480px; margin: 0 auto;">
                    <i class="bi bi-box-seam fs-1 text-muted d-block mb-3"></i>
                    <h4 class="fw-bold text-dark">No Products Found</h4>
                    <p class="text-muted small mb-4">We couldn't find any products matching your current search or filter criteria.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold">
                        View All Products
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
