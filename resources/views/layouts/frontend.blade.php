<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Garudo - B2B Building Materials Marketplace')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}">
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
        .brand-logo-img {
            height: 48px;
            max-height: 52px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
            display: block;
            transition: transform 0.2s ease;
        }
        .brand-logo-img:hover {
            transform: scale(1.02);
        }
        @media (max-width: 576px) {
            .brand-logo-img {
                height: 38px;
                max-width: 160px;
            }
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

        /* Premium Footer Styling Matching Reference Image */
        .garudo-footer {
            background-color: #0b1322;
            color: #94a3b8;
            font-size: 13px;
        }
        .footer-logo {
            display: inline-flex;
            align-items: baseline;
            font-weight: 900;
            font-size: 26px;
            letter-spacing: 0.5px;
            text-decoration: none;
            line-height: 1;
        }
        .footer-logo-main {
            color: #ffffff;
        }
        .footer-logo-dot {
            color: #ff6a00;
        }
        .footer-tagline {
            color: #8fa0b5;
            font-size: 12.5px;
            line-height: 1.6;
            margin-top: 14px;
            margin-bottom: 20px;
            max-width: 320px;
        }
        .footer-alerts-box {
            background: #111b2e;
            border: 1px solid #1c2b45;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 18px;
            max-width: 340px;
        }
        .footer-alerts-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 13.5px;
            margin-bottom: 4px;
        }
        .footer-alerts-sub {
            color: #8fa0b5;
            font-size: 11px;
            line-height: 1.4;
            margin-bottom: 12px;
        }
        .footer-alerts-input {
            background: #0b1322 !important;
            border: 1px solid #243552 !important;
            color: #ffffff !important;
            font-size: 12px;
            border-radius: 6px;
            padding: 7px 12px;
        }
        .footer-alerts-input::placeholder {
            color: #5d6f8a;
        }
        .footer-alerts-input:focus {
            border-color: #ff6a00 !important;
            box-shadow: 0 0 0 2px rgba(255, 106, 0, 0.2) !important;
        }
        .footer-alerts-btn {
            background: #ff6a00;
            color: #ffffff;
            font-weight: 700;
            font-size: 12.5px;
            border-radius: 6px;
            padding: 7px 16px;
            border: none;
            white-space: nowrap;
            transition: background 0.2s ease;
        }
        .footer-alerts-btn:hover {
            background: #e65c00;
            color: #ffffff;
        }
        .footer-pay-label {
            color: #8fa0b5;
            font-size: 11.5px;
            margin-right: 6px;
        }
        .footer-pay-chip {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 10.5px;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            margin-right: 4px;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .footer-social-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            text-decoration: none;
            margin-right: 6px;
            margin-top: 14px;
            transition: all 0.2s ease;
        }
        .footer-social-btn:hover {
            background: #ff6a00;
            color: #ffffff;
            transform: translateY(-2px);
        }
        .footer-col-title {
            color: #ffffff;
            font-weight: 800;
            font-size: 14.5px;
            margin-bottom: 18px;
            letter-spacing: 0.2px;
        }
        .footer-links-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
        .footer-links-list li {
            margin-bottom: 9px;
        }
        .footer-links-list a {
            color: #8fa0b5;
            text-decoration: none;
            font-size: 12.5px;
            transition: color 0.15s ease, transform 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .footer-links-list a:hover {
            color: #ffffff;
            transform: translateX(2px);
        }
        .footer-arrow {
            color: #ff6a00;
            font-weight: 800;
            font-size: 11px;
            font-family: monospace, sans-serif;
        }
        .footer-contact-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
        }
        .footer-contact-icon {
            color: #ff6a00;
            font-size: 15px;
            margin-top: 2px;
            flex-shrink: 0;
        }
        .footer-contact-title {
            color: #ffffff;
            font-size: 12.5px;
            font-weight: 700;
            line-height: 1.2;
        }
        .footer-contact-text {
            color: #8fa0b5;
            font-size: 11.5px;
            margin-top: 2px;
            line-height: 1.4;
        }
        .footer-app-btn {
            background: #111b2e;
            border: 1px solid #1f2f4a;
            border-radius: 8px;
            padding: 7px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            width: 155px;
            transition: all 0.2s ease;
        }
        .footer-app-btn:hover {
            background: #17243d;
            border-color: #2b3e5e;
            transform: translateY(-1px);
        }
        .footer-app-icon {
            flex-shrink: 0;
        }
        .footer-app-text {
            line-height: 1.15;
        }
        .footer-app-sub {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #8fa0b5;
            text-transform: uppercase;
            display: block;
        }
        .footer-app-title {
            font-size: 13px;
            font-weight: 800;
            color: #ffffff;
            display: block;
        }
        .footer-bottom-bar {
            background: #060b14;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 16px 0;
            margin-top: 40px;
        }
        .footer-bottom-text {
            color: #718096;
            font-size: 11.5px;
            line-height: 1.6;
        }
        .footer-bottom-text a {
            color: #8fa0b5;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .footer-bottom-text a:hover {
            color: #ffffff;
        }

        /* ---------------- VIBRANT PRE-LAUNCH ANNOUNCEMENT TICKER STRIP ---------------- */
        .garudo-launch-banner-strip {
            background: linear-gradient(90deg, #ec4899 0%, #8b5cf6 20%, #3b82f6 40%, #10b981 60%, #f59e0b 80%, #ef4444 100%);
            background-size: 300% 100%;
            border-bottom: 2px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 4px 18px rgba(139, 92, 246, 0.4);
            position: relative;
            z-index: 1045;
            animation: vibrantStripGradient 10s ease infinite;
            overflow: hidden;
        }

        @keyframes vibrantStripGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .launch-strip-container {
            display: flex;
            align-items: center;
            height: 38px;
            padding: 0 16px;
            gap: 14px;
            position: relative;
        }

        .launch-strip-pinned-left {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            z-index: 2;
        }

        .launch-pill-badge {
            background: #ffffff;
            color: #0f172a;
            font-weight: 800;
            font-size: 11px;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 9999px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .launch-pill-badge .bi-rocket-takeoff-fill {
            color: #ef4444;
            font-size: 13px;
        }

        .animate-rocket {
            display: inline-block;
            animation: rocketFloat 2s ease-in-out infinite;
        }

        @keyframes rocketFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-3px) rotate(6deg); }
        }

        /* Continuous Smooth Scrolling Marquee */
        .launch-marquee-wrapper {
            flex: 1;
            overflow: hidden;
            position: relative;
            white-space: nowrap;
            mask-image: linear-gradient(to right, transparent 0%, black 25px, black calc(100% - 25px), transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 25px, black calc(100% - 25px), transparent 100%);
        }

        .launch-marquee-track {
            display: inline-flex;
            white-space: nowrap;
            will-change: transform;
            animation: marqueeAnnouncement 28s linear infinite;
        }

        .launch-marquee-track:hover {
            animation-play-state: paused;
        }

        @keyframes marqueeAnnouncement {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .launch-marquee-content {
            display: inline-flex;
            align-items: center;
            gap: 24px;
            padding-right: 24px;
        }

        .ticker-item {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.45);
            letter-spacing: 0.25px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .ticker-highlight {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 1px 8px;
            border-radius: 999px;
            font-weight: 800;
            color: #ffedd5;
        }

        .ticker-sep {
            color: #fef08a;
            font-size: 13px;
            opacity: 0.9;
            filter: drop-shadow(0 0 4px rgba(254, 240, 138, 0.6));
        }

        .launch-strip-pinned-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            z-index: 2;
        }

        .launch-date-pill {
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            padding: 3.5px 12px;
            border-radius: 9999px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            display: inline-flex;
            align-items: center;
        }

        .launch-dismiss-btn {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            cursor: pointer;
            font-size: 11px;
            transition: all 0.2s ease;
        }

        .launch-dismiss-btn:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .launch-strip-container {
                padding: 0 10px;
                gap: 8px;
                height: 36px;
            }
            .launch-pill-badge {
                font-size: 10px;
                padding: 3px 8px;
            }
            .ticker-item {
                font-size: 11.5px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @if(($launchSettings['launch_mode_active'] ?? '1') == '1')
    <!-- Official Launch Announcement Scrolling Ticker Strip -->
    <div id="garudoLaunchStrip" class="garudo-launch-banner-strip">
        <div class="launch-strip-container">
            <!-- Pinned Left Badge -->
            <div class="launch-strip-pinned-left">
                <span class="launch-pill-badge">
                    <i class="bi bi-rocket-takeoff-fill animate-rocket"></i>
                    <span>Official Launch</span>
                </span>
            </div>

            <!-- Continuous Announcement Marquee -->
            <div class="launch-marquee-wrapper" onclick="showPreLaunchModal();" style="cursor: pointer;" title="Click for details">
                <div class="launch-marquee-track">
                    <div class="launch-marquee-content">
                        <span class="ticker-item">
                            <i class="bi bi-megaphone-fill text-warning"></i>
                            <span>{{ $launchSettings['launch_strip_text'] ?? 'Official Launching on 20th October | Pre-Launch Preview Active' }}</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-calendar2-check-fill text-warning"></i>
                            <span>Going Live on <span class="ticker-highlight">{{ $launchSettings['launch_date'] ?? '20th October' }}</span></span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-bag-check-fill text-warning"></i>
                            <span>Online Direct Purchasing & Checkout Opens on <span class="ticker-highlight">{{ $launchSettings['launch_date'] ?? '20th October' }}</span>!</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-heart-fill text-danger"></i>
                            <span>Explore 10,000+ Products & Add to Wishlist for 1-Click Ordering!</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-shield-check text-info"></i>
                            <span>India's Leading B2B Commerce & Wholesale Platform</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                    </div>

                    <!-- Duplicate for infinite seamless scroll -->
                    <div class="launch-marquee-content" aria-hidden="true">
                        <span class="ticker-item">
                            <i class="bi bi-megaphone-fill text-warning"></i>
                            <span>{{ $launchSettings['launch_strip_text'] ?? 'Official Launching on 20th October | Pre-Launch Preview Active' }}</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-calendar2-check-fill text-warning"></i>
                            <span>Going Live on <span class="ticker-highlight">{{ $launchSettings['launch_date'] ?? '20th October' }}</span></span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-bag-check-fill text-warning"></i>
                            <span>Online Direct Purchasing & Checkout Opens on <span class="ticker-highlight">{{ $launchSettings['launch_date'] ?? '20th October' }}</span>!</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-heart-fill text-danger"></i>
                            <span>Explore 10,000+ Products & Add to Wishlist for 1-Click Ordering!</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                        <span class="ticker-item">
                            <i class="bi bi-shield-check text-info"></i>
                            <span>India's Leading B2B Commerce & Wholesale Platform</span>
                        </span>
                        <span class="ticker-sep">✦</span>
                    </div>
                </div>
            </div>

            <!-- Pinned Right Badge & Dismiss -->
            <div class="launch-strip-pinned-right">
                <span class="launch-date-pill d-none d-md-inline-flex">
                    <i class="bi bi-calendar-event-fill me-1.5 text-warning"></i> Going Live: <strong>{{ $launchSettings['launch_date'] ?? '20th October' }}</strong>
                </span>
                <button type="button" class="launch-dismiss-btn" onclick="document.getElementById('garudoLaunchStrip').style.display='none';" title="Dismiss Announcement">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

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
                        {{ $launchSettings['header_tagline'] ?? "India's Leading B2B Commerce & Wholesale Network" }}
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
                        {{ $launchSettings['header_tagline_mobile'] ?? "India's #1 B2B Commerce & Wholesale Network" }}
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
                        <a href="{{ route('home') }}" class="text-decoration-none d-inline-flex align-items-center" title="Garudo B2B Marketplace">
                            <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" class="brand-logo-img">
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
            <a href="{{ route('home') }}" class="d-inline-flex align-items-center text-decoration-none">
                <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" style="height: 38px; max-height: 40px; width: auto; object-fit: contain;">
            </a>
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

    <!-- Comprehensive Footer Matching Design Image 1 -->
    <footer class="garudo-footer pt-5 pb-0 mt-5">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row g-4 justify-content-between">
                <!-- Column 1: Brand, Tagline, Price Alerts, Payments, Socials -->
                <div class="col-lg-3 col-xl-3">
                    <a href="{{ route('home') }}" class="footer-logo d-inline-block mb-3 text-decoration-none">
                        <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" style="height: 44px; width: auto; object-fit: contain;">
                    </a>
                    <p class="footer-tagline">
                        India's #1 Building Materials Marketplace. TMT Bars, Cement, AAC Blocks, ACP Panels and 10,000+ products from 5000+ verified vendors.
                    </p>

                    <!-- Get Price Alerts & Deals Box -->
                    <div class="footer-alerts-box">
                        <div class="footer-alerts-title">Get Price Alerts &amp; Deals</div>
                        <div class="footer-alerts-sub">Subscribe for market price updates, exclusive deals &amp; new arrivals.</div>
                        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you for subscribing to Garudo Price Alerts!');" class="d-flex gap-2">
                            <input type="email" class="form-control form-control-sm footer-alerts-input" placeholder="Enter your email" required>
                            <button type="submit" class="btn footer-alerts-btn">Subscribe</button>
                        </form>
                    </div>

                    <!-- Payment Badges -->
                    <div class="mb-2">
                        <span class="footer-pay-label">We accept:</span>
                        <span class="footer-pay-chip">UPI</span>
                        <span class="footer-pay-chip">Razorpay</span>
                        <span class="footer-pay-chip">Net Banking</span>
                        <span class="footer-pay-chip">Credit Card</span>
                        <div class="mt-1" style="margin-left: 65px;">
                            <span class="footer-pay-chip">EMI</span>
                            <span class="footer-pay-chip">COD</span>
                        </div>
                    </div>

                    <!-- Social Icons -->
                    <div class="d-flex align-items-center flex-wrap">
                        <a href="https://facebook.com" target="_blank" rel="noopener" class="footer-social-btn" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://instagram.com" target="_blank" rel="noopener" class="footer-social-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://twitter.com" target="_blank" rel="noopener" class="footer-social-btn" title="X (Twitter)"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://youtube.com" target="_blank" rel="noopener" class="footer-social-btn" title="YouTube"><i class="bi bi-youtube"></i></a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener" class="footer-social-btn" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="https://wa.me" target="_blank" rel="noopener" class="footer-social-btn" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-lg-2 col-md-4 col-6">
                    <h6 class="footer-col-title">Quick Links</h6>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('home') }}"><span class="footer-arrow">&gt;</span> Home</a></li>
                        <li><a href="{{ route('products.index') }}"><span class="footer-arrow">&gt;</span> Shop All Products</a></li>
                        <li><a href="{{ url('/vendors') }}"><span class="footer-arrow">&gt;</span> All Vendors</a></li>
                        <li><a href="{{ route('products.index', ['filter' => 'deals']) }}"><span class="footer-arrow">&gt;</span> Today's Deals</a></li>
                        <li><a href="{{ route('products.index', ['filter' => 'new']) }}"><span class="footer-arrow">&gt;</span> New Arrivals</a></li>
                        <li><a href="{{ url('/boq-estimator') }}"><span class="footer-arrow">&gt;</span> BOQ Estimator</a></li>
                        <li><a href="{{ route('vendor.register') }}"><span class="footer-arrow">&gt;</span> Sell on Garudo</a></li>
                        <li><a href="{{ route('products.index', ['filter' => 'bulk']) }}"><span class="footer-arrow">&gt;</span> Bulk Orders</a></li>
                    </ul>
                </div>

                <!-- Column 3: Top Categories -->
                <div class="col-lg-2 col-md-4 col-6">
                    <h6 class="footer-col-title">Top Categories</h6>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('products.index', ['category' => 'acp-sheets']) }}"><span class="footer-arrow">&gt;</span> ACP Sheets</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'wooden-series']) }}"><span class="footer-arrow">&gt;</span> Wooden Series</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'breeze-blocks']) }}"><span class="footer-arrow">&gt;</span> BREEZE BLOCKS</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'terracotta-clay-jali']) }}"><span class="footer-arrow">&gt;</span> Terracotta Clay Jali</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'metallic-series']) }}"><span class="footer-arrow">&gt;</span> Metallic Series</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'roofing-materials']) }}"><span class="footer-arrow">&gt;</span> Roofing Materials</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'glossy-series']) }}"><span class="footer-arrow">&gt;</span> Glossy Series</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'natural-stone-series']) }}"><span class="footer-arrow">&gt;</span> Natural Stone Series</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'paver-blocks']) }}"><span class="footer-arrow">&gt;</span> PAVER BLOCKS</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'sand-series']) }}"><span class="footer-arrow">&gt;</span> Sand Series</a></li>
                    </ul>
                </div>

                <!-- Column 4: Customer Service -->
                <div class="col-lg-2 col-md-4 col-6">
                    <h6 class="footer-col-title">Customer Service</h6>
                    <ul class="footer-links-list">
                        <li><a href="{{ url('/account') }}"><span class="footer-arrow">&gt;</span> My Account</a></li>
                        <li><a href="{{ url('/track-order') }}"><span class="footer-arrow">&gt;</span> Track Order</a></li>
                        <li><a href="{{ url('/return-policy') }}"><span class="footer-arrow">&gt;</span> Return Policy</a></li>
                        <li><a href="{{ url('/shipping-delivery') }}"><span class="footer-arrow">&gt;</span> Shipping &amp; Delivery</a></li>
                        <li><a href="{{ url('/faq') }}"><span class="footer-arrow">&gt;</span> FAQ</a></li>
                        <li><a href="{{ url('/contact-us') }}"><span class="footer-arrow">&gt;</span> Contact Us</a></li>
                        <li><a href="{{ url('/privacy-policy') }}"><span class="footer-arrow">&gt;</span> Privacy Policy</a></li>
                        <li><a href="{{ url('/terms-conditions') }}"><span class="footer-arrow">&gt;</span> Terms &amp; Conditions</a></li>
                        <li><a href="{{ url('/refund-policy') }}"><span class="footer-arrow">&gt;</span> Refund Policy</a></li>
                        <li><a href="{{ url('/about-us') }}"><span class="footer-arrow">&gt;</span> About Us</a></li>
                    </ul>
                </div>

                <!-- Column 5: Contact Us & Download App -->
                <div class="col-lg-3 col-md-6 col-12">
                    <h6 class="footer-col-title">Contact Us</h6>
                    
                    <div class="footer-contact-row">
                        <i class="bi bi-geo-alt-fill footer-contact-icon"></i>
                        <div>
                            <div class="footer-contact-title">Address</div>
                            <div class="footer-contact-text">Garudo.In Marketplace, India</div>
                        </div>
                    </div>

                    <div class="footer-contact-row">
                        <i class="bi bi-telephone-fill footer-contact-icon"></i>
                        <div>
                            <div class="footer-contact-title">Phone</div>
                            <div class="footer-contact-text">+91 80000 00000</div>
                        </div>
                    </div>

                    <div class="footer-contact-row">
                        <i class="bi bi-envelope-fill footer-contact-icon"></i>
                        <div>
                            <div class="footer-contact-title">Email</div>
                            <div class="footer-contact-text">support@garudo.in</div>
                        </div>
                    </div>

                    <div class="footer-contact-row">
                        <i class="bi bi-clock-fill footer-contact-icon"></i>
                        <div>
                            <div class="footer-contact-title">Working Hours</div>
                            <div class="footer-contact-text">Mon-Sat: 9AM – 7PM</div>
                        </div>
                    </div>

                    <div class="mt-3 pt-2">
                        <div class="text-white-50 small fw-semibold mb-2" style="font-size: 11px;">Download App</div>
                        <div class="d-flex flex-column gap-2">
                            <a href="#" class="footer-app-btn">
                                <svg class="footer-app-icon" viewBox="0 0 24 24" width="22" height="22" fill="none">
                                    <path d="M3.609 1.814L13.792 12 3.61 22.186A2.25 2.25 0 0 1 3 20.625V3.375c0-.6.223-1.15.609-1.561z" fill="#00D3FF"/>
                                    <path d="M17.207 8.586l-3.415 3.414 3.415 3.414 3.864-2.196a1.44 1.44 0 0 0 0-2.436l-3.864-2.196z" fill="#FFCE00"/>
                                    <path d="M13.792 12L3.609 1.814A2.316 2.316 0 0 1 4.5 1.5c.57 0 1.12.19 1.58.55l11.127 6.536L13.792 12z" fill="#00F076"/>
                                    <path d="M17.207 15.414L6.08 21.95c-.46.36-1.01.55-1.58.55a2.316 2.316 0 0 1-.891-.314L13.792 12l3.415 3.414z" fill="#FF3A44"/>
                                </svg>
                                <div class="footer-app-text">
                                    <span class="footer-app-sub">GET IT ON</span>
                                    <span class="footer-app-title">Google Play</span>
                                </div>
                            </a>

                            <a href="#" class="footer-app-btn">
                                <i class="bi bi-apple footer-app-icon text-white" style="font-size: 22px;"></i>
                                <div class="footer-app-text">
                                    <span class="footer-app-sub">DOWNLOAD ON</span>
                                    <span class="footer-app-title">App Store</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright Bar -->
        <div class="footer-bottom-bar">
            <div class="container-fluid px-4 text-center">
                <p class="footer-bottom-text mb-0">
                    &copy; 2026 Garudo.in – Smart Online Marketplace for Products, Services &amp; Business. All Rights Reserved. Built with <span class="text-danger">&#10084;</span> for India's construction industry. <span class="mx-1 text-muted">|</span> <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> <span class="mx-1 text-muted">&middot;</span> <a href="{{ url('/terms-conditions') }}">Terms</a> <span class="mx-1 text-muted">&middot;</span> <a href="{{ url('/sitemap') }}">Sitemap</a> <span class="mx-1 text-muted">&middot;</span> 🇮🇳 Proudly Made in India
                </p>
            </div>
        </div>
    </footer>

    <!-- jQuery & Bootstrap 5 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function ajaxAddToCart(productId, qty) {
            if (window.GARUDO_LAUNCH_MODE_ACTIVE) {
                showPreLaunchModal();
                return;
            }
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
                if (d.is_launch_mode) {
                    showPreLaunchModal(d.message);
                    return;
                }
                if (d.success) {
                    window.location.href = "{{ route('cart.index') }}";
                }
            })
            .catch(() => {
                window.location.href = "{{ route('cart.index') }}";
            });
        }

        function ajaxToggleWishlist(productId) {
            if (typeof toggleWishlistCard === 'function') {
                toggleWishlistCard(null, productId);
            } else {
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
        }

        /* ── Seamless Wishlist Toggle with Toast Notification ── */
        window.toggleWishlistCard = function(btn, productId) {
            if (!productId) return;

            const icon = btn ? btn.querySelector('i') : null;
            if (btn) btn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

            fetch(`/wishlist/toggle/${productId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                }
            })
            .then(r => r.json())
            .then(d => {
                if (btn) btn.disabled = false;
                if (d && d.success) {
                    const isAdded = d.action === 'added';

                    // Update all matching wishlist buttons on page
                    document.querySelectorAll(`.btn-wishlist-toggle[data-id="${productId}"]`).forEach(b => {
                        const ic = b.querySelector('i');
                        if (isAdded) {
                            b.classList.add('active');
                            if (b.classList.contains('btn-outline-danger')) {
                                b.classList.remove('btn-outline-danger');
                                b.classList.add('btn-danger', 'text-white');
                            }
                            b.setAttribute('title', 'Remove from Wishlist');
                            if (ic) {
                                ic.className = 'bi bi-heart-fill';
                                ic.style.color = '#ef4444';
                                ic.style.animation = 'heartPop 0.35s ease';
                            }
                        } else {
                            b.classList.remove('active');
                            if (b.classList.contains('btn-danger')) {
                                b.classList.remove('btn-danger', 'text-white');
                                b.classList.add('btn-outline-danger');
                            }
                            b.setAttribute('title', 'Add to Wishlist');
                            if (ic) {
                                ic.className = 'bi bi-heart';
                                ic.style.color = '#64748b';
                                ic.style.animation = '';
                            }
                        }
                    });

                    // Update header wishlist counters
                    if (typeof d.count !== 'undefined') {
                        document.querySelectorAll('.buildmart-badge-red, .wishlist-counter-badge').forEach(badge => {
                            badge.textContent = d.count;
                        });
                    }

                    // Show Toast Notification
                    showWishlistToast(
                        isAdded ? 'Added to Wishlist! ❤️' : 'Removed from Wishlist',
                        isAdded ? (d.message || 'Item saved to your procurement wishlist.') : (d.message || 'Item removed from your saved items.'),
                        isAdded
                    );
                } else {
                    showWishlistToast(
                        'Notice',
                        (d && d.message) ? d.message : 'Could not update wishlist. Please try again.',
                        false
                    );
                }
            })
            .catch(err => {
                if (btn) btn.disabled = false;
                console.error("Wishlist toggle error:", err);
                showWishlistToast('Notice', 'Could not update wishlist. Please try again.', false);
            });
        };

        // Wishlist Floating Toast Engine
        let wishlistToastTimer = null;
        window.showWishlistToast = function(title, desc, isAdded) {
            let toastEl = document.getElementById('garudoWishlistToast');
            if (!toastEl) return;

            const titleEl = document.getElementById('garudoToastTitle');
            const descEl = document.getElementById('garudoToastDesc');
            const iconEl = toastEl.querySelector('.garudo-toast-icon');

            if (titleEl) titleEl.textContent = title;
            if (descEl) descEl.textContent = desc;
            if (iconEl) {
                iconEl.className = isAdded ? 'bi bi-heart-fill text-danger garudo-toast-icon' : 'bi bi-heartbreak text-secondary garudo-toast-icon';
                iconEl.style.animation = 'none';
                iconEl.offsetHeight; // trigger reflow
                iconEl.style.animation = 'heartPop 0.35s ease';
            }

            toastEl.style.display = 'block';
            setTimeout(() => toastEl.classList.add('show'), 10);

            if (wishlistToastTimer) clearTimeout(wishlistToastTimer);
            wishlistToastTimer = setTimeout(() => {
                toastEl.classList.remove('show');
                setTimeout(() => { toastEl.style.display = 'none'; }, 300);
            }, 3200);
        };

        // Global Quick View Product Modal Handler
        document.addEventListener('DOMContentLoaded', function() {
            let currentQvProductId = null;
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-quick-view-modal');
                if (!btn) return;
                e.preventDefault();

                currentQvProductId = btn.getAttribute('data-id');
                const name = btn.getAttribute('data-name') || '';
                const price = btn.getAttribute('data-price') || '';
                const unit = btn.getAttribute('data-unit') || 'Piece';
                const mrp = btn.getAttribute('data-mrp') || '';
                const image = btn.getAttribute('data-image') || '';
                const vendor = btn.getAttribute('data-vendor') || '';
                const rating = btn.getAttribute('data-rating') || '4.8';
                const url = btn.getAttribute('data-url') || '#';

                const modalImg = document.getElementById('qvModalImage');
                if (modalImg) {
                    modalImg.src = image;
                    modalImg.alt = name;
                }
                const modalTitle = document.getElementById('qvModalTitle');
                if (modalTitle) modalTitle.textContent = name;
                const modalPrice = document.getElementById('qvModalPrice');
                if (modalPrice) modalPrice.textContent = price;
                const modalUnit = document.getElementById('qvModalUnit');
                if (modalUnit) modalUnit.textContent = '/ ' + unit;
                const modalMrp = document.getElementById('qvModalMrp');
                if (modalMrp) modalMrp.textContent = mrp;
                const modalVendor = document.getElementById('qvModalVendor');
                if (modalVendor) modalVendor.textContent = vendor;
                const modalRating = document.getElementById('qvModalRating');
                if (modalRating) modalRating.textContent = rating;
                const modalDetailsBtn = document.getElementById('qvModalViewDetailsBtn');
                if (modalDetailsBtn) modalDetailsBtn.href = url;
                const modalQty = document.getElementById('qvModalQty');
                if (modalQty) modalQty.value = '1';

                const modalEl = document.getElementById('quickViewProductModal');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }
            });

            const addCartBtn = document.getElementById('qvModalAddToCartBtn');
            if (addCartBtn) {
                addCartBtn.addEventListener('click', function() {
                    if (currentQvProductId && typeof ajaxAddToCart === 'function') {
                        const qty = parseInt(document.getElementById('qvModalQty').value, 10) || 1;
                        ajaxAddToCart(currentQvProductId, qty);
                    }
                });
            }
        });
    </script>

    <!-- Global Wishlist Toast Component -->
    <div id="garudoWishlistToast" class="garudo-wishlist-toast" style="display: none;">
        <div class="garudo-toast-content">
            <i class="bi bi-heart-fill text-danger garudo-toast-icon"></i>
            <div class="garudo-toast-text">
                <div class="garudo-toast-title" id="garudoToastTitle">Saved to Wishlist!</div>
                <div class="garudo-toast-desc" id="garudoToastDesc">Item added to your procurement wishlist.</div>
            </div>
            <a href="{{ route('wishlist.index') }}" class="garudo-toast-action">View</a>
        </div>
    </div>

    <!-- Global Quick View Product Modal -->
    <div class="modal fade" id="quickViewProductModal" tabindex="-1" aria-labelledby="quickViewProductModalLabel" aria-hidden="true" style="z-index: 10555;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-0 pb-0 pe-3 pt-3">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-1">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-5 text-center">
                            <div class="bg-light p-3 rounded-3 d-flex align-items-center justify-content-center" style="height: 250px; border: 1px solid #f1f5f9;">
                                <img id="qvModalImage" src="" alt="Product" class="img-fluid" style="max-height: 220px; max-width: 95%; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex align-items-center gap-2 mb-1.5">
                                <span class="badge bg-success-subtle text-success border border-success px-2 py-0.5" style="font-size: 10.5px;">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified Industrial Seller
                                </span>
                                <span class="text-muted small" id="qvModalVendor" style="font-size: 11px;"></span>
                            </div>
                            <h5 class="fw-bold text-dark mb-2" id="qvModalTitle" style="font-size: 1.15rem; line-height: 1.3;"></h5>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="d-flex text-warning" style="font-size: 11px;">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                                <span class="fw-bold text-dark small" id="qvModalRating">4.8</span>
                                <span class="text-muted small" style="font-size: 11px;">(Garudo Buyer Feedback)</span>
                            </div>
                            <div class="d-flex align-items-baseline gap-2 mb-3 pb-3 border-bottom" style="border-color: #f1f5f9 !important;">
                                <span class="fw-black text-dark fs-3" id="qvModalPrice" style="font-weight: 800;">₹0</span>
                                <span class="text-muted" id="qvModalUnit" style="font-size: 13px;">/ Piece</span>
                                <span class="text-muted text-decoration-line-through small ms-2" id="qvModalMrp"></span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <label class="form-label mb-0 fw-semibold text-muted small">Quantity:</label>
                                <input type="number" id="qvModalQty" class="form-control form-control-sm text-center fw-bold" value="1" min="1" max="9999" style="width: 75px;">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" id="qvModalAddToCartBtn" class="btn btn-dark fw-bold px-3 py-2 rounded-3 flex-fill" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none; box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);">
                                    <i class="bi bi-cart-plus me-1"></i> Add to Cart
                                </button>
                                <a id="qvModalViewDetailsBtn" href="#" class="btn btn-outline-dark fw-bold px-3 py-2 rounded-3 flex-fill text-center">
                                    View Details <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Card Hover & Wishlist Styles -->
    <style>
        .garudo-product-card {
            border-radius: 16px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.06), 0 1px 4px -1px rgba(15, 23, 42, 0.03) !important;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                        box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), 
                        border-color 0.25s ease !important;
            background: #ffffff !important;
        }
        .garudo-product-card:hover {
            transform: translateY(-6px) !important;
            box-shadow: 0 16px 32px -4px rgba(15, 23, 42, 0.12), 0 6px 14px -2px rgba(15, 23, 42, 0.05) !important;
            border-color: #cbd5e1 !important;
        }
        .garudo-product-card:hover .garudo-card-img,
        .garudo-product-card:hover .trending-card-img {
            transform: scale(1.08) !important;
        }
        .garudo-product-card:hover .garudo-card-title,
        .garudo-product-card:hover .trending-card-title {
            color: #2563eb !important;
        }

        .btn-wishlist-toggle {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .btn-wishlist-toggle:hover {
            transform: scale(1.15) !important;
            background: #fff1f2 !important;
            border-color: #fecdd3 !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
        }
        .btn-wishlist-toggle:hover i {
            color: #ef4444 !important;
        }
        .btn-wishlist-toggle.active {
            background: #fff1f2 !important;
            border-color: #fca5a5 !important;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2) !important;
        }
        .btn-wishlist-toggle.active i {
            color: #ef4444 !important;
            animation: heartPop 0.45s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes heartPop {
            0% { transform: scale(1); }
            40% { transform: scale(1.45) rotate(-6deg); }
            70% { transform: scale(0.9) rotate(3deg); }
            100% { transform: scale(1); }
        }

        .garudo-btn-quickview {
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .garudo-btn-quickview:hover {
            background: #0f172a !important;
            color: #ffffff !important;
            border-color: #0f172a !important;
            transform: translateY(-1.5px) !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.16) !important;
        }
        .garudo-btn-quickview:active {
            transform: scale(0.96) !important;
        }

        .garudo-btn-addcart {
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .garudo-btn-addcart:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%) !important;
            transform: translateY(-1.5px) !important;
            box-shadow: 0 6px 16px rgba(249, 115, 22, 0.4) !important;
        }
        .garudo-btn-addcart:active {
            transform: scale(0.96) !important;
        }

        .custom-products-scroll {
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 12px 14px 22px 14px !important;
            gap: 16px !important;
        }
        .custom-products-scroll::-webkit-scrollbar {
            display: none;
        }

        .carousel-arrow-nav {
            width: 42px !important;
            height: 42px !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px) !important;
            border: 1.5px solid #e2e8f0 !important;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.12) !important;
            z-index: 25 !important;
            cursor: pointer !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .carousel-arrow-prev {
            left: -14px !important;
        }
        .carousel-arrow-next {
            right: -14px !important;
        }
        .carousel-arrow-nav:hover {
            background: #0f172a !important;
            border-color: #0f172a !important;
            transform: translateY(-50%) scale(1.12) !important;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.25) !important;
        }
        .carousel-arrow-nav:hover i {
            color: #ffffff !important;
        }
        .carousel-arrow-nav:active {
            transform: translateY(-50%) scale(0.96) !important;
        }

        .garudo-wishlist-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            pointer-events: auto;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transform: translateY(20px);
            opacity: 0;
        }
        .garudo-wishlist-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .garudo-toast-content {
            background: #0f172a;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.35);
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            min-width: 290px;
        }
        .garudo-toast-icon {
            font-size: 20px;
        }
        .garudo-toast-text {
            flex-grow: 1;
            line-height: 1.25;
        }
        .garudo-toast-title {
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
        }
        .garudo-toast-desc {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }
        .garudo-toast-action {
            background: #ff5400;
            color: #ffffff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.15s ease;
        }
        .garudo-toast-action:hover {
            background: #e04800;
            color: #ffffff;
        }
    </style>

    <!-- Official Pre-Launch Notice Modal -->
    <div class="modal fade" id="garudoPreLaunchModal" tabindex="-1" aria-labelledby="garudoPreLaunchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 490px;">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0 position-relative text-white" style="background: linear-gradient(135deg, #070d18 0%, #17153a 60%, #2a1152 100%);">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="w-100 text-center pt-4 pb-2">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 74px; height: 74px; background: rgba(245, 158, 11, 0.15); border: 2px solid rgba(245, 158, 11, 0.45); box-shadow: 0 0 25px rgba(245, 158, 11, 0.35);">
                            <i class="bi bi-rocket-takeoff-fill text-warning fs-1 animate-rocket"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill mb-2 text-uppercase" style="letter-spacing: 0.8px; font-size: 11px;">
                                Pre-Launch Preview Mode
                            </span>
                        </div>
                        <h4 class="modal-title fw-bold text-white px-2 mb-1" id="garudoPreLaunchModalLabel">
                            Official Launching on <span class="text-warning">{{ $launchSettings['launch_date'] ?? '20th October' }}</span>!
                        </h4>
                        <div class="text-white-50 small mb-2">Garudo B2B Marketplace</div>
                    </div>
                </div>
                <div class="modal-body p-4 text-center bg-white">
                    <p class="text-dark fs-6 mb-3 fw-medium" id="preLaunchNoticeBody">
                        {{ $launchSettings['launch_notice_message'] ?? 'Direct order purchasing & checkout will officially go live on our launch day.' }}
                    </p>
                    
                    <div class="p-3 rounded-3 mb-4 text-start" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                        <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-bold" style="font-size: 13px;">
                            <i class="bi bi-stars text-warning"></i> What you can do during Pre-Launch:
                        </div>
                        <ul class="list-unstyled mb-0 small text-muted ps-3" style="line-height: 1.8;">
                            <li><i class="bi bi-check2-circle text-success me-1.5"></i> Browse 10,000+ verified B2B catalog products</li>
                            <li><i class="bi bi-check2-circle text-success me-1.5"></i> Save materials to your <strong>Wishlist</strong> for 1-click checkout on launch</li>
                            <li><i class="bi bi-check2-circle text-success me-1.5"></i> Connect with verified manufacturers & distributors</li>
                            <li><i class="bi bi-check2-circle text-success me-1.5"></i> Request custom wholesale quotes & sample test pieces</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning fw-bold py-2.5 rounded-3 text-dark shadow-sm" data-bs-dismiss="modal">
                            <i class="bi bi-compass me-1"></i> Continue Exploring Catalog
                        </button>
                        <a href="{{ route('customer.wishlist') }}" class="btn btn-outline-dark fw-semibold py-2 rounded-3">
                            <i class="bi bi-heart-fill me-1 text-danger"></i> View My Wishlist
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.GARUDO_LAUNCH_MODE_ACTIVE = {{ (($launchSettings['launch_mode_active'] ?? '1') == '1') ? 'true' : 'false' }};
        window.GARUDO_LAUNCH_DATE = "{{ $launchSettings['launch_date'] ?? '20th October' }}";

        function showPreLaunchModal(customMsg) {
            const modalEl = document.getElementById('garudoPreLaunchModal');
            if (modalEl) {
                if (customMsg) {
                    const bodyEl = document.getElementById('preLaunchNoticeBody');
                    if (bodyEl) bodyEl.textContent = customMsg;
                }
                const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                bsModal.show();
            } else {
                alert("Official Launching on " + window.GARUDO_LAUNCH_DATE + "! Product purchasing will start on launch day.");
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.GARUDO_LAUNCH_MODE_ACTIVE) {
                // Intercept any form posting to /cart/add
                document.addEventListener('submit', function(e) {
                    const form = e.target;
                    if (form && form.action && form.action.includes('/cart/add')) {
                        e.preventDefault();
                        e.stopPropagation();
                        showPreLaunchModal();
                        return false;
                    }
                }, true);

                // Intercept buy now / add to cart / checkout clicks
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('button, a');
                    if (!btn) return;
                    if (btn.closest('#garudoPreLaunchModal')) return;

                    const text = (btn.textContent || '').trim().toLowerCase();
                    const isCartOrBuy = btn.matches('.btn-add-to-cart, [data-action="add-to-cart"], .btn-buy-now, [data-action="buy-now"]')
                        || text.includes('add to cart') 
                        || text.includes('buy now')
                        || text.includes('proceed to secure checkout');

                    if (isCartOrBuy) {
                        e.preventDefault();
                        e.stopPropagation();
                        showPreLaunchModal();
                        return false;
                    }
                }, true);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

