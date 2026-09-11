<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Password Reset Request - GARUDO B2B Marketplace</title>
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
            --garudo-text-main: #0F172A;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--garudo-bg);
            color: var(--garudo-text-main);
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
            max-width: 460px;
            padding: 2.2rem;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 0.5rem;
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

        .vendor-badge-pill {
            background: #F0FDF4;
            color: #166534;
            border: 1px solid #BBF7D0;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            display: inline-block;
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

        .info-box {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 12px;
            padding: 1rem;
            font-size: 0.82rem;
            color: #166534;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        
        <!-- Logo & Header -->
        <div class="text-center mb-3">
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
                <span class="vendor-badge-pill mt-2">Vendor Portal Support</span>
            </div>
            <h5 class="fw-bold mt-3 mb-1">Reset Password Request</h5>
            <p class="text-muted small">Seller Hub Credential Recovery</p>
        </div>

        <div class="info-box d-flex gap-2 align-items-start">
            <i class="bi bi-shield-check fs-5 flex-shrink-0 text-success"></i>
            <div>
                <strong>Vendor Password Assistance:</strong> Submit your registered seller email. A reset request will be directed to the <strong>Super Administrator</strong>. Once approved, you will receive a temporary demo password. When you log in, you will be prompted to create your new permanent password.
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger p-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success p-3 mb-3 rounded-3 small" style="background: #E8F7F0; color: #0B7B58; border: 1px solid #A7F3D0;">
                <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                    <i class="bi bi-check-circle-fill"></i> Request Submitted!
                </div>
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-warning p-3 mb-3 rounded-3 small" style="background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A;">
                <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                    <i class="bi bi-info-circle-fill"></i> Request Already Pending
                </div>
                {{ session('info') }}
            </div>
        @endif

        <form action="{{ route('vendor.password.request.submit') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-semibold small">Registered Vendor Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="vendor@garudo.com" value="{{ old('email') }}" required autofocus>
                </div>
                <small class="text-muted">Enter the email registered with your vendor/seller account.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Registered Phone / WhatsApp <span class="text-muted">(Optional)</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-telephone"></i></span>
                    <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" value="{{ old('phone') }}">
                </div>
                <small class="text-muted">Used by Super Admin to verify your store ownership.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Notes / Company Details <span class="text-muted">(Optional)</span></label>
                <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Requesting password reset for ABC Enterprises store." style="font-size: 0.82rem;">{{ old('notes') }}</textarea>
            </div>

            <button class="btn btn-vendor-primary mb-3" type="submit">
                <i class="bi bi-send me-1"></i> Submit Vendor Reset Request
            </button>

            <div class="text-center pt-2 border-top">
                <p class="text-muted small mb-0">
                    Remembered your password? 
                    <a href="{{ route('vendor.login') }}" class="fw-bold text-success text-decoration-none">
                        Back to Vendor Login →
                    </a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>
