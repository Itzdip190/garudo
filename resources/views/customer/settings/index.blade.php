@extends('layouts.customer')

@section('title', 'Account Settings')

@section('content')
<div class="settings-page-container">
    
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row md-align-items-center justify-content-between mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Account Settings</h1>
            <p class="text-muted mb-0" style="font-size: 0.875rem;">Manage your business profile, contact details, GST registration, and security credentials.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="border-radius: 12px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        
        <!-- Left: Profile & Business Details -->
        <div class="col-12 col-lg-7">
            <div class="card-custom mb-4">
                <h2 class="card-title-lg mb-3">Personal & Business Information</h2>
                
                <form action="{{ route('customer.settings.profile') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.82rem;">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.82rem;">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.82rem;">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '+91 98765 43210') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 0.82rem;">Company / Business Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $customer->company_name ?? 'Apex Infra Projects Pvt Ltd') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 0.82rem;">GSTIN (B2B Tax Invoicing)</label>
                            <input type="text" name="gstin" class="form-control" value="{{ old('gstin', $customer->gstin ?? '07AAAAA0000A1Z5') }}" placeholder="e.g. 07AAAAA0000A1Z5">
                            <small class="text-muted" style="font-size: 0.72rem;">Input valid GSTIN to receive automatic GST tax credit on construction material invoices.</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn-primary-action">
                            <i class="bi bi-check2"></i> Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Security & Password -->
        <div class="col-12 col-lg-5">
            <div class="card-custom mb-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <i class="bi bi-shield-lock fs-5" style="color: var(--garudo-primary);"></i>
                    <h2 class="card-title-lg mb-0" style="font-size: 1.05rem;">Security & Password Reset</h2>
                </div>
                
                <form action="{{ route('customer.settings.password') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.82rem;">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" name="current_password" id="cust_curr_pass" class="form-control border-start-0 ps-0" placeholder="Enter current password" required>
                            <button class="btn btn-outline-secondary border-start-0 border" type="button" onclick="togglePassField('cust_curr_pass', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.82rem;">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="cust_new_pass" class="form-control border-start-0 ps-0" placeholder="Minimum 6 characters" required minlength="6">
                            <button class="btn btn-outline-secondary border-start-0 border" type="button" onclick="togglePassField('cust_new_pass', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">Must be at least 6 characters.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.82rem;">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password_confirmation" id="cust_conf_pass" class="form-control border-start-0 ps-0" placeholder="Repeat new password" required minlength="6">
                            <button class="btn btn-outline-secondary border-start-0 border" type="button" onclick="togglePassField('cust_conf_pass', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn-primary-action py-2 px-3">
                            <i class="bi bi-shield-check me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>

            <script>
            function togglePassField(id, btn) {
                const f = document.getElementById(id);
                const ic = btn.querySelector('i');
                if (f.type === 'password') {
                    f.type = 'text';
                    ic.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    f.type = 'password';
                    ic.classList.replace('bi-eye-slash', 'bi-eye');
                }
            }
            </script>

            <!-- Account Status Card -->
            <div class="card-custom">
                <h3 class="card-title-lg mb-2" style="font-size: 0.95rem;">Account Verification</h3>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success" style="font-size: 0.75rem;">Verified Customer</span>
                    <span class="badge bg-light text-dark border" style="font-size: 0.75rem;">B2B Buyer</span>
                </div>
                <p class="text-muted mb-0" style="font-size: 0.78rem;">
                    Your account is eligible for bulk trade credit and wholesale supplier rate inquiries.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection
