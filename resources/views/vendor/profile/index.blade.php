@extends('layouts.vendor')

@section('title', 'Profile & Password Reset')

@section('content')
<div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--garudo-text-main);">Profile & Security</h4>
        <p class="text-muted mb-0 small">Manage your vendor account details and reset your access password.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Business & Personal Details -->
    <div class="col-lg-7">
        <div class="card-custom h-100">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-person-badge fs-5" style="color: var(--garudo-primary);"></i>
                <h6 class="fw-bold mb-0 text-dark">Representative & Business Information</h6>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show p-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->has('name') || $errors->has('phone') || $errors->has('company_name'))
                <div class="alert alert-danger p-2 px-3 small rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->only(['name', 'phone', 'company_name']) as $errList)
                            @foreach($errList as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vendor.profile.update') }}" method="POST">
                @csrf
                
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $vendor->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Company / Store Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $vendor->company_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Registered Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control bg-light text-muted" value="{{ $vendor->email }}" readonly>
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i></span>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Email cannot be changed directly for security purposes.</small>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-success px-4" style="background-color: var(--garudo-primary); border: none; font-weight: 600;">
                        <i class="bi bi-check2 me-1"></i> Save Profile Details
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Password Reset / Security -->
    <div class="col-lg-5">
        <div class="card-custom h-100">
            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                <i class="bi bi-shield-lock fs-5" style="color: var(--garudo-primary);"></i>
                <h6 class="fw-bold mb-0 text-dark">Password Reset & Security</h6>
            </div>

            @if(session('password_success'))
                <div class="alert alert-success alert-dismissible fade show p-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                    <div>{{ session('password_success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                <div class="alert alert-danger p-2 px-3 small rounded-3 mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->only(['current_password', 'password', 'password_confirmation']) as $errList)
                            @foreach($errList as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vendor.profile.password') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                        <input type="password" name="current_password" id="current_password" class="form-control border-start-0 ps-0" placeholder="Enter current password" required>
                        <button class="btn btn-outline-secondary border-start-0 border" type="button" onclick="togglePass('current_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="new_password" class="form-control border-start-0 ps-0" placeholder="Minimum 6 characters" required minlength="6">
                        <button class="btn btn-outline-secondary border-start-0 border" type="button" onclick="togglePass('new_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small class="text-muted" style="font-size: 0.72rem;">Must be at least 6 characters with letters and numbers.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Repeat new password" required minlength="6">
                        <button class="btn btn-outline-secondary border-start-0 border" type="button" onclick="togglePass('password_confirmation', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <button type="submit" class="btn btn-dark px-4" style="font-weight: 600;">
                        <i class="bi bi-shield-check me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePass(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endsection
