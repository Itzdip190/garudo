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
                                <i class="bi bi-plus-circle me-1"></i>+ New Category
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
                                <i class="bi bi-plus-circle me-1"></i>+ New Brand
                            </button>
                        </div>
                        <select name="brand_id" id="brandSelect" class="form-select">
                            <option value="">-- Select Brand (Optional) --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
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

                <div id="customPolicyBox" class="d-none">
                    <label class="form-label fw-semibold small text-dark">Custom Policy / Return Terms</label>
                    <textarea name="custom_policy" class="form-control form-control-sm" rows="4" placeholder="Specify custom terms for this product, e.g. 24-hr moisture damage notice, broken tile compensation with unboxing video, or factory-sealed non-returnable condition.">{{ old('custom_policy') }}</textarea>
                </div>
            </div>

            <script>
            function togglePolicyField(val) {
                const customBox = document.getElementById('customPolicyBox');
                const inheritNotice = document.getElementById('inheritPolicyNotice');
                if (val === 'custom') {
                    customBox.classList.remove('d-none');
                    inheritNotice.classList.add('d-none');
                } else {
                    customBox.classList.add('d-none');
                    inheritNotice.classList.remove('d-none');
                }
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

<!-- Modal: Quick Add Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="addCategoryModalLabel"><i class="bi bi-grid-plus text-success me-2"></i>Create New Category</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickCategoryForm" onsubmit="submitQuickCategory(event)">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Category Name <span class="text-danger">*</span></label>
                        <input type="text" id="newCatName" class="form-control" placeholder="e.g. Ready Mix Concrete (RMC)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Parent Category (Optional)</label>
                        <select id="newCatParent" class="form-select">
                            <option value="">-- No Parent (Root Category) --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Short Description (Optional)</label>
                        <textarea id="newCatDesc" rows="2" class="form-control" placeholder="Brief details about materials under this category..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveCatBtn" class="btn btn-success btn-sm px-4" style="background-color: var(--garudo-primary); border: none;">
                        <i class="bi bi-check-lg me-1"></i> Save & Auto-Select
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Quick Add Brand -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold" id="addBrandModalLabel"><i class="bi bi-patch-check text-success me-2"></i>Create New Brand / Manufacturer</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickBrandForm" onsubmit="submitQuickBrand(event)">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Brand / Manufacturer Name <span class="text-danger">*</span></label>
                        <input type="text" id="newBrandName" class="form-control" placeholder="e.g. Tata Tiscon / Ambuja Cement" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Country of Origin</label>
                        <input type="text" id="newBrandOrigin" class="form-control" value="India">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Website URL (Optional)</label>
                        <input type="url" id="newBrandWebsite" class="form-control" placeholder="https://example.com">
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="saveBrandBtn" class="btn btn-success btn-sm px-4" style="background-color: var(--garudo-primary); border: none;">
                        <i class="bi bi-check-lg me-1"></i> Save & Auto-Select
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

    // 5. Quick AJAX Category Submission
    function submitQuickCategory(e) {
        e.preventDefault();
        const name = document.getElementById('newCatName').value.trim();
        const parent_id = document.getElementById('newCatParent').value;
        const description = document.getElementById('newCatDesc').value.trim();
        const btn = document.getElementById('saveCatBtn');

        if (!name) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

        fetch('{{ route("vendor.categories.quick-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, parent_id, description })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Save & Auto-Select';
            if (data.success) {
                const select = document.getElementById('categorySelect');
                const opt = new Option(data.category.name, data.category.id, true, true);
                select.add(opt);
                select.value = data.category.id;

                const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                modal.hide();
                document.getElementById('quickCategoryForm').reset();
            } else {
                alert('Error creating category: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Save & Auto-Select';
            alert('Request failed: ' + err.message);
        });
    }

    // 6. Quick AJAX Brand Submission
    function submitQuickBrand(e) {
        e.preventDefault();
        const name = document.getElementById('newBrandName').value.trim();
        const origin = document.getElementById('newBrandOrigin').value.trim();
        const website = document.getElementById('newBrandWebsite').value.trim();
        const btn = document.getElementById('saveBrandBtn');

        if (!name) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

        fetch('{{ route("vendor.brands.quick-store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, country_of_origin: origin, website })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Save & Auto-Select';
            if (data.success) {
                const select = document.getElementById('brandSelect');
                const opt = new Option(data.brand.name, data.brand.id, true, true);
                select.add(opt);
                select.value = data.brand.id;

                const modal = bootstrap.Modal.getInstance(document.getElementById('addBrandModal'));
                modal.hide();
                document.getElementById('quickBrandForm').reset();
            } else {
                alert('Error creating brand: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Save & Auto-Select';
            alert('Request failed: ' + err.message);
        });
    }
</script>
@endpush
@endsection
