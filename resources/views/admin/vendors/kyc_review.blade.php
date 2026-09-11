@extends('layouts.admin')

@section('title', 'KYC & Compliance Workbench - ' . $vendor->company_name)

@section('content')
<div class="mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-light btn-sm rounded-pill px-3 mb-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Vendor Profile
            </a>
            <div class="d-flex align-items-center gap-2">
                <h1 class="page-header-title mb-0">KYC & Compliance Review Workbench</h1>
                <span class="pill-status pill-{{ strtolower($vendor->kyc_status) }}">{{ ucfirst($vendor->kyc_status ?? 'Pending') }}</span>
            </div>
            <p class="text-muted small mb-0 mt-1">Review statutory filings, banking credentials, and documents for <strong>{{ $vendor->company_name }}</strong>.</p>
        </div>

        <!-- Master Decision Actions -->
        <div class="d-flex gap-2">
            @if($vendor->kyc_status !== 'verified')
                <form action="{{ route('admin.vendors.kyc.approve', $vendor->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-patch-check-fill me-1"></i> Approve KYC & Issue Certificate
                    </button>
                </form>

                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectKycModal">
                    <i class="bi bi-x-circle-fill me-1"></i> Reject KYC
                </button>
            @else
                <span class="btn btn-outline-success btn-sm rounded-pill px-3 pe-none fw-bold">
                    <i class="bi bi-check-circle-fill me-1"></i> Verified Channel Partner Active
                </span>
                @if($activeCertificate)
                    <a href="{{ $activeCertificate->verification_url }}" target="_blank" class="btn btn-light btn-sm rounded-pill px-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i> View Live Certificate
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($vendor->kyc_rejected_reason)
    <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
        <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Current Rejection Notice Recorded:</h6>
        <div>{{ $vendor->kyc_rejected_reason }}</div>
    </div>
@endif

<div class="row g-4">
    <!-- Left Column: Business & Bank Identifiers -->
    <div class="col-lg-4">
        <!-- Statutory Profile Card -->
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Statutory & Tax Profile</h6>
            
            <div class="mb-3">
                <div class="text-muted small">GSTIN Number</div>
                <div class="fw-bold fs-6">
                    <code class="text-success">{{ $vendor->gstin ?? 'Not Provided' }}</code>
                </div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Permanent Account Number (PAN)</div>
                <div class="fw-bold fs-6">
                    <code class="text-primary">{{ $vendor->pan_number ?? 'Not Provided' }}</code>
                </div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Entity Classification</div>
                <div class="fw-semibold text-dark">{{ ucfirst($vendor->business_type ?? 'Manufacturer') }}</div>
            </div>

            <div class="mb-3">
                <div class="text-muted small">Year Established</div>
                <div class="fw-semibold text-dark">{{ $vendor->year_established ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Bank Account Verification Card -->
        <div class="garudo-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h6 class="fw-bold text-dark mb-0">Payout Bank Details</h6>
                @if($bankDetails && $bankDetails->is_verified)
                    <span class="badge bg-success small">Verified</span>
                @else
                    <span class="badge bg-warning text-dark small">Unverified</span>
                @endif
            </div>

            @if($bankDetails)
                <div class="mb-2">
                    <div class="text-muted small">Account Holder Name</div>
                    <div class="fw-bold">{{ $bankDetails->account_holder_name }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Account Number</div>
                    <div class="fw-bold font-monospace">{{ $bankDetails->account_number }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Bank Name & Branch</div>
                    <div class="fw-semibold">{{ $bankDetails->bank_name }} ({{ $bankDetails->branch_name ?? 'Main' }})</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">IFSC Code</div>
                    <div class="fw-bold font-monospace text-primary">{{ $bankDetails->ifsc_code }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Account Type</div>
                    <div class="fw-semibold text-capitalize">{{ $bankDetails->account_type }}</div>
                </div>
            @else
                <div class="text-center py-3 text-muted small">
                    <i class="bi bi-bank fs-3 d-block mb-1"></i>
                    No bank details submitted yet.
                </div>
            @endif
        </div>

        <!-- Active Certificate Card (if verified) -->
        @if($activeCertificate)
            <div class="garudo-card p-4 border-success">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-success mb-0"><i class="bi bi-award-fill me-1"></i> Active Certificate</h6>
                    {!! $activeCertificate->status_badge !!}
                </div>
                <div class="small mb-1"><strong>Certificate No:</strong> <code>{{ $activeCertificate->certificate_number }}</code></div>
                <div class="small mb-1"><strong>Issue Date:</strong> {{ $activeCertificate->issue_date->format('d M Y') }}</div>
                <div class="small mb-2"><strong>Valid Until:</strong> {{ $activeCertificate->expiry_date->format('d M Y') }}</div>
                <div class="text-center p-2 bg-white border rounded-3 mb-2">
                    <div style="width: 80px; height: 80px; margin: 0 auto;">
                        {!! $activeCertificate->qr_code_svg !!}
                    </div>
                </div>
                <a href="{{ $activeCertificate->verification_url }}" target="_blank" class="btn btn-outline-success btn-sm w-100 rounded-pill">
                    Open Public Trust Page
                </a>
            </div>
        @endif
    </div>

    <!-- Right Column: Document Inspection Area -->
    <div class="col-lg-8">
        <div class="garudo-card p-4 mb-4">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Submitted Statutory Documents ({{ $documents->count() }})</h6>

            @if($documents->count() > 0)
                <div class="row g-4">
                    @foreach($documents as $doc)
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 bg-light h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-dark mb-0">{{ $doc->document_type_name }}</h6>
                                        {!! $doc->status_badge !!}
                                    </div>
                                    <div class="small text-muted mb-2">
                                        Uploaded on {{ $doc->uploaded_at->format('d M Y, h:i A') }}
                                    </div>

                                    @if($doc->rejection_notes)
                                        <div class="alert alert-danger p-2 small mb-2">
                                            <strong>Note:</strong> {{ $doc->rejection_notes }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                    <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> Inspect File
                                    </a>

                                    <div class="btn-group btn-group-sm">
                                        <form action="{{ route('admin.vendors.kyc.doc_status', [$vendor->id, $doc->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-outline-success {{ $doc->status === 'approved' ? 'active' : '' }}" title="Mark Verified">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-outline-danger {{ $doc->status === 'rejected' ? 'active' : '' }}" title="Reject Document" data-bs-toggle="modal" data-bs-target="#rejectDocModal{{ $doc->id }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Per-Document Reject Modal -->
                        <div class="modal fade" id="rejectDocModal{{ $doc->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('admin.vendors.kyc.doc_status', [$vendor->id, $doc->id]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title fw-bold">Reject Document: {{ $doc->document_type_name }}</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Reason for Rejection / Missing Info:</label>
                                                <textarea name="rejection_notes" class="form-control" rows="3" placeholder="e.g. Blurred photocopy, name mismatch, expired license..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger fw-bold">Save Rejection</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-files fs-1 d-block mb-2 text-secondary"></i>
                    <div>No KYC documents have been uploaded by this vendor yet.</div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Master Reject KYC Modal -->
<div class="modal fade" id="rejectKycModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.vendors.kyc.reject', $vendor->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-shield-x me-2"></i>Reject Vendor KYC Packet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Please specify the exact discrepancies so the vendor can rectify and re-submit their application.</p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Compliance Rejection Notes / Action Items <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" placeholder="e.g. GSTIN certificate does not match the trade name provided, or cancelled cheque is illegible..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-bold">Submit Rejection Notice</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
