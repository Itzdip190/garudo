@extends('layouts.customer')

@section('title', 'My Reviews')

@section('content')
<div class="reviews-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">My Reviews & Ratings</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Verified ratings and feedback shared for building materials suppliers.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customer.orders') }}" class="btn-primary-action">
                <i class="bi bi-star"></i> Review Past Orders
            </a>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="card-custom">
        @if($reviews->count() > 0)
        <div class="d-flex flex-column gap-4">
            @foreach($reviews as $review)
            <div class="pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-2">
                    <div>
                        <h3 class="fw-bold text-dark mb-1" style="font-size: 1rem;">{{ $review->product?->name ?? 'Material Review' }}</h3>
                        <div class="text-muted" style="font-size: 0.78rem;">
                            Supplier: <strong class="text-dark">{{ $review->vendor?->shop_name ?? $review->vendor?->company_name ?? 'Verified Seller' }}</strong> &bull; Reviewed on {{ $review->created_at?->format('d M, Y') }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($review->is_verified_purchase)
                        <span class="badge bg-light text-success border" style="font-size: 0.7rem;">
                            <i class="bi bi-patch-check-fill me-1"></i> Verified Purchase
                        </span>
                        @endif
                        <span class="badge-delivered">
                            {{ ucfirst($review->status ?? 'Approved') }}
                        </span>
                    </div>
                </div>

                <!-- Star Rating & Review Title -->
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="text-warning" style="font-size: 0.9rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                        @endfor
                    </div>
                    @if($review->title)
                    <span class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $review->title }}</span>
                    @endif
                </div>

                <p class="text-muted mb-0" style="font-size: 0.84rem; line-height: 1.5;">
                    {{ $review->review }}
                </p>
            </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
        @else
        <div class="text-center py-5 px-3">
            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; color: var(--garudo-primary);">
                <i class="bi bi-star fs-3"></i>
            </div>
            <h5 class="fw-bold text-dark mb-1">No Reviews Written Yet</h5>
            <p class="text-muted mb-3" style="font-size: 0.85rem;">Share ratings and reviews for suppliers after materials are delivered to your site.</p>
            <a href="{{ route('customer.orders') }}" class="btn-primary-action">
                <i class="bi bi-box-seam me-1"></i> View Delivered Orders
            </a>
        </div>
        @endif
    </div>

</div>
@endsection
