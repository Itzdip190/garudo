@extends('layouts.vendor')

@section('title', 'Store Banner & Logo')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Store Banner & Branding</h4>
        <p class="text-muted mb-0 small">Customize how your storefront appears to buyers across India.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-custom mb-4">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Store Logo</h6>
            <div class="d-flex align-items-center gap-3">
                <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    @if($vendor->company_logo)
                        <img src="{{ asset('storage/' . $vendor->company_logo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="bi bi-building fs-1 text-muted"></i>
                    @endif
                </div>
                <div>
                    <input type="file" class="form-control form-control-sm mb-1" accept="image/*">
                    <span class="text-muted small">Recommended: 400x400px (PNG / JPG)</span>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <h6 class="fw-bold mb-3 border-bottom pb-2">Store Header Banner</h6>
            <div class="bg-light border rounded p-4 text-center mb-3">
                <i class="bi bi-image fs-1 text-muted d-block mb-1"></i>
                <span class="text-muted small">Recommended: 1200x300px Banner image</span>
                <input type="file" class="form-control form-control-sm mt-2" accept="image/*">
            </div>
            <div class="text-end">
                <button class="btn btn-success" style="background-color: var(--garudo-primary); border: none;">Save Branding Assets</button>
            </div>
        </div>
    </div>
</div>
@endsection
