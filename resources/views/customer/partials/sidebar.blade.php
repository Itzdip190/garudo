<div class="d-flex flex-column justify-content-between h-100">
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('customer.dashboard') }}" class="sidebar-item-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2{{ request()->routeIs('customer.dashboard') ? '-fill' : '' }}"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.orders') }}" class="sidebar-item-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}">
                <i class="bi bi-bag{{ request()->routeIs('customer.orders*') ? '-fill' : '' }}"></i>
                <span>My Orders</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.quotes') }}" class="sidebar-item-link {{ request()->routeIs('customer.quotes*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text{{ request()->routeIs('customer.quotes*') ? '-fill' : '' }}"></i>
                <span>My Quotes</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.wishlist') }}" class="sidebar-item-link {{ request()->routeIs('customer.wishlist*') ? 'active' : '' }}">
                <i class="bi bi-heart{{ request()->routeIs('customer.wishlist*') ? '-fill' : '' }}"></i>
                <span>Wishlist</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.addresses') }}" class="sidebar-item-link {{ request()->routeIs('customer.addresses*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt{{ request()->routeIs('customer.addresses*') ? '-fill' : '' }}"></i>
                <span>Addresses</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.payment-methods') }}" class="sidebar-item-link {{ request()->routeIs('customer.payment-methods*') ? 'active' : '' }}">
                <i class="bi bi-credit-card{{ request()->routeIs('customer.payment-methods*') ? '-fill' : '' }}"></i>
                <span>Payment Methods</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.reviews') }}" class="sidebar-item-link {{ request()->routeIs('customer.reviews*') ? 'active' : '' }}">
                <i class="bi bi-star{{ request()->routeIs('customer.reviews*') ? '-fill' : '' }}"></i>
                <span>My Reviews</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.returns') }}" class="sidebar-item-link {{ request()->routeIs('customer.returns*') ? 'active' : '' }}">
                <i class="bi bi-arrow-counterclockwise"></i>
                <span>Returns & Refunds</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.inquiries') }}" class="sidebar-item-link {{ request()->routeIs('customer.inquiries*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text{{ request()->routeIs('customer.inquiries*') ? '-fill' : '' }}"></i>
                <span>My Inquiries</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.notifications') }}" class="sidebar-item-link {{ request()->routeIs('customer.notifications*') ? 'active' : '' }}">
                <i class="bi bi-bell{{ request()->routeIs('customer.notifications*') ? '-fill' : '' }}"></i>
                <span>Notifications</span>
            </a>
        </li>
        <li>
            <a href="{{ route('feedback.index') }}" class="sidebar-item-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}">
                <i class="bi bi-chat-heart{{ request()->routeIs('feedback.*') ? '-fill' : '' }}"></i>
                <span>Rate Garudo Software</span>
            </a>
        </li>
        <li>
            <a href="{{ route('customer.settings') }}" class="sidebar-item-link {{ request()->routeIs('customer.settings*') ? 'active' : '' }}">
                <i class="bi bi-gear{{ request()->routeIs('customer.settings*') ? '-fill' : '' }}"></i>
                <span>Account Settings</span>
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
                <p class="help-desc">Our support team is here to help you</p>
            </div>
        </div>
        <a href="mailto:support@garudo.com" class="btn-contact-support">
            Contact Support
        </a>
    </div>
</div>
