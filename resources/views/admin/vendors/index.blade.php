@extends('layouts.admin')

@section('title', 'Vendors & Sellers Management')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title">Vendors & Sellers Control</h1>
        <p class="page-header-subtitle mb-0">Onboard, verify business KYC, approve stores & monitor seller compliance.</p>
    </div>
</div>

<!-- ---------------- STATS PILLS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.vendors.index') }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ !request('status') ? 'border-primary' : '' }}">
                <div class="text-muted small fw-bold text-uppercase">Total Sellers</div>
                <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.vendors.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('status') === 'pending' ? 'border-warning' : '' }}">
                <div class="text-warning small fw-bold text-uppercase">Pending Approvals</div>
                <div class="fs-4 fw-bold text-warning mt-1">{{ $stats['pending'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.vendors.index', ['status' => 'approved']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('status') === 'approved' ? 'border-success' : '' }}">
                <div class="text-success small fw-bold text-uppercase">Verified Active</div>
                <div class="fs-4 fw-bold text-success mt-1">{{ $stats['approved'] }}</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.vendors.index', ['status' => 'suspended']) }}" class="text-decoration-none">
            <div class="garudo-card p-3 {{ request('status') === 'suspended' ? 'border-danger' : '' }}">
                <div class="text-danger small fw-bold text-uppercase">Suspended</div>
                <div class="fs-4 fw-bold text-danger mt-1">{{ $stats['suspended'] }}</div>
            </div>
        </a>
    </div>
</div>

<!-- ---------------- FILTER & SEARCH BAR ---------------- -->
<div class="garudo-card p-3 mb-4">
    <form action="{{ route('admin.vendors.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Company, Contact, GSTIN, Email..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Verification Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved & Live</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-garudo-primary w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
        </div>
        @if(request()->hasAny(['search', 'status']))
            <div class="col-md-2">
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        @endif
    </form>
</div>

<!-- ---------------- VENDORS TABLE ---------------- -->
<div class="garudo-card p-0 overflow-hidden">
    @if($vendors->count() > 0)
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Seller / Enterprise</th>
                        <th>Contact & Email</th>
                        <th>GSTIN / Tax ID</th>
                        <th>Products</th>
                        <th>Approval Status</th>
                        <th>Account</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 42px; height: 42px;">
                                        {{ strtoupper(substr($vendor->company_name ?? $vendor->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $vendor->company_name ?? $vendor->name }}</div>
                                        <div class="text-muted small">{{ $vendor->business_type ?? 'Manufacturer' }} • Est. {{ $vendor->year_established ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $vendor->phone ?? 'N/A' }}</div>
                                <div class="text-muted small">{{ $vendor->email }}</div>
                            </td>
                            <td>
                                @if($vendor->gstin)
                                    <code class="bg-light px-2 py-1 rounded text-dark">{{ $vendor->gstin }}</code>
                                @else
                                    <span class="text-muted small">Not Provided</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">{{ $vendor->products_count }} Listed</span>
                            </td>
                            <td>
                                <span class="pill-status pill-{{ strtolower($vendor->approval_status) }}">
                                    {{ ucfirst($vendor->approval_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="pill-status pill-{{ strtolower($vendor->status) }}">
                                    {{ ucfirst($vendor->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.vendors.show', $vendor->id) }}">
                                                <i class="bi bi-eye text-primary me-2"></i> Inspect Store & KYC
                                            </a>
                                        </li>
                                        @if($vendor->approval_status !== 'approved')
                                            <li>
                                                <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="bi bi-check-circle me-2"></i> Approve & Activate
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li>
                                            <button type="button" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#resetVendorPassModal{{ $vendor->id }}">
                                                <i class="bi bi-key me-2"></i> Reset Password
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.vendors.toggle_status', $vendor->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="bi bi-slash-circle me-2"></i> {{ $vendor->status === 'active' ? 'Suspend Account' : 'Reactivate Account' }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Reset Password Modal -->
                                <div class="modal fade text-start" id="resetVendorPassModal{{ $vendor->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content border-0 shadow">
                                            <form action="{{ route('admin.vendors.reset_password', $vendor->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header bg-dark text-white">
                                                    <h5 class="modal-title fw-bold"><i class="bi bi-key-fill text-warning me-2"></i>Reset Password for {{ $vendor->company_name ?? $vendor->name }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted small">Enter a new secure password for this seller. They will use this password to sign into the Vendor Portal.</p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">New Password</label>
                                                        <input type="text" name="password" class="form-control" placeholder="Enter new password (e.g. Vendor@2026#Secure)" value="Vendor@{{ rand(1000, 9999) }}#" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-garudo-primary fw-bold">Update & Hash Password</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $vendors->links() }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-shop fs-1 mb-2 d-block"></i>
            <h5>No Vendors Found</h5>
            <p class="small">Try adjusting your filters or search terms.</p>
        </div>
    @endif
</div>
@endsection
