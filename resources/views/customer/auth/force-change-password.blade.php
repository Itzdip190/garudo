<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - GARUDO B2B</title>
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
            max-width: 480px;
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

        .security-notice {
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            border-radius: 12px;
            padding: 1rem;
            font-size: 0.82rem;
            color: #92400E;
            line-height: 1.5;
            margin-bottom: 1.5rem;
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

        <div class="text-center mb-3">
            <h1 class="h4 fw-bold text-dark mb-1">Create Your Permanent Password</h1>
            <p class="text-muted" style="font-size: 0.82rem;">Welcome back, <strong>{{ $customer->name }}</strong></p>
        </div>

        <div class="security-notice d-flex gap-2 align-items-start">
            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0 text-warning"></i>
            <div>
                <strong>Security Action Required:</strong> You signed in using a temporary demo password provided by the Super Admin. For your account security, you must choose a new personal password before accessing your orders and quotes.
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger p-2 mb-3" style="border-radius: 8px; font-size: 0.8rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('customer.force_change_password.submit') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Temporary Demo Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                    <input type="password" name="current_password" class="form-control border-start-0 ps-0" placeholder="Enter the demo password you logged in with" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.82rem;">New Permanent Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="At least 6 characters" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size: 0.82rem;">Confirm New Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-check2-circle"></i></span>
                    <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Repeat new password" required>
                </div>
            </div>

            <button class="btn-primary-garudo mb-3" type="submit">
                <i class="bi bi-shield-lock-fill me-1"></i> Save New Password & Proceed
            </button>

            <div class="text-center pt-2 border-top">
                <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted text-decoration-none p-0" style="font-size: 0.82rem;">
                        <i class="bi bi-box-arrow-left me-1"></i> Sign out
                    </button>
                </form>
            </div>
        </form>
    </div>
</body>
</html>
