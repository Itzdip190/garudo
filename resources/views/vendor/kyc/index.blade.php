@extends('layouts.vendor')

@section('title', 'Vendor KYC & Trust Verification')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2">
            <h4 class="fw-bold mb-0" style="color: var(--garudo-text-main);">KYC & Channel Partner Verification</h4>
            @if($vendor->isVerifiedPartner())
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                    <i class="bi bi-patch-check-fill me-1"></i> Verified Partner
                </span>
            @elseif($vendor->kyc_status === 'submitted')
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">
                    <i class="bi bi-hourglass-split me-1"></i> Verification in Progress
                </span>
            @elseif($vendor->kyc_status === 'rejected')
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Action Required
                </span>
            @else
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">
                    <i class="bi bi-clock-history me-1"></i> Incomplete (Step {{ $vendor->kyc_step ?? 1 }}/4)
                </span>
            @endif
        </div>
        <p class="text-muted mb-0 small mt-1">Complete your statutory compliance to receive your 1-Year Free Membership & Verified Channel Partner Digital Certificate.</p>
    </div>

    @if($vendor->activeCertificate)
        <div>
            <a href="{{ route('vendor.certificate.show') }}" class="btn btn-outline-success rounded-pill fw-bold shadow-sm">
                <i class="bi bi-award-fill me-1"></i> View Digital Certificate
            </a>
        </div>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($vendor->kyc_rejected_reason)
    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 p-4">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-shield-x fs-2 text-danger"></i>
            <div>
                <h6 class="fw-bold mb-1">Compliance Team Feedback / Correction Needed:</h6>
                <p class="mb-0 text-dark">{{ $vendor->kyc_rejected_reason }}</p>
                <div class="small text-muted mt-2">Please update the requested information or re-upload clear document copies below and click <strong>Submit Verification</strong>.</div>
            </div>
        </div>
    </div>
@endif

