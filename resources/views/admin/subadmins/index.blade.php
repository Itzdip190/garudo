@extends('layouts.admin')

@section('title', 'Subadmins & Access Control')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="page-header-title d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock-fill text-success"></i>
            Subadmins & Staff Management
        </h1>
        <p class="page-header-subtitle mb-0">Create staff login credentials and control granular module access permissions across the Garudo platform.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.subadmins.create') }}" class="btn btn-garudo-primary d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-person-plus-fill"></i>
            <span>+ Create New Subadmin</span>
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- ---------------- STATS OVERVIEW CARDS ---------------- -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="garudo-card p-3 border-start border-4 border-success h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Total Admin Accounts</div>
                    <div class="fs-4 fw-bolder text-dark mt-1">{{ $stats['total'] }}</div>
                    <div class="small text-muted">{{ $stats['super_admins'] }} Root / {{ $stats['subadmins'] }} Subadmins</div>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-circle">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="garudo-card p-3 border-start border-4 border-primary h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Active Subadmins</div>
                    <div class="fs-4 fw-bolder text-primary mt-1">{{ $stats['active'] }}</div>
                    <div class="small text-success">Can log in & manage</div>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                    <i class="bi bi-check2-circle fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="garudo-card p-3 border-start border-4 border-warning h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Inactive / Suspended</div>
                    <div class="fs-4 fw-bolder text-warning mt-1">{{ $stats['inactive'] }}</div>
                    <div class="small text-muted">Login blocked</div>
                </div>
                <div class="bg-warning-subtle text-warning p-3 rounded-circle">
                    <i class="bi bi-pause-circle-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="garudo-card p-3 border-start border-4 border-info h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold text-uppercase">Permission Modules</div>
                    <div class="fs-4 fw-bolder text-info mt-1">20 Modules</div>
                    <div class="small text-muted">Granular access control</div>
                </div>
                <div class="bg-info-subtle text-info p-3 rounded-circle">
                    <i class="bi bi-key-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ---------------- SEARCH & FILTER BAR ---------------- -->
<div class="garudo-card p-3 mb-4">
    <form action="{{ route('admin.subadmins.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, login email, phone..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.subadmins.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- ---------------- SUBADMINS TABLE ---------------- -->
