<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Command Center - GARUDO B2B</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --admin-primary: #0B4D3C;
            --admin-primary-dark: #062E24;
            --admin-accent: #10B981;
            --admin-gold: #F59E0B;
            --admin-dark-bg: #0A1512;
            --admin-card-bg: rgba(14, 30, 24, 0.75);
            --admin-border: rgba(16, 185, 129, 0.25);
            --admin-text-main: #F8FAFC;
            --admin-text-muted: #94A3B8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--admin-dark-bg);
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(11, 77, 60, 0.45) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.25) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(6, 46, 36, 0.8) 0%, transparent 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--admin-text-main);
            position: relative;
            overflow-x: hidden;
            padding: 20px;
        }

        /* Ambient Glow Circles */
        .ambient-glow-1 {
            position: absolute;
            top: -10%;
            left: -5%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.2) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            pointer-events: none;
            filter: blur(50px);
        }

        .ambient-glow-2 {
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
        }

        .login-card-wrapper {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 10;
        }

        .login-glass-card {
            background: var(--admin-card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--admin-border);
            border-radius: 24px;
            padding: 2.5rem 2.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 30px rgba(16, 185, 129, 0.1);
            transition: all 0.3s ease;
        }

        .login-glass-card:hover {
            border-color: rgba(16, 185, 129, 0.4);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.7), 0 0 40px rgba(16, 185, 129, 0.15);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-icon-box {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #0B4D3C 0%, #10B981 100%);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 10px 20px rgba(11, 77, 60, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.3);
            color: #FFFFFF;
            font-size: 28px;
        }

        .brand-title {
            font-size: 1.65rem;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #FFFFFF;
            margin-bottom: 0.25rem;
        }

        .brand-title span {
            color: var(--admin-accent);
        }

        .super-admin-badge {
            background: rgba(245, 158, 11, 0.15);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.3);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .form-label {
            font-size: 0.825rem;
            font-weight: 600;
            color: #E2E8F0;
            margin-bottom: 0.45rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: #64748B;
            font-size: 1rem;
            pointer-events: none;
            z-index: 5;
            transition: color 0.2s;
        }

        .form-control-custom {
            width: 100%;
            background: rgba(6, 20, 15, 0.6);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 12px;
            padding: 0.75rem 1rem 0.75rem 2.6rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            background: rgba(6, 20, 15, 0.85);
            border-color: var(--admin-accent);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            color: #FFFFFF;
        }

        .form-control-custom:focus + .input-icon-left,
        .input-group-custom:focus-within .input-icon-left {
            color: var(--admin-accent);
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 4px 8px;
            font-size: 1.1rem;
            transition: color 0.2s;
            z-index: 5;
        }

        .password-toggle-btn:hover {
            color: #FFFFFF;
        }

        .btn-admin-submit {
            background: linear-gradient(135deg, #0B4D3C 0%, #10B981 100%);
            color: #FFFFFF;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            border: none;
            width: 100%;
            box-shadow: 0 8px 20px rgba(11, 77, 60, 0.4);
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-admin-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4);
            color: #FFFFFF;
        }

        .btn-admin-submit:active {
            transform: translateY(0);
        }

        .custom-checkbox .form-check-input {
            background-color: rgba(6, 20, 15, 0.6);
            border-color: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            cursor: pointer;
        }

        .custom-checkbox .form-check-input:checked {
            background-color: var(--admin-accent);
            border-color: var(--admin-accent);
        }

        .custom-checkbox .form-check-label {
            font-size: 0.825rem;
            color: var(--admin-text-muted);
            cursor: pointer;
        }

        .security-footer {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
            font-size: 0.75rem;
            color: var(--admin-text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: var(--admin-accent);
            font-weight: 600;
        }

        .alert-admin-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
            font-size: 0.85rem;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="login-card-wrapper">
        <div class="login-glass-card">
            
            <div class="brand-header">
                <div class="brand-icon-box">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1 class="brand-title">GARUDO <span>ADMIN</span></h1>
                <div class="super-admin-badge">
                    <i class="bi bi-shield-check"></i> Super Administrator Command Center
                </div>
            </div>

            @if($errors->any())
                <div class="alert-admin-error">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-5"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success bg-opacity-25 bg-success text-success border-success border-opacity-50 py-2 px-3 rounded-3 mb-3 small">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="admin-email">
                        <i class="bi bi-envelope"></i> Super Admin Email
                    </label>
                    <div class="input-group-custom">
                        <i class="bi bi-person-badge input-icon-left"></i>
                        <input 
                            type="email" 
                            name="email" 
                            id="admin-email" 
                            class="form-control-custom" 
                            placeholder="admin@garudo.com" 
                            value="{{ old('email', 'admin@garudo.com') }}" 
                            required 
                            autofocus
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label mb-0" for="admin-password">
                            <i class="bi bi-key"></i> Security Password
                        </label>
                    </div>
                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon-left"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="admin-password" 
                            class="form-control-custom" 
                            placeholder="••••••••••••" 
                            required
                        >
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="Toggle password view">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check custom-checkbox">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                        <label class="form-check-label" for="rememberMe">
                            Keep Secure Session Active
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-admin-submit">
                    <i class="bi bi-box-arrow-in-right"></i> Authenticate & Enter Command Center
                </button>
            </form>

            <div class="security-footer">
                <span class="security-badge"><i class="bi bi-lock-fill"></i> 256-Bit Encrypted</span>
                <span>•</span>
                <span>Garudo B2B Core v2.4</span>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password Visibility Toggle
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('admin-password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        toggleBtn.addEventListener('click', function () {
            if (passInput.type === 'password') {
                passInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html>
