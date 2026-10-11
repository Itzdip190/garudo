@extends('layouts.admin')

@section('title', 'Categories & Taxonomy')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Categories & Taxonomy</h1>
        <p class="page-header-subtitle mb-0">Organize industrial product catalog hierarchy, storefront popular categories, custom policies and visibility.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <form action="{{ route('admin.categories.sync-hub') }}" method="POST" class="d-inline" onsubmit="return confirm('Sync all 16 Visual Category & Subcategory Hub cards to the database? Existing categories will be preserved.');">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Sync 16 Visual Hub Categories
            </button>
        </form>
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
            <div class="text-warning small fw-bold text-uppercase">Featured on Home (Top Products Showcase)</div>
            <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['featured_categories'] }}</div>
        </div>
    </div>
</div>

<!-- ---------------- REAL-TIME STOREFRONT LIVE VIEW (16 VISUAL CATEGORY CARDS) ---------------- -->
<div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
    <div class="card-header bg-white border-bottom p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success px-2.5 py-1 fw-bold"><i class="bi bi-broadcast me-1"></i> STOREFRONT LIVE PREVIEW</span>
                <span class="badge bg-primary text-white fw-bold">16 Visual Hub Cards</span>
            </div>
            <h5 class="fw-bold mt-2 mb-0 text-dark">Real-Time Storefront Visual Category & Subcategory Hub</h5>
            <p class="text-muted small mb-0">Changes saved to categories, subcategories, or thumbnail images instantly update this preview and the buyer storefront.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-dark btn-sm fw-bold rounded-pill px-3 shadow-none">
                <i class="bi bi-box-arrow-up-right me-1"></i> View Live Storefront
            </a>
            <button class="btn btn-outline-secondary btn-sm rounded-circle p-1" type="button" data-bs-toggle="collapse" data-bs-target="#liveHubCollapse" aria-expanded="true" title="Toggle Live Preview">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
    </div>
    <div class="collapse show" id="liveHubCollapse">
        <div class="card-body p-3 bg-light bg-opacity-50">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-3">
                @foreach($visualCategoryHub as $cat)
                    <div class="col">
                        <div class="card h-100 border bg-white rounded-3 shadow-sm p-3 position-relative d-flex flex-column justify-content-between" style="border-color: #e2e8f0 !important; min-height: 320px;">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <h6 class="fw-bold text-dark text-uppercase mb-0 text-truncate" style="font-size: 0.8rem; letter-spacing: 0.3px; max-width: 140px;" title="{{ $cat['title'] }}">
                                        {{ $cat['title'] }}
                                    </h6>
                                    <span class="badge {{ $cat['is_db'] ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary' }}" style="font-size: 0.62rem;">
                                        {{ $cat['is_db'] ? 'DB Connected' : 'Default Preset' }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    @if($cat['id'])
                                        <button type="button" class="btn btn-sm btn-light border p-1 rounded-circle" style="width: 24px; height: 24px; font-size: 0.65rem;" onclick="openAddSubcategoryModal({{ $cat['id'] }}, '{{ addslashes($cat['title']) }}')" title="Add Subcategory under {{ $cat['title'] }}">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    @endif
                                    <a href="{{ $cat['view_all_url'] }}" target="_blank" class="text-primary fw-semibold text-decoration-none" style="font-size: 0.72rem;">
                                        View &rarr;
                                    </a>
                                </div>
                            </div>

                            <!-- Subcategories Grid matching Storefront Layout -->
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px 4px; justify-items: center; align-items: start; width: 100%; margin-top: auto; margin-bottom: auto; padding-top: 4px;">
                                @php
                                    $totalSubs = count($cat['subcategories']);
                                @endphp
                                @foreach($cat['subcategories'] as $idx => $sub)
                                    @php
                                        $gridColStyle = '';
                                        if ($totalSubs === 6) {
                                            if ($idx === 4) $gridColStyle = 'grid-column: 2;';
                                            elseif ($idx === 5) $gridColStyle = 'grid-column: 3;';
                                        } elseif ($totalSubs === 5) {
                                            if ($idx === 4) $gridColStyle = 'grid-column: 2 / span 2;';
                                        }
                                    @endphp
                                    <div class="text-center d-flex flex-column align-items-center position-relative" style="{{ $gridColStyle }} width: 100%; max-width: 68px;" title="{{ $sub['name'] }}">
                                        <div class="d-flex align-items-center justify-content-center mb-1 rounded bg-light position-relative" style="width: 54px; height: 54px; overflow: hidden; border: 1.5px solid #e2e8f0; border-radius: 10px !important;">
                                            <img src="{{ $sub['image'] }}" 
                                                 alt="{{ $sub['name'] }}" 
                                                 loading="lazy" 
                                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80';"
                                                 style="width: 100%; height: 100%; object-fit: contain; padding: 3px;">
                                        </div>
                                        <span style="font-size: 0.65rem; line-height: 1.15; font-weight: 500; color: #334155; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 26px; word-break: break-word;">
                                            {{ $sub['name'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- ---------------- HOMEPAGE POPULAR CATEGORIES MANAGER ---------------- -->
<div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border-bottom border-secondary pb-3">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark px-2 py-1 fw-bold"><i class="bi bi-grid-fill me-1"></i> STOREFRONT HOMEPAGE SHOWCASE</span>
                    <span class="badge bg-light text-dark fw-bold">Top 6 Columns</span>
                </div>
                <h5 class="fw-bold mt-2 mb-1 text-white">Popular Categories with Top Products Manager</h5>
                <p class="text-secondary small mb-0">Control which 6 categories and their real products appear on the homepage section. Set the order (Rank 1 to 6) to arrange columns.</p>
            </div>
            <div>
                <button type="button" class="btn btn-warning btn-sm fw-bold rounded-pill px-3" onclick="saveAllCategoryOrders()">
                    <i class="bi bi-check-circle-fill me-1"></i> Save Display Order
                </button>
            </div>
        </div>

        <div class="row g-3">
            @php
                $featuredList = $categories->filter(fn($c) => $c->is_featured)->sortBy('sort_order');
            @endphp
            @forelse($featuredList as $index => $fCat)
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="bg-dark bg-opacity-75 border border-secondary rounded-3 p-3 text-center position-relative h-100 d-flex flex-column justify-content-between">
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">#{{ $index + 1 }}</span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2">
                            <form action="{{ route('admin.categories.toggle-featured', $fCat->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger p-0 border-0" title="Remove from Homepage" onclick="return confirm('Remove {{ $fCat->name }} from homepage top showcase?');">
                                    <i class="bi bi-x-circle-fill fs-6"></i>
                                </button>
                            </form>
                        </div>
                        <div class="mt-3 mb-2">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 36px; height: 36px;">
                                <i class="bi {{ $fCat->icon ?? 'bi-tags' }}"></i>
                            </div>
                            <div class="fw-bold text-white small text-truncate" title="{{ $fCat->name }}">{{ $fCat->name }}</div>
                            <div class="text-secondary" style="font-size: 0.7rem;">{{ $fCat->products_count }} Products</div>
                        </div>
                        <div class="mt-2 pt-2 border-top border-secondary">
                            <label class="form-label text-secondary mb-1" style="font-size: 0.68rem;">Display Priority Rank</label>
                            <input type="number" class="form-control form-control-sm text-center bg-dark text-white border-secondary home-order-input" data-cat-id="{{ $fCat->id }}" value="{{ $fCat->sort_order ?: ($index + 1) }}" min="1" max="99">
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-3 text-secondary">
                        <i class="bi bi-info-circle fs-4 d-block mb-1"></i>
                        No categories currently marked as featured. Click the star icon on any category card below to feature it in the Homepage 6-column showcase.
                    </div>
                </div>
            @endforelse
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
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="fw-bold text-dark mb-0">{{ $parent->name }}</h5>
                                @if($parent->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i>Homepage (Rank #{{ $parent->sort_order ?: 1 }})</span>
                                @endif
                            </div>
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
                        <!-- Quick 1-Click Toggle for Homepage Feature -->
                        <form action="{{ route('admin.categories.toggle-featured', $parent->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $parent->is_featured ? 'btn-warning' : 'btn-outline-secondary' }} border-0 p-1" title="{{ $parent->is_featured ? 'Remove from Homepage Popular Categories' : 'Show in Homepage Popular Categories' }}">
                                <i class="bi {{ $parent->is_featured ? 'bi-star-fill' : 'bi-star' }} fs-6"></i>
                            </button>
                        </form>

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
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold text-muted small text-uppercase">Subcategories ({{ $parent->children->count() }})</div>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill py-0 px-2" style="font-size: 0.72rem;" onclick="openAddSubcategoryModal({{ $parent->id }}, '{{ addslashes($parent->name) }}')">
                            <i class="bi bi-plus"></i> Add Subcategory
                        </button>
                    </div>
                    @if($parent->children->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($parent->children as $sub)
                                <div class="badge bg-light text-dark border p-2 d-inline-flex align-items-center gap-2">
                                    @if($sub->image)
                                        <img src="{{ str_starts_with($sub->image, 'http') || str_starts_with($sub->image, '/') ? $sub->image : asset('storage/'.$sub->image) }}" class="rounded bg-white border" style="width: 22px; height: 22px; object-fit: contain;">
                                    @endif
                                    <span class="cursor-pointer fw-medium" onclick="openEditCategoryModal({{ json_encode($sub) }})" title="Click to edit subcategory">{{ $sub->name }}</span>
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.65rem;">{{ $sub->products_count }}</span>
                                    @if($sub->policy)
                                        <i class="bi bi-shield-check text-success" title="Subcategory Custom Policy Set"></i>
                                    @endif
                                    <button type="button" class="btn btn-link text-primary p-0 border-0 ms-1" style="font-size: 0.75rem;" onclick="openEditCategoryModal({{ json_encode($sub) }})" title="Edit Subcategory">
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
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="addCategoryModalTitle">Add Category / Subcategory</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-diagram-3 me-1"></i> Parent Category (Hierarchy Level) <span class="text-danger">*</span>
                        </label>
                        <select name="parent_id" id="add_cat_parent_id" class="form-select">
                            <option value="">None (Top-Level Main Category / Sector)</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}">↳ Subcategory under: {{ $parent->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text small">
                            Choose <strong>None</strong> for a main sector card (e.g. "Breeze Blocks & Jali"). Select a parent if this is a subcategory (e.g. "Terracotta Jali").
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category / Subcategory Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="add_cat_name" class="form-control" placeholder="e.g. Terracotta Jali, Cement, Wires & Cables" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Bootstrap Icon</label>
                            <input type="text" name="icon" class="form-control" placeholder="e.g. bi-building" value="bi-tags">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sort Order Rank</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <!-- Image Upload & URL Options -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-image me-1"></i> Upload Thumbnail Image
                            </label>
                            <input type="file" name="image" id="add_cat_image_file" class="form-control" accept="image/*" onchange="previewUploadImage(this, 'add_preview_wrap', 'add_preview_tag')">
                            <div class="form-text small">Upload .jpg, .png, or .webp (Max 3MB). Saved in public/uploads/categories.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-link-45deg me-1"></i> Or Direct Image URL
                            </label>
                            <input type="text" name="image_url" id="add_cat_image_url" class="form-control" placeholder="https://images.unsplash.com/... or /uploads/..." oninput="previewUrlImage(this.value, 'add_preview_wrap', 'add_preview_tag')">
                            <div class="form-text small">Web URL or static asset path.</div>
                        </div>
                    </div>

                    <!-- Live Image Preview for Add Modal -->
                    <div id="add_preview_wrap" class="d-none mb-3 p-2 bg-light border rounded d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <img id="add_preview_tag" src="" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <span class="small text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i> Thumbnail image loaded for storefront card</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-shield-check text-success me-1"></i> Category / Subcategory Policy Terms
                        </label>
                        <textarea name="policy" class="form-control" rows="3" placeholder="Define category-specific policy: e.g. Tiles breakage reported within 48 hours; Cement non-returnable once packaging seal broken."></textarea>
                        <small class="text-muted" style="font-size: 0.72rem;">Products listed under this category/subcategory will automatically inherit this policy.</small>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeaturedCheck">
                        <label class="form-check-label fw-semibold" for="isFeaturedCheck">
                            Feature on Storefront Homepage (Popular Categories with Top Products)
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
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Edit Category / Subcategory</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-diagram-3 me-1"></i> Parent Category (Hierarchy Level)
                        </label>
                        <select name="parent_id" id="edit_cat_parent_id" class="form-select">
                            <option value="">None (Top-Level Main Category / Sector)</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}">↳ Subcategory under: {{ $parent->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text small">Change hierarchy: select None to make top-level parent, or select a parent category.</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_cat_name" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Bootstrap Icon</label>
                            <input type="text" name="icon" id="edit_cat_icon" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Display Order Rank</label>
                            <input type="number" name="sort_order" id="edit_cat_sort_order" class="form-control" min="0">
                        </div>
                    </div>

                    <!-- Image Upload & URL Options for Edit -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-image me-1"></i> Replace Thumbnail Image
                            </label>
                            <input type="file" name="image" id="edit_cat_image_file" class="form-control" accept="image/*" onchange="previewUploadImage(this, 'edit_preview_wrap', 'edit_preview_tag')">
                            <div class="form-text small">Upload .jpg, .png, or .webp (Max 3MB).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-link-45deg me-1"></i> Or Image Web URL / Path
                            </label>
                            <input type="text" name="image_url" id="edit_cat_image_url" class="form-control" placeholder="https://images.unsplash.com/... or /uploads/..." oninput="previewUrlImage(this.value, 'edit_preview_wrap', 'edit_preview_tag')">
                            <div class="form-text small">Direct image URL or asset link.</div>
                        </div>
                    </div>

                    <!-- Live Image Preview for Edit Modal -->
                    <div id="edit_preview_wrap" class="mb-3 p-2 bg-light border rounded d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <img id="edit_preview_tag" src="https://placehold.co/100x100?text=No+Img" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <div>
                            <div class="small fw-bold text-dark">Current / New Thumbnail Preview</div>
                            <div class="small text-muted text-break" id="edit_preview_text">No image set</div>
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
                        <textarea name="policy" id="edit_cat_policy" class="form-control" rows="4" placeholder="Define category-specific policy terms. Products under this category automatically display these terms."></textarea>
                        <small class="text-muted" style="font-size: 0.72rem;">Products in this category/subcategory automatically inherit this policy.</small>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="edit_cat_is_featured">
                        <label class="form-check-label fw-semibold" for="edit_cat_is_featured">
                            Feature on Storefront Homepage (Popular Categories with Top Products)
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
function previewUploadImage(input, wrapId, tagId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(tagId).src = e.target.result;
            const wrap = document.getElementById(wrapId);
            if (wrap) wrap.classList.remove('d-none');
            const txt = document.getElementById('edit_preview_text');
            if (txt) txt.textContent = input.files[0].name + ' (New file selected)';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewUrlImage(url, wrapId, tagId) {
    if (url && url.trim() !== '') {
        document.getElementById(tagId).src = url.trim();
        const wrap = document.getElementById(wrapId);
        if (wrap) wrap.classList.remove('d-none');
        const txt = document.getElementById('edit_preview_text');
        if (txt) txt.textContent = url.trim();
    }
}

function openAddSubcategoryModal(parentId, parentName) {
    const parentSelect = document.getElementById('add_cat_parent_id');
    if (parentSelect) {
        parentSelect.value = parentId;
    }
    const modalTitle = document.getElementById('addCategoryModalTitle');
    if (modalTitle) {
        modalTitle.textContent = `Add Subcategory under: ${parentName}`;
    }
    // Clear image inputs
    const fileInput = document.getElementById('add_cat_image_file');
    if (fileInput) fileInput.value = '';
    const urlInput = document.getElementById('add_cat_image_url');
    if (urlInput) urlInput.value = '';
    const wrap = document.getElementById('add_preview_wrap');
    if (wrap) wrap.classList.add('d-none');

    new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
}

function openEditCategoryModal(cat) {
    document.getElementById('editCategoryForm').action = `/admin/categories/${cat.id}`;
    document.getElementById('edit_cat_name').value = cat.name || '';
    document.getElementById('edit_cat_parent_id').value = cat.parent_id || '';
    document.getElementById('edit_cat_icon').value = cat.icon || 'bi-tags';
    document.getElementById('edit_cat_sort_order').value = cat.sort_order || 0;
    document.getElementById('edit_cat_status').value = cat.status || 'active';
    document.getElementById('edit_cat_policy').value = cat.policy || '';
    document.getElementById('edit_cat_is_featured').checked = (cat.is_featured == 1);

    // Reset file input
    const fileInput = document.getElementById('edit_cat_image_file');
    if (fileInput) fileInput.value = '';

    // Set preview image
    const imgTag = document.getElementById('edit_preview_tag');
    const previewTxt = document.getElementById('edit_preview_text');
    const urlInput = document.getElementById('edit_cat_image_url');

    if (cat.image) {
        const src = (cat.image.startsWith('http') || cat.image.startsWith('/')) ? cat.image : ('/storage/' + cat.image);
        imgTag.src = src;
        previewTxt.textContent = cat.image;
        urlInput.value = cat.image.startsWith('http') ? cat.image : '';
    } else {
        imgTag.src = 'https://placehold.co/100x100?text=No+Img';
        previewTxt.textContent = 'No image set yet';
        urlInput.value = '';
    }

    new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
}

function saveAllCategoryOrders() {
    const inputs = document.querySelectorAll('.home-order-input');
    const orders = [];
    inputs.forEach(input => {
        orders.push({
            id: input.dataset.catId,
            sort_order: parseInt(input.value) || 0
        });
    });

    fetch('{{ route("admin.categories.update-homepage-order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ orders: orders })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Homepage category display order updated successfully!');
            location.reload();
        } else {
            alert('Failed to update category order.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred while saving display order.');
    });
}
</script>
@endpush
@endsection