<div class="garudo-card overflow-hidden">
    <div class="table-responsive">
        <table class="table admin-table mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 250px;">Subadmin Profile</th>
                    <th>Login ID (Email)</th>
                    <th>Role & Assigned Access</th>
                    <th>Status</th>
                    <th>Last Active</th>
                    <th class="text-end" style="width: 220px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle {{ $admin->is_super_admin ? 'bg-warning text-dark' : 'bg-primary text-white' }} fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 42px; height: 42px; font-size: 1.1rem;">
                                    @if($admin->is_super_admin)
                                        <i class="bi bi-star-fill text-dark"></i>
                                    @else
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                        {{ $admin->name }}
                                        @if($admin->is_super_admin)
                                            <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">SUPER ADMIN</span>
                                        @endif
                                        @if($admin->id === auth('admin')->id())
                                            <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">(You)</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">
                                        <i class="bi bi-telephone text-muted me-1"></i>{{ $admin->phone ?? 'No phone' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark font-monospace">{{ $admin->email }}</div>
                            <div class="small text-muted">Created {{ $admin->created_at ? $admin->created_at->format('d M, Y') : 'N/A' }}</div>
                        </td>
                        <td>
                            @if($admin->is_super_admin)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="bi bi-shield-fill-check me-1"></i> Full System Access (Unrestricted)
                                </span>
                            @else
                                @php
                                    $perms = $admin->getAllPermissions();
                                    $permCount = $perms->count();
                                @endphp
                                <div class="d-flex flex-wrap align-items-center gap-1">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                        {{ $admin->role_display_name }}
                                    </span>
                                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#viewPermsModal{{ $admin->id }}">
                                        <span class="badge bg-secondary-subtle text-dark border">
                                            <i class="bi bi-eye me-1"></i> {{ $permCount }} {{ Str::plural('permission', $permCount) }}
                                        </span>
                                    </button>
                                </div>

                                <!-- View Permissions Modal -->
                                <div class="modal fade" id="viewPermsModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title fs-6">
                                                    <i class="bi bi-shield-check text-success me-2"></i>
                                                    Access Permissions: {{ $admin->name }} ({{ $admin->email }})
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4" style="max-height: 450px; overflow-y: auto;">
                                                @if($permCount === 0)
                                                    <div class="text-center py-4 text-muted">
                                                        <i class="bi bi-exclamation-circle fs-2 d-block mb-2"></i>
                                                        No permissions assigned yet. Click "Edit & Permissions" to grant access.
                                                    </div>
                                                @else
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($perms as $p)
                                                            <div class="badge bg-light text-dark border p-2 text-start d-flex align-items-center gap-2">
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                                <div>
                                                                    <div class="fw-bold">{{ $p->display_name }}</div>
                                                                    <div class="text-muted" style="font-size: 0.7rem;">{{ $p->name }}</div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <a href="{{ route('admin.subadmins.edit', $admin->id) }}" class="btn btn-sm btn-garudo-primary">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit Permissions
                                                </a>
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($admin->status === 'active')
                                <span class="pill-status pill-active">Active</span>
                            @elseif($admin->status === 'suspended')
                                <span class="pill-status pill-suspended">Suspended</span>
                            @else
                                <span class="pill-status pill-rejected">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($admin->last_login_at)
                                <div class="text-dark small fw-semibold">{{ $admin->last_login_at->diffForHumans() }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">IP: {{ $admin->last_login_ip ?? 'Unknown' }}</div>
                            @else
                                <span class="text-muted small">Never logged in</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                <!-- Edit Subadmin & Permissions -->
                                <a href="{{ route('admin.subadmins.edit', $admin->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Profile & Permissions">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <!-- Fast Reset Password Button -->
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $admin->id }}" title="Reset Password">
                                    <i class="bi bi-key-fill"></i>
                                </button>

                                @if(!$admin->is_super_admin)
                                    <!-- Toggle Active / Inactive -->
                                    <form action="{{ route('admin.subadmins.toggle_status', $admin->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $admin->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $admin->status === 'active' ? 'Deactivate Subadmin' : 'Activate Subadmin' }}">
                                            <i class="bi {{ $admin->status === 'active' ? 'bi-pause-fill' : 'bi-play-fill' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Subadmin -->
                                    @if($admin->id !== auth('admin')->id())
                                        <form action="{{ route('admin.subadmins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete subadmin \'{{ $admin->name }}\'? This will revoke their login access completely.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Subadmin">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>

                            <!-- Reset Password Modal -->
                            <div class="modal fade" id="resetPasswordModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ route('admin.subadmins.reset_password', $admin->id) }}" method="POST" class="modal-content border-0 shadow">
                                        @csrf
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title fs-6">
                                                <i class="bi bi-key-fill text-warning me-2"></i>
                                                Reset Password: {{ $admin->name }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start">
                                            <p class="small text-muted mb-3">Set a new secure login password for this subadmin. They will use this password alongside their login ID (<strong>{{ $admin->email }}</strong>).</p>
                                            
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">New Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="new_password" id="modal_pass_{{ $admin->id }}" class="form-control" placeholder="Minimum 6 characters" required minlength="6">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassVisibility('modal_pass_{{ $admin->id }}')">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-garudo-primary">Save New Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people fs-1 text-secondary d-block mb-2"></i>
                                <h5>No Subadmins Found</h5>
                                <p class="small">Click the button below to create your first subadmin account and grant them specific access.</p>
                                <a href="{{ route('admin.subadmins.create') }}" class="btn btn-garudo-primary btn-sm mt-2">
                                    + Create First Subadmin
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
        <div class="p-3 border-top">
            {{ $admins->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function togglePassVisibility(inputId) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>
@endpush
