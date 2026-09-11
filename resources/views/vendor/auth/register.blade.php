<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Registration - GARUDO B2B</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --garudo-primary: #0B4D3C;
            --garudo-primary-hover: #083D30;
            --garudo-primary-light: #E8F7F0;
            --garudo-primary-text: #0B7B58;
            --garudo-bg: #F8FAFC;
            --garudo-border: #E2E8F0;
            --garudo-text-main: #0F172A;
            --garudo-text-muted: #64748B;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--garudo-bg);
            color: var(--garudo-text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .auth-card {
            background: #FFFFFF;
            border: 1px solid var(--garudo-border);
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            width: 100%;
            max-width: 600px;
            padding: 2.5rem 2.25rem;
        }

        .brand-logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .brand-name {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--garudo-primary);
            line-height: 1.1;
            margin: 0;
        }

        .brand-tagline {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--garudo-text-muted);
            text-transform: uppercase;
        }

        .vendor-badge-pill {
            background-color: var(--garudo-primary-light);
            color: var(--garudo-primary-text);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid #C6E9D7;
            display: inline-block;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid var(--garudo-border);
            padding: 0.65rem 0.95rem;
            font-size: 0.875rem;
            transition: all 0.15s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--garudo-primary);
            box-shadow: 0 0 0 3px rgba(11, 77, 60, 0.12);
        }

        .form-label {
            font-size: 0.813rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .btn-vendor-primary {
            background-color: var(--garudo-primary);
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            border: none;
            width: 100%;
            transition: background 0.15s;
        }

        .btn-vendor-primary:hover {
            background-color: var(--garudo-primary-hover);
            color: #FFFFFF;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="brand-logo-wrap mb-2">
                <div class="brand-logo-icon">
                    <svg viewBox="0 0 48 48" fill="none" style="width: 42px; height: 42px;" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 14C14 14 20 18 22 24C16 23 10 20 6 14Z" fill="#0B4D3C"/>
                        <path d="M8 20C16 20 22 24 24 30C18 29 12 26 8 20Z" fill="#0B4D3C"/>
                        <path d="M12 26C18 26 24 29 26 35C20 34 15 31 12 26Z" fill="#0B4D3C"/>
                        <path d="M22 24C26 16 34 12 42 12C36 18 30 22 24 30C23 27 22 25 22 24Z" fill="#0B4D3C"/>
                    </svg>
                </div>
                <div class="text-start">
                    <div class="brand-name">GARUDO</div>
                    <div class="brand-tagline">BUILD BETTER. TOGETHER.</div>
                </div>
            </a>
            <div>
                <span class="vendor-badge-pill mt-2">Become a Seller</span>
            </div>
            <h5 class="fw-bold mt-3 mb-1" style="color: var(--garudo-text-main);">Register as a Vendor</h5>
            <p class="text-muted small">Expand your building materials business across India</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger p-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('vendor.register.submit') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Ramesh Kumar" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company / Enterprise Name</label>
                    <input type="text" name="company_name" class="form-control" placeholder="e.g. Shree Balaji Traders" value="{{ old('company_name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Business Type</label>
                    <select name="business_type" class="form-select" required>
                        <option value="trader" {{ old('business_type') == 'trader' ? 'selected' : '' }}>Trader / Retailer</option>
                        <option value="wholesaler" {{ old('business_type') == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                        <option value="distributor" {{ old('business_type') == 'distributor' ? 'selected' : '' }}>Distributor</option>
                        <option value="manufacturer" {{ old('business_type') == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" value="{{ old('phone') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="vendor@example.com" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-check my-3">
                <input class="form-check-input" type="checkbox" id="termsCheck" required checked>
                <label class="form-check-label text-muted small" for="termsCheck">
                    I agree to the Garudo Vendor Terms of Service and Privacy Policy.
                </label>
            </div>

            <button class="btn btn-vendor-primary mb-3" type="submit">
                Complete Registration
            </button>

            <div class="text-center pt-2 border-top">
                <p class="text-muted small mb-2">Already have a registered vendor account?</p>
                <a href="{{ route('vendor.login') }}" class="fw-bold text-success text-decoration-none small">
                    Sign In to Vendor Portal →
                </a>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('customer.dashboard') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i> Return to Customer Dashboard
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
