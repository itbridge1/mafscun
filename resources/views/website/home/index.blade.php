@extends('website.common.layout')
@section('title', 'Bagmati PROSCUN Home || Home')
@section('meta')
    @include('website.common.meta', [
        '_title' => $_site_setting->title,
        '_keywords' => $_site_setting->keywords,
        '_detail' => strip_tags($_site_setting->description),
        '_folder' => 'siteSetting',
        '_photo' => $_site_setting->site_logo,
    ])
@endsection
@section('css')
@parent
<style>
    /* Global & Section Variables override */
    .caption {
        background: rgba(255, 255, 255, 0.34);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(10px);
        padding: 10px;
        border-radius: 5px;
        color: #192b1a;
        font-size: 15px;
    }

    @media (max-width: 576px) {
        .carousel-item img {
            max-height: none !important;
            width: 100% !important;
        }
    }

    /* Notice Board & About Us overrides */
    .notice-icon {
        margin-right: -50px;
    }

    .notice {
        padding: 0 !important;
        border-radius: 5px;
        border: 1px solid rgb(255, 255, 255);
        background-color: rgb(255, 255, 255);
        height: max-content;
    }

    .notice .notice-item {
        margin: 0px auto 15px !important;
    }

    .notice .notice-item .notice-content {
        padding: 5px !important;
    }

    .notice .button {
        background-color: rgb(12, 80, 80);
        width: 150px;
        text-align: center;
        line-height: 30px;
        font-weight: 600;
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        transition: all .5s;
    }

    .button-notice:hover {
        background: #083434 !important;
        color: black !important;
    }

    .about-us-title {
        color: var(--brand-green);
    }

    .about-us {
        padding: 20px;
        background-color: #f7f4f4 !important;
        box-shadow: 1px 5px 5px 4px #cac4c4 !important;
    }

    .about-us h1 {
        font-size: 4.5vmin;
        margin-left: 0px !important;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .testimonial-item {
        padding: 10px !important;
        border-radius: 5px !important;
        box-shadow: 5px 5px 5px 5px #d7d5d5 !important;
        background: rgb(247, 247, 252) !important;
    }

    @media (max-width: 876px) {
        .about-us h1,
        .about-us hr {
            margin-left: 0px !important;
            font-size: 24px !important;
            text-align: center !important;
        }
    }

    @media (max-width: 575px) {
        .marquee-row .col-2,
        .marquee-row .col-10 {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            text-align: center !important;
        }
        .marquee-row {
            flex-direction: column !important;
        }
    }

    /* ==========================================
       PREMIUM DESIGN SYSTEM - EMI & CONTACT SECTION
       ========================================== */
    .emi-contact-section {
        background-color: #fafdfb;
        border-radius: 20px;
        padding: 40px 15px !important;
    }
    
    .emi-card, .contact-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        height: 100%;
        transition: var(--transition-smooth, all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1));
    }
    
    .emi-card:hover, .contact-card:hover {
        box-shadow: 0 15px 35px rgba(1, 167, 85, 0.1) !important;
        border-color: var(--brand-green-light, #35b673) !important;
    }
    
    .section-card-title {
        color: var(--brand-green, #01a755) !important;
        font-weight: 800 !important;
        font-size: 1.5rem !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        padding-bottom: 12px;
    }
    
    .section-card-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background-color: var(--brand-cyan, #00acf1);
        border-radius: 2px;
    }

    /* Input Forms Styling */
    .emi-card label, .contact-card label, .contact-subtitle {
        font-weight: 600 !important;
        font-size: 0.88rem !important;
        color: #475569 !important;
        margin-bottom: 6px;
        display: inline-block;
    }
    
    .emi-card .form-control, .contact-card .form-control {
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 10px 12px !important;
        font-size: 0.95rem !important;
        color: #1e293b !important;
        background-color: #f8fafc !important;
        transition: all 0.2s ease-in-out !important;
    }
    
    .emi-card .form-control:focus, .contact-card .form-control:focus {
        background-color: #ffffff !important;
        border-color: var(--brand-green, #01a755) !important;
        box-shadow: 0 0 0 3px rgba(1, 167, 85, 0.15) !important;
        outline: none !important;
    }
    
    /* Buttons */
    .btn-calculate {
        background-color: var(--brand-green, #01a755) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        padding: 12px !important;
        border-radius: 8px !important;
        border: none !important;
        letter-spacing: 0.5px;
        transition: all 0.2s ease !important;
    }
    
    .btn-calculate:hover {
        background-color: var(--brand-green-hover, #008f48) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(1, 167, 85, 0.2) !important;
    }
    
    .btn-reset {
        background-color: transparent !important;
        color: #64748b !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        padding: 10px !important;
        border-radius: 8px !important;
        transition: all 0.2s ease !important;
    }
    
    .btn-reset:hover {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
    }
    
    .btn-submit-contact {
        background-color: var(--brand-cyan, #00acf1) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        padding: 10px 24px !important;
        border-radius: 8px !important;
        border: none !important;
        transition: all 0.2s ease !important;
    }
    
    .btn-submit-contact:hover {
        background-color: var(--brand-cyan-hover, #0093ce) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 172, 241, 0.2) !important;
    }
    
    /* Result Box styling */
    .emi-result-box {
        background-color: var(--brand-bg-light, #f4fcf7);
        border: 1px dashed var(--brand-green-light, #35b673);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
    }
    
    .emi-result-box .result-label {
        color: var(--brand-green, #01a755) !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
        text-transform: uppercase;
        margin-bottom: 8px !important;
        display: block;
    }
    
    .emi-result-box .result-input {
        background-color: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        color: var(--brand-green, #01a755) !important;
        font-size: 1.4rem !important;
        font-weight: 800 !important;
        text-align: center !important;
        padding: 8px !important;
    }
    
    /* Contact details section */
    .contact-details-col {
        border-right: 1px solid var(--brand-border, #ddf3e8);
        padding-right: 25px;
    }
    
    @media (max-width: 768px) {
        .contact-details-col {
            border-right: none;
            border-bottom: 1px solid var(--brand-border, #ddf3e8);
            padding-right: 0px;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
    }
    
    .contact-subtitle {
        font-size: 1.1rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 8px !important;
    }
    
    .contact-text {
        font-size: 0.88rem;
        color: #64748b;
        line-height: 1.5;
    }
    
    .contact-list {
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .contact-list li {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }
    
    .contact-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--brand-bg-light, #f4fcf7);
        border: 1px solid var(--brand-border, #ddf3e8);
        color: var(--brand-green, #01a755);
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .contact-detail-text {
        display: flex;
        flex-direction: column;
        text-align: left;
    }
    
    .contact-detail-text strong {
        font-size: 0.8rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .contact-detail-text span, .contact-detail-text a {
        font-size: 0.95rem;
        color: #334155;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .contact-detail-text a:hover {
        color: var(--brand-green, #01a755);
    }

    /* ==========================================
       PREMIUM SERVICES SECTION STYLING
       ========================================== */
    .services-section {
        background-color: #ffffff;
        padding: 50px 0 !important;
    }
    
    .services-title {
        font-size: 2.25rem !important;
        font-weight: 800 !important;
        color: var(--brand-green, #01a755) !important;
        text-align: center;
        position: relative;
        padding-bottom: 15px;
        letter-spacing: 0.5px;
    }
    
    .services-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: var(--brand-green, #01a755);
        border-radius: 2px;
    }

    .services-title::before {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 4px;
        background-color: var(--brand-cyan, #00acf1);
        border-radius: 2px;
        z-index: 1;
    }
    
    .service-card-link {
        text-decoration: none !important;
    }
    
    .service-card {
        background: #ffffff;
        border: 1px solid var(--brand-border, #ddf3e8);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
        transition: var(--transition-smooth, all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1));
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(1, 167, 85, 0.12);
        border-color: var(--brand-green-light, #35b673);
    }
    
    .service-img-wrapper {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4/3;
        background-color: #f8fafc;
    }
    
    .service-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .service-card:hover .service-img-wrapper img {
        transform: scale(1.08);
    }
    
    /* Overlay effect on hover */
    .service-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(1, 167, 85, 0.8); /* Brand Green with transparency */
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .service-card:hover .service-card-overlay {
        opacity: 1;
    }
    
    .btn-view-service {
        color: #ffffff;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 2px solid #ffffff;
        padding: 8px 16px;
        border-radius: 25px;
        transform: translateY(15px);
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .service-card:hover .btn-view-service {
        transform: translateY(0);
    }
    
    .service-card-body {
        padding: 20px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .service-card-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        transition: color 0.2s ease;
    }
    
    .service-card:hover .service-card-name {
        color: var(--brand-green, #01a755);
    }

    /* Scroll Reveal Animation Styles */
    .scroll-reveal {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
    
    .scroll-reveal.reveal-active {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
    
    /* Delay classes for staggered animation */
    .reveal-delay-1 { transition-delay: 0.15s !important; }
    .reveal-delay-2 { transition-delay: 0.3s !important; }
    .reveal-delay-3 { transition-delay: 0.45s !important; }
    .reveal-delay-4 { transition-delay: 0.6s !important; }

    /* Sticky Navbar Scroll Effect styles */
    .navbar.sticky-top {
        transition: all 0.3s ease-in-out !important;
    }
    
    .navbar.sticky-top.navbar-scrolled {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
        border-bottom: 1px solid var(--brand-border, #ddf3e8) !important;
    }

    /* Homepage Notice Board Card Styling */
    .home-notice-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-top: 4px solid var(--brand-green, #01a755) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
        padding: 28px 24px !important;
    }

    .home-notice-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 0;
    }

    .home-notice-icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background-color: #eaf8f1;
        color: var(--brand-green, #01a755);
        border-radius: 12px;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .home-notice-card-title {
        font-size: 1.35rem !important;
        font-weight: 800 !important;
        color: #1e293b !important;
        margin: 0 !important;
        text-align: left;
    }

    .home-notice-tabs {
        display: flex;
        gap: 6px;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--brand-border, #ddf3e8);
    }

    .home-notice-tabs button {
        padding: 7px 12px !important;
        border: 1px solid transparent !important;
        border-radius: 8px !important;
        background: transparent !important;
        color: #64748b !important;
        font-size: .82rem !important;
        font-weight: 650 !important;
        cursor: pointer;
    }

    .home-notice-tabs button:hover,
    .home-notice-tabs button.active {
        border-color: #dceee2 !important;
        background: #eff8f2 !important;
        color: var(--brand-green, #08784b) !important;
    }

    .home-notice-empty {
        padding: 20px 8px;
        color: #78867e;
        font-size: .88rem;
        text-align: center;
    }

    .home-notice-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        max-height: 360px;
        overflow-y: auto;
        padding-right: 6px;
        scroll-behavior: smooth;
        overscroll-behavior: contain;
        scrollbar-width: thin;
        scrollbar-color: #c9ddcf transparent;
    }

    .home-notice-list::-webkit-scrollbar { width: 5px; }
    .home-notice-list::-webkit-scrollbar-thumb { border-radius: 5px; background: #c9ddcf; }

    .home-notice-list-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        border-bottom: 1px dashed var(--brand-border, #ddf3e8);
        padding: 10px 8px 14px;
        border-radius: 10px;
        transition: background .2s ease, transform .2s ease;
        text-decoration: none !important;
    }

    .home-notice-list-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .home-notice-list-item:hover {
        transform: translateX(2px);
        background: #f7fbf8;
    }

    .home-notice-file-icon {
        display: flex;
        flex: 0 0 48px;
        min-height: 54px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        border: 1px solid #dcece1;
        border-radius: 9px;
        background: #f0f7f2;
        color: var(--brand-green, #08784b);
    }

    .home-notice-file-icon i { font-size: 1.05rem; }
    .home-notice-file-icon span { font-size: .52rem; font-weight: 800; letter-spacing: .04em; }
    .home-notice-file-icon.is-live { border-color: #f2dada; background: #fff5f4; color: #c34f4f; }
    .home-notice-item-content { min-width: 0; }

    /* Minimalist Date Badge */
    .home-notice-mini-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 50px;
        background-color: #f1f5f9;
        border-radius: 8px;
        flex-shrink: 0;
        border: 1px solid #e2e8f0;
    }

    .home-notice-mini-day {
        font-size: 1.1rem !important;
        font-weight: 800 !important;
        color: #1e293b !important;
        line-height: 1 !important;
    }

    .home-notice-mini-month {
        font-size: 0.68rem !important;
        font-weight: 700 !important;
        color: #64748b !important;
        text-transform: uppercase;
        margin-top: 2px !important;
    }

    .home-notice-item-content {
        flex-grow: 1;
        text-align: left;
    }

    .home-notice-item-title {
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        color: #334155 !important;
        margin-bottom: 4px !important;
        line-height: 1.4 !important;
        transition: color 0.2s ease;
        text-align: left;
    }

    .home-notice-list-item:hover .home-notice-item-title {
        color: var(--brand-green, #01a755) !important;
    }

    .home-notice-item-time {
        font-size: 0.78rem !important;
        color: #94a3b8 !important;
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 0 !important;
    }

    .home-notice-preview {
        display: block;
        max-height: 0;
        margin: 0 !important;
        overflow: hidden;
        color: #68786e;
        font-size: .8rem;
        line-height: 1.55;
        opacity: 0;
        transition: max-height .3s ease, margin .3s ease, opacity .2s ease;
    }

    .home-notice-list-item:hover .home-notice-preview,
    .home-notice-list-item:focus-visible .home-notice-preview,
    .home-notice-list-item.preview-open .home-notice-preview {
        max-height: 180px;
        overflow-y: auto;
        scroll-behavior: smooth;
        margin-top: 7px !important;
        opacity: 1;
    }

    @media (prefers-reduced-motion: reduce) {
        .home-notice-list, .home-notice-preview { scroll-behavior: auto; transition: none; }
    }

    /* Blinking for live running events */
    @keyframes eventPulse {
        0%   { opacity: 1; }
        50%  { opacity: 0.3; }
        100% { opacity: 1; }
    }
    .live-event-item {
        animation: eventPulse 1.4s ease-in-out infinite;
    }
    .live-event-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #dc3545;
        animation: eventPulse 1s infinite;
        margin-right: 4px;
        vertical-align: middle;
    }

    /* Header & Footer Link / Button */
    .home-notice-top-link {
        font-size: 0.76rem !important;
        font-weight: 700 !important;
        color: var(--brand-green, #08784b) !important;
        text-decoration: none !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        padding: 4px 8px !important;
        border-radius: 6px !important;
        transition: all 0.2s ease !important;
    }

    .home-notice-top-link:hover {
        background-color: #eff8f2 !important;
        color: #05482d !important;
    }

    .home-notice-empty-btn {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        background-color: #eff8f2 !important;
        border: 1px solid #dceee2 !important;
        color: var(--brand-green, #08784b) !important;
        padding: 6px 14px !important;
        border-radius: 8px !important;
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
    }

    .home-notice-empty-btn:hover {
        background-color: var(--brand-green, #08784b) !important;
        color: #ffffff !important;
    }

    /* Footer Button */
    .home-notice-footer-btn {
        display: flex;
        gap: 8px;
        margin-top: 18px;
    }

    .home-notice-footer-btn a {
        display: inline-flex !important;
        flex: 1 1 0;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background-color: #eff8f2 !important;
        border: 1px solid #dceee2;
        color: var(--brand-green, #08784b) !important;
        font-weight: 700 !important;
        font-size: 0.76rem !important;
        padding: 9px 10px !important;
        border-radius: 9px !important;
        text-decoration: none !important;
        transition: all 0.25s ease !important;
    }

    .home-notice-footer-btn a:hover {
        background-color: var(--brand-green, #08784b) !important;
        transform: translateY(-1px);
        color: #ffffff !important;
    }

    .stat-value {
        font-size: 1.2rem; /* Reduced from 2.5rem */
        color: #2e7d32;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 8px;
        overflow-wrap: break-word; /* Handles long numbers better */
        word-break: break-word;
    }

    /* Add responsive adjustments */
    @media (max-width: 768px) {
        .stat-value {
            font-size: 1.5rem; /* Even smaller on mobile */
        }
    }

    @media (max-width: 576px) {
        .stat-value {
            font-size: 1.25rem; /* Smallest for very small screens */
        }
    }
</style>
@endsection

@section('content')
    <!-- ========== carousal section========== -->
    @if (isset($data['_sliders']) && $data['_sliders']->count() > 0)
        <div class="container-xl">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <!-- <ol class="carousel-indicators">
                                                @foreach ($data['_sliders'] as $key => $_slider)
    <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
    @endforeach
                                            </ol> -->
                <div class="carousel-inner">
                    @foreach ($data['_sliders'] as $key => $_slider)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-interval="5000"
                            style="--slide-image: url('{{ \App\Facades\ViewHelper::getImagePath('slider', $_slider->photo) }}')">
                            <img src="{{ \App\Facades\ViewHelper::getImagePath('slider', $_slider->photo) }}"
                                class="d-block w-100 " alt="{{ $_slider->alt_text }}" width="100%"
                                />

                            <div class="carousel-caption">
                                <h5 class="caption">{{ $_slider->caption }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    @endif
    <!-- ==========End carousal section========== -->
    <div class="container-xl">
        <nav class="home-quick-links" aria-label="Explore the website">
            @if(config('services.events_enabled', true))
                <a href="{{ route('events.index') }}">
                    <span class="quick-link-icon"><i class="fas fa-calendar-alt"></i></span>
                    <span><strong>Events</strong><small>Learning and programs</small></span>
                </a>
            @endif
            <a href="{{ route('service') }}">
                <span class="quick-link-icon"><i class="fas fa-hand-holding-heart"></i></span>
                <span><strong>Our services</strong><small>Support for members</small></span>
            </a>
            <a href="{{ route('resource-list') }}">
                <span class="quick-link-icon"><i class="fas fa-book-open"></i></span>
                <span><strong>Resources</strong><small>Guides and documents</small></span>
            </a>
            <a href="{{ route('notices') }}">
                <span class="quick-link-icon"><i class="fas fa-bullhorn"></i></span>
                <span><strong>Notices</strong><small>Updates from PROSCUN</small></span>
            </a>
            <a href="{{ route('contact') }}">
                <span class="quick-link-icon"><i class="fas fa-headset"></i></span>
                <span><strong>Contact us</strong><small>We are here to help</small></span>
            </a>
        </nav>
    </div>
    <!-- ========== Latest news strip ========== -->
    @if (isset($data['_latest_news']) && $data['_latest_news']->count() > 0)
        <div class="container-xl">
            <div class="recent-news-strip" aria-label="Latest news">
                <span class="recent-news-label"><i class="far fa-newspaper"></i> Latest news</span>
                <div class="recent-news-items">
                    <div class="recent-news-track">
                        @for ($copy = 0; $copy < 2; $copy++)
                            <div class="recent-news-group" @if($copy === 1) aria-hidden="true" @endif>
                                @foreach ($data['_latest_news'] as $_news)
                                    <a href="{{ route('news.detail', $_news->slug) }}" @if($copy === 1) tabindex="-1" @endif>{{ $_news->title }}</a>
                                    @unless ($loop->last)<span aria-hidden="true">•</span>@endunless
                                @endforeach
                            </div>
                        @endfor
                    </div>
                </div>
                <a class="recent-news-more" href="{{ route('news') }}">All news <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    @endif
    @if (isset($data['_aboutUs']))
        <!-- ==========About section========== -->
        <div class="container-fluid about-us">
            <div class="row d-flex justify-content-between">
                <!-- About Us Section -->
                <div class="col-lg-6 about-us-item scroll-reveal">
                    <h2 class="about-us-title">{{ $data['_aboutUs']->title ?? 'About NEFSCUN' }}</h2>
                    <span class="home-section-accent" aria-hidden="true"></span>
                    <p class="about-us-description">
                        {{ \Illuminate\Support\Str::limit(strip_tags($data['_aboutUs']->description), 720) }}
                    </p>
                    <a class="about-us-read-more" href="{{ route('about.detail', $data['_aboutUs']->slug) }}">Explore about us <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>

                <section class="col-lg-6 home-messages scroll-reveal" aria-labelledby="homeMessagesTitle">
                    <h2 class="home-messages__heading" id="homeMessagesTitle">Messages &amp; Commitment</h2>
                    <div class="home-messages__list">
                        @if (isset($data['_commitmentFromCEO']))
                            <article class="home-message-card">
                                <h3>{{ $data['_commitmentFromCEO']->title ?? 'Commitment From CEO' }}</h3>
                                <div class="home-message-card__body">
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('aboutUs', $data['_commitmentFromCEO']->photo) }}" alt="{{ $data['_commitmentFromCEO']->title ?? 'Commitment From CEO' }}" onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}'">
                                    <p>{{ \App\Facades\ViewHelper::getStrippedContent($data['_commitmentFromCEO']->description, 280) }}
                                        <a href="{{ route('about.detail', $data['_commitmentFromCEO']->slug) }}">Read more</a>
                                    </p>
                                </div>
                            </article>
                        @endif
                        @if (isset($data['_chairmanVoice']))
                            <article class="home-message-card">
                                <h3>{{ $data['_chairmanVoice']->title ?? 'Chairman Voice' }}</h3>
                                <div class="home-message-card__body">
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('aboutUs', $data['_chairmanVoice']->photo) }}" alt="{{ $data['_chairmanVoice']->title ?? 'Chairman Voice' }}" onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}'">
                                    <p>{{ \App\Facades\ViewHelper::getStrippedContent($data['_chairmanVoice']->description, 280) }}
                                        <a href="{{ route('about.detail', $data['_chairmanVoice']->slug) }}">Read more</a>
                                    </p>
                                </div>
                            </article>
                        @endif
                    </div>
                </section>

            </div>
        </div>
    @endif





    <!--------- Testimonial / Statistics Section --------->


    <!--------- Statistics Section --------->
    @php $isSavingsStatistics = \Illuminate\Support\Str::contains($data['site_setting_title'] ?? '', 'बचतको प्रकारहरू'); @endphp
    <section class="home-stat-overview-section my-5 py-2 {{ $isSavingsStatistics ? 'home-stat-overview-section--savings' : '' }}" aria-labelledby="stat-overview-title">
        <div class="container-xl">
            <div class="text-center mb-4 pb-2">
                <span class="statistics-eyebrow">KEY INDICATORS</span>
                <h2 id="stat-overview-title" class="home-stat-main-title">
                    {{ $data['site_setting_title'] ?? 'वित्तीय परिसूचकहरू' }}
                </h2>
                <div class="home-section-accent mx-auto"></div>
            </div>

            @if(isset($data['site_setting_stat']) && $data['site_setting_stat']->count() > 0)
                <!-- Desktop/Tablet Statistics Carousel (Visible on MD and up) -->
                <div id="statisticsCarousel" class="carousel slide home-stat-carousel d-none d-md-block" data-ride="carousel" data-interval="3500">
                    <div class="carousel-inner">
                        @php
                            $chunks = $data['site_setting_stat']->chunk(4); // 4 items per slide
                        @endphp
                        
                        @foreach($chunks as $chunkIndex => $chunk)
                            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                <div class="row justify-content-center">
                                    @foreach($chunk as $statprofile)
                                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                            <div class="home-stat-card h-100">
                                                <div class="home-stat-card-body">
                                                    <div class="home-stat-icon-wrapper mb-3">
                                                        @if($statprofile->photo)
                                                            <img src="{{ asset('images/siteSetting/' . $statprofile->photo) }}" 
                                                                 alt="{{ $statprofile->name }}" 
                                                                 class="home-stat-thumb-img">
                                                        @else
                                                            <span class="home-stat-icon-circle">
                                                                <i class="fas fa-chart-line"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($isSavingsStatistics)
                                                        <h3 class="home-stat-card-label home-savings-card-title mb-2">{{ $statprofile->name }}</h3>
                                                        <p class="home-savings-card-detail mb-0">{{ $statprofile->value }}</p>
                                                    @else
                                                        <h3 class="home-stat-card-value text-break mb-1">{{ $statprofile->value ?? 0 }}</h3>
                                                        <p class="home-stat-card-label mb-0">{{ $statprofile->name }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Carousel Controls -->
                    @if($chunks->count() > 1)
                        <a class="carousel-control-prev home-stat-control" href="#statisticsCarousel" role="button" data-slide="prev" aria-label="Previous slide">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <a class="carousel-control-next home-stat-control" href="#statisticsCarousel" role="button" data-slide="next" aria-label="Next slide">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        
                        <!-- Carousel Indicators -->
                        <ol class="carousel-indicators home-stat-indicators">
                            @foreach($chunks as $chunkIndex => $chunk)
                                <li data-target="#statisticsCarousel" data-slide-to="{{ $chunkIndex }}" 
                                    class="{{ $chunkIndex == 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                    @endif
                </div>

                <!-- Mobile Statistics Carousel (Visible on XS/SM screens) -->
                <div id="statisticsCarouselMobile" class="carousel slide home-stat-carousel d-block d-md-none" data-ride="carousel" data-interval="3500">
                    <div class="carousel-inner">
                        @php
                            $mobileChunks = $data['site_setting_stat']->chunk(1); // 1 item per slide on mobile
                        @endphp
                        
                        @foreach($mobileChunks as $chunkIndex => $chunk)
                            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                <div class="row justify-content-center px-4">
                                    @foreach($chunk as $statprofile)
                                        <div class="col-12 mb-3">
                                            <div class="home-stat-card text-center h-100">
                                                <div class="home-stat-card-body py-4 px-3">
                                                    <div class="home-stat-icon-wrapper mb-3">
                                                        @if($statprofile->photo)
                                                            <img src="{{ asset('images/siteSetting/' . $statprofile->photo) }}" 
                                                                 alt="{{ $statprofile->name }}" 
                                                                 class="home-stat-thumb-img">
                                                        @else
                                                            <span class="home-stat-icon-circle">
                                                                <i class="fas fa-chart-line"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($isSavingsStatistics)
                                                        <h3 class="home-stat-card-label home-savings-card-title mb-2">{{ $statprofile->name }}</h3>
                                                        <p class="home-savings-card-detail mb-0">{{ $statprofile->value }}</p>
                                                    @else
                                                        <h3 class="home-stat-card-value text-break mb-2">{{ $statprofile->value ?? 0 }}</h3>
                                                        <p class="home-stat-card-label mb-0">{{ $statprofile->name }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Carousel Controls -->
                    @if($mobileChunks->count() > 1)
                        <a class="carousel-control-prev home-stat-control" href="#statisticsCarouselMobile" role="button" data-slide="prev" aria-label="Previous slide">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </a>
                        <a class="carousel-control-next home-stat-control" href="#statisticsCarouselMobile" role="button" data-slide="next" aria-label="Next slide">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </a>
                        
                        <!-- Carousel Indicators -->
                        <ol class="carousel-indicators home-stat-indicators">
                            @foreach($mobileChunks as $chunkIndex => $chunk)
                                <li data-target="#statisticsCarouselMobile" data-slide-to="{{ $chunkIndex }}" 
                                    class="{{ $chunkIndex == 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            @else
                <div class="statistics-empty">
                    <i class="fas fa-chart-bar" aria-hidden="true"></i>
                    <p>No statistics available at the moment.</p>
                </div>
            @endif
        </div>
    </section>
    <!---------End Statistics section--------->


    <!---------Chart Section--------->
    @include('website.home.section.chart')
    <!---------End Chart Section--------->
    @if (isset($data['_success_stories']) && $data['_success_stories']->count() > 0)
        <div class="container-xl mt-2 scroll-reveal">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <h3 class="text-center stat-header">Success Story</h3>
                </div>
                <div class="col-lg-6">
                    <div class="testimonial-box">
                        <div id="_success_stories" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                @foreach ($data['_success_stories'] as $key => $_story)
                                    <li data-target="#_success_stories{{ $key }}"
                                        data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}">
                                    </li>
                                @endforeach
                            </ul>
                            <!-- The slideshow -->
                            <div class="carousel-inner">
                                @foreach ($data['_success_stories'] as $key => $_story)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <div id="media_image-2" class="widget text-center widget_media_image">
                                            <img width="150" height="150"
                                                src="{{ ViewHelper::getImagePath('successStory', $_story->photo) }}"
                                                class="image success-img" alt="{{ $_story->name }}" loading="lazy"
                                                srcset="{{ ViewHelper::getImagePath('successStory', $_story->photo) }} 300w,
                                                                 {{ ViewHelper::getImagePath('successStory', $_story->photo) }} 150w,
                                                                 {{ ViewHelper::getImagePath('successStory', $_story->photo) }} 612w"
                                                sizes="(max-width: 300px) 100vw, 300px" />
                                        </div>
                                        <div class="text-center">
                                            <p>{!! $_story->description ??
                                                'There are many variations
                                                                                                                                                                                                                                                                                                                                                                                                        of passages of Lorem Ipsum available, but the majority have suffered
                                                                                                                                                                                                                                                                                                                                                                                                        alteration in some form, by injected humour, or randomised words which
                                                                                                                                                                                                                                                                                                                                                                                                        don’t look even slightly believable. If you are going to use a passage
                                                                                                                                                                                                                                                                                                                                                                                                        of Lorem Ipsum, you need to be sure there isn’t anything embarrassing
                                                                                                                                                                                                                                                                                                                                                                                                        hidden in the middle of text. All the Lorem Ipsum generators on the
                                                                                                                                                                                                                                                                                                                                                                                                        Internet tend to repeat predefined chunks as necessary, making this the
                                                                                                                                                                                                                                                                                                                                                                                                        first true generator on the Internet.' !!}</p>
                                        </div>
                                        <h4 class="testi-name text-center">
                                            {{ $_story->name ?? 'Diya Mirza' }}
                                        </h4>
                                        <div class="text-center">
                                            <ul class="social">
                                                <li>
                                                    <a href="mailto:{{ $_story->email }}" class="open_in_new_tab_class"
                                                        title="{{ $_story->email }}" id="team-social-link">
                                                        <i class="fa fa-envelope one"></i>
                                                        {{ $_story->email }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a tooltip-href="{{ $_story->phone }}" wpm-6310-tooltip="yes"
                                                        href="tel:{{ $_story->phone }}" class="wpm-6310-tooltip"
                                                        title="{{ $_story->phone }}" id="team-social-link">
                                                        <i class="fa fa-phone one"></i>
                                                        {{ $_story->phone }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <br>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="container-xl emi-contact-section mb-5 py-4">
        <div class="row justify-content-center">
            <!-- EMI Calculator -->
            <div class="col-lg-5 col-md-12 mb-4 mb-lg-0 scroll-reveal">
                <div class="emi-card p-4">
                    <h3 class="section-card-title text-center mb-4">EMI Calculator</h3>
                    <form class="form">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="principal" class="form-label">Loan Amount</label>
                                <input type="number" class="form-control" value="50000" id="principal">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="rate" class="form-label">Interest Rate %</label>
                                <input type="number" class="form-control" value="10" id="rate">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="month" class="form-label">Term (Months)</label>
                            <input type="number" class="form-control" value="5" id="month">
                        </div>
                        <div class="mb-4">
                            <button type="button" id="calculate" class="btn btn-calculate w-100">CALCULATE EMI</button>
                        </div>
                        
                        <div class="emi-result-box mb-4">
                            <label for="emi" class="result-label">Monthly Payment (EMI)</label>
                            <input type="text" class="form-control result-input" id="emi" readonly>
                        </div>
                        
                        <div>
                            <button type="reset" class="btn btn-reset w-100">RESET</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7 col-md-12 scroll-reveal reveal-delay-1">
                <div class="contact-card p-4">
                    <h3 class="section-card-title text-center mb-4">Contact Us</h3>
                    
                    <div class="row">
                        <!-- Contact Info Column -->
                        <div class="col-md-5 mb-4 mb-md-0 contact-details-col">
                            <h5 class="contact-subtitle">Get in Touch</h5>
                            <p class="contact-text mb-4">Reach out to us directly for any inquiries or support.</p>
                            
                            <ul class="contact-list">
                                <li>
                                    <div class="contact-icon-wrapper">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <div class="contact-detail-text">
                                        <strong>Location</strong>
                                        <span>{{ $_site_setting->location ?? 'New Banepa, Kavre' }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="contact-icon-wrapper">
                                        <i class="fa fa-mobile"></i>
                                    </div>
                                    <div class="contact-detail-text">
                                        <strong>Phone</strong>
                                        <a href="tel:{{ $_site_setting->phone ?? '011-665368' }}">{{ $_site_setting->phone ?? '011-665368' }}</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="contact-icon-wrapper">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="contact-detail-text">
                                        <strong>Email</strong>
                                        <a href="mailto:{{ $_site_setting->email ?? 'info@bagmatiproscun.org.np' }}">{{ $_site_setting->email ?? 'info@bagmatiproscun.org.np' }}</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Contact Form Column -->
                        <div class="col-md-7">
                            <form action="{{ route('contact.store') }}" method="post" class="form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <input type="text"
                                            class="{{ $errors->has('name') ? 'form-control is-invalid' : 'form-control' }}"
                                            name="name" required value="{{ old('name') }}"
                                            placeholder="Your Name">
                                        @error('name')
                                            <span class="help-block invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text"
                                            class="{{ $errors->has('phone') ? 'form-control is-invalid' : 'form-control' }}"
                                            name="phone" required value="{{ old('phone') }}"
                                            placeholder="Phone Number">
                                        @error('phone')
                                            <span class="help-block invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="email"
                                            class="{{ $errors->has('email') ? 'form-control is-invalid' : 'form-control' }}"
                                            name="email" required value="{{ old('email') }}"
                                            placeholder="Email Address">
                                        @error('email')
                                            <span class="help-block invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text"
                                            class="{{ $errors->has('subject') ? 'form-control is-invalid' : 'form-control' }}"
                                            name="subject" value="{{ old('subject') }}" placeholder="Subject">
                                        @error('subject')
                                            <span class="help-block invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <textarea class="{{ $errors->has('message') ? 'form-control is-invalid' : 'form-control' }}" name="message"
                                            rows="4" placeholder="Your Message" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="help-block invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group text-right mb-0">
                                    <button type="submit" class="btn btn-submit-contact">Send Message</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xl services-section py-5 my-4">
        <!-- Section Title -->
        <div class="text-center mb-5">
            <h2 class="services-title scroll-reveal">Our Services</h2>
        </div>
        
        <div class="row justify-content-center">
            @forelse($data['_serviceslist'] as $key => $service)
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 scroll-reveal reveal-delay-{{ $key % 4 }}">
                    <a href="{{ route('service.detail', $service->slug) }}" class="service-card-link">
                        <div class="service-card">
                            <div class="service-img-wrapper">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('serviceCategory', $service->photo) }}"
                                    alt="{{ $service->title }}" class="img-fluid">
                                <div class="service-card-overlay">
                                    <span class="btn-view-service">Explore <i class="fa fa-arrow-right"></i></span>
                                </div>
                            </div>
                            <div class="service-card-body">
                                <h5 class="service-card-name">{{ $service->title }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">No services available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
    @include('website.home.affiliation')

    @forelse($data['_popup']->take(1) as $_pop)
        <div class="modal fade" id="exampleModalCenter{{ $_pop->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="modal-title text-center" id="noticeModalTitle{{ $_pop->id }}">
                            {{ $_pop->title }}
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span style="color: red; font-size: 25px; " aria-hidden="true">&times;</span>
                            </button>
                        </h5>
                        <img style="display: block; margin-left: auto; margin-right: auto;" width="100%"
                            src="{{ \App\Facades\ViewHelper::getImagePath('notice', $_pop->photo) }}"
                            alt="{{ $_pop->title }}">
                        <div class="text-center">
                            <a href="{{ route('notices.detail', $_pop->slug) }}">View Detail...</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <!-- No popups available -->
    @endforelse

@endsection
@section('js')
    <script>
        $(function() {
            // Sticky Navbar Scroll Observer
            $(window).on('scroll', function() {
                if ($(window).scrollTop() > 50) {
                    $('.navbar.sticky-top').addClass('navbar-scrolled');
                } else {
                    $('.navbar.sticky-top').removeClass('navbar-scrolled');
                }
            });
            if ($(window).scrollTop() > 50) {
                $('.navbar.sticky-top').addClass('navbar-scrolled');
            }

            // Scroll Reveal Observer
            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('reveal-active');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.05,
                    rootMargin: '0px 0px -40px 0px'
                });

                document.querySelectorAll('.scroll-reveal').forEach(el => {
                    revealObserver.observe(el);
                });
            } else {
                document.querySelectorAll('.scroll-reveal').forEach(el => {
                    el.classList.add('reveal-active');
                });
            }

            calculateEMI();

            function setValue(element, value) {
                $(element).val(value);
            }

            function getValue(element) {
                return parseFloat($(element).val());
            }

            function calculateEMI() {
                let principal = getValue('#principal');
                let rate = getValue('#rate') / 1200;
                let month = getValue('#month');
                let r = 1 + rate;
                let p = principal * rate;
                let t = Math.pow(r, month);
                let u = p * t;
                let d = t - 1;

                let emi = (u / d).toFixed(2);

                setValue('#emi', emi)
            }
            $('body').on('click', '#calculate', function() {
                calculateEMI();
            })

            $('#search-nav-select').on('change', function(e) {
                var thisVal = $(this).val()
                $('.statistics-panel > .tab-pane').removeClass('active show');
                $('.statistics-panel > #' + thisVal).addClass("active show");
            })
        })
    </script>
    @if (isset($data['_popup']))
        <script>
            $(document).ready(function() {
                // Loop through each modal box
                @forelse($data['_popup']->take(1) as $_pop)
                    // Show the modal box
                    $('#exampleModalCenter{{ $_pop->id }}').modal('show');
                @empty
                    // No popups available
                @endforelse
            });
        </script>
    @endif
    <script>
        document.querySelectorAll('.home-updates-dock--legacy').forEach(function(dock) {
            const trigger = dock.querySelector('.home-updates-dock__trigger');
            if (!trigger) return;

            function closeDock() {
                dock.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            trigger.addEventListener('click', function() {
                const open = !dock.classList.contains('is-open');
                dock.classList.toggle('is-open', open);
                trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
            });

            document.addEventListener('click', function(event) {
                if (!dock.contains(event.target)) closeDock();
            });
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') closeDock();
            });
        });

        document.querySelectorAll('.home-notice-card--legacy').forEach(function(card) {
            const tabs = card.querySelectorAll('[data-update-filter]');
            const items = card.querySelectorAll('[data-update-type]');

            items.forEach(function(item) {
                item.addEventListener('pointerenter', function() { item.classList.add('preview-open'); });
                item.addEventListener('pointerleave', function() { item.classList.remove('preview-open'); });
                item.addEventListener('focusin', function() { item.classList.add('preview-open'); });
                item.addEventListener('focusout', function() { item.classList.remove('preview-open'); });
            });

            function showUpdates(type) {
                let visibleCount = 0;

                items.forEach(function(item) {
                    const visible = type === 'all'
                        ? Number(item.dataset.updateIndex) < 4
                        : item.dataset.updateType === type && Number(item.dataset.categoryIndex) < 3;
                    item.hidden = !visible;
                    if (visible) visibleCount++;
                });

                tabs.forEach(function(tab) {
                    const active = tab.dataset.updateFilter === type;
                    tab.classList.toggle('active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                card.querySelectorAll('[data-empty-type]').forEach(function(emptyState) {
                    emptyState.hidden = emptyState.dataset.emptyType !== type || visibleCount > 0;
                });

                const topLink = card.querySelector('#homeNoticeTopLink');
                const btnNotice = card.querySelector('#homeNoticeAllNoticesBtn');
                const btnEvent = card.querySelector('#homeNoticeAllEventsBtn');

                if (type === 'notice') {
                    if (topLink) {
                        topLink.href = '{{ route('notices') }}';
                        topLink.innerHTML = 'See all notices <i class="fas fa-arrow-right"></i>';
                    }
                    if (btnNotice) { btnNotice.style.display = 'inline-flex'; btnNotice.style.flex = '1 1 100%'; }
                    if (btnEvent) { btnEvent.style.display = 'none'; }
                } else if (type === 'event') {
                    if (topLink) {
                        topLink.href = '{{ route('events.index') }}';
                        topLink.innerHTML = 'See all events <i class="fas fa-arrow-right"></i>';
                    }
                    if (btnNotice) { btnNotice.style.display = 'none'; }
                    if (btnEvent) { btnEvent.style.display = 'inline-flex'; btnEvent.style.flex = '1 1 100%'; }
                } else {
                    if (topLink) {
                        topLink.href = '{{ route('notices') }}';
                        topLink.innerHTML = 'See all <i class="fas fa-arrow-right"></i>';
                    }
                    if (btnNotice) { btnNotice.style.display = 'inline-flex'; btnNotice.style.flex = '1 1 0'; }
                    if (btnEvent) { btnEvent.style.display = 'inline-flex'; btnEvent.style.flex = '1 1 0'; }
                }
            }

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    showUpdates(tab.dataset.updateFilter);
                });
            });

            showUpdates('all');
        });
    </script>
  {{-- <script src="{{ asset('website/assets/chart.js') }}"></script> 
    <script>
        @php
            $labels = $data['_statistics']->pluck('d_name')->toArray();
            $datas = $data['_statistics']->pluck('associated')->toArray();
            $totals = $data['_statistics']->pluck('total')->toArray();
            $colors = $data['_statistics']->pluck('color')->toArray();

            // Combine label with data value
            $xValues = array_map(
                function ($label, $data) {
                    return $label . ' (' . $data . ')';
                },
                $labels,
                $datas,
            );
        @endphp
        var xValues = {!! json_encode($xValues) !!};
        var yValues = {!! json_encode($datas) !!};
        var totalValues = {!! json_encode($totals) !!};
        var barColors = {!! json_encode($colors) !!};

        callChart("chart-line", xValues, yValues, totalValues, barColors, "Bagmati Province Organization");

        function callChart(chartId, xValues, yValues, totalValues, barColors, title) {
            const ctx = document.getElementById(chartId);
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: xValues,
                    datasets: [{
                            label: 'Total',
                            backgroundColor: barColors,
                            data: totalValues
                        },
                        {
                            label: 'Associated',
                            backgroundColor: barColors,
                            data: yValues
                        },
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: title
                        },
                    }
                },
            });
        }

        @foreach ($data['_statistics'] as $key => $statistics)

            @php
                $labels = $statistics->pluck('municipality_name')->toArray();
                $datas = $statistics->pluck('associated')->toArray();
                $totals = $statistics->pluck('total')->toArray();
                $colors = $statistics->pluck('color')->toArray();

                $xValues = array_map(
                    function ($label, $data) {
                        return $label . ' (' . $data . ')';
                    },
                    $labels,
                    $datas,
                );
            @endphp

            var xValues = {!! json_encode($xValues) !!};
            var yValues = {!! json_encode($datas) !!};
            var totalValues = {!! json_encode($totals) !!};
            var barColors = {!! json_encode($colors) !!};
            var title = '{{ $statistics->d_name }} Organization Report';

            callChart("chart-line-{{ $key }}", xValues, yValues, totalValues, barColors, title);
        @endforeach
    </script> --}}

    
@endsection
