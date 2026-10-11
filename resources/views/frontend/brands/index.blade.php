@extends('layouts.frontend')

@section('title', 'Certified Building Material Brands - Garudo B2B Marketplace')

@push('styles')
<style>
    .brand-hero-header {
        background: linear-gradient(135deg, #0d1b3e 0%, #162650 100%);
        color: #fff;
        border-radius: 0 0 16px 16px;
    }
    .brand-card {
        border: 1px solid var(--garudo-card-border);
        border-radius: 12px;
        background: #fff;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        height: 100%;
    }
    .brand-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(13, 27, 62, 0.08);
        border-color: var(--garudo-yellow);
        color: inherit;
    }
    .brand-logo-wrap {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .brand-logo-wrap img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Header -->
    <div class="brand-hero-header py-5 px-4 mb-4 text-center">
        <div class="container">
            <span class="badge bg-yellow text-dark px-3 py-1 rounded-pill fw-bold text-uppercase mb-2">Verified Manufacturing Partners</span>
            <h2 class="fw-black text-white mb-2">Explore Certified Building Material Brands</h2>
            <p class="text-white-50 mx-auto small mb-4" style="max-width: 650px;">
                Direct sourcing from India's leading manufacturers: UltraTech, Tata Tiscon, Jindal Panther, Kajaria, Astral, Asian Paints, and more.
            </p>

            <!-- Search Brands -->
            <div class="mx-auto" style="max-width: 500px;">
                <form action="{{ route('brands.index') }}" method="GET" class="input-group">
                    <input type="text" name="q" class="form-control form-control-lg border-0" placeholder="Search brand name (e.g. UltraTech, Tata)..." value="{{ $search }}">
                    <button class="btn btn-yellow px-4 fw-bold" type="submit"><i class="bi bi-search me-1"></i> Search</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Brands Grid -->
    <div class="container mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold mb-0 text-navy" style="color: var(--garudo-navy);">
                All Certified Brands ({{ $brands->count() }})
            </h5>
            <span class="text-muted small">100% Genuine & Manufacturer-Backed Warranty</span>
        </div>

        <div class="row g-3">
            @forelse($brands as $brand)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="{{ route('brands.show', $brand->slug) }}" class="brand-card">
                        <div class="brand-logo-wrap text-dark">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}">
                            @else
                                <i class="bi bi-patch-check-fill text-warning"></i>
                            @endif
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark text-truncate" style="font-size: 13px;">{{ $brand->name }}</h6>
                            <span class="badge bg-light text-muted border small">
                                {{ $brand->products_count }} Products Listed
                            </span>

                            @php
                                $vendorName = null;
                                if ($brand->ownerVendor) {
                                    $vendorName = $brand->ownerVendor->shop_name ?: ($brand->ownerVendor->name ?: $brand->ownerVendor->company_name ?? null);
                                } elseif ($brand->verifiedDealerAuthorizations && $brand->verifiedDealerAuthorizations->isNotEmpty()) {
                                    $firstDealer = $brand->verifiedDealerAuthorizations->first();
                                    if ($firstDealer && $firstDealer->vendor) {
                                        $vendorName = $firstDealer->vendor->shop_name ?: ($firstDealer->vendor->name ?: $firstDealer->vendor->company_name ?? null);
                                    }
                                }
                            @endphp
                            <div class="w-100 mt-2 pt-2 border-top text-start">
                                <div class="text-muted text-uppercase" style="font-size: 9.5px; font-weight: 700; letter-spacing: 0.5px;">Vendor / Supplier</div>
                                <div class="fw-semibold text-dark text-truncate" style="font-size: 11px;" title="{{ $vendorName ?: 'Garudo Verified Direct' }}">
                                    <i class="bi bi-shop text-warning me-1"></i>{{ $vendorName ?: 'Garudo Verified Direct' }}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                        <i class="bi bi-patch-question text-muted display-4 mb-3"></i>
                        <h5 class="fw-bold text-dark">No Brands Found</h5>
                        <p class="text-muted small">Try searching with a different brand keyword.</p>
                        <a href="{{ route('brands.index') }}" class="btn btn-sm btn-dark mx-auto">View All Brands</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
