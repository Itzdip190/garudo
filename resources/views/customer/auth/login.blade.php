<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - GARUDO B2B Marketplace</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --garudo-primary: #0B4D3C;
            --garudo-primary-hover: #083D30;
            --garudo-bg: #F8FAFC;
            --garudo-border: #E2E8F0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--garudo-bg);
            color: #0F172A;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        .auth-card {
            background: #FFFFFF;
            border: 1px solid var(--garudo-border);
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            width: 100%;
            max-width: 440px;
            padding: 2.2rem;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 1.5rem;
        }

        .brand-name {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--garudo-primary);
            line-height: 1;
        }

        .brand-tagline {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: #64748B;
            text-transform: uppercase;
        }

        .btn-primary-garudo {
            background-color: var(--garudo-primary);
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.75rem;
            border-radius: 10px;
            border: none;
            transition: background-color 0.15s;
            width: 100%;
        }

        .btn-primary-garudo:hover {
            background-color: var(--garudo-primary-hover);
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        
        <!-- Logo -->
        <a href="{{ route('home') }}" class="brand-logo-wrap">
            <div class="d-flex align-items-center justify-content-center" style="color: var(--garudo-primary);">
                <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 14C14 14 20 18 22 24C16 23 10 20 6 14Z" fill="#0B4D3C"/>
                    <path d="M8 20C16 20 22 24 24 30C18 29 12 26 8 20Z" fill="#0B4D3C"/>
                    <path d="M12 26C18 26 24 29 26 35C20 34 15 31 12 26Z" fill="#0B4D3C"/>
                    <path d="M22 24C26 16 34 12 42 12C36 18 30 22 24 30C23 27 22 25 22 24Z" fill="#0B4D3C"/>
                </svg>
            </div>
            <div class="d-flex flex-column text-start">
                <span class="brand-name">GARUDO</span>
                <span class="brand-tagline">BUILD BETTER. TOGETHER.</span>
            </div>
        </a>

        <!-- Portal Switcher Tabs -->
        <div class="d-flex justify-content-center mb-3">
            <div class="d-inline-flex p-1 rounded-pill bg-light border" style="gap: 4px;">
                <span class="btn btn-sm rounded-pill fw-bold px-3 py-1 text-white shadow-sm" style="background-color: var(--garudo-primary); font-size: 0.78rem; cursor: default;">
                    <i class="bi bi-person-fill me-1"></i> Customer Login
                </span>
                <a href="{{ route('vendor.login') }}" class="btn btn-sm rounded-pill fw-semibold px-3 py-1 text-muted text-decoration-none" style="font-size: 0.78rem;">
                    <i class="bi bi-shop me-1"></i> Vendor Login
                </a>
            </div>
        </div>

        <div class="text-center mb-4">
            <h1 class="h4 fw-bold text-dark mb-1">Customer Sign In</h1>
            <p class="text-muted" style="font-size: 0.82rem;">Access your dashboard, orders, and building material quotes</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger p-2 mb-3" style="border-radius: 8px; font-size: 0.8rem;">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success p-2 mb-3" style="border-radius: 8px; font-size: 0.8rem; background: #E8F7F0; color: #0B7B58; border: none;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('customer.login.submit') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label fw-semibold mb-0" style="font-size: 0.82rem;">Password</label>
                    <a href="{{ route('customer.password.request') }}" class="text-decoration-none fw-semibold" style="font-size: 0.78rem; color: var(--garudo-primary);">Forgot Password?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label text-muted" for="rememberMe" style="font-size: 0.8rem;">
                        Remember me
                    </label>
                </div>
            </div>

            <button class="btn-primary-garudo mb-3" type="submit">Sign In to Dashboard</button>

            <div class="text-center pt-2 border-top">
                <p class="text-muted mb-2" style="font-size: 0.82rem;">
                    Don't have a customer account? 
                    <a href="{{ route('customer.register') }}" class="fw-bold text-decoration-none" style="color: var(--garudo-primary);">Create Account</a>
                </p>
                <div class="p-2 rounded-3 mt-2" style="background: #F0FDF4; border: 1px dashed #BBF7D0;">
                    <p class="mb-1 text-muted" style="font-size: 0.78rem;">
                        Are you a building material vendor or manufacturer?
                    </p>
                    <a href="{{ route('vendor.login') }}" class="fw-bold text-decoration-none d-inline-flex align-items-center gap-1" style="color: #166534; font-size: 0.82rem;">
                        <i class="bi bi-shop"></i> Switch to Vendor Login &rarr;
                    </a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
