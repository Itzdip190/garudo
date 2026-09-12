@extends('layouts.admin')

@section('title', 'Inspect Dealer Authorization - ' . $authRecord->vendor->company_name)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.dealer-authorizations.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-2" style="width: 36px; height: 36px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-header-title mb-1">Dealership Inspection: {{ $authRecord->vendor->company_name }}</h1>
            <p class="page-header-subtitle mb-0">Authorized selling rights for Brand <strong>{{ $authRecord->brand->name }}</strong></p>
        </div>
    </div>
    <div>
        @if($authRecord->garudo_approval_status === 'verified')
            <span class="badge bg-success fs-6 px-3 py-2"><i class="bi bi-patch-check-fill me-1"></i> Verified & Active</span>
        @elseif($authRecord->garudo_approval_status === 'pending')
            <span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Pending Verification</span>
        @elseif($authRecord->garudo_approval_status === 'suspended')
            <span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-slash-circle me-1"></i> Authorization Revoked</span>
        @else
            <span class="badge bg-danger fs-6 px-3 py-2"><i class="bi bi-x-circle-fill me-1"></i> Application Rejected</span>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: Application Details & Dealership Scope -->
    <div class="col-lg-7">
        <!-- Dealership Scope Card -->
        <div class="garudo-card p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Authorization & Territory Scope</h5>
            
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <div class="text-muted small">Target Brand</div>
                    <div class="fw-bold text-dark fs-5">{{ $authRecord->brand->name }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Authorization Tier</div>
                    <div class="fw-bold text-primary">{{ $authRecord->formatted_type }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Territory Jurisdiction</div>
                    <div class="fw-bold text-dark">{{ $authRecord->formatted_territory }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Agreement Reference No</div>
                    <div class="fw-bold text-dark">{{ $authRecord->agreement_ref_no ?? 'N/A' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Valid From</div>
                    <div class="fw-bold text-dark">{{ $authRecord->valid_from?->format('d M Y') ?? 'Immediate' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Valid Until</div>
                    <div class="fw-bold text-dark">
                        {{ $authRecord->is_lifetime_valid ? 'Lifetime Validity' : ($authRecord->valid_until?->format('d M Y') ?? 'Open Contract') }}
                    </div>
                </div>
            </div>

            @if($authRecord->brand->ownerVendor)
                <div class="p-3 bg-light rounded border mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small fw-bold text-dark">Brand OEM Approval Status</div>
                            <small class="text-muted">Registered Brand Owner: {{ $authRecord->brand->ownerVendor->company_name }}</small>
                        </div>
                        @if($authRecord->brand_owner_approval === 'approved')
                            <span class="badge bg-success"><i class="bi bi-check-lg"></i> Approved by OEM</span>
                        @elseif($authRecord->brand_owner_approval === 'pending')
                            <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> OEM Approval Pending</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($authRecord->brand_owner_approval) }}</span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Dealership Proof Document -->
            <div>
                <label class="form-label text-muted small fw-bold text-uppercase">Uploaded Dealership Agreement / OEM Letter</label>
                @if($authRecord->authorization_doc_path)
                    <div class="p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf-fill fs-3 text-danger"></i>
                            <div>
                                <div class="fw-bold small text-dark">Dealership Certificate / Invoices Proof</div>
                                <small class="text-muted">Submitted by applicant</small>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $authRecord->authorization_doc_path) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i> View Document
                        </a>
                    </div>
                @else
                    <div class="alert alert-warning py-2 px-3 small">No document attachment found.</div>
                @endif
            </div>
        </div>

        <!-- Applicant Dealer Entity Details -->
        <div class="garudo-card p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-building text-success me-2"></i>Dealer Business Details</h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="text-muted small">Company Name</div>
                    <div class="fw-bold text-dark">{{ $authRecord->vendor->company_name ?? 'N/A' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">GSTIN Registration</div>
                    <div class="fw-bold text-dark">{{ $authRecord->vendor->gstin ?? 'N/A' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Contact Person</div>
                    <div class="fw-bold text-dark">{{ $authRecord->vendor->name ?? 'N/A' }}</div>
                </div>
                <div class="col-sm-6">
                    <div class="text-muted small">Email & Phone</div>
                    <div class="fw-bold text-dark">{{ $authRecord->vendor->email }} • {{ $authRecord->vendor->phone }}</div>
                </div>
            </div>
        </div>

        <!-- Audit Trail Logs -->
        @if($authRecord->logs && $authRecord->logs->count() > 0)
            <div class="garudo-card p-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-clock-history me-2"></i>Audit History</h5>
                <ul class="list-unstyled mb-0">
                    @foreach($authRecord->logs as $log)
                        <li class="mb-2 pb-2 border-bottom small">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">{{ ucwords(str_replace('_', ' ', $log->action)) }}</span>
                                <span class="text-muted">{{ $log->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="text-muted">{{ $log->notes }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- RIGHT: Verification Controls & Digital Certificate -->
    <div class="col-lg-5">
        @if($authRecord->garudo_approval_status === 'verified')
            <!-- Active Certificate Box -->
            <div class="garudo-card p-4 mb-4 border-success">
                <div class="text-center mb-3">
                    <div class="display-6 text-success"><i class="bi bi-award-fill"></i></div>
                    <h5 class="fw-bold text-dark mt-2 mb-1">Official Digital Certificate Active</h5>
                    <code class="fs-6 text-primary fw-bold">{{ $authRecord->certificate_number }}</code>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('certificate.dealer.verify', $authRecord->certificate_number) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="bi bi-qr-code me-1"></i> View Public Verification Page
                    </a>
                </div>

                <hr class="text-muted my-3">

                <!-- Revoke Action -->
                <button type="button" class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="collapse" data-bs-target="#revokeCollapse">
                    <i class="bi bi-slash-circle me-1"></i> Revoke / Suspend Authorization
                </button>

                <div class="collapse mt-2" id="revokeCollapse">
                    <form action="{{ route('admin.dealer-authorizations.revoke', $authRecord->id) }}" method="POST" class="p-3 bg-light rounded border border-danger-subtle">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label text-danger small fw-bold">Reason for Revocation</label>
                            <textarea name="reason" class="form-control form-control-sm" rows="2" placeholder="e.g. Contract terminated by OEM." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold">Confirm Revoke</button>
                    </form>
                </div>
            </div>
        @else
            <!-- Approve & Issue Certificate Form -->
            <div class="garudo-card p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-check2-circle text-success me-2"></i>Verify & Issue Certificate</h5>

                <form action="{{ route('admin.dealer-authorizations.approve', $authRecord->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Valid From Date</label>
                        <input type="date" name="valid_from" class="form-control form-control-sm" value="{{ $authRecord->valid_from ? $authRecord->valid_from->format('Y-m-d') : date('Y-m-d') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Valid Until Date</label>
                        <input type="date" name="valid_until" class="form-control form-control-sm" value="{{ $authRecord->valid_until ? $authRecord->valid_until->format('Y-m-d') : date('Y-m-d', strtotime('+1 year')) }}">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_lifetime_valid" id="is_lifetime_valid" value="1" {{ $authRecord->is_lifetime_valid ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-dark small" for="is_lifetime_valid">
                            Lifetime Valid Authorization
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Admin Review Notes</label>
                        <textarea name="admin_notes" class="form-control form-control-sm" rows="2" placeholder="e.g. Verified dealership contract with OEM. Territory confirmed."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold" onclick="return confirm('Verify this dealer and issue official Certificate ID & QR Code?');">
                        <i class="bi bi-patch-check-fill me-1"></i> Approve & Issue Certificate
                    </button>
                </form>

                <hr class="text-muted my-3">

                <!-- Reject Button -->
                <button type="button" class="btn btn-outline-danger w-100 py-2 small" data-bs-toggle="collapse" data-bs-target="#rejectCollapse">
                    <i class="bi bi-x-circle me-1"></i> Reject Application
                </button>

                <div class="collapse mt-3" id="rejectCollapse">
                    <form action="{{ route('admin.dealer-authorizations.reject', $authRecord->id) }}" method="POST" class="p-3 bg-light rounded border border-danger-subtle">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label text-danger small fw-bold">Rejection Reason</label>
                            <textarea name="rejection_reason" class="form-control form-control-sm" rows="2" placeholder="e.g. Agreement document illegible or invalid territory." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold">Confirm Rejection</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