<!-- Stepper Progress Header -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-body p-4">
        <div class="row text-center g-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('vendor.kyc.index', ['step' => 1]) }}" class="text-decoration-none d-block p-2 rounded-3 {{ $step == 1 ? 'bg-light fw-bold text-success' : 'text-muted' }}">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 rounded-circle {{ $vendor->kyc_step > 1 ? 'bg-success text-white' : ($step == 1 ? 'bg-success text-white' : 'bg-secondary-subtle text-muted') }}" style="width: 38px; height: 38px;">
                        @if($vendor->kyc_step > 1)<i class="bi bi-check-lg"></i>@else 1 @endif
                    </div>
                    <div class="small">1. Enterprise Details</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('vendor.kyc.index', ['step' => 2]) }}" class="text-decoration-none d-block p-2 rounded-3 {{ $step == 2 ? 'bg-light fw-bold text-success' : 'text-muted' }}">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 rounded-circle {{ $vendor->kyc_step > 2 ? 'bg-success text-white' : ($step == 2 ? 'bg-success text-white' : 'bg-secondary-subtle text-muted') }}" style="width: 38px; height: 38px;">
                        @if($vendor->kyc_step > 2)<i class="bi bi-check-lg"></i>@else 2 @endif
                    </div>
                    <div class="small">2. GST & PAN</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('vendor.kyc.index', ['step' => 3]) }}" class="text-decoration-none d-block p-2 rounded-3 {{ $step == 3 ? 'bg-light fw-bold text-success' : 'text-muted' }}">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 rounded-circle {{ $vendor->kyc_step > 3 ? 'bg-success text-white' : ($step == 3 ? 'bg-success text-white' : 'bg-secondary-subtle text-muted') }}" style="width: 38px; height: 38px;">
                        @if($vendor->kyc_step > 3)<i class="bi bi-check-lg"></i>@else 3 @endif
                    </div>
                    <div class="small">3. Payout Bank</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('vendor.kyc.index', ['step' => 4]) }}" class="text-decoration-none d-block p-2 rounded-3 {{ $step == 4 ? 'bg-light fw-bold text-success' : 'text-muted' }}">
                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 rounded-circle {{ $vendor->kyc_status === 'verified' || $vendor->kyc_status === 'submitted' ? 'bg-success text-white' : ($step == 4 ? 'bg-success text-white' : 'bg-secondary-subtle text-muted') }}" style="width: 38px; height: 38px;">
                        @if($vendor->kyc_status === 'verified')<i class="bi bi-patch-check-fill"></i>@else 4 @endif
                    </div>
                    <div class="small">4. Documents & Submit</div>
                </a>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="progress mt-3" style="height: 6px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $vendor->kyc_progress_percentage }}%;"></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Form Area -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            @if($step == 1)
                <!-- Step 1: Enterprise Profile Details -->
                <form action="{{ route('vendor.kyc.store_step') }}" method="POST">
                    @csrf
                    <input type="hidden" name="step" value="1">
                    
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-building me-2 text-success"></i>Step 1: Enterprise & Business Profile</h5>
                    <p class="text-muted small mb-4">Provide official business registration details as registered with the Ministry of Corporate Affairs / Registrar of Firms.</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Company / Trade Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $vendor->company_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Business Entity Type <span class="text-danger">*</span></label>
                            <select name="business_type" class="form-select" required>
                                <option value="manufacturer" {{ old('business_type', $vendor->business_type) == 'manufacturer' ? 'selected' : '' }}>Manufacturer / OEM</option>
                                <option value="wholesaler" {{ old('business_type', $vendor->business_type) == 'wholesaler' ? 'selected' : '' }}>Wholesaler / Bulk Trader</option>
                                <option value="distributor" {{ old('business_type', $vendor->business_type) == 'distributor' ? 'selected' : '' }}>Authorized Distributor</option>
                                <option value="trader" {{ old('business_type', $vendor->business_type) == 'trader' ? 'selected' : '' }}>Trader / Dealer</option>
                                <option value="retailer" {{ old('business_type', $vendor->business_type) == 'retailer' ? 'selected' : '' }}>Retailer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Year of Establishment</label>
                            <input type="number" name="year_established" class="form-control" placeholder="e.g. 2015" min="1900" max="{{ date('Y') }}" value="{{ old('year_established', $vendor->year_established) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Official Website</label>
                            <input type="url" name="company_website" class="form-control" placeholder="https://example.com" value="{{ old('company_website', $vendor->company_website) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Company Description & Specialization</label>
                            <textarea name="company_description" class="form-control" rows="3" placeholder="Briefly describe your products, production capacity, and distribution reach...">{{ old('company_description', $vendor->company_description) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold" style="background-color: var(--garudo-primary); border:none;">
                            Save & Continue to Step 2 <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>

            @elseif($step == 2)
                <!-- Step 2: Statutory & Tax Identifiers -->
                <form action="{{ route('vendor.kyc.store_step') }}" method="POST">
                    @csrf
                    <input type="hidden" name="step" value="2">

                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-receipt me-2 text-success"></i>Step 2: Statutory Tax & Legal Identifiers</h5>
                    <p class="text-muted small mb-4">Enter your GSTIN and Company/Proprietor PAN. These numbers will be verified against the National Tax Database.</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">15-Digit GSTIN Number <span class="text-danger">*</span></label>
                            <input type="text" name="gstin" class="form-control text-uppercase" placeholder="27AAAAA0000A1Z5" maxlength="15" value="{{ old('gstin', $vendor->gstin) }}" required>
                            <div class="form-text small">State Code + PAN + Entity Number + Z + Checksum</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">10-Digit PAN Number <span class="text-danger">*</span></label>
                            <input type="text" name="pan_number" class="form-control text-uppercase" placeholder="ABCDE1234F" maxlength="10" value="{{ old('pan_number', $vendor->pan_number) }}" required>
                            <div class="form-text small">Permanent Account Number (Company or Proprietor)</div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <a href="{{ route('vendor.kyc.index', ['step' => 1]) }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold" style="background-color: var(--garudo-primary); border:none;">
                            Save & Continue to Step 3 <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>

            @elseif($step == 3)
                <!-- Step 3: Payout Bank Account Details -->
                <form action="{{ route('vendor.kyc.store_step') }}" method="POST">
                    @csrf
                    <input type="hidden" name="step" value="3">

                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bank me-2 text-success"></i>Step 3: Payout Settlement Bank Account</h5>
                    <p class="text-muted small mb-4">All order earnings and settlements are deposited directly into this verified current/savings account.</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Account Holder Legal Name <span class="text-danger">*</span></label>
                            <input type="text" name="account_holder_name" class="form-control" placeholder="Name as per Bank Statement" value="{{ old('account_holder_name', $bankDetails->account_holder_name ?? $vendor->company_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Account Type <span class="text-danger">*</span></label>
                            <select name="account_type" class="form-select" required>
                                <option value="current" {{ old('account_type', $bankDetails->account_type ?? '') == 'current' ? 'selected' : '' }}>Current Account (Recommended for B2B)</option>
                                <option value="savings" {{ old('account_type', $bankDetails->account_type ?? '') == 'savings' ? 'selected' : '' }}>Savings Account</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Account Number <span class="text-danger">*</span></label>
                            <input type="text" name="account_number" class="form-control" placeholder="e.g. 50200012345678" value="{{ old('account_number', $bankDetails->account_number ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">11-Character IFSC Code <span class="text-danger">*</span></label>
                            <input type="text" name="ifsc_code" class="form-control text-uppercase" placeholder="e.g. HDFC0001234" maxlength="11" value="{{ old('ifsc_code', $bankDetails->ifsc_code ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Bank Name <span class="text-danger">*</span></label>
                            <input type="text" name="bank_name" class="form-control" placeholder="e.g. HDFC Bank / State Bank of India" value="{{ old('bank_name', $bankDetails->bank_name ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Branch Name</label>
                            <input type="text" name="branch_name" class="form-control" placeholder="e.g. Industrial Estate Branch" value="{{ old('branch_name', $bankDetails->branch_name ?? '') }}">
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                        <a href="{{ route('vendor.kyc.index', ['step' => 2]) }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold" style="background-color: var(--garudo-primary); border:none;">
                            Save & Continue to Step 4 <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </form>

            @elseif($step == 4)
                <!-- Step 4: Documents Upload & Final Submission -->
                <form action="{{ route('vendor.kyc.store_step') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <input type="hidden" name="step" value="4">

                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-check me-2 text-success"></i>Step 4: Upload Verification Documents</h5>
                    <p class="text-muted small mb-4">Upload scanned copies or clear photos of your statutory documents (PDF, JPG, PNG up to 5MB each).</p>

                    <div class="row g-4">
                        <!-- PAN Card Document -->
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small"><i class="bi bi-card-heading text-primary me-1"></i> PAN Card Copy <span class="text-danger">*</span></span>
                                    @if(isset($documents['pan']))
                                        {!! $documents['pan']->status_badge !!}
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted">Not Uploaded</span>
                                    @endif
                                </div>
                                <input type="file" name="pan_document" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                                @if(isset($documents['pan']))
                                    <div class="mt-2 small d-flex justify-content-between align-items-center">
                                        <a href="{{ $documents['pan']->file_url }}" target="_blank" class="text-primary text-decoration-none">
                                            <i class="bi bi-eye me-1"></i> View Current File ({{ Str::limit($documents['pan']->file_name, 20) }})
                                        </a>
                                        <span class="text-muted">{{ $documents['pan']->uploaded_at->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- GSTIN Certificate Document -->
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small"><i class="bi bi-file-earmark-ruled text-primary me-1"></i> GST Registration Cert. <span class="text-danger">*</span></span>
                                    @if(isset($documents['gstin_certificate']))
                                        {!! $documents['gstin_certificate']->status_badge !!}
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted">Not Uploaded</span>
                                    @endif
                                </div>
                                <input type="file" name="gstin_document" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                                @if(isset($documents['gstin_certificate']))
                                    <div class="mt-2 small d-flex justify-content-between align-items-center">
                                        <a href="{{ $documents['gstin_certificate']->file_url }}" target="_blank" class="text-primary text-decoration-none">
                                            <i class="bi bi-eye me-1"></i> View Current File ({{ Str::limit($documents['gstin_certificate']->file_name, 20) }})
                                        </a>
                                        <span class="text-muted">{{ $documents['gstin_certificate']->uploaded_at->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Cancelled Cheque / Bank Statement -->
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small"><i class="bi bi-receipt-cutoff text-primary me-1"></i> Cancelled Cheque / Statement</span>
                                    @if(isset($documents['cancelled_cheque']))
                                        {!! $documents['cancelled_cheque']->status_badge !!}
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted">Optional</span>
                                    @endif
                                </div>
                                <input type="file" name="cancelled_cheque_document" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                                @if(isset($documents['cancelled_cheque']))
                                    <div class="mt-2 small d-flex justify-content-between align-items-center">
                                        <a href="{{ $documents['cancelled_cheque']->file_url }}" target="_blank" class="text-primary text-decoration-none">
                                            <i class="bi bi-eye me-1"></i> View Current File ({{ Str::limit($documents['cancelled_cheque']->file_name, 20) }})
                                        </a>
                                        <span class="text-muted">{{ $documents['cancelled_cheque']->uploaded_at->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Trade License / Incorporation Certificate -->
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small"><i class="bi bi-award text-primary me-1"></i> Trade License / COI</span>
                                    @if(isset($documents['trade_license']))
                                        {!! $documents['trade_license']->status_badge !!}
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted">Optional</span>
                                    @endif
                                </div>
                                <input type="file" name="trade_license_document" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                                @if(isset($documents['trade_license']))
                                    <div class="mt-2 small d-flex justify-content-between align-items-center">
                                        <a href="{{ $documents['trade_license']->file_url }}" target="_blank" class="text-primary text-decoration-none">
                                            <i class="bi bi-eye me-1"></i> View Current File ({{ Str::limit($documents['trade_license']->file_name, 20) }})
                                        </a>
                                        <span class="text-muted">{{ $documents['trade_license']->uploaded_at->format('d M Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success px-4 rounded-pill fw-bold">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Save Uploaded Files
                        </button>
                    </div>
                </form>

                <!-- Final Verification Submit Card -->
                <div class="bg-primary-subtle border border-primary-subtle rounded-4 p-4 mt-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h6 class="fw-bold text-primary mb-1"><i class="bi bi-send-check me-2"></i>Ready to Submit for Compliance Verification?</h6>
                            <p class="small text-muted mb-0">Our compliance officers verify GSTIN active status, PAN linkage, and banking parameters within 24 hours.</p>
                        </div>
                        <form action="{{ route('vendor.kyc.submit') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow" style="background-color: var(--garudo-primary); border:none;" {{ $vendor->kyc_status === 'submitted' ? 'disabled' : '' }}>
                                @if($vendor->kyc_status === 'submitted')
                                    <i class="bi bi-check-circle-fill me-1"></i> Verification Submitted
                                @else
                                    <i class="bi bi-shield-check me-1"></i> Submit for Verification
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Sidebar: Benefits & Summary Card -->
    <div class="col-lg-4">
        <!-- Verification Summary Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Verification Summary</h6>
            
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Company</span>
                <span class="fw-bold small">{{ Str::limit($vendor->company_name, 22) }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">GSTIN</span>
                <span class="fw-bold small font-monospace">{{ $vendor->gstin ?? 'Not set' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">PAN</span>
                <span class="fw-bold small font-monospace">{{ $vendor->pan_number ?? 'Not set' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Bank Account</span>
                <span class="fw-bold small">{{ $bankDetails ? $bankDetails->masked_account_number : 'Not set' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small">Documents</span>
                <span class="badge bg-light text-dark border">{{ $vendor->kycDocuments->count() }} Attached</span>
            </div>
        </div>

        <!-- Verified Channel Partner Benefits Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #0B4D3C 0%, #15803D 100%);">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-patch-check-fill fs-4 text-warning"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Verified Channel Partner</h6>
                    <small class="text-white-50">Annual Membership Privileges</small>
                </div>
            </div>

            <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                <li class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-warning"></i> Official Digital Certificate of Authenticity
                </li>
                <li class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-warning"></i> 1-Year Free Premium Marketplace Access
                </li>
                <li class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-warning"></i> Standalone QR Code Verification Landing Page
                </li>
                <li class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-warning"></i> Priority Placement in Buyer RFP Quotations
                </li>
                <li class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-warning"></i> Verified Trust Seal on All Product Listings
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
