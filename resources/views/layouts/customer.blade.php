<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard') - GARUDO B2B</title>
    
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
            --garudo-card-bg: #FFFFFF;
            --garudo-border: #E2E8F0;
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
            padding: 0.75rem 1.5rem;
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
            width: 38px;
            height: 38px;
        }

        .brand-text-block {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--garudo-primary);
            line-height: 1.1;
            margin: 0;
        }

        .brand-tagline {
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--garudo-text-muted);
            text-transform: uppercase;
        }

        /* Search Bar */
        .nav-search-container {
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1.5px solid var(--garudo-border);
            border-radius: var(--garudo-radius-md);
            overflow: hidden;
            width: 100%;
            max-width: 540px;
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
            padding: 0.55rem 0.85rem;
            border-right: 1px solid var(--garudo-border);
            outline: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .nav-search-input {
            border: none;
            padding: 0.55rem 0.9rem;
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
            padding: 0.55rem 1.1rem;
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
            padding: 0.25rem 0.5rem;
            transition: color 0.15s;
        }

        .nav-action-link:hover {
            color: var(--garudo-primary);
        }

        .nav-action-link i {
            font-size: 1.25rem;
            margin-bottom: 2px;
            color: var(--garudo-text-main);
        }

        .nav-action-link:hover i {
            color: var(--garudo-primary);
        }

        .badge-count {
            position: absolute;
            top: -2px;
            right: 8px;
            background-color: var(--garudo-primary);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            border-radius: 50%;
            width: 17px;
            height: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }

        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            padding: 0.25rem 0.5rem;
            border-radius: var(--garudo-radius-md);
            transition: background 0.15s;
        }

        .user-profile-btn:hover {
            background: #F1F5F9;
        }

        .user-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: var(--garudo-primary);
            border: 1.5px solid var(--garudo-border);
        }

        .user-info-text {
            text-align: left;
            line-height: 1.2;
        }

        .user-greeting {
            font-size: 0.813rem;
            font-weight: 700;
            color: var(--garudo-text-main);
            margin: 0;
        }

        .user-role {
            font-size: 0.72rem;
            color: var(--garudo-text-muted);
            margin: 0;
        }

        /* ---------------- SIDEBAR ---------------- */
        .garudo-sidebar {
            width: 255px;
            flex-shrink: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.65rem 1rem;
            color: #475569;
            font-weight: 500;
            font-size: 0.85rem;
            text-decoration: none;
            border-radius: var(--garudo-radius-md);
            transition: all 0.15s ease-in-out;
        }

        .sidebar-item-link i {
            font-size: 1.15rem;
            color: #64748B;
            transition: color 0.15s;
        }

        .sidebar-item-link:hover {
            background-color: #F1F5F9;
            color: var(--garudo-primary);
        }

        .sidebar-item-link:hover i {
            color: var(--garudo-primary);
        }

        /* Active Sidebar Link */
        .sidebar-item-link.active {
            background-color: var(--garudo-primary) !important;
            color: #FFFFFF !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(11, 77, 60, 0.22);
        }

        .sidebar-item-link.active i {
            color: #FFFFFF !important;
        }

        /* Need Help Widget */
        .help-widget-card {
            background: #FFFFFF;
            border: 1px solid var(--garudo-border);
            border-radius: var(--garudo-radius-lg);
            padding: 1.25rem 1rem;
            box-shadow: var(--garudo-shadow-card);
            margin-top: 1.5rem;
        }

        .help-widget-header {
            display: flex;
            align-items: center;
            gap: 12px;
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
            font-size: 1.1rem;
            color: var(--garudo-primary);
            flex-shrink: 0;
        }

        .help-title {
            font-size: 0.875rem;
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
            padding: 1.4rem;
            margin-bottom: 1.5rem;
        }

        .card-title-lg {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--garudo-text-main);
            margin: 0;
        }

        /* Status Badges */
        .badge-delivered {
            background-color: #E8F8F0;
            color: #0E7045;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            display: inline-block;
        }

        .badge-transit {
            background-color: #FEF3E2;
            color: #B45309;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            display: inline-block;
        }

        .badge-cancelled {
            background-color: #FEECEB;
            color: #D92D20;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            display: inline-block;
        }

        .badge-default-tag {
            background-color: #E8F7F0;
            color: var(--garudo-primary);
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.2rem 0.55rem;
            border-radius: 9999px;
            margin-left: 6px;
        }

        /* Button Outlines */
        .btn-outline-action {
            background: #FFFFFF;
            border: 1.5px solid var(--garudo-border);
            color: #334155;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.4rem 0.85rem;
            border-radius: var(--garudo-radius-sm);
            text-decoration: none;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .btn-outline-action:hover {
            border-color: var(--garudo-primary);
            color: var(--garudo-primary);
            background: var(--garudo-primary-light);
        }

        .btn-primary-action {
            background: var(--garudo-primary);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: var(--garudo-radius-sm);
            border: none;
            text-decoration: none;
            transition: background 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary-action:hover {
            background: var(--garudo-primary-hover);
            color: #ffffff;
        }

        .view-all-link {
            color: var(--garudo-primary);
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: opacity 0.15s;
        }

        .view-all-link:hover {
            opacity: 0.8;
            color: var(--garudo-primary);
        }

        /* Mobile Sidebar Drawer */
        @media (max-width: 991.98px) {
            .garudo-sidebar-desktop {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ---------------- NAVBAR ---------------- -->
    <header class="garudo-navbar">
        <div class="container-fluid px-lg-4">
            <div class="d-flex align-items-center justify-content-between gap-3">
                
                <!-- Brand & Mobile Toggler -->
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light d-lg-none p-1 px-2 border" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <a href="{{ route('home') }}" class="brand-logo-wrap">
                        <div class="brand-logo-icon">
                            <!-- Wings Graphic SVG matching Garudo Logo -->
                            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 14C14 14 20 18 22 24C16 23 10 20 6 14Z" fill="#0B4D3C"/>
                                <path d="M8 20C16 20 22 24 24 30C18 29 12 26 8 20Z" fill="#0B4D3C"/>
                                <path d="M12 26C18 26 24 29 26 35C20 34 15 31 12 26Z" fill="#0B4D3C"/>
                                <path d="M22 24C26 16 34 12 42 12C36 18 30 22 24 30C23 27 22 25 22 24Z" fill="#0B4D3C"/>
                            </svg>
                        </div>
                        <div class="brand-text-block">
                            <span class="brand-name">GARUDO</span>
                            <span class="brand-tagline">BUILD BETTER. TOGETHER.</span>
                        </div>
                    </a>
                </div>

                <!-- Center Search Group -->
                <div class="d-none d-md-flex flex-grow-1 justify-content-center px-lg-4">
                    <form action="{{ route('products.index') }}" method="GET" class="nav-search-container">
                        <select name="category" class="category-select-btn">
                            <option value="">All Categories</option>
                            <option value="cement">Cement & Sand</option>
                            <option value="steel">TMT Steel Bars</option>
                            <option value="bricks">Bricks & Blocks</option>
                            <option value="paints">Paints & Finishes</option>
                            <option value="tiles">Tiles & Ceramics</option>
                        </select>
                        <input type="text" name="q" class="nav-search-input" placeholder="Search for building materials..." autocomplete="off">
                        <button type="submit" class="nav-search-btn" title="Search">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Right Action Navigation Items -->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('vendor.register') }}" class="nav-action-link d-none d-sm-flex">
                        <i class="bi bi-shop"></i>
                        <span>Become a Seller</span>
                    </a>

                    <a href="{{ route('customer.wishlist') }}" class="nav-action-link">
                        <i class="bi bi-heart"></i>
                        <span>Wishlist</span>
                    </a>

                    <a href="{{ route('customer.orders') }}" class="nav-action-link position-relative">
                        <i class="bi bi-cart3"></i>
                        <span class="badge-count">3</span>
                        <span>Cart</span>
                    </a>

                    <a href="{{ route('customer.notifications') }}" class="nav-action-link position-relative">
                        <i class="bi bi-bell"></i>
                        <span class="badge-count">6</span>
                        <span></span>
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="user-profile-btn dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar-circle">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="user-info-text d-none d-lg-block">
                                <p class="user-greeting">Hi, {{ explode(' ', auth('customer')->user()->name ?? 'Rahul')[0] }}</p>
                                <p class="user-role">Customer</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; font-size: 0.85rem; min-width: 200px;">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold text-dark">{{ auth('customer')->user()->name ?? 'Rahul Sharma' }}</div>
                                <div class="text-muted small">{{ auth('customer')->user()->email ?? 'rahul.sharma@example.com' }}</div>
                            </li>
                            <li><a class="dropdown-item py-2" href="{{ route('customer.dashboard') }}"><i class="bi bi-grid-fill me-2 text-success"></i> Dashboard</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('vendor.dashboard') }}"><i class="bi bi-shop me-2 text-success"></i> Switch to Vendor Panel</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('customer.orders') }}"><i class="bi bi-box-seam me-2"></i> My Orders</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('customer.quotes') }}"><i class="bi bi-file-earmark-text me-2"></i> My Quotes</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('customer.settings') }}"><i class="bi bi-gear me-2"></i> Account Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('customer.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- ---------------- MAIN LAYOUT ---------------- -->
    <div class="container-fluid px-lg-4 py-4">
        <div class="d-flex gap-4">
            
            <!-- Desktop Sidebar -->
            <aside class="garudo-sidebar garudo-sidebar-desktop">
                @include('customer.partials.sidebar')
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
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel" style="width: 280px;">
        <div class="offcanvas-header border-bottom">
            <div class="d-flex align-items-center gap-2">
                <span class="brand-name" style="font-size: 1.2rem;">GARUDO</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-3">
            @include('customer.partials.sidebar')
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
