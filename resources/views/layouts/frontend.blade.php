<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Garudo - B2B Building Materials Marketplace')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-body-font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --garudo-navy-top: #07152b;
            --garudo-navy: #07152b;
            --garudo-navy-light: #0d1e3d;
            --garudo-yellow: #ffb703;
            --garudo-yellow-hover: #f59e0b;
            --garudo-orange: #f26522;
            --garudo-red: #ef4444;
            --garudo-bg: #f4f6f9;
            --garudo-card-border: #e2e8f0;
            --garudo-text-dark: #1e293b;
            --garudo-text-muted: #64748b;
        }

        body {
            font-family: var(--bs-body-font-family);
            color: var(--garudo-text-dark);
            background-color: var(--garudo-bg);
            font-size: 13px;
        }

        .bg-navy-top { background-color: var(--garudo-navy-top); }
        .bg-navy { background-color: var(--garudo-navy); }
        .bg-navy-light { background-color: var(--garudo-navy-light); }
        .text-yellow { color: var(--garudo-yellow); }
        .bg-yellow { background-color: var(--garudo-yellow); color: #07152b; }
        .btn-yellow {
            background-color: var(--garudo-yellow);
            color: #07152b;
            font-weight: 700;
            border: none;
        }
        .btn-yellow:hover {
            background-color: var(--garudo-yellow-hover);
            color: #07152b;
        }
        .btn-orange {
            background-color: var(--garudo-orange);
            color: #fff;
            font-weight: 600;
            border: none;
        }
        .btn-orange:hover {
            background-color: #d95316;
            color: #fff;
        }

        /* -------------------------------------------------------------
           Header Tier 1: Top Utility Bar
        ------------------------------------------------------------- */
        .top-utility-bar {
            background-color: #07152b;
            font-size: 12px;
            letter-spacing: 0.15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            min-height: 40px;
            display: flex;
            align-items: center;
        }
        .top-trust-icon {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background: rgba(255, 183, 3, 0.16);
            border: 1px solid rgba(255, 183, 3, 0.35);
            color: #ffb703;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12.5px;
            flex-shrink: 0;
        }
        .top-bar-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #cbd5e1;
            font-size: 10px;
            font-weight: 700;
            padding: 2.5px 8px;
            border-radius: 12px;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
        }
        .top-util-link {
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.15s ease;
            font-weight: 500;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 5px;
        }
        .top-util-link:hover {
            color: #ffb703;
            background: rgba(255, 255, 255, 0.05);
        }
        .top-util-divider {
            display: inline-block;
            width: 1px;
            height: 14px;
            background: rgba(255, 255, 255, 0.14);
            margin: 0 4px;
            flex-shrink: 0;
        }
        .top-util-vendor-pill {
            background: rgba(255, 183, 3, 0.14);
            border: 1px solid rgba(255, 183, 3, 0.4);
            color: #ffb703;
            font-weight: 700;
            font-size: 11.5px;
            padding: 3.5px 12px;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .top-util-vendor-pill:hover {
            background: #ffb703;
            color: #07152b;
            border-color: #ffb703;
            box-shadow: 0 0 10px rgba(255, 183, 3, 0.35);
        }
        .top-bar-badge-dot {
            display: inline-block;
            width: 4px;
            height: 12px;
            background-color: #ffb703;
            border-radius: 2px;
            margin-right: 8px;
            vertical-align: middle;
        }

        /* -------------------------------------------------------------
           Header Tier 2: Main Middle Navbar
        ------------------------------------------------------------- */
        .main-header-bar {
            background-color: #ffffff;
            border-bottom: 1px solid #eef2f6;
            padding: 11px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .brand-cube-logo {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .brand-name {
            font-size: 24px;
            font-weight: 800;
            color: #07152b;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        .brand-tagline {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            letter-spacing: 0.1px;
            margin-top: -1px;
        }

        /* Search Box */
        .buildmart-search-container {
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            height: 42px;
            width: 100%;
        }
        .buildmart-search-container:focus-within {
            border-color: #ffb703;
            box-shadow: 0 0 0 3px rgba(255, 183, 3, 0.15);
        }
        .buildmart-cat-select {
            width: 140px;
            flex-shrink: 0;
            border: none;
            border-right: 1px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            padding: 0 12px;
            height: 100%;
            outline: none;
            cursor: pointer;
        }
        .buildmart-search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 0 16px;
            font-size: 13px;
            color: #1e293b;
            background: transparent;
            height: 100%;
            min-width: 80px;
        }
        .buildmart-search-input::placeholder {
            color: #94a3b8;
            font-size: 12.5px;
        }
        .btn-buildmart-search {
            background-color: #ffb703;
            color: #07152b;
            border: none;
            height: 100%;
            padding: 0 22px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background-color 0.15s ease;
            white-space: nowrap;
        }
        .btn-buildmart-search:hover {
            background-color: #f59e0b;
            color: #07152b;
        }

        /* Action Icons */
        .buildmart-action-link {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #475569;
            padding: 2px 9px;
            position: relative;
            transition: color 0.15s ease, transform 0.15s ease;
        }
        .buildmart-action-link:hover {
            color: #07152b;
        }
        .buildmart-action-icon {
            font-size: 19px;
            line-height: 1;
            margin-bottom: 3px;
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .buildmart-action-label {
            font-size: 10.5px;
            font-weight: 600;
            white-space: nowrap;
            letter-spacing: 0.1px;
            color: #334155;
        }
        .buildmart-badge {
            position: absolute;
            top: -4px;
            right: -8px;
            min-width: 16px;
            height: 16px;
            border-radius: 50%;
            font-size: 9px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            line-height: 1;
            padding: 0 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .buildmart-badge-red { background-color: #ef4444; }
        .buildmart-badge-orange { background-color: #f97316; }

        /* Sign In Pill Button */
        .btn-signin-pill {
            background-color: #07152b;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 12px;
            border-radius: 7px;
            padding: 9px 18px;
            border: none;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        .btn-signin-pill:hover {
            background-color: #0e2347;
            color: #ffb703 !important;
            box-shadow: 0 2px 8px rgba(7, 21, 43, 0.25);
        }

        /* -------------------------------------------------------------
           Header Tier 3: Bottom Category Strip
        ------------------------------------------------------------- */
        .bottom-category-bar {
            background-color: #07152b;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            border-bottom: 2px solid #ffb703;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1020;
        }

        /* Mobile Category Bar Trigger (High-End & Polished) */
        .mobile-cat-trigger-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 52px;
            padding: 8px 0;
            width: 100%;
            cursor: pointer;
            user-select: none;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .mobile-cat-trigger-bar:active {
            opacity: 0.9;
        }
        .mobile-cat-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .mobile-cat-icon-chip {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 183, 3, 0.15);
            border: 1px solid rgba(255, 183, 3, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffb703;
            font-size: 17px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .mobile-cat-trigger-bar:hover .mobile-cat-icon-chip,
        .mobile-cat-trigger-bar:active .mobile-cat-icon-chip {
            background: #ffb703;
            color: #07152b;
            border-color: #ffb703;
            box-shadow: 0 0 10px rgba(255, 183, 3, 0.4);
        }
        .mobile-cat-title {
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 700;
            letter-spacing: 0.2px;
            line-height: 1;
            white-space: nowrap;
        }
        .mobile-cat-badge {
            background: linear-gradient(135deg, #ffb703 0%, #f59e0b 100%);
            color: #07152b;
            font-size: 10.5px;
            font-weight: 800;
            padding: 3.5px 10px;
            border-radius: 20px;
            letter-spacing: 0.3px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 6px rgba(255, 183, 3, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.2);
            white-space: nowrap;
        }
        .mobile-cat-arrow-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffb703;
            font-size: 12px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .mobile-cat-trigger-bar:hover .mobile-cat-arrow-btn,
        .mobile-cat-trigger-bar:active .mobile-cat-arrow-btn {
            background: rgba(255, 183, 3, 0.2);
            border-color: #ffb703;
            transform: translateX(2px);
        }
        .cat-strip-container {
            display: flex;
            align-items: center;
            overflow-x: auto;
            scrollbar-width: none;
            min-height: 48px;
        }
        .cat-strip-container::-webkit-scrollbar {
            display: none;
        }
        .btn-all-cat-trigger {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: #ffffff;
            font-weight: 700;
            font-size: 13.5px;
            padding: 8px 14px 8px 0;
            border: none;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-all-cat-trigger:hover {
            color: #ffb703;
        }
        .desktop-cat-icon-chip {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background: rgba(255, 183, 3, 0.15);
            border: 1px solid rgba(255, 183, 3, 0.4);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffb703;
            font-size: 16px;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .btn-all-cat-trigger:hover .desktop-cat-icon-chip {
            background: #ffb703;
            color: #07152b;
            box-shadow: 0 0 10px rgba(255, 183, 3, 0.4);
        }
        .desktop-cat-divider {
            width: 1px;
            height: 22px;
            background: rgba(255, 255, 255, 0.16);
            margin: 0 12px 0 2px;
            flex-shrink: 0;
        }
        .cat-nav-link {
            color: #f1f5f9;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding: 12px 13px;
            white-space: nowrap;
            display: inline-block;
            position: relative;
            transition: color 0.15s ease, background-color 0.15s ease;
            border-radius: 4px;
        }
        .cat-nav-link:hover {
            color: #ffb703;
            background-color: rgba(255, 255, 255, 0.05);
        }
        .cat-nav-link.active {
            color: #ffb703;
            font-weight: 700;
        }
        .cat-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 12px;
            right: 12px;
            height: 3px;
            background-color: #ffb703;
            border-radius: 3px 3px 0 0;
        }

        /* -------------------------------------------------------------
           Slide-In Categories Drawer (Offcanvas Slider)
        ------------------------------------------------------------- */
        .mobile-cat-offcanvas {
            max-width: 380px;
            width: 88%;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 6px 0 30px rgba(0, 0, 0, 0.45);
        }
        @media (min-width: 992px) {
            .mobile-cat-offcanvas {
                max-width: 380px;
                width: 380px;
            }
        }
        .mobile-cat-header {
            background-color: #07152b;
            color: #ffffff;
            padding: 16px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .mobile-cat-body {
            background-color: #f8fafc;
            padding: 0;
            overflow-y: auto;
        }
        .mobile-drawer-section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #475569;
            background: #f1f5f9;
            padding: 10px 18px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .mobile-cat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            color: #0f172a;
            text-decoration: none;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            font-weight: 600;
            transition: background-color 0.15s ease, border-left-color 0.15s ease;
        }
        .mobile-cat-item:hover, .mobile-cat-item:active {
            background-color: #f8fafc;
            color: #07152b;
        }
        .mobile-cat-item.active {
            background-color: #fffdf5;
            border-left: 3.5px solid #ffb703;
            color: #07152b;
        }
        .mobile-cat-icon-box {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .mobile-cat-subtext {
            font-size: 10.5px;
            color: #64748b;
            font-weight: 400;
            line-height: 1.25;
            margin-top: 1px;
        }
        .mobile-nav-link-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            color: #334155;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            transition: background-color 0.15s ease;
        }
        .mobile-nav-link-item:hover, .mobile-nav-link-item:active {
            background-color: #f8fafc;
            color: #07152b;
            font-weight: 600;
        }

        /* General Card & Badges */
        .card-custom {
            border: 1px solid var(--garudo-card-border);
            border-radius: 6px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        }
        .section-header-title {
            font-weight: 800;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: var(--garudo-navy);
            border-bottom: 2px solid var(--garudo-yellow);
            display: inline-block;
            padding-bottom: 2px;
            margin-bottom: 12px;
        }
        .badge-discount {
            background-color: var(--garudo-red);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .product-price-current {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }
        .product-price-old {
            font-size: 11px;
            color: #94a3b8;
            text-decoration: line-through;
        }

        /* Footer styling */
        .garudo-footer {
            background-color: #081021;
            color: #94a3b8;
            font-size: 12px;
        }
        .garudo-footer h6 {
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .garudo-footer a {
            color: #94a3b8;
            text-decoration: none;
            line-height: 2;
            transition: color 0.15s;
        }
        .garudo-footer a:hover {
            color: var(--garudo-yellow);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Top Utility Bar (Tier 1: Upgraded Height & Premium B2B Styling) -->
    <div class="top-utility-bar">
        <div class="container-fluid px-3 px-lg-4">
            <!-- Desktop Layout (>= 992px): Spacious, High-Contrast & Verified Supplier Badge -->
            <div class="d-none d-lg-flex justify-content-between align-items-center w-100">
                <div class="d-flex align-items-center gap-2">
                    <span class="top-trust-icon">
                        <i class="bi bi-shield-fill-check"></i>
                    </span>
                    <span class="text-white fw-semibold" style="font-size: 12.5px; letter-spacing: 0.15px;">
                        India's Most Trusted Building Material Marketplace
                    </span>
                    <span class="top-bar-badge ms-1">
                        <i class="bi bi-patch-check-fill text-warning me-1" style="font-size: 9.5px;"></i> B2B Verified
                    </span>
                </div>

                <div class="d-flex align-items-center gap-1" style="font-size: 12px;">
                    <a href="#" class="top-util-link">
                        <i class="bi bi-phone text-warning me-1.5"></i> Download App
                    </a>
                    <span class="top-util-divider"></span>
                    <a href="{{ route('customer.orders') }}" class="top-util-link">
                        <i class="bi bi-truck text-warning me-1.5"></i> Track Order
                    </a>
                    <span class="top-util-divider"></span>
                    @auth('vendor')
                        <a href="{{ route('vendor.dashboard') }}" class="top-util-vendor-pill">
                            <i class="bi bi-speedometer2 me-1.5"></i> Vendor Hub
                        </a>
                    @else
                        <a href="{{ route('vendor.register') }}" class="top-util-vendor-pill">
                            <i class="bi bi-shop me-1.5"></i> Vendor Hub / Sell
                        </a>
                    @endauth
                    <span class="top-util-divider"></span>
                    <a href="{{ route('feedback.index') }}" class="top-util-link">
                        <i class="bi bi-headset text-warning me-1.5"></i> Help & Support
                    </a>
                    <span class="top-util-divider"></span>
                    <!-- Language Selector Dropdown -->
                    <div class="dropdown d-inline-block">
                        <a href="#" class="top-util-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-translate text-warning me-1.5"></i> English
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-1.5" style="font-size: 12px; min-width: 150px; z-index: 1065;">
                            <li><a class="dropdown-item py-1.5 active fw-bold" href="#">English (EN)</a></li>
                            <li><a class="dropdown-item py-1.5" href="#">Hindi (हिंदी)</a></li>
                            <li><a class="dropdown-item py-1.5" href="#">Gujarati (ગુજરાતી)</a></li>
                            <li><a class="dropdown-item py-1.5" href="#">Marathi (मराठी)</a></li>
                            <li><a class="dropdown-item py-1.5" href="#">Tamil (தமிழ்)</a></li>
                            <li><a class="dropdown-item py-1.5" href="#">Telugu (తెలుగు)</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Mobile & Tablet Layout (< 992px): Sleek, Single-Line, Zero Clutter -->
            <div class="d-flex d-lg-none justify-content-between align-items-center" style="min-height: 28px;">
                <div class="d-flex align-items-center overflow-hidden me-2">
                    <span class="top-bar-badge-dot flex-shrink-0"></span>
                    <span class="text-white fw-medium text-truncate" style="font-size: 11px; letter-spacing: 0.1px;">
                        India's #1 Building Material Marketplace
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0" style="font-size: 11px;">
                    @auth('vendor')
                        <a href="{{ route('vendor.dashboard') }}" class="top-util-link text-warning fw-semibold">
                            <i class="bi bi-speedometer2 me-1"></i> Vendor Hub
                        </a>
                    @else
                        <a href="{{ route('vendor.register') }}" class="top-util-link text-warning fw-semibold">
                            <i class="bi bi-shop me-1"></i> Sell
                        </a>
                    @endauth
                    <span class="text-white-50">|</span>
                    <a href="{{ route('customer.orders') }}" class="top-util-link">
                        Track
                    </a>
                    <span class="text-white-50">|</span>
                    <div class="dropdown d-inline-block">
                        <a href="#" class="top-util-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe text-warning me-0.5"></i> EN
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-2 py-1" style="font-size: 11px; min-width: 130px; z-index: 1065;">
                            <li><a class="dropdown-item py-1 active fw-bold" href="#">English</a></li>
                            <li><a class="dropdown-item py-1" href="#">Hindi (हिंदी)</a></li>
                            <li><a class="dropdown-item py-1" href="#">Gujarati (ગુજરાતી)</a></li>
                            <li><a class="dropdown-item py-1" href="#">Marathi (मराठी)</a></li>
                            <li><a class="dropdown-item py-1" href="#">Tamil (தமிழ்)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Middle Bar (Tier 2: Crisp White, Logo, Search & Action Icons) -->
    <header class="main-header-bar sticky-top">
        <div class="container-fluid px-4">
            <div class="row align-items-center g-2 g-xl-3">
                <!-- 1. Brand Logo & Tagline (Left) -->
                <div class="col-xl-3 col-lg-3 col-6">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-light border-0 p-1 me-1.5 d-lg-none text-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileCategoriesOffcanvas" aria-controls="mobileCategoriesOffcanvas" title="Open Categories Menu">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                        <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center">
                            <div class="brand-cube-logo me-2">
                                <svg width="40" height="40" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Top isometric face (Yellow) -->
                                    <polygon points="21,4 37,13 21,22 5,13" fill="#ffb703" />
                                    <!-- Left isometric face (Deep Navy) -->
                                    <polygon points="5,13 21,22 21,39 5,30" fill="#07152b" />
                                    <!-- Right isometric face (Steel Navy Blue) -->
                                    <polygon points="21,22 37,13 37,30 21,39" fill="#1e3a8a" />
                                    <!-- Building Grid Accent Lines -->
                                    <line x1="13" y1="17.5" x2="13" y2="34.5" stroke="#ffffff" stroke-width="1.2" stroke-opacity="0.35" />
                                    <line x1="29" y1="17.5" x2="29" y2="34.5" stroke="#ffffff" stroke-width="1.2" stroke-opacity="0.35" />
                                    <line x1="5" y1="21.5" x2="21" y2="30.5" stroke="#ffffff" stroke-width="1" stroke-opacity="0.25" />
                                    <line x1="21" y1="30.5" x2="37" y2="21.5" stroke="#ffffff" stroke-width="1" stroke-opacity="0.25" />
                                </svg>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <span class="brand-name" style="font-size: 22px;">Garudo</span>
                                <span class="brand-tagline">Build Better, Together</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 2. Integrated Search Bar with Categories (Center) -->
                <div class="col-xl-5 col-lg-4 d-none d-lg-block">
                    <form action="{{ route('products.index') }}" method="GET" class="buildmart-search-container m-0">
                        <select class="buildmart-cat-select" name="category">
                            <option value="">All Categories</option>
                            @php
                                $selectCats = $headerCategories ?? \App\Models\Category::whereNull('parent_id')->where('status', 'active')->orderBy('sort_order')->orderBy('name')->get();
                            @endphp
                            @forelse($selectCats as $scat)
                                <option value="{{ $scat->slug }}" {{ request('category') == $scat->slug ? 'selected' : '' }}>{{ $scat->name }}</option>
                            @empty
                                <option value="cement" {{ request('category') == 'cement' ? 'selected' : '' }}>Cement & Concrete</option>
                                <option value="steel" {{ request('category') == 'steel' ? 'selected' : '' }}>Steel & TMT Bars</option>
                                <option value="tiles" {{ request('category') == 'tiles' ? 'selected' : '' }}>Tiles & Marble</option>
                                <option value="pipes" {{ request('category') == 'pipes' ? 'selected' : '' }}>Pipes & Fittings</option>
                                <option value="electricals" {{ request('category') == 'electricals' ? 'selected' : '' }}>Electricals & Solar</option>
                                <option value="sanitaryware" {{ request('category') == 'sanitaryware' ? 'selected' : '' }}>Sanitaryware</option>
                                <option value="paints" {{ request('category') == 'paints' ? 'selected' : '' }}>Paints & Coating</option>
                                <option value="hardware" {{ request('category') == 'hardware' ? 'selected' : '' }}>Tools & Hardware</option>
                            @endforelse
                        </select>
                        <input type="text" name="search" class="buildmart-search-input" placeholder="Search materials, products, brands and vendors..." value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="btn-buildmart-search">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </form>
                </div>

                <!-- 3. Quick Action Icons & Sign In / Register (Right) -->
                <div class="col-xl-4 col-lg-5 col-6 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-1 gap-sm-2">
                        <!-- Tenders -->
                        <a href="{{ route('customer.quotes') }}" class="buildmart-action-link d-none d-sm-inline-flex" title="B2B Tenders & RFQs">
                            <span class="buildmart-action-icon"><i class="bi bi-box-seam"></i></span>
                            <span class="buildmart-action-label">Tenders</span>
                        </a>

                        <!-- Auctions -->
                        <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="buildmart-action-link d-none d-sm-inline-flex" title="Material Auctions & Live Lots">
                            <span class="buildmart-action-icon"><i class="bi bi-hammer"></i></span>
                            <span class="buildmart-action-label">Auctions</span>
                        </a>

                        <!-- Become a Vendor -->
                        @auth('vendor')
                            <a href="{{ route('vendor.dashboard') }}" class="buildmart-action-link d-none d-md-inline-flex" title="Vendor Command Center">
                                <span class="buildmart-action-icon"><i class="bi bi-person-badge"></i></span>
                                <span class="buildmart-action-label">Vendor Hub</span>
                            </a>
                        @else
                            <a href="{{ route('vendor.register') }}" class="buildmart-action-link d-none d-md-inline-flex" title="Sell on Marketplace">
                                <span class="buildmart-action-icon"><i class="bi bi-person-badge"></i></span>
                                <span class="buildmart-action-label">Become a Vendor</span>
                            </a>
                        @endauth

                        <!-- Wishlist with Live Counter Badge -->
                        @php
                            $navWishlistCount = \App\Services\WishlistService::getCount();
                        @endphp
                        <a href="{{ route('wishlist.index') }}" class="buildmart-action-link" title="Saved Wishlist">
                            <span class="buildmart-action-icon">
                                <i class="bi bi-heart"></i>
                                @if($navWishlistCount > 0)
                                    <span class="buildmart-badge buildmart-badge-red">{{ $navWishlistCount }}</span>
                                @else
                                    <span class="buildmart-badge buildmart-badge-red">0</span>
                                @endif
                            </span>
                        </a>

                        <!-- Cart with Live Counter Badge -->
                        @php
                            $navCartCount = \App\Services\CartService::getCount();
                        @endphp
                        <a href="{{ route('cart.index') }}" class="buildmart-action-link" title="Procurement Cart">
                            <span class="buildmart-action-icon">
                                <i class="bi bi-bag"></i>
                                @if($navCartCount > 0)
                                    <span class="buildmart-badge buildmart-badge-orange">{{ $navCartCount }}</span>
                                @else
                                    <span class="buildmart-badge buildmart-badge-orange">0</span>
                                @endif
                            </span>
                        </a>

                        <!-- Sign In / Register Pill Button -->
                        @if(auth('customer')->check())
                            <div class="dropdown d-inline-block ms-1">
                                <button class="btn-signin-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="My Account">
                                    <i class="bi bi-person-circle text-warning"></i>
                                    <span class="d-none d-md-inline text-truncate" style="max-width: 100px;">{{ auth('customer')->user()->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2" style="font-size: 12px; min-width: 220px; z-index: 1060;">
                                    <li class="px-2 py-2 border-bottom mb-1 bg-light rounded">
                                        <div class="fw-bold text-dark text-truncate">{{ auth('customer')->user()->name }}</div>
                                        <div class="text-muted small text-truncate" style="font-size: 11px;">{{ auth('customer')->user()->email }}</div>
                                        <span class="badge bg-primary-subtle text-primary rounded-pill mt-1" style="font-size: 10px;">Verified Buyer</span>
                                    </li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.dashboard') }}"><i class="bi bi-grid-1x2-fill me-2 text-primary"></i> Buyer Dashboard</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.orders') }}"><i class="bi bi-bag-check-fill me-2 text-success"></i> My Orders</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.quotes') }}"><i class="bi bi-file-earmark-text-fill me-2 text-warning"></i> Quotations & RFQs</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.wishlist') }}"><i class="bi bi-heart-fill me-2 text-danger"></i> Saved Wishlist</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.membership.index') }}"><i class="bi bi-patch-check-fill me-2 text-info"></i> Pro Membership</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.settings') }}"><i class="bi bi-gear-fill me-2 text-secondary"></i> Account Settings</a></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('customer.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-1.5 rounded text-danger fw-semibold"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @elseif(auth('vendor')->check())
                            <div class="dropdown d-inline-block ms-1">
                                <button class="btn-signin-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Vendor Portal">
                                    <i class="bi bi-shop text-warning"></i>
                                    <span class="d-none d-md-inline text-truncate" style="max-width: 100px;">{{ auth('vendor')->user()->company_name ?? auth('vendor')->user()->name }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2" style="font-size: 12px; min-width: 230px; z-index: 1060;">
                                    <li class="px-2 py-2 border-bottom mb-1 bg-light rounded">
                                        <div class="fw-bold text-dark text-truncate">{{ auth('vendor')->user()->company_name }}</div>
                                        <div class="text-muted small text-truncate" style="font-size: 11px;">{{ auth('vendor')->user()->email }}</div>
                                        <span class="badge bg-success-subtle text-success rounded-pill mt-1" style="font-size: 10px;">Verified Seller</span>
                                    </li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('vendor.dashboard') }}"><i class="bi bi-grid-1x2-fill me-2 text-success"></i> Vendor Dashboard</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('vendor.products.index') }}"><i class="bi bi-box-seam me-2 text-primary"></i> Manage Products</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('vendor.orders.index') }}"><i class="bi bi-receipt-cutoff me-2 text-info"></i> Customer Orders</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('store.show', auth('vendor')->user()->company_slug ?? auth('vendor')->user()->id) }}" target="_blank"><i class="bi bi-eye-fill me-2 text-warning"></i> View My Storefront</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('vendor.store.design') }}"><i class="bi bi-palette-fill me-2 text-primary"></i> Design Storefront</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('vendor.subscription.index') }}"><i class="bi bi-patch-check-fill me-2 text-success"></i> Plans & Subscription</a></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('vendor.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-1.5 rounded text-danger fw-semibold"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @elseif(auth('admin')->check())
                            <a href="{{ route('admin.dashboard') }}" class="btn-signin-pill ms-1 text-decoration-none">
                                <i class="bi bi-shield-lock-fill text-warning"></i> Admin Panel
                            </a>
                        @else
                            <div class="dropdown d-inline-block ms-1">
                                <button class="btn-signin-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Sign In / Register
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2" style="font-size: 12px; min-width: 220px; z-index: 1060;">
                                    <li><h6 class="dropdown-header px-2 py-1 text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">B2B Buyer / Customer</h6></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.login') }}"><i class="bi bi-box-arrow-in-right me-2 text-primary"></i> Buyer Login</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('customer.register') }}"><i class="bi bi-person-plus me-2 text-primary"></i> Register as Buyer</a></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li><h6 class="dropdown-header px-2 py-1 text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">Seller / Manufacturer</h6></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('vendor.login') }}"><i class="bi bi-shop me-2 text-success"></i> Seller Login</a></li>
                                    <li><a class="dropdown-item py-1.5 rounded" href="{{ route('vendor.register') }}"><i class="bi bi-building-add me-2 text-success"></i> Register as Seller</a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Mobile Search Bar (Only visible on screens < 992px) -->
                <div class="col-12 d-lg-none mt-1">
                    <form action="{{ route('products.index') }}" method="GET" class="buildmart-search-container m-0">
                        <input type="text" name="search" class="buildmart-search-input" placeholder="Search materials, products, brands..." value="{{ request('search') }}">
                        <button type="submit" class="btn-buildmart-search px-3">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Tier 3: Bottom Category Strip (Dark Navy Bar Matching Screenshot) -->
    <nav class="bottom-category-bar">
        <div class="container-fluid px-3 px-lg-4">
            <!-- Desktop View (>= 992px): Slide-In Categories Drawer Trigger + Full Category Links Row -->
            <div class="cat-strip-container d-none d-lg-flex align-items-center">
                <!-- All Categories Trigger: Desktop (>= 992px) Opens the Category Slider Drawer -->
                <div class="d-flex align-items-center">
                    <button class="btn-all-cat-trigger" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileCategoriesOffcanvas" aria-controls="mobileCategoriesOffcanvas" title="Open Categories Slider">
                        <span class="desktop-cat-icon-chip">
                            <i class="bi bi-list"></i>
                        </span>
                        <span>All Categories</span>
                        <i class="bi bi-chevron-right text-warning ms-1" style="font-size: 11px; opacity: 0.85;"></i>
                    </button>
                    <div class="desktop-cat-divider"></div>
                </div>

                <!-- Navigation Links (All 12 Desktop Links Preserved) -->
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="cat-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('products.index') }}" class="cat-nav-link {{ request()->routeIs('products.*') && !request('category') && !request('filter') ? 'active' : '' }}">Products</a>
                    <a href="{{ route('brands.index') }}" class="cat-nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}">Vendors</a>
                    <a href="{{ route('products.index', ['category' => 'services']) }}" class="cat-nav-link">Services</a>
                    <a href="{{ route('products.index', ['filter' => 'contractors']) }}" class="cat-nav-link">Contractors</a>
                    <a href="{{ route('products.index', ['filter' => 'architects']) }}" class="cat-nav-link">Architects & Designers</a>
                    <a href="{{ route('products.index', ['filter' => 'labour']) }}" class="cat-nav-link">Labour</a>
                    <a href="{{ route('products.index', ['filter' => 'projects']) }}" class="cat-nav-link">Projects</a>
                    <a href="{{ route('customer.quotes') }}" class="cat-nav-link">Tenders</a>
                    <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="cat-nav-link">Auctions</a>
                    <a href="{{ route('home') }}#deals-section" class="cat-nav-link text-warning fw-semibold"><i class="bi bi-lightning-fill text-warning me-1"></i>Deals</a>
                    <a href="{{ route('feedback.index') }}" class="cat-nav-link">Blogs</a>
                </div>
            </div>

            <!-- Mobile & Tablet View (< 992px): High-End Category Trigger Bar -->
            <div class="mobile-cat-trigger-bar d-flex d-lg-none" role="button" data-bs-toggle="offcanvas" data-bs-target="#mobileCategoriesOffcanvas" aria-controls="mobileCategoriesOffcanvas">
                <div class="mobile-cat-left">
                    <span class="mobile-cat-icon-chip">
                        <i class="bi bi-list"></i>
                    </span>
                    <span class="mobile-cat-title">All Categories</span>
                    @php
                        if (!isset($dynCount)) {
                            $dynamicCats = $headerCategories ?? \App\Models\Category::whereNull('parent_id')->where('status', 'active')->with(['children' => function($q){ $q->where('status', 'active'); }])->withCount('products')->orderBy('sort_order')->orderBy('name')->get();
                            $dynCount = $dynamicCats->count();
                        }
                    @endphp
                    <span class="mobile-cat-badge">
                        {{ $dynCount > 0 ? $dynCount . ' Categories' : 'Categories' }}
                    </span>
                </div>
                <span class="mobile-cat-arrow-btn">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </div>
        </div>
    </nav>

    <!-- Mobile & Tablet Slide-In Categories Drawer (Offcanvas Slider) -->
    <div class="offcanvas offcanvas-start mobile-cat-offcanvas" tabindex="-1" id="mobileCategoriesOffcanvas" aria-labelledby="mobileCategoriesOffcanvasLabel">
        <!-- Offcanvas Header -->
        <div class="offcanvas-header mobile-cat-header">
            <div class="d-flex align-items-center">
                <div class="brand-cube-logo me-2.5">
                    <svg width="36" height="36" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="21,4 37,13 21,22 5,13" fill="#ffb703" />
                        <polygon points="5,13 21,22 21,39 5,30" fill="#07152b" />
                        <polygon points="21,22 37,13 37,30 21,39" fill="#1e3a8a" />
                        <line x1="13" y1="17.5" x2="13" y2="34.5" stroke="#ffffff" stroke-width="1.2" stroke-opacity="0.35" />
                        <line x1="29" y1="17.5" x2="29" y2="34.5" stroke="#ffffff" stroke-width="1.2" stroke-opacity="0.35" />
                    </svg>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-white fw-bold" style="font-size: 19px; letter-spacing: -0.4px;">Garudo</span>
                    <span class="text-white-50" style="font-size: 9.5px; font-weight: 500;">Build Better, Together</span>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <!-- Offcanvas Body -->
        <div class="offcanvas-body mobile-cat-body">
            <!-- User Status Card -->
            @if(auth('customer')->check())
                <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center me-2.5" style="width: 38px; height: 38px; font-size: 15px;">
                            {{ substr(auth('customer')->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark text-truncate" style="font-size: 13px; max-width: 170px;">{{ auth('customer')->user()->name }}</div>
                            <span class="badge bg-primary-subtle text-primary rounded-pill" style="font-size: 9.5px;">Buyer Account</span>
                        </div>
                    </div>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;">Dashboard</a>
                </div>
            @elseif(auth('vendor')->check())
                <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success-subtle text-success fw-bold d-flex align-items-center justify-content-center me-2.5" style="width: 38px; height: 38px; font-size: 15px;">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark text-truncate" style="font-size: 13px; max-width: 170px;">{{ auth('vendor')->user()->company_name }}</div>
                            <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 9.5px;">Verified Seller</span>
                        </div>
                    </div>
                    <a href="{{ route('vendor.dashboard') }}" class="btn btn-sm btn-outline-success py-1 px-2" style="font-size: 11px;">Portal</a>
                </div>
            @else
                <div class="p-3 bg-white border-bottom">
                    <div class="small text-muted mb-2">Welcome to India's Building Materials Marketplace</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('customer.login') }}" class="btn btn-sm text-white flex-grow-1 py-1.5 fw-bold" style="background-color: #07152b; font-size: 11.5px;">
                            <i class="bi bi-person me-1"></i> Buyer Login
                        </a>
                        <a href="{{ route('vendor.login') }}" class="btn btn-sm btn-yellow flex-grow-1 py-1.5 fw-bold" style="font-size: 11.5px;">
                            <i class="bi bi-shop me-1"></i> Seller Login
                        </a>
                    </div>
                </div>
            @endif

            <!-- Section 1: All Categories (The 12 Categories from the navbar) -->
            <div class="mobile-drawer-section-title">
                <span class="d-flex align-items-center gap-1.5">
                    <i class="bi bi-grid-fill text-warning"></i>
                    <span>Marketplace Categories</span>
                </span>
                <span class="badge bg-warning text-dark rounded-pill fw-bold" style="font-size: 9.5px;">12 Categories</span>
            </div>
            <div class="bg-white">
                <!-- 1. Home -->
                <a href="{{ route('home') }}" class="mobile-cat-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-house-door-fill"></i>
                        </span>
                        <div>
                            <div>Home</div>
                            <div class="mobile-cat-subtext">Main marketplace homepage</div>
                        </div>
                    </div>
                    @if(request()->routeIs('home'))
                        <span class="badge bg-warning text-dark rounded-pill fw-bold" style="font-size: 9.5px;">Active</span>
                    @else
                        <i class="bi bi-chevron-right text-muted small"></i>
                    @endif
                </a>

                <!-- 2. Products -->
                <a href="{{ route('products.index') }}" class="mobile-cat-item {{ request()->routeIs('products.*') && !request('category') && !request('filter') ? 'active' : '' }}">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-success-subtle text-success">
                            <i class="bi bi-box-seam-fill"></i>
                        </span>
                        <div>
                            <div>Products</div>
                            <div class="mobile-cat-subtext">All construction supplies & goods</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 3. Vendors -->
                <a href="{{ route('brands.index') }}" class="mobile-cat-item {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-info-subtle text-info">
                            <i class="bi bi-shop"></i>
                        </span>
                        <div>
                            <div>Vendors</div>
                            <div class="mobile-cat-subtext">Verified manufacturers & dealers</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 4. Services -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-secondary-subtle text-secondary">
                            <i class="bi bi-gear-wide-connected"></i>
                        </span>
                        <div>
                            <div>Services</div>
                            <div class="mobile-cat-subtext">Civil contracting & fabrication</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 5. Contractors -->
                <a href="{{ route('products.index', ['filter' => 'contractors']) }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-person-workspace"></i>
                        </span>
                        <div>
                            <div>Contractors</div>
                            <div class="mobile-cat-subtext">General, EPC & specialized builders</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 6. Architects & Designers -->
                <a href="{{ route('products.index', ['filter' => 'architects']) }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-warning-subtle text-warning">
                            <i class="bi bi-pencil-ruler"></i>
                        </span>
                        <div>
                            <div>Architects & Designers</div>
                            <div class="mobile-cat-subtext">Interior designers & structural planners</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 7. Labour -->
                <a href="{{ route('products.index', ['filter' => 'labour']) }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-info-subtle text-info">
                            <i class="bi bi-people-fill"></i>
                        </span>
                        <div>
                            <div>Labour</div>
                            <div class="mobile-cat-subtext">Masons, bar-benders & site teams</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 8. Projects -->
                <a href="{{ route('products.index', ['filter' => 'projects']) }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-success-subtle text-success">
                            <i class="bi bi-buildings-fill"></i>
                        </span>
                        <div>
                            <div>Projects</div>
                            <div class="mobile-cat-subtext">Commercial, infra & housing sites</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 9. Tenders -->
                <a href="{{ route('customer.quotes') }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-warning-subtle text-warning">
                            <i class="bi bi-file-earmark-ruled-fill"></i>
                        </span>
                        <div>
                            <div>Tenders</div>
                            <div class="mobile-cat-subtext">Material RFQs & procurement bids</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>

                <!-- 10. Auctions -->
                <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-danger-subtle text-danger">
                            <i class="bi bi-hammer"></i>
                        </span>
                        <div>
                            <div>Auctions</div>
                            <div class="mobile-cat-subtext">Live surplus material bidding</div>
                        </div>
                    </div>
                    <span class="badge bg-danger-subtle text-danger rounded-pill fw-bold" style="font-size: 9.5px;">Live</span>
                </a>

                <!-- 11. Deals -->
                <a href="{{ route('home') }}#deals-section" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-warning text-dark">
                            <i class="bi bi-lightning-fill"></i>
                        </span>
                        <div>
                            <div>Deals</div>
                            <div class="mobile-cat-subtext">Discounted prices & clearance stock</div>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill fw-bold" style="font-size: 9.5px;">Hot Deals</span>
                </a>

                <!-- 12. Blogs -->
                <a href="{{ route('feedback.index') }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-secondary-subtle text-secondary">
                            <i class="bi bi-newspaper"></i>
                        </span>
                        <div>
                            <div>Blogs</div>
                            <div class="mobile-cat-subtext">Building guides & industry articles</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>
            </div>

            <!-- Section 2: Main Categories (Dynamic from Admin) -->
            <div class="mobile-drawer-section-title mt-2">
                <span class="d-flex align-items-center gap-1.5">
                    <i class="bi bi-bricks text-warning"></i>
                    <span>Main Categories</span>
                </span>
                <span class="badge bg-warning text-dark rounded-pill fw-bold" style="font-size: 9.5px;">
                    {{ (isset($dynamicCats) && $dynamicCats->count() > 0) ? $dynamicCats->count() . ' Categories' : 'All Categories' }}
                </span>
            </div>
            <div class="bg-white">
                @forelse($dynamicCats as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-primary-subtle text-primary">
                                <i class="bi {{ $cat->icon ?: 'bi-tags' }}"></i>
                            </span>
                            <div>
                                <div>{{ $cat->name }}</div>
                                <div class="mobile-cat-subtext">
                                    @if($cat->children && $cat->children->count() > 0)
                                        {{ $cat->children->pluck('name')->take(3)->join(', ') }}
                                    @elseif($cat->products_count > 0)
                                        {{ $cat->products_count }} {{ Str::plural('product', $cat->products_count) }} available
                                    @else
                                        Explore catalog & materials
                                    @endif
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                @empty
                    <a href="{{ route('products.index', ['category' => 'cement']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-primary-subtle text-primary"><i class="bi bi-box"></i></span>
                            <div><div>Cement & Concrete</div><div class="mobile-cat-subtext">OPC 53, PPC, White Cement, RMC</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'steel']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-danger-subtle text-danger"><i class="bi bi-layers"></i></span>
                            <div><div>Steel & TMT Bars</div><div class="mobile-cat-subtext">Fe 550D, Beams, Channels</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'tiles']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-info-subtle text-info"><i class="bi bi-grid"></i></span>
                            <div><div>Tiles, Marble & Granite</div><div class="mobile-cat-subtext">Vitrified, Ceramic, Natural Stone</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'pipes']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-success-subtle text-success"><i class="bi bi-droplet"></i></span>
                            <div><div>Pipes & Water Fittings</div><div class="mobile-cat-subtext">CPVC, UPVC, SWR, Water Tanks</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'electricals']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-warning-subtle text-warning"><i class="bi bi-lightning-charge"></i></span>
                            <div><div>Electricals & Solar</div><div class="mobile-cat-subtext">Cables, Switchgear, Panels</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'sanitaryware']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-primary-subtle text-primary"><i class="bi bi-water"></i></span>
                            <div><div>Sanitaryware & Bath</div><div class="mobile-cat-subtext">Wash Basins, Closets, Faucets</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'paints']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-danger-subtle text-danger"><i class="bi bi-paint-bucket"></i></span>
                            <div><div>Paints & Waterproofing</div><div class="mobile-cat-subtext">Emulsions, Primers, Sealants</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'hardware']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-secondary-subtle text-secondary"><i class="bi bi-tools"></i></span>
                            <div><div>Tools, Fasteners & Safety</div><div class="mobile-cat-subtext">Power Tools, Screws, Helmets</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'plywood']) }}" class="mobile-cat-item">
                        <div class="d-flex align-items-center">
                            <span class="mobile-cat-icon-box bg-warning-subtle text-warning"><i class="bi bi-tree"></i></span>
                            <div><div>Wood, Timber & Plywood</div><div class="mobile-cat-subtext">Marine Ply, Blockboards, Doors</div></div>
                        </div>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                @endforelse
            </div>

            <!-- Section 3: Utility & Support -->
            <div class="mobile-drawer-section-title mt-2">
                <span class="d-flex align-items-center gap-1.5">
                    <i class="bi bi-headset text-warning"></i>
                    <span>Customer Support & Services</span>
                </span>
            </div>
            <div class="bg-white mb-3">
                <a href="{{ route('customer.orders') }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-truck"></i>
                        </span>
                        <div>
                            <div>Track Order</div>
                            <div class="mobile-cat-subtext">Live dispatch & delivery updates</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>
                <a href="{{ route('vendor.register') }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-success-subtle text-success">
                            <i class="bi bi-shop"></i>
                        </span>
                        <div>
                            <div>Sell with Us</div>
                            <div class="mobile-cat-subtext">Join as a verified vendor / manufacturer</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>
                <a href="{{ route('feedback.index') }}" class="mobile-cat-item">
                    <div class="d-flex align-items-center">
                        <span class="mobile-cat-icon-box bg-info-subtle text-info">
                            <i class="bi bi-chat-dots"></i>
                        </span>
                        <div>
                            <div>Help & Feedback</div>
                            <div class="mobile-cat-subtext">24x7 customer support & queries</div>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>
                <div class="p-3 bg-light text-center small text-muted">
                    <i class="bi bi-telephone-fill text-warning me-1"></i> Bulk Helpline: <strong>+91 1800-123-4567</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Body Content -->
    <main>
        @yield('content')
    </main>

    <!-- Comprehensive Footer Matching Design Image -->
    <footer class="garudo-footer pt-5 pb-3 mt-5">
        <div class="container-fluid px-4">
            <div class="row g-4 pb-4 border-bottom border-secondary border-opacity-25">
                <!-- Column 1: Brand Info -->
                <div class="col-lg-3 col-md-6">
                    <div class="bg-navy text-white fw-black fs-4 px-2 py-1 rounded d-inline-block mb-3" style="font-weight: 900;">
                        GARUDO<span class="text-yellow">.IN</span>
                    </div>
                    <p class="small text-secondary mb-3" style="line-height: 1.6;">
                        India's Premier B2B Marketplace for Building Materials, Construction Supplies, Industrial Equipment, and Civil Infrastructure Procurement.
                    </p>
                    <div class="bg-navy-light p-3 rounded mb-3">
                        <div class="fw-bold text-white small mb-1">TOP PROMOTIONAL OFFER</div>
                        <div class="text-yellow small">Get 5% Instant Cash Back on Bulk GST Invoicing orders over ₹50,000!</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">About Garudo</a></li>
                        <li><a href="#">Company Overview</a></li>
                        <li><a href="#">Press & News</a></li>
                        <li><a href="#">Career Opportunities</a></li>
                        <li><a href="#">Bulk Order RFQ</a></li>
                        <li><a href="#">Supplier Verification</a></li>
                        <li><a href="#">Garudo Logistics</a></li>
                    </ul>
                </div>

                <!-- Column 3: Top Categories -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6>Top Categories</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Cement & Concrete</a></li>
                        <li><a href="#">TMT Steel Rebars</a></li>
                        <li><a href="#">Red Bricks & AAC Blocks</a></li>
                        <li><a href="#">PVC & CPVC Pipes</a></li>
                        <li><a href="#">Electrical Cables & Switches</a></li>
                        <li><a href="#">Paints, Emulsions & Primers</a></li>
                        <li><a href="#">Tiles, Marble & Granite</a></li>
                    </ul>
                </div>

                <!-- Column 4: Buyer & Seller Services -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6>Customer Service</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Help Center & FAQ</a></li>
                        <li><a href="#">Track Order Status</a></li>
                        <li><a href="#">Escrow Payment Guarantee</a></li>
                        <li><a href="#">Returns & Refund Policy</a></li>
                        <li><a href="{{ route('vendor.register') }}">Sell on Garudo</a></li>
                        <li><a href="#">Vendor Portal Login</a></li>
                        <li><a href="#">GST Invoice Support</a></li>
                    </ul>
                </div>

                <!-- Column 5: Contact & Mobile Apps -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h6>Contact Us</h6>
                    <p class="small mb-1"><i class="bi bi-geo-alt-fill text-yellow me-2"></i> Garudo Tower, Plot 42, Industrial Zone, Sector 62, Noida, NCR, India</p>
                    <p class="small mb-1"><i class="bi bi-telephone-fill text-yellow me-2"></i> +91 1800-123-4567 (Toll Free)</p>
                    <p class="small mb-3"><i class="bi bi-envelope-fill text-yellow me-2"></i> support@garudo.in</p>

                    <h6>Download Mobile App</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-light text-start py-1 px-2" style="font-size: 10px;">
                            <i class="bi bi-google-play fs-6 me-1"></i> Google Play
                        </button>
                        <button class="btn btn-sm btn-outline-light text-start py-1 px-2" style="font-size: 10px;">
                            <i class="bi bi-apple fs-6 me-1"></i> App Store
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 small text-secondary">
                <div>© {{ date('Y') }} GARUDO B2B MARKETPLACE PRIVATE LIMITED. All rights reserved.</div>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary">Privacy Policy</a>
                    <a href="#" class="text-secondary">Terms of Service</a>
                    <a href="#" class="text-secondary">Security</a>
                    <a href="#" class="text-secondary">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery & Bootstrap 5 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function ajaxAddToCart(productId, qty) {
            qty = qty || 1;
            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ product_id: productId, quantity: qty })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    window.location.href = "{{ route('cart.index') }}";
                }
            })
            .catch(() => {
                window.location.href = "{{ route('cart.index') }}";
            });
        }

        function ajaxToggleWishlist(productId) {
            fetch(`/wishlist/toggle/${productId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    window.location.href = "{{ route('wishlist.index') }}";
                }
            })
            .catch(() => {
                window.location.href = "{{ route('wishlist.index') }}";
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

