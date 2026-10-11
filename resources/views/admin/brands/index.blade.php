@extends('layouts.admin')

@section('title', 'Brand Master — Super Brands & Sub Brands')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title mb-1">
            <i class="bi bi-patch-check-fill text-success me-2"></i>Brand Master
        </h1>
        <p class="page-header-subtitle mb-0">Manage national Super Brands, Sub-brands, product line hierarchies, and trademark selling rights.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.brand-titles.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            <i class="bi bi-tags-fill me-1 text-primary"></i> Brand Titles & Tiers
        </a>
        <a href="{{ route('admin.brands.registry.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-journal-check me-1"></i> Brand Applications
        </a>
        <a href="{{ route('admin.dealer-authorizations.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-person-check me-1"></i> Dealer Authorizations
        </a>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-garudo-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-plus-circle-fill me-1"></i> Add New Brand
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- ---------------- STATS OVERVIEW ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card p-3 text-center">
            <div class="text-muted small fw-bold text-uppercase">Total Brands</div>
            <div class="kpi-value text-dark mb-0">{{ number_format($stats['total'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card p-3 text-center border-start border-4 border-success">
            <div class="text-success small fw-bold text-uppercase">Super Brands</div>
            <div class="kpi-value text-success mb-0">{{ number_format($stats['super_brands'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card p-3 text-center border-start border-4 border-primary">
            <div class="text-primary small fw-bold text-uppercase">Sub Brands</div>
            <div class="kpi-value text-primary mb-0">{{ number_format($stats['sub_brands'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card p-3 text-center border-start border-4 border-warning">
            <div class="text-warning small fw-bold text-uppercase">Protected</div>
            <div class="kpi-value text-warning mb-0">{{ number_format($stats['protected'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card p-3 text-center border-start border-4 border-info">
            <div class="text-info small fw-bold text-uppercase">Active Live</div>
            <div class="kpi-value text-info mb-0">{{ number_format($stats['active'] ?? 0) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="garudo-card p-3 text-center bg-light">
            <a href="{{ route('admin.brand-titles.index') }}" class="text-decoration-none d-flex flex-column align-items-center justify-content-center h-100">
                <i class="bi bi-tags fs-3 text-primary"></i>
                <span class="small fw-bold text-dark mt-1">Manage Titles</span>
            </a>
        </div>
    </div>
</div>

<!-- ---------------- FILTERS & SEARCH ---------------- -->
<div class="garudo-card p-3 mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small fw-bold me-1">Hierarchy:</span>
            <a class="btn btn-sm rounded-pill {{ $tierFilter === 'all' ? 'btn-dark' : 'btn-outline-secondary' }}" 
               href="{{ route('admin.brands.index', array_merge(request()->query(), ['tier' => 'all'])) }}">All Tiers</a>
            <a class="btn btn-sm rounded-pill {{ $tierFilter === 'super_brand' ? 'btn-success' : 'btn-outline-secondary' }}" 
               href="{{ route('admin.brands.index', array_merge(request()->query(), ['tier' => 'super_brand'])) }}">
               <i class="bi bi-diagram-2-fill me-1"></i> Super Brands Only
            </a>
            <a class="btn btn-sm rounded-pill {{ $tierFilter === 'sub_brand' ? 'btn-primary' : 'btn-outline-secondary' }}" 
               href="{{ route('admin.brands.index', array_merge(request()->query(), ['tier' => 'sub_brand'])) }}">
               <i class="bi bi-bezier2 me-1"></i> Sub Brands Only
            </a>

            @if(isset($brandTitles) && $brandTitles->count() > 0)
                <div class="dropdown">
                    <button class="btn btn-sm rounded-pill {{ $titleFilter !== 'all' ? 'btn-dark' : 'btn-outline-secondary' }} dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-tags me-1"></i> 
                        {{ $titleFilter !== 'all' ? ($brandTitles->firstWhere('id', $titleFilter)?->name ?? 'Custom Title') : 'Brand Title' }}
                    </button>
                    <ul class="dropdown-menu shadow-sm">
                        <li><a class="dropdown-item {{ $titleFilter === 'all' ? 'active' : '' }}" href="{{ route('admin.brands.index', array_merge(request()->query(), ['title' => 'all'])) }}">All Titles</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach($brandTitles as $bt)
                            <li>
                                <a class="dropdown-item {{ (string)$titleFilter === (string)$bt->id ? 'active' : '' }}" href="{{ route('admin.brands.index', array_merge(request()->query(), ['title' => $bt->id])) }}">
                                    <span class="badge {{ $bt->badge_class }} me-1">&bull;</span> {{ $bt->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="vr mx-2 d-none d-md-block"></div>

            <span class="text-muted small fw-bold me-1">Protection:</span>
            <a class="btn btn-sm rounded-pill {{ $protectionFilter === 'protected' ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}" 
               href="{{ route('admin.brands.index', array_merge(request()->query(), ['protection' => $protectionFilter === 'protected' ? 'all' : 'protected'])) }}">
               <i class="bi bi-shield-lock-fill me-1"></i> Protected
            </a>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('admin.brands.index') }}" method="GET" class="d-flex gap-2">
            <input type="hidden" name="tier" value="{{ $tierFilter }}">
            <input type="hidden" name="title" value="{{ $titleFilter }}">
            <input type="hidden" name="protection" value="{{ $protectionFilter }}">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="input-group input-group-sm" style="min-width: 260px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name, slug, origin..." value="{{ $search }}">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </div>
            @if($search !== '' || $tierFilter !== 'all' || $titleFilter !== 'all' || $protectionFilter !== 'all' || $statusFilter !== 'all')
                <a href="{{ route('admin.brands.index') }}" class="btn btn-sm btn-outline-danger rounded-pill" title="Reset Filters">
                    <i class="bi bi-x-circle"></i>
                </a>
            @endif
        </form>
    </div>
</div>

<!-- ---------------- BRANDS TABLE ---------------- -->
<div class="garudo-card overflow-hidden">
    <div class="table-responsive">
        <table class="table admin-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 70px;">Logo</th>
                    <th>Brand Details</th>
                    <th>Tier / Hierarchy</th>
                    <th class="text-center">Sub-Brands</th>
                    <th class="text-center">Products</th>
                    <th class="text-center">Dealers</th>
                    <th class="text-center">Protection</th>
                    <th class="text-center">Status</th>
                    <th class="text-end" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <!-- Logo -->
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" 
                                     alt="{{ $brand->name }}" 
                                     class="rounded-3 border object-fit-contain p-1 bg-white" 
                                     style="width: 48px; height: 48px;">
                            @else
                                <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center text-muted fw-bold" 
                                     style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($brand->name, 0, 2)) }}
                                </div>
                            @endif
                        </td>

                        <!-- Brand Details -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="fw-bold text-dark text-decoration-none fs-6">
                                    {{ $brand->name }}
                                </a>
                                @if($brand->is_featured)
                                    <span class="badge bg-warning text-dark small" title="Featured Brand">
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="text-muted small">
                                <code>{{ $brand->slug }}</code> &bull; 
                                <span class="text-secondary">{{ $brand->country_of_origin ?? 'India' }}</span>
                                @if($brand->trademark_number)
                                    &bull; <span class="text-muted" title="Trademark No.">TM: {{ $brand->trademark_number }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Hierarchy / Tier -->
                        <td>
                            <div class="d-flex flex-column gap-1 align-items-start">
                                @if($brand->brandTitle)
                                    <span class="badge rounded-pill {{ $brand->brandTitle->badge_class }} border px-3 py-1 fw-bold">
                                        <i class="bi bi-tags-fill me-1"></i> {{ $brand->brandTitle->name }}
                                    </span>
                                @elseif($brand->isSuperBrand())
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-1 fw-bold">
                                        <i class="bi bi-diagram-2-fill me-1"></i> Super Brand
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 fw-semibold">
                                        <i class="bi bi-bezier2 me-1"></i> Sub Brand
                                    </span>
                                @endif

                                @if(!$brand->isSuperBrand() && $brand->superBrand)
                                    <div class="small text-muted">
                                        Parent: <a href="{{ route('admin.brands.edit', $brand->superBrand->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $brand->superBrand->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </td>

                        <!-- Sub Brands Count -->
                        <td class="text-center">
                            @if($brand->isSuperBrand())
                                <a href="{{ route('admin.brands.index', ['tier' => 'sub_brand', 'search' => $brand->name]) }}" 
                                   class="badge rounded-pill bg-light text-dark border px-2 py-1 text-decoration-none">
                                    <strong>{{ $brand->sub_brands_count }}</strong> Sub-brands
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>

                        <!-- Products Count -->
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $brand->products_count }} Products
                            </span>
                        </td>

                        <!-- Verified Dealers Count -->
                        <td class="text-center">
                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                <i class="bi bi-patch-check-fill me-1"></i>{{ $brand->verified_dealer_authorizations_count }}
                            </span>
                        </td>

                        <!-- Protection Status Toggle -->
                        <td class="text-center">
                            <form action="{{ route('admin.brands.toggle-protection', $brand->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-sm rounded-pill px-2 py-1 {{ $brand->is_protected ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}" 
                                        title="{{ $brand->is_protected ? 'Restricted: Only verified dealers can list' : 'Open: Any vendor can list' }}">
                                    @if($brand->is_protected)
                                        <i class="bi bi-shield-lock-fill me-1"></i> Protected
                                    @else
                                        <i class="bi bi-shield-slash me-1"></i> Open
                                    @endif
                                </button>
                            </form>
                        </td>

                        <!-- Status Toggle -->
                        <td class="text-center">
                            <form action="{{ route('admin.brands.toggle-status', $brand->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm border-0 p-0" title="Click to toggle status">
                                    @if($brand->status === 'active')
                                        <span class="pill-status pill-active"><i class="bi bi-check-circle-fill"></i> Active</span>
                                    @else
                                        <span class="pill-status pill-rejected"><i class="bi bi-slash-circle-fill"></i> Inactive</span>
                                    @endif
                                </button>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                   class="btn btn-sm btn-outline-primary rounded-circle p-1" 
                                   style="width: 32px; height: 32px;" 
                                   title="Edit Brand">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete brand {{ $brand->name }}? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger rounded-circle p-1" 
                                            style="width: 32px; height: 32px;" 
                                            title="Delete Brand">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-patch-question display-4 text-muted mb-3"></i>
                                <h5 class="fw-bold text-dark">No Brands Found</h5>
                                <p class="text-muted small mb-3">No brands matched your filter criteria. Create your first brand now.</p>
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-garudo-primary btn-sm rounded-pill px-4">
                                    <i class="bi bi-plus-circle me-1"></i> Add Brand
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($brands->hasPages())
        <div class="p-3 border-top d-flex justify-content-between align-items-center">
            <span class="small text-muted">Showing {{ $brands->firstItem() }} to {{ $brands->lastItem() }} of {{ $brands->total() }} brands</span>
            {{ $brands->links() }}
        </div>
    @endif
</div>
@endsection
