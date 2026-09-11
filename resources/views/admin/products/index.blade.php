@extends('layouts.admin')

@section('title', 'Products Catalog & Moderation')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Products Catalog & Moderation</h1>
        <p class="page-header-subtitle mb-0">Approve seller listings, enforce quality standards & manage marketplace inventory.</p>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ !request('status') && !request('deal_of_day') && !request('sponsored') && !request('featured') ? 'border-primary' : '' }}">
                <div class="text-muted small fw-bold text-uppercase">Total Catalog</div>
                <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('status') === 'pending' ? 'border-danger' : '' }}">
                <div class="text-danger small fw-bold text-uppercase">Pending Review</div>
                <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['pending'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.products.index', ['deal_of_day' => '1']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('deal_of_day') === '1' ? 'border-danger bg-danger-subtle' : '' }}">
                <div class="text-danger small fw-bold text-uppercase"><i class="bi bi-fire me-1"></i> Deals of Day</div>
                <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['deals_count'] ?? 0 }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.products.index', ['sponsored' => '1']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('sponsored') === '1' ? 'border-primary bg-primary-subtle' : '' }}">
                <div class="text-primary small fw-bold text-uppercase"><i class="bi bi-award-fill me-1"></i> Sponsored Ads</div>
                <div class="fs-4 fw-bold text-primary mt-1">{{ $stats['sponsored_count'] ?? 0 }}</div>
            </div>
        </a>
    </div>
</div>

<!-- ---------------- FILTER & SEARCH BAR ---------------- -->
<div class="garudo-card p-3 mb-4">
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Product Name, SKU, Seller..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="vendor_id" class="form-select">
                <option value="">All Vendors</option>
                @foreach($vendors as $ven)
                    <option value="{{ $ven->id }}" {{ request('vendor_id') == $ven->id ? 'selected' : '' }}>{{ $ven->company_name ?? $ven->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-garudo-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
    </form>
</div>

<!-- ---------------- PRODUCTS TABLE ---------------- -->
<div class="garudo-card p-0 overflow-hidden">
    @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Product & Details</th>
                        <th>Seller / Vendor</th>
                        <th>Category</th>
                        <th>Selling Price</th>
                        <th style="min-width: 220px;">Storefront Placements</th>
                        <th>Approval</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; overflow: hidden;">
                                        @if($product->primaryImage && $product->primaryImage->image_path)
                                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="bi bi-box text-muted fs-4"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</div>
                                        <div class="text-muted small">SKU: <code>{{ $product->sku ?? 'N/A' }}</code></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $product->vendor->company_name ?? 'Unknown Vendor' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'General' }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">₹{{ number_format($product->selling_price, 2) }}</div>
                                @if($product->mrp > $product->selling_price)
                                    <div class="text-muted small text-decoration-line-through">₹{{ number_format($product->mrp, 2) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <!-- Deal of Day Toggle -->
                                    <form action="{{ route('admin.products.toggle_deal_of_day', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-xs px-2 py-1 rounded-pill border {{ $product->is_deal_of_the_day ? 'btn-danger text-white' : 'btn-light text-muted' }}" title="{{ $product->is_deal_of_the_day ? 'Remove from Deals of the Day' : 'Promote to Deals of the Day' }}" style="font-size: 11px;">
                                            <i class="bi bi-fire"></i> Deal
                                        </button>
                                    </form>

                                    <!-- Sponsored Toggle -->
                                    <form action="{{ route('admin.products.toggle_sponsored', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-xs px-2 py-1 rounded-pill border {{ $product->is_sponsored ? 'btn-primary text-white' : 'btn-light text-muted' }}" title="{{ $product->is_sponsored ? 'Remove from Sponsored Ad' : 'Promote to Sponsored Showcase' }}" style="font-size: 11px;">
                                            <i class="bi bi-award-fill"></i> Sponsor
                                        </button>
                                    </form>

                                    <!-- Featured Toggle -->
                                    <form action="{{ route('admin.products.toggle_featured', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-xs px-2 py-1 rounded-pill border {{ $product->is_featured ? 'btn-warning text-dark' : 'btn-light text-muted' }}" title="{{ $product->is_featured ? 'Remove Featured' : 'Mark as Featured' }}" style="font-size: 11px;">
                                            <i class="bi bi-star-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <span class="pill-status pill-{{ strtolower($product->approval_status) }}">
                                    {{ ucfirst($product->approval_status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.products.show', $product->id) }}">
                                                <i class="bi bi-eye text-primary me-2"></i> Inspect Specs
                                            </a>
                                        </li>
                                        @if($product->approval_status !== 'approved')
                                            <li>
                                                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="bi bi-check-circle me-2"></i> Approve & Publish
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li>
                                            <form action="{{ route('admin.products.toggle_status', $product->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="bi bi-toggle-on me-2"></i> {{ $product->status === 'active' ? 'Delist / Inactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Delete Product
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-box-seam fs-1 mb-2 d-block"></i>
            <h5>No Products Found</h5>
            <p class="small">Try adjusting your filters or search criteria.</p>
        </div>
    @endif
</div>
@endsection
