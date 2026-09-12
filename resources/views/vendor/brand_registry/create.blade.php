@extends('layouts.vendor')

@section('title', 'Apply for Brand Registration')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('vendor.brand-registry.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0 text-dark">Brand Registration & Trademark Verification</h4>
            <p class="text-muted mb-0 small">Register your OEM / Manufacturer brand on Garudo to receive Verified OEM Accreditation and protect your products.</p>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please resolve the following errors:</div>
        <ul class="mb-0 small">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('vendor.brand-registry.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <!-- LEFT: Brand Profile & Legal Details -->
        <div class="col-lg-8">
            <!-- SECTION 1: Brand Profile & Visuals -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark">
                    <span class="badge bg-primary text-white rounded-circle me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">1</span>
                    Brand Profile & Identity
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-dark">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="brand_name" class="form-control" placeholder="e.g. Tata Tiscon, UltraTech, Jindal Panther" value="{{ old('brand_name') }}" required>
                        <small class="text-muted">Exact brand name as registered in the Trademark Registry.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Applicant Brand Role <span class="text-danger">*</span></label>
                        <select name="brand_type" class="form-select" required>
                            <option value="oem_manufacturer" {{ old('brand_type') == 'oem_manufacturer' ? 'selected' : '' }}>Original Manufacturer (OEM)</option>
                            <option value="trademark_owner" {{ old('brand_type') == 'trademark_owner' ? 'selected' : '' }}>Trademark Owner / IP Holder</option>
                            <option value="authorized_importer" {{ old('brand_type') == 'authorized_importer' ? 'selected' : '' }}>Exclusive National Importer</option>
                            <option value="private_label" {{ old('brand_type') == 'private_label' ? 'selected' : '' }}>Private Label Brand</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Brand Logo <span class="text-muted">(Square 1:1, Max 3MB)</span></label>
                        <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                        <small class="text-muted">High-res logo with transparent or clean white background.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Brand Showcase Banner <span class="text-muted">(Max 5MB)</span></label>
                        <input type="file" name="banner" class="form-control" accept="image/png,image/jpeg,image/webp">
                        <small class="text-muted">Recommended dimension: 1200x400px.</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Brand Overview & Story</label>
                    <textarea name="brand_story" class="form-control" rows="3" placeholder="Tell B2B buyers about your manufacturing heritage, capacity, quality certifications (ISO/BIS), and vision...">{{ old('brand_story') }}</textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Official Website</label>
                        <input type="url" name="official_website" class="form-control" placeholder="https://brand.com" value="{{ old('official_website') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Country of Origin</label>
                        <input type="text" name="country_of_origin" class="form-control" value="{{ old('country_of_origin', 'India') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Year Established</label>
                        <input type="number" name="year_established" class="form-control" placeholder="e.g. 1995" min="1800" max="{{ date('Y') }}" value="{{ old('year_established') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Industry / Product Categories</label>
                    <select name="category_ids[]" class="form-select" multiple style="min-height: 120px;">
                        @foreach($categories as $parentCat)
                            <optgroup label="{{ $parentCat->name }}">
                                <option value="{{ $parentCat->id }}" {{ in_array($parentCat->id, old('category_ids', [])) ? 'selected' : '' }}>{{ $parentCat->name }} (All)</option>
                                @foreach($parentCat->children as $sub)
                                    <option value="{{ $sub->id }}" {{ in_array($sub->id, old('category_ids', [])) ? 'selected' : '' }}>-- {{ $sub->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple primary categories.</small>
                </div>
            </div>

            <!-- SECTION 2: Legal & Trademark Compliance Documentation -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark">
                    <span class="badge bg-danger text-white rounded-circle me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">2</span>
                    Trademark & Legal Documentation
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Trademark Application / Reg No <span class="text-danger">*</span></label>
                        <input type="text" name="trademark_number" class="form-control" placeholder="e.g. 4892104" value="{{ old('trademark_number') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Trademark Class (1–45) <span class="text-danger">*</span></label>
                        <input type="text" name="trademark_class" class="form-control" placeholder="e.g. Class 6, 19" value="{{ old('trademark_class') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark">Trademark Status <span class="text-danger">*</span></label>
                        <select name="trademark_status" class="form-select" required>
                            <option value="registered" {{ old('trademark_status') == 'registered' ? 'selected' : '' }}>Registered (® Registered TM)</option>
                            <option value="applied_pending" {{ old('trademark_status') == 'applied_pending' ? 'selected' : '' }}>Applied & Pending (™ Application)</option>
                            <option value="licensed" {{ old('trademark_status') == 'licensed' ? 'selected' : '' }}>Licensed by Legal Agreement</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Upload Trademark Certificate / TM Application Receipt <span class="text-danger">*</span></label>
                    <input type="file" name="trademark_doc" class="form-control" accept=".pdf,image/*" required>
                    <small class="text-muted">Upload official Trademark Certificate from Intellectual Property Office / Registry (PDF/Image max 10MB).</small>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Company Incorporation Certificate / GST</label>
                        <input type="file" name="company_incorporation_doc" class="form-control" accept=".pdf,image/*">
                        <small class="text-muted">Proof that the applicant entity owns or represents the brand.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-dark">Power of Attorney / Brand NOC <span class="text-muted">(If applicable)</span></label>
                        <input type="file" name="power_of_attorney_doc" class="form-control" accept=".pdf,image/*">
                        <small class="text-muted">Required if applying on behalf of a parent company.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Distribution Rules & Submission -->
        <div class="col-lg-4">
            <!-- SECTION 3: Distribution & Gating Rules -->
            <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark">
                    <span class="badge bg-success text-white rounded-circle me-2" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">3</span>
                    Catalog Protection Policy
                </h5>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_exclusive_distribution" id="is_exclusive_distribution" value="1" {{ old('is_exclusive_distribution', true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold text-dark small" for="is_exclusive_distribution">
                        Enable Brand Gating (Recommended)
                    </label>
                    <div class="text-muted mt-1" style="font-size: 0.78rem; line-height: 1.4;">
                        When enabled, unauthorized sellers cannot list products under your brand name. Only you and your officially authorized distributors/dealers can sell.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">OEM Warranty & Assurance Policy</label>
                    <textarea name="warranty_policy" class="form-control" rows="3" placeholder="e.g. Standard 10-year structural warranty on manufacturing defects. Free lab test certificates on bulk dispatch.">{{ old('warranty_policy') }}</textarea>
                </div>

                <hr class="text-muted my-3">

                <div class="alert alert-warning py-2 px-3 small mb-3">
                    <i class="bi bi-shield-lock-fill me-1"></i>
                    Your submitted documents will be reviewed by the Garudo Legal & Compliance team within 24–48 hours.
                </div>

                <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm" style="background-color: var(--garudo-primary); border: none;">
                    <i class="bi bi-shield-check me-1"></i> Submit Brand for Verification
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
