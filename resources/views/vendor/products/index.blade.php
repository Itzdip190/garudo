@extends('layouts.vendor')

@section('title', 'Product Management')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Products Directory</h4>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">{{ $counts['all'] }} Products</span>
        </div>
        <p class="text-muted mb-0 small">Manage building materials catalog, multi-tier pricing, technical specs, variations, and stock levels.</p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('vendor.products.quick-price') }}" class="btn btn-outline-warning text-dark btn-sm px-3 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-tag me-1"></i> Quick Price Change
        </a>
        <a href="{{ route('vendor.products.bulk') }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-cloud-arrow-up me-1"></i> Bulk & Backup
        </a>
        <a href="{{ route('vendor.products.logistics-policy') }}" class="btn btn-outline-primary btn-sm px-3 fw-semibold" style="border-radius: 8px;">
            <i class="bi bi-truck me-1"></i> F.O.R. Policy Guide
        </a>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-success btn-sm px-3 fw-bold" style="background-color: var(--garudo-primary); border: none; border-radius: 8px;">
            <i class="bi bi-plus-circle me-1"></i> Add New Product
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Filters & Search Toolbar -->
<div class="card-custom mb-3 p-3 shadow-sm">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <!-- Status Tabs -->
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('vendor.products.index', ['status' => 'all', 'category_id' => $categoryId]) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-dark' : 'btn-light border' }}" style="border-radius: 20px;">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('vendor.products.index', ['status' => 'active', 'category_id' => $categoryId]) }}" class="btn btn-sm {{ $status === 'active' ? 'btn-success' : 'btn-light border' }}" style="border-radius: 20px;">
                Active ({{ $counts['active'] }})
            </a>
            <a href="{{ route('vendor.products.index', ['status' => 'pending', 'category_id' => $categoryId]) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-light border' }}" style="border-radius: 20px;">
                Pending Approval ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('vendor.products.index', ['status' => 'inactive', 'category_id' => $categoryId]) }}" class="btn btn-sm {{ $status === 'inactive' ? 'btn-secondary' : 'btn-light border' }}" style="border-radius: 20px;">
                Inactive ({{ $counts['inactive'] }})
            </a>
        </div>

        <!-- Search & Category Filter Form -->
        <form method="GET" action="{{ route('vendor.products.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="hidden" name="status" value="{{ $status }}">
            
            <select name="category_id" class="form-select form-select-sm" style="max-width: 180px;" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search name, SKU, HSN..." style="max-width: 200px; border-radius: 8px;">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>

            @if($search || $categoryId)
                <a href="{{ route('vendor.products.index') }}" class="btn btn-sm btn-link text-muted text-decoration-none">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card-custom p-0 overflow-hidden shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3 text-muted small" style="width: 32%;">PRODUCT & DETAILS</th>
                    <th class="text-muted small">SKU</th>
                    <th class="text-muted small">CATEGORY</th>
                    <th class="text-muted small">B2B PRICE</th>
                    <th class="text-muted small">STOCK</th>
                    <th class="text-muted small">STATUS</th>
                    <th class="pe-3 text-end text-muted small" style="width: 14%;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0; overflow: hidden;">
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0">{{ $product->name }}</div>
                                    <div class="d-flex align-items-center gap-2 text-muted small" style="font-size: 0.75rem;">
                                        <span>MOQ: {{ $product->min_order_qty }} {{ $product->unit }}</span>
                                        @if($product->product_type === 'variable')
                                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-0">Variable ({{ $product->variants->count() }} Variants)</span>
                                        @endif
                                        @if($product->specifications->count() > 0)
                                            <span class="badge bg-light text-muted border px-2 py-0">{{ $product->specifications->count() }} Specs</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="small text-muted font-monospace">{{ $product->sku }}</td>
                        <td class="small">
                            <span class="badge bg-light text-dark border">{{ $product->category->name ?? 'General' }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">₹{{ number_format($product->selling_price, 2) }}</div>
                            @if($product->mrp > $product->selling_price)
                                <div class="text-muted text-decoration-line-through small" style="font-size: 0.72rem;">MRP: ₹{{ number_format($product->mrp, 2) }}</div>
                            @endif
                        </td>
                        <td class="small">
                            <span class="fw-semibold text-{{ ($product->inventory->quantity_available ?? 0) > 10 ? 'success' : 'danger' }}">
                                {{ $product->inventory->quantity_available ?? 0 }} {{ $product->unit }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="pe-3 text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-light border" title="View Public Page">
                                    <i class="bi bi-eye text-muted"></i>
                                </a>
                                <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Product">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Move this product to trash?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Product">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark">No Products Found</h6>
                            <p class="small text-muted mb-3">You have not added any products matching this criteria.</p>
                            <a href="{{ route('vendor.products.create') }}" class="btn btn-sm btn-success px-4" style="background-color: var(--garudo-primary); border-radius: 8px;">
                                <i class="bi bi-plus-lg me-1"></i> Add Your First Product
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
