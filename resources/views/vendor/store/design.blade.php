@extends('layouts.vendor')

@section('title', 'Storefront Designer & Branding Studio')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Storefront Designer & Branding Studio</h4>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">
                    <i class="bi bi-palette-fill me-1"></i> Public Showcase
                </span>
            </div>
            <p class="text-muted mb-0 small">Customize your public store banner, brand logo, tagline, and business bio seen by contractors & builders across India.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('store.show', $vendor->company_slug ?? $vendor->id) }}" target="_blank" class="btn btn-garudo-primary fw-bold shadow-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Live Storefront
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show p-3 small mb-4 rounded-4 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show p-3 small mb-4 rounded-4 shadow-sm border-0" role="alert">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('vendor.store.design.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Branding Controls -->
            <div class="col-lg-7">
                <!-- 1. Store Header & Identity -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-shop me-2 text-primary"></i> Store Identity & Custom Web Address
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Company / Brand Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $vendor->company_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Custom Store URL Slug</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light small">/store/</span>
                                    <input type="text" name="company_slug" class="form-control font-monospace small" value="{{ old('company_slug', $vendor->company_slug) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Business Structure / Type</label>
                                <select name="business_type" class="form-select">
                                    <option value="Manufacturer & Distributor" {{ $vendor->business_type === 'Manufacturer & Distributor' ? 'selected' : '' }}>Manufacturer & Distributor</option>
                                    <option value="Authorized Wholesale Dealer" {{ $vendor->business_type === 'Authorized Wholesale Dealer' ? 'selected' : '' }}>Authorized Wholesale Dealer</option>
                                    <option value="Stockist & Retailer" {{ $vendor->business_type === 'Stockist & Retailer' ? 'selected' : '' }}>Stockist & Retailer</option>
                                    <option value="Direct Producer / Mill" {{ $vendor->business_type === 'Direct Producer / Mill' ? 'selected' : '' }}>Direct Producer / Mill</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Official Corporate Website</label>
                                <input type="url" name="company_website" class="form-control" value="{{ old('company_website', $vendor->company_website) }}" placeholder="https://www.yourcompany.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Storefront Overview / About Company</label>
                                <textarea name="company_description" class="form-control" rows="3" placeholder="Tell buyers about your manufacturing capacity, certifications, dispatch hubs, and experience...">{{ old('company_description', $vendor->company_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Logo & Banner Studio -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-image-fill me-2 text-warning"></i> Brand Logo & Store Banner
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <!-- Logo Upload -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small d-block">Store Brand Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center overflow-hidden" style="width: 80px; height: 80px; min-width: 80px;">
                                    @if($vendor->company_logo)
                                        <img src="{{ asset('storage/' . $vendor->company_logo) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                                    @else
                                        <i class="bi bi-building fs-2 text-muted"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="company_logo" class="form-control form-control-sm" accept="image/*">
                                    <div class="form-text small">Upload Square PNG / JPG (Recommended: 400x400px).</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Banner Upload -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small d-block">Custom Hero Banner</label>
                            <input type="file" name="company_banner" class="form-control form-control-sm mb-2" accept="image/*">
                            <div class="form-text small mb-3">Upload wide landscape image (Recommended: 1600x400px).</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-garudo-primary fw-bold px-4 py-2.5 rounded-3 shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i> Save Storefront Design
                </button>
            </div>

            <!-- Right Column: Live Storefront Card Preview -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 sticky-top bg-white" style="top: 85px;">
                    <div class="card-header bg-dark text-white py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                        <span class="fw-bold small"><i class="bi bi-phone me-1"></i> Live Storefront Preview</span>
                        <span class="badge bg-success rounded-pill">Public View</span>
                    </div>
                    <div class="card-body p-0 overflow-hidden rounded-bottom-4">
                        <!-- Hero Preview Box -->
                        <div class="p-4 position-relative" style="background: linear-gradient(135deg, #0B4D3C 0%, #062E24 100%); color: #FFFFFF;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-3 bg-white p-2 d-flex align-items-center justify-content-center border" style="width: 60px; height: 60px;">
                                    @if($vendor->company_logo)
                                        <img src="{{ asset('storage/' . $vendor->company_logo) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    @else
                                        <i class="bi bi-shop text-success fs-3"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="badge bg-warning text-dark mb-1 fw-bold" style="font-size: 0.65rem;">
                                        <i class="bi bi-award-fill"></i> Verified Supplier
                                    </div>
                                    <h6 class="fw-bold text-white mb-0">{{ $vendor->company_name }}</h6>
                                    <div class="text-white-50" style="font-size: 0.72rem;">{{ $vendor->business_type ?? 'Manufacturer & Wholesaler' }}</div>
                                </div>
                            </div>
                            <p class="text-white-50 small mb-2 text-truncate" style="font-size: 0.75rem;">
                                {{ $vendor->company_description ?? 'Building materials direct supply & wholesale distribution.' }}
                            </p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 small">
                                    <i class="bi bi-geo-alt text-warning"></i> {{ $vendor->primary_dispatch_state ?? 'Pan-India' }}
                                </span>
                                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 small">
                                    <i class="bi bi-star-fill text-warning"></i> 4.8 / 5.0
                                </span>
                            </div>
                        </div>

                        <!-- Sample Products in preview -->
                        <div class="p-3 bg-light border-top">
                            <div class="fw-bold text-dark small mb-2">Listed Products ({{ $products->count() }})</div>
                            <div class="row g-2">
                                @forelse($products->take(4) as $p)
                                    <div class="col-6">
                                        <div class="p-2 bg-white rounded border">
                                            <div class="fw-semibold text-dark text-truncate small" style="font-size: 0.75rem;">{{ $p->name }}</div>
                                            <div class="fw-bold text-success" style="font-size: 0.8rem;">₹{{ number_format($p->selling_price, 2) }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted small py-3">No products listed yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
