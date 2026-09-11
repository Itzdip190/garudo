@extends('layouts.frontend')

@section('title', $brand->name . ' - Building Materials Catalog - Garudo')

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
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-4">
                <div class="rounded-circle bg-light border p-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; min-width: 80px;">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    @else
                        <i class="bi bi-patch-check-fill text-warning fs-1"></i>
                    @endif
                </div>
                <div>
                    <div class="badge bg-warning text-dark mb-1 fw-bold">Official Certified Brand</div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $brand->name }}</h3>
                    <p class="text-muted small mb-0">{{ $brand->description ?? 'Manufacturer direct building materials with wholesale pricing and guaranteed test certificates.' }}</p>
                </div>
            </div>
            <div class="text-md-end">
                <span class="fs-5 fw-bold text-dark d-block">{{ $products->total() }} Products</span>
                <span class="text-muted small">Available for Pan-India Dispatch</span>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-3">
        @forelse($products as $product)
            @php
                $img = $product->primaryImage ? asset('storage/' . $product->primaryImage->image_path) : 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=600&q=80';
            @endphp
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card-custom h-100 p-3 d-flex flex-column justify-content-between">
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
                        <div class="text-muted small mb-2" style="font-size: 11px;">
                            Supplier: <strong>{{ $product->vendor->company_name ?? 'Garudo Supplier' }}</strong>
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
@endsection
