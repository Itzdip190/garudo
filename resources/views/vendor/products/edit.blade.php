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
                        <label class="form-label fw-semibold small text-dark">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small text-dark">Brand</label>
                        <select name="brand_id" class="form-select">
                            <option value="">-- Select Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
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
</script>
@endpush
@endsection
