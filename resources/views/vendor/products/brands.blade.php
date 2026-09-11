@extends('layouts.vendor')

@section('title', 'Approved Brands')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Building Material Brands</h4>
        <p class="text-muted mb-0 small">Select from verified national and regional manufacturers and brands on Garudo.</p>
    </div>
</div>

<div class="row g-3">
    @forelse($brands as $brand)
        <div class="col-md-4 col-lg-3">
            <div class="card-custom text-center p-3 h-100">
                <div class="bg-light rounded p-3 mb-2 d-flex align-items-center justify-content-center" style="height: 70px;">
                    <i class="bi bi-shield-check text-success fs-2"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark">{{ $brand->name }}</h6>
                <span class="badge bg-success bg-opacity-10 text-success small">Verified Manufacturer</span>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <i class="bi bi-tags text-muted fs-1 mb-2 d-block"></i>
                <h6>No Brands Configured</h6>
                <p class="text-muted small">Brands will appear here as they are verified.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
