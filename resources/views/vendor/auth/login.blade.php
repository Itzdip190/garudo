<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login - GARUDO B2B</title>
    <link rel="icon" type="image/png" href="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}">
    
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
            padding: 1.5rem;
        }

        .auth-card {
            background: #FFFFFF;
            border: 1px solid var(--garudo-border);
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
            width: 100%;
            max-width: 440px;
            padding: 2.25rem 2rem;
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

        .form-control {
            border-radius: 8px;
            border: 1.5px solid var(--garudo-border);
            padding: 0.65rem 0.95rem;
            font-size: 0.875rem;
            transition: all 0.15s;
        }

        .form-control:focus {
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
            font-size: 0.9rem;
            padding: 0.7rem 1.25rem;
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
            <a href="{{ route('home') }}" class="brand-logo-wrap text-decoration-none justify-content-center mb-3" title="Garudo B2B Marketplace">
                <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" style="height: 52px; max-width: 230px; object-fit: contain;">
            </a>
            <!-- Portal Switcher Tabs -->
            <div class="d-flex justify-content-center mt-2 mb-2">
                <div class="d-inline-flex p-1 rounded-pill bg-light border" style="gap: 4px;">
                    <a href="{{ route('customer.login') }}" class="btn btn-sm rounded-pill fw-semibold px-3 py-1 text-muted text-decoration-none" style="font-size: 0.78rem;">
                        <i class="bi bi-person me-1"></i> Customer Login
                    </a>
                    <span class="btn btn-sm rounded-pill fw-bold px-3 py-1 text-white shadow-sm" style="background-color: var(--garudo-primary); font-size: 0.78rem; cursor: default;">
                        <i class="bi bi-shop me-1"></i> Vendor Login
                    </span>
                </div>
            </div>
            <h5 class="fw-bold mt-2 mb-1" style="color: var(--garudo-text-main);">Welcome Back</h5>
            <p class="text-muted small">Sign in to manage your building materials store</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger p-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ (request()->getHost() === 'seller.garudo.in' || request()->getHost() === 'vendor.garudo.in') ? url('/login') : route('vendor.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="vendor@garudo.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label">Password</label>
                    <a href="{{ route('vendor.password.request') }}" class="text-decoration-none small text-success fw-medium">Forgot Password?</a>
                </div>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                <label class="form-check-label text-muted small" for="rememberMe">
                    Remember me on this device
                </label>
            </div>

            <button class="btn btn-vendor-primary mb-3" type="submit">
                Sign In to Vendor Hub
            </button>

            <div class="text-center pt-2 border-top">
                <p class="text-muted small mb-2">Want to sell building materials on Garudo?</p>
                <a href="{{ (request()->getHost() === 'seller.garudo.in' || request()->getHost() === 'vendor.garudo.in') ? url('/register') : route('vendor.register') }}" class="fw-bold text-success text-decoration-none small">
                    Register as a Vendor / Seller →
                </a>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('customer.login') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-person me-1"></i> Switch to Customer Login
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
