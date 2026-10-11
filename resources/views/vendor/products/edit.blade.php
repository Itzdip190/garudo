@extends('layouts.vendor')

@section('title', 'Edit Product - ' . $product->name)

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Edit Product</h4>
            <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $product->status === 'active' ? 'success' : 'secondary' }} rounded-pill px-3 py-1 small fw-semibold">
                {{ ucfirst($product->status) }}
            </span>
        </div>
        <p class="text-muted mb-0 small">SKU: <strong class="text-dark">{{ $product->sku }}</strong> | Category: <span class="text-dark">{{ $product->category->name ?? 'N/A' }}</span></p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vendor.products.index') }}" class="btn btn-light border btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-arrow-left me-1"></i> Back to Catalog
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show p-3 small mb-4" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <div>
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <!-- 1. Identity -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle text-success me-2"></i>Product Identity</h6>
                    <span class="badge bg-light text-muted border small">Basic Info</span>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-dark">Product Title / Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg fs-6" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fw-semibold small text-dark mb-0">Category <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-link text-success p-0 small text-decoration-none fw-semibold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="bi bi-send-plus me-1"></i>+ Request Category
                            </button>
                        </div>
                        <select name="category_id" id="categorySelect" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} {{ $cat->parent ? '(' . $cat->parent->name . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fw-semibold small text-dark mb-0">Brand / Manufacturer</label>
                            <button type="button" class="btn btn-link text-success p-0 small text-decoration-none fw-semibold" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                                <i class="bi bi-send-plus me-1"></i>+ Request Brand
                            </button>
                        </div>
                        <select name="brand_id" id="brandSelect" class="form-select" onchange="checkBrandProtection(this)">
                            <option value="" data-protected="0" data-allowed="1">-- Select Brand (Optional / Generic) --</option>
                            @foreach($superBrands as $sb)
                                @if($sb->activeSubBrands->count() > 0)
                                    <optgroup label="👑 {{ $sb->name }} (Super Brand)">
                                        <option value="{{ $sb->id }}" 
                                                data-protected="{{ $sb->is_protected ? '1' : '0' }}" 
                                                data-allowed="{{ $sb->canVendorSell($vendor) ? '1' : '0' }}"
                                                {{ old('brand_id', $product->brand_id) == $sb->id ? 'selected' : '' }}>
                                            {{ $sb->name }} [All {{ $sb->name }} Line] {{ $sb->is_protected ? '🛡️' : '' }}
                                        </option>
                                        @foreach($sb->activeSubBrands as $sub)
                                            <option value="{{ $sub->id }}" 
                                                    data-protected="{{ $sub->is_protected ? '1' : '0' }}" 
                                                    data-allowed="{{ $sub->canVendorSell($vendor) ? '1' : '0' }}"
                                                    {{ old('brand_id', $product->brand_id) == $sub->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $sub->name }} (Sub Brand) {{ $sub->is_protected ? '🛡️' : '' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $sb->id }}" 
                                            data-protected="{{ $sb->is_protected ? '1' : '0' }}" 
                                            data-allowed="{{ $sb->canVendorSell($vendor) ? '1' : '0' }}"
                                            {{ old('brand_id', $product->brand_id) == $sb->id ? 'selected' : '' }}>
                                        {{ $sb->name }} {{ $sb->is_protected ? '🛡️' : '' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        <!-- Brand Protection Alerts -->
                        <div id="brandProtectionNotice" class="alert alert-warning py-2 px-3 small border-0 mt-2 d-none">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <i class="bi bi-shield-lock-fill text-warning me-1"></i>
                                    <strong>Trademark Protected:</strong> You need verified dealer authorization to list this brand.
                                </div>
                                <a href="{{ route('vendor.dealer-authorizations.apply') }}" target="_blank" class="btn btn-warning btn-sm py-0 px-2 fw-bold text-dark text-nowrap">
                                    Apply Now
                                </a>
                            </div>
                        </div>
                        <div id="brandVerifiedNotice" class="alert alert-success py-2 px-3 small border-0 mt-2 d-none">
                            <i class="bi bi-patch-check-fill text-success me-1"></i>
                            <strong>Verified Selling Rights:</strong> You are authorized to list products under this brand.
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-dark">SKU Code</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-dark">HSN Code</label>
                        <input type="text" name="hsn_code" class="form-control" value="{{ old('hsn_code', $product->hsn_code) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-dark">Barcode / EAN</label>
                        <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}">
                    </div>
                </div>
            </div>

            <!-- 2. Existing & New Images -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-images text-success me-2"></i>Product Visuals & Images</h6>
                    <small class="text-muted">Manage current photos or upload new ones</small>
                </div>

                @if($product->images->count() > 0)
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Current Saved Photos:</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach($product->images as $img)
                                <div class="position-relative border rounded p-2 bg-light text-center" style="width: 110px;">
                                    <img src="{{ $img->url }}" class="rounded mb-1" style="width: 90px; height: 90px; object-fit: cover;">
                                    @if($img->is_primary)
                                        <span class="badge bg-success w-100" style="font-size: 0.65rem;">Primary</span>
                                    @else
                                        <div class="form-check form-check-inline text-start m-0">
                                            <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $img->id }}" id="del_{{ $img->id }}">
                                            <label class="form-check-label text-danger" for="del_{{ $img->id }}" style="font-size: 0.7rem;">Delete</label>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-dark">Replace / Upload Main Photo</label>
                        <input type="file" name="main_image" class="form-control form-control-sm" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-dark">Add More Gallery Photos</label>
                        <input type="file" name="gallery_images[]" multiple class="form-control form-control-sm" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- 3. Technical Specifications -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-sliders text-success me-2"></i>Technical Specifications</h6>
                        <small class="text-muted">Technical material attributes and test standards</small>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addEditSpecRow()">
                        <i class="bi bi-plus-circle me-1"></i> Add Spec Row
                    </button>
                </div>

                <!-- Industry Presets Quick Load (Curated by Staff / Admins) -->
                <div class="bg-light p-3 rounded-3 mb-3 border">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="fw-semibold small text-dark"><i class="bi bi-magic text-warning me-1"></i>Auto-Fill Industry Specification Template:</span>
                        <span class="badge bg-success bg-opacity-10 text-success small" style="font-size: 0.72rem;">
                            <i class="bi bi-shield-check me-1"></i>Curated by Garudo Staff / Admins
                        </span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadEditSpecPreset('cement')">
                            <i class="bi bi-box-seam me-1"></i> Cement (OPC / PPC)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadEditSpecPreset('steel')">
                            <i class="bi bi-shield me-1"></i> Steel & TMT Bars
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadEditSpecPreset('blocks')">
                            <i class="bi bi-bricks me-1"></i> AAC Blocks / Bricks
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadEditSpecPreset('paints')">
                            <i class="bi bi-paint-bucket me-1"></i> Paints & Chemicals
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadEditSpecPreset('pipes')">
                            <i class="bi bi-water me-1"></i> Pipes & Plumbing
                        </button>
                    </div>
                    <div class="text-muted mt-2" style="font-size: 0.73rem;">
                        <i class="bi bi-info-circle me-1 text-primary"></i> Vendors select from staff-approved templates. To add a custom template, submit a request to Staff.
                    </div>
                </div>

                <div id="specsContainer">
                    @forelse($product->specifications as $idx => $spec)
                        <div class="row g-2 mb-2 spec-row align-items-center">
                            <div class="col-md-3">
                                <input type="text" name="specs[{{ $idx }}][group]" class="form-control form-control-sm" value="{{ $spec->spec_group }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="specs[{{ $idx }}][key]" class="form-control form-control-sm" value="{{ $spec->spec_key }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="specs[{{ $idx }}][value]" class="form-control form-control-sm" value="{{ $spec->spec_value }}">
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 border-0" onclick="this.closest('.spec-row').remove()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="row g-2 mb-2 spec-row align-items-center">
                            <div class="col-md-3">
                                <input type="text" name="specs[0][group]" class="form-control form-control-sm" placeholder="Group" value="General">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="specs[0][key]" class="form-control form-control-sm" placeholder="Specification Key">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="specs[0][value]" class="form-control form-control-sm" placeholder="Value">
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 border-0" onclick="this.closest('.spec-row').remove()">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 4. Description -->
            <div class="card-custom p-4 shadow-sm">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-3"><i class="bi bi-file-text text-success me-2"></i>Product Content & Description</h6>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Short Summary Overview</label>
                    <input type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Full Description</label>
                    <textarea name="description" rows="6" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label fw-semibold small text-dark">Key Highlights / Features</label>
                    <textarea name="features" rows="3" class="form-control">{{ old('features', $product->features) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Side Configuration Column -->
        <div class="col-lg-4">
            <!-- 1. B2B Pricing Card -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="bi bi-currency-rupee text-success me-2"></i>B2B Pricing</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Selling Price (₹) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">₹</span>
                        <input type="number" step="0.01" name="selling_price" class="form-control fw-bold fs-5 text-success" value="{{ old('selling_price', $product->selling_price) }}" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">MRP (₹)</label>
                        <input type="number" step="0.01" name="mrp" class="form-control form-control-sm" value="{{ old('mrp', $product->mrp) }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">Wholesale Price (₹)</label>
                        <input type="number" step="0.01" name="wholesale_price" class="form-control form-control-sm" value="{{ old('wholesale_price', $product->wholesale_price) }}">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">GST Rate (%)</label>
                        <select name="gst_rate" class="form-select form-select-sm">
                            <option value="0" {{ $product->gst_rate == 0 ? 'selected' : '' }}>0% (Nil)</option>
                            <option value="5" {{ $product->gst_rate == 5 ? 'selected' : '' }}>5% GST</option>
                            <option value="12" {{ $product->gst_rate == 12 ? 'selected' : '' }}>12% GST</option>
                            <option value="18" {{ $product->gst_rate == 18 ? 'selected' : '' }}>18% GST</option>
                            <option value="28" {{ $product->gst_rate == 28 ? 'selected' : '' }}>28% GST</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">Unit</label>
                        <input type="text" name="unit" class="form-control form-control-sm" value="{{ old('unit', $product->unit) }}">
                    </div>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="gst_inclusive" id="gstInclusive" {{ $product->gst_inclusive ? 'checked' : '' }}>
                    <label class="form-check-label small text-dark" for="gstInclusive">Price is GST Inclusive</label>
                </div>
            </div>

            <!-- 2. Inventory & Status -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="bi bi-box-seam text-success me-2"></i>Inventory & Status</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Available Stock Qty</label>
                    <input type="number" name="initial_stock" class="form-control" value="{{ old('initial_stock', $product->inventory->quantity_available ?? 100) }}">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">MOQ</label>
                        <input type="number" name="min_order_qty" class="form-control form-control-sm" value="{{ old('min_order_qty', $product->min_order_qty) }}" min="1">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">Listing Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="out_of_stock" {{ $product->status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Weight per Unit</label>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.001" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
                        <select name="weight_unit" class="form-select" style="max-width: 80px;">
                            <option value="kg" {{ $product->weight_unit === 'kg' ? 'selected' : '' }}>KG</option>
                            <option value="g" {{ $product->weight_unit === 'g' ? 'selected' : '' }}>g</option>
                        </select>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label fw-semibold small text-dark">
                        <i class="bi bi-building-gear text-success me-1"></i> Dispatch Warehouse / Depot
                    </label>
                    <select name="warehouse_id" class="form-select form-select-sm">
                        @if(isset($warehouses) && $warehouses->count() > 0)
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ (old('warehouse_id', $product->warehouse_id) == $wh->id || (!$product->warehouse_id && $wh->is_primary)) ? 'selected' : '' }}>
                                    {{ $wh->name }} ({{ $wh->state ?? 'Default' }} - {{ $wh->city ?? 'Central' }}) {{ $wh->is_primary ? '★ Primary' : '' }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Default Vendor Facility ({{ $vendor->company_name }})</option>
                        @endif
                    </select>
                    <div class="form-text small text-muted" style="font-size: 0.72rem;">Originating location for this material consignment.</div>
                </div>
            </div>

            <!-- 3. Category & Product Specific Policy -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-check text-success me-2"></i>Product Terms & Policy</h6>
                    <span class="badge bg-light text-muted border small">Category or Custom</span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Policy Setting</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="policy_type" id="editPolicyInherit" value="inherit" {{ old('policy_type', $product->policy_type ?? 'inherit') === 'inherit' ? 'checked' : '' }} onchange="toggleEditPolicyField(this.value)">
                            <label class="form-check-label small fw-semibold" for="editPolicyInherit">
                                Inherit Category Policy
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="policy_type" id="editPolicyCustom" value="custom" {{ old('policy_type', $product->policy_type ?? 'inherit') === 'custom' ? 'checked' : '' }} onchange="toggleEditPolicyField(this.value)">
                            <label class="form-check-label small fw-semibold" for="editPolicyCustom">
                                Custom Product Policy
                            </label>
                        </div>
                    </div>
                    <div id="editInheritPolicyNotice" class="mt-2 p-2 bg-light rounded small text-muted {{ old('policy_type', $product->policy_type ?? 'inherit') === 'custom' ? 'd-none' : '' }}" style="font-size: 0.75rem;">
                        <i class="bi bi-info-circle me-1 text-primary"></i> Automatically applies policy defined for Category: <strong>{{ $product->category->name ?? 'Standard' }}</strong>.
                    </div>
                </div>

                <div id="editCustomPolicyBox" class="{{ old('policy_type', $product->policy_type ?? 'inherit') === 'custom' ? '' : 'd-none' }}">
                    <label class="form-label fw-semibold small text-dark">Custom Policy / Return Terms</label>
                    <textarea name="custom_policy" class="form-control form-control-sm" rows="4" placeholder="Enter custom policy for this specific material.">{{ old('custom_policy', $product->custom_policy) }}</textarea>
                </div>
            </div>

            <!-- 4. Material Samples & Trial Specimen Configuration -->
            <div class="card-custom mb-4 p-4 shadow-sm border border-success border-opacity-25" style="background: linear-gradient(180deg, #FFFFFF 0%, #F8FAF9 100%);">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box2-heart-fill text-success me-2"></i>Product Samples & Trial Testing</h6>
                        <small class="text-muted">Offer material test swatches, cut specimens, or sample kits</small>
                    </div>
                    <div class="form-check form-switch fs-6 mb-0">
                        <input class="form-check-input cursor-pointer" type="checkbox" name="has_sample" id="hasSampleEditToggle" {{ old('has_sample', $product->has_sample) ? 'checked' : '' }} onchange="toggleEditSampleOptions(this)">
                        <label class="form-check-label fw-semibold small text-dark" for="hasSampleEditToggle">Enable Samples</label>
                    </div>
                </div>

                <div id="sampleEditDisabledNotice" class="p-3 bg-light rounded text-muted small {{ old('has_sample', $product->has_sample) ? 'd-none' : '' }}">
                    <i class="bi bi-info-circle me-1 text-primary"></i> <strong>Samples are currently disabled</strong> for this product. Enable the switch above to allow buyers to order sample specimens.
                </div>

                <div id="sampleEditConfigSection" class="{{ old('has_sample', $product->has_sample) ? '' : 'd-none' }}">
                    <!-- Sample Pricing Type Options -->
                    <label class="form-label fw-semibold small text-dark mb-2">Sample Pricing Structure <span class="text-danger">*</span></label>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="p-2.5 border rounded-3 d-flex flex-column gap-1 cursor-pointer sample-type-card bg-white h-100 position-relative">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-success small"><i class="bi bi-gift-fill me-1"></i>100% Free</span>
                                    <input type="radio" name="sample_type" value="free" {{ old('sample_type', $product->sample_type ?? 'free') === 'free' ? 'checked' : '' }} onchange="updateEditSamplePricingFields('free')">
                                </div>
                                <span class="text-muted" style="font-size: 0.72rem;">Material & Delivery fully sponsored (₹0).</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="p-2.5 border rounded-3 d-flex flex-column gap-1 cursor-pointer sample-type-card bg-white h-100 position-relative">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-primary small"><i class="bi bi-currency-rupee me-1"></i>Paid Sample</span>
                                    <input type="radio" name="sample_type" value="paid" {{ old('sample_type', $product->sample_type ?? 'free') === 'paid' ? 'checked' : '' }} onchange="updateEditSamplePricingFields('paid')">
                                </div>
                                <span class="text-muted" style="font-size: 0.72rem;">Buyer pays fixed sample price (+ shipping).</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="p-2.5 border rounded-3 d-flex flex-column gap-1 cursor-pointer sample-type-card bg-white h-100 position-relative">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-dark small"><i class="bi bi-truck me-1"></i>Partial Paid</span>
                                    <input type="radio" name="sample_type" value="partial_paid" {{ old('sample_type', $product->sample_type ?? 'free') === 'partial_paid' ? 'checked' : '' }} onchange="updateEditSamplePricingFields('partial_paid')">
                                </div>
                                <span class="text-muted" style="font-size: 0.72rem;">Sample is free (₹0), buyer pays courier fee.</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price & Shipping Fields -->
                    <div class="row g-2 mb-3">
                        <div class="col-6" id="editSamplePriceWrapper" style="{{ old('sample_type', $product->sample_type) === 'paid' ? '' : 'display: none;' }}">
                            <label class="form-label fw-semibold small text-dark mb-1">Sample Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="sample_price" id="editSamplePriceInput" class="form-control" placeholder="99.00" value="{{ old('sample_price', $product->sample_price ?? '0.00') }}">
                            </div>
                        </div>

                        <div class="col-6" id="editSampleShippingWrapper" style="{{ in_array(old('sample_type', $product->sample_type), ['paid', 'partial_paid']) ? '' : 'display: none;' }}">
                            <label class="form-label fw-semibold small text-dark mb-1">Sample Courier Fee (₹)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="sample_shipping_fee" id="editSampleShippingInput" class="form-control" placeholder="50.00" value="{{ old('sample_shipping_fee', $product->sample_shipping_fee ?? '0.00') }}">
                            </div>
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Max Sample Qty</label>
                            <input type="number" name="sample_max_qty" class="form-control form-control-sm" value="{{ old('sample_max_qty', $product->sample_max_qty ?? 1) }}" min="1" max="10">
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Sample Stock Quota</label>
                            <input type="number" name="sample_stock" class="form-control form-control-sm" placeholder="Unlimited" value="{{ old('sample_stock', $product->sample_stock) }}" min="0">
                        </div>
                    </div>

                    <!-- Specimen Specification -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark mb-1">Sample Specimen / Unit Description</label>
                        <input type="text" name="sample_unit_description" class="form-control form-control-sm" placeholder="e.g. 500g Test Bottle / 1 Tile Swatch (150x150mm)" value="{{ old('sample_unit_description', $product->sample_unit_description) }}">
                        <div class="form-text small text-muted" style="font-size: 0.72rem;">Specify dimensions, test volume, or cut sample size.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Sample Dispatch Notes / Instructions</label>
                        <textarea name="sample_instructions" rows="2" class="form-control form-control-sm" placeholder="e.g. Dispatched via Express Air within 24 hours. Includes technical test certificate.">{{ old('sample_instructions', $product->sample_instructions) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 6. Ask for Quotation (RFQ) & Custom Variations Controls -->
            @php
                $existingCustomFields = $product->getQuotationCustomFields();
            @endphp
            <div class="card-custom mb-4 p-4 shadow-sm border border-primary border-opacity-25" style="background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Ask for Quotation & Custom RFQ Controls</h6>
                        <small class="text-muted">Allow buyers to request custom pricing, tailored cut sizes, or bulk freight quotes</small>
                    </div>
                    <div class="form-check form-switch fs-6 mb-0">
                        <input class="form-check-input cursor-pointer" type="checkbox" name="allow_quotation" value="1" id="allowQuotationEditToggle" {{ old('allow_quotation', $product->allow_quotation ?? true) ? 'checked' : '' }} onchange="toggleEditQuotationOptions(this)">
                        <label class="form-check-label fw-semibold small text-dark" for="allowQuotationEditToggle">Enable RFQ</label>
                    </div>
                </div>

                <div id="quotationEditDisabledNotice" class="p-3 bg-light rounded text-muted small {{ old('allow_quotation', $product->allow_quotation ?? true) ? 'd-none' : '' }}">
                    <i class="bi bi-info-circle me-1 text-primary"></i> <strong>Ask for Quotation is disabled</strong> for this product. Buyers will only be able to add standard catalog quantities directly to cart.
                </div>

                <div id="quotationEditConfigSection" class="{{ old('allow_quotation', $product->allow_quotation ?? true) ? '' : 'd-none' }}">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Minimum RFQ Order Quantity</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="quotation_min_qty" class="form-control" placeholder="e.g. 50" value="{{ old('quotation_min_qty', $product->quotation_min_qty) }}" min="1">
                                <span class="input-group-text bg-light small">Units / Bulk MOQ</span>
                            </div>
                            <div class="form-text small text-muted" style="font-size: 0.72rem;">Leave empty to inherit standard product MOQ ({{ $product->min_order_qty ?? 1 }} {{ $product->unit }}).</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">BOQ / Drawing Document Upload</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="quotation_attachments_allowed" value="1" id="quotationAttachmentsEditToggle" {{ old('quotation_attachments_allowed', $product->quotation_attachments_allowed ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label small text-dark" for="quotationAttachmentsEditToggle">Allow buyers to attach BOQ & Drawings (.pdf/.dwg/.xls)</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label fw-semibold small text-dark mb-0">Custom Variations & Quotation Questions (Dynamic Form Fields)</label>
                                <button type="button" class="btn btn-link text-primary p-0 small text-decoration-none fw-semibold" onclick="addEditCustomRfqField()">
                                    <i class="bi bi-plus-circle me-1"></i>+ Add Custom Field / Spec
                                </button>
                            </div>
                            <p class="text-muted small mb-2" style="font-size: 0.73rem;">Define custom specifications or variation questions that the buyer can specify on the quotation form (e.g. "Steel Grade: Fe500D / Fe550D", "Custom Cut Length", "Surface Finish", "Delivery Schedule").</p>
                            
                            <div id="rfqEditFieldsContainer" class="d-flex flex-column gap-2">
                                @if(!empty($existingCustomFields))
                                    @foreach($existingCustomFields as $field)
                                        @php $fieldVal = is_array($field) ? ($field['name'] ?? '') : trim($field); @endphp
                                        @if(!empty($fieldVal))
                                            <div class="input-group input-group-sm rfq-field-row">
                                                <span class="input-group-text bg-light text-muted"><i class="bi bi-tag-fill"></i></span>
                                                <input type="text" name="quotation_custom_fields[]" class="form-control" value="{{ $fieldVal }}" placeholder="e.g. Custom Cut Length / Thickness / Grade">
                                                <button type="button" class="btn btn-outline-danger" onclick="this.closest('.rfq-field-row').remove()"><i class="bi bi-trash"></i></button>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="input-group input-group-sm rfq-field-row">
                                        <span class="input-group-text bg-light text-muted"><i class="bi bi-tag-fill"></i></span>
                                        <input type="text" name="quotation_custom_fields[]" class="form-control" placeholder="e.g. Custom Cut Length / Thickness / Grade Requirement" value="Grade / Specification Preference">
                                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.rfq-field-row').remove()"><i class="bi bi-trash"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">Quotation Guidelines / Response SLA for Buyers</label>
                            <textarea name="quotation_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. We provide mill test certificates and F.O.R. site delivery quotes within 24 hours. Wholesale rates applicable for 10+ metric tonnes.">{{ old('quotation_notes', $product->quotation_notes) }}</textarea>
                            <div class="form-text small text-muted" style="font-size: 0.72rem;">This note is prominently displayed to buyers inside the "Ask for Quotation" modal.</div>
                        </div>
                    </div>

                    <!-- Storefront RFQ Preview -->
                    <div class="p-2.5 bg-white rounded border border-primary border-opacity-25 mt-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small fw-bold text-dark"><i class="bi bi-eye text-primary me-1"></i>Buyer Storefront View:</span>
                            <span class="badge bg-primary text-white px-2 py-1">
                                <i class="bi bi-file-earmark-text me-1"></i> Ask for Quotation Enabled
                            </span>
                        </div>
                        <p class="text-muted mb-0 small" style="font-size: 0.73rem;">Quotation inquiries will instantly dispatch SMS/email alerts to your dashboard & Super Admin trade desk.</p>
                    </div>
                </div>
            </div>

            <script>
            function toggleEditPolicyField(val) {
                const customBox = document.getElementById('editCustomPolicyBox');
                const inheritNotice = document.getElementById('editInheritPolicyNotice');
                if (val === 'custom') {
                    customBox.classList.remove('d-none');
                    inheritNotice.classList.add('d-none');
                } else {
                    customBox.classList.add('d-none');
                    inheritNotice.classList.remove('d-none');
                }
            }

            function toggleEditSampleOptions(checkbox) {
                const configSection = document.getElementById('sampleEditConfigSection');
                const disabledNotice = document.getElementById('sampleEditDisabledNotice');
                if (checkbox.checked) {
                    configSection.classList.remove('d-none');
                    disabledNotice.classList.add('d-none');
                } else {
                    configSection.classList.add('d-none');
                    disabledNotice.classList.remove('d-none');
                }
            }

            function updateEditSamplePricingFields(type) {
                const priceWrap = document.getElementById('editSamplePriceWrapper');
                const shipWrap = document.getElementById('editSampleShippingWrapper');

                if (type === 'free') {
                    priceWrap.style.display = 'none';
                    shipWrap.style.display = 'none';
                } else if (type === 'paid') {
                    priceWrap.style.display = 'block';
                    shipWrap.style.display = 'block';
                } else if (type === 'partial_paid') {
                    priceWrap.style.display = 'none';
                    shipWrap.style.display = 'block';
                }
            }

            function toggleEditQuotationOptions(checkbox) {
                const configSection = document.getElementById('quotationEditConfigSection');
                const disabledNotice = document.getElementById('quotationEditDisabledNotice');
                if (checkbox.checked) {
                    configSection.classList.remove('d-none');
                    disabledNotice.classList.add('d-none');
                } else {
                    configSection.classList.add('d-none');
                    disabledNotice.classList.remove('d-none');
                }
            }

            function addEditCustomRfqField() {
                const container = document.getElementById('rfqEditFieldsContainer');
                const row = document.createElement('div');
                row.className = 'input-group input-group-sm rfq-field-row';
                row.innerHTML = `
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-tag-fill"></i></span>
                    <input type="text" name="quotation_custom_fields[]" class="form-control" placeholder="e.g. Custom Cut Length / Surface Finish / Grade">
                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.rfq-field-row').remove()"><i class="bi bi-trash"></i></button>
                `;
                container.appendChild(row);
            }
            </script>

            <!-- Submit Button Card -->
            <div class="card-custom p-4 shadow-sm sticky-top" style="top: 80px; z-index: 10;">
                <button type="submit" class="btn btn-success btn-lg w-100 py-3 mb-2 fw-bold shadow-sm" style="background-color: var(--garudo-primary); border: none; border-radius: 10px;">
                    <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                </button>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-light border w-100 text-muted" style="border-radius: 10px;">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

<!-- Modal: Quick Request Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="addCategoryModalLabel"><i class="bi bi-diagram-3 text-success me-2"></i>Request New Category / Subcategory</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickCategoryForm" onsubmit="submitQuickCategory(event)">
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background-color: #EBF5FF; color: #1E429F; border-radius: 8px;">
                        <i class="bi bi-shield-check me-1"></i> <strong>Admin Review:</strong> All new categories are verified by Super Admin before going live in the marketplace catalog.
                    </div>
                    <div id="catSuccessAlert" class="alert alert-success py-2 px-3 small border-0 mb-3 d-none"></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Category / Subcategory Name <span class="text-danger">*</span></label>
                        <input type="text" id="newCatName" class="form-control" placeholder="e.g. Ready Mix Concrete (RMC) / False Ceiling" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Placement in Hierarchy</label>
                        <select id="newCatParent" class="form-select">
                            <option value="">-- Create as Main Root Category --</option>
                            <optgroup label="Or add as Subcategory under:">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Justification / Details <span class="text-danger">*</span></label>
                        <textarea id="newCatDesc" rows="2" class="form-control" placeholder="Explain the building materials / products that fall under this category..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveCatBtn" class="btn btn-success btn-sm px-4" style="background-color: var(--garudo-primary); border: none;">
                        <i class="bi bi-send-check me-1"></i> Submit for Admin Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Quick Request Brand -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="addBrandModalLabel"><i class="bi bi-patch-check text-success me-2"></i>Request New Brand / Manufacturer</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickBrandForm" onsubmit="submitQuickBrand(event)">
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background-color: #EBF5FF; color: #1E429F; border-radius: 8px;">
                        <i class="bi bi-shield-check me-1"></i> <strong>Admin Review:</strong> New brand requests are verified by Super Admin to validate manufacturer authenticity before listing.
                    </div>
                    <div id="brandSuccessAlert" class="alert alert-success py-2 px-3 small border-0 mb-3 d-none"></div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Brand / Manufacturer Name <span class="text-danger">*</span></label>
                        <input type="text" id="newBrandName" class="form-control" placeholder="e.g. Tata Tiscon / Ambuja Cement / JSW Steel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Country of Origin</label>
                        <input type="text" id="newBrandOrigin" class="form-control" value="India">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Website URL (Optional)</label>
                        <input type="url" id="newBrandWebsite" class="form-control" placeholder="https://example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Justification / Catalog Note <span class="text-danger">*</span></label>
                        <textarea id="newBrandDesc" rows="2" class="form-control" placeholder="Provide details about the brand and authorized dealership..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveBrandBtn" class="btn btn-success btn-sm px-4" style="background-color: var(--garudo-primary); border: none;">
                        <i class="bi bi-send-check me-1"></i> Submit for Admin Verification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let specIndex = {{ $product->specifications->count() + 10 }};
    function addEditSpecRow(group = 'General', key = '', value = '') {
        const container = document.getElementById('specsContainer');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 spec-row align-items-center';
        row.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="specs[${specIndex}][group]" class="form-control form-control-sm" placeholder="Group" value="${group}">
            </div>
            <div class="col-md-4">
                <input type="text" name="specs[${specIndex}][key]" class="form-control form-control-sm" placeholder="Specification Key" value="${key}">
            </div>
            <div class="col-md-4">
                <input type="text" name="specs[${specIndex}][value]" class="form-control form-control-sm" placeholder="Value" value="${value}">
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 border-0" onclick="this.closest('.spec-row').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        specIndex++;
    }

    const editPresets = {
        cement: [
            { group: 'Grade & Standard', key: 'Cement Grade', value: 'PPC / 53 Grade OPC' },
            { group: 'Grade & Standard', key: 'Standard Compliance', value: 'IS 1489 (Part 1)' },
            { group: 'Physical Properties', key: 'Initial Setting Time', value: '30 - 45 Minutes' },
            { group: 'Compressive Strength', key: '28-Day Strength', value: '≥ 53 MPa' }
        ],
        steel: [
            { group: 'Grade & Standard', key: 'Steel Grade', value: 'Fe 500D / Fe 550D TMT' },
            { group: 'Grade & Standard', key: 'Standard Compliance', value: 'IS 1786:2008' },
            { group: 'Mechanical Properties', key: 'Yield Strength', value: '≥ 500 N/mm²' }
        ],
        blocks: [
            { group: 'Dimensions', key: 'Standard Size', value: '600 x 200 x 150 mm' },
            { group: 'Physical Properties', key: 'Dry Density', value: '550 - 650 kg/m³' },
            { group: 'Strength', key: 'Compressive Strength', value: '≥ 4.0 N/mm²' }
        ],
        paints: [
            { group: 'Finish & Sheen', key: 'Surface Sheen', value: 'Rich Matt / Silk Sheen' },
            { group: 'Coverage', key: 'Spreading Rate', value: '120 - 140 Sq.Ft / Liter' }
        ],
        pipes: [
            { group: 'Material & Class', key: 'Pipe Material', value: 'CPVC / UPVC Schedule 40 & 80' },
            { group: 'Standard', key: 'Standard Compliance', value: 'ASTM D 2846 / IS 15778' }
        ]
    };

    function loadEditSpecPreset(key) {
        if (!editPresets[key]) return;
        editPresets[key].forEach(item => {
            addEditSpecRow(item.group, item.key, item.value);
        });
    }

    // Quick AJAX Category Request Submission
    function submitQuickCategory(e) {
        e.preventDefault();
        const name = document.getElementById('newCatName').value.trim();
        const parent_id = document.getElementById('newCatParent').value;
        const reason = document.getElementById('newCatDesc').value.trim();
        const btn = document.getElementById('saveCatBtn');
        const alertBox = document.getElementById('catSuccessAlert');

        if (!name || !reason) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';

        fetch('{{ route("vendor.categories.quick-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, parent_id, reason, description: reason })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Submit for Admin Verification';
            if (data.success) {
                alertBox.className = 'alert alert-success py-2 px-3 small border-0 mb-3';
                alertBox.innerHTML = '<i class="bi bi-check-circle me-1"></i> ' + (data.message || 'Request submitted to Admin for verification.');
                alertBox.classList.remove('d-none');

                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                    if (modal) modal.hide();
                    document.getElementById('quickCategoryForm').reset();
                    alertBox.classList.add('d-none');
                }, 2200);
            } else {
                alert('Submission failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Submit for Admin Verification';
            alert('Request failed: ' + err.message);
        });
    }

    // Quick AJAX Brand Request Submission
    function submitQuickBrand(e) {
        e.preventDefault();
        const name = document.getElementById('newBrandName').value.trim();
        const origin = document.getElementById('newBrandOrigin').value.trim();
        const website = document.getElementById('newBrandWebsite').value.trim();
        const reason = document.getElementById('newBrandDesc').value.trim();
        const btn = document.getElementById('saveBrandBtn');
        const alertBox = document.getElementById('brandSuccessAlert');

        if (!name || !reason) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';

        fetch('{{ route("vendor.brands.quick-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, country_of_origin: origin, website, reason, description: reason })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Submit for Admin Verification';
            if (data.success) {
                alertBox.className = 'alert alert-success py-2 px-3 small border-0 mb-3';
                alertBox.innerHTML = '<i class="bi bi-check-circle me-1"></i> ' + (data.message || 'Request submitted to Admin for verification.');
                alertBox.classList.remove('d-none');

                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addBrandModal'));
                    if (modal) modal.hide();
                    document.getElementById('quickBrandForm').reset();
                    alertBox.classList.add('d-none');
                }, 2200);
            } else {
                alert('Submission failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-check me-1"></i> Submit for Admin Verification';
            alert('Request failed: ' + err.message);
        });
    }

    // Check Brand Protection & Selling Rights
    function checkBrandProtection(select) {
        if (!select) select = document.getElementById('brandSelect');
        if (!select) return;

        const option = select.options[select.selectedIndex];
        const isProtected = option && option.getAttribute('data-protected') === '1';
        const isAllowed = option && option.getAttribute('data-allowed') === '1';
        const noticeWarn = document.getElementById('brandProtectionNotice');
        const noticeOk = document.getElementById('brandVerifiedNotice');

        if (!noticeWarn || !noticeOk) return;

        if (!select.value) {
            noticeWarn.classList.add('d-none');
            noticeOk.classList.add('d-none');
            return;
        }

        if (isProtected) {
            if (!isAllowed) {
                noticeWarn.classList.remove('d-none');
                noticeOk.classList.add('d-none');
            } else {
                noticeWarn.classList.add('d-none');
                noticeOk.classList.remove('d-none');
            }
        } else {
            noticeWarn.classList.add('d-none');
            noticeOk.classList.add('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const brandSel = document.getElementById('brandSelect');
        if (brandSel && brandSel.value) {
            checkBrandProtection(brandSel);
        }
    });
</script>
@endpush
@endsection
