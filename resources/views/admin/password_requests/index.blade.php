@extends('layouts.admin')

@section('title', 'Password Reset Requests')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">
            <i class="bi bi-key-fill text-warning me-2"></i> Password Reset Requests
        </h1>
        <p class="page-header-subtitle mb-0">
            Offline password assistance: Issue temporary demo passwords for Customers & Vendors. The user will be required to choose a new password upon first sign-in.
        </p>
    </div>
</div>

@if(session('demo_password'))
    <div class="alert alert-success border-0 shadow-sm p-3 mb-4 rounded-3" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-left: 5px solid #10B981 !important;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="fw-bold text-success fs-6 mb-1">
                    <i class="bi bi-check-circle-fill me-1"></i> Temporary Demo Password Generated Successfully!
                </div>
                <div class="text-dark small">
                    Share these credentials with the {{ ucfirst(session('user_type', 'user')) }} via WhatsApp, SMS, or Phone:
                </div>
                <div class="mt-2 d-flex flex-wrap align-items-center gap-2">
                    <span class="badge bg-white text-dark border px-2 py-1 font-monospace">Email: <strong>{{ session('user_email') }}</strong></span>
                    <span class="badge bg-warning text-dark px-2 py-1 font-monospace fs-6">Demo Password: <strong id="flashDemoPass">{{ session('demo_password') }}</strong></span>
                    <span class="badge bg-white text-muted border px-2 py-1">Login: {{ session('user_type') === 'vendor' ? url('/vendor/login') : url('/account/login') }}</span>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-dark btn-sm px-3 fw-bold" onclick="copyCredentials('{{ session('user_email') }}', '{{ session('demo_password') }}', '{{ session('user_type') === 'vendor' ? url('/vendor/login') : url('/account/login') }}')">
                    <i class="bi bi-clipboard-check me-1"></i> Copy Full Text
                </button>
            </div>
        </div>
    </div>
