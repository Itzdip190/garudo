@php
    $currentVendor = auth('vendor')->user() ?? ($vendor ?? null);
    $unreadNotifCount = $currentVendor ? \App\Services\VendorNotificationService::getUnreadCount($currentVendor) : 0;
    $unreadMsgCount = $currentVendor ? \App\Services\VendorNotificationService::getUnreadMessagesCount($currentVendor) : 0;
    $quickNotifications = $currentVendor ? \App\Services\VendorNotificationService::getLatest($currentVendor, 6) : collect();
    $quickMessages = $currentVendor ? \App\Services\VendorNotificationService::getMessageThreads($currentVendor, 5) : collect();
@endphp

<header class="garudo-navbar">
    <div class="container-fluid px-lg-4">
        <div class="d-flex align-items-center justify-content-between gap-2 gap-md-3">
            
            <!-- Brand & Mobile Toggler -->
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <button class="btn btn-sm btn-light d-lg-none p-1 px-2 border" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileVendorSidebar" aria-controls="mobileVendorSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <div class="d-flex flex-column">
                    <a href="{{ route('vendor.dashboard') }}" class="brand-logo-wrap">
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
                    <span class="vendor-badge-pill align-self-start mt-1">Vendor Panel</span>
                </div>
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
                @php
                    $navVendorUser = auth('vendor')->user() ?? ($vendor ?? null);
                    $navStoreUrl = $navVendorUser ? route('store.show', $navVendorUser->company_slug ?? $navVendorUser->id) : route('home');
                @endphp
                <a href="{{ $navStoreUrl }}" target="_blank" class="nav-action-link d-none d-sm-flex" title="View Your Public Storefront">
                    <i class="bi bi-shop"></i>
                    <span>View Store</span>
                </a>

                <!-- 1. Messages Dropdown (Sleek Modern UI, Strictly No Horizontal Scroll) -->
                <div class="dropdown">
                    <button class="nav-action-link position-relative dropdown-toggle border-0 bg-transparent text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Buyer Messages & RFQs">
                        <i class="bi bi-chat-dots"></i>
                        @if($unreadMsgCount > 0)
                            <span class="badge-count-red" id="navMsgBadge">{{ $unreadMsgCount }}</span>
                        @endif
                        <span class="d-none d-md-inline">Messages</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0" style="border-radius: 16px; width: 375px; max-width: 95vw; z-index: 1060; overflow-x: hidden !important; box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.16) !important;">
                        <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-chat-left-dots-fill text-success" style="font-size: 0.85rem;"></i>
                                </div>
                                <span class="fw-bold text-dark" style="font-size: 0.88rem;">Messages & Inquiries</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($unreadMsgCount > 0)
                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold px-2 py-1" style="font-size: 0.68rem; border-radius: 9999px;">{{ $unreadMsgCount }} New</span>
                                    <form action="{{ route('vendor.messages.mark_all_read') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-success text-decoration-none fw-semibold" style="font-size: 0.72rem;">
                                            Mark Read
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size: 0.68rem; border-radius: 9999px;">All Caught Up</span>
                                @endif
                            </div>
                        </div>

                        <div class="list-group list-group-flush" style="max-height: 310px; overflow-y: auto; overflow-x: hidden !important; scrollbar-width: thin;">
                            @forelse($quickMessages as $qm)
                                <a href="{{ route('vendor.messages.index', ['thread_id' => $qm->id]) }}" class="list-group-item list-group-item-action p-2 px-3 border-bottom d-flex align-items-start gap-2 text-decoration-none {{ $qm->unread ? 'bg-light bg-opacity-75' : 'bg-white' }}" style="overflow-x: hidden !important; max-width: 100%;">
                                    <div class="rounded-circle {{ $qm->sender_type === 'admin' ? 'bg-success bg-opacity-10 text-success' : 'bg-primary bg-opacity-10 text-primary' }} border d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px; font-size: 1rem;">
                                        <i class="bi {{ $qm->sender_avatar ?? 'bi-person-circle' }}"></i>
                                    </div>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="d-flex align-items-center justify-content-between gap-1 mb-0">
                                            <span class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;">
                                                {{ $qm->sender_name }}
                                            </span>
                                            <span class="text-muted flex-shrink-0" style="font-size: 0.67rem;">
                                                {{ is_string($qm->created_at) ? $qm->created_at : $qm->created_at->diffForHumans(null, true) }}
                                            </span>
                                        </div>
                                        <div class="text-muted text-truncate" style="font-size: 0.74rem; line-height: 1.35;">
                                            {{ $qm->latest_message }}
                                        </div>
                                    </div>
                                    @if($qm->unread)
                                        <span class="p-1 bg-success rounded-circle align-self-center flex-shrink-0" style="width: 7px; height: 7px;"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-chat-left-check fs-2 text-success opacity-50 d-block mb-1"></i>
                                    <div class="small">No active message threads.</div>
                                </div>
                            @endforelse
                        </div>

                        <div class="p-2 border-top bg-light text-center">
                            <a href="{{ route('vendor.messages.index') }}" class="text-success fw-bold small text-decoration-none d-inline-flex align-items-center gap-1">
                                <span>Open All Messages & Reply</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Notifications Dropdown (Sleek Modern UI, Strictly No Horizontal Scroll) -->
                <div class="dropdown">
                    <button class="nav-action-link position-relative dropdown-toggle border-0 bg-transparent text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Product & Order Notifications">
                        <i class="bi bi-bell"></i>
                        @if($unreadNotifCount > 0)
                            <span class="badge-count-red" id="navNotifBadge">{{ $unreadNotifCount }}</span>
                        @endif
                        <span class="d-none d-md-inline">Notifications</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0" style="border-radius: 16px; width: 380px; max-width: 95vw; z-index: 1060; overflow-x: hidden !important; box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.16) !important;">
                        <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-bell-fill text-success" style="font-size: 0.85rem;"></i>
                                </div>
                                <span class="fw-bold text-dark" style="font-size: 0.88rem;">Notifications & Activity</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($unreadNotifCount > 0)
                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold px-2 py-1" style="font-size: 0.68rem; border-radius: 9999px;">{{ $unreadNotifCount }} New</span>
                                    <form action="{{ route('vendor.notifications.mark_all_read') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-success text-decoration-none fw-semibold" style="font-size: 0.72rem;">
                                            Mark All Read
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size: 0.68rem; border-radius: 9999px;">0 Unread</span>
                                @endif
                            </div>
                        </div>

                        <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto; overflow-x: hidden !important; scrollbar-width: thin;">
                            @forelse($quickNotifications as $qn)
                                <div class="list-group-item p-2 px-3 border-bottom d-flex align-items-start gap-2 {{ $qn->read_at ? 'bg-white' : 'bg-light bg-opacity-75' }}" style="overflow-x: hidden !important; max-width: 100%;">
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 36px; height: 36px; font-size: 1rem; color: #0B4D3C;">
                                        <i class="bi {{ $qn->icon ?? 'bi-bell' }}"></i>
                                    </div>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <div class="d-flex align-items-center justify-content-between gap-1 mb-0">
                                            <span class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;">
                                                {{ $qn->title }}
                                            </span>
                                            <span class="text-muted flex-shrink-0" style="font-size: 0.67rem;">
                                                {{ $qn->created_at ? $qn->created_at->diffForHumans(null, true) : 'Recently' }}
                                            </span>
                                        </div>
                                        <div class="text-muted text-truncate" style="font-size: 0.73rem; line-height: 1.35;">
                                            {{ $qn->message }}
                                        </div>
                                        @if($qn->link && $qn->link !== '#')
                                            <div class="mt-1">
                                                <a href="{{ $qn->link }}" class="text-success fw-semibold text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                                    <span>View Details</span>
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="bi bi-bell-slash fs-2 text-muted opacity-50 d-block mb-1"></i>
                                    <div class="small">No recent notifications.</div>
                                </div>
                            @endforelse
                        </div>

                        <div class="p-2 border-top bg-light text-center">
                            <a href="{{ route('vendor.notifications.index') }}" class="text-success fw-bold small text-decoration-none d-inline-flex align-items-center gap-1">
                                <span>View All Notifications</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Vendor Profile Dropdown -->
                <div class="dropdown">
                    <a href="#" class="vendor-profile-btn dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="vendor-avatar-circle">
                            @if(isset($vendor) && $vendor->company_logo)
                                <img src="{{ asset('storage/' . $vendor->company_logo) }}" alt="Logo">
                            @else
                                <i class="bi bi-building"></i>
                            @endif
                        </div>
                        <div class="vendor-info-text d-none d-lg-block">
                            <p class="vendor-name-title">
                                {{ $vendor->company_name ?? 'Shree Balaji Traders' }}
                            </p>
                            <p class="vendor-id-subtitle">Vendor ID: GRD-V{{ isset($vendor) ? str_pad($vendor->id, 4, '0', STR_PAD_LEFT) : '1256' }}</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px; font-size: 0.85rem; min-width: 220px;">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold text-dark">{{ $vendor->company_name ?? 'Shree Balaji Traders' }}</div>
                            <div class="text-muted small">{{ $vendor->email ?? 'support@garudo.in' }}</div>
                            <div class="mt-1">
                                <span class="badge bg-success bg-opacity-10 text-success fw-semibold" style="font-size: 0.7rem;">
                                    {{ ucfirst($vendor->approval_status ?? 'Approved') }} Seller
                                </span>
                            </div>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('vendor.dashboard') }}"><i class="bi bi-grid-fill me-2 text-success"></i> Vendor Dashboard</a></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('customer.dashboard') }}">
                                <i class="bi bi-arrow-left-right me-2 text-primary"></i> Switch to Customer Panel
                            </a>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('vendor.store.profile') }}"><i class="bi bi-shop me-2"></i> Store Settings</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('vendor.profile') }}"><i class="bi bi-person-gear me-2"></i> Profile & Password Reset</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('vendor.logout') }}" method="POST">
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
