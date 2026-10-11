<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden | GARUDO B2B</title>
    <link rel="icon" type="image/png" href="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .error-card {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            max-width: 540px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .icon-wrap {
            width: 80px;
            height: 80px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="mb-4">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" style="height: 46px; max-width: 100%; object-fit: contain;">
            </a>
        </div>
        <div class="icon-wrap">
            <i class="bi bi-shield-x"></i>
        </div>
        <h2 class="fw-bold mb-2">Access Restricted (403)</h2>
        <p class="text-secondary mb-4">
            {{ $exception->getMessage() ?: 'Your subadmin account does not have permission to access this section or perform this action. Please contact your Super Administrator to request permission.' }}
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-success px-4 py-2 fw-semibold">
                <i class="bi bi-house-door me-1"></i> Command Center
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-light px-4 py-2">
                <i class="bi bi-arrow-left me-1"></i> Go Back
            </a>
        </div>
    </div>
</body>
</html>
