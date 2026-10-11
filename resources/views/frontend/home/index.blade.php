@extends('layouts.frontend')

@section('title', 'Garudo B2B - India\'s No.1 B2B Building Materials Marketplace')

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- 1. HERO TOP SECTION: DEALS OF DAY, BULK ORDER HERO, AUCTION ZONE -->
    <style>
        .hero-section-row {
            margin-bottom: 24px;
        }
        .hero-col {
            display: flex;
            flex-direction: column;
        }
        /* Card Heights & Basics */
        .hero-card-base {
            border-radius: 14px;
            padding: 18px 16px;
            min-height: 405px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .hero-card-base:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        /* 1. Deals Card */
        .hero-card-deals {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-top: 3.5px solid #ff5400;
            box-shadow: 0 4px 18px rgba(255, 84, 0, 0.05);
        }
        /* 2. Sponsored Card */
        .hero-card-sponsored {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-top: 3.5px solid #2563eb;
            box-shadow: 0 4px 18px rgba(37, 99, 235, 0.05);
        }
        /* 3. Dark Bulk Order Card */
        .hero-card-bulk {
            background: linear-gradient(145deg, #07173b 0%, #030b1c 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 3.5px solid #f97316;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(7, 23, 59, 0.25);
        }
        /* 4. Dark Auction Card */
        .hero-card-auction {
            background: linear-gradient(145deg, #0d1527 0%, #050810 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-top: 3.5px solid #eab308;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(13, 21, 39, 0.25);
        }

        /* Header Rows */
        .hero-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .hero-card-header-dark {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .hero-header-title {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 800;
            font-size: 12.5px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .hero-timer-container {
            display: inline-flex;
            align-items: center;
            background: #0f172a;
            padding: 3px 7px;
            border-radius: 6px;
            gap: 2px;
        }
        .hero-timer-digit {
            font-size: 11px;
            font-weight: 800;
            color: #ff5400;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            min-width: 14px;
            text-align: center;
        }
        .hero-timer-sep {
            font-size: 11px;
            font-weight: 800;
            color: #ff8c42;
            animation: heroBlink 1s infinite;
        }
        @keyframes heroBlink {
            0%, 49% { opacity: 1; }
            50%, 100% { opacity: 0.3; }
        }

        /* Sponsored Badge Pill */
        .hero-ad-pill {
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 4px;
            letter-spacing: 0.4px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }
        .hero-ad-tag-badge {
            position: absolute;
            top: 2px;
            left: 2px;
            background: #1e293b;
            color: #f8fafc;
            font-size: 8px;
            font-weight: 800;
            padding: 1px 4px;
            border-radius: 3px;
            line-height: 1;
            z-index: 2;
            letter-spacing: 0.2px;
        }

        .hero-view-link {
            font-size: 11px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 2px;
            transition: color 0.15s ease;
        }
        .hero-view-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* Product Lists in White Cards */
        .hero-item-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-grow: 1;
        }
        .hero-item-row {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 6px;
            border-radius: 10px;
            transition: background 0.15s ease;
        }
        .hero-item-row:hover {
            background: #f8fafc;
        }
        .hero-item-img-box {
            position: relative;
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 3px;
            overflow: hidden;
        }
        .hero-item-img-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.2s ease;
        }
        .hero-item-row:hover .hero-item-img-box img {
            transform: scale(1.05);
        }
        .hero-discount-pill {
            position: absolute;
            top: 2px;
            left: 2px;
            background: #ff5400;
            color: #fff;
            font-weight: 800;
            font-size: 8.5px;
            padding: 1px 4px;
            border-radius: 3px;
            z-index: 2;
            line-height: 1;
        }

        .hero-item-details {
            flex-grow: 1;
            min-width: 0;
        }
        .hero-item-name {
            font-size: 11.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            text-decoration: none;
        }
        .hero-item-name:hover {
            color: #2563eb;
        }
        .hero-item-vendor {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .hero-item-price-line {
            font-size: 12.5px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: baseline;
            gap: 4px;
        }
        .hero-item-old-price {
            font-size: 10px;
            color: #94a3b8;
            text-decoration: line-through;
            font-weight: 500;
        }
        .hero-item-action-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
            color: #334155;
            padding: 4px 9px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            cursor: pointer;
            flex-shrink: 0;
        }
        .hero-item-action-btn:hover {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }
        .hero-item-sponsor-btn {
            background: rgba(37, 99, 235, 0.08);
            border: 1px solid rgba(37, 99, 235, 0.25);
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
            color: #2563eb;
            padding: 4px 9px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            flex-shrink: 0;
        }
        .hero-item-sponsor-btn:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        /* Horizontal Scroll Showcase for Sponsored Section */
        .hero-sponsor-hscroll {
            display: flex;
            flex-direction: row;
            gap: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: auto !important;
            -webkit-overflow-scrolling: touch;
            padding: 4px 2px 10px 2px;
            margin-bottom: 2px;
        }
        .hero-sponsor-hscroll::-webkit-scrollbar {
            height: 5px;
        }
        .hero-sponsor-hscroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .hero-sponsor-hscroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .hero-sponsor-hscroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .hero-sponsor-card {
            flex: 0 0 175px;
            width: 175px;
            min-width: 175px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease-in-out;
            position: relative;
        }
        .hero-sponsor-card:hover {
            border-color: #93c5fd;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.12);
            transform: translateY(-2px);
        }
        .hero-sponsor-card-img {
            width: 100%;
            height: 120px;
            background: #f8fafc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin-bottom: 8px;
            padding: 6px;
        }
        .hero-sponsor-card-img img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.25s ease;
        }
        .hero-sponsor-card:hover .hero-sponsor-card-img img {
            transform: scale(1.08);
        }

        .hero-scroll-nav-btn {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #334155;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .hero-scroll-nav-btn:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .hero-card-footer-strip {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 8px;
            margin-top: 8px;
            border-top: 1px dashed #e2e8f0;
        }

        /* Dark Hero Cards Content */
        .hero-dark-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #f8fafc;
            font-size: 9.5px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.4px;
            margin-bottom: 8px;
        }
        .hero-badge-live {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }
        .hero-live-dot {
            width: 7px;
            height: 7px;
            background: #ef4444;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: heroPulse 1.5s infinite;
        }
        @keyframes heroPulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        .hero-dark-title {
            font-size: 19px;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 3px;
            line-height: 1.2;
        }
        .hero-dark-subhead {
            font-size: 13.5px;
            font-weight: 700;
            color: #fdba74;
            margin-bottom: 3px;
            line-height: 1.3;
        }
        .hero-dark-desc {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 14px;
            line-height: 1.4;
            max-width: 90%;
            position: relative;
            z-index: 2;
        }
        .hero-feature-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 18px;
            position: relative;
            z-index: 2;
        }
        .hero-feature-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            font-weight: 600;
            color: #e2e8f0;
        }
        .hero-feature-icon {
            width: 22px;
            height: 22px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
            background: rgba(255, 84, 0, 0.12);
            color: #ff5400;
            border: 1px solid rgba(255, 84, 0, 0.3);
        }
        .hero-feature-icon-amber {
            background: rgba(234, 179, 8, 0.12);
            color: #eab308;
            border: 1px solid rgba(234, 179, 8, 0.3);
        }

        .hero-cta-btn {
            background: #ff5400;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            width: fit-content;
            position: relative;
            z-index: 2;
            box-shadow: 0 4px 14px rgba(255, 84, 0, 0.35);
            transition: all 0.2s ease-in-out;
        }
        .hero-cta-btn:hover {
            background: #e04800;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 84, 0, 0.5);
        }
        .hero-cta-amber {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
        }
        .hero-cta-amber:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            box-shadow: 0 6px 18px rgba(217, 119, 6, 0.5);
        }

        .hero-dark-bg-img {
            position: absolute;
            right: -10px;
            bottom: -5px;
            height: 68%;
            max-width: 50%;
            object-fit: contain;
            object-position: right bottom;
            z-index: 1;
            opacity: 0.88;
            pointer-events: none;
            transition: transform 0.3s ease;
        }
        .hero-card-bulk:hover .hero-dark-bg-img,
        .hero-card-auction:hover .hero-dark-bg-img {
            transform: scale(1.04);
        }

        @media (max-width: 1199px) {
            .hero-card-base {
                min-height: 385px;
            }
        }
        @media (max-width: 767px) {
            .hero-card-base {
                min-height: auto;
            }
            .hero-dark-bg-img {
                height: 50%;
                opacity: 0.35;
            }
        }

        /* Special Offers & Popular Categories Enhancements */
        .special-offer-card {
            min-height: 220px;
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }
        .special-offer-card:hover {
            transform: translateY(-5px);
        }
        .offer-img-box {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 48%;
            overflow: hidden;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .offer-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.4s ease;
        }
        .special-offer-card:hover .offer-img-box img {
            transform: scale(1.08);
        }
        .offer-img-fade-left {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
        }
        .offer-tag-pill {
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 3px 9px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 8px;
            backdrop-filter: blur(4px);
        }
        .popular-cat-card {
            min-height: 355px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .popular-cat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08) !important;
            border-color: #94a3b8 !important;
        }
        .top-product-row {
            transition: background 0.15s ease;
            padding: 7px 6px;
            border-radius: 8px;
        }
        .top-product-row:hover {
            background: #f1f5f9;
        }
        .top-product-row:hover .product-row-title {
            color: #2563eb !important;
        }
        .view-all-cat-link:hover {
            text-decoration: underline !important;
            color: #1d4ed8 !important;
        }

        /* -------------------------------------------------------------
           Image 1 Landing Sections:
           1. Services & Professionals Strip
           2. Live Tenders & Auctions Strip
           3. Sponsored Brand Partners Logo Strip
           4. Explore by Application Carousel Cards
           5. Trust & Assurance Badges
        ------------------------------------------------------------- */
        .landing-section-wrapper {
            margin-bottom: 22px;
            position: relative;
        }

        /* Scroll Reveal Animation */
        .garudo-reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.55s cubic-bezier(0.16, 1, 0.3, 1), transform 0.55s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .garudo-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Pulsing Live Beacon */
        .live-pulse-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #ef4444;
            margin-right: 4px;
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8);
            animation: livePulseRadar 1.6s infinite cubic-bezier(0.66, 0, 0, 1);
            vertical-align: middle;
        }
        .live-pulse-dot-green {
            background-color: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.8);
            animation: livePulseRadarGreen 1.6s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        @keyframes livePulseRadar {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.8); }
            70% { box-shadow: 0 0 0 7px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        @keyframes livePulseRadarGreen {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.8); }
            70% { box-shadow: 0 0 0 7px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        /* Universal Horizontal Scroll Tracks (Drag & Wheel Ready) */
        .services-scroll-track,
        .tenders-scroll-track,
        .sponsored-partners-track,
        .app-carousel-track,
        .categories-scroll-track,
        .hero-sponsor-hscroll {
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: auto !important; /* CRITICAL: Never use smooth here as it causes frame collision and stutter */
        }
        .services-scroll-track::-webkit-scrollbar,
        .tenders-scroll-track::-webkit-scrollbar,
        .sponsored-partners-track::-webkit-scrollbar,
        .app-carousel-track::-webkit-scrollbar,
        .categories-scroll-track::-webkit-scrollbar,
        .hero-sponsor-hscroll::-webkit-scrollbar {
            display: none;
        }
        .services-scroll-track.is-dragging,
        .tenders-scroll-track.is-dragging,
        .sponsored-partners-track.is-dragging,
        .app-carousel-track.is-dragging,
        .categories-scroll-track.is-dragging,
        .hero-sponsor-hscroll.is-dragging {
            cursor: grabbing !important;
            scroll-behavior: auto !important;
        }
        .services-scroll-track.is-dragging a,
        .tenders-scroll-track.is-dragging a,
        .sponsored-partners-track.is-dragging a,
        .app-carousel-track.is-dragging a,
        .categories-scroll-track.is-dragging a {
            pointer-events: none;
        }

        /* 1. Services & Professionals */
        .services-strip-container {
            display: flex;
            align-items: stretch;
            gap: 12px;
            position: relative;
        }
        .services-title-card {
            min-width: 175px;
            max-width: 175px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            flex-shrink: 0;
            position: relative;
            z-index: 2;
        }
        .services-title-card h6 {
            font-size: 15px;
            font-weight: 800;
            color: #07152b;
            line-height: 1.25;
            margin-bottom: 8px;
        }
        .services-scroll-track {
            display: flex;
            align-items: center;
            gap: 12px;
            overflow-x: auto;
            padding: 2px 6px 6px 2px;
            width: 100%;
        }
        .service-mini-card {
            min-width: 180px;
            max-width: 180px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        .service-mini-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.45) 50%, rgba(255,255,255,0) 100%);
            transform: rotate(25deg);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s, transform 0.65s ease;
        }
        .service-mini-card:hover::after {
            opacity: 1;
            transform: rotate(25deg) translate(380%, 0);
        }
        .service-mini-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.09);
            border-color: #ffb703;
        }
        .service-mini-img {
            width: 44px;
            height: 44px;
            border-radius: 9px;
            object-fit: cover;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }
        .service-mini-card:hover .service-mini-img {
            transform: scale(1.08);
        }
        .service-mini-info {
            overflow: hidden;
        }
        .service-mini-name {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .service-mini-link {
            font-size: 10.5px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
            font-weight: 600;
            transition: color 0.15s ease;
        }
        .service-mini-card:hover .service-mini-link {
            color: #d97706;
        }

        /* 2. Live Tenders & Auctions - Enlarged Height & Spacious Premium Cards */
        .tenders-strip-container {
            display: flex;
            align-items: stretch;
            gap: 16px;
            position: relative;
        }
        .tenders-title-card {
            min-width: 235px;
            max-width: 235px;
            min-height: 205px;
            background: linear-gradient(135deg, #1b204e 0%, #0d1238 100%);
            border-radius: 18px;
            padding: 26px 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 8px 24px rgba(16, 20, 56, 0.28);
            flex-shrink: 0;
            position: relative;
            z-index: 2;
        }
        .tenders-title-card h5 {
            font-size: 19px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.3;
            margin-bottom: 6px;
            letter-spacing: -0.2px;
        }
        .tenders-title-card p {
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.78);
            margin-bottom: 0;
            line-height: 1.45;
        }
        .tenders-scroll-track {
            display: flex;
            align-items: stretch;
            gap: 16px;
            overflow-x: auto;
            padding: 4px 6px 12px 4px;
            width: 100%;
            min-height: 205px;
            scroll-behavior: auto !important;
        }
        .tender-auction-card {
            min-width: 380px;
            max-width: 380px;
            min-height: 205px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        .tender-auction-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            transform: rotate(25deg);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s, transform 0.65s ease;
        }
        .tender-auction-card:hover::after {
            opacity: 1;
            transform: rotate(25deg) translate(380%, 0);
        }
        .tender-auction-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.11);
            border-color: #cbd5e1;
        }
        .tender-card-left {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex: 1;
            min-width: 0;
        }
        .tender-card-img {
            width: 140px;
            height: 140px;
            border-radius: 14px;
            object-fit: cover;
            flex-shrink: 0;
            align-self: center;
            transition: transform 0.35s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        .tender-auction-card:hover .tender-card-img {
            transform: scale(1.06);
        }
        .badge-tender {
            background: #1e3a8a;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.4px;
            display: inline-flex;
            align-items: center;
            width: fit-content;
        }
        .badge-auction {
            background: #3b0764;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.4px;
            display: inline-flex;
            align-items: center;
            width: fit-content;
        }
        .tender-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.35;
            margin: 6px 0 4px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .tender-card-location {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .tender-card-meta {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.35;
        }
        .tender-card-val {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .tender-card-btn {
            font-size: 11.5px;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
            width: fit-content;
            transition: all 0.15s ease;
        }
        .tender-card-btn-primary {
            border: 1.5px solid #2563eb;
            color: #2563eb;
            background: transparent;
        }
        .tender-card-btn-primary:hover {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .tender-card-btn-purple {
            border: 1.5px solid #3b0764;
            color: #3b0764;
            background: transparent;
        }
        .tender-card-btn-purple:hover {
            background: #3b0764;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 7, 100, 0.25);
        }

        /* ── Tender Detail Popup Modal ── */
        .tender-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(5, 10, 30, 0);
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            opacity: 0;
            visibility: hidden;
            transition: background 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                        backdrop-filter 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                        opacity 0.35s ease,
                        visibility 0s 0.45s;
            padding: 20px;
        }
        .tender-modal-overlay.is-open {
            opacity: 1;
            visibility: visible;
            background: rgba(5, 10, 30, 0.55);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: background 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                        backdrop-filter 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                        opacity 0.35s ease,
                        visibility 0s 0s;
        }
        .tender-modal-card {
            background: #ffffff;
            border-radius: 22px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.28),
                        0 0 0 1px rgba(255, 255, 255, 0.08);
            transform: scale(0.7) translateY(40px);
            opacity: 0;
            transition: transform 0.55s cubic-bezier(0.34, 1.56, 0.64, 1),
                        opacity 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .tender-modal-overlay.is-open .tender-modal-card {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
        .tender-modal-card::-webkit-scrollbar { width: 5px; }
        .tender-modal-card::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

        /* Modal Header */
        .tender-modal-header {
            position: relative;
            overflow: hidden;
            border-radius: 22px 22px 0 0;
            padding: 0;
        }
        .tender-modal-header-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .tender-modal-header-gradient {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(to top, rgba(5, 10, 30, 0.85) 0%, rgba(5, 10, 30, 0.2) 50%, transparent 100%);
            pointer-events: none;
        }
        .tender-modal-header-content {
            position: absolute;
            bottom: 16px;
            left: 20px;
            right: 60px;
            z-index: 2;
        }
        .tender-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            z-index: 5;
            transition: all 0.2s ease;
        }
        .tender-modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg) scale(1.1);
        }
        .tender-modal-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.5px;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .tender-modal-badge-tender {
            background: rgba(30, 58, 138, 0.9);
            color: #ffffff;
        }
        .tender-modal-badge-auction {
            background: rgba(88, 28, 135, 0.9);
            color: #ffffff;
        }
        .tender-modal-title {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.3;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.3);
        }

        /* Modal Body */
        .tender-modal-body {
            padding: 22px 22px 10px 22px;
        }
        .tender-modal-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 18px;
        }
        .tender-modal-info-item {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px 14px;
            opacity: 0;
            transform: translateY(12px);
            animation: tenderInfoReveal 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .tender-modal-info-item:nth-child(1) { animation-delay: 0.15s; }
        .tender-modal-info-item:nth-child(2) { animation-delay: 0.22s; }
        .tender-modal-info-item:nth-child(3) { animation-delay: 0.29s; }
        .tender-modal-info-item:nth-child(4) { animation-delay: 0.36s; }
        @keyframes tenderInfoReveal {
            to { opacity: 1; transform: translateY(0); }
        }
        .tender-modal-info-label {
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .tender-modal-info-value {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }
        .tender-modal-desc {
            font-size: 13px;
            color: #475569;
            line-height: 1.65;
            margin-bottom: 18px;
            padding: 14px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 3px solid #2563eb;
        }
        .tender-modal-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 18px;
        }
        .tender-modal-tag {
            font-size: 10px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            background: #eef2ff;
            color: #4338ca;
            border: 1px solid #e0e7ff;
        }

        /* Modal Footer */
        .tender-modal-footer {
            padding: 0 22px 22px 22px;
            display: flex;
            gap: 10px;
        }
        .tender-modal-btn {
            flex: 1;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .tender-modal-btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }
        .tender-modal-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
            color: #ffffff;
        }
        .tender-modal-btn-outline {
            background: transparent;
            color: #334155;
            border: 1.5px solid #e2e8f0;
        }
        .tender-modal-btn-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-2px);
            color: #334155;
        }
        .tender-modal-btn-auction {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
        }
        .tender-modal-btn-auction:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.5);
            color: #ffffff;
        }
        @media (max-width: 576px) {
            .tender-modal-card { max-width: 100%; border-radius: 18px; }
            .tender-modal-header-img { height: 160px; }
            .tender-modal-info-grid { grid-template-columns: 1fr; }
            .tender-modal-footer { flex-direction: column; }
        }

        /* Tender card clickable cursor */
        .tender-auction-card {
            cursor: pointer;
        }

        /* Generic Scroll Arrow Button */
        .track-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            cursor: pointer;
            z-index: 12;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .track-nav-btn:hover {
            background: #ffb703;
            color: #07152b;
            border-color: #ffb703;
            box-shadow: 0 4px 14px rgba(255, 183, 3, 0.45);
            transform: translateY(-50%) scale(1.08);
        }
        .track-nav-btn:active {
            transform: translateY(-50%) scale(0.95);
        }
        .track-nav-btn-dark {
            background: #0b1329;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.45);
        }
        .track-nav-btn-dark:hover {
            background: #1e293b;
            color: #ffb703;
            border-color: #ffb703;
            box-shadow: 0 4px 16px rgba(255, 183, 3, 0.45);
            transform: translateY(-50%) scale(1.08);
        }
        .track-nav-right {
            right: -8px;
        }
        .track-nav-left {
            left: 215px;
        }
        .services-nav-left {
            left: 178px;
        }
        .services-nav-right {
            right: -8px;
        }
        .tenders-nav-left {
            left: 238px;
        }
        .tenders-nav-right {
            right: -8px;
        }
        .brands-nav-left {
            left: 140px;
        }
        .brands-nav-right {
            right: 135px;
        }
        @media (max-width: 991px) {
            .brands-nav-right {
                right: -8px;
            }
        }
        @media (max-width: 768px) {
            .track-nav-btn {
                display: none;
            }
            .services-title-card, .tenders-title-card {
                min-width: 155px;
                max-width: 155px;
                padding: 16px 12px;
                min-height: 180px;
            }
            .tender-auction-card {
                min-width: 320px;
                max-width: 320px;
                min-height: 180px;
                padding: 14px 14px;
            }
            .tender-card-img {
                width: 115px;
                height: 115px;
            }
        }

        /* 3. Sponsored Partners Logo Row */
        .sponsored-partners-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            margin-bottom: 22px;
        }
        .sponsored-partners-title-box {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            white-space: nowrap;
            flex-shrink: 0;
            padding-right: 10px;
            border-right: 1px solid #f1f5f9;
        }
        .sponsored-partners-track {
            display: flex;
            align-items: center;
            gap: 20px;
            overflow-x: auto;
            flex-grow: 1;
            padding: 4px 6px;
        }
        .brand-logo-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            white-space: nowrap;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
            border-radius: 8px;
            border: 1px solid transparent;
        }
        .brand-logo-chip:hover {
            transform: translateY(-2px) scale(1.05);
            background: #f8fafc;
            border-color: #e2e8f0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        }

        /* 4. Explore by Application Carousel */
        .app-carousel-track {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            scrollbar-width: none;
            scroll-behavior: auto !important;
            padding: 4px 2px 10px 2px;
        }
        .app-carousel-track::-webkit-scrollbar {
            display: none;
        }
        .app-photo-card {
            min-width: 195px;
            flex: 1;
            height: 155px;
            border-radius: 14px;
            overflow: hidden;
            position: relative;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            flex-shrink: 0;
            display: block;
        }
        .app-photo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
        }
        .app-photo-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
            display: block;
        }
        .app-photo-card:hover img {
            transform: scale(1.06);
        }
        .app-card-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(7, 21, 43, 0.95) 0%, rgba(7, 21, 43, 0.45) 45%, rgba(0, 0, 0, 0.05) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 12px 14px;
        }
        .app-card-title {
            font-size: 13.5px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 2px;
            line-height: 1.2;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
        }
        .app-card-subtitle {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 5. Trust & Assurance Badges (Enhanced Premium Layout) */
        .trust-badges-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px;
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            overflow-x: auto;
            scrollbar-width: none;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
        }
        .trust-badges-bar::-webkit-scrollbar {
            display: none;
        }
        .trust-badge-item {
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            flex: 1;
            justify-content: center;
            padding: 2px 8px;
            transition: transform 0.2s ease;
        }
        .trust-badge-item:hover {
            transform: translateY(-2px);
        }
        .trust-badge-icon-wrap {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease;
        }
        .trust-badge-item:hover .trust-badge-icon-wrap {
            transform: scale(1.08);
        }
        .trust-badge-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 2px;
        }
        .trust-badge-subtitle {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            line-height: 1.2;
        }

        /* 6. Category Pill Scroller Strip (Enhanced 3D & Spacious Layout) */
        .categories-pill-wrapper {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px 16px 14px 16px;
            min-height: 136px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            position: relative;
            margin-bottom: 26px;
        }
        .categories-scroll-track {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: auto !important;
            padding: 4px 6px;
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
        }
        .categories-scroll-track::-webkit-scrollbar {
            display: none;
        }
        .categories-scroll-track.is-dragging {
            cursor: grabbing !important;
            scroll-behavior: auto !important;
        }
        .category-doodle-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            text-align: center;
            min-width: 104px;
            max-width: 112px;
            flex-shrink: 0;
            text-decoration: none;
            padding: 8px 6px 6px 6px;
            border-radius: 14px;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.22s ease, box-shadow 0.22s ease;
            background: transparent;
        }
        .category-doodle-card:hover {
            transform: translateY(-5px);
            background: #f8fafc;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }
        .category-doodle-icon-box {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .category-doodle-card:hover .category-doodle-icon-box {
            transform: scale(1.14);
        }
        .category-doodle-title {
            font-size: 11.5px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.25;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s ease;
        }
        .category-doodle-card:hover .category-doodle-title {
            color: #0284c7;
        }
        .cat-nav-btn {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        /* -------------------------------------------------------------
           11. ENHANCED TALL QUICK ACTION PROMO CARDS (MATCHING REFERENCE)
           ------------------------------------------------------------- */
        .quick-promo-cards-section {
            margin-bottom: 28px;
        }
        .quick-action-promo-card {
            border-radius: 16px !important;
            min-height: 205px;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            box-shadow: 0 8px 24px rgba(7, 21, 43, 0.12);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .quick-action-promo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(7, 21, 43, 0.22);
        }
        .promo-card-art-box {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 52%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }
        .promo-card-art-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            filter: drop-shadow(-4px 0 12px rgba(0, 0, 0, 0.25));
        }
        .quick-action-promo-card:hover .promo-card-art-img {
            transform: scale(1.08);
        }
        .promo-art-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, #091b3a 0%, rgba(9, 27, 58, 0.75) 25%, rgba(9, 27, 58, 0.1) 65%, transparent 100%);
            pointer-events: none;
        }
        .quick-promo-badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 2.5px 8px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 6px;
            backdrop-filter: blur(4px);
        }
        .quick-promo-btn {
            font-size: 11.5px !important;
            font-weight: 800 !important;
            letter-spacing: 0.3px;
            border-radius: 24px !important;
            padding: 7px 16px !important;
            transition: all 0.25s ease !important;
            box-shadow: 0 4px 14px rgba(255, 84, 0, 0.35);
        }
        .quick-promo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 84, 0, 0.55);
            filter: brightness(1.08);
        }

        /* -------------------------------------------------------------
           12. REDESIGNED "TRENDING NOW" SECTION & SMOOTH SCROLL EFFECTS
           ------------------------------------------------------------- */
        .trending-now-section {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 20px 22px 18px 22px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            margin-bottom: 28px;
            position: relative;
        }
        .trending-header-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .trending-fire-badge {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, #fee2e2 0%, #ffedd5 100%);
            color: #ea580c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            border: 1px solid #fed7aa;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.15);
            animation: firePulse 2s infinite ease-in-out;
        }
        @keyframes firePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }
        .trending-now-heading {
            font-size: 1.25rem;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .trending-now-subheading {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }
        .trending-view-all-btn {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 6px 16px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .trending-view-all-btn:hover {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            transform: translateX(2px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }
        .trending-view-all-btn:hover i {
            transform: translateX(3px);
        }
        .trending-view-all-btn i {
            transition: transform 0.2s ease;
        }

        /* Carousel Track & Frosted Glass Nav Arrows */
        .trending-now-scroll-track {
            display: flex;
            align-items: stretch;
            gap: 14px;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 6px 4px 10px 4px;
            scroll-behavior: smooth;
            cursor: grab;
            user-select: none;
            -webkit-user-select: none;
        }
        .trending-now-scroll-track::-webkit-scrollbar {
            display: none;
        }
        .trending-now-scroll-track.is-dragging {
            cursor: grabbing !important;
            scroll-behavior: auto !important;
        }
        .trending-now-scroll-track.is-dragging a {
            pointer-events: none;
        }

        .carousel-arrow-nav {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(8px) !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #0f172a !important;
            cursor: pointer !important;
            z-index: 10 !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .carousel-arrow-nav:hover:not(:disabled) {
            background: #0f172a !important;
            color: #ffffff !important;
            border-color: #0f172a !important;
            transform: translateY(-50%) scale(1.1) !important;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.25) !important;
        }
        .carousel-arrow-nav:disabled,
        .carousel-arrow-nav.is-disabled {
            opacity: 0.25 !important;
            pointer-events: none !important;
            cursor: not-allowed !important;
        }

        /* Dynamic Scroll Progress Bar */
        .trending-scroll-progress-wrap {
            width: 140px;
            height: 4px;
            background: #e2e8f0;
            border-radius: 10px;
            margin: 10px auto 2px;
            overflow: hidden;
            position: relative;
        }
        .trending-scroll-progress-bar {
            height: 100%;
            width: 25%;
            background: linear-gradient(90deg, #ff5400 0%, #f97316 50%, #ea580c 100%);
            border-radius: 10px;
            transition: width 0.12s ease-out;
        }

        /* Product Card Aesthetic Hover */
        .trending-product-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04) !important;
        }
        .trending-product-card:hover {
            transform: translateY(-5px);
            border-color: #cbd5e1 !important;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.1) !important;
        }
        .trending-product-card:hover .trending-card-img {
            transform: scale(1.07);
        }
        .trending-product-card:hover .trending-card-title {
            color: #2563eb;
        }
        .trending-product-card:hover .trending-card-cta {
            background: #ff5400 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(255, 84, 0, 0.35);
        }

        @media (max-width: 991px) {
            .quick-action-promo-card {
                min-height: 185px;
            }
            .trending-now-section {
                padding: 16px 14px 14px;
            }
            .carousel-arrow-nav {
                display: none !important;
            }
        }
    </style>

    <div class="row g-3 hero-section-row">
        <!-- 1. DEALS OF THE DAY (Dynamic from Admin) -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12 hero-col">
            <div class="hero-card-base hero-card-deals">
                <div>
                    <div class="hero-card-header">
                        <div class="hero-header-title">
                            <i class="bi bi-fire text-danger fs-6"></i>
                            <span>DEALS OF THE DAY</span>
                        </div>
                        <div class="hero-timer-container" title="Deal ends in">
                            <span class="hero-timer-digit" id="heroTimerHours">12</span>
                            <span class="hero-timer-sep">:</span>
                            <span class="hero-timer-digit" id="heroTimerMinutes">45</span>
                            <span class="hero-timer-sep">:</span>
                            <span class="hero-timer-digit" id="heroTimerSeconds">30</span>
                        </div>
                    </div>

                    <div class="hero-item-list">
                        @php
                            $displayedDeals = $dealsOfTheDay->take(3);
                            $defaultDealFallbacks = [
                                ['name' => 'UltraTech OPC 53 Cement 50Kg', 'price' => 395, 'mrp' => 495, 'rating' => 4.8, 'reviews' => '2.4K', 'discount' => 25, 'img' => asset('images/hero/cement.png')],
                                ['name' => 'Kajaria Vitrified Tiles 600x600', 'price' => 42, 'mrp' => 50, 'rating' => 4.7, 'reviews' => '1.8K', 'discount' => 15, 'img' => asset('images/hero/tiles.png')],
                                ['name' => 'TATA TMT Steel Bar 12mm', 'price' => 68, 'mrp' => 90, 'rating' => 4.9, 'reviews' => '3.1K', 'discount' => 21, 'img' => asset('images/hero/steel.png')],
                            ];
                        @endphp

                        @foreach($displayedDeals as $deal)
                            @php
                                $dealImg = $deal->primaryImage ? asset('storage/' . $deal->primaryImage->image_path) : asset('images/hero/cement.png');
                                $discountPct = $deal->discount_percent > 0 ? $deal->discount_percent : 20;
                                $isDealWishlisted = \App\Services\WishlistService::has($deal->id);
                            @endphp
                            <div class="hero-item-row position-relative">
                                <div class="hero-item-img-box position-relative">
                                    <span class="hero-discount-pill">{{ $discountPct }}% OFF</span>
                                    <img src="{{ $dealImg }}" alt="{{ $deal->name }}" onerror="this.onerror=null;this.src='{{ asset('images/hero/cement.png') }}';">
                                </div>
                                <div class="hero-item-details">
                                    <a href="{{ route('products.show', $deal->slug ?? $deal->id) }}" class="hero-item-name" title="{{ $deal->name }}">
                                        {{ $deal->name }}
                                    </a>
                                    <div class="hero-item-price-line">
                                        ₹{{ number_format($deal->selling_price) }}
                                        @if($deal->mrp > $deal->selling_price)
                                            <span class="hero-item-old-price">₹{{ number_format($deal->mrp) }}</span>
                                        @endif
                                    </div>
                                    <div class="hero-item-vendor">
                                        <i class="bi bi-star-fill text-warning" style="font-size: 9px;"></i>
                                        <span>{{ number_format($deal->rating > 0 ? $deal->rating : 4.8, 1) }} ({{ $deal->rating_count ?? '120+' }})</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                    <button type="button" 
                                            class="btn-wishlist-toggle {{ $isDealWishlisted ? 'active' : '' }}" 
                                            data-id="{{ $deal->id }}" 
                                            onclick="event.preventDefault(); event.stopPropagation(); toggleWishlistCard(this, {{ $deal->id }})" 
                                            title="{{ $isDealWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}" 
                                            style="width: 28px; height: 28px; border-radius: 50%; background: #ffffff; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; padding: 0;">
                                        <i class="bi {{ $isDealWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }}" style="font-size: 11.5px; color: {{ $isDealWishlisted ? '#ef4444' : '#64748b' }};"></i>
                                    </button>
                                    <button class="hero-item-action-btn" type="button" onclick="ajaxAddToCart({{ $deal->id }}, 1)" title="Add to Cart">
                                        Add <i class="bi bi-cart3"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        @if($displayedDeals->count() < 3)
                            @foreach(array_slice($defaultDealFallbacks, $displayedDeals->count()) as $fb)
                                <div class="hero-item-row position-relative">
                                    <div class="hero-item-img-box position-relative">
                                        <span class="hero-discount-pill">{{ $fb['discount'] }}% OFF</span>
                                        <img src="{{ $fb['img'] }}" alt="{{ $fb['name'] }}">
                                    </div>
                                    <div class="hero-item-details">
                                        <a href="{{ route('products.index') }}" class="hero-item-name" title="{{ $fb['name'] }}">
                                            {{ $fb['name'] }}
                                        </a>
                                        <div class="hero-item-price-line">
                                            ₹{{ number_format($fb['price']) }} <span class="hero-item-old-price">₹{{ number_format($fb['mrp']) }}</span>
                                        </div>
                                        <div class="hero-item-vendor">
                                            <i class="bi bi-star-fill text-warning" style="font-size: 9px;"></i>
                                            <span>{{ $fb['rating'] }} ({{ $fb['reviews'] }})</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('products.index') }}" class="hero-item-action-btn">
                                        Add <i class="bi bi-cart3"></i>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="hero-card-footer-strip">
                    <span><i class="bi bi-lightning-charge text-danger me-1"></i> Ends Today</span>
                    <a href="{{ route('products.index') }}" class="hero-view-link">View All Deals <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- 2. SPONSORED PRODUCTS (Horizontal Scrolling Showcase - Admin Managed) -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12 hero-col">
            <div class="hero-card-base hero-card-sponsored">
                <div>
                    <div class="hero-card-header">
                        <div class="hero-header-title">
                            <span class="hero-ad-pill"><i class="bi bi-award-fill"></i> SPONSORED</span>
                            <span style="color: #1e293b; font-size: 11.5px;">TOP PICKS</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="hero-scroll-nav-btn" onclick="scrollSponsoredTrack(-1)" title="Scroll Left">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button type="button" class="hero-scroll-nav-btn" onclick="scrollSponsoredTrack(1)" title="Scroll Right">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                            <a href="{{ route('products.index') }}" class="hero-view-link ms-1" style="font-size: 11px;">View All <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>

                    <!-- Horizontal Scrolling Products Container -->
                    <div class="hero-sponsor-hscroll" id="sponsoredScrollTrack">
                        @php
                            $curatedSponsors = [
                                ['name' => 'Jindal Panther 550D TMT Rebar 16mm', 'vendor' => 'Jindal Steel & Power', 'price' => 72, 'unit' => 'Kg', 'img' => asset('images/hero/steel.png')],
                                ['name' => 'ACC Gold Water Shield Cement 50Kg', 'vendor' => 'ACC Limited Official', 'price' => 410, 'unit' => 'Bag', 'img' => asset('images/hero/cement.png')],
                                ['name' => 'Somany Slip Shield Floor Tiles 600x600', 'vendor' => 'Somany Ceramics Ltd', 'price' => 48, 'unit' => 'Sq.ft.', 'img' => asset('images/hero/tiles.png')],
                                ['name' => 'TATA Tiscon 550D High Strength Steel', 'vendor' => 'TATA Steel B2B', 'price' => 69, 'unit' => 'Kg', 'img' => asset('images/hero/steel.png')],
                                ['name' => 'UltraTech Weather Plus Waterproofing', 'vendor' => 'UltraTech Building Solutions', 'price' => 440, 'unit' => 'Bag', 'img' => asset('images/hero/cement.png')],
                            ];
                        @endphp

                        @foreach($sponsoredProducts as $sponsorItem)
                            @php
                                $sponsorImg = $sponsorItem->primaryImage ? asset('storage/' . $sponsorItem->primaryImage->image_path) : asset('images/hero/steel.png');
                                $isSponsorWishlisted = \App\Services\WishlistService::has($sponsorItem->id);
                            @endphp
                            <div class="hero-sponsor-card position-relative">
                                <div>
                                    <div class="hero-sponsor-card-img position-relative">
                                        <span class="hero-ad-tag-badge">AD</span>
                                        <button type="button" 
                                                class="btn-wishlist-toggle position-absolute top-0 end-0 m-1.5 {{ $isSponsorWishlisted ? 'active' : '' }}" 
                                                data-id="{{ $sponsorItem->id }}" 
                                                onclick="event.preventDefault(); event.stopPropagation(); toggleWishlistCard(this, {{ $sponsorItem->id }})" 
                                                title="{{ $isSponsorWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}" 
                                                style="width: 26px; height: 26px; border-radius: 50%; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; z-index: 3;">
                                            <i class="bi {{ $isSponsorWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }}" style="font-size: 11.5px; color: {{ $isSponsorWishlisted ? '#ef4444' : '#64748b' }};"></i>
                                        </button>
                                        <img src="{{ $sponsorImg }}" alt="{{ $sponsorItem->name }}" onerror="this.onerror=null;this.src='{{ asset('images/hero/steel.png') }}';">
                                    </div>
                                    <div class="hero-item-vendor mb-1">
                                        <i class="bi bi-patch-check-fill text-primary" style="font-size: 10px;"></i>
                                        <span class="text-truncate">{{ $sponsorItem->vendor->company_name ?? $sponsorItem->brand->name ?? 'Verified Partner' }}</span>
                                    </div>
                                    <a href="{{ route('products.show', $sponsorItem->slug ?? $sponsorItem->id) }}" class="hero-item-name mb-1" style="white-space: normal; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.3; height: 32px;" title="{{ $sponsorItem->name }}">
                                        {{ $sponsorItem->name }}
                                    </a>
                                </div>
                                <div>
                                    <div class="hero-item-price-line mb-1.5">
                                        ₹{{ number_format($sponsorItem->selling_price) }}
                                        <span class="hero-item-unit">/ {{ $sponsorItem->unit ?? 'unit' }}</span>
                                    </div>
                                    <div class="d-flex gap-1 pt-1.5 border-top" style="border-color: #f1f5f9;">
                                        <button type="button" class="btn btn-sm flex-fill py-1 fw-semibold btn-quick-view-modal garudo-btn-quickview"
                                            data-id="{{ $sponsorItem->id }}"
                                            data-name="{{ $sponsorItem->name }}"
                                            data-price="₹{{ number_format($sponsorItem->selling_price) }}"
                                            data-unit="{{ $sponsorItem->unit ?: 'Piece' }}"
                                            data-mrp=""
                                            data-image="{{ $sponsorImg }}"
                                            data-vendor="{{ $sponsorItem->vendor->company_name ?? ($sponsorItem->brand->name ?? 'Verified Partner') }}"
                                            data-rating="{{ number_format($sponsorItem->rating ?: 4.8, 1) }}"
                                            data-url="{{ route('products.show', $sponsorItem->slug ?? $sponsorItem->id) }}"
                                            style="font-size: 10px; background: #ffffff; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 4px;">
                                            Quick View
                                        </button>
                                        <button type="button" onclick="ajaxAddToCart({{ $sponsorItem->id }}, 1)" 
                                            class="btn btn-sm flex-fill py-1 fw-semibold d-flex align-items-center justify-content-center garudo-btn-addcart" 
                                            style="font-size: 10px; background: #ffffff; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 4px;"
                                            title="Add to Cart">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($sponsoredProducts->count() < 4)
                            @foreach(array_slice($curatedSponsors, $sponsoredProducts->count()) as $cs)
                                <div class="hero-sponsor-card">
                                    <div>
                                        <div class="hero-sponsor-card-img">
                                            <span class="hero-ad-tag-badge">AD</span>
                                            <img src="{{ $cs['img'] }}" alt="{{ $cs['name'] }}">
                                        </div>
                                        <div class="hero-item-vendor mb-1">
                                            <i class="bi bi-patch-check-fill text-primary" style="font-size: 10px;"></i>
                                            <span class="text-truncate">{{ $cs['vendor'] }}</span>
                                        </div>
                                        <a href="{{ route('products.index') }}" class="hero-item-name mb-1" style="white-space: normal; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.3; height: 32px;" title="{{ $cs['name'] }}">
                                            {{ $cs['name'] }}
                                        </a>
                                    </div>
                                    <div>
                                        <div class="hero-item-price-line mb-1.5">
                                            ₹{{ number_format($cs['price']) }}
                                            <span class="hero-item-unit">/ {{ $cs['unit'] }}</span>
                                        </div>
                                        <div class="d-flex gap-1 pt-1.5 border-top" style="border-color: #f1f5f9;">
                                            <a href="{{ route('products.index') }}" class="btn btn-sm flex-fill py-1 fw-semibold text-center text-decoration-none garudo-btn-quickview" style="font-size: 10px; background: #ffffff; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 4px;">
                                                Quick View
                                            </a>
                                            <a href="{{ route('products.index') }}" class="btn btn-sm flex-fill py-1 fw-semibold text-center text-decoration-none garudo-btn-addcart" style="font-size: 10px; background: #ffffff; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 4px;">
                                                Add to Cart
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="hero-card-footer-strip">
                    <span><i class="bi bi-shield-check text-success me-1"></i> Verified Suppliers</span>
                    <a href="{{ route('products.index') }}" class="hero-view-link">View All <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- 3. BULK ORDER SPECIAL (Controllable via Admin: Mega Banners & Hero Ads) -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12 hero-col">
            <div class="hero-card-base hero-card-bulk">
                <div>
                    <div class="hero-card-header-dark">
                        <span class="hero-dark-badge"><i class="bi bi-boxes text-warning me-1"></i> {{ $heroBulkCard['badge'] ?? 'BULK ORDER SPECIAL' }}</span>
                        <span class="text-white-50 small" style="font-size: 10px;">Min. ₹50K+</span>
                    </div>

                    <h2 class="hero-dark-title">{{ $heroBulkCard['title'] ?? 'BULK ORDER SPECIAL' }}</h2>
                    <div class="hero-dark-subhead">{{ $heroBulkCard['subtitle'] ?? 'Save up to 40% on truckload orders of cement, steel, tiles & more.' }}</div>

                    <div class="hero-feature-group">
                        @if(!empty($heroBulkCard['point1']))
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon"><i class="bi bi-check-circle-fill text-warning"></i></div>
                            <span>{{ $heroBulkCard['point1'] }}</span>
                        </div>
                        @endif
                        @if(!empty($heroBulkCard['point2']))
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon"><i class="bi bi-check-circle-fill text-warning"></i></div>
                            <span>{{ $heroBulkCard['point2'] }}</span>
                        </div>
                        @endif
                        @if(!empty($heroBulkCard['point3']))
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon"><i class="bi bi-check-circle-fill text-warning"></i></div>
                            <span>{{ $heroBulkCard['point3'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <a href="{{ $heroBulkCard['button_url'] ?? route('customer.quotes') }}" class="hero-cta-btn">
                        {{ $heroBulkCard['button_text'] ?? 'Request Wholesale Quote →' }}
                    </a>
                </div>

                <img src="{{ $heroBulkCard['image'] ?? asset('images/hero/bulk_boxes.png') }}" class="hero-dark-bg-img" alt="Bulk Warehouse Freight">
            </div>
        </div>

        <!-- 4. AUCTION ZONE (Controllable via Admin: Mega Banners & Hero Ads) -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12 hero-col">
            <div class="hero-card-base hero-card-auction">
                <div>
                    <div class="hero-card-header-dark">
                        <span class="hero-dark-badge hero-badge-live">
                            <span class="hero-live-dot"></span> {{ $heroAuctionCard['badge'] ?? 'AUCTION ZONE' }}
                        </span>
                        <span class="text-warning small fw-bold" style="font-size: 10px;">Up To 70% OFF</span>
                    </div>

                    <h2 class="hero-dark-title">{{ $heroAuctionCard['title'] ?? 'AUCTION ZONE' }}</h2>
                    <div class="hero-dark-subhead">{{ $heroAuctionCard['subtitle'] ?? 'Bid on surplus inventory, clearance lots & factory overruns at up to 70% off.' }}</div>

                    <div class="hero-feature-group" style="margin-top: 10px;">
                        @if(!empty($heroAuctionCard['point1']))
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon hero-feature-icon-amber"><i class="bi bi-broadcast"></i></div>
                            <span>{{ $heroAuctionCard['point1'] }}</span>
                        </div>
                        @endif
                        @if(!empty($heroAuctionCard['point2']))
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon hero-feature-icon-amber"><i class="bi bi-patch-check"></i></div>
                            <span>{{ $heroAuctionCard['point2'] }}</span>
                        </div>
                        @endif
                        @if(!empty($heroAuctionCard['point3']))
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon hero-feature-icon-amber"><i class="bi bi-truck"></i></div>
                            <span>{{ $heroAuctionCard['point3'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <a href="{{ $heroAuctionCard['button_url'] ?? route('products.index') }}" class="hero-cta-btn hero-cta-amber">
                        {{ $heroAuctionCard['button_text'] ?? 'Enter Auction Zone →' }}
                    </a>
                </div>

                <img src="{{ $heroAuctionCard['image'] ?? asset('images/hero/auction_gavel.png') }}" class="hero-dark-bg-img" alt="Auction Gavel">
            </div>
        </div>
    </div>

    <!-- 1. FIND SERVICES & PROFESSIONALS (Exact Match to Image 1) -->
    <div class="landing-section-wrapper garudo-reveal">
        <div class="services-strip-container">
            <!-- Left Fixed Title Card -->
            <div class="services-title-card">
                <div>
                    <h6>Find Services &<br>Professionals</h6>
                </div>
                <div>
                    <a href="{{ route('products.index', ['category' => 'services']) }}" class="btn btn-dark btn-sm rounded-pill fw-semibold px-3 py-1.5" style="background: #07152b; font-size: 11px; white-space: nowrap;">
                        View All Services →
                    </a>
                </div>
            </div>

            <!-- Left Navigation Button -->
            <button class="track-nav-btn services-nav-left" id="servicesScrollLeft" title="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <!-- Horizontal Scroll Services Track -->
            <div class="services-scroll-track" id="servicesScrollTrack">
                <!-- 1. Contractors -->
                <a href="{{ route('products.index', ['filter' => 'contractors']) }}" class="service-mini-card">
                    <img src="{{ asset('images/landing/service_contractor.jpg') }}" class="service-mini-img" alt="Contractors">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Contractors</div>
                        <div class="service-mini-link">Find Experts →</div>
                    </div>
                </a>

                <!-- 2. Architects -->
                <a href="{{ route('products.index', ['filter' => 'architects']) }}" class="service-mini-card">
                    <img src="{{ asset('images/landing/service_architect.jpg') }}" class="service-mini-img" alt="Architects">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Architects</div>
                        <div class="service-mini-link">Get Designs →</div>
                    </div>
                </a>

                <!-- 3. Interior Designers -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="{{ asset('images/landing/service_interior.jpg') }}" class="service-mini-img" alt="Interior Designers">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Interior Designers</div>
                        <div class="service-mini-link">Design Spaces →</div>
                    </div>
                </a>

                <!-- 4. Civil Engineers -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="{{ asset('images/landing/service_engineer.jpg') }}" class="service-mini-img" alt="Civil Engineers">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Civil Engineers</div>
                        <div class="service-mini-link">Plan & Build →</div>
                    </div>
                </a>

                <!-- 5. Labor & Workers -->
                <a href="{{ route('products.index', ['filter' => 'labour']) }}" class="service-mini-card">
                    <img src="{{ asset('images/landing/service_labor.jpg') }}" class="service-mini-img" alt="Labor & Workers">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Labor & Workers</div>
                        <div class="service-mini-link">Skilled Workforce →</div>
                    </div>
                </a>

                <!-- 6. Installation Services / Logistics -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="{{ asset('images/landing/service_installation.jpg') }}" class="service-mini-img" alt="Installation Services">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Installation</div>
                        <div class="service-mini-link">On-time Delivery →</div>
                    </div>
                </a>

                <!-- 7. Project Consultants -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="{{ asset('images/landing/service_consultant.jpg') }}" class="service-mini-img" alt="Project Consultants">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Project Consultants</div>
                        <div class="service-mini-link">Expert Guidance →</div>
                    </div>
                </a>

                <!-- 8. Structural Engineers -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Structural Engineers">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Structural Engineers</div>
                        <div class="service-mini-link">Structure & Safety →</div>
                    </div>
                </a>

                <!-- 9. MEP Consultants -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="MEP Consultants">
                    <div class="service-mini-info">
                        <div class="service-mini-name">MEP Consultants</div>
                        <div class="service-mini-link">HVAC & Plumbing →</div>
                    </div>
                </a>

                <!-- 10. Landscape Architects -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Landscape Architects">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Landscape Architects</div>
                        <div class="service-mini-link">Greens & Outdoors →</div>
                    </div>
                </a>

                <!-- 11. Waterproofing Experts -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Waterproofing Experts">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Waterproofing</div>
                        <div class="service-mini-link">Leak-Proof Tech →</div>
                    </div>
                </a>

                <!-- 12. Vastu Consultants -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Vastu Consultants">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Vastu Consultants</div>
                        <div class="service-mini-link">Layouts & Harmony →</div>
                    </div>
                </a>

                <!-- 13. Land Surveyors -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Land Surveyors">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Land Surveyors</div>
                        <div class="service-mini-link">3D GPS Survey →</div>
                    </div>
                </a>

                <!-- 14. Solar EPC Contractors -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1509391365360-2e959784a276?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Solar EPC Contractors">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Solar EPC</div>
                        <div class="service-mini-link">Rooftop Solar →</div>
                    </div>
                </a>

                <!-- 15. Painting Contractors -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Painting Contractors">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Painting & Coat</div>
                        <div class="service-mini-link">Colors & Finish →</div>
                    </div>
                </a>

                <!-- 16. Heavy Equipment Operators -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Heavy Equipment Operators">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Heavy Equipment</div>
                        <div class="service-mini-link">Cranes & Diggers →</div>
                    </div>
                </a>

                <!-- 17. Demolition Specialists -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="Demolition Specialists">
                    <div class="service-mini-info">
                        <div class="service-mini-name">Demolition</div>
                        <div class="service-mini-link">Safe Demolition →</div>
                    </div>
                </a>

                <!-- 18. BIM & 3D Modelers -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="service-mini-card">
                    <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?w=150&auto=format&fit=crop&q=80" class="service-mini-img" alt="BIM & 3D Modelers">
                    <div class="service-mini-info">
                        <div class="service-mini-name">BIM & 3D Modeling</div>
                        <div class="service-mini-link">3D Coordination →</div>
                    </div>
                </a>
            </div>

            <!-- Right Navigation Button -->
            <button class="track-nav-btn services-nav-right" id="servicesScrollRight" title="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- 2. LIVE TENDERS & AUCTIONS (Exact Match to Image 1) -->
    <div class="landing-section-wrapper garudo-reveal">
        <div class="tenders-strip-container">
            <!-- Left Fixed Dark Navy Banner -->
            <div class="tenders-title-card">
                <div>
                    <h5>Live Tenders & Auctions</h5>
                    <p>Bid on Projects & Save Big</p>
                </div>
                <div>
                    <a href="{{ route('customer.quotes') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 11px; width: fit-content; border-color: rgba(255,255,255,0.35);">
                        View All →
                    </a>
                </div>
            </div>

            <!-- Left Navigation Button -->
            <button class="track-nav-btn track-nav-btn-dark tenders-nav-left" id="tendersScrollLeft" title="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <!-- Horizontal Scroll Tenders & Auctions Track -->
            <div class="tenders-scroll-track" id="tendersScrollTrack">
                <!-- Card 1: Commercial Building Tender -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-tender"><span class="live-pulse-dot-green"></span> TENDER</span>
                            <div class="tender-card-title">Construction of Commercial Building at Pune</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Maharashtra, India</div>
                            <div class="tender-card-meta">Deadline: <span class="text-dark fw-semibold">25 May 2026</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Est. Value: <span class="text-primary fw-bold">₹2.45 Cr</span></div>
                            <a href="{{ route('customer.quotes') }}" class="tender-card-btn tender-card-btn-primary">View Details</a>
                        </div>
                    </div>
                    <img src="{{ asset('images/landing/tender_commercial.jpg') }}" class="tender-card-img" alt="Commercial Building">
                </div>

                <!-- Card 2: Steel & Rebars Mega Auction -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-auction"><span class="live-pulse-dot"></span> AUCTION</span>
                            <div class="tender-card-title">Surplus Steel & Rebars Mega Auction</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Pan India</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold dynamic-auction-timer" data-countdown-seconds="45930">12h 45m 30s</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Starting Bid: <span class="text-dark fw-bold">₹2.15 Lakh</span></div>
                            <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="tender-card-btn tender-card-btn-purple">Bid Now</a>
                        </div>
                    </div>
                    <img src="{{ asset('images/landing/auction_steel.jpg') }}" class="tender-card-img" alt="Steel Rebars">
                </div>

                <!-- Card 3: Govt School Building Tender -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-tender"><span class="live-pulse-dot-green"></span> TENDER</span>
                            <div class="tender-card-title">Govt. School Building Construction Project</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Rajasthan, India</div>
                            <div class="tender-card-meta">Deadline: <span class="text-dark fw-semibold">30 May 2026</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Est. Value: <span class="text-primary fw-bold">₹1.85 Cr</span></div>
                            <a href="{{ route('customer.quotes') }}" class="tender-card-btn tender-card-btn-primary">View Details</a>
                        </div>
                    </div>
                    <img src="{{ asset('images/landing/tender_school.jpg') }}" class="tender-card-img" alt="School Building">
                </div>

                <!-- Card 4: Excavator & Heavy Equipment Auction -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-auction"><span class="live-pulse-dot"></span> AUCTION</span>
                            <div class="tender-card-title">Excavator & Heavy Equipment Auction</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Pan India</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold dynamic-auction-timer" data-countdown-seconds="224420">2d 14h 20m</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Starting Bid: <span class="text-dark fw-bold">₹8.75 Lakh</span></div>
                            <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="tender-card-btn tender-card-btn-purple">Bid Now</a>
                        </div>
                    </div>
                    <img src="{{ asset('images/landing/auction_excavator.jpg') }}" class="tender-card-img" alt="Excavator Equipment">
                </div>

                <!-- Card 5: High-Rise Luxury Residential Tower Tender -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-tender"><span class="live-pulse-dot-green"></span> TENDER</span>
                            <div class="tender-card-title">High-Rise Residential Tower (Phase 2)</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Bengaluru, Karnataka</div>
                            <div class="tender-card-meta">Deadline: <span class="text-dark fw-semibold">18 Jun 2026</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Est. Value: <span class="text-primary fw-bold">₹5.20 Cr</span></div>
                            <a href="{{ route('customer.quotes') }}" class="tender-card-btn tender-card-btn-primary">View Details</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Residential Tower">
                </div>

                <!-- Card 6: Bulk Cement Grade 53 Bags Auction -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-auction"><span class="live-pulse-dot"></span> AUCTION</span>
                            <div class="tender-card-title">Bulk Grade 53 Cement Bags Lot (5,000 MT)</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Gujarat / Pan India</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold dynamic-auction-timer" data-countdown-seconds="19334">05h 22m 14s</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Starting Bid: <span class="text-dark fw-bold">₹18.50 Lakh</span></div>
                            <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="tender-card-btn tender-card-btn-purple">Bid Now</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Cement Auction">
                </div>

                <!-- Card 7: Highway Culvert Drainage Tender -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-tender"><span class="live-pulse-dot-green"></span> TENDER</span>
                            <div class="tender-card-title">Highway Drainage & Culvert Network</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> NH-48, Gujarat</div>
                            <div class="tender-card-meta">Deadline: <span class="text-dark fw-semibold">12 Jun 2026</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Est. Value: <span class="text-primary fw-bold">₹3.60 Cr</span></div>
                            <a href="{{ route('customer.quotes') }}" class="tender-card-btn tender-card-btn-primary">View Details</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1517646287270-a5a9ca602e5c?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Highway Tender">
                </div>

                <!-- Card 8: Tower Cranes Lot Auction -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-auction"><span class="live-pulse-dot"></span> AUCTION</span>
                            <div class="tender-card-title">Tower Cranes & Hydraulic Scaffolding (2 Units)</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Delhi NCR</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold dynamic-auction-timer" data-countdown-seconds="116100">1d 08h 15m</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Starting Bid: <span class="text-dark fw-bold">₹14.80 Lakh</span></div>
                            <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="tender-card-btn tender-card-btn-purple">Bid Now</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1508873696983-2df5293cb32f?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Tower Crane">
                </div>

                <!-- Card 9: Smart City Municipal Solar Grid Tender -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-tender"><span class="live-pulse-dot-green"></span> TENDER</span>
                            <div class="tender-card-title">Smart City Municipal Solar & LED Grid</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Indore, MP</div>
                            <div class="tender-card-meta">Deadline: <span class="text-dark fw-semibold">28 Jun 2026</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Est. Value: <span class="text-primary fw-bold">₹1.45 Cr</span></div>
                            <a href="{{ route('customer.quotes') }}" class="tender-card-btn tender-card-btn-primary">View Details</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1509391365360-2e959784a276?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Smart City Solar">
                </div>

                <!-- Card 10: Vitrified Tiles Surplus Auction -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-auction"><span class="live-pulse-dot"></span> AUCTION</span>
                            <div class="tender-card-title">Italian Vitrified Tiles & Slabs Lot</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Morbi, Gujarat</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold dynamic-auction-timer" data-countdown-seconds="34810">09h 40m 10s</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Starting Bid: <span class="text-dark fw-bold">₹4.90 Lakh</span></div>
                            <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="tender-card-btn tender-card-btn-purple">Bid Now</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Vitrified Tiles">
                </div>

                <!-- Card 11: Multi-Specialty Hospital Works Tender -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-tender"><span class="live-pulse-dot-green"></span> TENDER</span>
                            <div class="tender-card-title">Multi-Specialty Hospital Civil & MEP Works</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Hyderabad, Telangana</div>
                            <div class="tender-card-meta">Deadline: <span class="text-dark fw-semibold">05 Jul 2026</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Est. Value: <span class="text-primary fw-bold">₹7.80 Cr</span></div>
                            <a href="{{ route('customer.quotes') }}" class="tender-card-btn tender-card-btn-primary">View Details</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Hospital Civil Works">
                </div>

                <!-- Card 12: Copper Cables & Switchgear Auction -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-auction"><span class="live-pulse-dot"></span> AUCTION</span>
                            <div class="tender-card-title">Industrial Copper Cables & Switchgear</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Chennai, TN</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold dynamic-auction-timer" data-countdown-seconds="58365">16h 12m 45s</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Starting Bid: <span class="text-dark fw-bold">₹6.30 Lakh</span></div>
                            <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="tender-card-btn tender-card-btn-purple">Bid Now</a>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=240&auto=format&fit=crop&q=80" class="tender-card-img" alt="Copper Cables">
                </div>
            </div>

            <!-- Right Navigation Button -->
            <button class="track-nav-btn track-nav-btn-dark tenders-nav-right" id="tendersScrollRight" title="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- ═══ Tender Detail Popup Modal (populated dynamically via JS) ═══ -->
    <div class="tender-modal-overlay" id="tenderModalOverlay">
        <div class="tender-modal-card" id="tenderModalCard">
            <div class="tender-modal-header">
                <img id="tenderModalImg" class="tender-modal-header-img" src="" alt="">
                <div class="tender-modal-header-gradient"></div>
                <button class="tender-modal-close" id="tenderModalClose" title="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="tender-modal-header-content">
                    <span class="tender-modal-badge" id="tenderModalBadge"></span>
                    <div class="tender-modal-title" id="tenderModalTitle"></div>
                </div>
            </div>
            <div class="tender-modal-body">
                <div class="tender-modal-info-grid" id="tenderModalInfoGrid">
                    <!-- Populated dynamically -->
                </div>
                <div class="tender-modal-desc" id="tenderModalDesc"></div>
                <div class="tender-modal-tags" id="tenderModalTags"></div>
            </div>
            <div class="tender-modal-footer" id="tenderModalFooter">
                <!-- Populated dynamically -->
            </div>
        </div>
    </div>

    <!-- 3. SPONSORED PARTNERS BRAND LOGO STRIP -->
    <div class="sponsored-partners-container garudo-reveal">
        <div class="sponsored-partners-title-box" style="flex-shrink:0; display:flex; flex-direction:column; justify-content:center;">
            <span class="fw-bold text-dark" style="font-size: 13px; line-height:1.2;">Trusted by 10,000+</span>
            <span class="text-muted" style="font-size: 10.5px;">Contractors, Builders &amp; Businesses</span>
            <div class="text-warning mt-1" style="font-size:11px;"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i> <span class="text-dark fw-bold" style="font-size:10px;">4.8</span> <span class="text-muted" style="font-size:10px;">(12,540 Reviews)</span></div>
        </div>

        <!-- Left Navigation Button -->
        <button class="track-nav-btn brands-nav-left" id="brandsScrollLeft" title="Scroll left" style="width: 28px; height: 28px; font-size: 11px;">
            <i class="bi bi-chevron-left"></i>
        </button>

        <!-- Horizontal Scroll Brand Track -->
        <div class="sponsored-partners-track" id="brandsScrollTrack">
            <!-- 1. UltraTech -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="UltraTech Cement">
                <span style="background: #ffcc00; color: #000; font-weight: 900; font-family: Impact, Arial Black, sans-serif; font-size: 14px; padding: 2px 7px; border-radius: 3px; letter-spacing: 0.5px; line-height: 1.1; display: inline-block; text-align: center;">
                    UltraTech<br><span style="font-size: 7px; font-family: sans-serif; font-weight: 800; letter-spacing: 1px; display: block; margin-top: -2px;">CEMENT</span>
                </span>
            </a>

            <!-- 2. ACC -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="ACC Cement">
                <span style="color: #dc2626; font-size: 20px; font-weight: 900; font-family: Arial Black, sans-serif; letter-spacing: -0.5px;">ACC</span>
            </a>

            <!-- 3. Ambuja -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Ambuja Cement">
                <span style="color: #034694; font-weight: 900; line-height: 1; text-align: center; display: inline-block;">
                    <span style="font-size: 16px; letter-spacing: -0.3px;">Ambuja</span><br>
                    <span style="font-size: 9px; font-weight: 700; letter-spacing: 0.8px;">Cement</span>
                </span>
            </a>

            <!-- 4. TATA TISCON -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="TATA TISCON">
                <span style="font-weight: 900; line-height: 1; text-align: center; display: inline-block;">
                    <span style="font-size: 13px; color: #0284c7;">TATA<span style="color: #ea580c; font-size: 11px; margin-left: 1px;">⌂</span></span><br>
                    <span style="font-size: 11px; color: #0f172a; letter-spacing: 0.5px;">TISCON</span><br>
                    <span style="font-size: 6.5px; color: #ea580c; font-weight: 800; letter-spacing: 0.8px; display: block; margin-top: 1px;">JOY OF BUILDING</span>
                </span>
            </a>

            <!-- 5. JSW Cement -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="JSW Cement">
                <span style="color: #0284c7; font-size: 15px; font-weight: 900; font-style: italic;">
                    JSW<span style="font-size: 10px; color: #64748b; font-style: normal; font-weight: 700; margin-left: 2px;">Cement</span>
                </span>
            </a>

            <!-- 6. Kajaria -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Kajaria Tiles">
                <span style="color: #07152b; font-size: 16px; font-weight: 900; font-family: 'Times New Roman', serif; letter-spacing: 0.5px;">Kajaria</span>
            </a>

            <!-- 7. SOMANY -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="SOMANY Tiles">
                <span style="color: #dc2626; font-weight: 900; line-height: 1; display: inline-block; text-align: center;">
                    <span style="font-size: 14px; letter-spacing: 0.5px;">SOMANY<span style="color: #0284c7;">?</span></span><br>
                    <span style="font-size: 7.5px; color: #64748b; font-weight: 600; letter-spacing: 0.5px;">Tiles | Bathware</span>
                </span>
            </a>

            <!-- 8. HAVELLS -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="HAVELLS">
                <span style="color: #dc2626; font-size: 13px; font-weight: 900; letter-spacing: 0.8px;">
                    <i class="bi bi-fire text-danger me-0.5"></i>HAVELLS
                </span>
            </a>

            <!-- 9. Legrand -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="legrand">
                <span style="color: #0f172a; font-size: 14.5px; font-weight: 800; font-family: sans-serif; letter-spacing: -0.2px;">
                    <span style="color: #dc2626; font-size: 11px; margin-right: 2px;">■</span>legrand<sup style="font-size: 8px;">®</sup>
                </span>
            </a>

            <!-- 10. ASTRAL PIPES -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="ASTRAL PIPES">
                <span style="color: #0284c7; font-weight: 900; line-height: 1; text-align: center; display: inline-block;">
                    <span style="font-size: 13px; letter-spacing: 0.5px;"><i class="bi bi-triangle-half me-0.5"></i>ASTRAL</span><br>
                    <span style="font-size: 8px; letter-spacing: 1.5px; color: #0369a1;">PIPES</span>
                </span>
            </a>

            <!-- 11. Asian Paints -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Asian Paints">
                <span style="color: #dc2626; font-weight: 900; font-size: 14px; letter-spacing: -0.2px;">
                    <span style="color: #f59e0b;">●</span> asian<span style="color: #1e293b;">paints</span>
                </span>
            </a>

            <!-- 12. Berger Paints -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Berger Paints">
                <span style="color: #059669; font-weight: 900; font-size: 14px; letter-spacing: 0.2px;">
                    <i class="bi bi-brush text-danger me-0.5"></i>Berger
                </span>
            </a>

            <!-- 13. Supreme -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Supreme Piping Solutions">
                <span style="color: #dc2626; font-weight: 900; font-size: 14.5px; font-style: italic; letter-spacing: 0.5px;">
                    Supreme
                </span>
            </a>

            <!-- 14. Finolex -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Finolex Cables">
                <span style="color: #0284c7; font-weight: 900; font-size: 14px; letter-spacing: 0.3px;">
                    Fin<span style="color: #ea580c;">o</span>lex
                </span>
            </a>

            <!-- 15. Polycab -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Polycab Wires">
                <span style="color: #dc2626; font-weight: 900; font-size: 14px; font-family: sans-serif; letter-spacing: 0.3px;">
                    POLYCAB
                </span>
            </a>

            <!-- 16. Jaquar -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Jaquar Bathware">
                <span style="color: #0f172a; font-weight: 800; font-size: 14px; letter-spacing: 2px; text-transform: uppercase;">
                    Jaquar
                </span>
            </a>

            <!-- 17. Saint-Gobain -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Saint-Gobain">
                <span style="color: #0284c7; font-weight: 900; font-size: 13px; letter-spacing: 0.2px;">
                    SAINT-GOBAIN
                </span>
            </a>

            <!-- 18. Jindal Panther -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Jindal Panther TMT">
                <span style="color: #0f172a; font-weight: 900; font-size: 13px; letter-spacing: 0.4px;">
                    JINDAL <span style="color: #ea580c;">PANTHER</span>
                </span>
            </a>

            <!-- 19. Dr. Fixit -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Dr. Fixit Pidilite">
                <span style="background: #facc15; color: #000; font-weight: 900; font-size: 11px; padding: 2px 6px; border-radius: 4px;">
                    Dr.Fixit
                </span>
            </a>

            <!-- 20. Schneider Electric -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Schneider Electric">
                <span style="color: #16a34a; font-weight: 800; font-size: 13px; letter-spacing: -0.2px;">
                    Schneider<span style="color: #0f172a; font-weight: 400; font-size: 11px;">Electric</span>
                </span>
            </a>

            <!-- 21. Godrej Locks -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Godrej Architectural Hardware">
                <span style="color: #dc2626; font-weight: 900; font-size: 14px; font-family: cursive, serif;">
                    Godrej
                </span>
            </a>

            <!-- 22. Cera Sanitaryware -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="CERA Sanitaryware">
                <span style="color: #0284c7; font-weight: 900; font-size: 14px; letter-spacing: 1px;">
                    CERA
                </span>
            </a>

            <!-- 23. Ashirvad Pipes -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Ashirvad Pipes">
                <span style="color: #ea580c; font-weight: 900; font-size: 13px; letter-spacing: 0.3px;">
                    ashirvad
                </span>
            </a>

            <!-- 24. Kamdhenu Steel -->
            <a href="{{ route('brands.index') }}" class="brand-logo-chip" title="Kamdhenu Steel">
                <span style="color: #dc2626; font-weight: 900; font-size: 13px; letter-spacing: 0.5px;">
                    KAMDHENU
                </span>
            </a>
        </div>

        <!-- Right Navigation Button -->
        <button class="track-nav-btn brands-nav-right" id="brandsScrollRight" title="Scroll right" style="width: 28px; height: 28px; font-size: 11px;">
            <i class="bi bi-chevron-right"></i>
        </button>

        <!-- View All Brands Button -->
        <a href="{{ route('brands.index') }}" class="btn btn-sm fw-semibold text-nowrap ms-2 flex-shrink-0" style="background: #f1f5f9; color: #0f172a; border: 1px solid #e2e8f0; font-size: 11.5px; padding: 5px 12px; border-radius: 6px; white-space: nowrap;">
            View All Brands <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <!-- 4. EXPLORE BY APPLICATION (Exact Match to Image 1 with Generated Photos) -->
    <div class="landing-section-wrapper garudo-reveal">
        <h5 class="fw-bold text-dark mb-3" style="font-size: 16px; letter-spacing: 0.3px; text-transform: uppercase;">EXPLORE BY APPLICATION</h5>
        <div class="position-relative">
            <!-- Left Navigation Button -->
            <button class="track-nav-btn apps-nav-left" id="appScrollLeft" style="left: -8px;" title="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="app-carousel-track" id="appCarouselTrack">
                <!-- 1. Home Construction -->
                <a href="{{ route('products.index') }}" class="app-photo-card">
                    <img src="{{ asset('images/landing/app_home.jpg') }}" alt="Home Construction">
                    <div class="app-card-gradient">
                        <div class="app-card-title">Home Construction</div>
                        <div class="app-card-subtitle">Everything for your dream home</div>
                    </div>
                </a>

                <!-- 2. Interior Design -->
                <a href="{{ route('products.index', ['category' => 'services']) }}" class="app-photo-card">
                    <img src="{{ asset('images/landing/app_interior.jpg') }}" alt="Interior Design">
                    <div class="app-card-gradient">
                        <div class="app-card-title">Interior Design</div>
                        <div class="app-card-subtitle">Beautify your living space</div>
                    </div>
                </a>

                <!-- 3. Exterior Elevation -->
                <a href="{{ route('products.index') }}" class="app-photo-card">
                    <img src="{{ asset('images/landing/app_exterior.jpg') }}" alt="Exterior Elevation">
                    <div class="app-card-gradient">
                        <div class="app-card-title">Exterior Elevation</div>
                        <div class="app-card-subtitle">Strong & stunning exteriors</div>
                    </div>
                </a>

                <!-- 4. Commercial Projects -->
                <a href="{{ route('products.index', ['filter' => 'projects']) }}" class="app-photo-card">
                    <img src="{{ asset('images/landing/app_commercial.jpg') }}" alt="Commercial Projects">
                    <div class="app-card-gradient">
                        <div class="app-card-title">Commercial Projects</div>
                        <div class="app-card-subtitle">Reliable materials for big projects</div>
                    </div>
                </a>

                <!-- 5. Renovation -->
                <a href="{{ route('products.index') }}" class="app-photo-card">
                    <img src="{{ asset('images/landing/app_renovation.jpg') }}" alt="Renovation">
                    <div class="app-card-gradient">
                        <div class="app-card-title">Renovation</div>
                        <div class="app-card-subtitle">Upgrade & renovate easily</div>
                    </div>
                </a>

                <!-- 6. Smart Building Solutions -->
                <a href="{{ route('products.index') }}" class="app-photo-card">
                    <img src="{{ asset('images/landing/app_smart.jpg') }}" alt="Smart Building Solutions">
                    <div class="app-card-gradient">
                        <div class="app-card-title">Smart Building Solutions</div>
                        <div class="app-card-subtitle">Modern solutions for tomorrow</div>
                    </div>
                </a>
            </div>

            <!-- Scroll Right Button -->
            <button class="track-nav-btn track-nav-right apps-nav-right" id="appScrollRight" title="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- 5. TRUST & ASSURANCE BADGES (Enhanced Premium Layout) -->
    <div class="trust-badges-bar">
        <!-- 1. Verified Sellers -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon-wrap" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb;">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <div class="trust-badge-title">Verified Sellers</div>
                <div class="trust-badge-subtitle">Trusted & verified vendors</div>
            </div>
        </div>
        <div class="vr opacity-20 d-none d-lg-block" style="height: 34px; background-color: #cbd5e1;"></div>

        <!-- 2. 100% Genuine Products -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon-wrap" style="background: linear-gradient(135deg, #fefce8 0%, #fef08a 100%); color: #d97706;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="trust-badge-title">100% Genuine Products</div>
                <div class="trust-badge-subtitle">Quality you can trust</div>
            </div>
        </div>
        <div class="vr opacity-20 d-none d-lg-block" style="height: 34px; background-color: #cbd5e1;"></div>

        <!-- 3. Best Price Guarantee -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon-wrap" style="background: linear-gradient(135deg, #fff1f2 0%, #fecdd3 100%); color: #e11d48;">
                <i class="bi bi-tags-fill"></i>
            </div>
            <div>
                <div class="trust-badge-title">Best Price Guarantee</div>
                <div class="trust-badge-subtitle">We beat the market price</div>
            </div>
        </div>
        <div class="vr opacity-20 d-none d-lg-block" style="height: 34px; background-color: #cbd5e1;"></div>

        <!-- 4. Secure Payments -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon-wrap" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); color: #16a34a;">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <div class="trust-badge-title">Secure Payments</div>
                <div class="trust-badge-subtitle">Safe & multiple payment options</div>
            </div>
        </div>
        <div class="vr opacity-20 d-none d-lg-block" style="height: 34px; background-color: #cbd5e1;"></div>

        <!-- 5. Pan India Delivery -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon-wrap" style="background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%); color: #0d9488;">
                <i class="bi bi-truck"></i>
            </div>
            <div>
                <div class="trust-badge-title">Pan India Delivery</div>
                <div class="trust-badge-subtitle">Fast delivery at your place</div>
            </div>
        </div>
        <div class="vr opacity-20 d-none d-lg-block" style="height: 34px; background-color: #cbd5e1;"></div>

        <!-- 6. Easy Returns -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon-wrap" style="background: linear-gradient(135deg, #faf5ff 0%, #ede9fe 100%); color: #7c3aed;">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div>
                <div class="trust-badge-title">Easy Returns</div>
                <div class="trust-badge-subtitle">Hassle free returns</div>
            </div>
        </div>
    </div>

    <!-- 6. CATEGORY SCROLLER STRIP (Enhanced 3D & Spacious Layout) -->
    <div class="categories-pill-wrapper garudo-reveal">
        <!-- Left Navigation Button -->
        <button class="track-nav-btn cat-nav-btn" id="catScrollLeft" style="left: -12px;" title="Scroll left">
            <i class="bi bi-chevron-left"></i>
        </button>

        <div class="categories-scroll-track" id="categoryScrollTrack">
            <!-- 1. Cement & Concrete -->
            <a href="{{ route('products.index', ['category' => 'cement']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="cemBg" x1="12" y1="8" x2="44" y2="50" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#F1F5F9"/>
                                <stop offset="1" stop-color="#CBD5E1"/>
                            </linearGradient>
                            <linearGradient id="cemBadge" x1="16" y1="22" x2="40" y2="38" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#EA580C"/>
                                <stop offset="1" stop-color="#C2410C"/>
                            </linearGradient>
                            <filter id="cemShadow" x="8" y="6" width="40" height="46" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#0F172A" flood-opacity="0.12"/>
                            </filter>
                        </defs>
                        <g filter="url(#cemShadow)">
                            <path d="M16 14C16 11 19 8 23 7.5L33 7.5C37 8 40 11 40 14L42 43C42 46.5 39 48.5 35 48.5H21C17 48.5 14 46.5 14 43L16 14Z" fill="url(#cemBg)" stroke="#334155" stroke-width="2.2" stroke-linejoin="round"/>
                            <path d="M19 7.5C19 4.5 22.5 3 28 3C33.5 3 37 4.5 37 7.5" stroke="#334155" stroke-width="2.2" stroke-linecap="round"/>
                            <path d="M16 17H40" stroke="#94A3B8" stroke-width="1.8" stroke-dasharray="2.5 2"/>
                            <rect x="18" y="22" width="20" height="17" rx="3.5" fill="url(#cemBadge)"/>
                            <path d="M22 28H34M22 33H30" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="28" cy="25" r="1.5" fill="#FEF08A"/>
                            <path d="M16 38L21 44M40 38L35 44" stroke="#94A3B8" stroke-width="1.8" stroke-linecap="round"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Cement &<br>Concrete</span>
            </a>

            <!-- 2. Steel & TMT Bars -->
            <a href="{{ route('products.index', ['category' => 'steel']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="stlGrad1" x1="10" y1="40" x2="48" y2="12" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#38BDF8"/>
                                <stop offset="0.5" stop-color="#0284C7"/>
                                <stop offset="1" stop-color="#0369A1"/>
                            </linearGradient>
                            <linearGradient id="stlGrad2" x1="16" y1="48" x2="52" y2="20" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#94A3B8"/>
                                <stop offset="0.5" stop-color="#475569"/>
                                <stop offset="1" stop-color="#1E293B"/>
                            </linearGradient>
                            <filter id="stlShadow" x="6" y="8" width="44" height="44" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#0284C7" flood-opacity="0.2"/>
                            </filter>
                        </defs>
                        <g filter="url(#stlShadow)">
                            <rect x="10" y="36" width="46" height="8.5" rx="4.25" transform="rotate(-38 10 36)" fill="url(#stlGrad1)" stroke="#0F172A" stroke-width="2"/>
                            <line x1="18" y1="36" x2="22" y2="31" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" opacity="0.85"/>
                            <line x1="25" y1="31" x2="29" y2="26" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" opacity="0.85"/>
                            <line x1="32" y1="26" x2="36" y2="21" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" opacity="0.85"/>
                            <line x1="39" y1="21" x2="43" y2="16" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" opacity="0.85"/>
                            <rect x="16" y="46" width="44" height="8" rx="4" transform="rotate(-38 16 46)" fill="url(#stlGrad2)" stroke="#0F172A" stroke-width="2"/>
                            <line x1="24" y1="46" x2="28" y2="41" stroke="#E2E8F0" stroke-width="1.8" stroke-linecap="round"/>
                            <line x1="31" y1="41" x2="35" y2="36" stroke="#E2E8F0" stroke-width="1.8" stroke-linecap="round"/>
                            <line x1="38" y1="36" x2="42" y2="31" stroke="#E2E8F0" stroke-width="1.8" stroke-linecap="round"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Steel &<br>TMT Bars</span>
            </a>

            <!-- 3. Bricks & Blocks -->
            <a href="{{ route('products.index', ['category' => 'bricks-blocks']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="brkTop" x1="10" y1="12" x2="46" y2="30" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FED7AA"/>
                                <stop offset="1" stop-color="#FDBA74"/>
                            </linearGradient>
                            <linearGradient id="brkSide1" x1="8" y1="24" x2="24" y2="48" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FB923C"/>
                                <stop offset="1" stop-color="#EA580C"/>
                            </linearGradient>
                            <linearGradient id="brkSide2" x1="24" y1="24" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#EA580C"/>
                                <stop offset="1" stop-color="#9A3412"/>
                            </linearGradient>
                            <filter id="brkShadow" x="6" y="10" width="44" height="42" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#7C2D12" flood-opacity="0.18"/>
                            </filter>
                        </defs>
                        <g filter="url(#brkShadow)">
                            <path d="M8 35L22 28L36 35L22 42L8 35Z" fill="url(#brkTop)" stroke="#334155" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M8 35V43L22 50V42L8 35Z" fill="url(#brkSide1)" stroke="#334155" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M22 42L36 35V43L22 50V42Z" fill="url(#brkSide2)" stroke="#334155" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M20 20L34 13L48 20L34 27L20 20Z" fill="url(#brkTop)" stroke="#334155" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M20 20V28L34 35V27L20 20Z" fill="url(#brkSide1)" stroke="#334155" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M34 27L48 20V28L34 35V27Z" fill="url(#brkSide2)" stroke="#334155" stroke-width="2" stroke-linejoin="round"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Bricks &<br>Blocks</span>
            </a>

            <!-- 4. Sand & Aggregates -->
            <a href="{{ route('products.index', ['category' => 'sand']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="sndGrad" x1="28" y1="8" x2="28" y2="48" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FEF08A"/>
                                <stop offset="0.6" stop-color="#FACC15"/>
                                <stop offset="1" stop-color="#EAB308"/>
                            </linearGradient>
                            <linearGradient id="rckGrad" x1="40" y1="28" x2="48" y2="40" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#E2E8F0"/>
                                <stop offset="1" stop-color="#64748B"/>
                            </linearGradient>
                            <filter id="sndShadow" x="6" y="8" width="44" height="44" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#854D0E" flood-opacity="0.15"/>
                            </filter>
                        </defs>
                        <g filter="url(#sndShadow)">
                            <path d="M28 10L7 44C5.5 46.5 8 49 11 49H45C48 49 50.5 46.5 49 44L28 10Z" fill="url(#sndGrad)" stroke="#334155" stroke-width="2.2" stroke-linejoin="round"/>
                            <path d="M28 10L31 27L45 49" stroke="#CA8A04" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="18" cy="40" r="2" fill="#713F12"/>
                            <circle cx="25" cy="36" r="1.8" fill="#854D0E"/>
                            <circle cx="23" cy="44" r="2.2" fill="#713F12"/>
                            <circle cx="34" cy="41" r="1.8" fill="#854D0E"/>
                            <circle cx="39" cy="45" r="2.5" fill="#713F12"/>
                            <path d="M42 32L47 27L51 31L48 37L43 35L42 32Z" fill="url(#rckGrad)" stroke="#334155" stroke-width="1.8" stroke-linejoin="round"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Sand &<br>Aggregates</span>
            </a>

            <!-- 5. Paints & Coatings -->
            <a href="{{ route('products.index', ['category' => 'paints']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="pntCan" x1="12" y1="18" x2="44" y2="50" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFFFFF"/>
                                <stop offset="0.7" stop-color="#F1F5F9"/>
                                <stop offset="1" stop-color="#CBD5E1"/>
                            </linearGradient>
                            <linearGradient id="pntColor" x1="14" y1="30" x2="42" y2="48" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FB923C"/>
                                <stop offset="1" stop-color="#EA580C"/>
                            </linearGradient>
                            <filter id="pntShadow" x="8" y="6" width="40" height="46" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#EA580C" flood-opacity="0.2"/>
                            </filter>
                        </defs>
                        <g filter="url(#pntShadow)">
                            <path d="M14 18L16.5 44C17 47.5 21 50 28 50C35 50 39 47.5 39.5 44L42 18" fill="url(#pntCan)" stroke="#334155" stroke-width="2.2" stroke-linejoin="round"/>
                            <ellipse cx="28" cy="18" rx="14" ry="5" fill="#FED7AA" stroke="#334155" stroke-width="2.2"/>
                            <path d="M12 20C12 7 44 7 44 20" stroke="#64748B" stroke-width="2.4" stroke-linecap="round"/>
                            <path d="M23 20V32C23 34.5 26.5 34.5 26.5 32V21" fill="#EA580C"/>
                            <path d="M15 32C20 36 36 29 41 33L39.5 44C39 47 35 49.5 28 49.5C21 49.5 17 47 16.5 44L15 32Z" fill="url(#pntColor)"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Paints &<br>Coatings</span>
            </a>

            <!-- 6. Tiles & Sanitary -->
            <a href="{{ route('products.index', ['category' => 'tiles']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="tilGrad" x1="10" y1="10" x2="46" y2="46" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFFFFF"/>
                                <stop offset="0.5" stop-color="#EFF6FF"/>
                                <stop offset="1" stop-color="#DBEAFE"/>
                            </linearGradient>
                            <filter id="tilShadow" x="6" y="6" width="44" height="44" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#0284C7" flood-opacity="0.18"/>
                            </filter>
                        </defs>
                        <g filter="url(#tilShadow)">
                            <rect x="10" y="10" width="36" height="36" rx="6" fill="url(#tilGrad)" stroke="#334155" stroke-width="2.2"/>
                            <line x1="10" y1="28" x2="46" y2="28" stroke="#93C5FD" stroke-width="2.2"/>
                            <line x1="28" y1="10" x2="28" y2="46" stroke="#93C5FD" stroke-width="2.2"/>
                            <line x1="15" y1="15" x2="22" y2="22" stroke="#38BDF8" stroke-width="2.4" stroke-linecap="round"/>
                            <line x1="33" y1="33" x2="40" y2="40" stroke="#38BDF8" stroke-width="2.4" stroke-linecap="round"/>
                            <path d="M38 15L39.5 19L43.5 20.5L39.5 22L38 26L36.5 22L32.5 20.5L36.5 19L38 15Z" fill="#0284C7"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Tiles &<br>Sanitary</span>
            </a>

            <!-- 7. Plumbing & Fittings -->
            <a href="{{ route('products.index', ['category' => 'pipes']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="pipGrad" x1="8" y1="14" x2="48" y2="30" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#0284C7"/>
                                <stop offset="0.5" stop-color="#0EA5E9"/>
                                <stop offset="1" stop-color="#0284C7"/>
                            </linearGradient>
                            <filter id="pipShadow" x="6" y="8" width="44" height="44" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#0284C7" flood-opacity="0.2"/>
                            </filter>
                        </defs>
                        <g filter="url(#pipShadow)">
                            <path d="M10 14H22C28 14 28 28 34 28H46" stroke="url(#pipGrad)" stroke-width="7.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 14H22C28 14 28 28 34 28H46" stroke="#F0F9FF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="7" y="9.5" width="6" height="9" rx="2" fill="#F59E0B" stroke="#B45309" stroke-width="1.6"/>
                            <rect x="43" y="23.5" width="6" height="9" rx="2" fill="#F59E0B" stroke="#B45309" stroke-width="1.6"/>
                            <circle cx="28" cy="21" r="5" fill="#38BDF8" stroke="#0369A1" stroke-width="2"/>
                            <path d="M28 35C28 35 24 40 24 43C24 45.5 25.8 47.5 28 47.5C30.2 47.5 32 45.5 32 43C32 40 28 35 28 35Z" fill="#0284C7"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Plumbing &<br>Fittings</span>
            </a>

            <!-- 8. Electricals & Lighting -->
            <a href="{{ route('products.index', ['category' => 'electricals']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="blbGrad" x1="28" y1="8" x2="28" y2="40" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FEF08A"/>
                                <stop offset="0.7" stop-color="#FDE047"/>
                                <stop offset="1" stop-color="#FACC15"/>
                            </linearGradient>
                            <filter id="blbShadow" x="10" y="4" width="36" height="48" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2.5" flood-color="#EAB308" flood-opacity="0.3"/>
                            </filter>
                        </defs>
                        <g filter="url(#blbShadow)">
                            <path d="M18 20C18 14.5 22.5 10 28 10C33.5 10 38 14.5 38 20C38 24.5 35.5 27.5 33 30.5V36C33 37.5 31.5 38.5 30 38.5H26C24.5 38.5 23 37.5 23 36V30.5C20.5 27.5 18 24.5 18 20Z" fill="url(#blbGrad)" stroke="#334155" stroke-width="2.2" stroke-linejoin="round"/>
                            <path d="M24 38.5H32V42.5C32 44 30.5 45 28 45C25.5 45 24 44 24 42.5V38.5Z" fill="#94A3B8" stroke="#334155" stroke-width="2"/>
                            <path d="M25 24L28 19L31 24" stroke="#B45309" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <line x1="28" y1="3" x2="28" y2="6.5" stroke="#EA580C" stroke-width="2.5" stroke-linecap="round"/>
                            <line x1="13" y1="10" x2="15.5" y2="12.5" stroke="#EA580C" stroke-width="2.5" stroke-linecap="round"/>
                            <line x1="43" y1="10" x2="40.5" y2="12.5" stroke="#EA580C" stroke-width="2.5" stroke-linecap="round"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Electricals &<br>Lighting</span>
            </a>

            <!-- 9. Hardware & Tools -->
            <a href="{{ route('products.index', ['category' => 'hardware']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="tlWrench" x1="12" y1="40" x2="42" y2="12" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFFFFF"/>
                                <stop offset="0.6" stop-color="#CBD5E1"/>
                                <stop offset="1" stop-color="#94A3B8"/>
                            </linearGradient>
                            <filter id="tlShadow" x="8" y="8" width="40" height="40" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#D97706" flood-opacity="0.2"/>
                            </filter>
                        </defs>
                        <g filter="url(#tlShadow)">
                            <path d="M41 12C38.5 9.5 34.5 10 32.5 13L14 38.5C13 40 13.5 42.5 15.5 43.5L17 44.5C19 45.5 21.5 45 23 43L40.5 20C43 18 43.5 14 41 12Z" fill="url(#tlWrench)" stroke="#334155" stroke-width="2.2"/>
                            <path d="M14 13L42 43" stroke="#EA580C" stroke-width="6" stroke-linecap="round"/>
                            <path d="M14 13L21 20.5" stroke="#475569" stroke-width="3.5" stroke-linecap="round"/>
                            <path d="M35 35.5L42 43" stroke="#F59E0B" stroke-width="7" stroke-linecap="round"/>
                            <circle cx="28" cy="28" r="3.5" fill="#F59E0B" stroke="#334155" stroke-width="2"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Hardware &<br>Tools</span>
            </a>

            <!-- 10. Doors & Windows -->
            <a href="{{ route('products.index', ['category' => 'doors-windows']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="drFrame" x1="12" y1="8" x2="44" y2="48" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FED7AA"/>
                                <stop offset="1" stop-color="#FDBA74"/>
                            </linearGradient>
                            <linearGradient id="drGlass" x1="15" y1="12" x2="26" y2="26" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#E0F2FE"/>
                                <stop offset="1" stop-color="#BAE6FD"/>
                            </linearGradient>
                            <filter id="drShadow" x="8" y="6" width="40" height="44" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#9A3412" flood-opacity="0.18"/>
                            </filter>
                        </defs>
                        <g filter="url(#drShadow)">
                            <rect x="12" y="7" width="32" height="42" rx="4" fill="url(#drFrame)" stroke="#334155" stroke-width="2.2"/>
                            <line x1="28" y1="7" x2="28" y2="49" stroke="#334155" stroke-width="2"/>
                            <rect x="15.5" y="11" width="9.5" height="14" rx="2" fill="url(#drGlass)" stroke="#0284C7" stroke-width="1.6"/>
                            <rect x="31" y="11" width="9.5" height="14" rx="2" fill="url(#drGlass)" stroke="#0284C7" stroke-width="1.6"/>
                            <rect x="15.5" y="30" width="9.5" height="14" rx="2" fill="#FB923C" stroke="#C2410C" stroke-width="1.6"/>
                            <rect x="31" y="30" width="9.5" height="14" rx="2" fill="#FB923C" stroke="#C2410C" stroke-width="1.6"/>
                            <circle cx="24.5" cy="28" r="1.8" fill="#FACC15" stroke="#713F12" stroke-width="1.2"/>
                            <circle cx="31.5" cy="28" r="1.8" fill="#FACC15" stroke="#713F12" stroke-width="1.2"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Doors &<br>Windows</span>
            </a>

            <!-- 11. Wood & Plywood -->
            <a href="{{ route('products.index', ['category' => 'plywood']) }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="wdPlank" x1="10" y1="12" x2="46" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FED7AA"/>
                                <stop offset="0.5" stop-color="#FDBA74"/>
                                <stop offset="1" stop-color="#FB923C"/>
                            </linearGradient>
                            <filter id="wdShadow" x="6" y="8" width="44" height="40" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#7C2D12" flood-opacity="0.18"/>
                            </filter>
                        </defs>
                        <g filter="url(#wdShadow)">
                            <rect x="10" y="11" width="36" height="34" rx="5" fill="url(#wdPlank)" stroke="#334155" stroke-width="2.2"/>
                            <path d="M14 18C21 15 31 20 42 17" stroke="#9A3412" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M14 28C24 25 33 31 42 27" stroke="#9A3412" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M14 38C22 35 29 40 42 36" stroke="#9A3412" stroke-width="1.8" stroke-linecap="round"/>
                            <ellipse cx="27" cy="27.5" rx="4" ry="2.5" fill="#78350F" opacity="0.8"/>
                            <circle cx="27" cy="27.5" r="1.2" fill="#FEF3C7"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title">Wood &<br>Plywood</span>
            </a>

            <!-- 12. All Categories -->
            <a href="{{ route('products.index') }}" class="category-doodle-card">
                <div class="category-doodle-icon-box">
                    <svg width="48" height="48" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="allG1" x1="12" y1="12" x2="26" y2="26" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#38BDF8"/>
                                <stop offset="1" stop-color="#0284C7"/>
                            </linearGradient>
                            <linearGradient id="allG2" x1="30" y1="12" x2="44" y2="26" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FBBF24"/>
                                <stop offset="1" stop-color="#D97706"/>
                            </linearGradient>
                            <linearGradient id="allG3" x1="12" y1="30" x2="26" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#34D399"/>
                                <stop offset="1" stop-color="#059669"/>
                            </linearGradient>
                            <linearGradient id="allG4" x1="30" y1="30" x2="44" y2="44" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#F472B6"/>
                                <stop offset="1" stop-color="#DB2777"/>
                            </linearGradient>
                            <filter id="allShadow" x="8" y="8" width="40" height="40" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#0284C7" flood-opacity="0.2"/>
                            </filter>
                        </defs>
                        <g filter="url(#allShadow)">
                            <rect x="12" y="12" width="14" height="14" rx="5" fill="url(#allG1)" stroke="#0F172A" stroke-width="1.8"/>
                            <rect x="30" y="12" width="14" height="14" rx="5" fill="url(#allG2)" stroke="#0F172A" stroke-width="1.8"/>
                            <rect x="12" y="30" width="14" height="14" rx="5" fill="url(#allG3)" stroke="#0F172A" stroke-width="1.8"/>
                            <rect x="30" y="30" width="14" height="14" rx="5" fill="url(#allG4)" stroke="#0F172A" stroke-width="1.8"/>
                            <circle cx="19" cy="19" r="2" fill="#FFFFFF"/>
                            <circle cx="37" cy="19" r="2" fill="#FFFFFF"/>
                            <circle cx="19" cy="37" r="2" fill="#FFFFFF"/>
                            <circle cx="37" cy="37" r="2" fill="#FFFFFF"/>
                        </g>
                    </svg>
                </div>
                <span class="category-doodle-title text-primary fw-bold">All<br>Categories</span>
            </a>
        </div>

        <!-- Right Navigation Button -->
        <button class="track-nav-btn track-nav-right cat-nav-btn" id="catScrollRight" style="right: -12px;" title="Scroll right">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <!-- 7. SPECIAL OFFERS FOR YOU (4 High-Impact Promotional Banners with Generated 3D Imagery) -->
    <div class="mb-4">
        <h5 class="section-header-title mb-3" style="font-size: 16px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.4px;">SPECIAL OFFERS FOR YOU</h5>
        <div class="row g-3">
            <!-- Banner 1: Contractor Special -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 rounded-4 text-white position-relative overflow-hidden h-100 special-offer-card" style="background: linear-gradient(135deg, #7c2d12 0%, #b45309 45%, #ea580c 100%); min-height: 220px; box-shadow: 0 10px 25px rgba(194, 65, 12, 0.25);">
                    <div class="p-4 position-relative z-2 d-flex flex-column justify-content-between h-100" style="max-width: 58%;">
                        <div>
                            <span class="offer-tag-pill" style="background: rgba(254, 215, 170, 0.2); border: 1px solid rgba(254, 215, 170, 0.4); color: #fed7aa;">CONTRACTOR SPECIAL</span>
                            <h3 class="fw-extrabold mb-1 text-white" style="font-size: 22px; line-height: 1.15; font-weight: 800;">Up to 30% OFF</h3>
                            <div class="fw-semibold mt-1 mb-3" style="font-size: 13.5px; color: #ffedd5;">On Bulk Orders</div>
                        </div>
                        <a href="{{ route('products.index', ['filter' => 'bulk']) }}" class="btn btn-sm rounded-pill text-white fw-bold px-3 py-2 align-self-start" style="font-size: 12px; background: rgba(124, 45, 18, 0.65); border: 1.5px solid #fdba74; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">Order Now</a>
                    </div>
                    <!-- Contractor Generated Image with Smooth Left Fade -->
                    <div class="offer-img-box">
                        <div class="offer-img-fade-left" style="background: linear-gradient(to right, #7c2d12 0%, rgba(124, 45, 18, 0.7) 18%, transparent 55%);"></div>
                        <img src="{{ asset('images/offers/contractor_special.jpg') }}" alt="Contractor Special">
                    </div>
                </div>
            </div>

            <!-- Banner 2: Festive Offer -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 rounded-4 text-white position-relative overflow-hidden h-100 special-offer-card" style="background: linear-gradient(135deg, #3b0764 0%, #581c87 45%, #7e22ce 100%); min-height: 220px; box-shadow: 0 10px 25px rgba(126, 34, 206, 0.3);">
                    <div class="p-4 position-relative z-2 d-flex flex-column justify-content-between h-100" style="max-width: 58%;">
                        <div>
                            <span class="offer-tag-pill" style="background: rgba(253, 224, 71, 0.2); border: 1px solid rgba(253, 224, 71, 0.4); color: #fde047;">FESTIVE OFFER</span>
                            <h3 class="fw-extrabold mb-1 text-white" style="font-size: 20px; line-height: 1.2; font-weight: 800;">Big Savings on</h3>
                            <div class="fw-semibold mt-1 mb-3" style="font-size: 14px; color: #f3e8ff;">Building Materials</div>
                        </div>
                        <a href="{{ route('products.index', ['filter' => 'festive']) }}" class="btn btn-sm rounded-pill text-white fw-bold px-3 py-2 align-self-start" style="font-size: 12px; background: #dc2626; border: none; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.5);">Shop Now</a>
                    </div>
                    <!-- Festive Gift Box Generated Image with Smooth Left Fade -->
                    <div class="offer-img-box">
                        <div class="offer-img-fade-left" style="background: linear-gradient(to right, #3b0764 0%, rgba(59, 7, 100, 0.7) 18%, transparent 55%);"></div>
                        <img src="{{ asset('images/offers/festive_gift_box.jpg') }}" alt="Festive Offer">
                    </div>
                </div>
            </div>

            <!-- Banner 3: Clearance Sale -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 rounded-4 text-white position-relative overflow-hidden h-100 special-offer-card" style="background: linear-gradient(135deg, #450a0a 0%, #7f1d1d 45%, #b91c1c 100%); min-height: 220px; box-shadow: 0 10px 25px rgba(185, 28, 28, 0.3);">
                    <div class="p-4 position-relative z-2 d-flex flex-column justify-content-between h-100" style="max-width: 58%;">
                        <div>
                            <span class="offer-tag-pill" style="background: rgba(254, 240, 138, 0.2); border: 1px solid rgba(254, 240, 138, 0.4); color: #fef08a;">CLEARANCE SALE</span>
                            <h3 class="fw-extrabold mb-1 text-white" style="font-size: 22px; line-height: 1.15; font-weight: 800;">Up to 50% OFF</h3>
                            <div class="fw-semibold mt-1 mb-3" style="font-size: 13.5px; color: #fee2e2;">Limited Stock</div>
                        </div>
                        <a href="{{ route('products.index', ['filter' => 'clearance']) }}" class="btn btn-sm rounded-pill fw-extrabold px-3 py-2 align-self-start" style="font-size: 12px; background: #eab308; color: #451a03; border: none; box-shadow: 0 4px 14px rgba(234, 179, 8, 0.45);">Grab Now</a>
                    </div>
                    <!-- Clearance Boxes Generated Image with Smooth Left Fade -->
                    <div class="offer-img-box">
                        <div class="offer-img-fade-left" style="background: linear-gradient(to right, #450a0a 0%, rgba(69, 10, 10, 0.7) 18%, transparent 55%);"></div>
                        <img src="{{ asset('images/offers/clearance_boxes.jpg') }}" alt="Clearance Sale">
                    </div>
                </div>
            </div>

            <!-- Banner 4: Combo Offers -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 rounded-4 text-white position-relative overflow-hidden h-100 special-offer-card" style="background: linear-gradient(135deg, #030712 0%, #0f172a 45%, #1e3a8a 100%); min-height: 220px; box-shadow: 0 10px 25px rgba(30, 58, 138, 0.3);">
                    <div class="p-4 position-relative z-2 d-flex flex-column justify-content-between h-100" style="max-width: 58%;">
                        <div>
                            <span class="offer-tag-pill" style="background: rgba(251, 146, 60, 0.2); border: 1px solid rgba(251, 146, 60, 0.4); color: #fb923c;">COMBO OFFERS</span>
                            <h3 class="fw-extrabold mb-1 text-white" style="font-size: 20px; line-height: 1.2; font-weight: 800;">More Products</h3>
                            <div class="fw-semibold mt-1 mb-3" style="font-size: 14px; color: #93c5fd;">More Savings</div>
                        </div>
                        <a href="{{ route('products.index', ['filter' => 'combo']) }}" class="btn btn-sm rounded-pill text-white fw-bold px-3 py-2 align-self-start" style="font-size: 12px; background: #2563eb; border: none; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.45);">Explore Now</a>
                    </div>
                    <!-- Combo Offers Paint Cans Generated Image with Smooth Left Fade -->
                    <div class="offer-img-box">
                        <div class="offer-img-fade-left" style="background: linear-gradient(to right, #030712 0%, rgba(3, 7, 18, 0.7) 18%, transparent 55%);"></div>
                        <img src="{{ asset('images/offers/combo_offers_paints.jpg') }}" alt="Combo Offers">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 8. POPULAR CATEGORIES WITH TOP PRODUCTS (6 Columns with Real Active Products & Admin Control) -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-header-title mb-0" style="font-size: 16px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.4px;">POPULAR CATEGORIES WITH TOP PRODUCTS</h5>
            <a href="{{ route('products.index') }}" class="small fw-bold text-decoration-none d-flex align-items-center gap-1 text-navy hover-primary" style="font-size: 13px;">
                <span>View All Categories</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($popularCategories as $pCategory)
                @php
                    $prodCount = $pCategory->total_products_count ?? ($pCategory->products_count ?? 80);
                    $topProds = $pCategory->top_products ?? collect();
                @endphp
                <div class="col-12 col-sm-6 col-md-4 col-xl-2">
                    <div class="card h-100 border rounded-4 p-3 bg-white popular-cat-card" style="border-color: #e2e8f0 !important; box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);">
                        <!-- Category Header -->
                        <div class="fw-bold text-center text-truncate mb-3 pb-2 border-bottom" style="font-size: 13.5px; color: #0f172a; min-height: 42px; display: flex; align-items: center; justify-content: center; border-color: #f1f5f9 !important;" title="{{ $pCategory->name }}">
                            {{ $pCategory->name }}
                        </div>

                        <!-- 3 Product Rows -->
                        <div class="d-flex flex-column gap-2 mb-3 flex-grow-1 justify-content-around">
                            @foreach($topProds as $prod)
                                <a href="{{ !empty($prod->slug) ? route('products.show', $prod->slug) : route('products.index', ['category' => $pCategory->slug]) }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark top-product-row">
                                    <div class="rounded-3 border d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 52px; height: 52px; background: #f8fafc; border-color: #eef2f6 !important; padding: 2px;">
                                        @if(!empty($prod->primary_image_url) && !str_contains($prod->primary_image_url, 'product-placeholder'))
                                            <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->name }}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                        @else
                                            <i class="bi {{ $prod->icon ?? ($pCategory->icon ?? 'bi-box-seam') }} text-primary fs-4 opacity-75"></i>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden flex-grow-1" style="min-width: 0;">
                                        <div class="fw-semibold text-truncate product-row-title" style="font-size: 12.5px; color: #1e293b; line-height: 1.3;" title="{{ $prod->name }}">
                                            {{ $prod->name }}
                                        </div>
                                        <div class="mt-1 d-flex align-items-baseline gap-1">
                                            <span class="fw-extrabold" style="font-size: 13px; color: #0f172a; font-weight: 800;">₹{{ number_format($prod->selling_price) }}</span>
                                            <span class="text-muted" style="font-size: 10.5px;">/ {{ $prod->unit ?: 'Unit' }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Card Footer: View All ({count}+) -->
                        <div class="pt-2 border-top text-center" style="border-color: #f1f5f9 !important;">
                            <a href="{{ route('products.index', ['category' => $pCategory->slug]) }}" class="text-primary fw-bold text-decoration-none d-block view-all-cat-link" style="font-size: 12px;">
                                View All ({{ $prodCount > 0 ? $prodCount . '+' : '80+' }})
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- PRODUCT SECTION 1: TRENDING PRODUCTS (ROW 1 - ABOVE BANNERS)  -->
    <!-- ============================================================== -->
    <div class="mb-4 position-relative" id="trending-products-section-1">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h4 class="section-header-title mb-0" style="font-weight: 800; color: #0f172a; font-size: 1.35rem; letter-spacing: -0.3px;">Trending Products</h4>
                
                <!-- Category Filter Tabs -->
                <div class="d-flex align-items-center flex-wrap gap-2 trending-category-tabs" data-target-row="trendingProductsRowSec1" id="trendingCategoryPillsSec1">
                    <button type="button" class="btn btn-sm border-0 p-0 px-2.5 py-1 fw-bold trending-cat-tab active" data-cat-id="all" style="font-size: 13px; color: #d97706; border-bottom: 2.5px solid #f59e0b !important; border-radius: 0; background: transparent;">
                        Best Sellers
                    </button>
                    @foreach($trendingCategories as $tCat)
                        <button type="button" class="btn btn-sm border-0 p-0 px-2.5 py-1 fw-semibold text-muted trending-cat-tab" data-cat-id="{{ $tCat->id }}" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent; transition: all 0.2s ease;">
                            {{ $tCat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- View All Products Link -->
            <a href="{{ route('products.index', ['sort' => 'trending']) }}" class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-decoration-none shadow-xs" style="background: #fffbeb; color: #92400e; border: 1px solid #fde68a; font-size: 12.5px; transition: all 0.2s ease;">
                View All Products <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Carousel Row with Floating Left/Right Arrows -->
        <div class="position-relative carousel-slider-wrapper">
            <!-- Left Prev Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 start-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-prev" onclick="scrollSectionCarousel('trendingProductsRowSec1', -340)" title="Previous">
                <i class="bi bi-chevron-left fs-6"></i>
            </button>

            <!-- Cards Scroll Container -->
            <div class="d-flex overflow-x-auto custom-products-scroll" id="trendingProductsRowSec1">
                @forelse($sec1TrendingProducts as $p)
                    @include('frontend.home.partials._trending_card', ['product' => $p])
                @empty
                    <div class="py-4 text-center text-muted w-100">
                        <i class="bi bi-box-seam fs-3 text-secondary d-block mb-1"></i>
                        No trending products found.
                    </div>
                @endforelse
            </div>

            <!-- Right Next Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 end-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-next" onclick="scrollSectionCarousel('trendingProductsRowSec1', 340)" title="Next">
                <i class="bi bi-chevron-right fs-6"></i>
            </button>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- PRODUCT SECTION 2: BEST SELLING PRODUCTS (ROW 1 - ABOVE BANNERS) -->
    <!-- ============================================================== -->
    <div class="mb-5 position-relative" id="best-selling-products-section-1">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h4 class="section-header-title mb-0" style="font-weight: 800; color: #0f172a; font-size: 1.25rem; letter-spacing: 0.3px; text-transform: uppercase;">BEST SELLING PRODUCTS</h4>
                
                <!-- 4 Placement Tabs -->
                <ul class="nav best-selling-header-tabs d-inline-flex border-0 gap-2" id="bestSellingTabSec2" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active p-0 px-2 py-1 fw-bold best-selling-tab-btn" id="sec2-top-rated-tab" data-bs-toggle="pill" data-bs-target="#sec2-top-rated-pane" type="button" role="tab" style="font-size: 13px; color: #ea580c; border-bottom: 2.5px solid #ea580c !important; border-radius: 0; background: transparent;">
                            Top Rated
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link p-0 px-2 py-1 fw-semibold text-muted best-selling-tab-btn" id="sec2-most-sold-tab" data-bs-toggle="pill" data-bs-target="#sec2-most-sold-pane" type="button" role="tab" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent;">
                            Most Sold
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link p-0 px-2 py-1 fw-semibold text-muted best-selling-tab-btn" id="sec2-trending-tab" data-bs-toggle="pill" data-bs-target="#sec2-trending-pane" type="button" role="tab" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent;">
                            Trending
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link p-0 px-2 py-1 fw-semibold text-muted best-selling-tab-btn" id="sec2-new-arrivals-tab" data-bs-toggle="pill" data-bs-target="#sec2-new-arrivals-pane" type="button" role="tab" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent;">
                            New Arrivals
                        </button>
                    </li>
                </ul>
            </div>

            <!-- View All Button -->
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold text-decoration-none shadow-xs" style="font-size: 12px; border-color: #cbd5e1; color: #475569;">
                View All
            </a>
        </div>

        <!-- Carousel Row with Floating Left/Right Arrows -->
        <div class="position-relative carousel-slider-wrapper">
            <!-- Left Prev Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 start-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-prev" onclick="scrollActiveBestSellingTab('bestSellingTabContentSec2', -340)" title="Previous">
                <i class="bi bi-chevron-left fs-6"></i>
            </button>

            <div class="tab-content" id="bestSellingTabContentSec2">
                <!-- PANE 1: Top Rated Products -->
                <div class="tab-pane fade show active" id="sec2-top-rated-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec2TopRatedRow">
                        @forelse($sec2TopRated as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No top rated products found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- PANE 2: Most Sold / Best Sellers -->
                <div class="tab-pane fade" id="sec2-most-sold-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec2MostSoldRow">
                        @forelse($sec2MostSold as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No products found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- PANE 3: Trending Best Products -->
                <div class="tab-pane fade" id="sec2-trending-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec2TrendingBestRow">
                        @forelse($sec2Trending as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No trending products found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- PANE 4: New Arrivals -->
                <div class="tab-pane fade" id="sec2-new-arrivals-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec2NewArrivalsRow">
                        @forelse($sec2NewArrivals as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No new arrivals found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Next Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 end-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-next" onclick="scrollActiveBestSellingTab('bestSellingTabContentSec2', 340)" title="Next">
                <i class="bi bi-chevron-right fs-6"></i>
            </button>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 3 MEGA PROMOTIONAL BANNERS (MIDDLE ROW - BETWEEN SEC 2 & SEC 3)-->
    <!-- (Row 1: Upper Placement - Controllable in Admin Panel)         -->
    <!-- ============================================================== -->
    @php
        $activeRow1Banners = (isset($promoBannersRow1) && $promoBannersRow1->isNotEmpty())
            ? $promoBannersRow1
            : ((isset($promoBanners) && $promoBanners->isNotEmpty()) ? $promoBanners->take(3) : collect());
    @endphp
    <div class="mb-4" id="promo-banners-showcase-mid">
        <div class="row g-3">
            @if($activeRow1Banners->isNotEmpty())
                @foreach($activeRow1Banners->take(3) as $b)
                    <div class="col-lg-4 col-md-12">
                        <div class="card border-0 rounded-4 overflow-hidden position-relative shadow-sm h-100 promo-banner-card" style="background: {{ $b->background_color ?: '#090d16' }}; min-height: 285px; height: 285px;">
                            <div class="position-absolute top-0 end-0 bottom-0 overflow-hidden" style="width: 58%; pointer-events: none; z-index: 0;">
                                <img src="{{ $b->image_url }}" onerror="this.onerror=null; this.src='/images/banners/mega_construction_sale.jpg';" alt="{{ $b->title }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center right;">
                                <div class="position-absolute top-0 start-0 bottom-0 end-0" style="background: linear-gradient(to right, {{ $b->background_color ?: '#090d16' }} 0%, rgba(9, 13, 22, 0.85) 25%, rgba(9, 13, 22, 0) 80%);"></div>
                            </div>
                            
                            <div class="card-body p-4 position-relative d-flex flex-column justify-content-between z-1" style="max-width: 60%;">
                                <div>
                                    <div class="fw-black text-white" style="font-size: 1.45rem; line-height: 1.15; font-weight: 900; letter-spacing: -0.4px;">
                                        {!! nl2br(e($b->title)) !!}
                                        @if(!empty($b->subtitle))
                                            <div class="badge mt-2" style="background: #facc15; color: #0f172a; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 4px;">{{ $b->subtitle }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ $b->link ?: route('products.index') }}" class="btn btn-sm text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid #475569; font-size: 12.5px; backdrop-filter: blur(6px);">
                                        {{ $b->button_text ?: 'Explore Now' }} <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- PRODUCT SECTION 3: TRENDING PRODUCTS (ROW 2 - BELOW BANNERS)  -->
    <!-- ============================================================== -->
    <div class="mb-4 position-relative" id="trending-products-section-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h4 class="section-header-title mb-0" style="font-weight: 800; color: #0f172a; font-size: 1.35rem; letter-spacing: -0.3px;">Trending Products</h4>
                
                <!-- Category Filter Tabs -->
                <div class="d-flex align-items-center flex-wrap gap-2 trending-category-tabs" data-target-row="trendingProductsRowSec3" id="trendingCategoryPillsSec3">
                    <button type="button" class="btn btn-sm border-0 p-0 px-2.5 py-1 fw-bold trending-cat-tab active" data-cat-id="all" style="font-size: 13px; color: #d97706; border-bottom: 2.5px solid #f59e0b !important; border-radius: 0; background: transparent;">
                        Best Sellers
                    </button>
                    @foreach($trendingCategories as $tCat)
                        <button type="button" class="btn btn-sm border-0 p-0 px-2.5 py-1 fw-semibold text-muted trending-cat-tab" data-cat-id="{{ $tCat->id }}" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent; transition: all 0.2s ease;">
                            {{ $tCat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- View All Products Link -->
            <a href="{{ route('products.index', ['sort' => 'trending']) }}" class="btn btn-sm rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 text-decoration-none shadow-xs" style="background: #fffbeb; color: #92400e; border: 1px solid #fde68a; font-size: 12.5px; transition: all 0.2s ease;">
                View All Products <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Carousel Row with Floating Left/Right Arrows -->
        <div class="position-relative carousel-slider-wrapper">
            <!-- Left Prev Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 start-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-prev" onclick="scrollSectionCarousel('trendingProductsRowSec3', -340)" title="Previous">
                <i class="bi bi-chevron-left fs-6"></i>
            </button>

            <!-- Cards Scroll Container -->
            <div class="d-flex overflow-x-auto custom-products-scroll" id="trendingProductsRowSec3">
                @forelse($sec3TrendingProducts as $p)
                    @include('frontend.home.partials._trending_card', ['product' => $p])
                @empty
                    <div class="py-4 text-center text-muted w-100">
                        <i class="bi bi-box-seam fs-3 text-secondary d-block mb-1"></i>
                        No trending products found.
                    </div>
                @endforelse
            </div>

            <!-- Right Next Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 end-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-next" onclick="scrollSectionCarousel('trendingProductsRowSec3', 340)" title="Next">
                <i class="bi bi-chevron-right fs-6"></i>
            </button>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- PRODUCT SECTION 4: BEST SELLING PRODUCTS (ROW 2 - BELOW BANNERS) -->
    <!-- ============================================================== -->
    <div class="mb-5 position-relative" id="best-selling-products-section-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h4 class="section-header-title mb-0" style="font-weight: 800; color: #0f172a; font-size: 1.25rem; letter-spacing: 0.3px; text-transform: uppercase;">BEST SELLING PRODUCTS</h4>
                
                <!-- 4 Placement Tabs -->
                <ul class="nav best-selling-header-tabs d-inline-flex border-0 gap-2" id="bestSellingTabSec4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active p-0 px-2 py-1 fw-bold best-selling-tab-btn" id="sec4-top-rated-tab" data-bs-toggle="pill" data-bs-target="#sec4-top-rated-pane" type="button" role="tab" style="font-size: 13px; color: #ea580c; border-bottom: 2.5px solid #ea580c !important; border-radius: 0; background: transparent;">
                            Top Rated
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link p-0 px-2 py-1 fw-semibold text-muted best-selling-tab-btn" id="sec4-most-sold-tab" data-bs-toggle="pill" data-bs-target="#sec4-most-sold-pane" type="button" role="tab" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent;">
                            Most Sold
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link p-0 px-2 py-1 fw-semibold text-muted best-selling-tab-btn" id="sec4-trending-tab" data-bs-toggle="pill" data-bs-target="#sec4-trending-pane" type="button" role="tab" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent;">
                            Trending
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link p-0 px-2 py-1 fw-semibold text-muted best-selling-tab-btn" id="sec4-new-arrivals-tab" data-bs-toggle="pill" data-bs-target="#sec4-new-arrivals-pane" type="button" role="tab" style="font-size: 13px; border-bottom: 2.5px solid transparent; border-radius: 0; background: transparent;">
                            New Arrivals
                        </button>
                    </li>
                </ul>
            </div>

            <!-- View All Button -->
            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold text-decoration-none shadow-xs" style="font-size: 12px; border-color: #cbd5e1; color: #475569;">
                View All
            </a>
        </div>

        <!-- Carousel Row with Floating Left/Right Arrows -->
        <div class="position-relative carousel-slider-wrapper">
            <!-- Left Prev Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 start-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-prev" onclick="scrollActiveBestSellingTab('bestSellingTabContentSec4', -340)" title="Previous">
                <i class="bi bi-chevron-left fs-6"></i>
            </button>

            <div class="tab-content" id="bestSellingTabContentSec4">
                <!-- PANE 1: Top Rated Products -->
                <div class="tab-pane fade show active" id="sec4-top-rated-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec4TopRatedRow">
                        @forelse($sec4TopRated as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No top rated products found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- PANE 2: Most Sold / Best Sellers -->
                <div class="tab-pane fade" id="sec4-most-sold-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec4MostSoldRow">
                        @forelse($sec4MostSold as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No products found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- PANE 3: Trending Best Products -->
                <div class="tab-pane fade" id="sec4-trending-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec4TrendingBestRow">
                        @forelse($sec4Trending as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No trending products found.</div>
                        @endforelse
                    </div>
                </div>

                <!-- PANE 4: New Arrivals -->
                <div class="tab-pane fade" id="sec4-new-arrivals-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex overflow-x-auto custom-products-scroll best-selling-row" id="sec4NewArrivalsRow">
                        @forelse($sec4NewArrivals as $p)
                            @include('frontend.home.partials._best_selling_card', ['product' => $p])
                        @empty
                            <div class="py-4 text-center text-muted w-100">No new arrivals found.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Next Arrow -->
            <button type="button" class="btn btn-white rounded-circle shadow border position-absolute top-50 end-0 translate-middle-y d-flex align-items-center justify-content-center carousel-arrow-nav carousel-arrow-next" onclick="scrollActiveBestSellingTab('bestSellingTabContentSec4', 340)" title="Next">
                <i class="bi bi-chevron-right fs-6"></i>
            </button>
        </div>
    </div>

    <!-- 10. VISUAL CATEGORY & SUBCATEGORY HUB (16 Standard Industry Cards Matching Reference) -->
    <style>
        .category-hub-card {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            min-height: 335px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.25rem 1.15rem 1.35rem;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        }
        .category-hub-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5px;
            background: linear-gradient(90deg, #2563eb 0%, #38bdf8 50%, #eab308 100%);
            opacity: 0.85;
            transition: all 0.3s ease;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
        .category-hub-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 36px -6px rgba(15, 23, 42, 0.11), 0 0 0 1px rgba(37, 99, 235, 0.16) !important;
            border-color: #93c5fd !important;
        }
        .category-hub-card:hover::before {
            height: 5px;
            opacity: 1;
            box-shadow: 0 2px 12px rgba(37, 99, 235, 0.35);
        }
        .category-view-all {
            font-size: 0.72rem;
            font-weight: 600;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
        }
        .category-view-all i {
            transition: transform 0.25s ease;
        }
        .category-hub-card:hover .category-view-all {
            color: #1d4ed8;
        }
        .category-hub-card:hover .category-view-all i {
            transform: translateX(4px);
        }
        .subcat-hub-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px 6px;
            justify-items: center;
            align-items: start;
            width: 100%;
            margin-top: auto;
            margin-bottom: auto;
            padding-top: 4px;
        }
        .subcat-item {
            transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .subcat-item:hover {
            transform: translateY(-5px);
        }
        .subcat-img-box {
            width: 62px;
            height: 62px;
            background: #f8fafc;
            border: 1.5px solid #edf2f7;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .subcat-img-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .subcat-item:hover .subcat-img-box {
            border-color: #3b82f6 !important;
            background: #ffffff !important;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.2), 0 0 0 3px rgba(59, 130, 246, 0.12) !important;
        }
        .subcat-item:hover .subcat-img-box img {
            transform: scale(1.18);
        }
        .subcat-img-box::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                60deg,
                transparent 30%,
                rgba(255, 255, 255, 0.6) 50%,
                transparent 70%
            );
            transform: rotate(25deg) translateY(-100%);
            transition: transform 0.6s ease;
            pointer-events: none;
        }
        .subcat-item:hover .subcat-img-box::after {
            transform: rotate(25deg) translateY(100%);
        }
        .subcat-name {
            font-size: 0.72rem;
            line-height: 1.22;
            font-weight: 550;
            color: #334155;
            margin-top: 5px;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 30px;
            word-break: break-word;
            transition: color 0.2s ease, font-weight 0.2s ease;
        }
        .subcat-item:hover .subcat-name {
            color: #2563eb !important;
            font-weight: 600 !important;
        }
        @media (max-width: 575.98px) {
            .category-hub-card {
                min-height: 300px;
                padding: 1.1rem 0.9rem;
            }
            .subcat-img-box {
                width: 54px;
                height: 54px;
            }
            .subcat-name {
                font-size: 0.67rem;
            }
        }
    </style>
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold text-dark text-uppercase mb-1" style="font-size: 1.15rem; letter-spacing: 0.5px;">
                    BROWSE SUPPLIES BY CATEGORY
                </h5>
                <div style="width: 50px; height: 3px; background: #eab308; border-radius: 2px;"></div>
            </div>
            <a href="{{ route('products.index') }}" class="text-primary fw-semibold small text-decoration-none d-flex align-items-center gap-1">
                <span>View All Categories</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
            @foreach($visualCategoryHub as $cat)
                <div class="col">
                    <div class="card h-100 category-hub-card">
                        <!-- Card Header -->
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                            <h6 class="fw-bold text-dark text-uppercase mb-0 text-truncate" style="font-size: 0.82rem; letter-spacing: 0.4px; color: #0f172a;" title="{{ $cat['title'] }}">
                                {{ $cat['title'] }}
                            </h6>
                            <a href="{{ $cat['view_all_url'] }}" class="category-view-all flex-shrink-0 ms-2">
                                <span>View All</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <!-- Subcategories Grid (Image 1 Layout: 4 items per row, centered bottom row) -->
                        <div class="subcat-hub-grid">
                            @php
                                $totalSubs = count($cat['subcategories']);
                            @endphp
                            @foreach($cat['subcategories'] as $idx => $sub)
                                @php
                                    $gridColStyle = '';
                                    if ($totalSubs === 6) {
                                        if ($idx === 4) $gridColStyle = 'grid-column: 2;';
                                        elseif ($idx === 5) $gridColStyle = 'grid-column: 3;';
                                    } elseif ($totalSubs === 5) {
                                        if ($idx === 4) $gridColStyle = 'grid-column: 2 / span 2;';
                                    }
                                @endphp
                                <a href="{{ $sub['url'] }}" 
                                   class="text-decoration-none text-center subcat-item d-flex flex-column align-items-center" 
                                   style="{{ $gridColStyle }} width: 100%; max-width: 74px;" 
                                   title="{{ $sub['name'] }}">
                                    <div class="subcat-img-box mb-1">
                                        <img src="{{ $sub['image'] }}" 
                                             alt="{{ $sub['name'] }}" 
                                             loading="lazy" 
                                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80';">
                                    </div>
                                    <span class="subcat-name">
                                        {{ $sub['name'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- 11. FOUR QUICK ACTION PROMO CARDS (MATCHING REFERENCE SCREENSHOT) -->
    <!-- ============================================================== -->
    <div class="quick-promo-cards-section" id="quickPromoCardsSection">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
            @php
                $promoCategoryLabels = [
                    'WHOLESALE HUB',
                    'EXPRESS DISPATCH',
                    'SUPER SAVER',
                    'CUSTOM RFQ'
                ];
            @endphp
            @foreach($quickPromoCards as $idx => $card)
                <div class="col">
                    <div class="card h-100 border-0 rounded-4 overflow-hidden position-relative quick-action-promo-card" 
                         style="background: {{ $card->bg_gradient ?? 'linear-gradient(135deg, #091b3a 0%, #061226 100%)' }};">
                        
                        <!-- Right-aligned 3D product/logistics visual -->
                        <div class="promo-card-art-box">
                            <img src="{{ $card->image_url ?? asset('images/quick_promos/bulk_order_pallet.jpg') }}" 
                                 alt="{{ $card->title_line1 }} {{ $card->title_line2 }}" 
                                 class="promo-card-art-img">
                            <!-- Subtle left fade for seamless blend -->
                            <div class="promo-art-overlay"></div>
                        </div>

                        <!-- Left-aligned Text Content -->
                        <div class="card-body p-3.5 p-lg-4 position-relative d-flex flex-column justify-content-between z-2" style="max-width: 60%;">
                            <div>
                                <span class="quick-promo-badge">
                                    {{ $promoCategoryLabels[$idx % 4] }}
                                </span>
                                <div class="fw-bold text-white text-uppercase" style="font-size: 13.5px; letter-spacing: 0.5px; line-height: 1.2;">
                                    {{ $card->title_line1 }}
                                </div>
                                <div class="text-uppercase" style="font-size: 18px; font-weight: 900; letter-spacing: 0.5px; line-height: 1.15; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                    {{ $card->title_line2 }}
                                </div>
                                <p class="mb-0 mt-1.5 text-white-50" style="font-size: 11.5px; line-height: 1.35; font-weight: 500;">
                                    {{ $card->subtitle }}
                                </p>
                            </div>

                            <div class="mt-3">
                                <a href="{{ $card->button_url ?: '/quotations' }}" 
                                   class="btn text-white quick-promo-btn d-inline-flex align-items-center gap-1.5" 
                                   style="background: {{ $card->button_color ?: '#ff5400' }}; border: none;">
                                    <span>{{ $card->button_text ?: 'Request Quote' }}</span>
                                    <i class="bi bi-chevron-right" style="font-size: 10px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- "TRENDING NOW" SHOWCASE (MATCHING REFERENCE SCREENSHOT)        -->
    <!-- ============================================================== -->
    @if($trendingNowActive ?? true)
    <div class="trending-now-section position-relative" id="trendingNowShowcase">
        <!-- Section Header: TRENDING NOW (Left) & View All -> (Right) -->
        <div class="trending-header-wrap">
            <div class="d-flex align-items-center gap-2.5">
                <span class="trending-fire-badge">
                    <i class="bi bi-fire"></i>
                </span>
                <div>
                    <h5 class="trending-now-heading mb-0">
                        {{ $trendingNowTitle ?? 'TRENDING NOW' }}
                    </h5>
                    <div class="trending-now-subheading d-none d-sm-block">
                        Fast-moving wholesale building materials & verified site supplies
                    </div>
                </div>
            </div>

            <a href="{{ $trendingNowViewAllUrl ?? route('products.index', ['filter' => 'trending']) }}" 
               class="trending-view-all-btn">
                <span>{{ $trendingNowViewAllText ?? 'View All Trending' }}</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Carousel Row with Floating Left/Right Arrows & Smooth Scroll -->
        <div class="position-relative carousel-slider-wrapper">
            <!-- Left Prev Arrow -->
            <button type="button" 
                    class="carousel-arrow-nav carousel-arrow-prev position-absolute top-50 start-0 translate-middle-y" 
                    id="trendingNowPrevBtn"
                    onclick="scrollTrendingNowRow(-380)" 
                    style="left: -16px;"
                    title="Previous Products">
                <i class="bi bi-chevron-left fs-6"></i>
            </button>

            <!-- Cards Scroll Container -->
            <div class="trending-now-scroll-track" 
                 id="trendingNowProductsTrack">
                @forelse($trendingNowProducts as $p)
                    @include('frontend.home.partials._trending_now_card', ['product' => $p])
                @empty
                    <div class="py-5 text-center text-muted w-100">
                        <i class="bi bi-box-seam fs-2 d-block mb-2 text-muted"></i>
                        No trending products available.
                    </div>
                @endforelse
            </div>

            <!-- Right Next Arrow -->
            <button type="button" 
                    class="carousel-arrow-nav carousel-arrow-next position-absolute top-50 end-0 translate-middle-y" 
                    id="trendingNowNextBtn"
                    onclick="scrollTrendingNowRow(380)" 
                    style="right: -16px;"
                    title="Next Products">
                <i class="bi bi-chevron-right fs-6"></i>
            </button>
        </div>

        <!-- Dynamic Scroll Progress Indicator -->
        <div class="trending-scroll-progress-wrap" title="Scroll Progress">
            <div class="trending-scroll-progress-bar" id="trendingNowProgressBar"></div>
        </div>
    </div>
    @endif

    <!-- ============================================================== -->
    <!-- 12. NEW ARRIVALS & WHY CHOOSE GARUDO                          -->
    <!-- ============================================================== -->
    <style>
        /* ── New Arrivals Section ── */
        #new-arrivals-section {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px 18px 18px;
            border: 1px solid #e8edf2;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }
        .na-section-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
            margin-bottom: 14px;
        }
        /* Product cards strip */
        .na-cards-strip {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: stretch;
        }
        .na-product-card {
            flex: 0 0 calc((100% - 5 * 10px) / 6);
            min-width: 0;
            background: #fff;
            border: 1px solid #e8edf2;
            border-radius: 10px;
            padding: 8px 8px 10px;
            cursor: pointer;
            transition: box-shadow 0.18s ease, transform 0.18s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
        }
        .na-product-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .na-product-img-wrap {
            width: 100%;
            aspect-ratio: 1 / 1.15;
            border-radius: 7px;
            overflow: hidden;
            background: #f4f6f9;
            margin-bottom: 7px;
            flex-shrink: 0;
        }
        .na-product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .na-product-name {
            font-size: 11.5px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.35;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .na-product-price {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            margin-top: auto;
        }
        /* Why Choose card */
        .na-why-choose-card {
            background: #ffffff;
            border: 1px solid #e8edf2;
            border-radius: 10px;
            padding: 16px 14px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .na-why-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 13px;
            letter-spacing: -0.2px;
        }
        .na-why-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 12px;
        }
        .na-why-item {
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .na-why-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #ff5400;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #fff;
            font-size: 11px;
        }
        .na-why-text {
            font-size: 12px;
            font-weight: 500;
            color: #334155;
            line-height: 1.35;
        }
        /* Stats card */
        .na-stats-card {
            background: #0f1c3f;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            text-align: center;
            height: 100%;
            padding: 12px 10px;
        }
        .na-stat-block {
            width: 100%;
            padding: 8px 0;
        }
        .na-stat-block + .na-stat-block {
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .na-stat-number {
            font-size: 1.5rem;
            font-weight: 900;
            color: #ffffff;
            line-height: 1;
            letter-spacing: -0.5px;
        }
        .na-stat-label {
            font-size: 10.5px;
            color: rgba(255,255,255,0.55);
            margin-top: 3px;
            font-weight: 400;
        }
    </style>

    <div class="mb-4" id="new-arrivals-section">
        <div class="na-section-title">New Arrivals</div>

        {{-- Main outer row: [Cards Strip] [Why Choose] [Stats] --}}
        <div class="d-flex gap-3 align-items-stretch" style="min-height: 155px;">

            {{-- LEFT: 6 Product Cards --}}
            <div class="na-cards-strip flex-grow-1" style="min-width:0;">
                @php
                    $defaultNewArrivals = [
                        ['name' => 'Smart Lock Door Handle', 'price' => '₹2,450', 'unit' => '', 'image' => asset('images/new_arrivals/smart_lock.jpg'), 'url' => route('products.index', ['search' => 'smart lock'])],
                        ['name' => 'WPC Wall Panel', 'price' => '₹320', 'unit' => 'Sq.Ft.', 'image' => asset('images/new_arrivals/wpc_panel.jpg'), 'url' => route('products.index', ['search' => 'wpc panel'])],
                        ['name' => 'Designer Basin', 'price' => '₹3,980', 'unit' => '', 'image' => asset('images/new_arrivals/designer_basin.jpg'), 'url' => route('products.index', ['search' => 'designer basin'])],
                        ['name' => 'False Ceiling Grid', 'price' => '₹65', 'unit' => 'Sq.Ft.', 'image' => asset('images/new_arrivals/false_ceiling.jpg'), 'url' => route('products.index', ['search' => 'false ceiling'])],
                        ['name' => 'Epoxy Flooring', 'price' => '₹120', 'unit' => 'Sq.Ft.', 'image' => asset('images/new_arrivals/epoxy_flooring.jpg'), 'url' => route('products.index', ['search' => 'epoxy flooring'])],
                        ['name' => 'Exterior Cladding Tile', 'price' => '₹65', 'unit' => 'Sq.Ft.', 'image' => asset('images/new_arrivals/stone_cladding.jpg'), 'url' => route('products.index', ['search' => 'cladding tile'])],
                    ];
                    $naItems = (isset($newArrivalsList) && $newArrivalsList->isNotEmpty())
                        ? $newArrivalsList->take(6)->map(fn($p) => [
                            'name'  => $p->name,
                            'price' => '₹' . (is_numeric($p->selling_price) ? number_format($p->selling_price) : $p->selling_price),
                            'unit'  => $p->unit ?? '',
                            'image' => $p->image_url,
                            'url'   => $p->url ?? route('products.index', ['search' => $p->name]),
                          ])->all()
                        : $defaultNewArrivals;
                @endphp

                @foreach($naItems as $naItem)
                    <a href="{{ $naItem['url'] ?? route('products.index') }}" class="na-product-card text-decoration-none">
                        <div class="na-product-img-wrap">
                            <img src="{{ $naItem['image'] }}"
                                 alt="{{ $naItem['name'] }}"
                                 onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.png') }}';">
                        </div>
                        <div class="na-product-name">{{ $naItem['name'] }}</div>
                        <div class="na-product-price">
                            {{ $naItem['price'] }}@if(!empty($naItem['unit']))<span style="font-weight:500;font-size:10.5px;color:#64748b;">/ {{ $naItem['unit'] }}</span>@endif
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- MIDDLE: Why Choose Garudo --}}
            <div style="flex: 0 0 240px; min-width: 0;">
                <div class="na-why-choose-card">
                    <div class="na-why-title">{{ $whyChooseTitle ?? 'Why Choose Garudo?' }}</div>
                    <div class="na-why-grid">
                        @php
                            $displayPoints = !empty($whyChoosePoints) ? $whyChoosePoints : [
                                '10,000+ Verified Products',
                                'Pan India Delivery Network',
                                '1000+ Verified Vendors',
                                'Secure Payments & GST',
                                'Best Price Guarantee',
                                'Expert Support 24/7',
                            ];
                        @endphp
                        @foreach($displayPoints as $pt)
                            <div class="na-why-item">
                                <span class="na-why-icon"><i class="bi bi-shield-check"></i></span>
                                <span class="na-why-text">{{ $pt }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: Stats Card --}}
            <div style="flex: 0 0 110px; min-width: 0;">
                <div class="na-stats-card">
                    <div class="na-stat-block">
                        <div class="na-stat-number">{{ $whyChooseStats['stat1_number'] ?? '25K+' }}</div>
                        <div class="na-stat-label">{{ $whyChooseStats['stat1_label'] ?? 'Happy Customers' }}</div>
                    </div>
                    <div class="na-stat-block">
                        <div class="na-stat-number">{{ $whyChooseStats['stat2_number'] ?? '10K+' }}</div>
                        <div class="na-stat-label">{{ $whyChooseStats['stat2_label'] ?? 'Projects Served' }}</div>
                    </div>
                    <div class="na-stat-block">
                        <div class="na-stat-number">{{ $whyChooseStats['stat3_number'] ?? '2500+' }}</div>
                        <div class="na-stat-label">{{ $whyChooseStats['stat3_label'] ?? 'Vendors' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- ============================================================== -->
    <!-- IMAGE 2: 3 MEGA PROMOTIONAL BANNERS (ROW 2 - LOWER PLACEMENT)  -->
    <!-- (Row 2: Above Bottom Highlights - Controllable in Admin Panel) -->
    <!-- ============================================================== -->
    @php
        $activeRow2Banners = (isset($promoBannersRow2) && $promoBannersRow2->isNotEmpty())
            ? $promoBannersRow2
            : ((isset($promoBanners) && $promoBanners->count() > 3)
                ? $promoBanners->skip(3)->take(3)
                : ((isset($promoBanners) && $promoBanners->isNotEmpty()) ? $promoBanners->take(3) : collect()));
    @endphp
    <div class="mb-4" id="promo-banners-showcase">
        <div class="row g-3">
            @if($activeRow2Banners->isNotEmpty())
                @foreach($activeRow2Banners->take(3) as $b)
                    <div class="col-lg-4 col-md-12">
                        <div class="card border-0 rounded-4 overflow-hidden position-relative shadow-sm h-100 promo-banner-card" style="background: {{ $b->background_color ?: '#090d16' }}; min-height: 285px; height: 285px;">
                            <div class="position-absolute top-0 end-0 bottom-0 overflow-hidden" style="width: 58%; pointer-events: none; z-index: 0;">
                                <img src="{{ $b->image_url }}" onerror="this.onerror=null; this.src='/images/banners/mega_construction_sale.jpg';" alt="{{ $b->title }}" style="width: 100%; height: 100%; object-fit: cover; object-position: center right;">
                                <div class="position-absolute top-0 start-0 bottom-0 end-0" style="background: linear-gradient(to right, {{ $b->background_color ?: '#090d16' }} 0%, rgba(9, 13, 22, 0.85) 25%, rgba(9, 13, 22, 0) 80%);"></div>
                            </div>
                            
                            <div class="card-body p-4 position-relative d-flex flex-column justify-content-between z-1" style="max-width: 60%;">
                                <div>
                                    <div class="fw-black text-white" style="font-size: 1.45rem; line-height: 1.15; font-weight: 900; letter-spacing: -0.4px;">
                                        {!! nl2br(e($b->title)) !!}
                                        @if(!empty($b->subtitle))
                                            <div class="badge mt-2" style="background: #facc15; color: #0f172a; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 4px;">{{ $b->subtitle }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ $b->link ?: route('products.index') }}" class="btn btn-sm text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background: rgba(15, 23, 42, 0.9); border: 1.5px solid #475569; font-size: 12.5px; backdrop-filter: blur(6px);">
                                        {{ $b->button_text ?: 'Explore Now' }} <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- 13. BOTTOM SERVICE HIGHLIGHTS (5 Dark Navy Blue Pillars)       -->
    <!-- (Exact Match to Image 1)                                       -->
    <!-- ============================================================== -->
    <div class="row g-2.5 mb-4 text-center">
        <div class="col-md col-6">
            <div class="p-3 bg-navy text-white rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #07152b;">
                <div class="mb-1 text-warning"><i class="bi bi-percent fs-4"></i></div>
                <div class="fw-bold small">Bulk Order Discounts</div>
                <div class="text-light text-opacity-75" style="font-size: 10px;">Save up to 40% on truckloads</div>
            </div>
        </div>
        <div class="col-md col-6">
            <div class="p-3 bg-navy text-white rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #07152b;">
                <div class="mb-1 text-warning"><i class="bi bi-file-earmark-check fs-4"></i></div>
                <div class="fw-bold small">GST Invoice Available</div>
                <div class="text-light text-opacity-75" style="font-size: 10px;">100% tax compliant billing</div>
            </div>
        </div>
        <div class="col-md col-4">
            <div class="p-3 bg-navy text-white rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #07152b;">
                <div class="mb-1 text-warning"><i class="bi bi-arrow-counterclockwise fs-4"></i></div>
                <div class="fw-bold small">Easy Returns</div>
                <div class="text-light text-opacity-75" style="font-size: 10px;">7-day hassle-free replacement</div>
            </div>
        </div>
        <div class="col-md col-4">
            <div class="p-3 bg-navy text-white rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #07152b;">
                <div class="mb-1 text-warning"><i class="bi bi-truck fs-4"></i></div>
                <div class="fw-bold small">Express Delivery</div>
                <div class="text-light text-opacity-75" style="font-size: 10px;">Fast delivery to your job site</div>
            </div>
        </div>
        <div class="col-md col-4">
            <div class="p-3 bg-navy text-white rounded-3 h-100 d-flex flex-column justify-content-center" style="background: #07152b;">
                <div class="mb-1 text-warning"><i class="bi bi-headset fs-4"></i></div>
                <div class="fw-bold small">24/7 Support</div>
                <div class="text-light text-opacity-75" style="font-size: 10px;">Dedicated support for builders</div>
            </div>
        </div>
    </div>


    <!-- ============================================================== -->
    <!-- BRAND PARTNERS STRIP (Exact Match to Reference Image)          -->
    <!-- ============================================================== -->
    <style>
        .brand-strip-wrapper {
            width: 100%;
        }
        .brand-strip-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
            position: relative;
            overflow: hidden;
        }
        .brand-trust-box {
            flex-shrink: 0;
            min-width: 195px;
        }
        .brand-trust-title {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.3;
        }
        .brand-trust-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 3px;
        }
        .brand-stars {
            color: #f59e0b;
            font-size: 11px;
            letter-spacing: -0.5px;
        }
        .brand-rating-num {
            font-size: 11px;
            font-weight: 800;
            color: #1e293b;
        }
        .brand-rating-count {
            font-size: 10.5px;
            color: #64748b;
        }
        .brand-strip-divider {
            width: 1px;
            height: 38px;
            background: #e2e8f0;
            flex-shrink: 0;
        }
        .brand-marquee-viewport {
            flex: 1;
            overflow: hidden;
            position: relative;
            mask-image: linear-gradient(to right, transparent, black 4%, black 96%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 4%, black 96%, transparent);
        }
        .brand-marquee-content {
            display: flex;
            align-items: center;
            gap: 36px;
            width: max-content;
            animation: brandContinuousScroll 32s linear infinite;
            will-change: transform;
        }
        .brand-marquee-content:hover {
            animation-play-state: paused;
        }
        @keyframes brandContinuousScroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
        .brand-chip {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.2s ease, opacity 0.2s ease;
            opacity: 0.9;
        }
        .brand-chip:hover {
            transform: scale(1.05);
            opacity: 1;
        }

        /* Brand Chip Logos Styling */
        .brand-logo-ultratech {
            background: #ffcc00;
            color: #000;
            font-weight: 900;
            font-family: Impact, 'Arial Black', sans-serif;
            font-size: 15px;
            padding: 3px 8px;
            border-radius: 3px;
            letter-spacing: 0.5px;
            line-height: 1.1;
            display: inline-block;
            text-align: center;
        }
        .brand-logo-ultratech small {
            font-size: 7.5px;
            font-family: sans-serif;
            font-weight: 800;
            letter-spacing: 1px;
            display: block;
            margin-top: -2px;
        }
        .brand-logo-acc {
            color: #dc2626;
            font-size: 21px;
            font-weight: 900;
            font-family: 'Arial Black', sans-serif;
            font-style: italic;
            letter-spacing: -0.5px;
        }
        .brand-logo-ambuja {
            color: #034694;
            font-weight: 900;
            font-size: 16px;
            letter-spacing: -0.3px;
            line-height: 1;
            display: inline-block;
            text-align: center;
        }
        .brand-logo-ambuja small {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.8px;
            display: block;
        }
        .brand-logo-jsw {
            color: #0284c7;
            font-size: 16px;
            font-weight: 900;
            font-style: italic;
        }
        .brand-logo-jsw small {
            font-size: 11px;
            color: #64748b;
            font-style: normal;
            font-weight: 700;
            margin-left: 2px;
        }
        .brand-logo-kajaria {
            color: #07152b;
            font-size: 17px;
            font-weight: 900;
            font-family: 'Times New Roman', serif;
            letter-spacing: 0.5px;
        }
        .brand-logo-astral {
            color: #0284c7;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: 0.5px;
            line-height: 1;
            display: inline-block;
        }
        .brand-logo-astral span {
            font-size: 8.5px;
            letter-spacing: 1.5px;
            color: #0369a1;
            display: block;
        }
        .brand-logo-tata {
            color: #0284c7;
            font-size: 13.5px;
            font-weight: 900;
            line-height: 1;
            display: inline-block;
        }
        .brand-logo-tata span {
            color: #0f172a;
            font-size: 11.5px;
            letter-spacing: 0.5px;
            display: block;
        }
        .brand-logo-havells {
            color: #dc2626;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.8px;
        }
        .brand-logo-asian {
            color: #dc2626;
            font-weight: 900;
            font-size: 14.5px;
            letter-spacing: -0.2px;
        }
        .brand-logo-asian span {
            color: #1e293b;
        }
        .brand-logo-berger {
            color: #059669;
            font-weight: 900;
            font-size: 14.5px;
            letter-spacing: 0.2px;
        }
        .brand-logo-supreme {
            color: #dc2626;
            font-weight: 900;
            font-size: 15px;
            font-style: italic;
            letter-spacing: 0.5px;
        }
        .brand-logo-finolex {
            color: #0284c7;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: 0.3px;
        }
        .brand-logo-finolex .text-orange {
            color: #ea580c;
        }
        .brand-logo-polycab {
            color: #dc2626;
            font-weight: 900;
            font-size: 14.5px;
            letter-spacing: 0.3px;
        }
        .brand-logo-jaquar {
            color: #0f172a;
            font-weight: 800;
            font-size: 13.5px;
            letter-spacing: 2px;
        }
        .brand-logo-drfixit {
            background: #facc15;
            color: #000;
            font-weight: 900;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 4px;
            display: inline-block;
        }

        .brand-view-all-btn {
            flex-shrink: 0;
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #374151;
            font-weight: 600;
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 6px;
            white-space: nowrap;
            text-decoration: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.15s ease;
        }
        .brand-view-all-btn:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
        }

        /* ============================================================== */
        /* CUSTOMER TESTIMONIALS (Exact Match to Reference Image)         */
        /* ============================================================== */
        .testimonials-card-container {
            background: #ffffff;
            border: 1px solid #e8edf2;
            border-radius: 14px;
            padding: 20px 20px 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .testimonials-section-title {
            font-size: 19px;
            font-weight: 800;
            color: #0d1b3e;
            margin-bottom: 14px;
        }
        .testimonials-track {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 4px 2px 8px;
            cursor: grab;
        }
        .testimonials-track::-webkit-scrollbar {
            display: none;
        }
        .testimonials-track.is-dragging {
            cursor: grabbing;
        }
        .testi-card {
            flex: 0 0 240px;
            background: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 16px 15px 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 160px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .testi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.07);
        }
        .testi-stars {
            color: #f59e0b;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .testi-quote {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 12px;
            flex-grow: 1;
        }
        .testi-author-row {
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .testi-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f1f5f9;
            flex-shrink: 0;
        }
        .testi-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .testi-role {
            font-size: 10.5px;
            color: #94a3b8;
        }
        .testi-nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
            font-size: 12px;
            color: #0f172a;
            transition: background 0.15s ease, transform 0.15s ease;
        }
        .testi-nav-arrow:hover {
            background: #f8fafc;
            transform: translateY(-50%) scale(1.08);
        }
        .testi-arrow-left {
            left: -12px;
        }
        .testi-arrow-right {
            right: -12px;
        }

        /* ============================================================== */
        /* SELL WITH US VENDOR CARD (Exact Match to Reference Image 3)    */
        /* ============================================================== */
        .vendor-sell-card {
            background: linear-gradient(135deg, #09152e 0%, #112247 100%);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 4px 18px rgba(9, 21, 46, 0.25);
            padding: 22px 20px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .vendor-sell-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 14px;
        }
        .vendor-sell-text {
            flex: 1;
        }
        .vendor-sell-title {
            font-weight: 800;
            font-size: 19px;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 6px;
        }
        .vendor-sell-subtitle {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.4;
            margin-bottom: 16px;
        }
        .vendor-sell-btn {
            background: #f59e0b;
            color: #0f172a !important;
            font-weight: 700;
            font-size: 13px;
            padding: 9px 20px;
            border-radius: 20px;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
            transition: all 0.2s ease;
        }
        .vendor-sell-btn:hover {
            background: #fbbf24;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.45);
        }
        .vendor-sell-media {
            flex-shrink: 0;
        }
        .vendor-store-img {
            width: 135px;
            height: 135px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        }
    </style>

    <div class="brand-strip-wrapper mb-4">
        <div class="brand-strip-container">
            <!-- Left Trust Score -->
            <div class="brand-trust-box">
                <div class="brand-trust-title">Trusted by 10,000+ Contractors,<br>Builders &amp; Businesses</div>
                <div class="brand-trust-rating">
                    <span class="brand-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </span>
                    <strong class="brand-rating-num">4.8</strong>
                    <span class="brand-rating-count">(12,540 Reviews)</span>
                </div>
            </div>

            <!-- Divider -->
            <div class="brand-strip-divider"></div>

            <!-- Auto-scrolling Marquee Track (Smooth Infinite Loop) -->
            <div class="brand-marquee-viewport" id="brandMarqueeViewport">
                <div class="brand-marquee-content" id="brandMarqueeContent">
                    <!-- Set 1 of Brand Chips -->
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="UltraTech Cement">
                        <span class="brand-logo-ultratech">UltraTech<small>CEMENT</small></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="ACC Cement">
                        <span class="brand-logo-acc">ACC</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Ambuja Cement">
                        <span class="brand-logo-ambuja"><i class="bi bi-triangle-fill me-1"></i>Ambuja<small>Cement</small></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="JSW Cement">
                        <span class="brand-logo-jsw">JSW <small>Cement</small></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Kajaria Tiles">
                        <span class="brand-logo-kajaria">Kajaria</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="ASTRAL PIPES">
                        <span class="brand-logo-astral"><i class="bi bi-triangle-half me-1"></i>ASTRAL <span>PIPES</span></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Tata Tiscon">
                        <span class="brand-logo-tata">TATA <span>TISCON</span></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Havells">
                        <span class="brand-logo-havells"><i class="bi bi-fire me-1"></i>HAVELLS</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Asian Paints">
                        <span class="brand-logo-asian">● asian<span>paints</span></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Berger Paints">
                        <span class="brand-logo-berger"><i class="bi bi-brush me-1"></i>Berger</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Supreme">
                        <span class="brand-logo-supreme">Supreme</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Finolex">
                        <span class="brand-logo-finolex">Fin<span class="text-orange">o</span>lex</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Polycab">
                        <span class="brand-logo-polycab">POLYCAB</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Jaquar">
                        <span class="brand-logo-jaquar">JAQUAR</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Dr. Fixit">
                        <span class="brand-logo-drfixit">Dr.Fixit</span>
                    </a>

                    <!-- Set 2 for Seamless Loop -->
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="UltraTech Cement">
                        <span class="brand-logo-ultratech">UltraTech<small>CEMENT</small></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="ACC Cement">
                        <span class="brand-logo-acc">ACC</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Ambuja Cement">
                        <span class="brand-logo-ambuja"><i class="bi bi-triangle-fill me-1"></i>Ambuja<small>Cement</small></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="JSW Cement">
                        <span class="brand-logo-jsw">JSW <small>Cement</small></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Kajaria Tiles">
                        <span class="brand-logo-kajaria">Kajaria</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="ASTRAL PIPES">
                        <span class="brand-logo-astral"><i class="bi bi-triangle-half me-1"></i>ASTRAL <span>PIPES</span></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Tata Tiscon">
                        <span class="brand-logo-tata">TATA <span>TISCON</span></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Havells">
                        <span class="brand-logo-havells"><i class="bi bi-fire me-1"></i>HAVELLS</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Asian Paints">
                        <span class="brand-logo-asian">● asian<span>paints</span></span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Berger Paints">
                        <span class="brand-logo-berger"><i class="bi bi-brush me-1"></i>Berger</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Supreme">
                        <span class="brand-logo-supreme">Supreme</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Finolex">
                        <span class="brand-logo-finolex">Fin<span class="text-orange">o</span>lex</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Polycab">
                        <span class="brand-logo-polycab">POLYCAB</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Jaquar">
                        <span class="brand-logo-jaquar">JAQUAR</span>
                    </a>
                    <a href="{{ route('brands.index') }}" class="brand-chip" title="Dr. Fixit">
                        <span class="brand-logo-drfixit">Dr.Fixit</span>
                    </a>
                </div>
            </div>

            <!-- View All Brands Button -->
            <a href="{{ route('brands.index') }}" class="brand-view-all-btn">
                View All Brands
            </a>
        </div>
    </div>

    <!-- 14. CUSTOMER TESTIMONIALS & SELL ON GARUDO BANNER -->
    <div class="row g-3 mb-4 align-items-stretch">
        <!-- Customer Testimonials (Auto-scroll Carousel) -->
        <div class="col-lg-8">
            <div class="testimonials-card-container h-100 d-flex flex-column justify-content-between">
                <div class="testimonials-header">
                    <h4 class="testimonials-section-title">What Our Customers Say</h4>
                </div>

                <div class="testimonials-track-wrap position-relative">
                    <!-- Nav buttons -->
                    <button class="testi-nav-arrow testi-arrow-left" id="testiPrevBtn" title="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <div class="testimonials-track" id="testimonialsTrack">
                        <!-- Review 1 -->
                        <div class="testi-card">
                            <div class="testi-stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testi-quote">"Best platform for all construction materials. Competitive prices and on-time delivery."</p>
                            <div class="testi-author-row">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=60" class="testi-avatar" alt="Ramesh Kumar">
                                <div>
                                    <div class="testi-name">Ramesh Kumar</div>
                                    <div class="testi-role">Contractor, Delhi</div>
                                </div>
                            </div>
                        </div>

                        <!-- Review 2 -->
                        <div class="testi-card">
                            <div class="testi-stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testi-quote">"Wide range of products and trusted vendors. Highly recommended!"</p>
                            <div class="testi-author-row">
                                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=60" class="testi-avatar" alt="Neha Sharma">
                                <div>
                                    <div class="testi-name">Neha Sharma</div>
                                    <div class="testi-role">Architect, Mumbai</div>
                                </div>
                            </div>
                        </div>

                        <!-- Review 3 -->
                        <div class="testi-card">
                            <div class="testi-stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testi-quote">"Tenders and auctions feature is very helpful for our business."</p>
                            <div class="testi-author-row">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=60" class="testi-avatar" alt="Suresh Patel">
                                <div>
                                    <div class="testi-name">Suresh Patel</div>
                                    <div class="testi-role">Builder, Ahmedabad</div>
                                </div>
                            </div>
                        </div>

                        <!-- Review 4 -->
                        <div class="testi-card">
                            <div class="testi-stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testi-quote">"Saved ₹3 Lakhs on TMT steel sourcing. Factory rates direct from Jindal!"</p>
                            <div class="testi-author-row">
                                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&auto=format&fit=crop&q=60" class="testi-avatar" alt="Rajesh Singh">
                                <div>
                                    <div class="testi-name">Rajesh Singh</div>
                                    <div class="testi-role">Civil Contractor, Pune</div>
                                </div>
                            </div>
                        </div>

                        <!-- Review 5 -->
                        <div class="testi-card">
                            <div class="testi-stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                            </div>
                            <p class="testi-quote">"RFQ response within 1 hour. Best B2B platform for building materials in India."</p>
                            <div class="testi-author-row">
                                <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=100&auto=format&fit=crop&q=60" class="testi-avatar" alt="Arjun Nair">
                                <div>
                                    <div class="testi-name">Arjun Nair</div>
                                    <div class="testi-role">Developer, Bangalore</div>
                                </div>
                            </div>
                        </div>

                        <!-- Review 6 -->
                        <div class="testi-card">
                            <div class="testi-stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <p class="testi-quote">"Delivery was prompt with zero breakage. Tiles range is exceptional!"</p>
                            <div class="testi-author-row">
                                <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&auto=format&fit=crop&q=60" class="testi-avatar" alt="Deepa Iyer">
                                <div>
                                    <div class="testi-name">Deepa Iyer</div>
                                    <div class="testi-role">Interior Designer, Chennai</div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end track -->

                    <button class="testi-nav-arrow testi-arrow-right" id="testiNextBtn" title="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sell on Garudo Banner Box (Exact match to Image 3) -->
        <div class="col-lg-4">
            <div class="vendor-sell-card h-100">
                <div class="vendor-sell-body">
                    <div class="vendor-sell-text">
                        <h4 class="vendor-sell-title">Sell With Us &amp; Grow Your Business</h4>
                        <p class="vendor-sell-subtitle">Join thousands of vendors and reach millions of customers</p>
                        <a href="{{ route('vendor.register') }}" class="vendor-sell-btn">
                            Become a Vendor
                        </a>
                    </div>
                    <div class="vendor-sell-media">
                        <img src="{{ asset('images/landing/storefront_vendor.jpg') }}" alt="Store Front" class="vendor-store-img">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- QUICK VIEW INTERACTIVE PRODUCT MODAL                           -->
    <!-- ============================================================== -->
    <div class="modal fade" id="quickViewProductModal" tabindex="-1" aria-labelledby="quickViewProductModalLabel" aria-hidden="true">
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
                                <button type="button" id="qvModalAddToCartBtn" class="btn btn-dark fw-bold px-3 py-2 rounded-3 flex-fill" style="background: #0f172a;">
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

</div>
@endsection

@push('scripts')
<style>
    /* Trending Category Pills */
    .trending-cat-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .trending-cat-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .trending-cat-btn.active {
        background: #0f172a !important;
        color: #ffffff !important;
        border-color: #0f172a !important;
    }

    /* Best Selling Tabs */
    .best-selling-tabs .best-tab-btn {
        border-bottom: 2.5px solid transparent !important;
        border-radius: 0 !important;
        color: #64748b;
        background: transparent !important;
    }
    .best-selling-tabs .best-tab-btn:hover {
        color: #0f172a;
    }
    .best-selling-tabs .best-tab-btn.active {
        color: #0f172a !important;
        font-weight: 800 !important;
        border-bottom-color: #f97316 !important;
        background: transparent !important;
    }

    /* Promo Banners Hover Effect */
    .promo-banner-card {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }
    .promo-banner-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.25) !important;
    }
    .promo-banner-card:hover .position-absolute.top-0.end-0 {
        transform: scale(1.04);
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
</style>
<script>
    // Live ticking countdown timer for Deals of the Day (counts down to midnight)
    (function initHeroCountdown() {
        function updateTimer() {
            const now = new Date();
            const endOfDay = new Date();
            endOfDay.setHours(23, 59, 59, 999);
            const diffMs = endOfDay - now;
            if (diffMs <= 0) {
                return;
            }
            const hours = Math.floor((diffMs / (1000 * 60 * 60)) % 24);
            const minutes = Math.floor((diffMs / (1000 * 60)) % 60);
            const seconds = Math.floor((diffMs / 1000) % 60);

            const pad = (n) => String(n).padStart(2, '0');
            const hEl = document.getElementById('heroTimerHours');
            const mEl = document.getElementById('heroTimerMinutes');
            const sEl = document.getElementById('heroTimerSeconds');

            if (hEl) hEl.textContent = pad(hours);
            if (mEl) mEl.textContent = pad(minutes);
            if (sEl) sEl.textContent = pad(seconds);
        }
        updateTimer();
        setInterval(updateTimer, 1000);
    })();

    // Horizontal Scroll for Sponsored Products Carousel
    function scrollSponsoredTrack(direction) {
        const track = document.getElementById('sponsoredScrollTrack');
        if (track) {
            track.scrollBy({ left: direction * 190, behavior: 'smooth' });
        }
    }

    // Universal Horizontal Scroller (Continuous Infinite Auto-Scroll + Mouse Wheel + Grab & Drag + Nav Arrows)
    // Runs non-stop ("ruk ruk k nahi") with seamless clone looping and frame-rate independent 60fps/120fps motion
    function setupHorizontalScroller(trackId, prevBtnId, nextBtnId, step = 320, autoScrollSpeed = 0.5) {
        const track = document.getElementById(trackId);
        if (!track) return;

        // CRITICAL: Force browser smooth-scroll to auto on track so requestAnimationFrame never stutters
        track.style.scrollBehavior = 'auto';

        const prevBtn = prevBtnId ? document.getElementById(prevBtnId) : null;
        const nextBtn = nextBtnId ? document.getElementById(nextBtnId) : null;

        const originalChildren = Array.from(track.children);
        if (originalChildren.length < 2) return;

        // Clone cards for seamless infinite continuous loop (never hits end wall, never rewinds)
        originalChildren.forEach(child => {
            const clone = child.cloneNode(true);
            clone.setAttribute('data-is-clone', 'true');
            track.appendChild(clone);
        });

        // Compute exact loop width between original cards and clone set
        function getLoopWidth() {
            const firstClone = track.querySelector('[data-is-clone="true"]');
            if (firstClone && originalChildren[0]) {
                const dist = firstClone.offsetLeft - originalChildren[0].offsetLeft;
                if (dist > 40) return dist;
            }
            return track.scrollWidth / 2;
        }

        let isHovered = false;
        let isDown = false;
        let isInteracting = false;
        let interactionTimeout = null;
        let startX = 0;
        let scrollLeftStart = 0;
        let dragMoved = false;
        let scrollPos = track.scrollLeft;
        let lastFrameTime = 0;

        function pauseAutoScroll(ms = 1800) {
            isInteracting = true;
            if (interactionTimeout) clearTimeout(interactionTimeout);
            interactionTimeout = setTimeout(() => {
                isInteracting = false;
                scrollPos = track.scrollLeft;
            }, ms);
        }

        // 1. Mouse Hover & Touch Handlers (Smooth pause on hover, instant resume on leave)
        track.addEventListener('mouseenter', () => {
            isHovered = true;
            scrollPos = track.scrollLeft;
        });
        track.addEventListener('mouseleave', () => {
            isHovered = false;
            scrollPos = track.scrollLeft;
        });
        track.addEventListener('touchstart', () => {
            isHovered = true;
            scrollPos = track.scrollLeft;
        }, { passive: true });
        track.addEventListener('touchend', () => {
            setTimeout(() => {
                isHovered = false;
                scrollPos = track.scrollLeft;
            }, 1200);
        }, { passive: true });

        // 2. Mouse Wheel Scroll (horizontal / vertical wheel conversion with infinite wrap)
        track.addEventListener('wheel', (e) => {
            const delta = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY;
            if (delta !== 0) {
                e.preventDefault();
                pauseAutoScroll(2000);
                const loopWidth = getLoopWidth();
                scrollPos += delta * 0.9;
                if (loopWidth > 40) {
                    while (scrollPos >= loopWidth) scrollPos -= loopWidth;
                    while (scrollPos < 0) scrollPos += loopWidth;
                }
                track.scrollLeft = scrollPos;
            }
        }, { passive: false });

        // 3. Mouse Grab & Drag (Seamless infinite dragging in both directions)
        track.addEventListener('mousedown', (e) => {
            if (e.button !== 0) return;
            isDown = true;
            dragMoved = false;
            pauseAutoScroll(2500);
            track.classList.add('is-dragging');
            startX = e.pageX - track.offsetLeft;
            scrollLeftStart = track.scrollLeft;
            scrollPos = track.scrollLeft;
        });

        window.addEventListener('mouseup', () => {
            if (!isDown) return;
            isDown = false;
            track.classList.remove('is-dragging');
            scrollPos = track.scrollLeft;
            pauseAutoScroll(1500);
            setTimeout(() => { dragMoved = false; }, 60);
        });

        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.4;
            if (Math.abs(walk) > 4) dragMoved = true;

            const loopWidth = getLoopWidth();
            let newPos = scrollLeftStart - walk;
            if (loopWidth > 40) {
                while (newPos >= loopWidth) newPos -= loopWidth;
                while (newPos < 0) newPos += loopWidth;
            }
            track.scrollLeft = newPos;
            scrollPos = newPos;
        });

        // Prevent accidental card navigation on drag release
        track.addEventListener('click', (e) => {
            if (dragMoved) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);

        // 4. Smooth Programmatic Stepping for Nav Buttons (always enabled, seamless infinite wrap)
        let animRAF = null;
        function smoothStep(offset) {
            pauseAutoScroll(2500);
            if (animRAF) cancelAnimationFrame(animRAF);

            const loopWidth = getLoopWidth();
            const start = track.scrollLeft;
            const duration = 380; // ms
            const startTime = performance.now();

            function anim(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out cubic
                const ease = 1 - Math.pow(1 - progress, 3);
                let current = start + offset * ease;

                if (loopWidth > 40) {
                    while (current >= loopWidth) current -= loopWidth;
                    while (current < 0) current += loopWidth;
                }

                track.scrollLeft = current;
                scrollPos = current;

                if (progress < 1) {
                    animRAF = requestAnimationFrame(anim);
                } else {
                    animRAF = null;
                }
            }
            animRAF = requestAnimationFrame(anim);
        }

        if (prevBtn) {
            prevBtn.style.opacity = '1';
            prevBtn.style.pointerEvents = 'auto';
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                smoothStep(-step);
            });
            prevBtn.addEventListener('mouseenter', () => { isHovered = true; });
            prevBtn.addEventListener('mouseleave', () => { isHovered = false; });
        }

        if (nextBtn) {
            nextBtn.style.opacity = '1';
            nextBtn.style.pointerEvents = 'auto';
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                smoothStep(step);
            });
            nextBtn.addEventListener('mouseenter', () => { isHovered = true; });
            nextBtn.addEventListener('mouseleave', () => { isHovered = false; });
        }

        // 5. Continuous Non-Stop Infinite Auto-Scroll Engine (60fps/120fps Sub-Pixel)
        if (autoScrollSpeed > 0) {
            function autoScrollStep(timestamp) {
                if (!lastFrameTime) lastFrameTime = timestamp;
                const deltaTime = Math.min(timestamp - lastFrameTime, 50); // cap at 50ms to prevent jumps on tab switch
                lastFrameTime = timestamp;

                if (!isHovered && !isInteracting && !isDown) {
                    const loopWidth = getLoopWidth();
                    if (loopWidth > 40) {
                        // Frame-rate independent constant velocity drift
                        scrollPos += autoScrollSpeed * (deltaTime / 16.667);

                        // Seamless continuous wrap without ANY pause, stutter or rewind
                        while (scrollPos >= loopWidth) {
                            scrollPos -= loopWidth;
                        }
                        while (scrollPos < 0) {
                            scrollPos += loopWidth;
                        }

                        track.scrollLeft = scrollPos;
                    }
                }
                requestAnimationFrame(autoScrollStep);
            }
            requestAnimationFrame(autoScrollStep);
        }
    }

    // ═══ Tender/Auction Detail Popup Modal System ═══
    function initTenderPopupModal() {
        const overlay = document.getElementById('tenderModalOverlay');
        const card = document.getElementById('tenderModalCard');
        const closeBtn = document.getElementById('tenderModalClose');
        if (!overlay || !card) return;

        // Rich descriptions for each card (keyed by title substring)
        const tenderDescriptions = {
            'Commercial Building': 'Supply and installation of structural steel, RCC framework, MEP services, facade cladding, and interior finishing for a 12-story commercial complex. Includes HVAC ducting, fire suppression systems, and elevator installation. All materials must comply with IS standards.',
            'Steel & Rebars': 'Surplus inventory clearance of TMT Fe-500D rebars (8mm–32mm), MS angles, channels, and HR plates from major infrastructure project. All material is ISI-certified with mill test certificates available. Inspection available at Bhiwandi warehouse.',
            'School Building': 'Complete civil construction of a 3-story government primary school including foundation, superstructure, plastering, flooring, sanitary fittings, electrical wiring, and boundary wall. Work to follow CPWD specifications with 24-month defect liability.',
            'Excavator': 'Multi-lot auction of used heavy equipment including JCB 3DX backhoe loaders, Komatsu PC200 excavators, and Caterpillar 950H wheel loaders. All machines serviced within last 6 months. Individual lot bidding available.',
            'Residential Tower': 'Phase 2 of premium 28-floor residential tower requiring supply of AAC blocks, ready-mix concrete (M40 grade), aluminum formwork panels, waterproofing membranes, and premium sanitary ware. Phased delivery schedule over 18 months.',
            'Cement Bags': 'Direct-from-factory surplus lot of 5,000 metric tons of OPC Grade 53 cement in 50kg bags. Leading manufacturer brand. Fresh production with 90-day shelf life remaining. FOB plant gate price. Minimum bid for entire lot.',
            'Highway Drainage': 'Design, supply, and construction of box culverts, RCC pipe culverts, and surface drainage network for 42km highway stretch. Includes earth excavation, pipe laying, manhole construction, and road restoration works.',
            'Tower Cranes': 'Potain MC 310 tower cranes (2 units) with 12-ton lifting capacity and 65m jib length, plus Haki modular scaffolding system (8,000 sq.m coverage). Equipment in excellent working condition with valid safety certificates.',
            'Solar': 'End-to-end EPC contract for municipal solar street lighting (2,500 poles) and rooftop solar installations (500 kWp) across smart city zones. Includes LED fixtures, lithium batteries, IoT-based monitoring, and 5-year AMC.',
            'Vitrified Tiles': 'Premium Italian-design vitrified tiles (600×1200mm, 800×1600mm) and engineered marble slabs. GVT and PGVT finish in multiple designs. Factory seconds and export surplus at 40-60% below retail MRP.',
            'Hospital': 'Complete civil, structural, MEP, and medical gas piping works for a 250-bed multi-specialty hospital. Scope includes OT complex, ICU units, radiology suite shielding, CSSD, and medical waste management infrastructure.',
            'Copper Cables': 'Industrial-grade copper power cables (1.1kV to 33kV), control cables, XLPE insulated, armoured variants. Plus ABB/Schneider switchgear panels (ACB, MCCB, VCB). All items with warranty and test reports.'
        };

        const tenderTags = {
            'Commercial Building': ['Structural Steel', 'RCC', 'MEP', 'Facade', 'HVAC'],
            'Steel & Rebars': ['TMT Bars', 'Fe-500D', 'MS Angles', 'HR Plates', 'ISI Certified'],
            'School Building': ['Civil Works', 'CPWD Specs', 'Govt. Project', 'Flooring', 'Electrical'],
            'Excavator': ['JCB 3DX', 'Komatsu PC200', 'CAT 950H', 'Heavy Equipment'],
            'Residential Tower': ['AAC Blocks', 'RMC M40', 'Aluminum Formwork', 'Waterproofing'],
            'Cement Bags': ['OPC 53 Grade', '5000 MT', 'Factory Direct', 'Bulk Lot'],
            'Highway Drainage': ['Box Culvert', 'RCC Pipes', 'Earth Excavation', 'NH-48'],
            'Tower Cranes': ['Potain MC 310', '12T Capacity', 'Haki Scaffolding', 'Safety Certified'],
            'Solar': ['EPC Contract', 'Solar LED', 'IoT Monitoring', 'Smart City', '5yr AMC'],
            'Vitrified Tiles': ['GVT/PGVT', '600×1200', 'Italian Design', 'Export Surplus'],
            'Hospital': ['250-Bed', 'OT Complex', 'Medical Gas', 'Radiology', 'CSSD'],
            'Copper Cables': ['XLPE 33kV', 'ABB Switchgear', 'Armoured Cable', 'VCB Panel']
        };

        function findMatchingKey(title, map) {
            for (const key of Object.keys(map)) {
                if (title.includes(key)) return key;
            }
            return null;
        }

        function openTenderModal(cardEl) {
            // Extract data from card DOM
            const titleEl = cardEl.querySelector('.tender-card-title');
            const locationEl = cardEl.querySelector('.tender-card-location');
            const metaEl = cardEl.querySelector('.tender-card-meta');
            const valEl = cardEl.querySelector('.tender-card-val');
            const imgEl = cardEl.querySelector('.tender-card-img');
            const badgeEl = cardEl.querySelector('.badge-tender, .badge-auction');
            const isAuction = !!cardEl.querySelector('.badge-auction');

            const title = titleEl ? titleEl.textContent.trim() : '';
            const location = locationEl ? locationEl.textContent.trim() : '';
            const meta = metaEl ? metaEl.textContent.trim() : '';
            const value = valEl ? valEl.textContent.trim() : '';
            const imgSrc = imgEl ? imgEl.src : '';

            // Populate modal
            document.getElementById('tenderModalImg').src = imgSrc;
            document.getElementById('tenderModalImg').alt = title;
            document.getElementById('tenderModalTitle').textContent = title;

            const badgeSpan = document.getElementById('tenderModalBadge');
            badgeSpan.className = 'tender-modal-badge ' + (isAuction ? 'tender-modal-badge-auction' : 'tender-modal-badge-tender');
            badgeSpan.innerHTML = '<span class="' + (isAuction ? 'live-pulse-dot' : 'live-pulse-dot-green') + '"></span> ' + (isAuction ? 'LIVE AUCTION' : 'LIVE TENDER');

            // Info grid
            const infoGrid = document.getElementById('tenderModalInfoGrid');
            infoGrid.innerHTML = `
                <div class="tender-modal-info-item">
                    <div class="tender-modal-info-label"><i class="bi bi-geo-alt me-1"></i>Location</div>
                    <div class="tender-modal-info-value">${location.replace(/^[\s\uFEFF\xA0]*/, '')}</div>
                </div>
                <div class="tender-modal-info-item">
                    <div class="tender-modal-info-label"><i class="bi bi-${isAuction ? 'hammer' : 'calendar-event'} me-1"></i>${isAuction ? 'Countdown' : 'Deadline'}</div>
                    <div class="tender-modal-info-value" style="color: ${isAuction ? '#dc2626' : '#0f172a'}">${meta.replace(/^(Deadline:|Ends In:)\s*/, '')}</div>
                </div>
                <div class="tender-modal-info-item">
                    <div class="tender-modal-info-label"><i class="bi bi-currency-rupee me-1"></i>${isAuction ? 'Starting Bid' : 'Estimated Value'}</div>
                    <div class="tender-modal-info-value" style="color: ${isAuction ? '#0f172a' : '#2563eb'}">${value.replace(/^(Est\. Value:|Starting Bid:)\s*/, '')}</div>
                </div>
                <div class="tender-modal-info-item">
                    <div class="tender-modal-info-label"><i class="bi bi-shield-check me-1"></i>Status</div>
                    <div class="tender-modal-info-value" style="color: #16a34a"><i class="bi bi-circle-fill me-1" style="font-size:7px"></i> Active & Open</div>
                </div>
            `;

            // Description
            const descKey = findMatchingKey(title, tenderDescriptions);
            const desc = descKey ? tenderDescriptions[descKey] : 'Detailed specifications and scope of work available upon request. Contact our procurement team for complete tender documents and bid submission guidelines.';
            document.getElementById('tenderModalDesc').textContent = desc;

            // Tags
            const tagsContainer = document.getElementById('tenderModalTags');
            const tagKey = findMatchingKey(title, tenderTags);
            const tags = tagKey ? tenderTags[tagKey] : ['Construction', 'B2B', 'Garudo Verified'];
            tagsContainer.innerHTML = tags.map(t => `<span class="tender-modal-tag">${t}</span>`).join('');

            // Footer buttons
            const footer = document.getElementById('tenderModalFooter');
            if (isAuction) {
                footer.innerHTML = `
                    <a href="${cardEl.querySelector('.tender-card-btn').href}" class="tender-modal-btn tender-modal-btn-auction">
                        <i class="bi bi-hammer"></i> Place Bid Now
                    </a>
                    <button class="tender-modal-btn tender-modal-btn-outline" onclick="document.getElementById('tenderModalOverlay').classList.remove('is-open')">
                        <i class="bi bi-bookmark"></i> Save
                    </button>
                `;
            } else {
                footer.innerHTML = `
                    <a href="${cardEl.querySelector('.tender-card-btn').href}" class="tender-modal-btn tender-modal-btn-primary">
                        <i class="bi bi-file-earmark-text"></i> View Full Details
                    </a>
                    <button class="tender-modal-btn tender-modal-btn-outline" onclick="document.getElementById('tenderModalOverlay').classList.remove('is-open')">
                        <i class="bi bi-bookmark"></i> Save
                    </button>
                `;
            }

            // Open with spring animation
            overlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';

            // Re-trigger info item animations
            infoGrid.querySelectorAll('.tender-modal-info-item').forEach(item => {
                item.style.animation = 'none';
                item.offsetHeight; // Force reflow
                item.style.animation = '';
            });
        }

        function closeTenderModal() {
            overlay.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        // Close handlers
        closeBtn.addEventListener('click', closeTenderModal);
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeTenderModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeTenderModal();
        });

        // Delegated click handler so both original and cloned cards open the popup modal seamlessly
        document.addEventListener('click', (e) => {
            const cardEl = e.target.closest('.tender-auction-card');
            if (!cardEl) return;
            // Don't open modal if user was dragging
            if (cardEl.closest('.is-dragging')) return;
            // Don't open if clicking the action button directly (let it navigate)
            if (e.target.closest('.tender-card-btn')) return;
            e.preventDefault();
            openTenderModal(cardEl);
        });
    }

    // Initialize all tracks & animations on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Horizontal Scrolling + Seamless Continuous Infinite Auto-Scrolling
        setupHorizontalScroller('servicesScrollTrack', 'servicesScrollLeft', 'servicesScrollRight', 300, 0.45);
        setupHorizontalScroller('tendersScrollTrack', 'tendersScrollLeft', 'tendersScrollRight', 380, 0.5);
        setupHorizontalScroller('brandsScrollTrack', 'brandsScrollLeft', 'brandsScrollRight', 260, 0.55);
        setupHorizontalScroller('appCarouselTrack', 'appScrollLeft', 'appScrollRight', 240, 0.4);
        setupHorizontalScroller('categoryScrollTrack', 'catScrollLeft', 'catScrollRight', 240, 0.45);
        setupHorizontalScroller('sponsoredScrollTrack', null, null, 200, 0.35);

        // Dynamic Live Countdown Timers for Auctions (ticks every second)
        const auctionTimerEls = document.querySelectorAll('.dynamic-auction-timer');
        if (auctionTimerEls.length > 0) {
            setInterval(() => {
                auctionTimerEls.forEach((el) => {
                    let sec = parseInt(el.getAttribute('data-countdown-seconds') || '0', 10);
                    if (sec > 0) {
                        sec--;
                        el.setAttribute('data-countdown-seconds', sec);
                        const days = Math.floor(sec / 86400);
                        const hours = Math.floor((sec % 86400) / 3600);
                        const mins = Math.floor((sec % 3600) / 60);
                        const secs = sec % 60;
                        const pad = (n) => String(n).padStart(2, '0');
                        if (days > 0) {
                            el.textContent = `${days}d ${pad(hours)}h ${pad(mins)}m`;
                        } else {
                            el.textContent = `${pad(hours)}h ${pad(mins)}m ${pad(secs)}s`;
                        }
                    }
                });
            }, 1000);
        }

        // Scroll Reveal Animation with IntersectionObserver
        if ('IntersectionObserver' in window) {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08 });

            document.querySelectorAll('.garudo-reveal').forEach(el => {
                revealObserver.observe(el);
            });
        } else {
            document.querySelectorAll('.garudo-reveal').forEach(el => el.classList.add('is-visible'));
        }

        // ═══ Front Page Admin-Curated Sections Engine ═══

        // 1. Horizontal Carousel Smooth Scroll
        window.scrollSectionCarousel = function(elementId, offset) {
            const el = document.getElementById(elementId);
            if (el) {
                el.scrollBy({ left: offset, behavior: 'smooth' });
            }
        };

        window.scrollActiveBestSellingTab = function(containerId, offset) {
            let activePane = null;
            if (typeof containerId === 'string') {
                activePane = document.querySelector('#' + containerId + ' .tab-pane.active .best-selling-row');
            } else if (typeof containerId === 'number') {
                offset = containerId;
            }
            if (!activePane) {
                activePane = document.querySelector('.tab-content .tab-pane.active .best-selling-row');
            }
            if (activePane) {
                activePane.scrollBy({ left: offset, behavior: 'smooth' });
            }
        };

        // 2. Trending Products Category Filter Tabs (Handles both Section 1 and Section 3)
        function initTrendingCategoryFilter() {
            document.querySelectorAll('.trending-category-tabs').forEach(tabContainer => {
                const targetRowId = tabContainer.getAttribute('data-target-row');
                const targetRow = targetRowId ? document.getElementById(targetRowId) : null;
                const tabBtns = tabContainer.querySelectorAll('.trending-cat-tab');
                if (!tabBtns.length) return;

                tabBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        tabBtns.forEach(b => {
                            b.classList.remove('active');
                            b.style.setProperty('border-bottom', '2.5px solid transparent', 'important');
                            b.style.color = '#64748b';
                            b.classList.add('text-muted');
                            b.classList.remove('fw-bold');
                        });
                        this.classList.add('active');
                        this.classList.remove('text-muted');
                        this.classList.add('fw-bold');
                        this.style.setProperty('border-bottom', '2.5px solid #f59e0b', 'important');
                        this.style.color = '#d97706';

                        const selectedCat = this.getAttribute('data-cat-id');
                        if (targetRow) {
                            const cards = targetRow.querySelectorAll('.trending-product-card-wrap');
                            cards.forEach(card => {
                                const cardCat = card.getAttribute('data-cat-id');
                                if (selectedCat === 'all' || cardCat === selectedCat) {
                                    card.style.display = 'block';
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                            targetRow.scrollTo({ left: 0, behavior: 'smooth' });
                        }
                    });
                });
            });
        }

        // 3. Best Selling Tabs Active Indicator Switcher
        function initBestSellingTabStyles() {
            const tabBtns = document.querySelectorAll('.best-selling-tab-btn, .best-tab-btn');
            tabBtns.forEach(btn => {
                btn.addEventListener('shown.bs.tab', function() {
                    tabBtns.forEach(b => {
                        b.style.setProperty('border-bottom', '2.5px solid transparent', 'important');
                        b.style.color = '#64748b';
                        b.classList.remove('fw-bold');
                        b.classList.add('text-muted');
                    });
                    this.classList.remove('text-muted');
                    this.classList.add('fw-bold');
                    this.style.setProperty('border-bottom', '2.5px solid #ea580c', 'important');
                    this.style.color = '#ea580c';
                });
            });
        }

        // 4. Interactive Quick View Modal
        function initQuickViewProductModal() {
            const modalEl = document.getElementById('quickViewProductModal');
            if (!modalEl) return;

            const modal = new bootstrap.Modal(modalEl);
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

                modal.show();
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
        }

        initTrendingCategoryFilter();
        initBestSellingTabStyles();
        initQuickViewProductModal();

        // Initialize Tender/Auction Popup Modal
        initTenderPopupModal();

        // Initialize Trending Now Carousel & Scroll Effects
        initTrendingNowCarousel();

        // ── Testimonials Scrollable Carousel ──
        initTestimonialsCarousel();
    });

    function initTestimonialsCarousel() {
        const track   = document.getElementById('testimonialsTrack');
        const prevBtn = document.getElementById('testiPrevBtn');
        const nextBtn = document.getElementById('testiNextBtn');
        if (!track) return;

        const STEP = 254; // px per card step
        let autoScrollInterval = null;
        let isUserInteracting = false;

        function scrollNextCard() {
            if (isUserInteracting) return;
            const maxScroll = track.scrollWidth - track.clientWidth;
            if (track.scrollLeft >= maxScroll - 15) {
                track.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                track.scrollBy({ left: STEP, behavior: 'smooth' });
            }
        }

        function startAutoScroll() {
            if (autoScrollInterval) clearInterval(autoScrollInterval);
            autoScrollInterval = setInterval(scrollNextCard, 3200);
        }

        function pauseAutoScrollTemporarily(durationMs = 4000) {
            isUserInteracting = true;
            if (autoScrollInterval) clearInterval(autoScrollInterval);
            setTimeout(() => {
                isUserInteracting = false;
                startAutoScroll();
            }, durationMs);
        }

        startAutoScroll();

        // Pause auto-scroll on hover
        track.addEventListener('mouseenter', () => { isUserInteracting = true; });
        track.addEventListener('mouseleave', () => { isUserInteracting = false; });

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                track.scrollBy({ left: -STEP, behavior: 'smooth' });
                pauseAutoScrollTemporarily(5000);
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                track.scrollBy({ left:  STEP, behavior: 'smooth' });
                pauseAutoScrollTemporarily(5000);
            });
        }

        // Mouse drag-to-scroll
        let isDown = false, startX, scrollLeft, hasDragged = false;
        track.addEventListener('mousedown', (e) => {
            isDown = true; hasDragged = false; isUserInteracting = true;
            track.classList.add('is-dragging');
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
        });
        track.addEventListener('mouseleave', () => {
            isDown = false;
            track.classList.remove('is-dragging');
            isUserInteracting = false;
        });
        track.addEventListener('mouseup', () => {
            isDown = false;
            track.classList.remove('is-dragging');
            pauseAutoScrollTemporarily(3000);
        });
        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.5;
            if (Math.abs(walk) > 5) hasDragged = true;
            track.scrollLeft = scrollLeft - walk;
        });
        track.addEventListener('click', (e) => {
            if (hasDragged) { e.preventDefault(); e.stopPropagation(); hasDragged = false; }
        }, true);
    }

    function scrollTrendingNowRow(offset) {
        const track = document.getElementById('trendingNowProductsTrack');
        if (track) {
            track.scrollBy({ left: offset, behavior: 'smooth' });
        }
    }

    function initTrendingNowCarousel() {
        const track = document.getElementById('trendingNowProductsTrack');
        const prevBtn = document.getElementById('trendingNowPrevBtn');
        const nextBtn = document.getElementById('trendingNowNextBtn');
        const progressBar = document.getElementById('trendingNowProgressBar');

        if (!track) return;

        function updateScrollState() {
            const maxScroll = track.scrollWidth - track.clientWidth;
            const currentScroll = track.scrollLeft;

            if (prevBtn) {
                if (currentScroll <= 12) {
                    prevBtn.classList.add('is-disabled');
                    prevBtn.disabled = true;
                } else {
                    prevBtn.classList.remove('is-disabled');
                    prevBtn.disabled = false;
                }
            }

            if (nextBtn) {
                if (currentScroll >= maxScroll - 12 || maxScroll <= 0) {
                    nextBtn.classList.add('is-disabled');
                    nextBtn.disabled = true;
                } else {
                    nextBtn.classList.remove('is-disabled');
                    nextBtn.disabled = false;
                }
            }

            if (progressBar && maxScroll > 0) {
                const ratio = Math.min(Math.max(currentScroll / maxScroll, 0), 1);
                const barWidth = 20 + (ratio * 80); // Scales gracefully from 20% to 100%
                progressBar.style.width = barWidth + '%';
            }
        }

        // Listen for scroll events with passive performance
        track.addEventListener('scroll', updateScrollState, { passive: true });

        // Mouse Drag to Scroll implementation
        let isDown = false;
        let startX;
        let scrollLeft;
        let hasDragged = false;

        track.addEventListener('mousedown', (e) => {
            isDown = true;
            hasDragged = false;
            track.classList.add('is-dragging');
            startX = e.pageX - track.offsetLeft;
            scrollLeft = track.scrollLeft;
        });

        track.addEventListener('mouseleave', () => {
            if (isDown) {
                isDown = false;
                track.classList.remove('is-dragging');
            }
        });

        track.addEventListener('mouseup', () => {
            isDown = false;
            track.classList.remove('is-dragging');
        });

        track.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - track.offsetLeft;
            const walk = (x - startX) * 1.5;
            if (Math.abs(walk) > 5) {
                hasDragged = true;
            }
            track.scrollLeft = scrollLeft - walk;
        });

        // Prevent click if mouse dragged
        track.addEventListener('click', (e) => {
            if (hasDragged) {
                e.preventDefault();
                e.stopPropagation();
                hasDragged = false;
            }
        }, true);

        // Initial update and resize observer
        setTimeout(updateScrollState, 200);
        window.addEventListener('resize', updateScrollState, { passive: true });
    }
</script>
@endpush
