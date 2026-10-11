@extends('layouts.admin')

@section('title', 'Edit Brand — ' . $brand->name)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="flex-grow-1">
        <div class="d-flex align-items-center gap-2">
            <h1 class="page-header-title mb-0">Edit Brand: {{ $brand->name }}</h1>
            @if($brand->isSuperBrand())
                <span class="badge bg-success-subtle text-success border border-success-subtle">Super Brand</span>
            @else
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Sub Brand</span>
            @endif
        </div>
        <p class="page-header-subtitle mb-0">Update brand hierarchy, details, trademark compliance, and dealer restrictions.</p>
    </div>
    <div>
        <a href="{{ route('brands.show', $brand->slug) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Storefront
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Please fix the following validation errors:</div>
        <ul class="mb-0 small ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- LEFT: Primary Details & Hierarchy -->
        <div class="col-lg-8">
            <!-- 1. HIERARCHY SELECTOR -->
            <div class="garudo-card p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-diagram-2-fill text-success me-2"></i>1. Brand Hierarchy & Custom Title</h5>
                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#createTitleModal">
                        <i class="bi bi-plus-circle-fill me-1"></i> + Create Brand Title
                    </button>
                </div>
                <p class="text-muted small mb-3">Choose whether this brand is a top-level parent umbrella (Super Brand) or a child product line (Sub Brand), and optionally assign a custom title.</p>

                <!-- Custom Brand Title Dropdown -->
                <div class="mb-3 p-3 bg-light rounded-3 border">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label class="form-label fw-bold text-dark small mb-0">Brand Title / Tier Classification</label>
                        <a href="{{ route('admin.brand-titles.index') }}" target="_blank" class="small text-decoration-none text-muted">
                            Manage Titles <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                    <select name="brand_title_id" id="brandTitleSelect" class="form-select" onchange="handleBrandTitleChange(this)">
                        <option value="">-- Standard (Auto according to Tier) --</option>
                        @foreach($brandTitles as $bt)
                            <option value="{{ $bt->id }}" 
                                    data-is-parent="{{ $bt->is_parent_tier ? '1' : '0' }}"
                                    {{ old('brand_title_id', $brand->brand_title_id) == $bt->id ? 'selected' : '' }}>
                                {{ $bt->name }} {{ $bt->is_parent_tier ? '(Parent Tier)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted mt-1 d-block">Select a title like <em>Super Brand</em>, <em>National Brand</em>, <em>Premium Brand</em>, or create a new one.</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="d-flex align-items-start gap-3 p-3 border rounded-3 cursor-pointer tier-radio-box" id="tierSuperBox" style="background: #F8FAFC;">
                            <input class="form-check-input mt-1" type="radio" name="brand_tier" id="tierSuper" value="super_brand" {{ old('brand_tier', $brand->brand_tier ?? ($brand->parent_id ? 'sub_brand' : 'super_brand')) === 'super_brand' ? 'checked' : '' }} onchange="toggleHierarchyUI()">
                            <div>
                                <span class="fw-bold d-block text-dark"><i class="bi bi-diagram-2-fill text-success me-1"></i> Super Brand (Parent)</span>
                                <span class="text-muted small">Master/umbrella entity (e.g., Tata Group, UltraTech, JSW)</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="d-flex align-items-start gap-3 p-3 border rounded-3 cursor-pointer tier-radio-box" id="tierSubBox" style="background: #F8FAFC;">
                            <input class="form-check-input mt-1" type="radio" name="brand_tier" id="tierSub" value="sub_brand" {{ old('brand_tier', $brand->brand_tier ?? ($brand->parent_id ? 'sub_brand' : 'super_brand')) === 'sub_brand' ? 'checked' : '' }} onchange="toggleHierarchyUI()">
                            <div>
                                <span class="fw-bold d-block text-dark"><i class="bi bi-bezier2 text-primary me-1"></i> Sub Brand (Child Line)</span>
                                <span class="text-muted small">Specific variant/product family (e.g., Tata Tiscon, UltraTech Super)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Parent Brand Selector (Shown only if Sub Brand) -->
                <div id="parentBrandContainer" class="p-3 bg-light rounded-3 border {{ old('brand_tier', $brand->brand_tier ?? ($brand->parent_id ? 'sub_brand' : 'super_brand')) === 'sub_brand' ? '' : 'd-none' }}">
                    <label class="form-label fw-bold text-dark small mb-1">
                        Select Parent Super Brand <span class="text-danger">*</span>
                    </label>
                    <select name="parent_id" id="parentBrandSelect" class="form-select">
                        <option value="">-- Choose Super Brand --</option>
                        @foreach($superBrands as $sb)
                            <option value="{{ $sb->id }}" {{ old('parent_id', $brand->parent_id) == $sb->id ? 'selected' : '' }}>
                                {{ $sb->name }} ({{ $sb->country_of_origin ?? 'India' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($brand->isSuperBrand() && $brand->subBrands->count() > 0)
                    <div class="mt-3 p-3 bg-white border rounded-3">
                        <span class="small fw-bold text-dark d-block mb-2"><i class="bi bi-bezier2 text-primary me-1"></i> Attached Sub Brands ({{ $brand->subBrands->count() }}):</span>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($brand->subBrands as $child)
                                <a href="{{ route('admin.brands.edit', $child->id) }}" class="badge rounded-pill bg-light text-dark border text-decoration-none px-3 py-2">
                                    {{ $child->name }} <i class="bi bi-pencil-square ms-1 text-primary"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- 2. BASIC BRAND INFORMATION -->
            <div class="garudo-card p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>2. Brand Identity & Details</h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Slug (URL key) <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $brand->slug) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Brand Type <span class="text-danger">*</span></label>
                        <select name="brand_type" class="form-select" required>
                            <option value="oem_manufacturer" {{ old('brand_type', $brand->brand_type) == 'oem_manufacturer' ? 'selected' : '' }}>OEM Manufacturer</option>
                            <option value="trademark_owner" {{ old('brand_type', $brand->brand_type) == 'trademark_owner' ? 'selected' : '' }}>Trademark Owner</option>
                            <option value="authorized_importer" {{ old('brand_type', $brand->brand_type) == 'authorized_importer' ? 'selected' : '' }}>Authorized Importer</option>
                            <option value="private_label" {{ old('brand_type', $brand->brand_type) == 'private_label' ? 'selected' : '' }}>Private Label</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Country of Origin</label>
                        <input type="text" name="country_of_origin" class="form-control" value="{{ old('country_of_origin', $brand->country_of_origin) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small">Brand Description</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description', $brand->description) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Official Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $brand->website) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Support Email</label>
                        <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $brand->support_email) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Support Phone</label>
                        <input type="text" name="support_phone" class="form-control" value="{{ old('support_phone', $brand->support_phone) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Year Established</label>
                        <input type="number" name="year_established" class="form-control" min="1800" max="{{ date('Y') }}" value="{{ old('year_established', $brand->year_established) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $brand->sort_order) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold small">Warranty Policy & Guarantee Terms</label>
                        <textarea name="warranty_policy" rows="2" class="form-control">{{ old('warranty_policy', $brand->warranty_policy) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 3. TRADEMARK & LEGAL COMPLIANCE -->
            <div class="garudo-card p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-warning me-2"></i>3. Trademark & Legal Information</h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Trademark Number</label>
                        <input type="text" name="trademark_number" class="form-control" value="{{ old('trademark_number', $brand->trademark_number) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Trademark Class</label>
                        <input type="text" name="trademark_class" class="form-control" value="{{ old('trademark_class', $brand->trademark_class) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small">Trademark Status</label>
                        <select name="trademark_status" class="form-select">
                            <option value="registered" {{ old('trademark_status', $brand->trademark_status) == 'registered' ? 'selected' : '' }}>Registered (Certified)</option>
                            <option value="applied_pending" {{ old('trademark_status', $brand->trademark_status) == 'applied_pending' ? 'selected' : '' }}>Applied / Pending</option>
                            <option value="licensed" {{ old('trademark_status', $brand->trademark_status) == 'licensed' ? 'selected' : '' }}>Licensed / Franchise</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Upload New Trademark Certificate</label>
                        <input type="file" name="trademark_doc" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        @if($brand->trademark_doc)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $brand->trademark_doc) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> View Current Document
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Settings, Protection, Media -->
        <div class="col-lg-4">
            <!-- BRAND PROTECTION SETTINGS -->
            <div class="garudo-card p-4 mb-4 border-start border-4 border-warning">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-lock-fill text-warning me-2"></i>Brand Protection Controls</h6>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_protected" value="1" id="isProtectedCheck" {{ old('is_protected', $brand->is_protected) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small text-dark" for="isProtectedCheck">
                        Enable Trademark Protection
                    </label>
                    <div class="text-muted small mt-1">
                        Restricts unauthorized vendors from adding products under this brand without verified dealership certification.
                    </div>
                </div>

                <hr class="my-3">

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeaturedCheck" {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small text-dark" for="isFeaturedCheck">
                        Featured Brand (Homepage)
                    </label>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold small">Brand Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $brand->status) == 'active' ? 'selected' : '' }}>Active (Live on Marketplace)</option>
                        <option value="inactive" {{ old('status', $brand->status) == 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                    </select>
                </div>
            </div>

            <!-- MEDIA ASSETS -->
            <div class="garudo-card p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-image text-primary me-2"></i>Brand Media</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Brand Logo</label>
                    @if($brand->logo)
                        <div class="mb-2 text-center p-2 border rounded-3 bg-white">
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="object-fit-contain" style="max-height: 80px;">
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control" accept="image/*">
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold small">Brand Header Banner</label>
                    @if($brand->banner)
                        <div class="mb-2 text-center p-1 border rounded-3 bg-white">
                            <img src="{{ asset('storage/' . $brand->banner) }}" alt="{{ $brand->name }}" class="w-100 object-fit-cover rounded-2" style="max-height: 80px;">
                        </div>
                    @endif
                    <input type="file" name="banner" class="form-control" accept="image/*">
                </div>
            </div>

            <!-- SUBMIT BUTTONS -->
            <div class="garudo-card p-3">
                <button type="submit" class="btn btn-garudo-primary w-100 fw-bold py-2 mb-2">
                    <i class="bi bi-check-circle-fill me-1"></i> Update Brand
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary w-100 btn-sm">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

<!-- MODAL: Quick Create Brand Title -->
<div class="modal fade" id="createTitleModal" tabindex="-1" aria-labelledby="createTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="createTitleModalLabel">
                    <i class="bi bi-tags-fill text-primary me-2"></i>Create New Brand Title / Tier
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickTitleForm" onsubmit="submitQuickTitle(event)">
                <div class="modal-body">
                    <div id="titleAlertBox" class="alert py-2 px-3 small border-0 mb-3 d-none"></div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Title / Tier Name <span class="text-danger">*</span></label>
                        <input type="text" id="newTitleName" class="form-control" placeholder="e.g. Super Brand / National Brand / Global Icon" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Badge Color Style <span class="text-danger">*</span></label>
                        <select id="newTitleColor" class="form-select">
                            <option value="success" selected>Emerald Green (Super Brand style)</option>
                            <option value="primary">Navy Blue (Sub Brand style)</option>
                            <option value="purple">Royal Purple (National Brand style)</option>
                            <option value="gold">Amber Gold (Premium Brand style)</option>
                            <option value="info">Teal / Sky Blue (OEM style)</option>
                            <option value="warning">Warm Orange</option>
                            <option value="dark">Dark Slate</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="newTitleIsParent" value="1" checked>
                        <label class="form-check-label fw-semibold small text-dark" for="newTitleIsParent">
                            Acts as Parent Umbrella (Super Brand)
                        </label>
                        <div class="text-muted small">Brands with this title can have other Sub Brands attached to them.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="saveTitleBtn" class="btn btn-garudo-primary btn-sm px-3 fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Title & Select
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleHierarchyUI() {
    const isSub = document.getElementById('tierSub').checked;
    const parentContainer = document.getElementById('parentBrandContainer');
    const parentSelect = document.getElementById('parentBrandSelect');

    if (isSub) {
        parentContainer.classList.remove('d-none');
        parentSelect.setAttribute('required', 'required');
    } else {
        parentContainer.classList.add('d-none');
        parentSelect.removeAttribute('required');
    }
}

function handleBrandTitleChange(select) {
    const option = select.options[select.selectedIndex];
    if (!option || !select.value) return;

    const isParent = option.getAttribute('data-is-parent') === '1';
    if (isParent) {
        document.getElementById('tierSuper').checked = true;
    } else {
        document.getElementById('tierSub').checked = true;
    }
    toggleHierarchyUI();
}

function submitQuickTitle(e) {
    e.preventDefault();
    const name = document.getElementById('newTitleName').value.trim();
    const badge_color = document.getElementById('newTitleColor').value;
    const is_parent_tier = document.getElementById('newTitleIsParent').checked ? 1 : 0;
    const btn = document.getElementById('saveTitleBtn');
    const alertBox = document.getElementById('titleAlertBox');

    if (!name) return;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

    fetch('{{ route("admin.brand-titles.quick-store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name, badge_color, is_parent_tier })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Title & Select';

        if (data.success && data.title) {
            // Append to dropdown and select it
            const select = document.getElementById('brandTitleSelect');
            const newOpt = document.createElement('option');
            newOpt.value = data.title.id;
            newOpt.setAttribute('data-is-parent', data.title.is_parent_tier ? '1' : '0');
            newOpt.textContent = data.title.name + (data.title.is_parent_tier ? ' (Parent Tier)' : '');
            newOpt.selected = true;
            select.appendChild(newOpt);

            handleBrandTitleChange(select);

            alertBox.className = 'alert alert-success py-2 px-3 small border-0 mb-3';
            alertBox.innerHTML = '<i class="bi bi-check-circle me-1"></i> ' + data.message;
            alertBox.classList.remove('d-none');

            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTitleModal'));
                if (modal) modal.hide();
                document.getElementById('quickTitleForm').reset();
                alertBox.classList.add('d-none');
            }, 1200);
        } else {
            alertBox.className = 'alert alert-danger py-2 px-3 small border-0 mb-3';
            alertBox.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> ' + (data.message || 'Error creating title');
            alertBox.classList.remove('d-none');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Save Title & Select';
        alertBox.className = 'alert alert-danger py-2 px-3 small border-0 mb-3';
        alertBox.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> ' + err.message;
        alertBox.classList.remove('d-none');
    });
}

document.addEventListener('DOMContentLoaded', toggleHierarchyUI);
</script>
@endsection