@endif

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3 {{ $pendingCount > 0 ? 'border-warning' : '' }}">
            <div class="text-warning small fw-bold text-uppercase d-flex justify-content-between">
                <span>Pending Requests</span>
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $pendingCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Requires Super Admin Action</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3">
            <div class="text-success small fw-bold text-uppercase d-flex justify-content-between">
                <span>Approved (Issued)</span>
                <i class="bi bi-check2-circle"></i>
            </div>
            <div class="fs-3 fw-bold text-success mt-1">{{ $approvedCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Temporary Passwords Set</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3">
            <div class="text-danger small fw-bold text-uppercase d-flex justify-content-between">
                <span>Rejected</span>
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="fs-3 fw-bold text-danger mt-1">{{ $rejectedCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">Declined Requests</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="garudo-card p-3">
            <div class="text-muted small fw-bold text-uppercase d-flex justify-content-between">
                <span>Total Received</span>
                <i class="bi bi-collection"></i>
            </div>
            <div class="fs-3 fw-bold text-dark mt-1">{{ $totalCount }}</div>
            <small class="text-muted" style="font-size: 0.72rem;">All historical requests</small>
        </div>
    </div>
</div>

<!-- ---------------- SEARCH & FILTERS ---------------- -->
<div class="garudo-card p-3 mb-4">
    <form action="{{ route('admin.password-requests.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Email, Name, or Phone..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending Only</option>
                <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Approved Only</option>
                <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected Only</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>All Portals</option>
                <option value="customer" {{ $typeFilter === 'customer' ? 'selected' : '' }}>Customer (Buyer)</option>
                <option value="vendor" {{ $typeFilter === 'vendor' ? 'selected' : '' }}>Vendor (Seller)</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-garudo-primary w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- ---------------- REQUESTS TABLE ---------------- -->
<div class="garudo-card p-0 overflow-hidden">
    @if($requests->count() > 0)
        <div class="table-responsive">
            <table class="table admin-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Portal / User</th>
                        <th>Registered Email</th>
                        <th>Phone / Notes</th>
                        <th>Status</th>
                        <th>Demo Password</th>
                        <th>Requested At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($req->user_type === 'vendor')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                            <i class="bi bi-shop me-1"></i> Vendor
                                        </span>
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">
                                            <i class="bi bi-person-fill me-1"></i> Customer
                                        </span>
                                    @endif
                                    <span class="fw-bold text-dark">{{ $req->name ?: 'User' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="font-monospace fw-semibold">{{ $req->email }}</span>
                            </td>
                            <td>
                                @if($req->phone)
                                    <div><i class="bi bi-telephone me-1 text-muted"></i>{{ $req->phone }}</div>
                                @endif
                                @if($req->admin_notes)
                                    <small class="text-muted text-truncate d-block" style="max-width: 200px;" title="{{ $req->admin_notes }}">
                                        {{ $req->admin_notes }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="pill-status pill-pending">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @elseif($req->status === 'approved')
                                    <span class="pill-status pill-approved">
                                        <i class="bi bi-check-circle-fill"></i> Approved
                                    </span>
                                @else
                                    <span class="pill-status pill-rejected">
                                        <i class="bi bi-x-circle-fill"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($req->temp_password)
                                    <div class="d-flex align-items-center gap-1">
                                        <code class="bg-light px-2 py-1 rounded text-dark fw-bold border font-monospace">{{ $req->temp_password }}</code>
                                        <button type="button" class="btn btn-sm btn-outline-secondary p-1 px-2 border-0" title="Copy Password" onclick="navigator.clipboard.writeText('{{ $req->temp_password }}'); alert('Password copied: {{ $req->temp_password }}');">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $req->created_at->format('d M Y, h:i A') }}
                                <div style="font-size: 0.7rem;">{{ $req->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- Approve Button -->
                                    <button type="button" class="btn btn-sm {{ $req->status === 'pending' ? 'btn-success' : 'btn-outline-success' }}" data-bs-toggle="modal" data-bs-target="#approveModal{{ $req->id }}" title="{{ $req->status === 'pending' ? 'Approve & Issue Demo Password' : 'Re-issue Demo Password' }}">
                                        <i class="bi bi-key-fill me-1"></i> {{ $req->status === 'pending' ? 'Approve' : 'Re-issue' }}
                                    </button>

                                    @if($req->status === 'pending')
                                        <!-- Reject Button -->
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}" title="Reject Request">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.password-requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Delete this request record?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-muted p-1" title="Delete Log">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Approve Modal -->
                                <div class="modal fade text-start" id="approveModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.password-requests.approve', $req->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="bi bi-key-fill text-success me-1"></i> 
                                                        Approve & Set Demo Password
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="small text-muted mb-3">
                                                        Setting a demo password updates the user's password in the database and marks <code>must_change_password = true</code>. When they log in with this demo password, they will be prompted to set their new permanent password.
                                                    </p>
                                                    <div class="mb-3 bg-light p-3 rounded-3">
                                                        <div class="small text-muted">Target Account:</div>
                                                        <div class="fw-bold text-dark">{{ $req->name ?: 'User' }} ({{ ucfirst($req->user_type) }})</div>
                                                        <div class="font-monospace text-muted small">{{ $req->email }}</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Custom Demo Password (Optional)</label>
                                                        <input type="text" name="demo_password" class="form-control font-monospace" placeholder="Leave empty to auto-generate (e.g. Garudo@{{ rand(1000, 9999) }})">
                                                        <small class="text-muted" style="font-size: 0.75rem;">If left blank, an automatic readable password like <code>Garudo@4829</code> will be generated.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Admin Note / Record</label>
                                                        <textarea name="admin_notes" class="form-control" rows="2" placeholder="e.g., Verified via phone call with manager.">{{ $req->admin_notes }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                                                        <i class="bi bi-check-circle me-1"></i> Confirm & Issue Demo Password
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @if($req->status === 'pending')
                                    <!-- Reject Modal -->
                                    <div class="modal fade text-start" id="rejectModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.password-requests.reject', $req->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold text-danger">
                                                            <i class="bi bi-x-circle me-1"></i> Reject Password Request
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="small text-muted mb-3">
                                                            Are you sure you want to decline this password reset request for <strong>{{ $req->email }}</strong>?
                                                        </p>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small">Reason for Rejection</label>
                                                            <textarea name="rejection_reason" class="form-control" rows="3" placeholder="e.g., Email address not found in system or unverified caller." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Decline Request</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="p-3 border-top">
                {{ $requests->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <div class="fs-1 text-muted mb-2"><i class="bi bi-inbox"></i></div>
            <h5 class="fw-bold text-dark mb-1">No Password Reset Requests Found</h5>
            <p class="text-muted small">
                {{ $statusFilter !== 'all' || $search !== '' ? 'No requests match your current filters.' : 'No customer or vendor has requested password assistance.' }}
            </p>
        </div>
    @endif
</div>

<script>
function copyCredentials(email, password, loginUrl) {
    const text = `Garudo B2B Marketplace Credentials:\n\nEmail: ${email}\nTemporary Password: ${password}\nLogin URL: ${loginUrl}\n\nNote: You will be asked to set your new personal password upon first login.`;
    navigator.clipboard.writeText(text).then(() => {
        alert('Credentials copied to clipboard! You can now paste and send them to the user via WhatsApp or SMS.');
    }).catch(err => {
        prompt('Copy your credentials text:', text);
    });
}
</script>
@endsection
