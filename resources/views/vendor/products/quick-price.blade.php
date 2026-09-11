@extends('layouts.vendor')

@section('title', 'Quick Price Change System')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Quick Price Change System</h4>
            <span class="badge bg-warning bg-opacity-10 text-dark rounded-pill px-3 py-1 small fw-semibold">Batch & Inline Pricing</span>
        </div>
        <p class="text-muted mb-0 small">Adjust prices in bulk across categories/subcategories (percentage/flat amount) or edit prices directly in the grid below.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> Products Catalog
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- 1. Batch Formula Price Updater Card -->
<div class="card-custom mb-4 p-4 shadow-sm" style="border-left: 4px solid var(--garudo-primary);">
    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-calculator text-success me-2"></i>Batch Formula Price Updater (Category / Brand Wise)</h6>
            <small class="text-muted">Apply bulk percentage or flat price changes across an entire category or brand in 1-click</small>
        </div>
    </div>

    <form action="{{ route('vendor.products.quick-price.batch') }}" method="POST" onsubmit="return confirmBatchUpdate(this)">
        @csrf
        <div class="row g-3 align-items-end">
            <!-- Filter Category -->
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-dark mb-1">Target Category</label>
                <select name="category_id" class="form-select form-select-sm" id="batchCategory">
                    <option value="">-- All Categories (Entire Catalog) --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Brand -->
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-dark mb-1">Target Brand</label>
                <select name="brand_id" class="form-select form-select-sm" id="batchBrand">
                    <option value="">-- All Brands --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Target Price Field -->
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-dark mb-1">Price Type to Update</label>
                <select name="target_field" class="form-select form-select-sm" required>
                    <option value="selling_price">Selling Price Only</option>
                    <option value="wholesale_price">Wholesale Price</option>
                    <option value="mrp">MRP / Retail Price</option>
                    <option value="all_prices">All Prices Proportionally</option>
                </select>
            </div>

            <!-- Action Type -->
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-dark mb-1">Adjustment Action</label>
                <select name="action_type" class="form-select form-select-sm" required>
                    <option value="percentage_increase">Percentage Increase (+ %)</option>
                    <option value="percentage_decrease">Percentage Decrease (- %)</option>
                    <option value="flat_increase">Flat Amount Increase (+ ₹)</option>
                    <option value="flat_decrease">Flat Amount Decrease (- ₹)</option>
                    <option value="set_fixed">Set to Fixed Price (₹ Exact)</option>
                </select>
            </div>

            <!-- Amount / Percentage Value -->
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-dark mb-1">Value (₹ or %)</label>
                <div class="input-group input-group-sm">
                    <input type="number" step="0.01" name="amount_value" class="form-control" placeholder="e.g. 5 or 20" min="0.01" required>
                    <button type="submit" class="btn btn-success px-3 fw-bold" style="background-color: var(--garudo-primary); border: none;">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- 2. Fast Inline Editable Price Grid -->
<div class="card-custom p-0 overflow-hidden shadow-sm">
    <!-- Filter Toolbar -->
    <div class="p-3 bg-light border-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <form method="GET" action="{{ route('vendor.products.quick-price') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <select name="category_id" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                <option value="">Filter by Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="brand_id" class="form-select form-select-sm" style="max-width: 180px;" onchange="this.form.submit()">
                <option value="">Filter by Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $brandId == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>

            <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search by name or SKU..." style="max-width: 220px;">
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i></button>
            
            @if($categoryId || $brandId || $search)
                <a href="{{ route('vendor.products.quick-price') }}" class="btn btn-sm btn-link text-muted text-decoration-none">Clear</a>
            @endif
        </form>

        <div>
            <button type="button" class="btn btn-success btn-sm px-4 fw-bold" onclick="document.getElementById('inlinePriceForm').submit()" style="background-color: var(--garudo-primary); border: none; border-radius: 8px;">
                <i class="bi bi-check2-all me-1"></i> Save All Changed Prices
            </button>
        </div>
    </div>

    <!-- Pricing Table Form -->
    <form id="inlinePriceForm" action="{{ route('vendor.products.quick-price.inline') }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-white text-muted small border-bottom">
                    <tr>
                        <th class="ps-3" style="width: 35%;">PRODUCT & SKU</th>
                        <th style="width: 15%;">CATEGORY</th>
                        <th style="width: 16%;">SELLING PRICE (₹)</th>
                        <th style="width: 16%;">MRP / RETAIL (₹)</th>
                        <th style="width: 16%;">WHOLESALE PRICE (₹)</th>
                        <th class="pe-3 text-end" style="width: 8%;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0; overflow: hidden;">
                                        <img src="{{ $product->primary_image_url }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 280px;" title="{{ $product->name }}">{{ $product->name }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">SKU: {{ $product->sku }} | Unit: {{ $product->unit }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-muted">{{ $product->category->name ?? 'General' }}</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-success fw-bold">₹</span>
                                    <input type="number" step="0.01" name="prices[{{ $product->id }}][selling_price]" class="form-control fw-bold text-success" value="{{ $product->selling_price }}">
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted">₹</span>
                                    <input type="number" step="0.01" name="prices[{{ $product->id }}][mrp]" class="form-control" value="{{ $product->mrp }}">
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted">₹</span>
                                    <input type="number" step="0.01" name="prices[{{ $product->id }}][wholesale_price]" class="form-control" value="{{ $product->wholesale_price ?? $product->selling_price }}">
                                </div>
                            </td>
                            <td class="pe-3 text-end">
                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam fs-1 d-block mb-2 text-muted"></i>
                                <h6>No products found to update pricing</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @if($products->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $products->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmBatchUpdate(form) {
        const catSelect = document.getElementById('batchCategory');
        const catText = catSelect.options[catSelect.selectedIndex].text;
        const amount = form.elements['amount_value'].value;
        const action = form.elements['action_type'].value;

        return confirm(`Are you sure you want to apply this price update (${action} of ${amount}) to target: "${catText}"? This will modify product prices immediately.`);
    }
</script>
@endpush
@endsection
