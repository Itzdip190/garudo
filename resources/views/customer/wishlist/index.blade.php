@extends('layouts.customer')

@section('title', 'My Wishlist')

@section('content')
<div class="wishlist-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">My Wishlist ({{ $wishlistItems->total() ?? $wishlistItems->count() }})</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Materials and supplies saved for upcoming building projects.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.index') }}" class="btn-primary-action">
                <i class="bi bi-search"></i> Explore Marketplace
            </a>
        </div>
    </div>

    <!-- Wishlist Grid -->
    @if($wishlistItems->count() > 0)
    <div class="row g-4 mb-4">
        @foreach($wishlistItems as $item)
        @php
            $product = $item->product;
        @endphp
        @if($product)
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <div class="card-custom h-100 p-0 overflow-hidden d-flex flex-column justify-content-between">
                <div>
                    <!-- Product Image -->
                    <div class="position-relative bg-light text-center" style="height: 180px;">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=300&auto=format&fit=crop&q=80'">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
                                <i class="bi bi-box fs-1"></i>
                            </div>
                        @endif
                        <span class="badge position-absolute top-0 start-0 m-2 bg-success" style="font-size: 0.68rem; font-weight: 600;">
                            In Stock
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="p-3">
                        <span class="text-muted" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 600;">{{ $product->category?->name ?? 'Building Material' }}</span>
                        <h3 class="fw-bold text-dark mt-1 mb-1 text-truncate" style="font-size: 0.92rem;" title="{{ $product->name }}">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="text-dark text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="text-muted mb-2" style="font-size: 0.75rem;">
                            <i class="bi bi-shop me-1"></i> {{ $product->vendor?->shop_name ?? $product->vendor?->company_name ?? 'Verified Seller' }}
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="fw-extrabold text-dark" style="font-size: 1.15rem; color: var(--garudo-primary) !important;">₹{{ number_format($product->price ?? $product->selling_price ?? 0, 2) }}</span>
                            @if(!empty($product->mrp) && $product->mrp > ($product->price ?? $product->selling_price ?? 0))
                            <span class="text-muted text-decoration-line-through" style="font-size: 0.8rem;">₹{{ number_format($product->mrp, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="p-3 pt-0">
                    <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="btn-primary-action w-100 justify-content-center py-2">
                        <i class="bi bi-cart-plus me-1"></i> View & Order
                    </a>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    <div>
        {{ $wishlistItems->links() }}
    </div>
    @else
    <div class="card-custom text-center py-5">
        <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
            <i class="bi bi-heart fs-3"></i>
        </div>
        <h5 class="fw-bold text-dark mb-1">Your Wishlist is Empty</h5>
        <p class="text-muted mb-3" style="font-size: 0.85rem;">Save materials you want to compare or order for your project site.</p>
        <a href="{{ route('products.index') }}" class="btn-primary-action">
            <i class="bi bi-search me-1"></i> Explore Products
        </a>
    </div>
    @endif

</div>
@endsection
