@extends('layouts.frontend')

@section('title', 'Browse Products - ' . config('marketplace.name'))

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Product Catalog</h3>
    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card product-card h-100">
                    <div class="position-relative bg-light text-center p-3">
                        <img src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->file_path) : 'https://placehold.co/300x300?text='.$product->name }}" class="img-fluid" style="height: 180px; object-fit: contain;" alt="{{ $product->name }}">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold text-dark text-truncate mb-2">{{ $product->name }}</h6>
                        <div class="mt-auto">
                            <div class="fw-bold fs-5 text-dark">₹{{ number_format($product->selling_price, 2) }}</div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-dark btn-sm w-100">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info">No products found matching your search.</div></div>
        @endforelse
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
