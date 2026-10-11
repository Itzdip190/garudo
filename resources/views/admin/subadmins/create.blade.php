@extends('layouts.admin')

@section('title', 'Create New Subadmin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <div class="mb-1">
            <a href="{{ route('admin.subadmins.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Back to Subadmins Directory
            </a>
        </div>
        <h1 class="page-header-title d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill text-success"></i>
            Create New Subadmin Account
        </h1>
        <p class="page-header-subtitle mb-0">Create new staff login credentials and select exactly which modules they can view and control.</p>
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

<form action="{{ route('admin.subadmins.store') }}" method="POST" id="subadminForm">
    @csrf

    <div class="row g-4 mb-4">
        <!-- ---------------- SECTION 1: ACCOUNT CREDENTIALS ---------------- -->
        <div class="col-12 col-xl-4">
            <div class="garudo-card p-4 h-100 position-sticky" style="top: 90px;">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-person-vcard text-primary"></i>
                    Login Credentials
                </h5>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Rahul Sharma" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Login Email ID <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="subadmin@garudo.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-text small text-muted">The subadmin will log into the admin panel with this email ID.</div>
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Contact Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-telephone text-muted"></i></span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. 9876543210" value="{{ old('phone') }}">
                    </div>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold mb-0">Login Password <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none small" onclick="generateRandomPassword()">
                            <i class="bi bi-magic me-1"></i> Generate
                        </button>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="adminPasswordInput" class="form-control @error('password') is-invalid @enderror" placeholder="At least 6 characters" required minlength="6">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('adminPasswordInput')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" id="adminPasswordConfirmInput" class="form-control" placeholder="Repeat same password" required minlength="6">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Account Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Allow Login)</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Login Disabled)</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Base Role Template (Optional)</label>
                    <select name="role_id" class="form-select">
                        <option value="">Custom Permission Profile</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name ?? $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text small text-muted">You can also check individual permissions in the matrix on the right.</div>
                </div>

                <div class="d-grid gap-2 pt-2 border-top">
                    <button type="submit" class="btn btn-garudo-primary py-2 fw-bold">
                        <i class="bi bi-shield-check me-1"></i> Save & Create Subadmin
                    </button>
                    <a href="{{ route('admin.subadmins.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>

        <!-- ---------------- SECTION 2: ACCESS CONTROL & PERMISSIONS ---------------- -->
        <div class="col-12 col-xl-8">
            <!-- Fast Presets Card -->
            <div class="garudo-card p-4 mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 pb-3 mb-3 border-bottom">
                    <div>
                        <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            Quick Role Presets (1-Click Selection)
                        </h5>
                        <p class="small text-muted mb-0">Click any preset below to automatically select common permission packages, then customize as needed.</p>
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
                                    <div class="form-check mb-2">
                                        <input class="form-check-input permission-checkbox" 
                                               type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permKey }}" 
                                               id="perm_{{ Str::slug($permKey) }}"
                                               {{ (is_array(old('permissions')) && in_array($permKey, old('permissions'))) ? 'checked' : '' }}
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
                    <i class="bi bi-info-circle me-1"></i> Admin can adjust or revoke any permissions at any time later.
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.subadmins.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-garudo-primary fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Create Subadmin
                    </button>
                </div>
            </div>
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
        alert('Generated secure temporary password: ' + pass + '\nMake sure to copy or share this with the new subadmin.');
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
        // First uncheck all
        selectAllPermissions(false);
        // Check only those in preset
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
