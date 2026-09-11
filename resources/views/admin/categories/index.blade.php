@extends('layouts.admin')

@section('title', 'Categories & Taxonomy')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Categories & Taxonomy</h1>
        <p class="page-header-subtitle mb-0">Organize industrial product catalog hierarchy, subcategories, custom policies and storefront visibility.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.catalog-requests.index', ['type' => 'category']) }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-clock-history me-1"></i> Vendor Requests
        </a>
        <button class="btn btn-garudo-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-1"></i> Add New Category
        </button>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-muted small fw-bold text-uppercase">Main Industry Sectors</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total_parents'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-primary small fw-bold text-uppercase">Subcategories</div>
            <div class="fs-4 fw-bold text-primary mt-1">{{ $stats['total_subcategories'] }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="garudo-card p-3">
            <div class="text-warning small fw-bold text-uppercase">Featured on Home</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['featured_categories'] }}</div>
        </div>
    </div>
</div>

<!-- ---------------- CATEGORIES HIERARCHY TREE ---------------- -->
<div class="row g-4">
    @foreach($categories as $parent)
        <div class="col-lg-6">
            <div class="garudo-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fs-4" style="width: 44px; height: 44px;">
                            <i class="bi {{ $parent->icon ?? 'bi-tags' }}"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">{{ $parent->name }}</h5>
                            <div class="text-muted small">Slug: <code>{{ $parent->slug }}</code> • {{ $parent->products_count }} Total Products</div>
                            @if($parent->policy)
                                <div class="text-success small mt-1 d-flex align-items-center gap-1">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Category Policy Configured</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($parent->is_featured)
                            <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Featured</span>
                        @endif

                        <button type="button" class="btn btn-sm btn-outline-secondary border-0 p-1" onclick="openEditCategoryModal({{ json_encode($parent) }})" title="Edit Category & Policy">
                            <i class="bi bi-pencil fs-6"></i>
                        </button>

                        <form action="{{ route('admin.categories.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Delete this parent category and all its associations?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Delete">
                                <i class="bi bi-trash fs-6"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Subcategories List -->
                <div class="subcategories-wrap">
                    <div class="fw-semibold text-muted small text-uppercase mb-2">Subcategories ({{ $parent->children->count() }})</div>
                    @if($parent->children->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($parent->children as $sub)
                                <div class="badge bg-light text-dark border p-2 d-inline-flex align-items-center gap-2">
                                    <span class="cursor-pointer" onclick="openEditCategoryModal({{ json_encode($sub) }})" title="Click to edit subcategory policy">{{ $sub->name }}</span>
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.65rem;">{{ $sub->products_count }}</span>
                                    @if($sub->policy)
                                        <i class="bi bi-shield-check text-success" title="Subcategory Custom Policy Set"></i>
                                    @endif
                                    <button type="button" class="btn btn-link text-primary p-0 border-0 ms-1" style="font-size: 0.75rem;" onclick="openEditCategoryModal({{ json_encode($sub) }})" title="Edit Policy">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $sub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete subcategory {{ $sub->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 border-0 ms-1" style="font-size: 0.75rem;"><i class="bi bi-x"></i></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted small">No subcategories created yet.</div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal: Add Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Add Category / Subcategory</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Parent Category (Optional for Subcategory)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">None (Create as Top-Level Category)</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Electrical Transformers, TMT Bars, Ceramic Tiles" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Bootstrap Icon Name</label>
                            <input type="text" name="icon" class="form-control" placeholder="e.g. bi-lightning, bi-building, bi-droplet" value="bi-tags">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-shield-check text-success me-1"></i> Category / Subcategory Specific Policy & Return Terms
                        </label>
                        <textarea name="policy" class="form-control" rows="4" placeholder="Define category-specific policy: e.g. Tiles breakage reported within 48 hours with photographic proof; Cement non-returnable once packaging seal broken. Products in this category automatically inherit this policy."></textarea>
                        <small class="text-muted" style="font-size: 0.72rem;">Products listed under this category/subcategory will automatically inherit this policy unless the vendor overrides it for an individual product.</small>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeaturedCheck">
                        <label class="form-check-label fw-semibold" for="isFeaturedCheck">
                            Feature on Storefront Homepage
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-garudo-primary fw-bold">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Category & Policy -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Edit Category & Policy</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_cat_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Bootstrap Icon Name</label>
                            <input type="text" name="icon" id="edit_cat_icon" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" id="edit_cat_status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-shield-check text-success me-1"></i> Specific Policy & Return / Cancellation Terms
                        </label>
                        <textarea name="policy" id="edit_cat_policy" class="form-control" rows="5" placeholder="Define category-specific policy terms. Products under this category automatically display these terms."></textarea>
                        <small class="text-muted" style="font-size: 0.72rem;">Products in this category/subcategory automatically inherit this policy unless overridden with a product-specific policy.</small>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="edit_cat_is_featured">
                        <label class="form-check-label fw-semibold" for="edit_cat_is_featured">
                            Feature on Storefront Homepage
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-garudo-primary fw-bold">Update Category & Policy</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openEditCategoryModal(cat) {
    document.getElementById('editCategoryForm').action = `/admin/categories/${cat.id}`;
    document.getElementById('edit_cat_name').value = cat.name || '';
    document.getElementById('edit_cat_icon').value = cat.icon || 'bi-tags';
    document.getElementById('edit_cat_status').value = cat.status || 'active';
    document.getElementById('edit_cat_policy').value = cat.policy || '';
    document.getElementById('edit_cat_is_featured').checked = (cat.is_featured == 1);

    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}
</script>
@endpush
@endsection
