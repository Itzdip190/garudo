@extends('layouts.admin')

@section('title', 'Edit Subadmin - ' . $subadmin->name)

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="mb-1">
            <a href="{{ route('admin.subadmins.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Back to Subadmins Directory
            </a>
        </div>
        <h1 class="page-header-title d-flex align-items-center gap-2">
            <i class="bi bi-pencil-square text-primary"></i>
            Edit Subadmin: {{ $subadmin->name }}
        </h1>
        <p class="page-header-subtitle mb-0">Update account credentials and customize module access permissions for this staff member.</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <h6 class="alert-heading fw-bold d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:
        </h6>
        <ul class="mb-0 ps-3 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('admin.subadmins.update', $subadmin->id) }}" method="POST" id="subadminForm">
    @csrf
    @method('PUT')

    <div class="row g-4 mb-4">
        <!-- ---------------- SECTION 1: ACCOUNT CREDENTIALS ---------------- -->
        <div class="col-12 col-xl-4">
            <div class="garudo-card p-4 h-100 position-sticky" style="top: 90px;">
                <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-3">
                    <div class="avatar-circle {{ $subadmin->is_super_admin ? 'bg-warning text-dark' : 'bg-primary text-white' }} fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        @if($subadmin->is_super_admin)
                            <i class="bi bi-star-fill text-dark"></i>
                        @else
                            {{ strtoupper(substr($subadmin->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $subadmin->name }}</h6>
                        <span class="badge {{ $subadmin->is_super_admin ? 'bg-warning text-dark' : 'bg-info-subtle text-info border' }}">
                            {{ $subadmin->role_display_name }}
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $subadmin->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Login Email ID <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $subadmin->email) }}" required>
                    </div>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Contact Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-telephone text-muted"></i></span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $subadmin->phone) }}">
                    </div>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="p-3 bg-light rounded border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold text-dark mb-0">Change Password (Optional)</label>
                        <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none small" onclick="generateRandomPassword()">
                            <i class="bi bi-magic me-1"></i> Generate
                        </button>
                    </div>
                    <div class="text-muted small mb-2" style="font-size: 0.72rem;">Leave fields blank if you do not want to alter the current password.</div>
                    
                    <div class="mb-2">
                        <div class="input-group">
                            <input type="password" name="password" id="adminPasswordInput" class="form-control form-control-sm @error('password') is-invalid @enderror" placeholder="New password (min 6 chars)">
                            <button class="btn btn-sm btn-outline-secondary" type="button" onclick="togglePasswordVisibility('adminPasswordInput')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <input type="password" name="password_confirmation" id="adminPasswordConfirmInput" class="form-control form-control-sm" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Account Status <span class="text-danger">*</span></label>
                    @if($subadmin->is_super_admin)
                        <input type="text" class="form-control" value="Active (Root Super Admin Protected)" disabled>
                        <input type="hidden" name="status" value="active">
                    @else
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $subadmin->status) === 'active' ? 'selected' : '' }}>Active (Allow Login)</option>
                            <option value="inactive" {{ old('status', $subadmin->status) === 'inactive' ? 'selected' : '' }}>Inactive (Login Disabled)</option>
                            <option value="suspended" {{ old('status', $subadmin->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    @endif
                </div>

                @if(!$subadmin->is_super_admin)
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Base Role Template (Optional)</label>
                        <select name="role_id" class="form-select">
                            <option value="">Custom Permission Profile</option>
                            @php
                                $currentRoleId = $subadmin->roles->first()?->id;
                            @endphp
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $currentRoleId) == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="d-grid gap-2 pt-2 border-top">
                    <button type="submit" class="btn btn-garudo-primary py-2 fw-bold">
                        <i class="bi bi-save me-1"></i> Update Subadmin Details
                    </button>
                    <a href="{{ route('admin.subadmins.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>

        <!-- ---------------- SECTION 2: ACCESS CONTROL & PERMISSIONS ---------------- -->
        <div class="col-12 col-xl-8">
            @if($subadmin->is_super_admin)
                <div class="garudo-card p-4 text-center py-5">
                    <div class="p-3 bg-warning-subtle text-warning d-inline-block rounded-circle mb-3">
                        <i class="bi bi-shield-fill-check fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Root Super Administrator Account</h4>
                    <p class="text-muted max-w-lg mx-auto mb-0">
                        This account is the master Root Super Admin of the Garudo B2B Marketplace. It intrinsically has 100% full, unrestricted privileges across all system components, databases, payments, settings, and staff operations. Permissions cannot be removed from the root account.
                    </p>
                </div>
            @else
                <!-- Fast Presets Card -->
                <div class="garudo-card p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 pb-3 mb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                <i class="bi bi-lightning-charge-fill text-warning"></i>
                                Quick Role Presets (1-Click Selection)
                            </h5>
                            <p class="small text-muted mb-0">Click any preset below to reset or override permissions with a pre-configured profile.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="selectAllPermissions(true)">
                                <i class="bi bi-check-all"></i> Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="selectAllPermissions(false)">
                                <i class="bi bi-x"></i> Clear All
                            </button>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @foreach($presets as $preset)
                            <button type="button" 
                                    class="btn btn-sm btn-light border d-inline-flex align-items-center gap-2 py-2 px-3 hover-shadow"
                                    onclick="applyPreset({{ json_encode($preset['permissions']) }})"
                                    title="{{ $preset['description'] }}">
                                <span class="badge {{ $preset['badge_class'] ?? 'bg-secondary' }}">{{ $preset['badge'] }}</span>
                                <span class="fw-semibold text-dark">{{ $preset['name'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Granular Permissions Matrix -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-grid-3x3-gap-fill text-primary"></i>
                        Granular Permission Matrix
                    </h5>
                    <span class="badge bg-dark" id="selectedCountBadge">0 Permissions Selected</span>
                </div>

                <div class="row g-3">
                    @foreach($modules as $moduleName => $modData)
                        <div class="col-12 col-md-6">
                            <div class="garudo-card p-3 h-100 module-card border">
                                <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 bg-light rounded text-success">
                                            <i class="bi {{ $modData['icon'] ?? 'bi-folder' }} fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">{{ $moduleName }}</h6>
                                            <small class="text-muted" style="font-size: 0.72rem;">{{ $modData['description'] ?? '' }}</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2" style="font-size: 0.72rem;" onclick="toggleModuleCheckboxes(this)">
                                        Toggle All
                                    </button>
                                </div>

                                <div class="permissions-list mt-2">
                                    @foreach($modData['permissions'] as $permKey => $permInfo)
                                        @php
                                            $isChecked = false;
                                            if (old('permissions')) {
                                                $isChecked = in_array($permKey, old('permissions'));
                                            } else {
                                                $isChecked = in_array($permKey, $assignedPermissionNames);
                                            }
                                        @endphp
                                        <div class="form-check mb-2">
                                            <input class="form-check-input permission-checkbox" 
                                                   type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permKey }}" 
                                                   id="perm_{{ Str::slug($permKey) }}"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   onchange="updateSelectedCount()">
                                            <label class="form-check-label text-dark" for="perm_{{ Str::slug($permKey) }}">
                                                <div class="fw-semibold small">{{ $permInfo['label'] }}</div>
                                                <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">{{ $permInfo['desc'] }}</div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Floating Action -->
                <div class="garudo-card p-3 mt-4 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i> Permissions will take effect immediately upon saving.
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.subadmins.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-garudo-primary fw-bold">
                            <i class="bi bi-check2-circle me-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function generateRandomPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%';
        let pass = '';
        for (let i = 0; i < 10; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('adminPasswordInput').value = pass;
        document.getElementById('adminPasswordConfirmInput').value = pass;
        document.getElementById('adminPasswordInput').type = 'text';
        document.getElementById('adminPasswordConfirmInput').type = 'text';
        alert('Generated new password: ' + pass + '\nRemember to save changes to apply it.');
    }

    function selectAllPermissions(check) {
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = check;
        });
        updateSelectedCount();
    }

    function toggleModuleCheckboxes(btn) {
        const card = btn.closest('.module-card');
        const checkboxes = card.querySelectorAll('.permission-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        updateSelectedCount();
    }

    function applyPreset(permissionKeys) {
        selectAllPermissions(false);
        permissionKeys.forEach(key => {
            const cb = document.querySelector(`.permission-checkbox[value="${key}"]`);
            if (cb) {
                cb.checked = true;
            }
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const count = document.querySelectorAll('.permission-checkbox:checked').length;
        const total = document.querySelectorAll('.permission-checkbox').length;
        const badge = document.getElementById('selectedCountBadge');
        if (badge) {
            badge.innerText = `${count} of ${total} Permissions Selected`;
            badge.className = count > 0 ? 'badge bg-success' : 'badge bg-secondary';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedCount();
    });
</script>
@endpush
