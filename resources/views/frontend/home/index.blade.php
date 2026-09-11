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
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
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
            scroll-snap-align: start;
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
            scrollbar-width: none;
            scroll-behavior: smooth;
            padding: 2px 4px 4px 2px;
            width: 100%;
        }
        .services-scroll-track::-webkit-scrollbar {
            display: none;
        }
        .service-mini-card {
            min-width: 175px;
            max-width: 175px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        .service-mini-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            border-color: #ffb703;
        }
        .service-mini-img {
            width: 44px;
            height: 44px;
            border-radius: 9px;
            object-fit: cover;
            flex-shrink: 0;
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
            font-weight: 500;
        }
        .service-mini-card:hover .service-mini-link {
            color: #ffb703;
        }

        /* 2. Live Tenders & Auctions */
        .tenders-strip-container {
            display: flex;
            align-items: stretch;
            gap: 12px;
            position: relative;
        }
        .tenders-title-card {
            min-width: 215px;
            max-width: 215px;
            background: linear-gradient(135deg, #1b204e 0%, #101438 100%);
            border-radius: 16px;
            padding: 20px 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 14px rgba(16, 20, 56, 0.25);
            flex-shrink: 0;
        }
        .tenders-title-card h5 {
            font-size: 16.5px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 4px;
        }
        .tenders-title-card p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 0;
        }
        .tenders-scroll-track {
            display: flex;
            align-items: stretch;
            gap: 12px;
            overflow-x: auto;
            scrollbar-width: none;
            scroll-behavior: smooth;
            padding: 2px 4px 4px 2px;
            width: 100%;
        }
        .tenders-scroll-track::-webkit-scrollbar {
            display: none;
        }
        .tender-auction-card {
            min-width: 325px;
            max-width: 325px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
            flex-shrink: 0;
            position: relative;
        }
        .tender-auction-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
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
            width: 105px;
            height: 105px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            align-self: center;
        }
        .badge-tender {
            background: #1e3a8a;
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 12px;
            letter-spacing: 0.4px;
            display: inline-block;
            width: fit-content;
        }
        .badge-auction {
            background: #3b0764;
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 12px;
            letter-spacing: 0.4px;
            display: inline-block;
            width: fit-content;
        }
        .tender-card-title {
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
            margin: 4px 0 2px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .tender-card-location {
            font-size: 10.5px;
            color: #64748b;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .tender-card-meta {
            font-size: 10px;
            color: #64748b;
            line-height: 1.2;
        }
        .tender-card-val {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 2px;
            margin-bottom: 6px;
        }
        .tender-card-btn {
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 12px;
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
        }
        .tender-card-btn-purple {
            border: 1.5px solid #3b0764;
            color: #3b0764;
            background: transparent;
        }
        .tender-card-btn-purple:hover {
            background: #3b0764;
            color: #ffffff;
        }

        /* Generic Scroll Arrow Button */
        .track-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s ease;
        }
        .track-nav-btn:hover {
            background: #ffb703;
            color: #07152b;
            border-color: #ffb703;
            box-shadow: 0 4px 12px rgba(255, 183, 3, 0.35);
        }
        .track-nav-btn-dark {
            background: #0b1329;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        }
        .track-nav-btn-dark:hover {
            background: #1e293b;
            color: #ffb703;
            border-color: #ffb703;
            box-shadow: 0 4px 14px rgba(255, 183, 3, 0.4);
        }
        .track-nav-right {
            right: -8px;
        }
        .track-nav-left {
            left: 215px;
        }
        @media (max-width: 768px) {
            .track-nav-btn {
                display: none;
            }
            .services-title-card, .tenders-title-card {
                min-width: 145px;
                max-width: 145px;
                padding: 14px 10px;
            }
        }

        /* 3. Sponsored Partners Logo Row */
        .sponsored-partners-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            overflow-x: auto;
            scrollbar-width: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            margin-bottom: 22px;
        }
        .sponsored-partners-bar::-webkit-scrollbar {
            display: none;
        }
        .brand-logo-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 8px;
            white-space: nowrap;
            text-decoration: none;
            transition: transform 0.15s ease;
            flex-shrink: 0;
        }
        .brand-logo-chip:hover {
            transform: scale(1.05);
        }

        /* 4. Explore by Application Carousel */
        .app-carousel-track {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            scrollbar-width: none;
            scroll-behavior: smooth;
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

        /* 5. Trust & Assurance Badges */
        .trust-badges-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            overflow-x: auto;
            scrollbar-width: none;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            margin-bottom: 26px;
        }
        .trust-badges-bar::-webkit-scrollbar {
            display: none;
        }
        .trust-badge-item {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            flex: 1;
            justify-content: center;
            padding: 0 8px;
        }
        .trust-badge-icon {
            font-size: 22px;
            line-height: 1;
            flex-shrink: 0;
        }
        .trust-badge-title {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .trust-badge-subtitle {
            font-size: 10px;
            color: #64748b;
            line-height: 1.2;
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
                            @endphp
                            <div class="hero-item-row">
                                <div class="hero-item-img-box">
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
                                <button class="hero-item-action-btn" type="button" onclick="ajaxAddToCart({{ $deal->id }}, 1)" title="Add to Cart">
                                    Add <i class="bi bi-cart3"></i>
                                </button>
                            </div>
                        @endforeach

                        @if($displayedDeals->count() < 3)
                            @foreach(array_slice($defaultDealFallbacks, $displayedDeals->count()) as $fb)
                                <div class="hero-item-row">
                                    <div class="hero-item-img-box">
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
                            @endphp
                            <div class="hero-sponsor-card">
                                <div>
                                    <div class="hero-sponsor-card-img">
                                        <span class="hero-ad-tag-badge">AD</span>
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
                                    <div class="hero-item-price-line mb-2">
                                        ₹{{ number_format($sponsorItem->selling_price) }}
                                        <span class="hero-item-unit">/ {{ $sponsorItem->unit ?? 'unit' }}</span>
                                    </div>
                                    <a href="{{ route('products.show', $sponsorItem->slug ?? $sponsorItem->id) }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill py-1 d-flex align-items-center justify-content-center gap-1" style="font-size: 11px; font-weight: 700;">
                                        View <i class="bi bi-arrow-up-right"></i>
                                    </a>
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
                                        <div class="hero-item-price-line mb-2">
                                            ₹{{ number_format($cs['price']) }}
                                            <span class="hero-item-unit">/ {{ $cs['unit'] }}</span>
                                        </div>
                                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill py-1 d-flex align-items-center justify-content-center gap-1" style="font-size: 11px; font-weight: 700;">
                                            View <i class="bi bi-arrow-up-right"></i>
                                        </a>
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

        <!-- 3. BULK ORDER SPECIAL (Expanded & Modernized) -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12 hero-col">
            <div class="hero-card-base hero-card-bulk">
                <div>
                    <div class="hero-card-header-dark">
                        <span class="hero-dark-badge"><i class="bi bi-boxes text-warning me-1"></i> B2B WHOLESALE</span>
                        <span class="text-white-50 small" style="font-size: 10px;">Min. ₹50K+</span>
                    </div>

                    <h2 class="hero-dark-title">BULK ORDER SPECIAL</h2>
                    <div class="hero-dark-subhead">Save Up To 40% On Factory Loads</div>
                    <div class="hero-dark-desc">Direct wholesale rates for commercial contractors &amp; builders.</div>

                    <div class="hero-feature-group">
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon"><i class="bi bi-person-badge"></i></div>
                            <span>Dedicated Account Manager</span>
                        </div>
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon"><i class="bi bi-truck"></i></div>
                            <span>Priority Fleet Dispatch</span>
                        </div>
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon"><i class="bi bi-file-earmark-text"></i></div>
                            <span>Custom GST Quotations</span>
                        </div>
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon"><i class="bi bi-credit-card-2-front"></i></div>
                            <span>Flexible Net-30 Terms</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="{{ route('customer.quotes') }}" class="hero-cta-btn">
                        Request Bulk Quote <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <img src="{{ asset('images/hero/bulk_boxes.png') }}" class="hero-dark-bg-img" alt="Bulk Warehouse Freight">
            </div>
        </div>

        <!-- 4. AUCTION ZONE (Expanded & Modernized) -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-12 hero-col">
            <div class="hero-card-base hero-card-auction">
                <div>
                    <div class="hero-card-header-dark">
                        <span class="hero-dark-badge hero-badge-live">
                            <span class="hero-live-dot"></span> LIVE AUCTIONS
                        </span>
                        <span class="text-warning small fw-bold" style="font-size: 10px;">Up To 60% OFF</span>
                    </div>

                    <h2 class="hero-dark-title">AUCTION ZONE</h2>
                    <div class="hero-dark-subhead">Bid &amp; Save On Surplus Materials</div>
                    <div class="hero-dark-desc">Acquire surplus steel, tiles, electricals &amp; plant equipment at floor rates.</div>

                    <div class="hero-feature-group" style="margin-top: 10px;">
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon hero-feature-icon-amber"><i class="bi bi-broadcast"></i></div>
                            <span>Real-Time Live Bidding</span>
                        </div>
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon hero-feature-icon-amber"><i class="bi bi-patch-check"></i></div>
                            <span>100% Verified Quality Lots</span>
                        </div>
                        <div class="hero-feature-row">
                            <div class="hero-feature-icon hero-feature-icon-amber"><i class="bi bi-shield-lock"></i></div>
                            <span>Escrow Protected Checkout</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="{{ route('products.index') }}" class="hero-cta-btn hero-cta-amber">
                        Explore Auctions <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <img src="{{ asset('images/hero/auction_gavel.png') }}" class="hero-dark-bg-img" alt="Auction Gavel">
            </div>
        </div>
    </div>

    <!-- 1. FIND SERVICES & PROFESSIONALS (Exact Match to Image 1) -->
    <div class="landing-section-wrapper">
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
            </div>

            <!-- Scroll Right Button -->
            <button class="track-nav-btn track-nav-right" onclick="document.getElementById('servicesScrollTrack').scrollBy({left: 280, behavior: 'smooth'})" title="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- 2. LIVE TENDERS & AUCTIONS (Exact Match to Image 1) -->
    <div class="landing-section-wrapper">
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
            <button class="track-nav-btn track-nav-btn-dark track-nav-left" onclick="document.getElementById('tendersScrollTrack').scrollBy({left: -330, behavior: 'smooth'})" title="Scroll left">
                <i class="bi bi-chevron-left"></i>
            </button>

            <!-- Horizontal Scroll Tenders & Auctions Track -->
            <div class="tenders-scroll-track" id="tendersScrollTrack">
                <!-- Card 1: Commercial Building Tender -->
                <div class="tender-auction-card">
                    <div class="tender-card-left">
                        <div>
                            <span class="badge-tender">TENDER</span>
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
                            <span class="badge-auction">AUCTION</span>
                            <div class="tender-card-title">Surplus Steel & Rebars Mega Auction</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Pan India</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold">12h 45m 30s</span></div>
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
                            <span class="badge-tender">TENDER</span>
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
                            <span class="badge-auction">AUCTION</span>
                            <div class="tender-card-title">Excavator & Heavy Equipment Auction</div>
                            <div class="tender-card-location"><i class="bi bi-geo-alt me-0.5"></i> Pan India</div>
                            <div class="tender-card-meta">Ends In: <span class="text-danger fw-bold">2d 14h 20m</span></div>
                        </div>
                        <div>
                            <div class="tender-card-val">Starting Bid: <span class="text-dark fw-bold">₹8.75 Lakh</span></div>
                            <a href="{{ route('products.index', ['filter' => 'auctions']) }}" class="tender-card-btn tender-card-btn-purple">Bid Now</a>
                        </div>
                    </div>
                    <img src="{{ asset('images/landing/auction_excavator.jpg') }}" class="tender-card-img" alt="Excavator Equipment">
                </div>
            </div>

            <!-- Right Navigation Button -->
            <button class="track-nav-btn track-nav-btn-dark track-nav-right" onclick="document.getElementById('tendersScrollTrack').scrollBy({left: 330, behavior: 'smooth'})" title="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- 3. SPONSORED PARTNERS BRAND LOGO STRIP (Exact Match to Image 1) -->
    <div class="sponsored-partners-bar">
        <div class="d-flex flex-column text-muted fw-bold" style="font-size: 11px; line-height: 1.1; white-space: nowrap; flex-shrink: 0;">
            <span>Sponsored</span>
            <span>Partners</span>
        </div>

        <div class="d-flex align-items-center gap-4 flex-grow-1 justify-content-around px-2">
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
        </div>

        <a href="{{ route('vendor.register') }}" class="text-muted fw-semibold text-decoration-none small text-nowrap ms-2" style="font-size: 11px;">
            Sponsor Your Brand →
        </a>
    </div>

    <!-- 4. EXPLORE BY APPLICATION (Exact Match to Image 1 with Generated Photos) -->
    <div class="landing-section-wrapper">
        <h5 class="fw-bold text-dark mb-3" style="font-size: 16px; letter-spacing: 0.3px; text-transform: uppercase;">EXPLORE BY APPLICATION</h5>
        <div class="position-relative">
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
            <button class="track-nav-btn track-nav-right" onclick="document.getElementById('appCarouselTrack').scrollBy({left: 240, behavior: 'smooth'})" title="Scroll right">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- 5. TRUST & ASSURANCE BADGES (Exact Match to Image 1) -->
    <div class="trust-badges-bar">
        <!-- 1. Verified Sellers -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon text-dark"><i class="bi bi-person-check-fill"></i></div>
            <div>
                <div class="trust-badge-title">Verified Sellers</div>
                <div class="trust-badge-subtitle">Trusted & verified vendors</div>
            </div>
        </div>
        <div class="vr opacity-25 d-none d-md-block" style="height: 28px;"></div>

        <!-- 2. 100% Genuine Products -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon text-dark"><i class="bi bi-patch-check-fill text-warning"></i></div>
            <div>
                <div class="trust-badge-title">100% Genuine Products</div>
                <div class="trust-badge-subtitle">Quality you can trust</div>
            </div>
        </div>
        <div class="vr opacity-25 d-none d-md-block" style="height: 28px;"></div>

        <!-- 3. Best Price Guarantee -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon text-dark"><i class="bi bi-tags-fill text-danger"></i></div>
            <div>
                <div class="trust-badge-title">Best Price Guarantee</div>
                <div class="trust-badge-subtitle">We beat the market price</div>
            </div>
        </div>
        <div class="vr opacity-25 d-none d-md-block" style="height: 28px;"></div>

        <!-- 4. Secure Payments -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon text-dark"><i class="bi bi-shield-lock-fill text-primary"></i></div>
            <div>
                <div class="trust-badge-title">Secure Payments</div>
                <div class="trust-badge-subtitle">Safe & multiple payment options</div>
            </div>
        </div>
        <div class="vr opacity-25 d-none d-md-block" style="height: 28px;"></div>

        <!-- 5. Pan India Delivery -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon text-dark"><i class="bi bi-truck text-success"></i></div>
            <div>
                <div class="trust-badge-title">Pan India Delivery</div>
                <div class="trust-badge-subtitle">Fast delivery at your place</div>
            </div>
        </div>
        <div class="vr opacity-25 d-none d-md-block" style="height: 28px;"></div>

        <!-- 6. Easy Returns -->
        <div class="trust-badge-item">
            <div class="trust-badge-icon text-dark"><i class="bi bi-arrow-repeat text-info"></i></div>
            <div>
                <div class="trust-badge-title">Easy Returns</div>
                <div class="trust-badge-subtitle">Hassle free returns</div>
            </div>
        </div>
    </div>

    <!-- 6. CATEGORY GRID BUTTONS (12 Icons) -->
    <div class="row g-2 mb-4">
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-boxes fs-3 text-warning"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Cement & Concrete</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-stack fs-3 text-danger"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Steel & TMT</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-square-fill fs-3 text-primary"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Bricks & Blocks</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-moisture fs-3 text-info"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Sand & Aggregate</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-paint-bucket fs-3 text-success"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Paints & Coatings</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-droplet-half fs-3 text-primary"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Pipes & Sanitary</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-house-door fs-3 text-secondary"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Roofing & Cladding</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-lightbulb fs-3 text-warning"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Electricals</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-window fs-3 text-dark"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Windows & Doors</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-grid fs-3 text-danger"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Tiles & Flooring</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark h-100">
                <i class="bi bi-tools fs-3 text-success"></i>
                <span class="small fw-semibold mt-1" style="font-size: 10px;">Hardware</span>
            </a>
        </div>
        <div class="col-lg-1 col-md-2 col-4 text-center">
            <a href="#" class="card card-custom p-2 text-decoration-none text-dark bg-light h-100">
                <i class="bi bi-grid-3x3-gap fs-3 text-navy"></i>
                <span class="small fw-bold mt-1" style="font-size: 10px;">All Categories</span>
            </a>
        </div>
    </div>

    <!-- 7. SPECIAL OFFERS FOR YOU (4 Gradient Banners) -->
    <div class="mb-4">
        <h5 class="section-header-title">SPECIAL OFFERS FOR YOU</h5>
        <div class="row g-3">
            <!-- Banner 1 -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #c2410c 0%, #ea580c 100%);">
                    <span class="badge bg-dark w-auto d-inline-block mb-1" style="width: max-content;">CONTRACTOR SPECIAL</span>
                    <h5 class="fw-bold mb-1">Up to 30% OFF</h5>
                    <p class="small text-light mb-2">On Bulk Cement & Steel Rebars</p>
                    <button class="btn btn-sm btn-light fw-bold text-dark w-auto align-self-start" style="width: max-content;">Book Now</button>
                </div>
            </div>
            <!-- Banner 2 -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #431407 0%, #7c2d12 100%);">
                    <span class="badge bg-warning text-dark w-auto d-inline-block mb-1" style="width: max-content;">EXCLUSIVE OFFER</span>
                    <h5 class="fw-bold mb-1">Big Savings on</h5>
                    <p class="small text-light mb-2">Building Materials & Bricks</p>
                    <button class="btn btn-sm btn-yellow fw-bold w-auto align-self-start" style="width: max-content;">Explore Offers</button>
                </div>
            </div>
            <!-- Banner 3 -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%);">
                    <span class="badge bg-light text-danger w-auto d-inline-block mb-1" style="width: max-content;">CLEARANCE SALE</span>
                    <h5 class="fw-bold mb-1">Up to 50% OFF</h5>
                    <p class="small text-light mb-2">Limited Surplus Stock Sale</p>
                    <button class="btn btn-sm btn-light fw-bold text-danger w-auto align-self-start" style="width: max-content;">Claim Deal</button>
                </div>
            </div>
            <!-- Banner 4 -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
                    <span class="badge bg-warning text-dark w-auto d-inline-block mb-1" style="width: max-content;">COMBO OFFERS</span>
                    <h5 class="fw-bold mb-1">More Products</h5>
                    <p class="small text-light mb-2">More Savings for Infrastructure</p>
                    <button class="btn btn-sm btn-yellow fw-bold w-auto align-self-start" style="width: max-content;">View Combos</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 8. POPULAR CATEGORIES WITH TOP PRODUCTS (Columns) -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="section-header-title mb-0">POPULAR CATEGORIES WITH TOP PRODUCTS</h5>
            <a href="#" class="small fw-bold text-navy text-decoration-none">See All Categories <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="row g-3">
            <!-- Column 1: Cement & Concrete -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom p-2 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">Cement & Concrete</div>
                    <div class="small mb-2">
                        <div class="fw-semibold">UltraTech PPC Cement</div>
                        <div class="text-muted">₹385 / Bag</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">ACC Concrete Plus</div>
                        <div class="text-muted">₹395 / Bag</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Ambuja Kawach</div>
                        <div class="text-muted">₹410 / Bag</div>
                    </div>
                    <a href="#" class="small text-primary fw-bold mt-auto pt-2">View 150+ Products <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <!-- Column 2: Steel & TMT Bars -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom p-2 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">Steel & TMT Bars</div>
                    <div class="small mb-2">
                        <div class="fw-semibold">TATA Tiscon 12mm</div>
                        <div class="text-muted">₹62,500 / Ton</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Jindal Panther 10mm</div>
                        <div class="text-muted">₹60,000 / Ton</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">JSW Neosteel 16mm</div>
                        <div class="text-muted">₹61,200 / Ton</div>
                    </div>
                    <a href="#" class="small text-primary fw-bold mt-auto pt-2">View 80+ Products <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <!-- Column 3: Tiles & Stone -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom p-2 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">Tiles & Stone</div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Kajaria Vitrified 2x2</div>
                        <div class="text-muted">₹45 / Sq.Ft</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Somany Wall Tile</div>
                        <div class="text-muted">₹38 / Sq.Ft</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Granite Black Slab</div>
                        <div class="text-muted">₹120 / Sq.Ft</div>
                    </div>
                    <a href="#" class="small text-primary fw-bold mt-auto pt-2">View 300+ Products <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <!-- Column 4: Pipes & Fittings -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom p-2 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">Pipes & Fittings</div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Astral CPVC Pipe 1"</div>
                        <div class="text-muted">₹180 / Piece</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Supreme PVC 4"</div>
                        <div class="text-muted">₹320 / Piece</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Finolex SWR Pipe</div>
                        <div class="text-muted">₹290 / Piece</div>
                    </div>
                    <a href="#" class="small text-primary fw-bold mt-auto pt-2">View 220+ Products <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <!-- Column 5: Electricals & Lighting -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom p-2 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">Electricals & Lighting</div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Polycab 2.5sqmm Wire</div>
                        <div class="text-muted">₹1,850 / Coil</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Havells 16A MCB</div>
                        <div class="text-muted">₹145 / Unit</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Philips 18W LED</div>
                        <div class="text-muted">₹240 / Unit</div>
                    </div>
                    <a href="#" class="small text-primary fw-bold mt-auto pt-2">View 400+ Products <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <!-- Column 6: Tools & Equipment -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom p-2 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">Tools & Equipment</div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Bosch Angle Grinder</div>
                        <div class="text-muted">₹2,850 / Unit</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">DeWalt Drill Machine</div>
                        <div class="text-muted">₹3,450 / Unit</div>
                    </div>
                    <div class="small mb-2">
                        <div class="fw-semibold">Concrete Mixer 10/7</div>
                        <div class="text-muted">₹1,15,000 / Unit</div>
                    </div>
                    <a href="#" class="small text-primary fw-bold mt-auto pt-2">View 180+ Products <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- 9. BEST SELLING PRODUCTS GRID & MEGA SALE BANNERS -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="section-header-title mb-0">BEST SELLING PRODUCTS</h5>
            <span class="small text-muted">Filter: <a href="#" class="text-dark fw-bold">All Products</a> | <a href="#">Civil</a> | <a href="#">Plumbing</a> | <a href="#">Electricals</a></span>
        </div>
        <div class="row g-3 mb-3">
            <!-- Product 1 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom h-100 p-2 text-center">
                    <span class="badge badge-discount position-absolute top-0 start-0 m-2">HOT</span>
                    <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=200&auto=format&fit=crop&q=60" class="img-fluid rounded mb-2" style="height: 120px; object-fit: contain;" alt="Jaquar Faucet">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 12px;">Jaquar Chrome Brass Faucet</div>
                    <div class="product-price-current my-1">₹1,850 <span class="product-price-old">₹2,400</span></div>
                    <div class="text-muted" style="font-size: 10px;"><i class="bi bi-star-fill text-warning"></i> 4.8 (120)</div>
                    <button class="btn btn-sm btn-outline-dark w-100 mt-2 py-1"><i class="bi bi-cart-plus me-1"></i> Add to Cart</button>
                </div>
            </div>
            <!-- Product 2 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom h-100 p-2 text-center">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2" style="font-size: 9px;">10% OFF</span>
                    <img src="https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=200&auto=format&fit=crop&q=60" class="img-fluid rounded mb-2" style="height: 120px; object-fit: contain;" alt="Asian Paints Bucket">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 12px;">Asian Paints Apex 20L</div>
                    <div class="product-price-current my-1">₹4,150 <span class="product-price-old">₹4,800</span></div>
                    <div class="text-muted" style="font-size: 10px;"><i class="bi bi-star-fill text-warning"></i> 4.9 (85)</div>
                    <button class="btn btn-sm btn-outline-dark w-100 mt-2 py-1"><i class="bi bi-cart-plus me-1"></i> Add to Cart</button>
                </div>
            </div>
            <!-- Product 3 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom h-100 p-2 text-center">
                    <img src="https://images.unsplash.com/photo-1585314062340-f1a5a7c9328d?w=200&auto=format&fit=crop&q=60" class="img-fluid rounded mb-2" style="height: 120px; object-fit: contain;" alt="Ceramic Toilet Seat">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 12px;">Hindware One Piece Commode</div>
                    <div class="product-price-current my-1">₹7,250 <span class="product-price-old">₹9,000</span></div>
                    <div class="text-muted" style="font-size: 10px;"><i class="bi bi-star-fill text-warning"></i> 4.7 (42)</div>
                    <button class="btn btn-sm btn-outline-dark w-100 mt-2 py-1"><i class="bi bi-cart-plus me-1"></i> Add to Cart</button>
                </div>
            </div>
            <!-- Product 4 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom h-100 p-2 text-center">
                    <img src="https://images.unsplash.com/photo-1615873968403-89e068629265?w=200&auto=format&fit=crop&q=60" class="img-fluid rounded mb-2" style="height: 120px; object-fit: contain;" alt="Floor Tile">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 12px;">AGL Vitrified Floor Tile</div>
                    <div class="product-price-current my-1">₹40 <span class="product-price-old">₹52</span> <span class="text-muted small">/sq.ft</span></div>
                    <div class="text-muted" style="font-size: 10px;"><i class="bi bi-star-fill text-warning"></i> 4.6 (67)</div>
                    <button class="btn btn-sm btn-outline-dark w-100 mt-2 py-1"><i class="bi bi-cart-plus me-1"></i> Add to Cart</button>
                </div>
            </div>
            <!-- Product 5 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom h-100 p-2 text-center">
                    <img src="https://images.unsplash.com/photo-1541888946425-d0fbb186a5b3?w=200&auto=format&fit=crop&q=60" class="img-fluid rounded mb-2" style="height: 120px; object-fit: contain;" alt="Steel Rebars">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 12px;">TMT Steel Rods 16mm</div>
                    <div class="product-price-current my-1">₹58,500 <span class="product-price-old">₹64,000</span></div>
                    <div class="text-muted" style="font-size: 10px;"><i class="bi bi-star-fill text-warning"></i> 4.9 (140)</div>
                    <button class="btn btn-sm btn-outline-dark w-100 mt-2 py-1"><i class="bi bi-cart-plus me-1"></i> Add to Cart</button>
                </div>
            </div>
            <!-- Product 6 -->
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card card-custom h-100 p-2 text-center">
                    <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?w=200&auto=format&fit=crop&q=60" class="img-fluid rounded mb-2" style="height: 120px; object-fit: contain;" alt="Teak Wood Door">
                    <div class="fw-bold text-dark text-truncate" style="font-size: 12px;">WPC Flush Main Door</div>
                    <div class="product-price-current my-1">₹3,800 <span class="product-price-old">₹4,500</span></div>
                    <div class="text-muted" style="font-size: 10px;"><i class="bi bi-star-fill text-warning"></i> 4.8 (31)</div>
                    <button class="btn btn-sm btn-outline-dark w-100 mt-2 py-1"><i class="bi bi-cart-plus me-1"></i> Add to Cart</button>
                </div>
            </div>
        </div>

        <!-- 3 Mega Sale Banners -->
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card card-custom p-3 bg-dark text-white border-0" style="background: linear-gradient(135deg, #111827 0%, #374151 100%);">
                    <h4 class="fw-black text-warning mb-1">MEGA CONSTRUCTION SALE</h4>
                    <p class="small text-light mb-2">Save up to 40% on Heavy TMT Steel & Cement Orders</p>
                    <a href="#" class="btn btn-sm btn-yellow fw-bold align-self-start">Explore Offers</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3 bg-navy text-white border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <h4 class="fw-bold text-white mb-1">KAJARIA TILES</h4>
                    <p class="small text-light mb-2">Export Quality Glazed Vitrified Tiles Direct Factory Rate</p>
                    <a href="#" class="btn btn-sm btn-light fw-bold text-navy align-self-start">Browse Catalogue</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <h4 class="fw-bold text-white mb-1">ASTRAL PIPES</h4>
                    <p class="small text-light mb-2">Strongest CPVC & SWR Plumbing Systems for Projects</p>
                    <a href="#" class="btn btn-sm btn-yellow fw-bold align-self-start">View Piping Catalogue</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 10. MULTI-CATEGORY GRID LAYOUT (16 Specific Blocks) -->
    <div class="mb-4">
        <h5 class="section-header-title">BROWSE SUPPLIES BY CATEGORY</h5>
        <div class="row g-3">
            <!-- Grid Item 1: Construction Materials -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">CONSTRUCTION MATERIALS</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Cement (PPC/OPC)</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>TMT Steel Bars</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>AAC & Flyash Blocks</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Red Clay Bricks</span><a href="#">View All</a></div>
                </div>
            </div>
            <!-- Grid Item 2: Plumbing & Drainage -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">PLUMBING & DRAINAGE</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>CPVC & UPVC Pipes</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Brass Valves & Fittings</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>SWR Sewerage Pipes</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Water Storage Tanks</span><a href="#">View All</a></div>
                </div>
            </div>
            <!-- Grid Item 3: Electrical Materials -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">ELECTRICAL MATERIALS</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Industrial Copper Cables</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Modular Switches & Boxes</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>MCBs & Distribution Boards</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>LED Commercial Panel Lights</span><a href="#">View All</a></div>
                </div>
            </div>
            <!-- Grid Item 4: Paints & Exterior -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">PAINTS & EXTERIOR</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Interior Acrylic Emulsion</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Exterior Weathercoat Paints</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Wall Putty & Waterproofing</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Enamel & Metal Primers</span><a href="#">View All</a></div>
                </div>
            </div>
            <!-- Grid Item 5: Flooring & Tiles -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">FLOORING & TILES</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>GVT & PGVT Tiles</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Granite Slabs & Steps</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Wooden Laminate Flooring</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Tile Adhesive & Grout</span><a href="#">View All</a></div>
                </div>
            </div>
            <!-- Grid Item 6: Tools & Hardware -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">TOOLS & HARDWARE</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Power Drills & Grinders</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Hand Toolkits & Spanners</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Fasteners, Screws & Bolts</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Architectural Fittings</span><a href="#">View All</a></div>
                </div>
            </div>
            <!-- Grid Item 7: Safety & Protection -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">SAFETY & PROTECTION</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Safety Helmets & Shoes</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>High-Vis Reflective Jackets</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Fall Protection Harnesses</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Fire Extinguishers</span><a href="#">View All</a></div>
                </div>
            </div>
            <!-- Grid Item 8: Solar & Energy -->
            <div class="col-lg-3 col-md-6">
                <div class="card card-custom p-3 h-100">
                    <div class="fw-bold text-navy border-bottom pb-1 mb-2">SOLAR & ENERGY</div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Mono PERC Solar Panels</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>On-Grid Inverters</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Solar Mounting Structures</span><a href="#">View All</a></div>
                    <div class="d-flex justify-content-between small text-muted mb-1"><span>Solar Cables & Connectors</span><a href="#">View All</a></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 11. FOUR PROMOTIONAL BENEFIT BANNERS -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #0a1324 0%, #162650 100%);">
                <div class="fw-bold text-yellow mb-1"><i class="bi bi-percent me-1"></i> BULK SPEED SAVINGS</div>
                <div class="small text-light">Order over 50 Tons and unlock instant 8% cash refund at checkout.</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #0a1324 0%, #162650 100%);">
                <div class="fw-bold text-yellow mb-1"><i class="bi bi-shield-check me-1"></i> DIRECT FACTORY RATE</div>
                <div class="small text-light">Zero middleman markup. Verified price guarantee from brands directly.</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #0a1324 0%, #162650 100%);">
                <div class="fw-bold text-yellow mb-1"><i class="bi bi-truck me-1"></i> LOWEST FREIGHT RATE</div>
                <div class="small text-light">Integrated Garudo Logistics for site delivery across 2500+ Indian cities.</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom p-3 text-white border-0" style="background: linear-gradient(135deg, #0a1324 0%, #162650 100%);">
                <div class="fw-bold text-yellow mb-1"><i class="bi bi-file-text me-1"></i> RAISE CUSTOM RFQ</div>
                <div class="small text-light">Upload BOQ or material list and get binding quotes within 2 hours.</div>
            </div>
        </div>
    </div>

    <!-- 12. NEW ARRIVALS & WHY CHOOSE GARUDO -->
    <div class="row g-3 mb-4">
        <!-- New Arrivals (Left) -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="section-header-title mb-0">NEW ARRIVALS</h5>
                <a href="#" class="small text-muted">View All <i class="bi bi-chevron-right"></i></a>
            </div>
            <div class="row g-2">
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-2 text-center h-100">
                        <img src="https://images.unsplash.com/photo-1558002038-1055907df827?w=150&auto=format&fit=crop&q=60" class="img-fluid rounded mb-1" style="height: 90px; object-fit: contain;" alt="Digital Lock">
                        <div class="fw-bold text-truncate small">Yale Smart Door Lock</div>
                        <div class="fw-bold text-navy mt-1">₹14,500</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-2 text-center h-100">
                        <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?w=150&auto=format&fit=crop&q=60" class="img-fluid rounded mb-1" style="height: 90px; object-fit: contain;" alt="PVC Panel">
                        <div class="fw-bold text-truncate small">WPC Wall Panel 8x2ft</div>
                        <div class="fw-bold text-navy mt-1">₹1,250</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-2 text-center h-100">
                        <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=150&auto=format&fit=crop&q=60" class="img-fluid rounded mb-1" style="height: 90px; object-fit: contain;" alt="Bathtub">
                        <div class="fw-bold text-truncate small">Acrylic Freestanding Tub</div>
                        <div class="fw-bold text-navy mt-1">₹28,500</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card card-custom p-2 text-center h-100">
                        <img src="https://images.unsplash.com/photo-1509391365360-2e959784a276?w=150&auto=format&fit=crop&q=60" class="img-fluid rounded mb-1" style="height: 90px; object-fit: contain;" alt="Solar Inverter">
                        <div class="fw-bold text-truncate small">Luminous 5kW Solar Inverter</div>
                        <div class="fw-bold text-navy mt-1">₹42,000</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Why Choose Garudo? (Right Box with Stats) -->
        <div class="col-lg-4">
            <div class="card card-custom p-3 bg-navy text-white h-100 justify-content-between">
                <div>
                    <h5 class="fw-bold text-yellow mb-2"><i class="bi bi-patch-check-fill me-1"></i> Why Choose Garudo?</h5>
                    <ul class="list-unstyled small mb-3">
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-yellow me-2"></i> Direct Factory Wholesale Pricing</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-yellow me-2"></i> 100% Verified Manufacturers & Brands</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-yellow me-2"></i> Instant Custom Quotation (RFQ) Response</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-yellow me-2"></i> Secure Escrow Payment & GST Billing</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-yellow me-2"></i> Pan-India Express Site Delivery</li>
                    </ul>
                </div>
                <div class="row g-2 text-center pt-2 border-top border-secondary">
                    <div class="col-6">
                        <div class="fw-black fs-5 text-yellow">250K+</div>
                        <div class="small text-light" style="font-size: 10px;">Products</div>
                    </div>
                    <div class="col-6">
                        <div class="fw-black fs-5 text-yellow">10K+</div>
                        <div class="small text-light" style="font-size: 10px;">Suppliers</div>
                    </div>
                    <div class="col-6">
                        <div class="fw-black fs-5 text-yellow">500K+</div>
                        <div class="small text-light" style="font-size: 10px;">Buyers</div>
                    </div>
                    <div class="col-6">
                        <div class="fw-black fs-5 text-yellow">2500+</div>
                        <div class="small text-light" style="font-size: 10px;">Cities Covered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 13. BOTTOM SERVICE HIGHLIGHTS (5 Blue Banners) -->
    <div class="row g-2 mb-4 text-center">
        <div class="col">
            <div class="p-3 bg-navy text-white rounded">
                <i class="bi bi-percent fs-4 text-yellow"></i>
                <div class="fw-bold small mt-1">Bulk Order Discounts</div>
                <div class="text-secondary" style="font-size: 9px;">Extra margin for mega orders</div>
            </div>
        </div>
        <div class="col">
            <div class="p-3 bg-navy text-white rounded">
                <i class="bi bi-tags-fill fs-4 text-yellow"></i>
                <div class="fw-bold small mt-1">Direct Factory Prices</div>
                <div class="text-secondary" style="font-size: 9px;">Zero middleman commission</div>
            </div>
        </div>
        <div class="col">
            <div class="p-3 bg-navy text-white rounded">
                <i class="bi bi-arrow-counterclockwise fs-4 text-yellow"></i>
                <div class="fw-bold small mt-1">Easy Returns</div>
                <div class="text-secondary" style="font-size: 9px;">Hassle free replacement</div>
            </div>
        </div>
        <div class="col">
            <div class="p-3 bg-navy text-white rounded">
                <i class="bi bi-truck fs-4 text-yellow"></i>
                <div class="fw-bold small mt-1">Fast Pan-India Delivery</div>
                <div class="text-secondary" style="font-size: 9px;">Express door & site delivery</div>
            </div>
        </div>
        <div class="col">
            <div class="p-3 bg-navy text-white rounded">
                <i class="bi bi-headset fs-4 text-yellow"></i>
                <div class="fw-bold small mt-1">24/7 Support</div>
                <div class="text-secondary" style="font-size: 9px;">Dedicated key account assistance</div>
            </div>
        </div>
    </div>

    <!-- 14. CUSTOMER TESTIMONIALS & SELL ON GARUDO BANNER -->
    <div class="row g-3 mb-4">
        <!-- Customer Testimonials -->
        <div class="col-lg-8">
            <h5 class="section-header-title">WHAT OUR CUSTOMERS SAY</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card card-custom p-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=60" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Contractor">
                            <div>
                                <div class="fw-bold small">Rajesh Sharma</div>
                                <div class="text-muted" style="font-size: 10px;">Civil Contractor, Delhi</div>
                            </div>
                        </div>
                        <p class="small text-muted mb-2">"Garudo helped us source 200 tons of TMT steel at factory rates directly from Jindal. Saved around ₹3 Lakhs on our commercial project."</p>
                        <div class="text-warning mt-auto"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-custom p-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&auto=format&fit=crop&q=60" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Architect">
                            <div>
                                <div class="fw-bold small">Priya Mehta</div>
                                <div class="text-muted" style="font-size: 10px;">Lead Architect, Mumbai</div>
                            </div>
                        </div>
                        <p class="small text-muted mb-2">"The tiles and sanitaryware range is incredible. Delivery was prompt at our project site with zero broken items."</p>
                        <div class="text-warning mt-auto"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-custom p-3 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=60" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" alt="Builder">
                            <div>
                                <div class="fw-bold small">Vikram Patel</div>
                                <div class="text-muted" style="font-size: 10px;">Builder, Ahmedabad</div>
                            </div>
                        </div>
                        <p class="small text-muted mb-2">"RFQ response within 1 hour! Best B2B platform for building materials in India hands down."</p>
                        <div class="text-warning mt-auto"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sell on Garudo Banner Box -->
        <div class="col-lg-4">
            <div class="card card-custom p-4 bg-navy text-white h-100 d-flex flex-column justify-content-between border-0" style="background: linear-gradient(135deg, #0d1b3e 0%, #162650 100%);">
                <div>
                    <span class="badge bg-warning text-dark fw-bold mb-2">FOR MANUFACTURERS & SUPPLIERS</span>
                    <h4 class="fw-bold text-white mb-2">Sell All India on Garudo B2B</h4>
                    <p class="small text-light mb-3">Connect directly with 500,000+ verified contractors, builders, and commercial buyers.</p>
                </div>
                <div>
                    <a href="{{ route('vendor.register') }}" class="btn btn-yellow w-100 py-2 fw-bold"><i class="bi bi-shop me-1"></i> Register as Supplier / Partner</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
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
</script>
@endpush

