@extends('layouts.admin')

@section('title', 'Homepage Merchandising & Storefront Control')

@section('content')
<div class="container-fluid py-3">

    <!-- PAGE HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h3 class="fw-bold mb-0 text-dark">Homepage Control Center</h3>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">
                    <i class="bi bi-broadcast me-1"></i> Live Storefront
                </span>
            </div>
            <p class="text-muted small mb-0 mt-1">
                Curate authentic catalog products, section placements, promotional banners, and category filter pills on the homepage.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5 shadow-sm">
                <i class="bi bi-box-arrow-up-right"></i> View Storefront
            </a>

            <form action="{{ route('admin.homepage.sync-defaults') }}" method="POST" class="m-0" onsubmit="return confirm('Sync and restore default verified products & banners to database?');">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1.5 shadow-sm" title="Re-seed & verify realistic catalog products">
                    <i class="bi bi-arrow-repeat"></i> Sync Default Catalog
                </button>
            </form>

            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#createBannerModal" style="background: var(--garudo-primary); border-color: var(--garudo-primary);">
                <i class="bi bi-plus-circle"></i> Add Promo Banner
            </button>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- VISUAL STOREFRONT LAYOUT ARCHITECTURE ROADMAP (FOR ADMIN UNDERSTANDING) -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 pb-3 border-bottom border-secondary border-opacity-25 mb-3">
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary px-2.5 py-1 text-uppercase fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Storefront Section Map</span>
                        <h5 class="fw-bold mb-0 text-white">Homepage Layout & 5-Step Display Sequence</h5>
                    </div>
                    <p class="text-white-50 small mb-0 mt-1">
                        Use this visual guide to see the exact display order on the live landing page and which admin column controls each section.
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold">
                        <i class="bi bi-arrow-down-circle me-1"></i> Exact Storefront Display Order (Top to Bottom)
                    </span>
                </div>
            </div>

            <!-- 5 Step Flow Diagram: Sec 1 -> Sec 2 -> Image 2 Banners -> Sec 3 -> Sec 4 -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-2.5">
                <!-- STEP 1: SECTION 1 (TRENDING - ABOVE BANNERS) -->
                <div class="col">
                    <div class="p-3 rounded-3 h-100 position-relative d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.05); border: 1.5px solid rgba(245, 158, 11, 0.45);">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge" style="background: #f59e0b; color: #0f172a; font-weight: 800; font-size: 10px;">1 • TOP ROW</span>
                                <span class="badge bg-dark text-warning border border-warning border-opacity-25" style="font-size: 9.5px;">[SECTION 1]</span>
                            </div>
                            <h6 class="fw-bold text-white mb-1" style="font-size: 13px;">
                                <i class="bi bi-fire text-warning me-1"></i> Trending Products
                            </h6>
                            <p class="text-white-50 mb-2" style="font-size: 11px; line-height: 1.35;">
                                Placed <strong>ABOVE</strong> banners. White cards, ₹/Unit, Rating & Verified Seller badge.
                            </p>
                        </div>
                        <div class="pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <span class="badge bg-warning-subtle text-warning" style="font-size: 9.5px;">Column: Sec 1</span>
                            <span class="text-warning small" style="font-size: 10.5px;">Above Banners</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: SECTION 2 (BEST SELLING - ABOVE BANNERS) -->
                <div class="col">
                    <div class="p-3 rounded-3 h-100 position-relative d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.05); border: 1.5px solid rgba(16, 185, 129, 0.45);">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge" style="background: #10b981; color: #ffffff; font-weight: 800; font-size: 10px;">2 • SECOND ROW</span>
                                <span class="badge bg-dark text-success border border-success border-opacity-25" style="font-size: 9.5px;">[SECTION 2]</span>
                            </div>
                            <h6 class="fw-bold text-white mb-1" style="font-size: 13px;">
                                <i class="bi bi-bag-check-fill text-success me-1"></i> BEST SELLING
                            </h6>
                            <p class="text-white-50 mb-2" style="font-size: 11px; line-height: 1.35;">
                                Placed <strong>ABOVE</strong> banners. % OFF badge, Quick View & Add to Cart.
                            </p>
                        </div>
                        <div class="pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <span class="badge bg-success-subtle text-success" style="font-size: 9.5px;">Column: Sec 2</span>
                            <span class="text-success small" style="font-size: 10.5px;">Above Banners</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: IMAGE 2 SECTION (3 MEGA BANNERS - MIDDLE) -->
                <div class="col">
                    <div class="p-3 rounded-3 h-100 position-relative d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.08); border: 1.5px solid rgba(139, 92, 246, 0.55);">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge" style="background: #8b5cf6; color: #ffffff; font-weight: 800; font-size: 10px;">3 • MIDDLE ROW</span>
                                <span class="badge bg-dark text-purple border border-purple border-opacity-25" style="font-size: 9.5px; color: #c4b5fd;">[IMAGE 2 SECTION]</span>
                            </div>
                            <h6 class="fw-bold text-white mb-1" style="font-size: 13px;">
                                <i class="bi bi-images text-warning me-1"></i> 3 Mega Promo Banners
                            </h6>
                            <p class="text-white-50 mb-2" style="font-size: 11px; line-height: 1.35;">
                                Mega Construction, Kajaria & Astral (Height: <strong>290px</strong>).
                            </p>
                        </div>
                        <div class="pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <span class="badge bg-purple-subtle text-purple" style="font-size: 9.5px; background: #ede9fe; color: #7c3aed;">Tab 2: Banners</span>
                            <a href="#banners-pane" onclick="document.getElementById('banners-tab').click()" class="text-warning small text-decoration-none fw-bold" style="font-size: 10.5px;">
                                Manage <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: SECTION 3 (TRENDING - BELOW BANNERS) -->
                <div class="col">
                    <div class="p-3 rounded-3 h-100 position-relative d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.05); border: 1.5px solid rgba(37, 99, 235, 0.45);">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge" style="background: #2563eb; color: #ffffff; font-weight: 800; font-size: 10px;">4 • FOURTH ROW</span>
                                <span class="badge bg-dark text-primary border border-primary border-opacity-25" style="font-size: 9.5px; color: #93c5fd;">[SECTION 3]</span>
                            </div>
                            <h6 class="fw-bold text-white mb-1" style="font-size: 13px;">
                                <i class="bi bi-fire text-primary me-1"></i> Trending Products
                            </h6>
                            <p class="text-white-50 mb-2" style="font-size: 11px; line-height: 1.35;">
                                Placed <strong>BELOW</strong> banners. Can show entirely different items than Sec 1.
                            </p>
                        </div>
                        <div class="pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <span class="badge bg-primary-subtle text-primary" style="font-size: 9.5px;">Column: Sec 3</span>
                            <span class="text-primary small" style="font-size: 10.5px;">Below Banners</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: SECTION 4 (BEST SELLING - BELOW BANNERS) -->
                <div class="col">
                    <div class="p-3 rounded-3 h-100 position-relative d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.05); border: 1.5px solid rgba(239, 68, 68, 0.45);">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge" style="background: #ef4444; color: #ffffff; font-weight: 800; font-size: 10px;">5 • FIFTH ROW</span>
                                <span class="badge bg-dark text-danger border border-danger border-opacity-25" style="font-size: 9.5px;">[SECTION 4]</span>
                            </div>
                            <h6 class="fw-bold text-white mb-1" style="font-size: 13px;">
                                <i class="bi bi-award-fill text-danger me-1"></i> BEST SELLING
                            </h6>
                            <p class="text-white-50 mb-2" style="font-size: 11px; line-height: 1.35;">
                                Placed <strong>BELOW</strong> banners. Independent selection across 4 tabs.
                            </p>
                        </div>
                        <div class="pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <span class="badge bg-danger-subtle text-danger" style="font-size: 9.5px;">Column: Sec 4</span>
                            <span class="text-danger small" style="font-size: 10.5px;">Below Banners</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. METRICS COUNTERS -->
    <div class="row g-3 mb-4">
        <!-- SCREENSHOT SECTION: 4 QUICK ACTION PROMO CARDS -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4" style="border-left-color: #0284c7 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">[Screenshot] 4 Action Cards</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['quick_promos'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Above Trending Now)</div>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-grid-1x2-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCREENSHOT SECTION: TRENDING NOW -->
        <div class="col-6 col-md-3 col-xl-3">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4" style="border-left-color: #ff5400 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">[Screenshot] Trending Now</div>
                        <div class="fs-4 fw-bold text-dark mt-1" style="color: #ff5400 !important;">{{ $stats['is_trending_now'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Horizontal Carousel)</div>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #fff2ea; color: #ff5400;">
                        <i class="bi bi-fire fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-xl-2">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">[Sec 1] Trending</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['sec1_trending'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Above Banners)</div>
                    </div>
                    <div class="rounded-3 bg-warning-subtle text-warning p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-fire fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-xl-2">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">[Sec 2] Best Seller</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['sec2_best_seller'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Above Banners)</div>
                    </div>
                    <div class="rounded-3 bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-bag-check-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-xl-2">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4 border-purple" style="border-left-color: #8b5cf6 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">[Image 2] 3 Mega Ads</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['banners'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Middle Row)</div>
                    </div>
                    <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #ede9fe; color: #8b5cf6;">
                        <i class="bi bi-images fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-xl-2">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">[Sec 3] Trending</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['sec3_trending'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Below Banners)</div>
                    </div>
                    <div class="rounded-3 bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-fire fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-xl-2">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4 border-danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">[Sec 4] Best Seller</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['sec4_best_seller'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Below Banners)</div>
                    </div>
                    <div class="rounded-3 bg-danger-subtle text-danger p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-award-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3 col-xl-2">
            <div class="card border-0 rounded-3 shadow-xs h-100 p-3 bg-white border-start border-4 border-secondary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small fw-semibold">Catalog Total</div>
                        <div class="fs-4 fw-bold text-dark mt-1">{{ $stats['total_active'] }}</div>
                        <div class="text-muted" style="font-size: 10.5px;">(Active & Approved)</div>
                    </div>
                    <div class="rounded-3 bg-secondary-subtle text-secondary p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-check-all fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MAIN TABS NAVIGATION -->
    <div class="card border-0 rounded-3 shadow-xs mb-4">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs border-0 px-3 pt-2" id="homepageManagerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark py-3 px-3 border-0 border-bottom border-3 border-primary" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-pane" type="button" role="tab">
                        <i class="bi bi-box-seam me-1 text-primary"></i> 1. Catalog Section Placements
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-muted py-3 px-3 border-0" id="banners-tab" data-bs-toggle="tab" data-bs-target="#banners-pane" type="button" role="tab">
                        <i class="bi bi-images me-1 text-warning"></i> 2. Mega Banners & Hero Ads ({{ $banners->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-muted py-3 px-3 border-0" id="quick-cards-tab" data-bs-toggle="tab" data-bs-target="#quick-cards-pane" type="button" role="tab">
                        <i class="bi bi-grid-1x2-fill me-1" style="color: #0284c7;"></i> 3. [Screenshot] 4 Action Cards ({{ $quickPromoCards->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-muted py-3 px-3 border-0" id="trending-now-tab" data-bs-toggle="tab" data-bs-target="#trending-now-pane" type="button" role="tab">
                        <i class="bi bi-fire me-1" style="color: #ff5400;"></i> 4. [Screenshot] Trending Now & View All
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-muted py-3 px-3 border-0" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-pane" type="button" role="tab">
                        <i class="bi bi-tags me-1 text-success"></i> 5. Category Pills
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-muted py-3 px-3 border-0" id="new-arrivals-tab" data-bs-toggle="tab" data-bs-target="#new-arrivals-pane" type="button" role="tab">
                        <i class="bi bi-stars me-1" style="color: #ea580c;"></i> 6. [Image 3] New Arrivals & Why Choose
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="homepageManagerTabsContent">

                <!-- ======================================================== -->
                <!-- PANE 1: PRODUCTS & SECTION PLACEMENTS                    -->
                <!-- ======================================================== -->
                <div class="tab-pane fade show active" id="products-pane" role="tabpanel">

                    <!-- Explanatory Legend for Admin Understanding -->
                    <div class="alert alert-light border rounded-3 p-3 mb-3" style="background: #f8fafc;">
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                            <div class="small">
                                <span class="fw-bold text-dark me-2"><i class="bi bi-info-circle-fill text-primary"></i> Numbered Section Placements:</span>
                                <span class="badge bg-warning text-dark me-1">1. [Sec 1] Trending (Above)</span>
                                <span class="badge bg-success text-white me-1">2. [Sec 2] Best Selling (Above)</span>
                                <span class="badge bg-dark text-warning border border-warning me-1">3. [Image 2] 3 Mega Ads</span>
                                <span class="badge bg-primary text-white me-1">4. [Sec 3] Trending (Below)</span>
                                <span class="badge bg-danger text-white">5. [Sec 4] Best Selling (Below)</span>
                            </div>
                            <div class="small fw-semibold text-muted text-nowrap">
                                Showing {{ $catalogProducts->firstItem() ?? 0 }}-{{ $catalogProducts->lastItem() ?? 0 }} of {{ $catalogProducts->total() }} Products
                            </div>
                        </div>
                    </div>

                    <!-- Search & Filter Bar -->
                    <form method="GET" action="{{ route('admin.homepage.index') }}" class="row g-2 mb-3 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Search product name, SKU, or category...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="section" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Storefront Sections</option>
                                <option value="trending_now" {{ request('section') === 'trending_now' ? 'selected' : '' }}>★ [Screenshot] Trending Now Section</option>
                                <option value="sec1" {{ request('section') === 'sec1' ? 'selected' : '' }}>[Section 1] Trending Products (Above Banners)</option>
                                <option value="sec2" {{ request('section') === 'sec2' ? 'selected' : '' }}>[Section 2] Best Selling (Above Banners)</option>
                                <option value="sec3" {{ request('section') === 'sec3' ? 'selected' : '' }}>[Section 3] Trending Products (Below Banners)</option>
                                <option value="sec4" {{ request('section') === 'sec4' ? 'selected' : '' }}>[Section 4] Best Selling (Below Banners)</option>
                                <option value="new_arrival" {{ request('section') === 'new_arrival' ? 'selected' : '' }}>★ [Image 3] New Arrivals Section</option>
                            </select>
                        </div>

                        <div class="col-md-1 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-dark w-100">Filter</button>
                            @if(request()->hasAny(['search', 'category_id', 'section']))
                                <a href="{{ route('admin.homepage.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset"><i class="bi bi-x"></i></a>
                            @endif
                        </div>
                    </form>

                    <!-- Products Table with Explicit Section Markers in Table Header -->
                    <div class="table-responsive border rounded-3">
                        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">Image</th>
                                    <th>Product Details</th>
                                    <th>Price / Unit</th>
                                    <th>Category</th>
                                    
                                    <!-- COLUMN 0: [SCREENSHOT] TRENDING NOW -->
                                    <th class="text-center border-start" style="width: 135px; background: rgba(255, 84, 0, 0.08);">
                                        <span class="badge text-white mb-1 fw-bold" style="font-size: 9.5px; background: #ff5400;">SCREENSHOT</span>
                                        <div class="fw-bold text-dark">Trending Now</div>
                                        <div class="text-muted" style="font-size: 10px;">(White Cards Row)</div>
                                    </th>

                                    <!-- COLUMN 1: SECTION 1 (TRENDING - ABOVE BANNERS) -->
                                    <th class="text-center border-start" style="width: 125px; background: rgba(245, 158, 11, 0.08);">
                                        <span class="badge bg-warning text-dark mb-1 fw-bold" style="font-size: 9.5px;">SECTION 1</span>
                                        <div class="fw-bold text-dark">Trending</div>
                                        <div class="text-muted" style="font-size: 10px;">(Above Banners)</div>
                                    </th>

                                    <!-- COLUMN 2: SECTION 2 (BEST SELLING - ABOVE BANNERS) -->
                                    <th class="text-center border-start" style="width: 125px; background: rgba(16, 185, 129, 0.08);">
                                        <span class="badge bg-success text-white mb-1 fw-bold" style="font-size: 9.5px;">SECTION 2</span>
                                        <div class="fw-bold text-dark">Best Selling</div>
                                        <div class="text-muted" style="font-size: 10px;">(Above Banners)</div>
                                    </th>

                                    <!-- COLUMN 3: SECTION 3 (TRENDING - BELOW BANNERS) -->
                                    <th class="text-center border-start" style="width: 125px; background: rgba(37, 99, 235, 0.08);">
                                        <span class="badge bg-primary text-white mb-1 fw-bold" style="font-size: 9.5px;">SECTION 3</span>
                                        <div class="fw-bold text-dark">Trending</div>
                                        <div class="text-muted" style="font-size: 10px;">(Below Banners)</div>
                                    </th>

                                    <!-- COLUMN 4: SECTION 4 (BEST SELLING - BELOW BANNERS) -->
                                    <th class="text-center border-start" style="width: 125px; background: rgba(239, 68, 68, 0.08);">
                                        <span class="badge bg-danger text-white mb-1 fw-bold" style="font-size: 9.5px;">SECTION 4</span>
                                        <div class="fw-bold text-dark">Best Selling</div>
                                        <div class="text-muted" style="font-size: 10px;">(Below Banners)</div>
                                    </th>

                                    <!-- COLUMN 5: IMAGE 3 NEW ARRIVALS -->
                                    <th class="text-center border-start border-end" style="width: 125px; background: rgba(234, 88, 12, 0.08);">
                                        <span class="badge text-white mb-1 fw-bold" style="font-size: 9.5px; background: #ea580c;">IMAGE 3</span>
                                        <div class="fw-bold text-dark">New Arrivals</div>
                                        <div class="text-muted" style="font-size: 10px;">(Section 12 Row)</div>
                                    </th>

                                    <th class="text-end" style="width: 70px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($catalogProducts as $prod)
                                    <tr>
                                        <td>
                                            <div class="rounded-2 border bg-light d-flex align-items-center justify-content-center p-1" style="width: 48px; height: 48px; overflow: hidden;">
                                                <img src="{{ $prod->primary_image_url }}" onerror="this.onerror=null; this.src='/images/product-placeholder.png';" alt="{{ $prod->name }}" style="max-height: 40px; max-width: 40px; object-fit: contain;">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $prod->name }}</div>
                                            <div class="text-muted small d-flex align-items-center gap-2">
                                                <span>SKU: <code>{{ $prod->sku }}</code></span>
                                                <span>•</span>
                                                <span class="text-warning"><i class="bi bi-star-fill"></i> {{ number_format($prod->rating ?: 4.8, 1) }} ({{ $prod->rating_count }})</span>
                                                @if($prod->vendor)
                                                    <span>•</span>
                                                    <span class="text-muted"><i class="bi bi-shop"></i> {{ $prod->vendor->company_name }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">₹{{ number_format($prod->selling_price) }}</div>
                                            <div class="text-muted" style="font-size: 11px;">/ {{ $prod->unit ?: 'Piece' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $prod->category->name ?? 'Uncategorized' }}</span>
                                        </td>

                                        <!-- COLUMN 0 TOGGLE: [SCREENSHOT] TRENDING NOW -->
                                        <td class="text-center border-start" style="background: rgba(255, 84, 0, 0.03);">
                                            <button type="button" onclick="ajaxToggleSection({{ $prod->id }}, 'is_trending_now', this)" 
                                                class="btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn {{ $prod->is_trending_now ? 'text-white fw-bold' : 'btn-outline-secondary' }}" 
                                                style="font-size: 11px; min-width: 80px; {{ $prod->is_trending_now ? 'background-color: #ff5400; border-color: #ff5400;' : '' }}" 
                                                title="Toggle for Screenshot Trending Now Section">
                                                <i class="bi {{ $prod->is_trending_now ? 'bi-fire' : 'bi-dash' }}"></i> {{ $prod->is_trending_now ? 'Active' : 'Off' }}
                                            </button>
                                        </td>

                                        <!-- COLUMN 1 TOGGLE: SECTION 1 (TRENDING - ABOVE BANNERS) -->
                                        <td class="text-center border-start" style="background: rgba(245, 158, 11, 0.03);">
                                            <button type="button" onclick="ajaxToggleSection({{ $prod->id }}, 'sec1_trending', this)" 
                                                class="btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn {{ $prod->sec1_trending ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary' }}" 
                                                style="font-size: 11px; min-width: 80px;" title="Toggle for Section 1: Trending Products (Above Banners)">
                                                <i class="bi {{ $prod->sec1_trending ? 'bi-fire' : 'bi-dash' }}"></i> {{ $prod->sec1_trending ? 'Active' : 'Off' }}
                                            </button>
                                        </td>

                                        <!-- COLUMN 2 TOGGLE: SECTION 2 (BEST SELLING - ABOVE BANNERS) -->
                                        <td class="text-center border-start" style="background: rgba(16, 185, 129, 0.03);">
                                            <button type="button" onclick="ajaxToggleSection({{ $prod->id }}, 'sec2_best_seller', this)" 
                                                class="btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn {{ $prod->sec2_best_seller ? 'btn-success text-white fw-bold' : 'btn-outline-secondary' }}" 
                                                style="font-size: 11px; min-width: 80px;" title="Toggle for Section 2: Best Selling (Above Banners)">
                                                <i class="bi {{ $prod->sec2_best_seller ? 'bi-check2-circle' : 'bi-dash' }}"></i> {{ $prod->sec2_best_seller ? 'Active' : 'Off' }}
                                            </button>
                                        </td>

                                        <!-- COLUMN 3 TOGGLE: SECTION 3 (TRENDING - BELOW BANNERS) -->
                                        <td class="text-center border-start" style="background: rgba(37, 99, 235, 0.03);">
                                            <button type="button" onclick="ajaxToggleSection({{ $prod->id }}, 'sec3_trending', this)" 
                                                class="btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn {{ $prod->sec3_trending ? 'btn-primary text-white fw-bold' : 'btn-outline-secondary' }}" 
                                                style="font-size: 11px; min-width: 80px;" title="Toggle for Section 3: Trending Products (Below Banners)">
                                                <i class="bi {{ $prod->sec3_trending ? 'bi-fire' : 'bi-dash' }}"></i> {{ $prod->sec3_trending ? 'Active' : 'Off' }}
                                            </button>
                                        </td>

                                        <!-- COLUMN 4 TOGGLE: SECTION 4 (BEST SELLING - BELOW BANNERS) -->
                                        <td class="text-center border-start" style="background: rgba(239, 68, 68, 0.03);">
                                            <button type="button" onclick="ajaxToggleSection({{ $prod->id }}, 'sec4_best_seller', this)" 
                                                class="btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn {{ $prod->sec4_best_seller ? 'btn-danger text-white fw-bold' : 'btn-outline-secondary' }}" 
                                                style="font-size: 11px; min-width: 80px;" title="Toggle for Section 4: Best Selling (Below Banners)">
                                                <i class="bi {{ $prod->sec4_best_seller ? 'bi-bag-check-fill' : 'bi-dash' }}"></i> {{ $prod->sec4_best_seller ? 'Active' : 'Off' }}
                                            </button>
                                        </td>

                                        <!-- COLUMN 5 TOGGLE: IMAGE 3 NEW ARRIVALS -->
                                        <td class="text-center border-start border-end" style="background: rgba(234, 88, 12, 0.03);">
                                            <button type="button" onclick="ajaxToggleSection({{ $prod->id }}, 'is_new_arrival', this)" 
                                                class="btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn {{ $prod->is_new_arrival ? 'text-white fw-bold' : 'btn-outline-secondary' }}" 
                                                style="font-size: 11px; min-width: 80px; {{ $prod->is_new_arrival ? 'background-color: #ea580c; border-color: #ea580c;' : '' }}" 
                                                title="Toggle for Section 12: New Arrivals Showcase">
                                                <i class="bi {{ $prod->is_new_arrival ? 'bi-stars' : 'bi-dash' }}"></i> {{ $prod->is_new_arrival ? 'Active' : 'Off' }}
                                            </button>
                                        </td>

                                        <td class="text-end">
                                            <a href="{{ route('products.show', $prod->slug) }}" target="_blank" class="btn btn-sm btn-light border p-1" title="View on storefront">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                            No products found matching your filter criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $catalogProducts->links() }}
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- PANE 2: PROMOTIONAL SALE ADS & BANNERS                   -->
                <!-- ======================================================== -->
                <div class="tab-pane fade" id="banners-pane" role="tabpanel">
                    
                    <!-- ======================================================== -->
                    <!-- ROW 1: [IMAGE 2 SECTION] 3 MEGA PROMOTIONAL BANNERS     -->
                    <!-- ======================================================== -->
                    <div class="alert alert-warning border-warning border-opacity-50 rounded-3 p-3 mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background: #fffbeb;">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-warning text-dark p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-images fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <span>[IMAGE 2 SECTION - ROW 1] 3 Mega Promotional Banners (Height: 290px)</span>
                                    <span class="badge bg-warning text-dark fw-bold">Upper Row: Between Sec 2 & Sec 3</span>
                                </div>
                                <div class="text-secondary small mt-0.5">
                                    These 3 banners appear in the <strong>middle of the homepage</strong> — directly between Section 1 & 2 (Above) and Section 3 & 4 (Below). Active banners in Row 1 are rendered directly.
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-dark text-nowrap" data-bs-toggle="modal" data-bs-target="#createBannerModalRow1">
                            <i class="bi bi-plus-lg me-1"></i> Add Banner (Row 1)
                        </button>
                    </div>

                    <div class="row g-3 mb-4">
                        @forelse($bannersRow1 as $b)
                            <div class="col-lg-4 col-md-6">
                                <div class="card border rounded-3 overflow-hidden shadow-xs h-100 d-flex flex-column justify-content-between">
                                    <!-- Banner Image Preview -->
                                    <div class="position-relative" style="height: 150px; background: {{ $b->background_color ?: '#0b0f19' }}; overflow: hidden;">
                                        <img src="{{ $b->image_url }}" onerror="this.onerror=null; this.src='/images/banners/mega_construction_sale.jpg';" alt="{{ $b->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        <span class="badge position-absolute top-0 start-0 m-2 px-2 py-1 {{ $b->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($b->status) }}
                                        </span>
                                        <span class="badge position-absolute top-0 end-0 m-2 px-2 py-1 bg-dark text-white fw-bold">
                                            Row 1
                                        </span>
                                        @if($b->subtitle)
                                            <span class="badge position-absolute bottom-0 start-0 m-2 px-2 py-1 bg-warning text-dark fw-bold">
                                                {{ $b->subtitle }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $b->title }}">{!! nl2br(e($b->title)) !!}</h6>
                                        <div class="text-muted small mb-2 text-truncate">Link: <code>{{ $b->link ?: 'None' }}</code></div>
                                        <div class="text-muted small">Button: <span class="badge bg-light text-dark border">{{ $b->button_text ?: 'Explore Now' }}</span></div>
                                    </div>

                                    <div class="card-footer bg-light border-top p-2.5 d-flex align-items-center justify-content-between">
                                        <form action="{{ route('admin.homepage.banners.toggle', $b->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $b->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}" style="font-size: 11px;">
                                                <i class="bi {{ $b->status === 'active' ? 'bi-pause-fill' : 'bi-play-fill' }}"></i> {{ $b->status === 'active' ? 'Pause Ad' : 'Activate Ad' }}
                                            </button>
                                        </form>

                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBannerModal{{ $b->id }}" style="font-size: 11px;">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>

                                            <form action="{{ route('admin.homepage.banners.destroy', $b->id) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this banner ad?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size: 11px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT BANNER MODAL -->
                            <div class="modal fade" id="editBannerModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.homepage.banners.update', $b->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Banner Ad (Row {{ $b->section_row ?? 1 }})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Placement Row</label>
                                                <select name="section_row" class="form-select form-select-sm">
                                                    <option value="1" {{ ($b->section_row ?? 1) == 1 ? 'selected' : '' }}>Row 1 (Upper Placement - Between Sec 2 & Sec 3)</option>
                                                    <option value="2" {{ ($b->section_row ?? 1) == 2 ? 'selected' : '' }}>Row 2 (Lower Placement - Above Highlights)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Banner Title / Heading</label>
                                                <input type="text" name="title" value="{{ $b->title }}" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Subtitle / Discount Tag</label>
                                                <input type="text" name="subtitle" value="{{ $b->subtitle }}" class="form-control form-control-sm" placeholder="e.g. UP TO 40% or Elegant Tiles For Every Space">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Button Text</label>
                                                <input type="text" name="button_text" value="{{ $b->button_text }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Target Link / URL</label>
                                                <input type="text" name="link" value="{{ $b->link }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Background Color</label>
                                                <input type="text" name="background_color" value="{{ $b->background_color ?: '#0b0f19' }}" class="form-control form-control-sm" placeholder="#0b0f19">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Upload New Image</label>
                                                <input type="file" name="banner_image" class="form-control form-control-sm" accept="image/*">
                                                <div class="form-text">Leave blank to keep existing image.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Or Image URL / Path</label>
                                                <input type="text" name="image_url" value="{{ $b->image }}" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted">
                                <i class="bi bi-images fs-2 d-block mb-2 text-secondary"></i>
                                No promotional banners found in Row 1. Click "Add Banner (Row 1)" to create one.
                            </div>
                        @endforelse
                    </div>

                    <!-- ======================================================== -->
                    <!-- ROW 2: [IMAGE 2 SECTION] 3 MEGA PROMOTIONAL BANNERS     -->
                    <!-- ======================================================== -->
                    <div class="alert alert-info border-info border-opacity-50 rounded-3 p-3 mb-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background: #f0f9ff;">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle bg-info text-dark p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-images fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <span>[IMAGE 2 SECTION - ROW 2] 3 Mega Promotional Banners (Height: 290px)</span>
                                    <span class="badge bg-info text-dark fw-bold">Lower Row: Above Highlights</span>
                                </div>
                                <div class="text-secondary small mt-0.5">
                                    These 3 banners appear in the <strong>lower placement of the homepage</strong> — directly above the bottom service highlight cards. Active banners in Row 2 are rendered directly.
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-dark text-nowrap" data-bs-toggle="modal" data-bs-target="#createBannerModalRow2">
                            <i class="bi bi-plus-lg me-1"></i> Add Banner (Row 2)
                        </button>
                    </div>

                    <div class="row g-3 mb-4">
                        @forelse($bannersRow2 as $b)
                            <div class="col-lg-4 col-md-6">
                                <div class="card border rounded-3 overflow-hidden shadow-xs h-100 d-flex flex-column justify-content-between">
                                    <!-- Banner Image Preview -->
                                    <div class="position-relative" style="height: 150px; background: {{ $b->background_color ?: '#0b0f19' }}; overflow: hidden;">
                                        <img src="{{ $b->image_url }}" onerror="this.onerror=null; this.src='/images/banners/mega_construction_sale.jpg';" alt="{{ $b->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        <span class="badge position-absolute top-0 start-0 m-2 px-2 py-1 {{ $b->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($b->status) }}
                                        </span>
                                        <span class="badge position-absolute top-0 end-0 m-2 px-2 py-1 bg-dark text-white fw-bold">
                                            Row 2
                                        </span>
                                        @if($b->subtitle)
                                            <span class="badge position-absolute bottom-0 start-0 m-2 px-2 py-1 bg-warning text-dark fw-bold">
                                                {{ $b->subtitle }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $b->title }}">{!! nl2br(e($b->title)) !!}</h6>
                                        <div class="text-muted small mb-2 text-truncate">Link: <code>{{ $b->link ?: 'None' }}</code></div>
                                        <div class="text-muted small">Button: <span class="badge bg-light text-dark border">{{ $b->button_text ?: 'Explore Now' }}</span></div>
                                    </div>

                                    <div class="card-footer bg-light border-top p-2.5 d-flex align-items-center justify-content-between">
                                        <form action="{{ route('admin.homepage.banners.toggle', $b->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $b->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}" style="font-size: 11px;">
                                                <i class="bi {{ $b->status === 'active' ? 'bi-pause-fill' : 'bi-play-fill' }}"></i> {{ $b->status === 'active' ? 'Pause Ad' : 'Activate Ad' }}
                                            </button>
                                        </form>

                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBannerModal{{ $b->id }}" style="font-size: 11px;">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>

                                            <form action="{{ route('admin.homepage.banners.destroy', $b->id) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this banner ad?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size: 11px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT BANNER MODAL -->
                            <div class="modal fade" id="editBannerModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.homepage.banners.update', $b->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Banner Ad (Row {{ $b->section_row ?? 2 }})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Placement Row</label>
                                                <select name="section_row" class="form-select form-select-sm">
                                                    <option value="1" {{ ($b->section_row ?? 2) == 1 ? 'selected' : '' }}>Row 1 (Upper Placement - Between Sec 2 & Sec 3)</option>
                                                    <option value="2" {{ ($b->section_row ?? 2) == 2 ? 'selected' : '' }}>Row 2 (Lower Placement - Above Highlights)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Banner Title / Heading</label>
                                                <input type="text" name="title" value="{{ $b->title }}" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Subtitle / Discount Tag</label>
                                                <input type="text" name="subtitle" value="{{ $b->subtitle }}" class="form-control form-control-sm" placeholder="e.g. UP TO 40% or Elegant Tiles For Every Space">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Button Text</label>
                                                <input type="text" name="button_text" value="{{ $b->button_text }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Target Link / URL</label>
                                                <input type="text" name="link" value="{{ $b->link }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Background Color</label>
                                                <input type="text" name="background_color" value="{{ $b->background_color ?: '#0b0f19' }}" class="form-control form-control-sm" placeholder="#0b0f19">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Upload New Image</label>
                                                <input type="file" name="banner_image" class="form-control form-control-sm" accept="image/*">
                                                <div class="form-text">Leave blank to keep existing image.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Or Image URL / Path</label>
                                                <input type="text" name="image_url" value="{{ $b->image }}" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted">
                                <i class="bi bi-images fs-2 d-block mb-2 text-secondary"></i>
                                No promotional banners found in Row 2. Click "Add Banner (Row 2)" to create one.
                            </div>
                        @endforelse
                    </div>

                    <!-- ======================================================== -->
                    <!-- PART B: [IMAGE 3 SECTION] HERO SPECIAL CARDS            -->
                    <!-- ======================================================== -->
                    <div class="card border rounded-3 p-3 mb-2 mt-4" style="background: #f8fafc;">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: #0f172a; color: #ff5400;">
                                    <i class="bi bi-layout-split fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">[IMAGE 3 SECTION] Hero Special Cards (Bulk Order Special & Auction Zone)</h6>
                                    <p class="text-muted small mb-0">Control the 2 dark hero cards displayed prominently at the top right of the landing page.</p>
                                </div>
                            </div>
                            <span class="badge bg-dark text-white px-2.5 py-1">Hero Storefront Placement</span>
                        </div>

                        <form action="{{ route('admin.homepage.hero-cards.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <!-- Card 1: Bulk Order Special -->
                                <div class="col-lg-6">
                                    <div class="card h-100 border-0 rounded-3 shadow-xs p-3" style="background: #091b3a; color: #ffffff;">
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-white border-opacity-10">
                                            <span class="badge bg-warning text-dark fw-bold">Card 3 • Bulk Order</span>
                                            <span class="badge bg-dark text-white-50 border border-white border-opacity-25">Hero Section</span>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Badge Text</label>
                                                <input type="text" name="hero_bulk_badge" value="{{ $heroCardSettings['hero_bulk_badge'] ?? 'B2B WHOLESALE' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Sub-tag / Min Limit</label>
                                                <input type="text" name="hero_bulk_min" value="{{ $heroCardSettings['hero_bulk_min'] ?? 'Min. ₹50K+' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Title Headline</label>
                                            <input type="text" name="hero_bulk_title" value="{{ $heroCardSettings['hero_bulk_title'] ?? 'BULK ORDER SPECIAL' }}" class="form-control form-control-sm bg-dark text-white border-secondary fw-bold" required>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Subhead Highlight</label>
                                            <input type="text" name="hero_bulk_subhead" value="{{ $heroCardSettings['hero_bulk_subhead'] ?? 'Save Up To 40% On Factory Loads' }}" class="form-control form-control-sm bg-dark text-white border-secondary text-warning">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Description</label>
                                            <textarea name="hero_bulk_desc" class="form-control form-control-sm bg-dark text-white border-secondary" rows="2">{{ $heroCardSettings['hero_bulk_desc'] ?? 'Direct wholesale rates for commercial contractors & builders.' }}</textarea>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Features / Checklist (One per line)</label>
                                            <textarea name="hero_bulk_features" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace" rows="3" style="font-size: 11px;">{{ $heroCardSettings['hero_bulk_features'] ?? "Dedicated Account Manager\nPriority Fleet Dispatch\nCustom GST Quotations\nFlexible Net-30 Terms" }}</textarea>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Button Text</label>
                                                <input type="text" name="hero_bulk_btn_text" value="{{ $heroCardSettings['hero_bulk_btn_text'] ?? 'Request Bulk Quote' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Button Link URL</label>
                                                <input type="text" name="hero_bulk_btn_url" value="{{ $heroCardSettings['hero_bulk_btn_url'] ?? '/quotations' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Image URL / Path</label>
                                            <input type="text" name="hero_bulk_image" value="{{ $heroCardSettings['hero_bulk_image'] ?? '' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                        </div>
                                        <div>
                                            <label class="form-label small text-white-50 mb-1">Upload New 3D Image</label>
                                            <input type="file" name="hero_bulk_image_file" class="form-control form-control-sm bg-dark text-white border-secondary" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Auction Zone -->
                                <div class="col-lg-6">
                                    <div class="card h-100 border-0 rounded-3 shadow-xs p-3" style="background: #0d1527; color: #ffffff;">
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-white border-opacity-10">
                                            <span class="badge bg-danger text-white fw-bold">Card 4 • Auction Zone</span>
                                            <span class="badge bg-dark text-white-50 border border-white border-opacity-25">Hero Section</span>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Badge Text</label>
                                                <input type="text" name="hero_auction_badge" value="{{ $heroCardSettings['hero_auction_badge'] ?? 'LIVE AUCTIONS' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Discount Sub-tag</label>
                                                <input type="text" name="hero_auction_discount" value="{{ $heroCardSettings['hero_auction_discount'] ?? 'Up To 60% OFF' }}" class="form-control form-control-sm bg-dark text-white border-secondary text-warning">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Title Headline</label>
                                            <input type="text" name="hero_auction_title" value="{{ $heroCardSettings['hero_auction_title'] ?? 'AUCTION ZONE' }}" class="form-control form-control-sm bg-dark text-white border-secondary fw-bold" required>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Subhead Highlight</label>
                                            <input type="text" name="hero_auction_subhead" value="{{ $heroCardSettings['hero_auction_subhead'] ?? 'Bid & Save On Surplus Materials' }}" class="form-control form-control-sm bg-dark text-white border-secondary text-warning">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Description</label>
                                            <textarea name="hero_auction_desc" class="form-control form-control-sm bg-dark text-white border-secondary" rows="2">{{ $heroCardSettings['hero_auction_desc'] ?? 'Acquire surplus steel, tiles, electricals & plant equipment at floor rates.' }}</textarea>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Features / Checklist (One per line)</label>
                                            <textarea name="hero_auction_features" class="form-control form-control-sm bg-dark text-white border-secondary font-monospace" rows="3" style="font-size: 11px;">{{ $heroCardSettings['hero_auction_features'] ?? "Real-Time Live Bidding\n100% Verified Quality Lots\nEscrow Protected Checkout" }}</textarea>
                                        </div>

                                        <div class="row g-2 mb-2">
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Button Text</label>
                                                <input type="text" name="hero_auction_btn_text" value="{{ $heroCardSettings['hero_auction_btn_text'] ?? 'Explore Auctions' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-white-50 mb-1">Button Link URL</label>
                                                <input type="text" name="hero_auction_btn_url" value="{{ $heroCardSettings['hero_auction_btn_url'] ?? '/products?filter=trending' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label small text-white-50 mb-1">Image URL / Path</label>
                                            <input type="text" name="hero_auction_image" value="{{ $heroCardSettings['hero_auction_image'] ?? '' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                        </div>
                                        <div>
                                            <label class="form-label small text-white-50 mb-1">Upload New 3D Image</label>
                                            <input type="file" name="hero_auction_image_file" class="form-control form-control-sm bg-dark text-white border-secondary" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm" style="background: #ff5400; border-color: #ff5400;">
                                    <i class="bi bi-check-circle me-1"></i> Save Hero Special Cards
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- PANE 3: [SCREENSHOT] 4 QUICK ACTION PROMO CARDS          -->
                <!-- ======================================================== -->
                <div class="tab-pane fade" id="quick-cards-pane" role="tabpanel">
                    <div class="alert alert-info border-info border-opacity-50 rounded-3 p-3 mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3" style="background: #f0f9ff;">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="rounded-circle text-white p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #0284c7;">
                                <i class="bi bi-grid-1x2-fill fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                    <span>[SCREENSHOT TOP SECTION] 4 Quick Action Promo Cards</span>
                                    <span class="badge text-white fw-bold" style="background: #0284c7;">Directly Above Trending Now</span>
                                </div>
                                <div class="text-secondary small mt-0.5">
                                    Control the 4 dark navy action cards seen in the screenshot (Bulk Order, Ready Stock, Combo Offers, Post Requirement). Change titles, button text, destination link, or upload new realistic 3D imagery.
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm text-white text-nowrap" data-bs-toggle="modal" data-bs-target="#createPromoCardModal" style="background: #0284c7;">
                            <i class="bi bi-plus-lg me-1"></i> Add Action Card
                        </button>
                    </div>

                    <div class="row g-3">
                        @forelse($quickPromoCards as $card)
                            <div class="col-lg-3 col-md-6">
                                <div class="card border rounded-3 overflow-hidden shadow-xs h-100 d-flex flex-column justify-content-between">
                                    <!-- Card Preview Container mimicking frontend appearance -->
                                    <div class="p-3 position-relative text-white d-flex flex-column justify-content-between" style="background: {{ $card->bg_gradient ?: 'linear-gradient(135deg, #091b3a 0%, #061226 100%)' }}; min-height: 210px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                                        <!-- Top row: order + status -->
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-dark text-white-50 border border-secondary" style="font-size: 10px;">
                                                Slot #{{ $card->sort_order ?: ($loop->index + 1) }}
                                            </span>
                                            <span class="badge {{ $card->is_active ? 'bg-success' : 'bg-secondary' }}" style="font-size: 10px;">
                                                {{ $card->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <!-- Middle row: text + 3D image -->
                                        <div class="d-flex align-items-center justify-content-between gap-2 my-auto">
                                            <div style="max-width: 65%;">
                                                <div class="fw-bold text-white text-uppercase lh-1 mb-1" style="font-size: 14px; letter-spacing: -0.2px;">
                                                    {{ $card->title_line1 }}
                                                </div>
                                                @if($card->title_line2)
                                                    <div class="fw-bold text-uppercase lh-1 mb-1.5" style="font-size: 14px; color: {{ $card->button_color ?: '#ff5400' }};">
                                                        {{ $card->title_line2 }}
                                                    </div>
                                                @endif
                                                <div class="text-white-50" style="font-size: 10.5px; line-height: 1.25;">
                                                    {{ $card->subtitle }}
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background: rgba(255,255,255,0.05); border-radius: 8px;">
                                                <img src="{{ $card->image_url }}" onerror="this.onerror=null; this.src='/images/product-placeholder.png';" alt="{{ $card->title_line1 }}" style="max-width: 65px; max-height: 65px; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));">
                                            </div>
                                        </div>

                                        <!-- Bottom button preview -->
                                        <div class="pt-2">
                                            <span class="btn btn-sm w-100 fw-bold text-white text-nowrap rounded-2 py-1" style="font-size: 11px; background-color: {{ $card->button_color ?: '#ff5400' }}; border: none; pointer-events: none;">
                                                {{ $card->button_text }} &rsaquo;
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Card Info Body -->
                                    <div class="card-body p-2.5 bg-white border-top">
                                        <div class="text-muted small text-truncate mb-1">
                                            <i class="bi bi-link-45deg me-1"></i>URL: <code>{{ $card->button_url }}</code>
                                        </div>
                                        <div class="text-muted small text-truncate">
                                            <i class="bi bi-image me-1"></i>Path: <span class="badge bg-light text-dark border">{{ $card->image ?: 'None' }}</span>
                                        </div>
                                    </div>

                                    <!-- Card Action Footer -->
                                    <div class="card-footer bg-light border-top p-2 d-flex align-items-center justify-content-between">
                                        <form action="{{ route('admin.homepage.promo-cards.toggle', $card->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $card->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" style="font-size: 11px;">
                                                <i class="bi {{ $card->is_active ? 'bi-pause-fill' : 'bi-play-fill' }}"></i> {{ $card->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>

                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPromoCardModal{{ $card->id }}" style="font-size: 11px;">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>

                                            <form action="{{ route('admin.homepage.promo-cards.destroy', $card->id) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this action card?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size: 11px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- EDIT PROMO CARD MODAL -->
                            <div class="modal fade" id="editPromoCardModal{{ $card->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.homepage.promo-cards.update', $card->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Edit Promo Card #{{ $loop->iteration }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label fw-bold small">Title Line 1 *</label>
                                                    <input type="text" name="title_line1" value="{{ $card->title_line1 }}" class="form-control form-control-sm" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-bold small">Title Line 2 (Colored)</label>
                                                    <input type="text" name="title_line2" value="{{ $card->title_line2 }}" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Subtitle</label>
                                                <input type="text" name="subtitle" value="{{ $card->subtitle }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label fw-bold small">Button Text</label>
                                                    <input type="text" name="button_text" value="{{ $card->button_text }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-bold small">Button Color</label>
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <input type="color" name="button_color" value="{{ $card->button_color ?: '#ff5400' }}" class="form-control form-control-color form-control-sm" style="width: 42px; height: 32px;">
                                                        <input type="text" name="button_color_text" value="{{ $card->button_color ?: '#ff5400' }}" class="form-control form-control-sm" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Button URL / Link</label>
                                                <input type="text" name="button_url" value="{{ $card->button_url }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Background Gradient (CSS)</label>
                                                <input type="text" name="bg_gradient" value="{{ $card->bg_gradient }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Upload 3D Picture / Image</label>
                                                <input type="file" name="promo_image" class="form-control form-control-sm" accept="image/*">
                                                <div class="form-text">Leave empty to retain existing picture.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Or Image Path / URL</label>
                                                <input type="text" name="image_url" value="{{ $card->image }}" class="form-control form-control-sm">
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-6">
                                                    <label class="form-label fw-bold small">Sort Order</label>
                                                    <input type="number" name="sort_order" value="{{ $card->sort_order }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-6 d-flex align-items-end">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck{{ $card->id }}" {{ $card->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold small" for="isActiveCheck{{ $card->id }}">
                                                            Active Card
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm text-white" style="background: #0284c7;">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="bi bi-grid-1x2 fs-1 d-block mb-2 text-secondary"></i>
                                No quick action promo cards found. Click "Add Action Card" or run "Sync Default Catalog" above.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- PANE 4: [SCREENSHOT] TRENDING NOW MERCHANDISING          -->
                <!-- ======================================================== -->
                <div class="tab-pane fade" id="trending-now-pane" role="tabpanel">
                    <div class="alert alert-warning border-warning border-opacity-50 rounded-3 p-3 mb-4 d-flex align-items-center gap-2.5" style="background: #fffbeb;">
                        <div class="rounded-circle text-white p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: #ff5400;">
                            <i class="bi bi-fire fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span>[SCREENSHOT BOTTOM SECTION] "TRENDING NOW" Merchandising & View All Control</span>
                                <span class="badge text-white fw-bold" style="background: #ff5400;">Horizontal Scrollable Row</span>
                            </div>
                            <div class="text-secondary small mt-0.5">
                                Configure the section header title, the "View All →" button text and target link, and manage which products appear in the clean minimalist white card carousel.
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Left Column: Section Header & Button Settings -->
                        <div class="col-lg-5">
                            <div class="card border rounded-3 shadow-xs h-100">
                                <div class="card-header bg-white py-3 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="bi bi-sliders me-1.5 text-primary"></i> Section Header & "View All" Button Control
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <form action="{{ route('admin.homepage.trending-settings.update') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-dark">Section Heading Title *</label>
                                            <input type="text" name="trending_now_title" value="{{ $homepageSettings['trending_now_title'] ?? 'TRENDING NOW' }}" class="form-control form-control-sm" placeholder="e.g. TRENDING NOW" required>
                                            <div class="form-text">Bold dark header displayed above the product carousel.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-dark">"View All" Button Text *</label>
                                            <input type="text" name="trending_now_view_all_text" value="{{ $homepageSettings['trending_now_view_all_text'] ?? 'View All →' }}" class="form-control form-control-sm" placeholder="e.g. View All →" required>
                                            <div class="form-text">Button text displayed on the right edge of the section header.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-dark">"View All" Destination URL / Route *</label>
                                            <input type="text" name="trending_now_view_all_url" value="{{ $homepageSettings['trending_now_view_all_url'] ?? '/products?filter=trending' }}" class="form-control form-control-sm" placeholder="e.g. /products?filter=trending" required>
                                            <div class="form-text">Where users land when clicking "View All →". Can be <code>/products?filter=trending</code>, a specific category like <code>/categories/tiles</code>, or any custom URL.</div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="trending_now_active" id="trendingActiveSwitch" {{ ($homepageSettings['trending_now_active'] ?? '1') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold small text-dark" for="trendingActiveSwitch">
                                                    Show "Trending Now" Section on Live Homepage
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Live Preview Box -->
                                        <div class="p-3 rounded-2 border mb-3" style="background: #fafafa;">
                                            <div class="small fw-bold text-muted mb-2 text-uppercase" style="font-size: 10.5px; letter-spacing: 0.5px;">Live Header Preview</div>
                                            <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded border">
                                                <div class="fw-black text-dark text-uppercase" style="font-weight: 800; font-size: 16px; letter-spacing: -0.3px;">
                                                    {{ $homepageSettings['trending_now_title'] ?? 'TRENDING NOW' }}
                                                </div>
                                                <span class="btn btn-sm px-3 py-1 rounded-pill fw-bold text-white" style="font-size: 12px; background: #ff5400;">
                                                    {{ $homepageSettings['trending_now_view_all_text'] ?? 'View All →' }}
                                                </span>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-sm text-white w-100 fw-bold py-2 shadow-sm" style="background: #ff5400;">
                                            <i class="bi bi-check2-circle me-1"></i> Save Trending Now Settings
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Curated Trending Now Products -->
                        <div class="col-lg-7">
                            <div class="card border rounded-3 shadow-xs h-100">
                                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="bi bi-bag-heart me-1.5" style="color: #ff5400;"></i> Curated Products in "Trending Now"
                                        </h6>
                                        <div class="text-muted small">Products currently appearing in the horizontal carousel.</div>
                                    </div>
                                    <span class="badge text-white px-2.5 py-1 rounded-pill fw-bold" style="background: #ff5400;">
                                        {{ $stats['is_trending_now'] }} Active
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    @php
                                        $trendingNowCurated = \App\Models\Product::where('is_trending_now', true)
                                            ->with(['primaryImage', 'category'])
                                            ->take(20)
                                            ->get();
                                    @endphp

                                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                                        <table class="table table-hover align-middle mb-0" style="font-size: 12.5px;">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th style="width: 50px;">Img</th>
                                                    <th>Product Name</th>
                                                    <th>Price / Unit</th>
                                                    <th>Category</th>
                                                    <th class="text-end" style="width: 100px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($trendingNowCurated as $tp)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ $tp->primary_image_url }}" onerror="this.onerror=null; this.src='/images/product-placeholder.png';" alt="{{ $tp->name }}" class="rounded border" style="width: 38px; height: 38px; object-fit: contain;">
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold text-dark">{{ $tp->name }}</div>
                                                            <div class="text-muted" style="font-size: 10.5px;">SKU: {{ $tp->sku }}</div>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold text-dark">₹{{ number_format($tp->selling_price) }}</div>
                                                            <div class="text-muted" style="font-size: 10.5px;">/ {{ $tp->unit ?: 'Piece' }}</div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-dark border">{{ $tp->category->name ?? 'General' }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <button type="button" onclick="ajaxToggleSection({{ $tp->id }}, 'is_trending_now', this); setTimeout(() => location.reload(), 600);" class="btn btn-sm btn-outline-danger px-2 py-0.5" style="font-size: 11px;" title="Remove from Trending Now">
                                                                <i class="bi bi-x-circle me-0.5"></i> Remove
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4 text-muted">
                                                            <i class="bi bi-inbox fs-2 d-block mb-1 text-secondary"></i>
                                                            No products currently marked for Trending Now.<br>
                                                            Switch to <strong>Tab 1 (Catalog Section Placements)</strong> and toggle products in the orange <code>SCREENSHOT</code> column.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-light border-top p-2.5 text-center">
                                    <button type="button" onclick="document.getElementById('products-tab').click();" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">
                                        <i class="bi bi-search me-1"></i> Add More Products from Tab 1 Catalog Table &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- PANE 5: TRENDING CATEGORY FILTER PILLS                   -->
                <!-- ======================================================== -->
                <div class="tab-pane fade" id="categories-pane" role="tabpanel">
                    <!-- Prominent Admin Marking for PRODUCT SECTION 1 CATEGORY PILLS -->
                    <div class="alert alert-success border-success border-opacity-50 rounded-3 p-3 mb-4 d-flex align-items-center gap-2.5" style="background: #f0fdf4;">
                        <div class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="bi bi-tags-fill fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span>[PRODUCT SECTION 1] Trending Category Filter Bar</span>
                                <span class="badge bg-success text-white fw-bold">Section 1 Header Tabs</span>
                            </div>
                            <div class="text-secondary small mt-0.5">
                                Toggle which root product categories appear as the clickable filter tabs directly next to <strong>"Trending Products"</strong> on the landing page. Clicking a tab filters the carousel row instantly without page reload.
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach($categories as $c)
                            <div class="col-md-4 col-lg-3">
                                <div class="card border rounded-3 p-3 bg-white shadow-xs d-flex flex-row align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px;">
                                            <i class="bi {{ $c->icon ?: 'bi-box' }} fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 13px;">{{ $c->name }}</div>
                                            <div class="text-muted small">{{ $c->products_count ?? $c->products()->count() }} Products</div>
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.homepage.categories.toggle', $c->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm rounded-pill px-2.5 py-1 {{ $c->show_in_home ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="font-size: 11px;">
                                            {{ $c->show_in_home ? 'Active' : 'Disabled' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- PANE 6: [IMAGE 3] NEW ARRIVALS & WHY CHOOSE GARUDO      -->
                <!-- ======================================================== -->
                <div class="tab-pane fade" id="new-arrivals-pane" role="tabpanel">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge" style="background: #ea580c; color: #fff; font-size: 11px;">IMAGE 3 LAYOUT</span>
                                <h5 class="fw-bold text-dark mb-0">New Arrivals & Why Choose Garudo Section</h5>
                            </div>
                            <p class="text-muted small mb-0">
                                Directly controls Section 12 on the homepage matching <code>Image 3</code>: 6 vertical product cards in a single row, followed by the Why Choose Garudo 6-point card and dark navy live statistics block.
                            </p>
                        </div>
                        <a href="{{ route('home') }}#new-arrivals-section" target="_blank" class="btn btn-outline-dark btn-sm text-nowrap">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Preview On Storefront
                        </a>
                    </div>

                    <form action="{{ route('admin.homepage.new-arrivals.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- SUB-SECTION 1: THE 6 VERTICAL PRODUCT CARDS -->
                        <div class="card border rounded-3 mb-4 shadow-none">
                            <div class="card-header bg-light py-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-grid-3x2-gap-fill text-warning me-1"></i> 6 Product Cards (Single Horizontal Row)</h6>
                                    <span class="text-muted" style="font-size: 11px;">Aspect ratio ~ 3:4 rounded image, title, price badge, and direct storefront link.</span>
                                </div>
                                <span class="badge bg-dark">Cards 1 — 6</span>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    @php
                                        $cardDefaults = [
                                            1 => ['title' => 'Smart Lock Door Handle', 'price' => '2,450', 'unit' => '', 'url' => '/products?search=smart+lock', 'img' => asset('images/new_arrivals/smart_lock.jpg')],
                                            2 => ['title' => 'WPC Wall Panel', 'price' => '320', 'unit' => 'Sq.Ft.', 'url' => '/products?search=wpc+wall+panel', 'img' => asset('images/new_arrivals/wpc_panel.jpg')],
                                            3 => ['title' => 'Designer Basin', 'price' => '3,980', 'unit' => '', 'url' => '/products?search=designer+basin', 'img' => asset('images/new_arrivals/designer_basin.jpg')],
                                            4 => ['title' => 'False Ceiling Grid', 'price' => '65', 'unit' => 'Sq.Ft.', 'url' => '/products?search=false+ceiling', 'img' => asset('images/new_arrivals/false_ceiling.jpg')],
                                            5 => ['title' => 'Epoxy Flooring', 'price' => '120', 'unit' => 'Sq.Ft.', 'url' => '/products?search=epoxy+flooring', 'img' => asset('images/new_arrivals/epoxy_flooring.jpg')],
                                            6 => ['title' => 'Exterior Cladding Tile', 'price' => '65', 'unit' => 'Sq.Ft.', 'url' => '/products?search=cladding+tile', 'img' => asset('images/new_arrivals/stone_cladding.jpg')],
                                        ];
                                    @endphp

                                    @for($i = 1; $i <= 6; $i++)
                                        @php
                                            $cTitle = $newArrivalsSettings["card{$i}_title"] ?? $cardDefaults[$i]['title'];
                                            $cPrice = $newArrivalsSettings["card{$i}_price"] ?? $cardDefaults[$i]['price'];
                                            $cUnit  = $newArrivalsSettings["card{$i}_unit"] ?? $cardDefaults[$i]['unit'];
                                            $cUrl   = $newArrivalsSettings["card{$i}_url"] ?? $cardDefaults[$i]['url'];
                                            $cImg   = $newArrivalsSettings["card{$i}_image"] ?? $cardDefaults[$i]['img'];
                                        @endphp
                                        <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                                            <div class="border rounded-3 p-2.5 h-100 bg-light-subtle d-flex flex-column justify-content-between">
                                                <div>
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <span class="badge bg-secondary fw-bold" style="font-size: 10px;">Card {{ $i }}</span>
                                                    </div>

                                                    <!-- Image Thumbnail Preview -->
                                                    <div class="rounded-2 overflow-hidden border mb-2 bg-white text-center position-relative" style="height: 130px;">
                                                        <img src="{{ $cImg }}" alt="{{ $cTitle }}" class="w-100 h-100 object-fit-cover" onerror="this.onerror=null; this.src='/images/product-placeholder.png';">
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold mb-1" style="font-size: 11px;">Upload New Image</label>
                                                        <input type="file" name="card{{ $i }}_image_file" class="form-control form-control-sm" accept="image/*" style="font-size: 11px;">
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold mb-1" style="font-size: 11px;">Or Image Path / URL</label>
                                                        <input type="text" name="card{{ $i }}_image" value="{{ $cImg }}" class="form-control form-control-sm" style="font-size: 11px;">
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label small fw-bold mb-1" style="font-size: 11px;">Product Title *</label>
                                                        <input type="text" name="card{{ $i }}_title" value="{{ $cTitle }}" class="form-control form-control-sm fw-bold" style="font-size: 11.5px;" required>
                                                    </div>

                                                    <div class="row g-1 mb-2">
                                                        <div class="col-7">
                                                            <label class="form-label small fw-bold mb-1" style="font-size: 11px;">Price (₹)</label>
                                                            <input type="text" name="card{{ $i }}_price" value="{{ $cPrice }}" class="form-control form-control-sm fw-bold text-success" style="font-size: 11.5px;">
                                                        </div>
                                                        <div class="col-5">
                                                            <label class="form-label small fw-bold mb-1" style="font-size: 11px;">Unit</label>
                                                            <input type="text" name="card{{ $i }}_unit" value="{{ $cUnit }}" class="form-control form-control-sm" placeholder="Sq.Ft." style="font-size: 11px;">
                                                        </div>
                                                    </div>

                                                    <div class="mb-1">
                                                        <label class="form-label small fw-bold mb-1" style="font-size: 11px;">Link URL</label>
                                                        <input type="text" name="card{{ $i }}_url" value="{{ $cUrl }}" class="form-control form-control-sm text-muted" style="font-size: 11px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- SUB-SECTION 2: WHY CHOOSE GARUDO & LIVE STATS CARD -->
                        <div class="row g-3 mb-4">
                            <!-- Left: Why Choose Garudo Points -->
                            <div class="col-lg-7">
                                <div class="card border rounded-3 h-100 shadow-none">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-shield-check text-warning me-1"></i> Why Choose Garudo? (Feature Grid)</h6>
                                            <span class="text-muted" style="font-size: 11px;">Displays in a clean 2x3 grid with circular orange shield badges.</span>
                                        </div>
                                        <span class="badge bg-warning text-dark">6 Key Points</span>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Card Heading Title</label>
                                            <input type="text" name="why_choose_title" value="{{ $newArrivalsSettings['why_choose_title'] ?? 'Why Choose Garudo?' }}" class="form-control form-control-sm fw-bold">
                                        </div>
                                        <div>
                                            <label class="form-label small fw-bold">Features / Highlights (1 per line)</label>
                                            <textarea name="why_choose_features" rows="7" class="form-control form-control-sm" style="font-family: monospace; font-size: 12px;">{{ $newArrivalsSettings['why_choose_features'] }}</textarea>
                                            <span class="text-muted" style="font-size: 10.5px;">Each line creates a verified feature item with an orange circular shield icon (e.g. <code>10,000+ Verified Products</code>).</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Live Statistics Navy Box -->
                            <div class="col-lg-5">
                                <div class="card border rounded-3 h-100 shadow-none text-white" style="background: #0f1c3f;">
                                    <div class="card-header border-bottom border-white border-opacity-10 py-3 d-flex align-items-center justify-content-between bg-transparent">
                                        <div>
                                            <h6 class="fw-bold text-white mb-0"><i class="bi bi-graph-up-arrow text-warning me-1"></i> Live Statistics Block</h6>
                                            <span class="text-white-50" style="font-size: 11px;">Dark navy card on the far right with 3 vertically stacked metrics.</span>
                                        </div>
                                        <span class="badge bg-warning text-dark">Dark Navy</span>
                                    </div>
                                    <div class="card-body p-3">
                                        <!-- Stat 1 -->
                                        <div class="row g-2 mb-3 pb-3 border-bottom border-white border-opacity-10">
                                            <div class="col-5">
                                                <label class="form-label small text-white-50 mb-1" style="font-size: 11px;">Metric 1 Number</label>
                                                <input type="text" name="stat1_number" value="{{ $newArrivalsSettings['stat1_number'] ?? '25K+' }}" class="form-control form-control-sm fw-bold text-warning bg-dark text-white border-secondary">
                                            </div>
                                            <div class="col-7">
                                                <label class="form-label small text-white-50 mb-1" style="font-size: 11px;">Metric 1 Label</label>
                                                <input type="text" name="stat1_label" value="{{ $newArrivalsSettings['stat1_label'] ?? 'Happy Customers' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                        </div>

                                        <!-- Stat 2 -->
                                        <div class="row g-2 mb-3 pb-3 border-bottom border-white border-opacity-10">
                                            <div class="col-5">
                                                <label class="form-label small text-white-50 mb-1" style="font-size: 11px;">Metric 2 Number</label>
                                                <input type="text" name="stat2_number" value="{{ $newArrivalsSettings['stat2_number'] ?? '10K+' }}" class="form-control form-control-sm fw-bold text-warning bg-dark text-white border-secondary">
                                            </div>
                                            <div class="col-7">
                                                <label class="form-label small text-white-50 mb-1" style="font-size: 11px;">Metric 2 Label</label>
                                                <input type="text" name="stat2_label" value="{{ $newArrivalsSettings['stat2_label'] ?? 'Projects Served' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                        </div>

                                        <!-- Stat 3 -->
                                        <div class="row g-2">
                                            <div class="col-5">
                                                <label class="form-label small text-white-50 mb-1" style="font-size: 11px;">Metric 3 Number</label>
                                                <input type="text" name="stat3_number" value="{{ $newArrivalsSettings['stat3_number'] ?? '2500+' }}" class="form-control form-control-sm fw-bold text-warning bg-dark text-white border-secondary">
                                            </div>
                                            <div class="col-7">
                                                <label class="form-label small text-white-50 mb-1" style="font-size: 11px;">Metric 3 Label</label>
                                                <input type="text" name="stat3_label" value="{{ $newArrivalsSettings['stat3_label'] ?? 'Vendors' }}" class="form-control form-control-sm bg-dark text-white border-secondary">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 border">
                            <div class="small text-muted">
                                <i class="bi bi-info-circle text-primary me-1"></i> Changes save instantly to database and display live on storefront.
                            </div>
                            <button type="submit" class="btn text-white px-4 py-2 fw-bold" style="background: #ea580c;">
                                <i class="bi bi-check2-circle me-1"></i> Save Image 3 New Arrivals & Why Choose Settings
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- CREATE BANNER MODAL (ROW 1) -->
<div class="modal fade" id="createBannerModalRow1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.homepage.banners.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <input type="hidden" name="section_row" value="1">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Banner Ad (Row 1 - Middle Placement)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Banner Title / Heading *</label>
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. MEGA CONSTRUCTION SALE" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subtitle / Discount Tag</label>
                    <input type="text" name="subtitle" class="form-control form-control-sm" placeholder="e.g. UP TO 40% or Elegant Tiles For Every Space">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Button Text</label>
                    <input type="text" name="button_text" value="Explore Now" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Target Link / URL</label>
                    <input type="text" name="link" value="/products" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Background Color</label>
                    <input type="text" name="background_color" value="#0b0f19" class="form-control form-control-sm" placeholder="#0b0f19">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Upload Banner Image</label>
                    <input type="file" name="banner_image" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Or Image URL / Existing Asset Path</label>
                    <input type="text" name="image_url" class="form-control form-control-sm" placeholder="e.g. images/banners/mega_construction_sale.jpg">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-primary">Create Row 1 Banner</button>
            </div>
        </form>
    </div>
</div>

<!-- CREATE BANNER MODAL (ROW 2) -->
<div class="modal fade" id="createBannerModalRow2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.homepage.banners.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <input type="hidden" name="section_row" value="2">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Banner Ad (Row 2 - Lower Placement)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Banner Title / Heading *</label>
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. MEGA CONSTRUCTION SALE" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subtitle / Discount Tag</label>
                    <input type="text" name="subtitle" class="form-control form-control-sm" placeholder="e.g. UP TO 40% or Complete Plumbing & Drainage">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Button Text</label>
                    <input type="text" name="button_text" value="Explore Now" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Target Link / URL</label>
                    <input type="text" name="link" value="/products" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Background Color</label>
                    <input type="text" name="background_color" value="#0b0f19" class="form-control form-control-sm" placeholder="#0b0f19">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Upload Banner Image</label>
                    <input type="file" name="banner_image" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Or Image URL / Existing Asset Path</label>
                    <input type="text" name="image_url" class="form-control form-control-sm" placeholder="e.g. images/banners/mega_construction_sale.jpg">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-primary">Create Row 2 Banner</button>
            </div>
        </form>
    </div>
</div>

<!-- GENERAL CREATE BANNER MODAL (FALLBACK) -->
<div class="modal fade" id="createBannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.homepage.banners.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create Homepage Promotional Ad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Placement Row *</label>
                    <select name="section_row" class="form-select form-select-sm">
                        <option value="1">Row 1 (Upper Placement - Between Sec 2 & Sec 3)</option>
                        <option value="2">Row 2 (Lower Placement - Above Highlights)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Banner Title / Heading *</label>
                    <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. MEGA CONSTRUCTION SALE" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subtitle / Discount Tag</label>
                    <input type="text" name="subtitle" class="form-control form-control-sm" placeholder="e.g. UP TO 40% or Exclusive Bulk Discounts">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Button Text</label>
                    <input type="text" name="button_text" value="Explore Now" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Target Link / URL</label>
                    <input type="text" name="link" value="/products" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Background Color</label>
                    <input type="text" name="background_color" value="#0b0f19" class="form-control form-control-sm" placeholder="#0b0f19">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Upload Banner Image</label>
                    <input type="file" name="banner_image" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Or Image URL / Existing Asset Path</label>
                    <input type="text" name="image_url" class="form-control form-control-sm" placeholder="e.g. images/banners/mega_construction_sale.jpg">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm btn-primary">Create Banner</button>
            </div>
        </form>
    </div>
</div>

<!-- CREATE PROMO CARD MODAL -->
<div class="modal fade" id="createPromoCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.homepage.promo-cards.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create Quick Action Promo Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Title Line 1 *</label>
                        <input type="text" name="title_line1" class="form-control form-control-sm" placeholder="e.g. BULK ORDER" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Title Line 2 (Colored)</label>
                        <input type="text" name="title_line2" class="form-control form-control-sm" placeholder="e.g. SAVE MORE">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Subtitle</label>
                    <input type="text" name="subtitle" class="form-control form-control-sm" placeholder="e.g. Best Prices on Large Quantities">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Button Text</label>
                        <input type="text" name="button_text" value="Request Quote" class="form-control form-control-sm">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small">Button Color</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="color" name="button_color" value="#ff5400" class="form-control form-control-color form-control-sm" style="width: 42px; height: 32px;">
                            <input type="text" name="button_color_text" value="#ff5400" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Button URL / Link</label>
                    <input type="text" name="button_url" value="/quotations" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Background Gradient (CSS)</label>
                    <input type="text" name="bg_gradient" value="linear-gradient(135deg, #091b3a 0%, #061226 100%)" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Upload 3D Picture / Image</label>
                    <input type="file" name="promo_image" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Or Existing Image Path / URL</label>
                    <input type="text" name="image_url" class="form-control form-control-sm" placeholder="e.g. images/quick_promos/bulk_order_pallet.jpg">
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label fw-bold small">Sort Order</label>
                        <input type="number" name="sort_order" value="1" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="newCardActive" checked>
                            <label class="form-check-label fw-bold small" for="newCardActive">
                                Active Card
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-sm text-white" style="background: #0284c7;">Create Action Card</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Real-time AJAX toggle for product section placements
    function ajaxToggleSection(productId, flag, btnElement) {
        btnElement.disabled = true;
        const originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

        fetch('{{ route("admin.homepage.products.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                flag: flag
            })
        })
        .then(response => response.json())
        .then(data => {
            btnElement.disabled = false;
            if (data.success) {
                // Update button style dynamically
                if (data.active) {
                    if (flag === 'is_trending_now') {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn text-white fw-bold';
                        btnElement.style.backgroundColor = '#ff5400';
                        btnElement.style.borderColor = '#ff5400';
                        btnElement.innerHTML = '<i class="bi bi-fire"></i> Active';
                    } else if (flag === 'is_new_arrival') {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn text-white fw-bold';
                        btnElement.style.backgroundColor = '#ea580c';
                        btnElement.style.borderColor = '#ea580c';
                        btnElement.innerHTML = '<i class="bi bi-stars"></i> Active';
                    } else if (flag === 'sec1_trending') {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn btn-warning text-dark fw-bold';
                        btnElement.innerHTML = '<i class="bi bi-fire"></i> Active';
                    } else if (flag === 'sec2_best_seller') {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn btn-success text-white fw-bold';
                        btnElement.innerHTML = '<i class="bi bi-check2-circle"></i> Active';
                    } else if (flag === 'sec3_trending') {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn btn-primary text-white fw-bold';
                        btnElement.innerHTML = '<i class="bi bi-fire"></i> Active';
                    } else if (flag === 'sec4_best_seller') {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn btn-danger text-white fw-bold';
                        btnElement.innerHTML = '<i class="bi bi-bag-check-fill"></i> Active';
                    } else if (flag === 'is_trending') {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn btn-warning text-dark fw-bold';
                        btnElement.innerHTML = '<i class="bi bi-fire"></i> Active';
                    } else {
                        btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn btn-success text-white fw-bold';
                        btnElement.innerHTML = '<i class="bi bi-check2"></i> Active';
                    }
                } else {
                    btnElement.style.backgroundColor = '';
                    btnElement.style.borderColor = '';
                    btnElement.className = 'btn btn-sm rounded-pill px-2.5 py-1 section-toggle-btn btn-outline-secondary';
                    btnElement.innerHTML = '<i class="bi bi-dash"></i> Off';
                }

                // Show subtle toast notification
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <div class="toast show align-items-center text-white bg-dark border-0 shadow-lg" role="alert">
                        <div class="d-flex">
                            <div class="toast-body small">
                                <i class="bi bi-check-circle-fill text-success me-1"></i> ${data.message}
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2500);
            } else {
                btnElement.innerHTML = originalHtml;
                alert('Action failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            btnElement.disabled = false;
            btnElement.innerHTML = originalHtml;
            console.error(err);
            alert('Request failed. Please try again.');
        });
    }
</script>
@endpush
@endsection
