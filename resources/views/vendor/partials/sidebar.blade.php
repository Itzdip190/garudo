<div class="d-flex flex-column justify-content-between h-100">
    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('vendor.dashboard') }}" class="sidebar-item-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-grid-1x2{{ request()->routeIs('vendor.dashboard') ? '-fill' : '' }} menu-icon"></i>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>

        <!-- KYC Verification & Accreditation -->
        <li>
            <a href="{{ route('vendor.kyc.index') }}" class="sidebar-item-link {{ request()->routeIs('vendor.kyc.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-shield-check menu-icon text-success"></i>
                    <span>KYC & Verification</span>
                </div>
                @if(auth('vendor')->check() && auth('vendor')->user()->isVerifiedPartner())
                    <span class="badge-sidebar badge-sidebar-green"><i class="bi bi-check-lg"></i> Verified</span>
                @else
                    <span class="badge-sidebar badge-sidebar-red">Required</span>
                @endif
            </a>
        </li>

        <!-- Channel Partner Digital Certificate -->
        <li>
            <a href="{{ route('vendor.certificate.show') }}" class="sidebar-item-link {{ request()->routeIs('vendor.certificate.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-award{{ request()->routeIs('vendor.certificate.*') ? '-fill' : '' }} menu-icon text-warning"></i>
                    <span>Partner Certificate</span>
                </div>
                <span class="badge-sidebar badge-sidebar-green">Official</span>
            </a>
        </li>

        <!-- Plans & Subscription -->
        <li>
            <a href="{{ route('vendor.subscription.index') }}" class="sidebar-item-link {{ request()->routeIs('vendor.subscription.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-patch-check{{ request()->routeIs('vendor.subscription.*') ? '-fill' : '' }} menu-icon text-success"></i>
                    <span>Plans & Subscription</span>
                </div>
                @php
                    $sidebarVendorUser = auth('vendor')->user();
                    $activePlanName = $sidebarVendorUser && $sidebarVendorUser->activeSubscription ? ($sidebarVendorUser->activeSubscription->plan->name ?? $sidebarVendorUser->activeSubscription->plan_name) : 'Upgrade';
                @endphp
                <span class="badge-sidebar badge-sidebar-green">{{ $activePlanName }}</span>
            </a>
        </li>

        <!-- Products (Collapsible) -->
        <li>
            <a class="sidebar-item-link {{ request()->routeIs('vendor.products.*') || request()->routeIs('vendor.brands') || request()->routeIs('vendor.categories') || request()->routeIs('vendor.attributes') ? 'active' : '' }}" data-bs-toggle="collapse" href="#collapseProducts" role="button" aria-expanded="{{ request()->routeIs('vendor.products.*') || request()->routeIs('vendor.brands') || request()->routeIs('vendor.categories') || request()->routeIs('vendor.attributes') ? 'true' : 'false' }}" aria-controls="collapseProducts">
                <div class="menu-main-content">
                    <i class="bi bi-box-seam menu-icon"></i>
                    <span>Products</span>
                </div>
                <i class="bi bi-chevron-down text-muted" style="font-size: 0.75rem;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('vendor.products.*') || request()->routeIs('vendor.brands') || request()->routeIs('vendor.categories') || request()->routeIs('vendor.attributes') ? 'show' : '' }}" id="collapseProducts">
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('vendor.products.index') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.products.index') ? 'active' : '' }}">All Products</a></li>
                    <li><a href="{{ route('vendor.products.create') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.products.create') ? 'active' : '' }}">Add New Product</a></li>
                    <li><a href="{{ route('vendor.products.quick-price') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.products.quick-price*') ? 'active' : '' }}"><i class="bi bi-tag me-1 text-warning"></i>Quick Price Change</a></li>
                    <li><a href="{{ route('vendor.products.bulk') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.products.bulk') ? 'active' : '' }}"><i class="bi bi-cloud-arrow-up me-1"></i>Bulk & Backup</a></li>
                    <li><a href="{{ route('vendor.products.logistics-policy') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.products.logistics-policy') ? 'active' : '' }}"><i class="bi bi-truck me-1 text-primary"></i>F.O.R. Logistics Policy</a></li>
                    <li><a href="{{ route('vendor.brands') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.brands') ? 'active' : '' }}">Brands</a></li>
                    <li><a href="{{ route('vendor.categories') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.categories') ? 'active' : '' }}">Categories</a></li>
                    <li><a href="{{ route('vendor.attributes') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.attributes') ? 'active' : '' }}">Attributes</a></li>
                </ul>
            </div>
        </li>

        <!-- Orders -->
        <li>
            <a href="{{ route('vendor.orders.index') }}" class="sidebar-item-link {{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-bag-check menu-icon"></i>
                    <span>Orders</span>
                </div>
                @if(isset($stats['pending_orders']) && $stats['pending_orders'] > 0)
                    <span class="badge-sidebar badge-sidebar-red">{{ $stats['pending_orders'] }}</span>
                @endif
            </a>
        </li>

        <!-- Returns & Refunds -->
        <li>
            <a href="{{ route('vendor.returns') }}" class="sidebar-item-link {{ request()->routeIs('vendor.returns') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-arrow-counterclockwise menu-icon"></i>
                    <span>Returns & Refunds</span>
                </div>
                @if(isset($stats['returns_count']) && $stats['returns_count'] > 0)
                    <span class="badge-sidebar badge-sidebar-red">{{ $stats['returns_count'] }}</span>
                @endif
            </a>
        </li>

        <!-- Quotations -->
        <li>
            <a href="{{ route('vendor.quotations.index') }}" class="sidebar-item-link {{ request()->routeIs('vendor.quotations.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-file-earmark-text menu-icon"></i>
                    <span>Quotations</span>
                </div>
                @if(isset($stats['quotations_count']) && $stats['quotations_count'] > 0)
                    <span class="badge-sidebar badge-sidebar-red">{{ $stats['quotations_count'] }}</span>
                @endif
            </a>
        </li>

        <!-- Bulk Orders -->
        <li>
            <a href="{{ route('vendor.bulk-orders') }}" class="sidebar-item-link {{ request()->routeIs('vendor.bulk-orders') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-boxes menu-icon"></i>
                    <span>Bulk Orders</span>
                </div>
            </a>
        </li>

        <!-- Earnings & Payouts -->
        <li>
            <a href="{{ route('vendor.earnings') }}" class="sidebar-item-link {{ request()->routeIs('vendor.earnings') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-cash-stack menu-icon"></i>
                    <span>Earnings & Payouts</span>
                </div>
            </a>
        </li>

        <!-- Payments -->
        <li>
            <a href="{{ route('vendor.payments') }}" class="sidebar-item-link {{ request()->routeIs('vendor.payments') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-credit-card menu-icon"></i>
                    <span>Payments</span>
                </div>
            </a>
        </li>

        <!-- Reviews & Ratings -->
        <li>
            <a href="{{ route('vendor.reviews') }}" class="sidebar-item-link {{ request()->routeIs('vendor.reviews') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-star menu-icon"></i>
                    <span>Reviews & Ratings</span>
                </div>
            </a>
        </li>

        <!-- Customers -->
        <li>
            <a href="{{ route('vendor.customers') }}" class="sidebar-item-link {{ request()->routeIs('vendor.customers') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-people menu-icon"></i>
                    <span>Customers</span>
                </div>
            </a>
        </li>

        <!-- Messages & Buyer RFQs -->
        @php
            $sidebarVendor = auth('vendor')->user() ?? ($vendor ?? null);
            $sideUnreadMsg = $sidebarVendor ? \App\Services\VendorNotificationService::getUnreadMessagesCount($sidebarVendor) : 0;
            $sideUnreadNotif = $sidebarVendor ? \App\Services\VendorNotificationService::getUnreadCount($sidebarVendor) : 0;
        @endphp
        <li>
            <a href="{{ route('vendor.messages.index') }}" class="sidebar-item-link {{ request()->routeIs('vendor.messages.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-chat-dots menu-icon"></i>
                    <span>Messages</span>
                </div>
                @if($sideUnreadMsg > 0)
                    <span class="badge-sidebar badge-sidebar-red">
                        {{ $sideUnreadMsg }}
                    </span>
                @endif
            </a>
        </li>

        <!-- Notifications -->
        <li>
            <a href="{{ route('vendor.notifications.index') }}" class="sidebar-item-link {{ request()->routeIs('vendor.notifications.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-bell menu-icon"></i>
                    <span>Notifications</span>
                </div>
                @if($sideUnreadNotif > 0)
                    <span class="badge-sidebar badge-sidebar-green">
                        {{ $sideUnreadNotif }}
                    </span>
                @endif
            </a>
        </li>


        <!-- Analytics & Reports -->
        <li>
            <a href="{{ route('vendor.analytics') }}" class="sidebar-item-link {{ request()->routeIs('vendor.analytics') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-graph-up-arrow menu-icon"></i>
                    <span>Analytics & Reports</span>
                </div>
            </a>
        </li>

        <!-- Marketing Tools -->
        <li>
            <a href="{{ route('vendor.marketing') }}" class="sidebar-item-link {{ request()->routeIs('vendor.marketing') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-megaphone menu-icon"></i>
                    <span>Marketing Tools</span>
                </div>
                <span class="badge-sidebar badge-sidebar-green">New</span>
            </a>
        </li>

        <!-- Store Management -->
        <li>
            <a class="sidebar-item-link {{ request()->routeIs('vendor.store.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#collapseStore" role="button" aria-expanded="{{ request()->routeIs('vendor.store.*') ? 'true' : 'false' }}" aria-controls="collapseStore">
                <div class="menu-main-content">
                    <i class="bi bi-shop-window menu-icon"></i>
                    <span>Store Management</span>
                </div>
                <i class="bi bi-chevron-down text-muted" style="font-size: 0.75rem;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('vendor.store.*') ? 'show' : '' }}" id="collapseStore">
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('vendor.store.design') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.store.design*') ? 'active' : '' }}"><i class="bi bi-palette text-warning me-1"></i>Design Storefront</a></li>
                    <li><a href="{{ route('vendor.store.profile') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.store.profile') ? 'active' : '' }}">Store Profile</a></li>
                    <li><a href="{{ route('vendor.store.business') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.store.business') ? 'active' : '' }}">Business Details</a></li>
                    <li><a href="{{ route('vendor.store.media') }}" class="sidebar-submenu-link {{ request()->routeIs('vendor.store.media') ? 'active' : '' }}">Store Banner & Logo</a></li>
                    @php
                        $sideVnd = auth('vendor')->user();
                    @endphp
                    @if($sideVnd)
                        <li><a href="{{ route('store.show', $sideVnd->company_slug ?? $sideVnd->id) }}" target="_blank" class="sidebar-submenu-link text-success"><i class="bi bi-box-arrow-up-right me-1"></i>View Public Store</a></li>
                    @endif
                </ul>
            </div>
        </li>

        <!-- Shipping Settings -->
        <li>
            <a href="{{ route('vendor.shipping') }}" class="sidebar-item-link {{ request()->routeIs('vendor.shipping') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-truck menu-icon"></i>
                    <span>Shipping Settings</span>
                </div>
            </a>
        </li>

        <!-- Warehouses & Depots -->
        <li>
            <a href="{{ route('vendor.warehouses.index') }}" class="sidebar-item-link {{ request()->routeIs('vendor.warehouses.*') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-building-gear menu-icon"></i>
                    <span>Warehouses & Depots</span>
                </div>
                <span class="badge-sidebar badge-sidebar-green">Multi-Hub</span>
            </a>
        </li>

        <!-- Profile & Business -->
        <li>
            <a href="{{ route('vendor.profile') }}" class="sidebar-item-link {{ request()->routeIs('vendor.profile') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-person-badge menu-icon"></i>
                    <span>Profile & Business</span>
                </div>
            </a>
        </li>

        <!-- Support Tickets -->
        <li>
            <a href="{{ route('vendor.support') }}" class="sidebar-item-link {{ request()->routeIs('vendor.support') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-chat-left-dots menu-icon"></i>
                    <span>Support Tickets</span>
                </div>
                @if(isset($stats['pending_enquiries']) && $stats['pending_enquiries'] > 0)
                    <span class="badge-sidebar badge-sidebar-red">{{ $stats['pending_enquiries'] }}</span>
                @endif
            </a>
        </li>

        <!-- Settings -->
        <li>
            <a href="{{ route('vendor.settings') }}" class="sidebar-item-link {{ request()->routeIs('vendor.settings') ? 'active' : '' }}">
                <div class="menu-main-content">
                    <i class="bi bi-gear menu-icon"></i>
                    <span>Settings</span>
                </div>
            </a>
        </li>
    </ul>

    <!-- Bottom Help & Support Widget -->
    <div class="help-widget-card">
        <div class="help-widget-header">
            <div class="help-icon-circle">
                <i class="bi bi-headset"></i>
            </div>
            <div>
                <h6 class="help-title">Need Help?</h6>
                <p class="help-desc">Our support team is here to help you.</p>
            </div>
        </div>
        <a href="{{ route('vendor.support') }}" class="btn-contact-support">
            Contact Support
        </a>
    </div>
</div>
