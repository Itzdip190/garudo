@extends('layouts.frontend')

@section('title', $product->name . ' - ' . config('marketplace.name'))

@section('content')
<div class="container py-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3 small">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Image Gallery (Image 2 UX Reference) -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-3">
                <div class="text-center mb-3 border rounded p-2 bg-white">
                    <img id="mainProductImage" src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->file_path) : 'https://placehold.co/500x500?text='.$product->name }}" class="img-fluid" style="max-height: 400px; object-fit: contain;" alt="{{ $product->name }}">
                </div>
                <!-- Thumbnails -->
                <div class="d-flex gap-2 overflow-auto py-1">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/'.$img->file_path) }}" class="img-thumbnail thumb-select cursor-pointer" style="width: 70px; height: 70px; object-fit: cover;" onclick="$('#mainProductImage').attr('src', this.src);">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Main Info & Vendor Box -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-navy mb-2">{{ $product->category->name ?? 'Industrial' }}</span>
                        <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>
                        <p class="small text-muted mb-2">SKU: <span class="fw-medium text-dark">{{ $product->sku }}</span> | HSN: <span class="fw-medium text-dark">{{ $product->hsn_code ?? 'N/A' }}</span></p>
                    </div>
                </div>

                <!-- Rating -->
                <div class="d-flex align-items-center mb-3">
                    <div class="text-warning me-2 fs-6">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <span class="fw-bold fs-6 me-2">{{ $product->rating ?? '4.5' }}</span>
                    <span class="small text-muted">({{ $product->rating_count ?? 12 }} Reviews)</span>
                </div>

                <!-- Price Box -->
                <div class="bg-light p-3 rounded mb-4 border">
                    <div class="d-flex align-items-baseline gap-3 mb-1">
                        <span class="fs-2 fw-bold text-dark">₹{{ number_format($product->selling_price, 2) }}</span>
                        <span class="text-muted text-decoration-line-through fs-5">₹{{ number_format($product->mrp, 2) }}</span>
                        <span class="badge bg-success">Save {{ round((($product->mrp - $product->selling_price) / $product->mrp) * 100) }}%</span>
                    </div>
                    <span class="small text-muted">+ GST @ {{ $product->gst_rate }}% extra</span>
                </div>

                @if(session('sample_success'))
                    <div class="alert alert-success alert-dismissible fade show p-3 rounded-3 shadow-sm border-0 mb-3" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-success fs-4"></i>
                            <div>
                                <strong class="d-block">Sample Request Confirmed!</strong>
                                <span class="small">{{ session('sample_success') }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('quotation_success'))
                    <div class="alert alert-success alert-dismissible fade show p-3 rounded-3 shadow-sm border-0 mb-3" role="alert" style="background: #ECFDF5; border-left: 4px solid #10B981 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-check-fill text-success fs-3"></i>
                            <div>
                                <strong class="d-block text-dark">Quotation Request (RFQ) Received!</strong>
                                <span class="small text-muted">{{ session('quotation_success') }}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Vendor Highlights Box -->
                <div class="border rounded p-3 mb-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-primary fs-4"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $product->vendor->company_name ?? 'Garudo Verified Supplier' }}</h6>
                                <span class="small text-muted">{{ $product->vendor->business_type ?? 'Supplier' }}</span>
                            </div>
                        </div>
                        @php
                            $isOem = $product->brand && $product->brand->isOwnedBy($product->vendor);
                            $dealerAuth = ($product->brand && !$isOem) ? $product->vendor->brandAuthorizations()->where('brand_id', $product->brand->id)->where('garudo_approval_status', 'verified')->first() : null;
                        @endphp

                        @if($isOem)
                            <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                <i class="bi bi-patch-check-fill me-1"></i> Verified OEM
                            </span>
                        @elseif($dealerAuth)
                            <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1">
                                <i class="bi bi-shield-check me-1"></i> Authorized Dealer
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success"><i class="bi bi-check-circle me-1"></i> Verified</span>
                        @endif
                    </div>

                    @if($dealerAuth && $dealerAuth->certificate_number)
                        <div class="p-2 rounded bg-light border mb-2 d-flex justify-content-between align-items-center small">
                            <span class="text-secondary"><i class="bi bi-award-fill text-warning me-1"></i>Garudo Authorized Dealer of <strong>{{ $product->brand->name }}</strong></span>
                            <a href="{{ route('certificate.dealer.verify', $dealerAuth->certificate_number) }}" target="_blank" class="text-primary text-decoration-none fw-semibold">
                                View Certificate <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    @endif

                    <div class="row text-center mt-3 pt-2 border-top g-2">
                        <div class="col-6"><button class="btn btn-outline-dark btn-sm w-100"><i class="bi bi-telephone me-1"></i> Call Vendor</button></div>
                        <div class="col-6"><button class="btn btn-success btn-sm w-100"><i class="bi bi-whatsapp me-1"></i> WhatsApp</button></div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- B2B Ask for Quotation (RFQ) Callout Banner (If RFQ Allowed) -->
                <!-- ========================================================= -->
                @if($product->isQuotationEnabled())
                    <div class="garudo-callout-rfq mb-3">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                                    <span class="badge text-white px-2.5 py-1 fw-bold" style="background: linear-gradient(135deg, #0B4D3C 0%, #166534 100%); font-size: 0.72rem; letter-spacing: 0.5px;">
                                        <i class="bi bi-calculator-fill me-1 text-warning"></i>B2B DIRECT RFQ
                                    </span>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 fw-semibold" style="font-size: 0.72rem;">
                                        <i class="bi bi-shield-check me-1"></i>Direct Supplier Quotes
                                    </span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">Looking for Bulk / Wholesale Project Pricing?</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem; line-height: 1.45;">
                                    {{ $product->quotation_notes ?? 'Request custom factory-direct pricing, project volume discounts, customized cut lengths, and tailored freight terms.' }}
                                    @if($product->quotation_min_qty)
                                        <span class="d-block text-success fw-semibold mt-1"><i class="bi bi-check2-circle me-1"></i>Minimum RFQ Quantity: <strong>{{ number_format($product->quotation_min_qty) }} {{ $product->unit ?? 'Units' }}</strong></span>
                                    @endif
                                </p>
                            </div>
                            <div class="align-self-sm-center">
                                <button type="button" class="btn btn-garudo-emerald px-3.5 py-2.5 fw-bold text-nowrap shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#requestQuotationModal">
                                    <i class="bi bi-file-earmark-text-fill text-warning"></i> Ask for Quotation
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ========================================================= -->
                <!-- Product Material Sample Banner (If Sample is Available)   -->
                <!-- ========================================================= -->
                @if($product->has_sample)
                    <div class="garudo-callout-sample mb-3">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1.5">
                                    <span class="badge bg-success text-white px-2.5 py-1 font-monospace fw-bold" style="font-size: 0.72rem;">
                                        <i class="bi bi-box2-heart-fill me-1"></i>TEST & TRIAL
                                    </span>
                                    {!! $product->getSampleBadge() !!}
                                </div>
                                <h6 class="fw-bold text-dark mb-1 fs-6">Request Material Sample / Test Kit</h6>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem; line-height: 1.45;">
                                    {{ $product->sample_unit_description ? 'Specimen: ' . $product->sample_unit_description . '.' : 'Test specimen available for quality testing & lab approval.' }}
                                    @if($product->sample_type === 'free')
                                        <strong class="text-success">100% Free Sample (₹0)</strong>
                                    @elseif($product->sample_type === 'paid')
                                        <strong class="text-primary">Sample Unit: ₹{{ number_format($product->sample_price, 2) }}</strong> (+ ₹{{ number_format($product->sample_shipping_fee, 2) }} Courier)
                                    @elseif($product->sample_type === 'partial_paid')
                                        <strong class="text-dark">Sample Material ₹0 + ₹{{ number_format($product->sample_shipping_fee, 2) }} Courier Handling</strong>
                                    @endif
                                </p>
                            </div>
                            <div class="align-self-sm-center">
                                <button type="button" class="btn btn-success btn-sm px-3.5 py-2.5 fw-bold text-nowrap shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#orderSampleModal" style="background: #0B4D3C; border: none; border-radius: 10px;">
                                    <i class="bi bi-box-seam text-warning"></i> Order Sample
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if(($launchSettings['launch_mode_active'] ?? '1') == '1')
                <!-- Pre-Launch Notice for Product Purchasing -->
                <div class="p-3 mb-3 rounded-3 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.12) 0%, rgba(217, 119, 6, 0.06) 100%); border: 1.5px solid rgba(245, 158, 11, 0.35);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.2); color: #d97706;">
                        <i class="bi bi-rocket-takeoff-fill fs-5 animate-rocket"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 13.5px;">
                            <span>Official Launching on {{ $launchSettings['launch_date'] ?? '20th October' }}!</span>
                            <span class="badge bg-warning text-dark px-2 py-0.5 rounded-pill" style="font-size: 10px;">PRE-LAUNCH</span>
                        </div>
                        <div class="small text-muted" style="font-size: 12px;">
                            Direct purchasing will be live on launch day. Add this item to your Wishlist for instant 1-click ordering!
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quantity and Add to Cart Form -->
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="row align-items-center g-2 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label small fw-semibold text-muted mb-1">Order Quantity:</label>
                            <div class="input-group">
                                <input type="number" name="quantity" min="{{ $product->min_order_qty ?? 1 }}" value="{{ $product->min_order_qty ?? 1 }}" class="form-control text-center font-monospace fw-bold" required>
                                <span class="input-group-text bg-light small">{{ $product->unit ?? 'Units' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-8 pt-4">
                            <span class="small text-muted">
                                <i class="bi bi-info-circle me-1"></i> MOQ: <strong>{{ $product->min_order_qty ?? 1 }} {{ $product->unit ?? 'Units' }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-yellow btn-lg w-100 fw-bold py-2.5 shadow-sm">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                        </div>
                        <div class="col-md-5">
                            <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold py-2.5 shadow-sm" onclick="this.form.action='{{ route('cart.add') }}'">
                                <i class="bi bi-lightning-charge-fill me-1"></i> Buy Now
                            </button>
                        </div>
                        @php
                            $isProductWishlisted = \App\Services\WishlistService::has($product->id);
                        @endphp
                        <div class="col-md-2">
                            <button type="button" 
                                    class="btn btn-lg w-100 btn-wishlist-toggle {{ $isProductWishlisted ? 'active btn-danger text-white' : 'btn-outline-danger' }}" 
                                    data-id="{{ $product->id }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); toggleWishlistCard(this, {{ $product->id }});" 
                                    title="{{ $isProductWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                                    style="border-radius: 10px; height: 100%; min-height: 48px; display: flex; align-items: center; justify-content: center; transition: all 0.25s ease;">
                                <i class="bi {{ $isProductWishlisted ? 'bi-heart-fill' : 'bi-heart' }} fs-5"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================================================== -->
    <!-- GLOBAL SUBTLE MODAL ANIMATION & UI STYLES                                  -->
    <!-- ========================================================================== -->
    <!-- GLOBAL UNIQUE HORIZON 3D PERSPECTIVE MODAL ANIMATION & UI STYLES           -->
    <!-- ========================================================================== -->
    <style>
        /* 1. Backdrop: Smooth, zero-flicker subtle obsidian tint */
        .modal-backdrop {
            background-color: #04090F !important;
        }
        .modal-backdrop.fade {
            opacity: 0 !important;
            transition: opacity 0.20s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .modal-backdrop.show {
            opacity: 0.76 !important;
            backdrop-filter: blur(8px) saturate(140%) !important;
            -webkit-backdrop-filter: blur(8px) saturate(140%) !important;
        }

        /* 2. Reset Bootstrap dialog transitions and add 3D perspective viewport */
        #requestQuotationModal.modal.fade .modal-dialog,
        #orderSampleModal.modal.fade .modal-dialog {
            transition: none !important;
            transform: none !important;
            margin: 1.75rem auto;
            perspective: 1200px;
        }
        #requestQuotationModal.modal.fade .modal-dialog {
            max-width: 860px;
        }
        #orderSampleModal.modal.fade .modal-dialog {
            max-width: 780px;
        }

        /* 3. Unique Horizon 3D Perspective Dock Card (GPU Hardware Accelerated) */
        .garudo-sheet-card {
            background: #FFFFFF;
            border-radius: 22px;
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 30px 70px -15px rgba(2, 44, 34, 0.42), 0 0 0 1px rgba(16, 185, 129, 0.25);
            overflow: hidden;
            position: relative;
            opacity: 0;
            transform: perspective(1200px) rotateX(10deg) translateY(22px) scale(0.96);
            transform-origin: 50% 0%;
            will-change: transform, opacity;
            backface-visibility: hidden;
            -webkit-font-smoothing: subpixel-antialiased;
            transition: transform 0.24s cubic-bezier(0.16, 1, 0.3, 1), 
                        opacity 0.20s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* When modal is visible */
        .modal.show .garudo-sheet-card {
            opacity: 1;
            transform: perspective(1200px) rotateX(0deg) translateY(0) scale(1);
        }

        /* 4. Luxury Dark Horizon Header with Laser Shimmer */
        .garudo-modal-head-luxury {
            background: linear-gradient(135deg, #03140F 0%, #062B1F 50%, #081726 100%);
            padding: 1.25rem 1.75rem;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(16, 185, 129, 0.25);
        }
        .garudo-modal-head-luxury::before {
            content: '';
            position: absolute;
            top: -60%;
            left: -30%;
            width: 160%;
            height: 220%;
            background: radial-gradient(circle at 20% 30%, rgba(16, 185, 129, 0.28) 0%, transparent 45%),
                        radial-gradient(circle at 80% 70%, rgba(245, 158, 11, 0.16) 0%, transparent 40%);
            pointer-events: none;
        }

        /* Moving Laser Horizon Light Beam */
        .garudo-header-laser {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, rgba(16, 185, 129, 0.8) 30%, #FBBF24 50%, rgba(16, 185, 129, 0.8) 70%, transparent 100%);
            background-size: 200% 100%;
            animation: garudoLaserScan 3.2s linear infinite;
            pointer-events: none;
        }
        @keyframes garudoLaserScan {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .garudo-head-icon {
            width: 46px;
            height: 46px;
            border-radius: 13px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FBBF24;
            font-size: 1.35rem;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(0,0,0,0.3);
            animation: iconFloat 4s ease-in-out infinite;
        }
        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        .garudo-btn-close-light {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
        }
        .garudo-btn-close-light:hover {
            background: rgba(255, 255, 255, 0.28);
            transform: rotate(90deg) scale(1.08);
            color: #FFFFFF;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.3);
        }

        /* 5. Stepper Bar */
        .garudo-stepper {
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
            padding: 0.7rem 1.65rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .garudo-step-pill {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.45rem 0.75rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            user-select: none;
        }
        .garudo-step-pill:hover {
            background: rgba(16, 185, 129, 0.08);
            transform: translateY(-1px);
        }
        .garudo-step-pill.active {
            background: #FFFFFF;
            border: 1px solid rgba(16, 185, 129, 0.35);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            transform: translateY(-1px);
        }
        .garudo-step-pill .step-circle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #E2E8F0;
            color: #64748B;
            font-size: 0.75rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .garudo-step-pill.active .step-circle {
            background: linear-gradient(135deg, #0B4D3C 0%, #10B981 100%);
            color: #FFFFFF;
            box-shadow: 0 3px 8px rgba(11, 77, 60, 0.35);
        }
        .garudo-step-pill.completed .step-circle {
            background: #10B981;
            color: #FFFFFF;
        }
        .garudo-step-pill .step-txt {
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748B;
            transition: color 0.2s ease;
        }
        .garudo-step-pill.active .step-txt {
            color: #064E3B;
        }
        .garudo-step-line {
            width: 18px;
            height: 2px;
            background: #CBD5E1;
            flex-shrink: 0;
        }

        /* 6. Modal Body & Unique Horizon Step Glide */
        .garudo-modal-body {
            padding: 1.4rem 1.75rem;
            background: #F8FAF9;
            max-height: 70vh;
            overflow-y: auto;
        }
        .garudo-step-pane {
            display: none;
        }
        .garudo-step-pane.active {
            display: block;
            animation: garudoHorizonSlide 0.24s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes garudoHorizonSlide {
            0% { opacity: 0; transform: translateX(12px) scale(0.99); }
            100% { opacity: 1; transform: translateX(0) scale(1); }
        }

        /* 7. Form Controls */
        .garudo-label {
            font-size: 0.81rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.35rem;
            display: block;
        }
        .garudo-input {
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            padding: 0.58rem 0.88rem;
            font-size: 0.87rem;
            background: #FFFFFF;
            color: #0F172A;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .garudo-input:focus {
            border-color: #0B4D3C;
            box-shadow: 0 0 0 3.5px rgba(11, 77, 60, 0.12);
            outline: none;
            transform: translateY(-1px);
        }
        .garudo-input::placeholder {
            color: #94A3B8;
            font-size: 0.81rem;
        }

        /* 8. Urgency Cards */
        .garudo-urgency-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px;
        }
        .garudo-urgency-card {
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            padding: 0.6rem 0.8rem;
            background: #FFFFFF;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .garudo-urgency-card:hover {
            border-color: #10B981;
            background: #F0FDF4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
        }
        .garudo-urgency-card.selected {
            border-color: #0B4D3C;
            background: #ECFDF5;
            box-shadow: 0 0 0 3px rgba(11, 77, 60, 0.12);
            transform: translateY(-1px);
        }
        .garudo-urgency-card input[type="radio"] {
            display: none;
        }

        /* 9. Dropzone */
        .garudo-dropzone {
            border: 2px dashed #CBD5E1;
            border-radius: 14px;
            background: #FFFFFF;
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .garudo-dropzone:hover {
            border-color: #10B981;
            background: #F0FDF4;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.12);
        }

        /* 10. Callout Banners on Product Page with Micro-Glow */
        .garudo-callout-rfq {
            background: linear-gradient(135deg, #ECFDF5 0%, #F0FDF4 50%, #F8FAFC 100%);
            border: 1.5px solid rgba(16, 185, 129, 0.38) !important;
            border-radius: 16px;
            padding: 1.15rem 1.35rem;
            box-shadow: 0 6px 20px -4px rgba(11, 77, 60, 0.09);
            position: relative;
            overflow: hidden;
            transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.22s ease;
        }
        .garudo-callout-rfq:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px -4px rgba(11, 77, 60, 0.15);
        }

        .garudo-callout-sample {
            background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 45%, #F8FAF9 100%);
            border: 1.5px solid rgba(16, 185, 129, 0.42) !important;
            border-radius: 16px;
            padding: 1.15rem 1.35rem;
            box-shadow: 0 6px 20px -4px rgba(16, 185, 129, 0.09);
            position: relative;
            overflow: hidden;
            transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.22s ease;
        }
        .garudo-callout-sample:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px -4px rgba(16, 185, 129, 0.15);
        }

        .btn-garudo-emerald {
            background: linear-gradient(135deg, #0B4D3C 0%, #15803D 50%, #10B981 100%);
            color: #FFFFFF !important;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 14px rgba(11, 77, 60, 0.28);
        }
        .btn-garudo-emerald:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 22px rgba(11, 77, 60, 0.38);
        }
        .btn-garudo-emerald:active {
            transform: translateY(0) scale(0.99);
        }
    </style>

    <!-- ========================================================================== -->
    <!-- MODAL: Request / Order Material Sample                                     -->
    <!-- ========================================================================== -->
    @if($product->has_sample)
        @php
            $authCustomer = auth('customer')->user();
            $defaultAddr = $authCustomer ? $authCustomer->defaultAddress : null;
        @endphp
        <div class="modal fade" id="orderSampleModal" tabindex="-1" aria-labelledby="orderSampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content garudo-sheet-card border-0">
                    
                    <!-- Header -->
                    <div class="garudo-modal-head-luxury">
                        <div class="garudo-header-laser"></div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="garudo-head-icon">
                                    <i class="bi bi-box2-heart-fill"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-0.5">
                                        <span class="badge text-white px-2 py-0.5 fw-bold" style="background: rgba(255,255,255,0.18); font-size: 0.68rem;">
                                            <i class="bi bi-patch-check-fill text-warning me-0.5"></i> MATERIAL SAMPLE KIT
                                        </span>
                                        {!! $product->getSampleBadge() !!}
                                    </div>
                                    <h5 class="fw-bold mb-0 text-white" id="orderSampleModalLabel">Order Material Test Specimen</h5>
                                    <small class="text-white-50" style="font-size: 0.78rem;">
                                        Supplied by <strong class="text-white">{{ $product->vendor->company_name ?? 'Verified Supplier' }}</strong>
                                    </small>
                                </div>
                            </div>
                            <button type="button" class="garudo-btn-close-light" data-bs-dismiss="modal" aria-label="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('sample.request.store') }}" method="POST" id="storeSampleForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="garudo-modal-body">
                            <!-- Product Snippet & Specimen Info -->
                            <div class="p-3 bg-white rounded-3 border mb-3 shadow-sm">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->file_path) : 'https://placehold.co/100x100?text=Material' }}" 
                                         class="rounded-3 border" style="width: 52px; height: 52px; object-fit: cover;" alt="{{ $product->name }}">
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $product->name }}</h6>
                                        <div class="d-flex flex-wrap align-items-center gap-2 small text-muted mt-1" style="font-size: 0.78rem;">
                                            <span>Specimen: <strong class="text-dark">{{ $product->sample_unit_description ?? 'Test piece / Swatch' }}</strong></span>
                                            <span>•</span>
                                            <span>SKU: <strong class="text-dark">{{ $product->sku }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                @if($product->sample_instructions)
                                    <div class="mt-2 pt-2 border-top small text-muted" style="font-size: 0.76rem;">
                                        <i class="bi bi-info-circle text-primary me-1"></i><strong>Supplier Note:</strong> {{ $product->sample_instructions }}
                                    </div>
                                @endif
                            </div>

                            <div class="row g-2.5">
                                <!-- Buyer Contact Details -->
                                <div class="col-md-6">
                                    <label class="garudo-label">Your Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_name" class="form-control garudo-input" value="{{ old('buyer_name', $authCustomer->name ?? '') }}" placeholder="e.g. Rajesh Sharma" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="garudo-label">Company / Firm Name</label>
                                    <input type="text" name="company_name" class="form-control garudo-input" value="{{ old('company_name', $authCustomer->company_name ?? '') }}" placeholder="e.g. Apex Builders & Contractors">
                                </div>

                                <div class="col-md-6">
                                    <label class="garudo-label">Mobile Phone <span class="text-danger">*</span></label>
                                    <input type="tel" name="buyer_phone" class="form-control garudo-input" value="{{ old('buyer_phone', $authCustomer->phone ?? '') }}" placeholder="e.g. 9876543210" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="garudo-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="buyer_email" class="form-control garudo-input" value="{{ old('buyer_email', $authCustomer->email ?? '') }}" placeholder="e.g. rajesh@apexbuilders.in" required>
                                </div>

                                <!-- Shipping Address -->
                                <div class="col-12">
                                    <label class="garudo-label">Delivery Address (Site / Office) <span class="text-danger">*</span></label>
                                    <textarea name="delivery_address" rows="2" class="form-control garudo-input" placeholder="Complete street address, site plot number, landmark..." required>{{ old('delivery_address', $defaultAddr->address_line1 ?? '') }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="garudo-label">City <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control garudo-input" value="{{ old('city', $defaultAddr->city ?? '') }}" placeholder="e.g. Mumbai" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="garudo-label">State <span class="text-danger">*</span></label>
                                    <input type="text" name="state" class="form-control garudo-input" value="{{ old('state', $defaultAddr->state ?? 'Maharashtra') }}" placeholder="e.g. Maharashtra" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="garudo-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="pincode" class="form-control garudo-input" value="{{ old('pincode', $defaultAddr->postal_code ?? '') }}" placeholder="e.g. 400001" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="garudo-label">Project Type / Application</label>
                                    <select name="project_type" class="form-select garudo-input">
                                        <option value="Residential Construction">Residential Construction / Villa</option>
                                        <option value="Commercial High-Rise">Commercial High-Rise / Office</option>
                                        <option value="Infrastructure & Roadworks">Infrastructure & Roadworks</option>
                                        <option value="Architectural Specification">Architectural Specification</option>
                                        <option value="Lab & Quality Testing">Lab & Quality Testing</option>
                                        <option value="Wholesale Resale Trial">Wholesale Resale Trial</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="garudo-label">Sample Quantity</label>
                                    <div class="input-group">
                                        <input type="number" name="quantity" id="sampleModalQty" class="form-control garudo-input text-center font-monospace fw-bold" 
                                               value="1" min="1" max="{{ $product->sample_max_qty ?? 1 }}" oninput="recalculateSampleCost(this.value)">
                                        <span class="input-group-text bg-light small text-muted" style="border-radius: 0 10px 10px 0;">Max {{ $product->sample_max_qty ?? 1 }}</span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="garudo-label">Special Testing Notes / Remarks</label>
                                    <input type="text" name="notes" class="form-control garudo-input form-control-sm" placeholder="e.g. Need compressive strength test certificate with sample.">
                                </div>
                            </div>

                            <!-- Cost Summary Box -->
                            <div class="p-3 bg-white rounded-3 border mt-3 shadow-sm">
                                <div class="d-flex align-items-center justify-content-between mb-1 small">
                                    <span class="text-muted">Sample Unit Price:</span>
                                    <span class="fw-semibold text-dark">
                                        @if($product->sample_type === 'free' || $product->sample_type === 'partial_paid')
                                            ₹0.00 (Free Material)
                                        @else
                                            ₹{{ number_format($product->sample_price, 2) }} / unit
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-1 small">
                                    <span class="text-muted">Courier / Dispatch Handling:</span>
                                    <span class="fw-semibold text-dark">
                                        @if($product->sample_type === 'free')
                                            ₹0.00 (Sponsored Delivery)
                                        @else
                                            ₹{{ number_format($product->sample_shipping_fee, 2) }}
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <strong class="text-dark small">Total Amount Payable:</strong>
                                    <strong class="text-success fs-5" id="sampleModalTotalDisplay">
                                        @php
                                            $initTotal = ($product->sample_type === 'paid') 
                                                ? ($product->sample_price * 1 + $product->sample_shipping_fee) 
                                                : (($product->sample_type === 'partial_paid') ? $product->sample_shipping_fee : 0.00);
                                        @endphp
                                        ₹{{ number_format($initTotal, 2) }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-top bg-white p-3 px-4 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-light border btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                            <button type="submit" class="btn btn-garudo-emerald btn-sm px-4 fw-bold shadow-sm">
                                <i class="bi bi-send-check me-1"></i> Submit Sample Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        const sampleBasePrice = {{ ($product->sample_type === 'paid') ? $product->sample_price : 0 }};
        const sampleShippingFee = {{ in_array($product->sample_type, ['paid', 'partial_paid']) ? $product->sample_shipping_fee : 0 }};

        function recalculateSampleCost(qty) {
            qty = parseInt(qty) || 1;
            const total = (sampleBasePrice * qty) + sampleShippingFee;
            const elem = document.getElementById('sampleModalTotalDisplay');
            if (elem) elem.innerText = '₹' + total.toFixed(2);
        }
        </script>
    @endif

    <!-- ========================================================================== -->
    <!-- MODAL: Request / Ask for Custom B2B Quotation (RFQ)                        -->
    <!-- ========================================================================== -->
    @if($product->isQuotationEnabled())
        @php
            $authCustomer = auth('customer')->user();
            $defaultAddr = $authCustomer ? $authCustomer->defaultAddress : null;
            $customFields = $product->getQuotationCustomFields();
            $productVariants = $product->variants()->where('status', 'active')->get();
        @endphp

        <div class="modal fade" id="requestQuotationModal" tabindex="-1" aria-labelledby="requestQuotationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content garudo-sheet-card border-0">
                    
                    <!-- Luxury Header -->
                    <div class="garudo-modal-head-luxury">
                        <div class="garudo-header-laser"></div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="garudo-head-icon">
                                    <i class="bi bi-file-earmark-ruled-fill"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-0.5">
                                        <span class="badge text-white px-2 py-0.5 fw-bold" style="background: rgba(255,255,255,0.18); font-size: 0.68rem; letter-spacing: 0.5px;">
                                            <i class="bi bi-lightning-charge-fill text-warning me-0.5"></i> B2B DIRECT RFQ
                                        </span>
                                        <span class="badge bg-warning text-dark px-2 py-0.5 fw-bold" style="font-size: 0.68rem;">OFFICIAL SUPPLIER DESK</span>
                                    </div>
                                    <h5 class="fw-bold mb-0 text-white" id="requestQuotationModalLabel" style="letter-spacing: -0.2px;">Request Direct Price Quotation</h5>
                                    <small class="text-white-50" style="font-size: 0.78rem;">
                                        Direct from <strong class="text-white">{{ $product->vendor->company_name ?? 'Verified Manufacturer' }}</strong> • Monitored by Garudo Trade Desk
                                    </small>
                                </div>
                            </div>
                            <button type="button" class="garudo-btn-close-light" data-bs-dismiss="modal" aria-label="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- 3-Step Interactive Navigation Tabs -->
                    <div class="garudo-stepper">
                        <div class="garudo-step-pill active" id="stepPill1" onclick="jumpToStep(1);">
                            <div class="step-circle" id="stepNum1">1</div>
                            <span class="step-txt">Quantity & Specs</span>
                        </div>
                        <div class="garudo-step-line"></div>
                        <div class="garudo-step-pill" id="stepPill2" onclick="jumpToStep(2);">
                            <div class="step-circle" id="stepNum2">2</div>
                            <span class="step-txt">Buyer & Delivery</span>
                        </div>
                        <div class="garudo-step-line"></div>
                        <div class="garudo-step-pill" id="stepPill3" onclick="jumpToStep(3);">
                            <div class="step-circle" id="stepNum3">3</div>
                            <span class="step-txt">BOQ & Submit</span>
                        </div>
                    </div>

                    <form action="{{ route('quotation.request.store') }}" method="POST" enctype="multipart/form-data" id="storeQuotationForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="unit" value="{{ $product->unit ?? 'Units' }}">

                        <div class="garudo-modal-body">
                            
                            <!-- ============================================================== -->
                            <!-- STEP 1: Product Specifications & Quantity Requirements       -->
                            <!-- ============================================================== -->
                            <div class="garudo-step-pane active" id="rfqStep1">
                                <!-- Product Spec Strip -->
                                <div class="p-3 bg-white rounded-3 border mb-3 shadow-sm">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->file_path) : 'https://placehold.co/100x100?text=Material' }}" 
                                             class="rounded-3 border" style="width: 54px; height: 54px; object-fit: cover;" alt="{{ $product->name }}">
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                                <span class="badge bg-light text-secondary border px-2 py-0.5" style="font-size: 0.72rem;">{{ $product->category->name ?? 'Material' }}</span>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                                    <i class="bi bi-patch-check-fill me-1"></i>{{ $product->vendor->company_name ?? 'Verified Supplier' }}
                                                </span>
                                                @if($product->quotation_min_qty)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 0.72rem;">
                                                        <i class="bi bi-box-seam me-1"></i>Min RFQ: {{ number_format($product->quotation_min_qty) }} {{ $product->unit ?? 'Units' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h6 class="fw-bold text-dark mb-0 text-truncate" id="rfqSummaryProductName">{{ $product->name }}</h6>
                                            <div class="text-muted small mt-0.5" style="font-size: 0.76rem;">SKU: <strong class="text-dark">{{ $product->sku }}</strong> • Billing Unit: <strong class="text-dark">{{ $product->unit ?? 'Units' }}</strong></div>
                                        </div>
                                    </div>

                                    @if($product->quotation_notes)
                                        <div class="mt-2.5 pt-2 border-top small text-success d-flex align-items-start gap-1.5" style="font-size: 0.78rem;">
                                            <i class="bi bi-info-circle-fill mt-0.5 text-success"></i>
                                            <div><strong>Manufacturer Note:</strong> {{ $product->quotation_notes }}</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row g-2.5">
                                    <!-- Variant Selector (If product has variants) -->
                                    @if($productVariants->isNotEmpty())
                                        <div class="col-md-6">
                                            <label class="garudo-label">Material Grade / Variant</label>
                                            <select name="selected_variant" id="rfqSelectedVariant" class="form-select garudo-input" onchange="updateLiveSummary();">
                                                <option value="">-- Standard / Base Specification --</option>
                                                @foreach($productVariants as $v)
                                                    <option value="{{ $v->sku }} (₹{{ number_format($v->wholesale_price ?: $v->selling_price, 2) }})">
                                                        {{ $v->sku }} — ₹{{ number_format($v->wholesale_price ?: $v->selling_price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <!-- Quantity Input -->
                                    <div class="{{ $productVariants->isNotEmpty() ? 'col-md-6' : 'col-md-6' }}">
                                        <label class="garudo-label">Estimated Quantity Required <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="quantity" id="rfqInputQuantity" min="{{ $product->quotation_min_qty ?? $product->min_order_qty ?? 1 }}" 
                                                   value="{{ $product->quotation_min_qty ?? $product->min_order_qty ?? 1 }}" 
                                                   class="form-control garudo-input font-monospace fw-bold text-center" oninput="updateLiveSummary();" required>
                                            <span class="input-group-text bg-light text-muted small fw-semibold" style="border-radius: 0 10px 10px 0;">{{ $product->unit ?? 'Units' }}</span>
                                        </div>
                                        @if($product->quotation_min_qty)
                                            <div class="form-text small text-muted" style="font-size: 0.74rem;">Supplier minimum order: {{ number_format($product->quotation_min_qty) }} {{ $product->unit ?? 'Units' }}</div>
                                        @endif
                                    </div>

                                    <!-- Target Price Expected (Optional) -->
                                    <div class="col-md-6">
                                        <label class="garudo-label">Target Rate / Price Expectation <span class="text-muted fw-normal">(Optional)</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-muted" style="border-radius: 10px 0 0 10px;">₹</span>
                                            <input type="number" step="0.01" name="target_price" id="rfqInputTargetPrice" class="form-control garudo-input" placeholder="e.g. {{ number_format($product->selling_price, 2) }}" oninput="updateLiveSummary();">
                                            <span class="input-group-text bg-light text-muted small" style="border-radius: 0 10px 10px 0;">/ {{ $product->unit ?? 'Unit' }}</span>
                                        </div>
                                    </div>

                                    <!-- Dispatch Urgency Selection Cards -->
                                    <div class="col-12">
                                        <label class="garudo-label">Dispatch Urgency / Project Timeline</label>
                                        <div class="garudo-urgency-grid">
                                            <label class="garudo-urgency-card" onclick="selectUrgency(this);">
                                                <input type="radio" name="urgency_level" value="Immediate (1-3 Days)">
                                                <span class="fs-5">⚡</span>
                                                <div>
                                                    <strong class="d-block text-dark small" style="font-size: 0.8rem;">Immediate</strong>
                                                    <span class="text-muted" style="font-size: 0.72rem;">1-3 Business Days</span>
                                                </div>
                                            </label>

                                            <label class="garudo-urgency-card selected" onclick="selectUrgency(this);">
                                                <input type="radio" name="urgency_level" value="Urgent (1 Week)" checked>
                                                <span class="fs-5">🚀</span>
                                                <div>
                                                    <strong class="d-block text-dark small" style="font-size: 0.8rem;">High Priority</strong>
                                                    <span class="text-muted" style="font-size: 0.72rem;">Within 1 Week</span>
                                                </div>
                                            </label>

                                            <label class="garudo-urgency-card" onclick="selectUrgency(this);">
                                                <input type="radio" name="urgency_level" value="Standard (2-4 Weeks)">
                                                <span class="fs-5">📅</span>
                                                <div>
                                                    <strong class="d-block text-dark small" style="font-size: 0.8rem;">Standard Schedule</strong>
                                                    <span class="text-muted" style="font-size: 0.72rem;">2-4 Weeks Project</span>
                                                </div>
                                            </label>

                                            <label class="garudo-urgency-card" onclick="selectUrgency(this);">
                                                <input type="radio" name="urgency_level" value="Planning / Estimation">
                                                <span class="fs-5">📊</span>
                                                <div>
                                                    <strong class="d-block text-dark small" style="font-size: 0.8rem;">Budgeting / BOQ</strong>
                                                    <span class="text-muted" style="font-size: 0.72rem;">Tender Estimation</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Expected Delivery Date -->
                                    <div class="col-md-6">
                                        <label class="garudo-label">Expected Delivery by Site Date</label>
                                        <input type="date" name="expected_delivery_date" class="form-control garudo-input" min="{{ date('Y-m-d') }}">
                                    </div>

                                    <!-- Vendor Configured Custom Fields / Variation Options -->
                                    @if(!empty($customFields))
                                        <div class="col-12">
                                            <div class="p-3 rounded-3 border bg-white shadow-sm">
                                                <label class="form-label fw-bold small text-dark mb-2.5 d-flex align-items-center gap-1.5">
                                                    <i class="bi bi-sliders text-success"></i> Manufacturer Technical Parameters:
                                                </label>
                                                <div class="row g-2.5">
                                                    @foreach($customFields as $idx => $field)
                                                        @php
                                                            $fieldLabel = is_array($field) ? ($field['label'] ?? $field['name'] ?? 'Spec ' . ($idx+1)) : trim($field);
                                                            $fieldType = is_array($field) ? ($field['type'] ?? 'text') : 'text';
                                                            $fieldOptions = is_array($field) ? ($field['options'] ?? '') : '';
                                                            $isRequired = is_array($field) ? (!empty($field['required'])) : false;
                                                            $optArray = !empty($fieldOptions) ? array_filter(array_map('trim', explode(',', $fieldOptions))) : [];
                                                        @endphp
                                                        @if(!empty($fieldLabel))
                                                            <div class="col-md-6">
                                                                <label class="garudo-label mb-1" style="font-size: 0.78rem;">
                                                                    {{ $fieldLabel }}
                                                                    @if($isRequired)<span class="text-danger">*</span>@endif
                                                                </label>
                                                                @if($fieldType === 'select' && count($optArray) > 0)
                                                                    <select name="custom_variations[{{ $fieldLabel }}]" class="form-select garudo-input form-control-sm" {{ $isRequired ? 'required' : '' }}>
                                                                        <option value="">-- Select {{ $fieldLabel }} --</option>
                                                                        @foreach($optArray as $opt)
                                                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @elseif($fieldType === 'checkbox' && count($optArray) > 0)
                                                                    @foreach($optArray as $opt)
                                                                        <div class="form-check form-check-inline mt-1">
                                                                            <input class="form-check-input" type="checkbox" name="custom_variations[{{ $fieldLabel }}][]" value="{{ $opt }}" id="chk_{{ $idx }}_{{ $loop->index }}">
                                                                            <label class="form-check-label small" for="chk_{{ $idx }}_{{ $loop->index }}">{{ $opt }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <input type="text" name="custom_variations[{{ $fieldLabel }}]" class="form-control garudo-input form-control-sm" placeholder="Specify {{ $fieldLabel }}..." {{ $isRequired ? 'required' : '' }}>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ============================================================== -->
                            <!-- STEP 2: Buyer Business Profile & Delivery Location             -->
                            <!-- ============================================================== -->
                            <div class="garudo-step-pane" id="rfqStep2">
                                <div class="p-3 bg-white rounded-3 border mb-3 shadow-sm">
                                    <div class="d-flex align-items-center gap-2 text-dark fw-bold small mb-2">
                                        <i class="bi bi-person-badge-fill text-success fs-5"></i>
                                        <span>Procurement Contact & Organization</span>
                                    </div>
                                    <div class="row g-2.5">
                                        <div class="col-md-6">
                                            <label class="garudo-label">Contact Person Name <span class="text-danger">*</span></label>
                                            <input type="text" name="buyer_name" id="rfqBuyerName" class="form-control garudo-input" value="{{ old('buyer_name', $authCustomer->name ?? '') }}" placeholder="e.g. Rajesh Sharma" oninput="updateLiveSummary();" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="garudo-label">Company / Contractor / Firm Name</label>
                                            <input type="text" name="company_name" id="rfqCompanyName" class="form-control garudo-input" value="{{ old('company_name', $authCustomer->company_name ?? '') }}" placeholder="e.g. Apex Infrastructure Projects LLP" oninput="updateLiveSummary();">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="garudo-label">Mobile Phone (Direct) <span class="text-danger">*</span></label>
                                            <input type="tel" name="buyer_phone" id="rfqBuyerPhone" class="form-control garudo-input" value="{{ old('buyer_phone', $authCustomer->phone ?? '') }}" placeholder="e.g. 9876543210" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="garudo-label">Procurement Email <span class="text-danger">*</span></label>
                                            <input type="email" name="buyer_email" id="rfqBuyerEmail" class="form-control garudo-input" value="{{ old('buyer_email', $authCustomer->email ?? '') }}" placeholder="e.g. procurement@apexinfra.com" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="garudo-label">GSTIN Number <span class="badge bg-light text-secondary border ms-1 fw-normal">Input Tax Credit</span></label>
                                            <input type="text" name="gstin" class="form-control garudo-input text-uppercase" value="{{ old('gstin', $authCustomer->gst_number ?? '') }}" placeholder="e.g. 27AAAAA0000A1Z5">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="garudo-label">Project Nature / Application</label>
                                            <select name="project_type" class="form-select garudo-input">
                                                <option value="Commercial High-Rise">Commercial High-Rise / Corporate Park</option>
                                                <option value="Residential Housing Project">Residential Township / Villa Project</option>
                                                <option value="Infrastructure / Highways">Infrastructure, Roads & Bridges</option>
                                                <option value="Industrial Factory / Warehouse">Industrial Factory / Warehouse Shed</option>
                                                <option value="Government / EPC Tender">Government / EPC Contract Tender</option>
                                                <option value="Wholesale Distribution">Wholesale Stocking / Resale</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Site Details -->
                                <div class="p-3 bg-white rounded-3 border shadow-sm">
                                    <div class="d-flex align-items-center gap-2 text-dark fw-bold small mb-2">
                                        <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                                        <span>F.O.R. Site Delivery Location</span>
                                    </div>
                                    <div class="row g-2.5">
                                        <div class="col-12">
                                            <label class="garudo-label">Site Address / Plot / Industrial Area</label>
                                            <textarea name="delivery_address" rows="2" class="form-control garudo-input" placeholder="Complete site location, plot/survey number, landmark...">{{ old('delivery_address', $defaultAddr->address_line1 ?? '') }}</textarea>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="garudo-label">City / District</label>
                                            <input type="text" name="delivery_city" id="rfqDeliveryCity" class="form-control garudo-input" value="{{ old('delivery_city', $defaultAddr->city ?? '') }}" placeholder="e.g. Pune" oninput="updateLiveSummary();">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="garudo-label">State</label>
                                            <input type="text" name="delivery_state" id="rfqDeliveryState" class="form-control garudo-input" value="{{ old('delivery_state', $defaultAddr->state ?? 'Maharashtra') }}" placeholder="e.g. Maharashtra" oninput="updateLiveSummary();">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="garudo-label">Pincode</label>
                                            <input type="text" name="delivery_pincode" class="form-control garudo-input" value="{{ old('delivery_pincode', $defaultAddr->postal_code ?? '') }}" placeholder="e.g. 411001">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================================== -->
                            <!-- STEP 3: Attachments, Special Notes & Live Review              -->
                            <!-- ============================================================== -->
                            <div class="garudo-step-pane" id="rfqStep3">
                                <div class="row g-2.5">
                                    <!-- Drag & Drop File Zone -->
                                    <div class="col-12">
                                        <label class="garudo-label">Attach BOQ Spreadsheet / CAD Drawings <span class="text-muted fw-normal">(Optional)</span></label>
                                        <div class="garudo-dropzone" onclick="document.getElementById('rfqAttachmentFile').click();">
                                            <input type="file" name="attachment" id="rfqAttachmentFile" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.dwg,.jpg,.jpeg,.png,.webp" onchange="handleRfqFileSelect(this);">
                                            <i class="bi bi-cloud-arrow-up-fill fs-2 text-success d-block mb-1"></i>
                                            <div class="fw-bold text-dark small mb-0.5">Click to browse or drop project drawings / BOQ</div>
                                            <div class="text-muted" style="font-size: 0.74rem;">Supported: .PDF, .DWG, .XLSX, .DOCX, .PNG, .JPG (Max 10MB)</div>
                                            
                                            <div id="rfqFileChip" class="d-none mt-2 p-1.5 px-3 rounded-pill bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-2 small fw-bold">
                                                <i class="bi bi-file-earmark-check-fill"></i>
                                                <span id="rfqFileNameDisplay"></span>
                                                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1" onclick="event.stopPropagation(); removeRfqFile();">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Special Notes -->
                                    <div class="col-12">
                                        <label class="garudo-label">Special Quality Terms / Crane Unloading / Billing Notes</label>
                                        <textarea name="buyer_notes" rows="2" class="form-control garudo-input" placeholder="e.g. NABL test certificate mandatory. Need 40ft trailer access. 30-day corporate billing credit."></textarea>
                                    </div>

                                    <!-- Live Confirmation Summary Slip -->
                                    <div class="col-12">
                                        <div class="p-3 rounded-3 border bg-white shadow-sm" style="border-left: 4px solid #10B981 !important;">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <span class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">Quotation Request Summary</span>
                                                <span class="badge bg-success text-white px-2 py-0.5" style="font-size: 0.7rem;">READY TO TRANSMIT</span>
                                            </div>
                                            <div class="row g-2 small text-muted">
                                                <div class="col-sm-6">
                                                    <span>Product:</span> <strong class="text-dark d-block text-truncate" id="sumProdName">{{ $product->name }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span>Est. Volume:</span> <strong class="text-success d-block" id="sumQtyDisplay">{{ number_format($product->quotation_min_qty ?? $product->min_order_qty ?? 1) }} {{ $product->unit ?? 'Units' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span>Buyer Contact:</span> <strong class="text-dark d-block" id="sumBuyerDisplay">{{ $authCustomer->name ?? 'Contact Person' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <span>Destination:</span> <strong class="text-dark d-block" id="sumDestDisplay">{{ $defaultAddr->city ?? 'Site Location' }}, {{ $defaultAddr->state ?? 'India' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Footer & Step Controls -->
                        <div class="modal-footer p-3 px-4 d-flex justify-content-between align-items-center" style="background: #FFFFFF; border-top: 1px solid #E2E8F0;">
                            <div class="d-none d-sm-flex align-items-center gap-2 text-muted small" style="font-size: 0.76rem;">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <span><strong>Garudo Escrow Assurance:</strong> Direct transmission & response SLA &lt; 4 Hours</span>
                            </div>

                            <div class="d-flex gap-2 ms-auto">
                                <button type="button" class="btn btn-light border btn-sm px-3" id="btnRfqPrev" style="display: none; border-radius: 8px;" onclick="navigateRfqStep(-1);">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </button>
                                
                                <button type="button" class="btn btn-garudo-emerald btn-sm px-3.5" id="btnRfqNext" onclick="navigateRfqStep(1);">
                                    <span>Next: Buyer Details</span> <i class="bi bi-arrow-right ms-1"></i>
                                </button>

                                <button type="submit" class="btn btn-garudo-emerald btn-sm px-4" id="btnRfqSubmit" style="display: none;">
                                    <i class="bi bi-send-fill text-warning me-1"></i>
                                    <span>Submit Quotation Request</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        let currentRfqStep = 1;

        function jumpToStep(step) {
            if (step > currentRfqStep) {
                if (!validateStep(currentRfqStep)) return;
            }
            showRfqStep(step);
        }

        function navigateRfqStep(dir) {
            if (dir > 0) {
                if (!validateStep(currentRfqStep)) return;
                showRfqStep(currentRfqStep + 1);
            } else {
                showRfqStep(currentRfqStep - 1);
            }
        }

        function validateStep(step) {
            if (step === 1) {
                const qtyInput = document.getElementById('rfqInputQuantity');
                if (!qtyInput || !qtyInput.value || parseInt(qtyInput.value) <= 0) {
                    qtyInput.focus();
                    alert('Please specify a valid quantity for this quotation request.');
                    return false;
                }
            } else if (step === 2) {
                const name = document.getElementById('rfqBuyerName');
                const phone = document.getElementById('rfqBuyerPhone');
                const email = document.getElementById('rfqBuyerEmail');

                if (!name || !name.value.trim()) {
                    name.focus();
                    alert('Please provide your contact name.');
                    return false;
                }
                if (!phone || !phone.value.trim()) {
                    phone.focus();
                    alert('Please provide your phone number.');
                    return false;
                }
                if (!email || !email.value.trim()) {
                    email.focus();
                    alert('Please provide your email address.');
                    return false;
                }
            }
            return true;
        }

        function showRfqStep(step) {
            if (step < 1) step = 1;
            if (step > 3) step = 3;
            currentRfqStep = step;

            // Panes
            document.querySelectorAll('.garudo-step-pane').forEach((p, idx) => {
                p.classList.toggle('active', (idx + 1) === step);
            });

            // Stepper bar
            for (let i = 1; i <= 3; i++) {
                const pill = document.getElementById('stepPill' + i);
                const num = document.getElementById('stepNum' + i);
                if (pill && num) {
                    pill.classList.remove('active', 'completed');
                    if (i === step) {
                        pill.classList.add('active');
                        num.innerHTML = i;
                    } else if (i < step) {
                        pill.classList.add('completed');
                        num.innerHTML = '<i class="bi bi-check-lg"></i>';
                    } else {
                        num.innerHTML = i;
                    }
                }
            }

            // Buttons
            const btnPrev = document.getElementById('btnRfqPrev');
            const btnNext = document.getElementById('btnRfqNext');
            const btnSubmit = document.getElementById('btnRfqSubmit');

            if (step === 1) {
                btnPrev.style.display = 'none';
                btnNext.style.display = 'inline-flex';
                btnNext.innerHTML = '<span>Next: Buyer Details</span> <i class="bi bi-arrow-right ms-1"></i>';
                btnSubmit.style.display = 'none';
            } else if (step === 2) {
                btnPrev.style.display = 'inline-flex';
                btnNext.style.display = 'inline-flex';
                btnNext.innerHTML = '<span>Next: Drawings & Review</span> <i class="bi bi-arrow-right ms-1"></i>';
                btnSubmit.style.display = 'none';
            } else if (step === 3) {
                btnPrev.style.display = 'inline-flex';
                btnNext.style.display = 'none';
                btnSubmit.style.display = 'inline-flex';
                updateLiveSummary();
            }
        }

        function selectUrgency(elem) {
            document.querySelectorAll('.garudo-urgency-card').forEach(c => c.classList.remove('selected'));
            elem.classList.add('selected');
            const radio = elem.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
            updateLiveSummary();
        }

        function handleRfqFileSelect(input) {
            const chip = document.getElementById('rfqFileChip');
            const nameDisplay = document.getElementById('rfqFileNameDisplay');
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeMb = (file.size / (1024 * 1024)).toFixed(1);
                nameDisplay.innerText = file.name + ' (' + sizeMb + ' MB)';
                chip.classList.remove('d-none');
            } else {
                chip.classList.add('d-none');
            }
        }

        function removeRfqFile() {
            const input = document.getElementById('rfqAttachmentFile');
            if (input) input.value = '';
            const chip = document.getElementById('rfqFileChip');
            if (chip) chip.classList.add('d-none');
        }

        function updateLiveSummary() {
            const qty = document.getElementById('rfqInputQuantity')?.value || '1';
            const unit = '{{ $product->unit ?? 'Units' }}';
            const targetPrice = document.getElementById('rfqInputTargetPrice')?.value;
            const buyerName = document.getElementById('rfqBuyerName')?.value || '{{ $authCustomer->name ?? 'Contact Person' }}';
            const city = document.getElementById('rfqDeliveryCity')?.value || '{{ $defaultAddr->city ?? 'Site' }}';
            const state = document.getElementById('rfqDeliveryState')?.value || '{{ $defaultAddr->state ?? 'India' }}';

            const sumQty = document.getElementById('sumQtyDisplay');
            if (sumQty) sumQty.innerText = Number(qty).toLocaleString() + ' ' + unit + (targetPrice ? ' @ ₹' + targetPrice : '');

            const sumBuyer = document.getElementById('sumBuyerDisplay');
            if (sumBuyer) sumBuyer.innerText = buyerName;

            const sumDest = document.getElementById('sumDestDisplay');
            if (sumDest) sumDest.innerText = city + ', ' + state;
        }

        // Reset to Step 1 whenever modal closes
        document.addEventListener('DOMContentLoaded', () => {
            const modalEl = document.getElementById('requestQuotationModal');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    showRfqStep(1);
                });
            }
        });
        </script>
    @endif

    <!-- Product Description & Specifications Tabs -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
                <ul class="nav nav-tabs fw-bold" id="productTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active text-dark" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc">Product Description</button></li>
                    <li class="nav-item"><button class="nav-link text-dark" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs">Specifications</button></li>
                    <li class="nav-item">
                        <button class="nav-link text-dark" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy">
                            <i class="bi bi-shield-check me-1 text-success"></i> Policy & Terms
                        </button>
                    </li>
                </ul>
                <div class="tab-content pt-4" id="productTabContent">
                    <div class="tab-pane fade show active" id="desc">
                        <p>{!! nl2br(e($product->description ?? 'No description provided.')) !!}</p>
                    </div>
                    <div class="tab-pane fade" id="specs">
                        <table class="table table-bordered">
                            <tbody>
                                <tr><th class="bg-light w-25">Category</th><td>{{ $product->category->name ?? 'N/A' }}</td></tr>
                                <tr><th class="bg-light">Brand</th><td>{{ $product->brand->name ?? 'Generic' }}</td></tr>
                                <tr><th class="bg-light">Minimum Order Quantity</th><td>{{ $product->min_order_qty }} {{ $product->unit }}</td></tr>
                                @if($product->warehouse)
                                    <tr><th class="bg-light">Primary Dispatch State</th><td>{{ $product->warehouse->state }} ({{ $product->warehouse->city }})</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="policy">
                        <div class="p-3 bg-light rounded border mb-3">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                    {{ $product->policy_type === 'custom' ? 'Product-Specific Policy' : (($product->category->name ?? 'Category') . ' Standard Policy') }}
                                </span>
                                @if($product->warehouse)
                                    <span class="badge bg-secondary-subtle text-dark border">
                                        <i class="bi bi-geo-alt me-1"></i> Dispatched From: {{ $product->warehouse->city }}, {{ $product->warehouse->state }}
                                    </span>
                                @endif
                            </div>
                            <p class="mb-0 text-dark" style="line-height: 1.6; font-size: 0.9rem;">
                                {!! nl2br(e($product->getEffectivePolicy())) !!}
                            </p>
                        </div>
                        <div class="row g-3 small text-muted">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-white">
                                    <i class="bi bi-patch-check-fill text-success fs-5"></i>
                                    <div>
                                        <strong class="text-dark d-block">Genuine Materials</strong>
                                        <span>Direct from certified B2B suppliers</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-white">
                                    <i class="bi bi-truck text-primary fs-5"></i>
                                    <div>
                                        <strong class="text-dark d-block">Transit Inspection</strong>
                                        <span>Verify before OTP handover</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-white">
                                    <i class="bi bi-receipt text-info fs-5"></i>
                                    <div>
                                        <strong class="text-dark d-block">GST Invoicing</strong>
                                        <span>100% Tax credit compliant</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
