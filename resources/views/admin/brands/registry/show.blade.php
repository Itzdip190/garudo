@extends('layouts.admin')

@section('title', 'Review Brand Application - ' . $application->brand_name)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.brands.registry.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-header-title mb-1">Review Brand: {{ $application->brand_name }}</h1>
            <p class="page-header-subtitle mb-0">Submitted by <strong>{{ $application->vendor->company_name ?? 'N/A' }}</strong> on {{ $application->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>
    <div>
        @if($application->status === 'approved')
            <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> Authorized Brand OEM</span>
        @elseif($application->status === 'pending')
            <span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Pending Verification</span>
        @elseif($application->status === 'rejected')
            <span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-x-circle-fill me-1"></i> Application Rejected</span>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: Brand Profile & Applicant Details -->
    <div class="col-lg-7">
        <!-- Brand Visual & Identity -->
        <div class="garudo-card p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-award-fill text-primary me-2"></i>Brand Identity & Visuals</h5>
            <div class="d-flex gap-3 align-items-start mb-4">
                @if($application->logo_path)
                    <img src="{{ asset('storage/' . $application->logo_path) }}" alt="{{ $application->brand_name }}" class="rounded border p-2 bg-white" style="width: 90px; height: 90px; object-fit: contain;">
                @else
                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 90px; height: 90px; font-size: 2rem;">
                        {{ substr($application->brand_name, 0, 2) }}
                    </div>
                @endif
                <div>
                    <h3 class="fw-bold text-dark mb-1">{{ $application->brand_name }}</h3>
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                            <i class="bi bi-shield-check me-1"></i>{{ ucwords(str_replace('_', ' ', $application->brand_type)) }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-globe me-1"></i>Origin: {{ $application->country_of_origin ?? 'India' }}
                        </span>
                        @if($application->year_established)
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-calendar-check me-1"></i>Est: {{ $application->year_established }}
                            </span>
                        @endif
                    </div>
                    @if($application->official_website)
                        <a href="{{ $application->official_website }}" target="_blank" class="small text-decoration-none text-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>{{ $application->official_website }}
                        </a>
                    @endif
                </div>
            </div>

            @if($application->banner_path)
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold text-uppercase">Brand Banner Showcase</label>
                    <img src="{{ asset('storage/' . $application->banner_path) }}" alt="Banner" class="img-fluid rounded border" style="max-height: 180px; width: 100%; object-fit: cover;">
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label text-muted small fw-bold text-uppercase">Brand Story & Overview</label>
                <div class="p-3 bg-light rounded border text-secondary" style="line-height: 1.6;">
                    {{ $application->brand_story ?? 'No detailed story provided.' }}
                </div>
            </div>

            @if($application->warranty_policy)
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold text-uppercase">OEM Warranty & Assurance Policy</label>
                    <div class="p-3 bg-light rounded border text-secondary small">
                        {{ $application->warranty_policy }}
                    </div>
                </div>
            @endif

            <div>
                <label class="form-label text-muted small fw-bold text-uppercase">Target Product Categories</label>
                <div class="d-flex flex-wrap gap-2">
                    @forelse($selectedCategories as $cat)
                        <span class="badge bg-secondary-subtle text-dark border px-2 py-1"><i class="bi bi-tag-fill text-muted me-1"></i>{{ $cat->name }}</span>
                    @empty
                        <span class="text-muted small">All Platform Categories</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Applicant Vendor & Legal Profile -->
        <div class="garudo-card p-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-building-check text-success me-2"></i>Applicant Legal Entity</h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="text-muted small">Company Name</div>
                    <div class="fw-bold text-dark">{{ $application->vendor->company_name ?? 'N/A' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">GSTIN Registration</div>
                    <div class="fw-bold text-dark">{{ $application->vendor->gstin ?? 'N/A' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Official Email</div>
                    <div class="fw-bold text-dark">{{ $application->official_email ?? $application->vendor->email }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Contact Phone</div>
                    <div class="fw-bold text-dark">{{ $application->official_phone ?? $application->vendor->phone }}</div>
                </div>
            </div>

            <hr class="my-3 text-muted">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small fw-bold text-dark">Vendor KYC & Verification Status</div>
                    <small class="text-muted">Account Status: {{ ucfirst($application->vendor->approval_status ?? 'N/A') }}</small>
                </div>
                @if($application->vendor && $application->vendor->isVerifiedPartner())
                    <span class="badge bg-success"><i class="bi bi-shield-fill-check me-1"></i>KYC Verified Partner</span>
                @else
                    <span class="badge bg-warning text-dark"><i class="bi bi-shield-exclamation me-1"></i>KYC Incomplete</span>
                @endif
            </div>
        </div>
    </div>

    <!-- RIGHT: Legal & Trademark Compliance & Action Controls -->
    <div class="col-lg-5">
        <!-- Trademark & Compliance Documents -->
        <div class="garudo-card p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-shield-lock-fill text-danger me-2"></i>Trademark & Legal Docs</h5>
            
            <div class="p-3 bg-light rounded border mb-3">
                <div class="row g-2 small">
                    <div class="col-6 text-muted">Trademark No:</div>
                    <div class="col-6 fw-bold text-dark">{{ $application->trademark_number ?? 'N/A' }}</div>
                    <div class="col-6 text-muted">Trademark Class:</div>
                    <div class="col-6 fw-bold text-dark">Class {{ $application->trademark_class ?? '-' }}</div>
                    <div class="col-6 text-muted">TM Status:</div>
                    <div class="col-6 fw-bold text-success">{{ ucfirst($application->trademark_status) }}</div>
                </div>
            </div>

            <!-- Download / Inspect Links -->
            <div class="list-group list-group-flush border rounded mb-3">
                @if($application->trademark_doc_path)
                    <a href="{{ asset('storage/' . $application->trademark_doc_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf-fill fs-4 text-danger"></i>
                            <div>
                                <div class="fw-bold small text-dark">Trademark Registration Certificate</div>
                                <small class="text-muted">Mandatory Legal Proof</small>
                            </div>
                        </div>
                        <i class="bi bi-box-arrow-up-right text-primary"></i>
                    </a>
                @endif

                @if($application->company_incorporation_doc_path)
                    <a href="{{ asset('storage/' . $application->company_incorporation_doc_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-check-fill fs-4 text-primary"></i>
                            <div>
                                <div class="fw-bold small text-dark">Company Incorporation Certificate / GST</div>
                                <small class="text-muted">Entity verification</small>
                            </div>
                        </div>
                        <i class="bi bi-box-arrow-up-right text-primary"></i>
                    </a>
                @endif

                @if($application->power_of_attorney_doc_path)
                    <a href="{{ asset('storage/' . $application->power_of_attorney_doc_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-text-fill fs-4 text-warning"></i>
                            <div>
                                <div class="fw-bold small text-dark">Power of Attorney / Brand NOC</div>
                                <small class="text-muted">Authorization letter</small>
                            </div>
                        </div>
                        <i class="bi bi-box-arrow-up-right text-primary"></i>
                    </a>
                @endif
            </div>

            <div class="alert alert-info py-2 px-3 small mb-0">
                <i class="bi bi-info-circle-fill me-1"></i>
                Verify the TM Number on the official IP India / Trademark Registry before approving OEM status.
            </div>
        </div>

        <!-- Authorization Action Controls -->
        <div class="garudo-card p-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-check2-circle text-success me-2"></i>Accreditation Actions</h5>

            @if($application->status !== 'approved')
                <form action="{{ route('admin.brands.registry.approve', $application->id) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_protected" id="is_protected" value="1" {{ $application->is_exclusive_distribution ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-dark small" for="is_protected">
                            Enable Brand Protection & Gating
                        </label>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            Only authorized distributors/dealers and this Brand OEM will be permitted to list products under this brand name.
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1">
                        <label class="form-check-label fw-bold text-dark small" for="is_featured">
                            Featured Brand on Homepage Directory
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Admin Verification Notes (Optional)</label>
                        <textarea name="admin_notes" class="form-control form-control-sm" rows="2" placeholder="e.g. TM Class 19 verified via IP India. Verified OEM badge granted."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold" onclick="return confirm('Authorize this Brand and grant official Verified OEM Status?');">
                        <i class="bi bi-patch-check-fill me-1"></i> Authorize & Grant Verified OEM Badge
                    </button>
                </form>

                <hr class="text-muted my-3">

                <!-- Reject Option -->
                <button type="button" class="btn btn-outline-danger w-100 py-2 small" data-bs-toggle="collapse" data-bs-target="#rejectCollapse">
                    <i class="bi bi-x-circle me-1"></i> Reject Application
                </button>

                <div class="collapse mt-3" id="rejectCollapse">
                    <form action="{{ route('admin.brands.registry.reject', $application->id) }}" method="POST" class="p-3 bg-light rounded border border-danger-subtle">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label text-danger small fw-bold">Rejection Reason (Required)</label>
                            <textarea name="rejection_reason" class="form-control form-control-sm" rows="2" placeholder="e.g. Trademark certificate mismatch or expired document." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold">Confirm Rejection</button>
                    </form>
                </div>
            @else
                <div class="alert alert-success py-3 px-3 mb-3">
                    <div class="fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Officially Authorized Brand</div>
                    <p class="small mb-1 mt-1">Verified on {{ $application->reviewed_at?->format('d M Y') ?? 'N/A' }} by {{ $application->reviewer->name ?? 'Admin' }}.</p>
                    @if($application->createdBrand)
                        <a href="{{ route('brands.show', $application->createdBrand->slug) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill mt-2">
                            <i class="bi bi-eye me-1"></i> View Live Brand Page
                        </a>
                    @endif
                </div>

                @if($application->createdBrand)
                    <form action="{{ route('admin.brands.toggle-protection', $application->createdBrand->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $application->createdBrand->is_protected ? 'btn-outline-danger' : 'btn-outline-success' }} w-100">
                            <i class="bi {{ $application->createdBrand->is_protected ? 'bi-lock-fill' : 'bi-unlock-fill' }} me-1"></i>
                            {{ $application->createdBrand->is_protected ? 'Disable Gating (Make Open)' : 'Enable Gating (Protect Brand)' }}
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
