@extends('layouts.vendor')

@section('title', 'Add New Product')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">Add New Product</h4>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-semibold">B2B Material Listing</span>
        </div>
        <p class="text-muted mb-0 small">List standard building materials, configure multi-tier wholesale pricing, technical specs, and variations.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vendor.products.bulk') }}" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
            <i class="bi bi-cloud-arrow-up me-1"></i> Bulk Excel Upload
        </a>
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

<form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <!-- 1. Basic Product Info Card -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle text-success me-2"></i>Product Identity & Categorization</h6>
                    <span class="badge bg-light text-muted border small">Step 1 of 4</span>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold small text-dark">Product Title / Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg fs-6" placeholder="e.g. UltraTech Super Cement PPC 50kg Bag (IS 1489)" value="{{ old('name') }}" required>
                        <div class="form-text small text-muted">Include Brand, Grade/Variant, and Packaging size for best search ranking.</div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fw-semibold small text-dark mb-0">Category <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-link text-success p-0 small text-decoration-none fw-semibold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="bi bi-send-plus me-1"></i>+ Request Category
                            </button>
                        </div>
                        <select name="category_id" id="categorySelect" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                                {{ old('brand_id') == $sb->id ? 'selected' : '' }}>
                                            {{ $sb->name }} [All {{ $sb->name }} Line] {{ $sb->is_protected ? '🛡️' : '' }}
                                        </option>
                                        @foreach($sb->activeSubBrands as $sub)
                                            <option value="{{ $sub->id }}" 
                                                    data-protected="{{ $sub->is_protected ? '1' : '0' }}" 
                                                    data-allowed="{{ $sub->canVendorSell($vendor) ? '1' : '0' }}"
                                                    {{ old('brand_id') == $sub->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $sub->name }} (Sub Brand) {{ $sub->is_protected ? '🛡️' : '' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $sb->id }}" 
                                            data-protected="{{ $sb->is_protected ? '1' : '0' }}" 
                                            data-allowed="{{ $sb->canVendorSell($vendor) ? '1' : '0' }}"
                                            {{ old('brand_id') == $sb->id ? 'selected' : '' }}>
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
                        <input type="text" name="sku" class="form-control" placeholder="Auto-generated if blank" value="{{ old('sku') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-dark">HSN / SAC Code</label>
                        <input type="text" name="hsn_code" class="form-control" placeholder="e.g. 252329 (Cement)" value="{{ old('hsn_code') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-dark">Barcode / EAN (Optional)</label>
                        <input type="text" name="barcode" class="form-control" placeholder="e.g. 890123456789" value="{{ old('barcode') }}">
                    </div>
                </div>
            </div>

            <!-- 2. Product Media & Gallery Photos -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-images text-success me-2"></i>Product Visuals & Photo Gallery</h6>
                        <small class="text-muted">Primary catalog photo and optional multi-angle / site application images</small>
                    </div>
                    <span class="badge bg-light text-muted border small">Step 2 of 4</span>
                </div>

                <div class="row g-3">
                    <!-- Main Product Image -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-dark">Main / Primary Product Photo <span class="badge bg-success bg-opacity-10 text-success">Required</span></label>
                        <div class="border-2 border-dashed rounded p-3 text-center bg-light position-relative" style="border: 2px dashed #CBD5E1; min-height: 160px;">
                            <input type="file" name="main_image" id="mainImageInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" onchange="previewMainImage(this)">
                            <div id="mainImagePlaceholder" class="py-3">
                                <i class="bi bi-cloud-arrow-up text-success fs-1"></i>
                                <div class="fw-bold small text-dark mt-1">Upload Main Photo</div>
                                <div class="text-muted" style="font-size: 0.75rem;">PNG, JPG, WEBP up to 5MB</div>
                            </div>
                            <img id="mainImagePreview" src="" alt="Main Preview" class="img-fluid rounded d-none" style="max-height: 140px; object-fit: contain;">
                        </div>
                    </div>

                    <!-- Related / Gallery Photos -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-dark">Related / Gallery Photos <span class="text-muted fw-normal">(Optional Multi-Upload)</span></label>
                        <div class="border-2 border-dashed rounded p-3 text-center bg-light position-relative" style="border: 2px dashed #CBD5E1; min-height: 160px;">
                            <input type="file" name="gallery_images[]" id="galleryImagesInput" multiple class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*" onchange="previewGalleryImages(this)">
                            <div id="galleryPlaceholder" class="py-3">
                                <i class="bi bi-collection text-primary fs-1"></i>
                                <div class="fw-bold small text-dark mt-1">Add Related Photos</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Select multiple photos at once</div>
                            </div>
                            <div id="galleryPreviewContainer" class="d-flex flex-wrap gap-2 justify-content-center d-none"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Technical Specifications & Material Attributes -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-sliders text-success me-2"></i>Technical Specifications & Properties</h6>
                        <small class="text-muted">Grade, Dimensions, Test Standards, Compressive Strength, and Custom Specs</small>
                    </div>
                    <span class="badge bg-light text-muted border small">Step 3 of 4</span>
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
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadSpecPreset('cement')">
                            <i class="bi bi-box-seam me-1"></i> Cement (OPC / PPC)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadSpecPreset('steel')">
                            <i class="bi bi-shield me-1"></i> Steel & TMT Bars
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadSpecPreset('blocks')">
                            <i class="bi bi-bricks me-1"></i> AAC Blocks / Bricks
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadSpecPreset('paints')">
                            <i class="bi bi-paint-bucket me-1"></i> Paints & Chemicals
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success bg-white fw-semibold" onclick="loadSpecPreset('pipes')">
                            <i class="bi bi-water me-1"></i> Pipes & Plumbing
                        </button>
                    </div>
                    <div class="text-muted mt-2" style="font-size: 0.73rem;">
                        <i class="bi bi-info-circle me-1 text-primary"></i> Choose an approved industry template to instantly populate technical properties. Need a new custom specification template? Staff will add it upon request via <a href="{{ route('vendor.attributes') }}" class="text-success text-decoration-none fw-semibold">Catalog Requests</a>.
                    </div>
                </div>

                <!-- Dynamic Specifications Rows -->
                <div id="specsContainer">
                    <!-- Default Initial Rows -->
                    <div class="row g-2 mb-2 spec-row align-items-center">
                        <div class="col-md-3">
                            <input type="text" name="specs[0][group]" class="form-control form-control-sm" placeholder="Group (e.g. Technical)" value="General">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="specs[0][key]" class="form-control form-control-sm" placeholder="Specification Key (e.g. Grade)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="specs[0][value]" class="form-control form-control-sm" placeholder="Value (e.g. Fe 500D / 53 MPa)">
                        </div>
                        <div class="col-md-1 text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 border-0" onclick="removeSpecRow(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addSpecRow()">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Specification Row
                    </button>
                </div>
            </div>

            <!-- 4. Product Type & Variations (Simple vs Variable Product) -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-diagram-2 text-success me-2"></i>Product Variations & Matrix</h6>
                        <small class="text-muted">Create variations based on Sizes (8mm, 10mm, 12mm), Grades, or Packaging</small>
                    </div>
                    <div class="form-check form-switch fs-6 mb-0">
                        <input class="form-check-input cursor-pointer" type="checkbox" id="productTypeToggle" onchange="toggleVariableProduct(this)">
                        <label class="form-check-label fw-semibold small text-dark" for="productTypeToggle">Has Variations</label>
                    </div>
                </div>

                <input type="hidden" name="product_type" id="productTypeInput" value="simple">

                <div id="simpleProductNotice" class="p-3 bg-light rounded text-muted small">
                    <i class="bi bi-info-circle me-1 text-primary"></i> Currently set as a <strong>Simple Product</strong> (single standard SKU). Enable "Has Variations" switch above if this product has multiple sizes, grades, colors, or packages.
                </div>

                <div id="variationSection" class="d-none">
                    <div class="p-3 bg-light rounded border mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small text-dark mb-1">Variant Attributes (Comma Separated)</label>
                                <input type="text" id="variantAttributesInput" class="form-control form-control-sm" placeholder="e.g. 8mm, 10mm, 12mm, 16mm, 20mm, 25mm, 32mm">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success btn-sm w-100" onclick="generateVariantsTable()" style="background-color: var(--garudo-primary); border: none;">
                                    <i class="bi bi-grid-3x3-gap me-1"></i> Generate Variations
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0" id="variantsTable">
                            <thead class="bg-light small">
                                <tr>
                                    <th style="min-width: 140px;">Variant Name / Size</th>
                                    <th style="min-width: 130px;">Variant SKU</th>
                                    <th style="min-width: 110px;">B2B Price (₹) *</th>
                                    <th style="min-width: 110px;">MRP (₹)</th>
                                    <th style="min-width: 110px;">Wholesale (₹)</th>
                                    <th style="min-width: 90px;">Stock</th>
                                    <th style="width: 50px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="variantsTableBody">
                                <!-- Generated Dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5. Product Description & Overview -->
            <div class="card-custom p-4 shadow-sm">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-3"><i class="bi bi-file-text text-success me-2"></i>Product Overview & Application Details</h6>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Short Summary Overview</label>
                    <input type="text" name="short_description" class="form-control" placeholder="e.g. Premium Grade 53 Portland Pozzolana Cement for heavy RCC and marine structures." value="{{ old('short_description') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Full Description & Application Instructions</label>
                    <textarea name="description" rows="6" class="form-control" placeholder="Provide complete application method, curing guidelines, test certificate compliance, storage warnings...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label fw-semibold small text-dark">Key Highlights / Features (One per line)</label>
                    <textarea name="features" rows="3" class="form-control" placeholder="• High initial and 28-day compressive strength&#10;• Low heat of hydration prevents thermal cracking&#10;• BIS Certified under IS: 1489 (Part 1)">{{ old('features') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Right Side Configuration Column -->
        <div class="col-lg-4">
            <!-- 1. B2B Pricing Card -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="bi bi-currency-rupee text-success me-2"></i>B2B Pricing & Taxes</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">B2B Base Selling Price (₹) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">₹</span>
                        <input type="number" step="0.01" name="selling_price" class="form-control fw-bold fs-5 text-success" placeholder="340.00" value="{{ old('selling_price') }}" required>
                    </div>
                    <div class="form-text small text-muted">Ex-Factory or Base catalog price per unit.</div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">MRP / Retail (₹)</label>
                        <input type="number" step="0.01" name="mrp" class="form-control form-control-sm" placeholder="390.00" value="{{ old('mrp') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">Wholesale Price (₹)</label>
                        <input type="number" step="0.01" name="wholesale_price" class="form-control form-control-sm" placeholder="325.00" value="{{ old('wholesale_price') }}">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">GST Rate (%)</label>
                        <select name="gst_rate" class="form-select form-select-sm">
                            <option value="0">0% (Nil)</option>
                            <option value="5">5% GST</option>
                            <option value="12">12% GST</option>
                            <option value="18" selected>18% GST (Standard)</option>
                            <option value="28">28% GST (Cement/Luxury)</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">Unit</label>
                        <select name="unit" class="form-select form-select-sm">
                            <option value="Bag" selected>Bag (50 Kg)</option>
                            <option value="Ton">Ton (Metric Ton)</option>
                            <option value="Piece">Piece / Number</option>
                            <option value="Kg">Kilogram (Kg)</option>
                            <option value="Sq.Ft">Sq. Feet</option>
                            <option value="Liter">Liter</option>
                            <option value="Bucket">Bucket (20L)</option>
                            <option value="Box">Box / Carton</option>
                            <option value="Bundle">Bundle</option>
                            <option value="Cubic Meter">Cubic Meter (Cum)</option>
                        </select>
                    </div>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="gst_inclusive" id="gstInclusive" checked>
                    <label class="form-check-label small text-dark" for="gstInclusive">Price is GST Inclusive</label>
                </div>
            </div>

            <!-- 2. Inventory & MOQ -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2"><i class="bi bi-box-seam text-success me-2"></i>Inventory & Order Policy</h6>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Initial Stock Quantity Available</label>
                    <input type="number" name="initial_stock" class="form-control" value="{{ old('initial_stock', 500) }}" min="0">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">Min Order (MOQ) <span class="text-danger">*</span></label>
                        <input type="number" name="min_order_qty" class="form-control form-control-sm" value="{{ old('min_order_qty', 20) }}" min="1">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small text-dark">Max Order Qty</label>
                        <input type="number" name="max_order_qty" class="form-control form-control-sm" placeholder="Unlimited" value="{{ old('max_order_qty') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Weight per Unit (for logistics calculation)</label>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.001" name="weight" class="form-control" placeholder="50" value="{{ old('weight', 50) }}">
                        <select name="weight_unit" class="form-select" style="max-width: 80px;">
                            <option value="kg" selected>KG</option>
                            <option value="g">g</option>
                            <option value="lb">lb</option>
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
                                <option value="{{ $wh->id }}" {{ (old('warehouse_id') == $wh->id || $wh->is_primary) ? 'selected' : '' }}>
                                    {{ $wh->name }} ({{ $wh->state ?? 'Default' }} - {{ $wh->city ?? 'Central' }}) {{ $wh->is_primary ? '★ Primary' : '' }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Default Vendor Facility ({{ $vendor->company_name }})</option>
                        @endif
                    </select>
                    <div class="form-text small text-muted" style="font-size: 0.72rem;">Location where material is stocked and from which it will be dispatched.</div>
                </div>
            </div>

            <!-- 3. Manufacturer Delivery & F.O.R. Policy -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-truck text-success me-2"></i>Logistics & F.O.R. Policy</h6>
                    <a href="{{ route('vendor.products.logistics-policy') }}" target="_blank" class="small text-decoration-none text-success">
                        <i class="bi bi-question-circle"></i> Guide
                    </a>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="free_shipping" id="forEligibility">
                    <label class="form-check-label fw-semibold small text-dark" for="forEligibility">
                        F.O.R. Destination (Free Delivery on Bulk)
                    </label>
                    <div class="text-muted" style="font-size: 0.75rem;">Free Freight paid by manufacturer if MOQ is met.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Standard Freight Rate (if below F.O.R. threshold)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light">₹</span>
                        <input type="number" step="0.01" name="shipping_charges" class="form-control" placeholder="0.00" value="{{ old('shipping_charges', 0.00) }}">
                    </div>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="cod_available" id="codAvailable" checked>
                    <label class="form-check-label small text-dark" for="codAvailable">Cash On Delivery / Project Site Payment Allowed</label>
                </div>
            </div>

            <!-- 4. Category & Product Specific Policy -->
            <div class="card-custom mb-4 p-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-check text-success me-2"></i>Product Terms & Policy</h6>
                    <span class="badge bg-light text-muted border small">Category or Custom</span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Policy Setting</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="policy_type" id="policyInherit" value="inherit" checked onchange="togglePolicyField(this.value)">
                            <label class="form-check-label small fw-semibold" for="policyInherit">
                                Inherit Category Policy
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="policy_type" id="policyCustom" value="custom" onchange="togglePolicyField(this.value)">
                            <label class="form-check-label small fw-semibold" for="policyCustom">
                                Custom Product Policy
                            </label>
                        </div>
                    </div>
                    <div id="inheritPolicyNotice" class="mt-2 p-2 bg-light rounded small text-muted" style="font-size: 0.75rem;">
                        <i class="bi bi-info-circle me-1 text-primary"></i> Automatically applies the return, damage reporting, and inspection policy defined for this Category/Sub-category.
                    </div>
                </div>

            <!-- 5. Material Samples & Trial Specimen Configuration -->
            <div class="card-custom mb-4 p-4 shadow-sm border border-success border-opacity-25" style="background: linear-gradient(180deg, #FFFFFF 0%, #F8FAF9 100%);">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box2-heart-fill text-success me-2"></i>Product Samples & Trial Testing</h6>
                        <small class="text-muted">Offer material test swatches, cut specimens, or sample kits to B2B buyers</small>
                    </div>
                    <div class="form-check form-switch fs-6 mb-0">
                        <input class="form-check-input cursor-pointer" type="checkbox" name="has_sample" id="hasSampleToggle" onchange="toggleSampleOptions(this)">
                        <label class="form-check-label fw-semibold small text-dark" for="hasSampleToggle">Enable Samples</label>
                    </div>
                </div>

                <div id="sampleDisabledNotice" class="p-3 bg-light rounded text-muted small">
                    <i class="bi bi-info-circle me-1 text-primary"></i> <strong>Samples are currently disabled</strong> for this product. Enable the switch above if you want contractors, architects, and buyers to order physical test pieces before placing bulk orders.
                </div>

                <div id="sampleConfigSection" class="d-none">
                    <!-- Sample Pricing Type Options -->
                    <label class="form-label fw-semibold small text-dark mb-2">Sample Pricing Structure <span class="text-danger">*</span></label>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="p-2.5 border rounded-3 d-flex flex-column gap-1 cursor-pointer sample-type-card bg-white h-100 position-relative" style="transition: all 0.2s;" id="cardTypeFree">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-success small"><i class="bi bi-gift-fill me-1"></i>100% Free</span>
                                    <input type="radio" name="sample_type" value="free" checked onchange="updateSamplePricingFields('free')">
                                </div>
                                <span class="text-muted" style="font-size: 0.72rem;">Material & Delivery fully sponsored by vendor (₹0).</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="p-2.5 border rounded-3 d-flex flex-column gap-1 cursor-pointer sample-type-card bg-white h-100 position-relative" style="transition: all 0.2s;" id="cardTypePaid">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-primary small"><i class="bi bi-currency-rupee me-1"></i>Paid Sample</span>
                                    <input type="radio" name="sample_type" value="paid" onchange="updateSamplePricingFields('paid')">
                                </div>
                                <span class="text-muted" style="font-size: 0.72rem;">Buyer pays fixed sample price (+ shipping).</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="p-2.5 border rounded-3 d-flex flex-column gap-1 cursor-pointer sample-type-card bg-white h-100 position-relative" style="transition: all 0.2s;" id="cardTypePartial">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-dark small"><i class="bi bi-truck me-1"></i>Partial Paid</span>
                                    <input type="radio" name="sample_type" value="partial_paid" onchange="updateSamplePricingFields('partial_paid')">
                                </div>
                                <span class="text-muted" style="font-size: 0.72rem;">Sample is free (₹0), buyer pays shipping only.</span>
                            </label>
                        </div>
                    </div>

                    <!-- Price & Shipping Fields -->
                    <div class="row g-2 mb-3">
                        <div class="col-6" id="samplePriceWrapper" style="display: none;">
                            <label class="form-label fw-semibold small text-dark mb-1">Sample Price (₹) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="sample_price" id="samplePriceInput" class="form-control" placeholder="99.00" value="{{ old('sample_price', '0.00') }}">
                            </div>
                        </div>

                        <div class="col-6" id="sampleShippingWrapper" style="display: none;">
                            <label class="form-label fw-semibold small text-dark mb-1">Sample Courier Fee (₹)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">₹</span>
                                <input type="number" step="0.01" name="sample_shipping_fee" id="sampleShippingInput" class="form-control" placeholder="50.00" value="{{ old('sample_shipping_fee', '0.00') }}">
                            </div>
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Max Sample Qty Per Buyer</label>
                            <input type="number" name="sample_max_qty" class="form-control form-control-sm" value="{{ old('sample_max_qty', 1) }}" min="1" max="10">
                        </div>

                        <div class="col-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Sample Stock Quota (Optional)</label>
                            <input type="number" name="sample_stock" class="form-control form-control-sm" placeholder="Unlimited" value="{{ old('sample_stock') }}" min="0">
                        </div>
                    </div>

                    <!-- Specimen Specification -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark mb-1">Sample Specimen / Unit Description</label>
                        <input type="text" name="sample_unit_description" class="form-control form-control-sm" placeholder="e.g. 500g Test Bottle / 1 Piece Cut Swatch (150x150mm) / 1 Brick Specimen" value="{{ old('sample_unit_description') }}">
                        <div class="form-text small text-muted" style="font-size: 0.72rem;">Specify dimensions, test pouch volume, or cut sample size.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-dark mb-1">Sample Dispatch Notes / Instructions</label>
                        <textarea name="sample_instructions" rows="2" class="form-control form-control-sm" placeholder="e.g. Dispatched via Express Air within 24 hours. Includes technical test certificate & batch lab report.">{{ old('sample_instructions') }}</textarea>
                    </div>

                    <!-- Live Storefront Badge Preview -->
                    <div class="p-2.5 bg-white rounded border border-success border-opacity-25 mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small fw-bold text-dark"><i class="bi bi-eye text-primary me-1"></i>Storefront Preview:</span>
                            <span id="previewSampleBadge" class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                <i class="bi bi-gift-fill me-1"></i> 100% Free Sample Available
                            </span>
                        </div>
                        <p class="text-muted mb-0 small" style="font-size: 0.73rem;">Buyers will see a dedicated "Order Sample" CTA with real-time tracking.</p>
                    </div>
            <!-- 6. Ask for Quotation (RFQ) & Custom Variations Controls -->
            <div class="card-custom mb-4 p-4 shadow-sm border border-primary border-opacity-25" style="background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Ask for Quotation & Custom RFQ Controls</h6>
                        <small class="text-muted">Allow buyers to request custom pricing, tailored cut sizes, or bulk freight quotes</small>
                    </div>
                    <div class="form-check form-switch fs-6 mb-0">
                        <input class="form-check-input cursor-pointer" type="checkbox" name="allow_quotation" value="1" id="allowQuotationToggle" checked onchange="toggleQuotationOptions(this)">
                        <label class="form-check-label fw-semibold small text-dark" for="allowQuotationToggle">Enable RFQ</label>
                    </div>
                </div>

                <div id="quotationDisabledNotice" class="p-3 bg-light rounded text-muted small d-none">
                    <i class="bi bi-info-circle me-1 text-primary"></i> <strong>Ask for Quotation is disabled</strong> for this product. Buyers will only be able to add standard catalog quantities directly to cart.
                </div>

                <div id="quotationConfigSection">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">Minimum RFQ Order Quantity</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="quotation_min_qty" class="form-control" placeholder="e.g. 50" value="{{ old('quotation_min_qty') }}" min="1">
                                <span class="input-group-text bg-light small">Units / Bulk MOQ</span>
                            </div>
                            <div class="form-text small text-muted" style="font-size: 0.72rem;">Leave empty to inherit standard product MOQ.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark mb-1">BOQ / Drawing Document Upload</label>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="quotation_attachments_allowed" value="1" id="quotationAttachmentsToggle" checked>
                                <label class="form-check-label small text-dark" for="quotationAttachmentsToggle">Allow buyers to attach BOQ & Drawings (.pdf/.dwg/.xls)</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label fw-semibold small text-dark mb-0">Custom Variations & Quotation Questions (Dynamic Form Fields)</label>
                                <button type="button" class="btn btn-link text-primary p-0 small text-decoration-none fw-semibold" onclick="addCustomRfqField()">
                                    <i class="bi bi-plus-circle me-1"></i>+ Add Custom Field / Spec
                                </button>
                            </div>
                            <p class="text-muted small mb-2" style="font-size: 0.73rem;">Define custom specifications or variation questions that the buyer can specify on the quotation form (e.g. "Steel Grade: Fe500D / Fe550D", "Custom Cut Length", "Surface Finish", "Delivery Schedule").</p>
                            
                            <div id="rfqFieldsContainer" class="d-flex flex-column gap-2">
                                <div class="input-group input-group-sm rfq-field-row">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-tag-fill"></i></span>
                                    <input type="text" name="quotation_custom_fields[]" class="form-control" placeholder="e.g. Custom Cut Length / Thickness / Grade Requirement" value="Grade / Specification Preference">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.rfq-field-row').remove()"><i class="bi bi-trash"></i></button>
                                </div>
                                <div class="input-group input-group-sm rfq-field-row">
                                    <span class="input-group-text bg-light text-muted"><i class="bi bi-tag-fill"></i></span>
                                    <input type="text" name="quotation_custom_fields[]" class="form-control" placeholder="e.g. Custom Cut Length / Thickness / Grade Requirement" value="Delivery Site Unloading Facility Available (Yes/No)">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.rfq-field-row').remove()"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark mb-1">Quotation Guidelines / Response SLA for Buyers</label>
                            <textarea name="quotation_notes" rows="2" class="form-control form-control-sm" placeholder="e.g. We provide mill test certificates and F.O.R. site delivery quotes within 24 hours. Wholesale rates applicable for 10+ metric tonnes.">{{ old('quotation_notes') }}</textarea>
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
            function toggleSampleOptions(checkbox) {
                const configSection = document.getElementById('sampleConfigSection');
                const disabledNotice = document.getElementById('sampleDisabledNotice');
                if (checkbox.checked) {
                    configSection.classList.remove('d-none');
                    disabledNotice.classList.add('d-none');
                } else {
                    configSection.classList.add('d-none');
                    disabledNotice.classList.remove('d-none');
                }
            }

            function updateSamplePricingFields(type) {
                const priceWrap = document.getElementById('samplePriceWrapper');
                const shipWrap = document.getElementById('sampleShippingWrapper');
                const previewBadge = document.getElementById('previewSampleBadge');

                if (type === 'free') {
                    if (priceWrap) priceWrap.style.display = 'none';
                    if (shipWrap) shipWrap.style.display = 'none';
                    if (previewBadge) {
                        previewBadge.className = 'badge bg-success-subtle text-success border border-success px-2 py-1';
                        previewBadge.innerHTML = '<i class="bi bi-gift-fill me-1"></i> 100% Free Sample Available';
                    }
                } else if (type === 'paid') {
                    if (priceWrap) priceWrap.style.display = 'block';
                    if (shipWrap) shipWrap.style.display = 'block';
                    if (previewBadge) {
                        previewBadge.className = 'badge bg-primary-subtle text-primary border border-primary px-2 py-1';
                        previewBadge.innerHTML = '<i class="bi bi-currency-rupee me-1"></i> Paid Sample Available';
                    }
                } else if (type === 'partial_paid') {
                    if (priceWrap) priceWrap.style.display = 'none';
                    if (shipWrap) shipWrap.style.display = 'block';
                    if (previewBadge) {
                        previewBadge.className = 'badge bg-warning-subtle text-dark border border-warning px-2 py-1';
                        previewBadge.innerHTML = '<i class="bi bi-truck me-1"></i> Sample Available (Shipping Only)';
                    }
                }
            }

            function toggleQuotationOptions(checkbox) {
                const configSection = document.getElementById('quotationConfigSection');
                const disabledNotice = document.getElementById('quotationDisabledNotice');
                if (checkbox.checked) {
                    configSection.classList.remove('d-none');
                    disabledNotice.classList.add('d-none');
                } else {
                    configSection.classList.add('d-none');
                    disabledNotice.classList.remove('d-none');
                }
            }

            function addCustomRfqField() {
                const container = document.getElementById('rfqFieldsContainer');
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
                    <i class="bi bi-cloud-check-fill me-2"></i> Publish Product to B2B Catalog
                </button>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-light border w-100 text-muted" style="border-radius: 10px;">
                    Cancel & Return
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
                        <label class="form-label fw-semibold small">Justification / Details (Why is this needed?) <span class="text-danger">*</span></label>
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
    // 1. Image Preview Handlers
    function previewMainImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mainImagePreview').src = e.target.result;
                document.getElementById('mainImagePreview').classList.remove('d-none');
                document.getElementById('mainImagePlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGalleryImages(input) {
        const container = document.getElementById('galleryPreviewContainer');
        container.innerHTML = '';
        if (input.files && input.files.length > 0) {
            container.classList.remove('d-none');
            document.getElementById('galleryPlaceholder').classList.add('d-none');

            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgWrap = document.createElement('div');
                    imgWrap.className = 'position-relative border rounded p-1 bg-white shadow-sm';
                    imgWrap.style.width = '60px';
                    imgWrap.style.height = '60px';
                    imgWrap.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">`;
                    container.appendChild(imgWrap);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // 2. Specifications Dynamic Rows
    let specIndex = 1;
    function addSpecRow(group = 'General', key = '', value = '') {
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
                <button type="button" class="btn btn-outline-danger btn-sm p-1 px-2 border-0" onclick="removeSpecRow(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        specIndex++;
    }

    function removeSpecRow(btn) {
        const row = btn.closest('.spec-row');
        if (document.querySelectorAll('.spec-row').length > 1) {
            row.remove();
        } else {
            row.querySelectorAll('input').forEach(i => i.value = '');
        }
    }

    // 3. Industry Spec Presets
    const industryPresets = {
        cement: [
            { group: 'Grade & Standard', key: 'Cement Grade', value: 'PPC / 53 Grade OPC' },
            { group: 'Grade & Standard', key: 'Standard Compliance', value: 'IS 1489 (Part 1)' },
            { group: 'Physical Properties', key: 'Initial Setting Time', value: '30 - 45 Minutes' },
            { group: 'Physical Properties', key: 'Final Setting Time', value: '300 - 600 Minutes' },
            { group: 'Compressive Strength', key: '28-Day Strength', value: '≥ 53 MPa' },
            { group: 'Packaging', key: 'Bag Type', value: 'HDPE Moisture Resistant Laminated Bag' }
        ],
        steel: [
            { group: 'Grade & Standard', key: 'Steel Grade', value: 'Fe 500D / Fe 550D TMT' },
            { group: 'Grade & Standard', key: 'Standard Compliance', value: 'IS 1786:2008' },
            { group: 'Mechanical Properties', key: 'Yield Strength', value: '≥ 500 N/mm²' },
            { group: 'Mechanical Properties', key: 'Elongation', value: '≥ 16% (High Ductility)' },
            { group: 'Chemical Properties', key: 'Carbon Equivalent (CE)', value: 'Max 0.42%' },
            { group: 'Corrosion Resistance', key: 'Protection Type', value: 'Thermo-Mechanically Treated Quenched' }
        ],
        blocks: [
            { group: 'Dimensions', key: 'Standard Size', value: '600 x 200 x 150 mm' },
            { group: 'Physical Properties', key: 'Dry Density', value: '550 - 650 kg/m³' },
            { group: 'Strength', key: 'Compressive Strength', value: '≥ 4.0 N/mm²' },
            { group: 'Thermal Properties', key: 'Thermal Conductivity', value: '0.16 - 0.18 W/m-k' },
            { group: 'Fire Resistance', key: 'Fire Rating', value: 'Up to 4 Hours' }
        ],
        paints: [
            { group: 'Finish & Sheen', key: 'Surface Sheen', value: 'Rich Matt / Silk Sheen' },
            { group: 'Coverage', key: 'Spreading Rate', value: '120 - 140 Sq.Ft / Liter / 2 Coats' },
            { group: 'Drying Time', key: 'Touch Dry', value: '30 Minutes' },
            { group: 'Durability', key: 'Warranty Period', value: '5 - 7 Years Exterior Durability' },
            { group: 'VOC', key: 'VOC Content', value: 'Low VOC (< 50 gm/liter)' }
        ],
        pipes: [
            { group: 'Material & Class', key: 'Pipe Material', value: 'CPVC / UPVC Schedule 40 & 80' },
            { group: 'Standard', key: 'Standard Compliance', value: 'ASTM D 2846 / IS 15778' },
            { group: 'Pressure Rating', key: 'Working Pressure', value: 'Up to 28.1 kg/cm² at 23°C' },
            { group: 'Temperature', key: 'Max Working Temp', value: 'Up to 93°C (Hot Water)' }
        ]
    };

    function loadSpecPreset(presetKey) {
        if (!industryPresets[presetKey]) return;
        const container = document.getElementById('specsContainer');
        container.innerHTML = '';
        specIndex = 0;
        industryPresets[presetKey].forEach(item => {
            addSpecRow(item.group, item.key, item.value);
        });
    }

    // 4. Variable Product Toggle & Matrix Generator
    function toggleVariableProduct(checkbox) {
        const variationSection = document.getElementById('variationSection');
        const simpleNotice = document.getElementById('simpleProductNotice');
        const typeInput = document.getElementById('productTypeInput');

        if (checkbox.checked) {
            variationSection.classList.remove('d-none');
            simpleNotice.classList.add('d-none');
            typeInput.value = 'variable';
        } else {
            variationSection.classList.add('d-none');
            simpleNotice.classList.remove('d-none');
            typeInput.value = 'simple';
        }
    }

    function generateVariantsTable() {
        const inputStr = document.getElementById('variantAttributesInput').value.trim();
        if (!inputStr) {
            alert('Please enter variant attributes (e.g. 8mm, 10mm, 12mm).');
            return;
        }

        const basePrice = document.querySelector('input[name="selling_price"]').value || '0';
        const baseMrp = document.querySelector('input[name="mrp"]').value || basePrice;
        const baseWholesale = document.querySelector('input[name="wholesale_price"]').value || basePrice;

        const attributes = inputStr.split(',').map(s => s.trim()).filter(s => s.length > 0);
        const tbody = document.getElementById('variantsTableBody');
        tbody.innerHTML = '';

        attributes.forEach((attr, idx) => {
            const skuVal = 'VAR-' + attr.replace(/[^a-zA-Z0-9]/g, '').toUpperCase() + '-' + Math.floor(100 + Math.random() * 900);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="fw-bold small text-dark">${attr}</div>
                    <input type="hidden" name="variants[${idx}][name]" value="${attr}">
                </td>
                <td>
                    <input type="text" name="variants[${idx}][sku]" class="form-control form-control-sm" value="${skuVal}">
                </td>
                <td>
                    <input type="number" step="0.01" name="variants[${idx}][selling_price]" class="form-control form-control-sm" value="${basePrice}">
                </td>
                <td>
                    <input type="number" step="0.01" name="variants[${idx}][mrp]" class="form-control form-control-sm" value="${baseMrp}">
                </td>
                <td>
                    <input type="number" step="0.01" name="variants[${idx}][wholesale_price]" class="form-control form-control-sm" value="${baseWholesale}">
                </td>
                <td>
                    <input type="number" name="variants[${idx}][stock]" class="form-control form-control-sm" value="50">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm p-1 border-0" onclick="this.closest('tr').remove()">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // 5. Quick AJAX Category Request Submission
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

    // 6. Quick AJAX Brand Request Submission
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

    // 7. Check Brand Protection & Selling Rights
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
