<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor & Brand Registration — GARUDO B2B</title>
    <link rel="icon" type="image/png" href="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --garudo-primary: #0B4D3C;
            --garudo-primary-hover: #083D30;
            --garudo-primary-light: #E8F7F0;
            --garudo-primary-text: #0B7B58;
            --garudo-bg: #F8FAFC;
            --garudo-border: #E2E8F0;
            --garudo-text-main: #0F172A;
            --garudo-text-muted: #64748B;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--garudo-bg);
            color: var(--garudo-text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.25rem;
        }

        .auth-card {
            background: #FFFFFF;
            border: 1px solid var(--garudo-border);
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08);
            width: 100%;
            max-width: 760px;
            padding: 2.5rem 2.25rem;
        }

        .brand-logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .brand-name {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--garudo-primary);
            line-height: 1.1;
            margin: 0;
        }

        .brand-tagline {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--garudo-text-muted);
            text-transform: uppercase;
        }

        /* 2-Option Pathway Selector Cards */
        .reg-type-card {
            border: 2px solid var(--garudo-border);
            border-radius: 12px;
            padding: 1.15rem 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #FFFFFF;
            height: 100%;
            position: relative;
        }

        .reg-type-card:hover {
            border-color: #94A3B8;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }

        .reg-type-card.selected {
            border-color: var(--garudo-primary);
            background: var(--garudo-primary-light);
            box-shadow: 0 0 0 3px rgba(11, 77, 60, 0.12);
        }

        .reg-type-card .icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 0.75rem;
        }

        .reg-type-card.selected .icon-circle {
            background-color: var(--garudo-primary);
            color: #FFFFFF;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid var(--garudo-border);
            padding: 0.65rem 0.95rem;
            font-size: 0.875rem;
            transition: all 0.15s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--garudo-primary);
            box-shadow: 0 0 0 3px rgba(11, 77, 60, 0.12);
        }

        .form-label {
            font-size: 0.813rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .btn-vendor-primary {
            background-color: var(--garudo-primary);
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            border: none;
            width: 100%;
            transition: background 0.15s;
        }

        .btn-vendor-primary:hover {
            background-color: var(--garudo-primary-hover);
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="brand-logo-wrap text-decoration-none justify-content-center mb-3" title="Garudo B2B Marketplace">
                <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" style="height: 52px; max-width: 230px; object-fit: contain;">
            </a>
            <h4 class="fw-bold mt-3 mb-1" style="color: var(--garudo-text-main);">Join Garudo Marketplace</h4>
            <p class="text-muted small">Choose your business model to get started with verified seller privileges.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger p-3 small rounded-3 mb-4">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i>Please check the required fields:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger p-3 small rounded-3 mb-4">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('vendor.register.submit') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
            @csrf

            <!-- ---------------- STEP 1: PATHWAY SELECTOR ---------------- -->
            <label class="form-label fw-bold text-dark mb-2">Select Registration Category <span class="text-danger">*</span></label>
            <div class="row g-3 mb-4">
                <!-- Option 1: Standard Vendor -->
                <div class="col-md-6">
                    <div class="reg-type-card {{ old('registration_type', 'standard_vendor') === 'standard_vendor' ? 'selected' : '' }}" 
                         id="cardVendor" onclick="selectRegType('standard_vendor')">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="icon-circle bg-light text-success">
                                <i class="bi bi-shop-window"></i>
                            </div>
                            <input class="form-check-input" type="radio" name="registration_type" id="typeVendor" value="standard_vendor" 
                                   {{ old('registration_type', 'standard_vendor') === 'standard_vendor' ? 'checked' : '' }}>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Register as a Vendor</h6>
                        <p class="text-muted small mb-0">For Wholesalers, Distributors, Retailers, and Material Stockists selling products across India.</p>
                    </div>
                </div>

                <!-- Option 2: Brand Owner / Manufacturer -->
                <div class="col-md-6">
                    <div class="reg-type-card {{ old('registration_type') === 'brand_owner' ? 'selected' : '' }}" 
                         id="cardBrand" onclick="selectRegType('brand_owner')">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="icon-circle bg-light text-primary">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <input class="form-check-input" type="radio" name="registration_type" id="typeBrand" value="brand_owner" 
                                   {{ old('registration_type') === 'brand_owner' ? 'checked' : '' }}>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Register as a Brand</h6>
                        <p class="text-muted small mb-0">For Trademark Owners, OEMs, and Manufacturers launching a certified brand on Garudo.</p>
                    </div>
                </div>
            </div>

            <!-- ---------------- COMMON CONTACT INFORMATION ---------------- -->
            <div class="p-3 bg-light rounded-3 border mb-4">
                <span class="small fw-bold text-dark d-block mb-3"><i class="bi bi-person-badge text-secondary me-1"></i> Authorized Contact Person</span>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Ramesh Kumar" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number (OTP Enabled) <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Official Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="contact@company.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
            </div>

            <!-- ---------------- SECTION A: VENDOR / DEALER FIELDS ---------------- -->
            <div id="vendorFieldsSection" class="{{ old('registration_type', 'standard_vendor') === 'brand_owner' ? 'd-none' : '' }}">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-building text-success me-2"></i>Business & Dealership Profile</h6>

                <div class="row g-3 mb-4">
                    <div class="col-md-7">
                        <label class="form-label">Company / Enterprise / Shop Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" id="vendorCompanyName" class="form-control" placeholder="e.g. Shree Balaji Traders" value="{{ old('company_name') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Business Nature <span class="text-danger">*</span></label>
                        <select name="business_type" class="form-select">
                            <option value="trader" {{ old('business_type') == 'trader' ? 'selected' : '' }}>Trader / Retailer</option>
                            <option value="wholesaler" {{ old('business_type') == 'wholesaler' ? 'selected' : '' }}>Wholesaler / Stockist</option>
                            <option value="distributor" {{ old('business_type') == 'distributor' ? 'selected' : '' }}>Authorized Distributor</option>
                            <option value="manufacturer" {{ old('business_type') == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                        </select>
                    </div>
                </div>

                <!-- Dealership Authorization Question -->
                <div class="p-3 border rounded-3 bg-white mb-4">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="sells_existing_brand" value="1" id="sellsBrandCheck" 
                               {{ old('sells_existing_brand') == '1' ? 'checked' : '' }} onchange="toggleDealershipModule()">
                        <label class="form-check-label fw-bold text-dark small" for="sellsBrandCheck">
                            Do you sell products of any existing Brand on Garudo? (e.g. Tata, UltraTech, JSW)
                        </label>
                    </div>
                    <small class="text-muted d-block">If you are an Authorized Dealer of a registered brand, you can attach your Dealership Certificate now for instant approval.</small>

                    <!-- Dealership Proof Inputs -->
                    <div id="dealershipProofModule" class="mt-3 pt-3 border-top {{ old('sells_existing_brand') == '1' ? '' : 'd-none' }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Brand You Represent <span class="text-danger">*</span></label>
                                <select name="dealership_brand_id" id="dealershipBrandSelect" class="form-select">
                                    <option value="">-- Choose Brand --</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b->id }}" {{ old('dealership_brand_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->name }} {{ $b->is_protected ? '(Protected)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Territory Jurisdiction</label>
                                <select name="territory_state" class="form-select">
                                    <option value="">-- Select State --</option>
                                    @foreach($indianStates as $st)
                                        <option value="{{ $st }}" {{ old('territory_state') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dealership Agreement / Ref Number</label>
                                <input type="text" name="agreement_ref_no" class="form-control" placeholder="e.g. DLR/TATA/2026/089" value="{{ old('agreement_ref_no') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Valid Until (Leave blank if Lifetime)</label>
                                <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Upload Dealership Authorization Certificate / Letter</label>
                                <input type="file" name="authorization_doc" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Upload company authorization letter on brand letterhead (PDF/Image up to 10MB). Super Admin will verify this before granting selling rights.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---------------- SECTION B: BRAND OWNER / MANUFACTURER FIELDS ---------------- -->
            <div id="brandFieldsSection" class="{{ old('registration_type') === 'brand_owner' ? '' : 'd-none' }}">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-award-fill text-warning me-2"></i>New Brand & Legal Trademark Verification</h6>

                <!-- Brand Hierarchy Choice -->
                <div class="p-3 bg-light border rounded-3 mb-3">
                    <label class="form-label fw-bold text-dark small mb-2">Brand Hierarchy Tier <span class="text-danger">*</span></label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="brand_tier" id="brandTierSuper" value="super_brand" 
                                   {{ old('brand_tier', 'super_brand') === 'super_brand' ? 'checked' : '' }} onchange="toggleBrandTierUI()">
                            <label class="form-check-label fw-semibold small" for="brandTierSuper">
                                Independent Super Brand (Master Brand)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="brand_tier" id="brandTierSub" value="sub_brand" 
                                   {{ old('brand_tier') === 'sub_brand' ? 'checked' : '' }} onchange="toggleBrandTierUI()">
                            <label class="form-check-label fw-semibold small" for="brandTierSub">
                                Sub Brand (under existing Parent)
                            </label>
                        </div>
                    </div>

                    <div id="parentSuperBrandDiv" class="mt-3 {{ old('brand_tier') === 'sub_brand' ? '' : 'd-none' }}">
                        <label class="form-label small fw-semibold">Select Parent Super Brand <span class="text-danger">*</span></label>
                        <select name="parent_brand_id" id="parentBrandIdSelect" class="form-select">
                            <option value="">-- Choose Existing Super Brand --</option>
                            @foreach($superBrands as $sb)
                                <option value="{{ $sb->id }}" {{ old('parent_brand_id') == $sb->id ? 'selected' : '' }}>
                                    {{ $sb->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Brand / Trade Name <span class="text-danger">*</span></label>
                        <input type="text" name="brand_name" id="brandNameInput" class="form-control" placeholder="e.g. BuildMax TMT" value="{{ old('brand_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Brand Type <span class="text-danger">*</span></label>
                        <select name="brand_type" class="form-select">
                            <option value="oem_manufacturer" {{ old('brand_type') == 'oem_manufacturer' ? 'selected' : '' }}>OEM Manufacturer</option>
                            <option value="trademark_owner" {{ old('brand_type') == 'trademark_owner' ? 'selected' : '' }}>Trademark Owner</option>
                            <option value="authorized_importer" {{ old('brand_type') == 'authorized_importer' ? 'selected' : '' }}>Authorized Importer</option>
                            <option value="private_label" {{ old('brand_type') == 'private_label' ? 'selected' : '' }}>Private Label</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Brand Logo (Square)</label>
                        <input type="file" name="brand_logo" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Official Website</label>
                        <input type="url" name="official_website" class="form-control" placeholder="https://brand.com" value="{{ old('official_website') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Brand Story & Overview</label>
                        <textarea name="brand_story" rows="2" class="form-control" placeholder="Tell us about the manufacturing history, production capacity, and standards...">{{ old('brand_story') }}</textarea>
                    </div>
                </div>

                <!-- LEGAL TRADEMARK MANDATORY PROOF -->
                <div class="p-3 border rounded-3 bg-white mb-4 border-start border-4 border-warning">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-warning me-2"></i>Mandatory Legal Verification Documents</h6>
                    <p class="text-muted small mb-3">To protect brand authenticity and prevent counterfeit listings, Super Admin verifies all trademark certificates before brand approval.</p>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Trademark Number <span class="text-danger">*</span></label>
                            <input type="text" name="trademark_number" id="tmNumberInput" class="form-control" placeholder="e.g. 5421980" value="{{ old('trademark_number') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trademark Class <span class="text-danger">*</span></label>
                            <input type="text" name="trademark_class" id="tmClassInput" class="form-control" placeholder="e.g. Class 19 / Class 6" value="{{ old('trademark_class') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trademark Status <span class="text-danger">*</span></label>
                            <select name="trademark_status" class="form-select">
                                <option value="registered" {{ old('trademark_status') == 'registered' ? 'selected' : '' }}>Registered (Certified)</option>
                                <option value="applied_pending" {{ old('trademark_status') == 'applied_pending' ? 'selected' : '' }}>Applied / Pending Slip</option>
                                <option value="licensed" {{ old('trademark_status') == 'licensed' ? 'selected' : '' }}>Licensed / Franchise</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Upload Trademark Certificate / Form TM-A <span class="text-danger">*</span></label>
                            <input type="file" name="trademark_doc" id="tmDocInput" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Government Trademark Certificate or Application Acknowledgement.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Upload Company Incorporation / GST / Factory License <span class="text-danger">*</span></label>
                            <input type="file" name="company_incorporation_doc" id="incorpDocInput" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">GSTIN, CIN certificate, MSME Udyam, or Factory License.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Consent -->
            <div class="form-check my-3">
                <input class="form-check-input" type="checkbox" id="termsCheck" required checked>
                <label class="form-check-label text-muted small" for="termsCheck">
                    I certify that all provided details and documents are genuine and agree to Garudo's Seller Terms of Service.
                </label>
            </div>

            <button class="btn btn-vendor-primary mb-3" type="submit" id="submitBtn">
                <i class="bi bi-shield-lock-fill me-1"></i> Complete Registration
            </button>

            <div class="text-center pt-2 border-top">
                <p class="text-muted small mb-2">Already have a registered account?</p>
                <a href="{{ route('vendor.login') }}" class="fw-bold text-success text-decoration-none small">
                    Sign In to Seller Command Center →
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function selectRegType(type) {
        document.getElementById('typeVendor').checked = (type === 'standard_vendor');
        document.getElementById('typeBrand').checked = (type === 'brand_owner');

        const cardVendor = document.getElementById('cardVendor');
        const cardBrand = document.getElementById('cardBrand');
        const vendorSection = document.getElementById('vendorFieldsSection');
        const brandSection = document.getElementById('brandFieldsSection');
        const vendorCompanyName = document.getElementById('vendorCompanyName');
        const brandNameInput = document.getElementById('brandNameInput');
        const tmNumberInput = document.getElementById('tmNumberInput');
        const tmClassInput = document.getElementById('tmClassInput');
        const tmDocInput = document.getElementById('tmDocInput');
        const incorpDocInput = document.getElementById('incorpDocInput');
        const submitBtn = document.getElementById('submitBtn');

        if (type === 'brand_owner') {
            cardBrand.classList.add('selected');
            cardVendor.classList.remove('selected');
            brandSection.classList.remove('d-none');
            vendorSection.classList.add('d-none');

            // Set required attributes for Brand Owner
            brandNameInput.setAttribute('required', 'required');
            tmNumberInput.setAttribute('required', 'required');
            tmClassInput.setAttribute('required', 'required');
            tmDocInput.setAttribute('required', 'required');
            incorpDocInput.setAttribute('required', 'required');
            vendorCompanyName.setAttribute('required', 'required');
            vendorCompanyName.placeholder = "e.g. BuildMax Steels Pvt Ltd (Manufacturing Entity)";

            submitBtn.innerHTML = '<i class="bi bi-award-fill me-1"></i> Submit Brand for Super Admin Verification';
        } else {
            cardVendor.classList.add('selected');
            cardBrand.classList.remove('selected');
            vendorSection.classList.remove('d-none');
            brandSection.classList.add('d-none');

            // Remove required attributes from Brand Owner
            brandNameInput.removeAttribute('required');
            tmNumberInput.removeAttribute('required');
            tmClassInput.removeAttribute('required');
            tmDocInput.removeAttribute('required');
            incorpDocInput.removeAttribute('required');
            vendorCompanyName.setAttribute('required', 'required');
            vendorCompanyName.placeholder = "e.g. Shree Balaji Traders";

            submitBtn.innerHTML = '<i class="bi bi-shop-window me-1"></i> Complete Vendor Registration';
        }
    }

    function toggleDealershipModule() {
        const checked = document.getElementById('sellsBrandCheck').checked;
        const module = document.getElementById('dealershipProofModule');
        const brandSelect = document.getElementById('dealershipBrandSelect');

        if (checked) {
            module.classList.remove('d-none');
            brandSelect.setAttribute('required', 'required');
        } else {
            module.classList.add('d-none');
            brandSelect.removeAttribute('required');
            brandSelect.value = '';
        }
    }

    function toggleBrandTierUI() {
        const isSub = document.getElementById('brandTierSub').checked;
        const parentDiv = document.getElementById('parentSuperBrandDiv');
        const parentSelect = document.getElementById('parentBrandIdSelect');

        if (isSub) {
            parentDiv.classList.remove('d-none');
            parentSelect.setAttribute('required', 'required');
        } else {
            parentDiv.classList.add('d-none');
            parentSelect.removeAttribute('required');
            parentSelect.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const currentType = document.querySelector('input[name="registration_type"]:checked')?.value || 'standard_vendor';
        selectRegType(currentType);
    });
    </script>
</body>
</html>
