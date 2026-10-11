<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin Command Center') - GARUDO B2B</title>
    <link rel="icon" type="image/png" href="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --garudo-primary: #0B4D3C;
            --garudo-primary-dark: #062E24;
            --garudo-primary-light: #E8F7F0;
            --garudo-accent: #10B981;
            --garudo-gold: #F59E0B;
            --garudo-danger: #EF4444;
            --garudo-info: #0284C7;
            --garudo-bg: #F1F5F9;
            --garudo-card-bg: #FFFFFF;
            --garudo-border: #E2E8F0;
            --garudo-text-main: #0F172A;
            --garudo-text-muted: #64748B;
            --sidebar-width: 270px;
            --header-height: 70px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--garudo-bg);
            color: var(--garudo-text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            font-size: 0.875rem;
        }

        /* ---------------- SIDEBAR ---------------- */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #071A14 0%, #0B2920 100%);
            color: #E2E8F0;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(16, 185, 129, 0.15);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .sidebar-brand-box {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
            text-decoration: none;
            color: inherit;
        }

        .sidebar-brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #0B4D3C 0%, #10B981 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 1.35rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .sidebar-brand-text h4 {
            font-size: 1.15rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.5px;
            color: #FFFFFF;
        }

        .sidebar-brand-text span {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--garudo-gold);
            text-transform: uppercase;
            display: block;
        }

        .sidebar-nav {
            padding: 1rem 0.75rem;
            flex: 1;
        }

        .nav-section-title {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748B;
            padding: 0.75rem 0.85rem 0.35rem;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            color: #94A3B8;
            font-weight: 500;
            text-decoration: none;
            margin-bottom: 2px;
            transition: all 0.2s ease;
        }

        .admin-nav-link .link-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-nav-link i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
            color: #64748B;
            transition: color 0.2s;
        }

        .admin-nav-link:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.06);
        }

        .admin-nav-link:hover i {
            color: var(--garudo-accent);
        }

        .admin-nav-link.active {
            color: #FFFFFF;
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.2) 0%, rgba(11, 77, 60, 0.4) 100%);
            border-left: 3px solid var(--garudo-accent);
            font-weight: 600;
        }

        .admin-nav-link.active i {
            color: var(--garudo-accent);
        }

        .badge-nav-counter {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 9999px;
        }

        .sidebar-user-footer {
            padding: 1rem 1.25rem;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #0B4D3C;
            border: 1.5px solid var(--garudo-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-weight: 700;
        }

        /* ---------------- MAIN CONTENT WRAPPER ---------------- */
        .admin-main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: margin-left 0.3s ease;
        }

        /* ---------------- TOP NAVBAR ---------------- */
        .admin-header {
            height: var(--header-height);
            background: #FFFFFF;
            border-bottom: 1px solid var(--garudo-border);
            padding: 0 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .header-search-box {
            position: relative;
            width: 360px;
        }

        .header-search-box input {
            background: var(--garudo-bg);
            border: 1px solid var(--garudo-border);
            border-radius: 10px;
            padding: 0.5rem 1rem 0.5rem 2.4rem;
            font-size: 0.85rem;
            width: 100%;
            outline: none;
            transition: all 0.2s;
        }

        .header-search-box input:focus {
            background: #FFFFFF;
            border-color: var(--garudo-primary);
            box-shadow: 0 0 0 3px rgba(11, 77, 60, 0.1);
        }

        .header-search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .system-status-pill {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #10B981;
            border-radius: 50%;
            animation: pulseDot 1.8s infinite;
        }

        @keyframes pulseDot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* ---------------- PAGE BODY ---------------- */
        .admin-content {
            padding: 2rem;
            flex: 1;
        }

        .page-header-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 0.25rem;
        }

        .page-header-subtitle {
            color: var(--garudo-text-muted);
            font-size: 0.85rem;
        }

        /* ---------------- UI CARDS ---------------- */
        .garudo-card {
            background: #FFFFFF;
            border: 1px solid var(--garudo-border);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
            transition: all 0.2s ease;
        }

        .garudo-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        }

        .kpi-card {
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .kpi-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .kpi-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.2;
            margin: 0.4rem 0 0.15rem;
        }

        .kpi-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--garudo-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ---------------- TABLES ---------------- */
        .admin-table {
            width: 100%;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .admin-table th {
            background: #F8FAFC;
            color: #475569;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--garudo-border);
        }

        .admin-table td {
            padding: 0.95rem 1rem;
            border-bottom: 1px solid var(--garudo-border);
            font-size: 0.85rem;
        }

        .admin-table tbody tr:hover {
            background-color: #F8FAFC;
        }

        /* Status Pills */
        .pill-status {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-transform: capitalize;
        }

        .pill-active, .pill-approved, .pill-verified, .pill-paid, .pill-delivered {
            background: #DCFCE7;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }

        .pill-pending, .pill-processing {
            background: #FEF3C7;
            color: #B45309;
            border: 1px solid #FDE68A;
        }

        .pill-rejected, .pill-suspended, .pill-cancelled, .pill-failed {
            background: #FEE2E2;
            color: #B91C1C;
            border: 1px solid #FECACA;
        }

        .pill-shipped, .pill-dispatched {
            background: #E0F2FE;
            color: #0369A1;
            border: 1px solid #BAE6FD;
        }

        /* Buttons */
        .btn-garudo-primary {
            background: var(--garudo-primary);
            color: #FFFFFF;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }
        .btn-garudo-primary:hover {
            background: var(--garudo-primary-dark);
            color: #FFFFFF;
        }

        /* Mobile Sidebar Toggle */
        @media (max-width: 992px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ---------------- SIDEBAR NAVIGATION ---------------- -->
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-box flex-column align-items-start py-3 text-decoration-none" title="Garudo Super Admin">
            <img src="{{ asset('images/garudo-eagle-logo.png') . '?v=eagle_final' }}" alt="GARUDO.in" style="height: 38px; width: auto; max-width: 100%; object-fit: contain;">
            <div class="sidebar-brand-text mt-1.5">
                <span>{{ auth('admin')->user()?->is_super_admin ? 'Super Administrator' : (auth('admin')->user()?->role_display_name ?? 'Subadmin / Staff') }}</span>
            </div>
        </a>

        @php
            $currentAdmin = auth('admin')->user();
        @endphp

        <div class="sidebar-nav">
            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('dashboard.view'))
            <div class="nav-section-title">Core Command</div>
            
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Command Center</span>
                </div>
            </a>
            @endif

            <div class="nav-section-title">Marketplace Entities</div>

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('vendors.view'))
            <a href="{{ route('admin.vendors.index') }}" class="admin-nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-shop-window"></i>
                    <span>Vendors / Sellers</span>
                </div>
                @php
                    $pendingVendorsCount = \App\Models\Vendor::where('approval_status', 'pending')->count();
                @endphp
                @if($pendingVendorsCount > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingVendorsCount }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('customers.view'))
            <a href="{{ route('admin.customers.index') }}" class="admin-nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-people-fill"></i>
                    <span>Buyers / Customers</span>
                </div>
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('products.view'))
            <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>Products & Catalog</span>
                </div>
                @php
                    $pendingProductsCount = \App\Models\Product::where('approval_status', 'pending')->count();
                @endphp
                @if($pendingProductsCount > 0)
                    <span class="badge-nav-counter bg-danger text-white">{{ $pendingProductsCount }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('categories.view'))
            <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-diagram-3-fill"></i>
                    <span>Categories & Tree</span>
                </div>
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('brands.view'))
            <a href="{{ route('admin.brands.index') }}" class="admin-nav-link {{ request()->routeIs('admin.brands.index', 'admin.brands.create', 'admin.brands.edit') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-patch-check-fill text-success"></i>
                    <span>Brand Master</span>
                </div>
            </a>

            <a href="{{ route('admin.brand-titles.index') }}" class="admin-nav-link {{ request()->routeIs('admin.brand-titles.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-tags-fill text-primary"></i>
                    <span>Brand Titles & Tiers</span>
                </div>
            </a>

            <a href="{{ route('admin.brands.registry.index') }}" class="admin-nav-link {{ request()->routeIs('admin.brands.registry.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-award-fill text-warning"></i>
                    <span>Brand Registry & OEM</span>
                </div>
                @php
                    $pendingBrandApps = \Illuminate\Support\Facades\Schema::hasTable('brand_registrations')
                        ? \App\Models\BrandRegistration::where('status', 'pending')->count()
                        : 0;
                @endphp
                @if($pendingBrandApps > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingBrandApps }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('dealers.view'))
            <a href="{{ route('admin.dealer-authorizations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.dealer-authorizations.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-patch-check-fill text-success"></i>
                    <span>Dealer Authorizations</span>
                </div>
                @php
                    $pendingDealerAuths = \Illuminate\Support\Facades\Schema::hasTable('brand_dealer_authorizations')
                        ? \App\Models\BrandDealerAuthorization::where('garudo_approval_status', 'pending')->count()
                        : 0;
                @endphp
                @if($pendingDealerAuths > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingDealerAuths }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('attributes.view'))
            <a href="{{ route('admin.attributes.index') }}" class="admin-nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-sliders"></i>
                    <span>Attributes & Specs</span>
                </div>
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('catalog_requests.view'))
            <a href="{{ route('admin.catalog-requests.index') }}" class="admin-nav-link {{ request()->routeIs('admin.catalog-requests.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-inbox-fill"></i>
                    <span>Catalog & Brand Requests</span>
                </div>
                @php
                    $pendingCatalogRequests = \Illuminate\Support\Facades\Schema::hasTable('catalog_requests')
                        ? \App\Models\CatalogRequest::where('status', 'pending')->count()
                        : 0;
                @endphp
                @if($pendingCatalogRequests > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingCatalogRequests }}</span>
                @endif
            </a>
            @endif

            <div class="nav-section-title">Operations & Supply</div>

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('orders.view'))
            <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>B2B Orders</span>
                </div>
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('quotations.view'))
            <a href="{{ route('admin.quotations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.quotations.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-file-earmark-ruled-fill text-info"></i>
                    <span>Quotations & RFQs</span>
                </div>
                @php
                    $pendingAdminQuotes = \Illuminate\Support\Facades\Schema::hasTable('quotations')
                        ? \App\Models\Quotation::whereIn('status', ['pending', 'new'])->count()
                        : 0;
                @endphp
                @if($pendingAdminQuotes > 0)
                    <span class="badge-nav-counter bg-primary text-white">{{ $pendingAdminQuotes }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('samples.view'))
            <a href="{{ route('admin.samples.index') }}" class="admin-nav-link {{ request()->routeIs('admin.samples.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-box2-heart-fill text-success"></i>
                    <span>Product Samples & Trials</span>
                </div>
                @php
                    $pendingAdminSamples = \Illuminate\Support\Facades\Schema::hasTable('sample_requests')
                        ? \App\Models\SampleRequest::where('status', 'pending')->count()
                        : 0;
                @endphp
                @if($pendingAdminSamples > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingAdminSamples }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('logistics.view'))
            <a href="{{ route('admin.logistics.index') }}" class="admin-nav-link {{ request()->routeIs('admin.logistics.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-truck"></i>
                    <span>Logistics & Fleet</span>
                </div>
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('marketing.view'))
            <a href="{{ route('admin.marketing.campaigns') }}" class="admin-nav-link {{ request()->routeIs('admin.marketing.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-rocket-takeoff-fill text-warning"></i>
                    <span>Boost Campaigns & Payments</span>
                </div>
                @php
                    $pendingBoostsCount = \Illuminate\Support\Facades\Schema::hasTable('promotional_campaigns')
                        ? \App\Models\PromotionalCampaign::where('status', 'pending_payment_approval')->count()
                        : 0;
                @endphp
                @if($pendingBoostsCount > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingBoostsCount }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('subscriptions.view'))
            <div class="nav-section-title">Subscriptions & Tiers</div>

            <a href="{{ route('admin.subscriptions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.subscriptions.index') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-patch-check-fill text-success"></i>
                    <span>Subscribers & Approvals</span>
                </div>
                @php
                    $pendingSubsCount = \Illuminate\Support\Facades\Schema::hasTable('subscriptions')
                        ? \App\Models\Subscription::where('status', 'pending_approval')->count()
                        : 0;
                @endphp
                @if($pendingSubsCount > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingSubsCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.subscriptions.plans') }}" class="admin-nav-link {{ request()->routeIs('admin.subscriptions.plans*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-sliders text-info"></i>
                    <span>Manage Pricing Plans</span>
                </div>
            </a>
            @endif

            <div class="nav-section-title">Platform Controls</div>

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('password_requests.view'))
            <a href="{{ route('admin.password-requests.index') }}" class="admin-nav-link {{ request()->routeIs('admin.password-requests.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-key-fill"></i>
                    <span>Password Requests</span>
                </div>
                @php
                    $pendingPwCount = \Illuminate\Support\Facades\Schema::hasTable('password_reset_requests')
                        ? \App\Models\PasswordResetRequest::where('status', 'pending')->count()
                        : 0;
                @endphp
                @if($pendingPwCount > 0)
                    <span class="badge-nav-counter bg-warning text-dark">{{ $pendingPwCount }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('notifications.view'))
            <a href="{{ route('admin.notifications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notifications</span>
                </div>
                @php
                    $unreadNotifCount = \App\Services\AdminNotificationService::getUnreadCount();
                @endphp
                @if($unreadNotifCount > 0)
                    <span class="badge-nav-counter bg-danger text-white">{{ $unreadNotifCount }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('homepage.view'))
            <a href="{{ route('admin.homepage.index') }}" class="admin-nav-link {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-layout-text-window-reverse text-warning"></i>
                    <span>Homepage Control</span>
                </div>
                <span class="badge-nav-counter bg-warning text-dark">Live</span>
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('subadmins.view'))
            <a href="{{ route('admin.subadmins.index') }}" class="admin-nav-link {{ request()->routeIs('admin.subadmins.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-shield-lock-fill text-success"></i>
                    <span>Subadmins & Staff</span>
                </div>
                @php
                    $staffBadgeCount = \App\Models\Admin::where('is_super_admin', false)->count();
                @endphp
                @if($staffBadgeCount > 0)
                    <span class="badge-nav-counter bg-success text-white">{{ $staffBadgeCount }}</span>
                @endif
            </a>
            @endif

            @if(!$currentAdmin || $currentAdmin->is_super_admin || $currentAdmin->hasPermission('settings.view'))
            <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <div class="link-left">
                    <i class="bi bi-sliders2"></i>
                    <span>Platform Settings</span>
                </div>
            </a>
            @endif

            <a href="{{ url('/') }}" target="_blank" class="admin-nav-link">
                <div class="link-left">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <span>View Storefront</span>
                </div>
            </a>
        </div>

        <div class="sidebar-user-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="admin-user-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold text-white small text-truncate" style="max-width: 155px;">{{ auth('admin')->user()->name ?? 'Super Admin' }}</div>
                    <div class="text-white-50 text-truncate" style="font-size: 0.7rem; max-width: 155px;">
                        {{ auth('admin')->user()?->is_super_admin ? 'Root Administrator' : (auth('admin')->user()?->role_display_name ?? 'Subadmin / Staff') }}
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Secure Logout">
                    <i class="bi bi-power fs-5"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- ---------------- MAIN APPLICATION AREA ---------------- -->
    <div class="admin-main-wrapper">
        
        <!-- Top Header -->
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggleBtn">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="header-search-box d-none d-md-block">
                    <i class="bi bi-search"></i>
                    <input type="text" id="adminGlobalSearch" placeholder="Search orders, vendors, products...">
                </div>
            </div>

            <div class="header-actions">
                <div class="system-status-pill d-none d-sm-inline-flex">
                    <span class="status-dot"></span>
                    <span>Garudo Live v2.4</span>
                </div>

                <!-- Notifications Bell Dropdown -->
                @php
                    $headerNotifs = \App\Services\AdminNotificationService::getLatest(5);
                    $headerUnreadCount = \App\Services\AdminNotificationService::getUnreadCount();
                @endphp
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-circle position-relative p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                        <i class="bi bi-bell fs-6 text-dark"></i>
                        @if($headerUnreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                {{ $headerUnreadCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0" style="width: 340px;">
                        <div class="p-3 bg-dark text-white rounded-top d-flex justify-content-between align-items-center">
                            <div class="fw-bold small"><i class="bi bi-bell-fill me-1 text-warning"></i> Activity Alerts</div>
                            <span class="badge bg-danger rounded-pill">{{ $headerUnreadCount }} New</span>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 280px; overflow-y: auto;">
                            @forelse($headerNotifs as $hNotif)
                                <a href="{{ $hNotif->link ?? '#' }}" class="list-group-item list-group-item-action p-2 d-flex align-items-start gap-2">
                                    <div class="fs-5 flex-shrink-0 mt-1">
                                        <i class="bi {{ $hNotif->icon ?? 'bi-bell text-primary' }}"></i>
                                    </div>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="fw-bold text-dark text-truncate small">{{ $hNotif->title }}</div>
                                        <div class="text-muted text-truncate" style="font-size: 0.75rem;">{{ $hNotif->message }}</div>
                                        <div class="text-muted" style="font-size: 0.68rem;">{{ $hNotif->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-3 text-center text-muted small">No notifications yet.</div>
                            @endforelse
                        </div>
                        <div class="p-2 text-center border-top bg-light rounded-bottom">
                            <a href="{{ route('admin.notifications.index') }}" class="text-primary fw-semibold text-decoration-none small">
                                View All Notifications <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Button -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Quick Links
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item" href="{{ route('admin.vendors.index', ['status' => 'pending']) }}"><i class="bi bi-hourglass-split me-2 text-warning"></i> Pending Vendors</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.products.index', ['status' => 'pending']) }}"><i class="bi bi-clock-history me-2 text-danger"></i> Pending Products</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}"><i class="bi bi-plus-circle me-2 text-success"></i> Add Category</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear me-2 text-primary"></i> Marketplace Config</a></li>
                    </ul>
                </div>

                <!-- Admin Profile Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle gap-2" data-bs-toggle="dropdown">
                        <div class="admin-user-avatar" style="width: 34px; height: 34px; font-size: 0.8rem;">
                            {{ strtoupper(substr(auth('admin')->user()->name ?? 'SA', 0, 2)) }}
                        </div>
                        <span class="fw-semibold d-none d-md-inline text-dark small">{{ auth('admin')->user()->name ?? 'Super Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold small">{{ auth('admin')->user()->name ?? 'Super Admin' }}</div>
                            <div class="text-muted small" style="font-size: 0.75rem;">{{ auth('admin')->user()->email ?? 'admin@garudo.com' }}</div>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="bi bi-sliders me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-6"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2 fs-6"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Page Dynamic Body -->
        <main class="admin-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small mt-auto">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <div>© {{ date('Y') }} <strong>GARUDO B2B Marketplace</strong> • Enterprise Administrator Platform</div>
                <div><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Environment: {{ config('app.env') }}</span></div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const adminSidebar = document.getElementById('adminSidebar');
        if (sidebarToggleBtn && adminSidebar) {
            sidebarToggleBtn.addEventListener('click', () => {
                adminSidebar.classList.toggle('show');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
