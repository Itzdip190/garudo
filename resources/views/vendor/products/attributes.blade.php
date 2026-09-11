@extends('layouts.vendor')

@section('title', 'Product Attributes & Specs')

@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Product Attributes & Specs</h4>
        <p class="text-muted mb-0 small">Standardized material grades, sizes, thicknesses, and technical specs.</p>
    </div>
    <div>
        <button class="btn btn-garudo-primary btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#requestSpecModal" style="background-color: var(--garudo-primary); color: #fff;">
            <i class="bi bi-plus-circle me-1"></i> Request New Specification
        </button>
    </div>
</div>

<!-- ---------------- STANDARD SPECIFICATIONS AVAILABLE ---------------- -->
<div class="card-custom mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0 text-dark">Standard Specifications Available</h6>
        <span class="badge bg-light text-muted border small">{{ count($attributes) }} Active Standards</span>
    </div>
    
    <div class="row g-3">
        @forelse($attributes as $attr)
            <div class="col-md-4">
                <div class="p-3 border rounded h-100 bg-white shadow-xs">
                    <div class="fw-bold text-success mb-1">{{ $attr->display_name }}</div>
                    <div class="small text-muted">
                        @if($attr->values->count() > 0)
                            {{ $attr->values->pluck('display_value')->join(', ') }}
                        @else
                            <span class="fst-italic">Custom / Free Text specification</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-sliders fs-2 d-block mb-1"></i>
                    No standard specifications configured yet.
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- ---------------- VENDOR'S SUBMITTED REQUESTS ---------------- -->
<div class="card-custom">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h6 class="fw-bold mb-0 text-dark">My Specification Requests</h6>
            <p class="text-muted small mb-0">Track custom specifications & grades you requested from the Admin team.</p>
        </div>
        <span class="badge bg-light text-dark border small">{{ count($myRequests) }} Requests</span>
    </div>

    @if(count($myRequests) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light text-muted text-uppercase small">
                    <tr>
                        <th>Requested Spec Name</th>
                        <th>Proposed Values / Grades</th>
                        <th style="min-width: 250px;">Reason / Justification</th>
                        <th>Submitted On</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myRequests as $req)
                        <tr>
                            <td class="fw-bold text-dark">{{ $req->name }}</td>
                            <td>
                                @if($req->values)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach(preg_split('/[,\r\n]+/', $req->values) as $val)
                                            @if(trim($val) !== '')
                                                <span class="badge bg-light text-dark border small">{{ trim($val) }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark small" style="white-space: pre-wrap;">{{ $req->reason }}</div>
                                @if($req->admin_notes)
                                    <div class="mt-1 small {{ $req->status === 'approved' ? 'text-success' : 'text-danger' }}">
                                        <strong>Admin Note:</strong> {{ $req->admin_notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $req->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending Review</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved & Added</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Declined</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-4 bg-light rounded text-center text-muted small">
            <i class="bi bi-chat-square-text fs-4 d-block mb-1 text-secondary"></i>
            You haven't requested any custom specifications yet. If you manufacture or sell materials requiring specific grades not listed above, click <strong>"Request New Specification"</strong>.
        </div>
    @endif
</div>

<!-- ---------------- REQUEST SPECIFICATION MODAL ---------------- -->
<div class="modal fade" id="requestSpecModal" tabindex="-1" aria-labelledby="requestSpecModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('vendor.catalog-requests.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="attribute_spec">

                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="requestSpecModalLabel">
                        <i class="bi bi-sliders text-success me-2"></i>Request New Specification / Attribute
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 small border-0 mb-3" style="background-color: #EBF5FF; color: #1E429F; border-radius: 8px;">
                        <i class="bi bi-info-circle me-1"></i> All catalog specifications are reviewed and approved by the Super Admin to ensure standardized listings across the marketplace.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Specification Name / Attribute <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Binding Wire Gauge, AAC Block Density, Tile Thickness" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Proposed Values / Grades <span class="text-muted">(Comma separated)</span></label>
                        <textarea name="values" class="form-control" rows="2" placeholder="e.g. 16 Gauge, 18 Gauge, 20 Gauge, 22 Gauge">{{ old('values') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Reason for Request <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Please describe why this specification is necessary for your products (e.g. We manufacture heavy-duty galvanised binding wire which requires specific gauge ratings not available currently)..." required minlength="10">{{ old('reason') }}</textarea>
                        <small class="text-muted">A valid reason is required so the admin team can verify and approve it promptly.</small>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-garudo-primary fw-bold px-4" style="background-color: var(--garudo-primary); color: #fff;">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
