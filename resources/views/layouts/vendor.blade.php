<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vendor Dashboard') - GARUDO B2B</title>
    <link rel="icon" type="image/png" href="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js CDN for Analytics Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --garudo-primary: #0B4D3C;
            --garudo-primary-hover: #083D30;
            --garudo-primary-light: #E8F7F0;
            --garudo-primary-text: #0B7B58;
            --garudo-accent: #10B981;
            --garudo-bg: #F8FAFC;
            --garudo-card-bg: #FFFFFF;
            --garudo-border: #E2E8F0;
            --garudo-border-light: #F1F5F9;
            --garudo-text-main: #0F172A;
            --garudo-text-muted: #64748B;
            --garudo-text-sub: #94A3B8;
            --garudo-radius-sm: 8px;
            --garudo-radius-md: 12px;
            --garudo-radius-lg: 16px;
            --garudo-radius-xl: 20px;
            --garudo-shadow-sm: 0 1px 3px rgba(0,0,0,0.03), 0 1px 2px rgba(0,0,0,0.02);
            --garudo-shadow-card: 0 2px 10px rgba(15, 23, 42, 0.04), 0 1px 3px rgba(15, 23, 42, 0.02);
            --garudo-shadow-hover: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--garudo-bg);
            color: var(--garudo-text-main);
            -webkit-font-smoothing: antialiased;
            font-size: 0.875rem;
            min-height: 100vh;
        }

        /* ---------------- TOP NAVBAR ---------------- */
        .garudo-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--garudo-border);
            padding: 0.65rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .brand-logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--garudo-primary);
        }

        .brand-logo-icon svg {
            width: 36px;
            height: 36px;
        }

        .brand-text-block {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--garudo-primary);
            line-height: 1.1;
            margin: 0;
        }

        .brand-tagline {
            font-size: 0.58rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: var(--garudo-text-muted);
            text-transform: uppercase;
        }

        .vendor-badge-pill {
            background-color: var(--garudo-primary-light);
            color: var(--garudo-primary-text);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            border: 1px solid #C6E9D7;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        /* Navbar Search Bar */
        .nav-search-container {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1.5px solid var(--garudo-border);
            border-radius: var(--garudo-radius-md);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .nav-search-container:focus-within {
            border-color: var(--garudo-primary);
            box-shadow: 0 0 0 3px rgba(11, 77, 60, 0.12);
        }

        .category-select-btn {
            border: none;
            background: transparent;
            font-size: 0.813rem;
            font-weight: 500;
            color: var(--garudo-text-main);
            padding: 0.5rem 0.85rem;
            border-right: 1px solid var(--garudo-border);
            outline: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .nav-search-input {
            border: none;
            padding: 0.5rem 0.9rem;
            font-size: 0.84rem;
            color: var(--garudo-text-main);
            width: 100%;
            outline: none;
            background: transparent;
        }

        .nav-search-input::placeholder {
            color: #94A3B8;
        }

        .nav-search-btn {
            background-color: var(--garudo-primary);
            color: #FFFFFF;
            border: none;
            padding: 0.5rem 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.15s;
            cursor: pointer;
            margin: 3px;
            border-radius: var(--garudo-radius-sm);
        }

        .nav-search-btn:hover {
            background-color: var(--garudo-primary-hover);
        }

        /* Nav Right Actions */
        .nav-action-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--garudo-text-muted);
            font-size: 0.72rem;
            font-weight: 500;
            position: relative;
            padding: 0.25rem 0.6rem;
            transition: color 0.15s;
            background: transparent;
            border: none;
        }

        .nav-action-link:hover {
            color: var(--garudo-primary);
        }

        .nav-action-link i {
            font-size: 1.25rem;
            margin-bottom: 2px;
            color: #334155;
            transition: color 0.15s;
        }

        .nav-action-link:hover i {
            color: var(--garudo-primary);
        }

        .badge-count-red {
            position: absolute;
            top: -2px;
            right: 10px;
            background-color: #EF4444;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            border-radius: 50%;
            min-width: 17px;
            height: 17px;
            padding: 0 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }

        /* Prevent all horizontal scrollbar leakage */
        .list-group, .dropdown-menu, .card-custom {
            overflow-x: hidden !important;
        }
        .list-group-item {
            overflow-x: hidden !important;
            max-width: 100% !important;
        }

        /* Modern sleek scrollbars for lists & chat (Strictly No Horizontal Scrollbars) */
        ::-webkit-scrollbar {
            width: 5px;
            height: 0px !important;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        ::-webkit-scrollbar-horizontal {
            display: none !important;
            height: 0px !important;
            width: 0px !important;
            opacity: 0 !important;
        }

        .vendor-profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            padding: 0.25rem 0.5rem;
            border-radius: var(--garudo-radius-md);
            transition: background 0.15s;
        }

        .vendor-profile-btn:hover {
            background: #F1F5F9;
        }

        .vendor-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #FEF3C7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #D97706;
            border: 1.5px solid #FDE68A;
            overflow: hidden;
        }

        .vendor-avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .vendor-info-text {
            text-align: left;
            line-height: 1.2;
        }

        .vendor-name-title {
            font-size: 0.813rem;
            font-weight: 700;
            color: var(--garudo-text-main);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .vendor-id-subtitle {
            font-size: 0.7rem;
            color: var(--garudo-text-muted);
            margin: 0;
        }

        /* ---------------- SIDEBAR ---------------- */
        .garudo-sidebar {
            width: 250px;
            flex-shrink: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .sidebar-item-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 0.95rem;
            color: #475569;
            font-weight: 500;
            font-size: 0.84rem;
            text-decoration: none;
            border-radius: var(--garudo-radius-md);
            transition: all 0.15s ease-in-out;
            cursor: pointer;
        }

        .sidebar-item-link .menu-main-content {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .sidebar-item-link i.menu-icon {
            font-size: 1.12rem;
            color: #64748B;
            transition: color 0.15s;
            width: 18px;
            display: inline-flex;
            justify-content: center;
        }

        .sidebar-item-link:hover {
            background-color: #F1F5F9;
            color: var(--garudo-primary);
        }

        .sidebar-item-link:hover i.menu-icon {
            color: var(--garudo-primary);
        }

        /* Active Sidebar Link */
        .sidebar-item-link.active {
            background-color: var(--garudo-primary) !important;
            color: #FFFFFF !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(11, 77, 60, 0.22);
        }

        .sidebar-item-link.active i.menu-icon {
            color: #FFFFFF !important;
        }

        .sidebar-item-link.active .badge-sidebar {
            background-color: rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
        }

        .badge-sidebar {
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 9999px;
            padding: 0.15rem 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .badge-sidebar-red {
            background-color: #EF4444;
            color: #ffffff;
        }

        .badge-sidebar-green {
            background-color: var(--garudo-primary-light);
            color: var(--garudo-primary-text);
            border: 1px solid #A7F3D0;
        }

        /* Submenu */
        .sidebar-submenu {
            list-style: none;
            padding-left: 2rem;
            margin: 4px 0 6px 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar-submenu-link {
            display: block;
            padding: 0.4rem 0.75rem;
            color: #64748B;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            border-radius: var(--garudo-radius-sm);
            transition: all 0.15s;
        }

        .sidebar-submenu-link:hover {
            color: var(--garudo-primary);
            background-color: #F1F5F9;
        }

        .sidebar-submenu-link.active {
            color: var(--garudo-primary);
            font-weight: 700;
            background-color: var(--garudo-primary-light);
        }

        /* Need Help Widget */
        .help-widget-card {
            background: #FFFFFF;
            border: 1px solid var(--garudo-border);
            border-radius: var(--garudo-radius-lg);
            padding: 1.15rem 1rem;
            box-shadow: var(--garudo-shadow-card);
            margin-top: 1.5rem;
        }

        .help-widget-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.85rem;
        }

        .help-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: var(--garudo-primary);
            flex-shrink: 0;
        }

        .help-title {
            font-size: 0.85rem;
            font-weight: 700;
            margin: 0;
            color: var(--garudo-text-main);
        }

        .help-desc {
            font-size: 0.72rem;
            color: var(--garudo-text-muted);
            margin: 0;
            line-height: 1.3;
        }

        .btn-contact-support {
            background-color: var(--garudo-primary);
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.813rem;
            padding: 0.55rem 1rem;
            border-radius: var(--garudo-radius-sm);
            border: none;
            width: 100%;
            transition: background-color 0.15s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-contact-support:hover {
            background-color: var(--garudo-primary-hover);
            color: #FFFFFF;
        }

        /* ---------------- COMMON CARDS & UI ---------------- */
        .card-custom {
            background: var(--garudo-card-bg);
            border: 1px solid var(--garudo-border);
            border-radius: var(--garudo-radius-lg);
            box-shadow: var(--garudo-shadow-card);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .card-title-header {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--garudo-text-main);
            margin: 0;
        }

        /* Mobile Responsive Navigation & Dropdowns */
        @media (max-width: 991.98px) {
            .garudo-sidebar-desktop {
                display: none;
            }
            .garudo-navbar {
                padding: 0.5rem 0.75rem;
            }
            .brand-name {
                font-size: 1.15rem;
            }
            .nav-action-link {
                padding: 0.2rem 0.4rem;
                font-size: 0.68rem;
            }
            .badge-count-red {
                top: -3px;
                right: 4px;
                min-width: 15px;
                height: 15px;
                font-size: 0.6rem;
            }
        }
        @media (max-width: 576px) {
            .dropdown-menu {
                max-width: 92vw !important;
                right: -10px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ---------------- NAVBAR ---------------- -->
    @include('vendor.partials.navbar')

    <!-- ---------------- MAIN LAYOUT ---------------- -->
    <div class="container-fluid px-lg-4 py-3">
        <div class="d-flex gap-4">
            
            <!-- Desktop Sidebar -->
            <aside class="garudo-sidebar garudo-sidebar-desktop">
                @include('vendor.partials.sidebar')
            </aside>

            <!-- Main Dynamic Content Area -->
            <main class="flex-grow-1 min-vw-0">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="border-radius: 12px; border: none; background: #E8F7F0; color: #0B7B58;">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="border-radius: 12px; border: none; background: #FEECEB; color: #D92D20;">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <!-- ---------------- MOBILE OFFCANVAS SIDEBAR ---------------- -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileVendorSidebar" aria-labelledby="mobileVendorSidebarLabel" style="width: 280px;">
        <div class="offcanvas-header border-bottom">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('home') }}" class="d-inline-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" style="height: 32px; max-height: 34px; width: auto; object-fit: contain;">
                </a>
                <span class="vendor-badge-pill ms-2">Vendor Panel</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-3">
            @include('vendor.partials.sidebar')
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
