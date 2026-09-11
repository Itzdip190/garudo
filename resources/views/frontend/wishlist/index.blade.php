@extends('layouts.frontend')

@section('title', 'Saved Wishlist - Garudo B2B Marketplace')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: var(--garudo-navy);">
                <i class="bi bi-heart-fill me-2 text-danger"></i> Saved Wishlist Items ({{ $count }})
            </h4>
            <p class="text-muted small mb-0">Saved products for future procurement, bulk RFQ comparison, and price monitoring.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            <i class="bi bi-box-seam me-1"></i> Browse More Products
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($count > 0)
        <div class="row g-3">
            @foreach($products as $product)
                @php
                    $img = $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=400&q=80';
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card-custom h-100 p-3 d-flex flex-column justify-content-between position-relative">
                        <!-- Remove button -->
                        <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light rounded-circle shadow-sm border" title="Remove from Wishlist" style="width: 32px; height: 32px; padding: 0;">
                                <i class="bi bi-x text-danger fs-6"></i>
                            </button>
                        </form>

                        <div>
                            <div style="height: 160px; background: #f8fafc; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <img src="{{ $img }}" alt="{{ $product->name }}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </a>
                            </div>

                            <div class="text-muted small mb-1" style="font-size: 10px;">
                                {{ $product->brand->name ?? $product->category->name ?? 'Materials' }}
                            </div>
                            <h6 class="fw-bold mb-1 text-truncate" title="{{ $product->name }}">
                                <a href="{{ route('products.show', $product->slug) }}" class="text-dark text-decoration-none">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <div class="text-muted small mb-2" style="font-size: 11px;">
                                Supplier: <strong>{{ $product->vendor->company_name ?? 'Garudo Supplier' }}</strong>
                            </div>

                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                <span class="fs-5 fw-bold text-dark">₹{{ number_format($product->selling_price, 2) }}</span>
                                <span class="text-muted small">/ {{ $product->unit ?? 'pc' }}</span>
                            </div>
                        </div>

                        <div class="pt-2 border-top mt-2">
                            <form action="{{ route('wishlist.move_to_cart', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-yellow w-100 fw-bold">
                                    <i class="bi bi-cart-plus me-1"></i> Move to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty Wishlist -->
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 90px; height: 90px;">
                <i class="bi bi-heart text-muted display-4"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Your Wishlist is Empty</h4>
            <p class="text-muted small mx-auto mb-4" style="max-width: 480px;">
                Save building materials, check wholesale rates, and plan project procurement by adding items to your wishlist.
            </p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-yellow px-4 py-2.5 fw-bold rounded-pill shadow-sm">
                    <i class="bi bi-box-seam me-1"></i> Explore Products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
