@extends('layouts.app')
@section('title', 'Hire-a-Friend - Find Your Companion and Hire-a-Friend')

@php
    $favIds = auth()->check() ? auth()->user()->favorites()->pluck('users.id')->toArray() : [];
@endphp

@section('styles')
<style>
    /* Spacing & Base */
    .section-spacing {
        padding: 2.25rem 0;
    }

    .section-title {
        font-family: 'Jost', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.5px;
    }

    html.dark .section-title {
        color: #ffffff;
    }

    @media (max-width: 767.98px) {
        .section-title {
            font-size: 1.35rem !important;
        }
        .slider-arrow-btn {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.8rem !important;
        }
        .view-all-link {
            font-size: 0.85rem !important;
        }
    }

    @media (max-width: 360px) {
        .section-title {
            font-size: 1.12rem !important;
        }
        .slider-arrow-btn {
            width: 26px !important;
            height: 26px !important;
            font-size: 0.7rem !important;
        }
        .view-all-link {
            font-size: 0.8rem !important;
        }
    }

    /* Carousel Slideshow Section */
    .hero-slideshow {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius-lg);
        height: 480px;
        background: var(--bg-inverse);
        margin-bottom: 2.25rem;
    }

    .slideshow-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.85;
    }

    .slideshow-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 4rem 3.5rem;
        background: linear-gradient(transparent 0%, rgba(11, 21, 48, 0.9) 100%);
        color: #ffffff;
        z-index: 2;
    }

    .slideshow-title {
        color: #ffffff !important;
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.5px;
        margin-bottom: 1rem;
    }

    @media (max-width: 767.98px) {
        .slideshow-title {
            font-size: 2.25rem;
        }
        .hero-slideshow {
            height: 350px;
        }
        .slideshow-content {
            padding: 2rem;
        }
    }

    /* Companion Cards (Hire-a-Friend Style) */
    .hire-companion-card {
        border-radius: 16px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .hire-companion-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
        border-color: rgba(37, 99, 235, 0.2);
    }

    /* Bio Hover Overlay */
    .card-bio-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7) !important; /* Darker background for contrast */
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 3;
    }

    .hire-companion-card:hover .card-bio-overlay {
        opacity: 1;
        visibility: visible;
    }

    .bio-content {
        text-align: center;
        color: #ffffff !important;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.95), 0 1px 3px rgba(0, 0, 0, 0.9) !important;
    }

    .bio-title {
        font-family: 'Jost', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #e879f9 !important;
        margin-bottom: 0.5rem;
        transform: translateY(-10px);
        transition: transform 0.3s ease;
    }

    .hire-companion-card:hover .bio-title {
        transform: translateY(0);
    }

    .bio-text {
        font-size: 0.8rem;
        line-height: 1.4;
        color: rgba(255, 255, 255, 0.95) !important;
        margin: 0;
        transform: translateY(10px);
        transition: transform 0.3s ease;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .hire-companion-card:hover .bio-text {
        transform: translateY(0);
    }

    .card-img-container {
        position: relative;
        overflow: hidden;
        aspect-ratio: 1 / 1;
    }

    .card-overlay-details {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 2.5rem 1.25rem 1.25rem;
        background: linear-gradient(transparent 0%, rgba(15, 23, 42, 0.95) 100%);
        color: #ffffff;
    }

    .card-overlay-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.15rem;
        color: #ffffff !important;
    }

    .card-rating-badge {
        position: absolute;
        top: 1rem;
        end: 1rem;
        background: rgba(255, 255, 255, 0.95);
        color: #0f172a;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 0.4rem 0.65rem;
        border-radius: 30px;
        box-shadow: var(--shadow-sm);
    }

    .card-stats-grid {
        background: var(--card-bg);
        padding: 1.25rem 0.5rem;
        border-bottom: 1px solid var(--card-border);
    }

    .card-stat-col {
        text-align: center;
    }

    .card-stat-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.15rem;
    }

    .card-stat-value {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-dark);
    }

    .btn-black-cta {
        background: var(--bg-inverse);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.65rem 0;
        border-radius: 12px;
        border: none;
        width: 100%;
        display: block;
        text-align: center;
        transition: var(--transition);
        margin-top: 1rem;
    }

    .btn-black-cta:hover {
        background: #222222;
    }

    html.dark .btn-black-cta {
        background: #334155;
    }
    html.dark .btn-black-cta:hover {
        background: #475569;
    }

    /* Collage styling */
    .collage-row {
        height: 240px;
        overflow: hidden;
        border-radius: 20px;
    }

    .collage-panel img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .collage-panel:hover img {
        transform: scale(1.05);
    }

    /* ── How It Works – Step Cards ──────────────────────── */
    .how-it-works-section {
        background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
        border-radius: 28px;
        padding: 4rem 2.5rem;
    }

    html.dark .how-it-works-section {
        background: linear-gradient(135deg, rgba(30,41,70,0.6) 0%, rgba(15,23,42,0.8) 100%);
    }

    .step-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 2.5rem 2rem 2.25rem;
        text-align: center;
        box-shadow: 0 4px 24px rgba(11, 21, 48, 0.08);
        border: 1px solid rgba(226, 232, 240, 0.9);
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        overflow: visible;
    }

    html.dark .step-card {
        background: rgba(30, 41, 70, 0.85);
        border-color: rgba(71, 85, 105, 0.5);
        box-shadow: 0 4px 24px rgba(0,0,0,0.3);
    }

    .step-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 48px rgba(11, 21, 48, 0.16);
    }

    html.dark .step-card:hover {
        box-shadow: 0 20px 48px rgba(0,0,0,0.45);
    }

    /* Connector line between cards on desktop */
    .step-connector {
        position: absolute;
        top: 65px;
        right: -50%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #e2e8f0, transparent);
        z-index: 0;
        pointer-events: none;
    }
    html.dark .step-connector {
        background: linear-gradient(90deg, rgba(71,85,105,0.5), transparent);
    }
    @media (max-width: 991.98px) {
        .step-connector { display: none; }
    }

    /* Circle icon */
    .circle-icon-box {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.75rem;
        font-size: 2.75rem;
        position: relative;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .step-card:hover .circle-icon-box {
        transform: scale(1.08);
    }

    .step-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 36px;
        height: 36px;
        background: var(--bg-primary);
        color: #ffffff;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.78rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.18);
        letter-spacing: 0.02em;
    }

    html.dark .step-badge {
        background: #f8faff;
        color: var(--text-primary);
        border-color: rgba(30,41,70,0.85);
    }

    .step-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.65rem;
        letter-spacing: -0.2px;
    }

    html.dark .step-card-title {
        color: #f1f5f9;
    }

    .step-card-desc {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.65;
        margin: 0;
        max-width: 240px;
    }

    html.dark .step-card-desc {
        color: #94a3b8;
    }

    /* Phone Mockup floating animations */
    .phone-mockup-container {
        position: relative;
        width: 320px;
        margin: 0 auto;
    }

    .phone-mockup-frame {
        border: 12px solid #1e293b;
        border-radius: 40px;
        background: var(--bg-inverse);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        aspect-ratio: 9 / 18;
    }

    @keyframes floatHeart {
        0% {
            transform: translateY(100px) scale(0.6) rotate(0deg);
            opacity: 0;
        }
        50% {
            opacity: 0.8;
        }
        100% {
            transform: translateY(-120px) scale(1.1) rotate(15deg);
            opacity: 0;
        }
    }

    .floating-heart {
        position: absolute;
        color: #ec4899;
        font-size: 1.75rem;
        animation: floatHeart 4s infinite ease-in-out;
    }

    /* ── Companion Horizontal Carousel ─────────────────── */
    .slider-arrow-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1.5px solid var(--card-border);
        background: var(--card-bg);
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        flex-shrink: 0;
    }
    .slider-arrow-btn:hover {
        background: #2563EB;
        border-color: #2563EB;
        color: #fff;
        transform: scale(1.08);
    }
    .slider-arrow-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        transform: none;
    }

    .companion-slider-wrapper {
        position: relative;
        overflow: hidden;
    }

    .companion-slider {
        display: flex;
        gap: 1.25rem;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 8px;
        cursor: grab;
        user-select: none;
    }
    .companion-slider:active {
        cursor: grabbing;
    }
    /* Hide scrollbar */
    .companion-slider::-webkit-scrollbar { display: none; }
    .companion-slider { -ms-overflow-style: none; scrollbar-width: none; }

    .companion-slide {
        flex: 0 0 280px;
        scroll-snap-align: start;
        min-width: 280px;
    }

    @media (max-width: 575.98px) {
        .companion-slide { flex: 0 0 82vw; min-width: 82vw; }
    }
    @media (min-width: 576px) and (max-width: 991.98px) {
        .companion-slide { flex: 0 0 260px; min-width: 260px; }
    }

    /* Dot indicators */
    .slider-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
    }
    .slider-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-block;
    }
    .slider-dot.active {
        background: #2563EB;
        width: 22px;
        border-radius: 4px;
    }

    /* ── Photo Mosaic Collage ─────────────────────────── */
    .photo-mosaic {
        display: flex;
        flex-direction: column;
        gap: 6px;
        border-radius: 20px;
        overflow: hidden;
    }

    .mosaic-row {
        display: flex;
        gap: 6px;
    }

    .mosaic-row-1 { height: 240px; }
    .mosaic-row-2 { height: 190px; }
    .mosaic-row-3 { height: 160px; }

    .mosaic-cell {
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    .mosaic-link {
        display: block;
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .mosaic-link img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
        display: block;
    }

    .mosaic-link:hover img {
        transform: scale(1.08);
    }

    .mosaic-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 1.5rem 0.85rem 0.75rem;
        background: linear-gradient(transparent 0%, rgba(10, 15, 40, 0.88) 100%);
        color: #fff;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .mosaic-link:hover .mosaic-overlay {
        opacity: 1;
    }

    .mosaic-name {
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #ffffff !important;
    }

    .mosaic-city {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.7);
    }

    @media (max-width: 575.98px) {
        .mosaic-row-1 { height: 140px; }
        .mosaic-row-2 { height: 110px; }
        .mosaic-row-3 { height: 90px; }
        .mosaic-row { gap: 3px; }
        .photo-mosaic { gap: 3px; }
    }

    /* Hero Carousel Indicators & Navigation Arrows */
    .hero-indicators.carousel-indicators {
        position: absolute;
        bottom: 2.5rem;
        left: 3.5rem;
        right: auto;
        display: flex;
        gap: 8px;
        z-index: 10;
        margin: 0;
        padding: 0;
        justify-content: flex-start;
    }
    
    .hero-indicators.carousel-indicators .hero-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.25s ease;
        text-indent: 0;
        opacity: 1;
    }
    
    .hero-indicators.carousel-indicators .hero-dot.active {
        background: #ffffff;
        width: 26px;
        border-radius: 5px;
    }
    
    .hero-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        cursor: pointer;
        z-index: 10;
        transition: all 0.25s ease;
    }
    
    .hero-arrow:hover {
        background: #ffffff;
        color: var(--text-primary);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .hero-arrow-prev {
        left: 2rem;
    }
    
    .hero-arrow-next {
        right: 2rem;
    }

    @media (max-width: 767.98px) {
        .hero-indicators.carousel-indicators {
            left: 2rem;
            bottom: 1.5rem;
        }
        .hero-arrow {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .hero-arrow-prev {
            left: 1rem;
        }
        .hero-arrow-next {
            right: 1rem;
        }
    }

    /* Favorites button styles */
    .card-fav-btn {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        font-size: 1.15rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transition: var(--transition);
        z-index: 5;
        cursor: pointer;
    }
    .card-fav-btn:hover {
        transform: scale(1.1);
        background: #fff;
        box-shadow: 0 6px 16px rgba(0,0,0,0.18);
    }
    html.dark .card-fav-btn {
        background: rgba(15, 23, 42, 0.9);
        color: #ef4444;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .mosaic-fav-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transition: var(--transition);
        z-index: 10;
        cursor: pointer;
    }
    .mosaic-fav-btn:hover {
        transform: scale(1.1);
        background: #fff;
    }
    html.dark .mosaic-fav-btn {
        background: rgba(15, 23, 42, 0.9);
        color: #ef4444;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
</style>
@endsection

@section('content')
<div class="container pt-4 pb-0">
    
    <!-- Hero Banner Slideshow/Carousel Section -->
    <div id="heroCarousel" class="carousel slide hero-slideshow" data-bs-ride="carousel" data-bs-interval="3500" data-bs-touch="true">
        <div class="carousel-inner h-100">
            <!-- Slide 1: Group friends hangout -->
            <div class="carousel-item active h-100">
                <img src="{{ asset('images/hero_slide1.jpg') }}" class="slideshow-img parallax-bg" data-parallax-speed="0.25" alt="Indian friends group">
                <div class="slideshow-overlay"></div>
                <div class="slideshow-content">
                    <span class="hero-eyebrow">Trusted by 10,000+ people across India</span>
                    <h1 class="slideshow-title">Turn free time<br>into earnings</h1>
                    <h5 class="fw-medium text-white-50 mb-4">Become a companionship provider (CP) today!</h5>
                    <a href="{{ route('register') }}" class="btn btn-light magnetic-btn rounded-pill px-4 py-2 fw-bold text-theme-primary shadow-sm">Become a CP Now</a>
                </div>
            </div>
            <!-- Slide 2: Indian social event -->
            <div class="carousel-item h-100">
                <img src="{{ asset('images/hero_slide2.jpg') }}" class="slideshow-img parallax-bg" data-parallax-speed="0.25" alt="Indian social event">
                <div class="slideshow-overlay"></div>
                <div class="slideshow-content">
                    <span class="hero-eyebrow">Available in Indore, Bhopal, Jabalpur &amp; more</span>
                    <h1 class="slideshow-title">Find local friends<br>in Madhya Pradesh</h1>
                    <h5 class="fw-medium text-white-50 mb-4">Discover verified companions for dinners, events, or sports.</h5>
                    <a href="{{ route('companions.index') }}" class="btn btn-light magnetic-btn rounded-pill px-4 py-2 fw-bold text-theme-primary shadow-sm">Explore Companions</a>
                </div>
            </div>
            <!-- Slide 3: Travel group -->
            <div class="carousel-item h-100">
                <img src="{{ asset('images/hero_slide3.jpg') }}" class="slideshow-img parallax-bg" data-parallax-speed="0.25" alt="Indian travel group">
                <div class="slideshow-overlay"></div>
                <div class="slideshow-content">
                    <span class="hero-eyebrow">100% KYC verified companions</span>
                    <h1 class="slideshow-title">Travel, explore<br>&amp; make memories</h1>
                    <h5 class="fw-medium text-white-50 mb-4">Join treks, city tours, and adventures with local companions.</h5>
                    <a href="{{ route('companions.index') }}" class="btn btn-light magnetic-btn rounded-pill px-4 py-2 fw-bold text-theme-primary shadow-sm">Browse Travel CPs</a>
                </div>
            </div>
        </div>

        <!-- Custom slide indicators -->
        <div class="carousel-indicators hero-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="hero-dot active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" class="hero-dot" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" class="hero-dot" aria-label="Slide 3"></button>
        </div>

        <!-- Arrow controls -->
        <button class="hero-arrow hero-arrow-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="hero-arrow hero-arrow-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div> <!-- end of hero carousel -->

    @if(isset($showingNearbyFallback) && $showingNearbyFallback)
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center gap-3" role="alert" style="background-color: rgba(245, 158, 11, 0.1); border-left: 5px solid var(--warning-color) !important; color: var(--text-dark);">
            <div class="text-warning fs-3"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="small flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1" style="color: var(--warning-color);">No exact matches in your city</h6>
                We couldn't find active companions in <strong>{{ session('user_location.city') }}</strong> yet. Showing top/recommended companions nearby and across <strong>{{ session('user_location.state', 'Madhya Pradesh') }}</strong>.
            </div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close" style="top: auto; position: relative; padding: 0.5rem; filter: var(--bs-alert-btn-close-white, none);"></button>
        </div>
    @endif

    <!-- Section 1 & 3 Combined: Draggable Horizontal Companion Carousel -->
    <section class="section-spacing">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-nowrap">
            <h3 class="section-title mb-0 text-nowrap">Top Profiles</h3>
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="d-flex gap-1 gap-sm-2">
                    <button id="sliderPrev" class="slider-arrow-btn" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button id="sliderNext" class="slider-arrow-btn" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <a href="{{ route('companions.index') }}" class="text-decoration-none fw-semibold text-primary view-all-link text-nowrap" style="font-size: 0.95rem;">View All</a>
            </div>
        </div>

        <!-- Draggable Scroll Track -->
        <div class="companion-slider-wrapper">
            <div class="companion-slider" id="companionSlider">
                @forelse($topCompanions as $companion)
                    <div class="companion-slide">
                        <div class="hire-companion-card card-hover tilt-3d-card float-subtle skeleton-layer">
                            <!-- Image fold -->
                            <div class="card-img-container img-hover-container">
                                <button type="button" class="card-fav-btn {{ in_array($companion->id, $favIds) ? 'active' : '' }}" data-companion-id="{{ $companion->id }}" onclick="event.preventDefault(); toggleFavorite({{ $companion->id }}, this);" title="{{ in_array($companion->id, $favIds) ? 'Remove from favorites' : 'Add to favorites' }}">
                                    <i class="bi {{ in_array($companion->id, $favIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}" style="{{ in_array($companion->id, $favIds) ? 'color:#ef4444;' : '' }}"></i>
                                </button>
                                @if($companion->profile_picture)
                                    <img src="{{ $companion->profile_picture_url }}" class="w-100 h-100 object-fit-cover img-hover-zoom" alt="{{ $companion->name }}">
                                @else
                                    <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white fw-bold">CP</div>
                                @endif
                                <div class="card-overlay-details">
                                    <h5 class="card-overlay-title">{{ $companion->name }}, {{ 21 + ($companion->id % 10) }}</h5>
                                    <small class="text-white-50"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $companion->city->name ?? 'India' }}</small>
                                </div>
                                <span class="card-rating-badge">
                                    ⭐ {{ $companion->rating > 0 ? number_format($companion->rating, 1) : 'New' }}
                                </span>
                                <!-- Bio Hover Overlay -->
                                <div class="card-bio-overlay">
                                    <div class="bio-content">
                                        <h6 class="bio-title"><i class="bi bi-person-badge-fill me-1"></i> About Me</h6>
                                        <p class="bio-text">{{ $companion->bio ?? $companion->partnerProfile->bio ?? 'No bio description provided.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Details fold -->
                            <div class="p-3 d-flex flex-column flex-grow-1" style="background-color: var(--card-bg);">
                                <div class="card-stats-grid">
                                    <div class="row g-0">
                                        <div class="col-4 card-stat-col border-end border-light">
                                            <span class="card-stat-label">Meetups</span>
                                            <span class="card-stat-value">{{ $companion->bookingsAsPartner()->where('status', 'completed')->count() }}</span>
                                        </div>
                                        <div class="col-4 card-stat-col border-end border-light">
                                            <span class="card-stat-label">Age</span>
                                            <span class="card-stat-value">{{ 21 + ($companion->id % 10) }}y</span>
                                        </div>
                                        <div class="col-4 card-stat-col">
                                            <span class="card-stat-label">Score</span>
                                            <span class="card-stat-value">{{ $companion->rating > 0 ? (80 + ($companion->rating * 4)) . '%' : '0%' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('companions.show', $companion->id) }}" class="btn-black-cta mt-auto">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">No companion profiles live yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Dot indicators -->
        <div class="slider-dots mt-3" id="sliderDots">
            @foreach($topCompanions as $i => $companion)
                <span class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
            @endforeach
        </div>
    </section>

    <!-- Section 2: Full-Width Photo Mosaic Collage (All companions) -->
    <section class="section-spacing">
        <!-- Masonry collage grid -->
        <div class="photo-mosaic">
            {{-- Row 1: 3 photos tall-wide-tall --}}
            <div class="mosaic-row mosaic-row-1">
                @php $allCompanions = $mosaicCompanions; $idx = 0; @endphp
                @foreach($allCompanions->take(3) as $c)
                    <div class="mosaic-cell reveal-zoom">
                        <a href="{{ route('companions.show', $c->id) }}" class="mosaic-link img-hover-container">
                            <img src="{{ $c->profile_picture_url }}" alt="{{ $c->name }}" loading="lazy" class="img-hover-zoom">
                            <div class="mosaic-overlay">
                                <span class="mosaic-name">{{ $c->name }}</span>
                                <span class="mosaic-city"><i class="bi bi-geo-alt-fill"></i> {{ $c->city->name ?? '' }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            {{-- Row 2: 4 photos equal --}}
            <div class="mosaic-row mosaic-row-2">
                @foreach($allCompanions->skip(3)->take(4) as $c)
                    <div class="mosaic-cell reveal-zoom">
                        <a href="{{ route('companions.show', $c->id) }}" class="mosaic-link img-hover-container">
                            <img src="{{ $c->profile_picture_url }}" alt="{{ $c->name }}" loading="lazy" class="img-hover-zoom">
                            <div class="mosaic-overlay">
                                <span class="mosaic-name">{{ $c->name }}</span>
                                <span class="mosaic-city"><i class="bi bi-geo-alt-fill"></i> {{ $c->city->name ?? '' }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            {{-- Row 3: 5 photos equal smaller --}}
            <div class="mosaic-row mosaic-row-3">
                @foreach($allCompanions->skip(7)->take(5) as $c)
                    <div class="mosaic-cell reveal-zoom">
                        <a href="{{ route('companions.show', $c->id) }}" class="mosaic-link img-hover-container">
                            <img src="{{ $c->profile_picture_url }}" alt="{{ $c->name }}" loading="lazy" class="img-hover-zoom">
                            <div class="mosaic-overlay">
                                <span class="mosaic-name">{{ $c->name }}</span>
                                <span class="mosaic-city"><i class="bi bi-geo-alt-fill"></i> {{ $c->city->name ?? '' }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 3: Recommended for You — second draggable carousel -->
    <section class="section-spacing">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-nowrap">
            <h3 class="section-title mb-0 text-nowrap">Recommended for You</h3>
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <div class="d-flex gap-1 gap-sm-2">
                    <button id="recPrev" class="slider-arrow-btn" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
                    <button id="recNext" class="slider-arrow-btn" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
                </div>
                <a href="{{ route('companions.index') }}" class="text-decoration-none fw-semibold text-primary view-all-link text-nowrap" style="font-size: 0.95rem;">View All</a>
            </div>
        </div>

        <div class="companion-slider-wrapper">
            <div class="companion-slider" id="recSlider">
                @forelse($recommendedCompanions as $companion)
                    <div class="companion-slide">
                        <div class="hire-companion-card card-hover tilt-3d-card float-subtle skeleton-layer">
                            <div class="card-img-container img-hover-container">
                                <button type="button" class="card-fav-btn {{ in_array($companion->id, $favIds) ? 'active' : '' }}" data-companion-id="{{ $companion->id }}" onclick="event.preventDefault(); toggleFavorite({{ $companion->id }}, this);" title="{{ in_array($companion->id, $favIds) ? 'Remove from favorites' : 'Add to favorites' }}">
                                    <i class="bi {{ in_array($companion->id, $favIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}" style="{{ in_array($companion->id, $favIds) ? 'color:#ef4444;' : '' }}"></i>
                                </button>
                                @if($companion->profile_picture)
                                    <img src="{{ $companion->profile_picture_url }}" class="w-100 h-100 object-fit-cover img-hover-zoom" alt="{{ $companion->name }}">
                                @else
                                    <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white fw-bold">CP</div>
                                @endif
                                <div class="card-overlay-details">
                                    <h5 class="card-overlay-title">{{ $companion->name }}, {{ 21 + ($companion->id % 10) }}</h5>
                                    <small class="text-white-50"><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $companion->city->name ?? 'India' }}</small>
                                </div>
                                <span class="card-rating-badge">⭐ {{ $companion->rating > 0 ? number_format($companion->rating, 1) : 'New' }}</span>
                                <!-- Bio Hover Overlay -->
                                <div class="card-bio-overlay">
                                    <div class="bio-content">
                                        <h6 class="bio-title"><i class="bi bi-person-badge-fill me-1"></i> About Me</h6>
                                        <p class="bio-text">{{ $companion->bio ?? $companion->partnerProfile->bio ?? 'No bio description provided.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1" style="background-color: var(--card-bg);">
                                <div class="card-stats-grid">
                                    <div class="row g-0">
                                        <div class="col-4 card-stat-col border-end border-light">
                                            <span class="card-stat-label">Meetups</span>
                                            <span class="card-stat-value">{{ $companion->bookingsAsPartner()->where('status', 'completed')->count() }}</span>
                                        </div>
                                        <div class="col-4 card-stat-col border-end border-light">
                                            <span class="card-stat-label">Age</span>
                                            <span class="card-stat-value">{{ 21 + ($companion->id % 10) }}y</span>
                                        </div>
                                        <div class="col-4 card-stat-col">
                                            <span class="card-stat-label">Score</span>
                                            <span class="card-stat-value">{{ $companion->rating > 0 ? (80 + ($companion->rating * 4)) . '%' : '0%' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('companions.show', $companion->id) }}" class="btn-black-cta mt-auto">View Profile</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5 w-100">No more companions yet.</div>
                @endforelse
            </div>
        </div>
        <div class="slider-dots mt-3" id="recDots">
            @foreach($recommendedCompanions as $i => $companion)
                <span class="slider-dot {{ $i === 0 ? 'active' : '' }}"></span>
            @endforeach
        </div>
    </section>

    <!-- Section 5: Companionship Provider (CP) Workflow Steps -->
    <section class="section-spacing">
        <div class="how-it-works-section">
            <!-- Section heading -->
            <div class="text-center mb-5">
                <span class="d-inline-block mb-2 px-3 py-1 rounded-pill fw-bold text-uppercase"
                      style="background: rgba(37,99,235,0.09); color: #2563eb; font-size: 0.72rem; letter-spacing: 0.12em;">
                    How Does It Work?
                </span>
                <h2 class="section-title mt-2 mb-0">For Companionship Provider (CP)</h2>
            </div>

            <!-- 3 independent step cards -->
            <div class="row g-4 justify-content-center">

                <!-- Card 1 -->
                <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                    <div class="step-card reveal-up">
                        <div class="circle-icon-box" style="background: rgba(236,72,153,0.1); color: #ec4899;">
                            <span class="step-badge">01</span>
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <h5 class="step-card-title">Register &amp; Get Verified</h5>
                        <p class="step-card-desc">Sign up, complete identity verification, and set your availability &amp; service preferences.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                    <div class="step-card reveal-up">
                        <div class="circle-icon-box" style="background: rgba(20,184,166,0.1); color: #14b8a6;">
                            <span class="step-badge">02</span>
                            <i class="bi bi-chat-left-heart-fill"></i>
                        </div>
                        <h5 class="step-card-title">Accept Requests</h5>
                        <p class="step-card-desc">Review customer requests, confirm meetups, and communicate through the in-app chat.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-12 col-sm-10 col-md-6 col-lg-4">
                    <div class="step-card reveal-up">
                        <div class="circle-icon-box" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                            <span class="step-badge">03</span>
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h5 class="step-card-title">Meet &amp; Earn</h5>
                        <p class="step-card-desc">Provide the companionship service, ensure a great experience, and receive payment directly in your account.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Section 6: Why Choose Us Section with hearts mockup -->
    <section class="section-spacing">
        <div class="row align-items-center">
            <!-- Left Side Column -->
            <div class="col-md-7 mb-4 mb-md-0 text-center text-md-start">
                <h2 class="section-title display-5 fw-bold mb-4" style="font-size: 2.85rem;">Why Choose Us</h2>
                <div class="row g-4 mt-2">
                    <div class="col-sm-6">
                        <h6 class="fw-bold text-theme-primary mb-2"><i class="bi bi-shield-fill text-success me-2"></i>Safe and Secure</h6>
                        <p class="text-muted small">Verified profiles of companionship providers to build user safety and platform trust.</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold text-theme-primary mb-2"><i class="bi bi-heart-fill text-danger me-2"></i>No Pressure</h6>
                        <p class="text-muted small">We prioritize friendly companionship, casual guide meetups, and hobby sharing over dating pressure.</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold text-theme-primary mb-2"><i class="bi bi-emoji-smile-fill text-warning me-2"></i>Mental Well-being</h6>
                        <p class="text-muted small">Reduce social loneliness, isolate anxiety, and build positive, low-stress personal connections.</p>
                    </div>
                    <div class="col-sm-6">
                        <h6 class="fw-bold text-theme-primary mb-2"><i class="bi bi-headset text-primary me-2"></i>24/7 Support Channel</h6>
                        <p class="text-muted small">Platform coordinators are always online to resolve disputes, manage bookings, and handle secure payments.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side Column: Phone Mockup & animated floating hearts -->
            <div class="col-md-5 position-relative text-center d-none d-md-block">
                <div class="phone-mockup-container reveal-left">
                    <!-- Animated Floating Hearts -->
                    <span class="floating-heart" style="top: 20%; left: -20px; animation-delay: 0s;"><i class="bi bi-heart-fill"></i></span>
                    <span class="floating-heart text-danger" style="top: 50%; right: -25px; animation-delay: 1.5s; font-size: 1.2rem;"><i class="bi bi-heart-fill"></i></span>
                    <span class="floating-heart" style="bottom: 30%; left: -35px; animation-delay: 2.5s; font-size: 1.4rem;"><i class="bi bi-heart-fill"></i></span>
                    <span class="floating-heart text-danger" style="top: 10%; right: 40px; animation-delay: 0.8s; font-size: 1.1rem;"><i class="bi bi-heart-fill"></i></span>
                    <span class="floating-heart" style="top: 70%; left: 10px; animation-delay: 0.4s; font-size: 1.3rem; color: #ec4899;"><i class="bi bi-heart-fill"></i></span>
                    <span class="floating-heart" style="bottom: 10%; right: 15px; animation-delay: 2.1s; font-size: 1.5rem; color: #7c3aed;"><i class="bi bi-heart-fill"></i></span>
                    <span class="floating-heart text-danger" style="top: -5%; left: 40%; animation-delay: 1.2s; font-size: 1rem;"><i class="bi bi-heart-fill"></i></span>
                    <span class="floating-heart" style="bottom: -10%; left: 50%; animation-delay: 3s; font-size: 1.8rem; color: #f43f5e;"><i class="bi bi-heart-fill"></i></span>
                    
                    <!-- Phone Frame -->
                    <div class="phone-mockup-frame">
                        <div class="w-100 h-100 bg-theme-card d-flex flex-column align-items-center justify-content-center text-theme-primary p-4" style="background: var(--bg-color) !important;">
                            <div class="text-center d-flex flex-column align-items-center">
                                <div class="brand-logo-container mb-3" style="width: 48px; height: 48px; background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white !important; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <h4 class="fw-bold text-theme-primary mb-2">Hire-a-Friend</h4>
                                <p class="text-muted small px-3">Connect safely, share hobbies, and make memorable real-life companion connections today.</p>
                                <img src="{{ asset('images/register.png') }}" class="img-fluid rounded-4 shadow mt-3 mb-2" style="max-height: 180px; object-fit: cover;" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Explicitly initialize and cycle the hero carousel
    const heroEl = document.getElementById('heroCarousel');
    if (heroEl) {
        const carouselInstance = new bootstrap.Carousel(heroEl, {
            interval: 3500,
            ride: 'carousel',
            touch: true
        });
        carouselInstance.cycle();
    }

    const slider = document.getElementById('companionSlider');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    const dots = document.querySelectorAll('.slider-dot');
    if (!slider) return;

    const CARD_WIDTH = 280 + 20; // card + gap

    // ── Arrow buttons ──────────────────────────────────
    prevBtn && prevBtn.addEventListener('click', () => {
        slider.scrollBy({ left: -CARD_WIDTH, behavior: 'smooth' });
    });
    nextBtn && nextBtn.addEventListener('click', () => {
        slider.scrollBy({ left: CARD_WIDTH, behavior: 'smooth' });
    });

    // ── Update dot on scroll ───────────────────────────
    function updateDots() {
        let maxScroll = slider.scrollWidth - slider.clientWidth;
        let index = 0;
        if (maxScroll > 0) {
            let scrollPercentage = slider.scrollLeft / maxScroll;
            scrollPercentage = Math.max(0, Math.min(1, scrollPercentage));
            index = Math.round(scrollPercentage * (dots.length - 1));
        }
        dots.forEach((d, i) => d.classList.toggle('active', i === index));
        prevBtn && (prevBtn.disabled = slider.scrollLeft <= 0);
        nextBtn && (nextBtn.disabled = slider.scrollLeft >= maxScroll - 4);
    }
    slider.addEventListener('scroll', updateDots, { passive: true });
    updateDots();

    // ── Dot click navigation ───────────────────────────
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            let maxScroll = slider.scrollWidth - slider.clientWidth;
            if (maxScroll > 0) {
                let targetScroll = (i / (dots.length - 1)) * maxScroll;
                slider.scrollTo({ left: targetScroll, behavior: 'smooth' });
            }
        });
    });

    // ── Mouse drag-to-scroll ───────────────────────────
    let isDragging = false, startX = 0, scrollStart = 0;

    slider.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX - slider.offsetLeft;
        scrollStart = slider.scrollLeft;
        slider.style.cursor = 'grabbing';
        slider.style.scrollBehavior = 'auto';
    });
    document.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;
        slider.style.cursor = 'grab';
        slider.style.scrollBehavior = 'smooth';
        // Snap to nearest card
        const nearest = Math.round(slider.scrollLeft / CARD_WIDTH) * CARD_WIDTH;
        slider.scrollTo({ left: nearest, behavior: 'smooth' });
    });
    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        slider.scrollLeft = scrollStart - (x - startX);
    });

    // ── Prevent click on cards after drag ─────────────
    slider.addEventListener('click', (e) => {
        if (Math.abs(slider.scrollLeft - scrollStart) > 5) {
            e.stopPropagation();
            e.preventDefault();
        }
    }, true);
});
</script>

<script>
// ── Second slider: Recommended for You ─────────────────
document.addEventListener('DOMContentLoaded', function () {
    const recSlider = document.getElementById('recSlider');
    const recPrev   = document.getElementById('recPrev');
    const recNext   = document.getElementById('recNext');
    const recDots   = document.querySelectorAll('#recDots .slider-dot');
    if (!recSlider) return;

    const CW = 280 + 20; // card width + gap

    recPrev && recPrev.addEventListener('click', () => recSlider.scrollBy({ left: -CW, behavior: 'smooth' }));
    recNext && recNext.addEventListener('click', () => recSlider.scrollBy({ left:  CW, behavior: 'smooth' }));

    function syncDots() {
        let maxScroll = recSlider.scrollWidth - recSlider.clientWidth;
        let idx = 0;
        if (maxScroll > 0) {
            let scrollPercentage = recSlider.scrollLeft / maxScroll;
            scrollPercentage = Math.max(0, Math.min(1, scrollPercentage));
            idx = Math.round(scrollPercentage * (recDots.length - 1));
        }
        recDots.forEach((d, i) => d.classList.toggle('active', i === idx));
        recPrev && (recPrev.disabled = recSlider.scrollLeft <= 0);
        recNext && (recNext.disabled = recSlider.scrollLeft >= maxScroll - 4);
    }
    recSlider.addEventListener('scroll', syncDots, { passive: true });
    syncDots();

    recDots.forEach((dot, i) => dot.addEventListener('click', () => {
        let maxScroll = recSlider.scrollWidth - recSlider.clientWidth;
        if (maxScroll > 0) {
            let targetScroll = (i / (recDots.length - 1)) * maxScroll;
            recSlider.scrollTo({ left: targetScroll, behavior: 'smooth' });
        }
    }));

    let recDragging = false, recStartX = 0, recScrollStart = 0;
    recSlider.addEventListener('mousedown', (e) => {
        recDragging = true;
        recStartX = e.pageX - recSlider.offsetLeft;
        recScrollStart = recSlider.scrollLeft;
        recSlider.style.cursor = 'grabbing';
        recSlider.style.scrollBehavior = 'auto';
    });
    document.addEventListener('mouseup', () => {
        if (!recDragging) return;
        recDragging = false;
        recSlider.style.cursor = 'grab';
        recSlider.style.scrollBehavior = 'smooth';
        recSlider.scrollTo({ left: Math.round(recSlider.scrollLeft / CW) * CW, behavior: 'smooth' });
    });
    document.addEventListener('mousemove', (e) => {
        if (!recDragging) return;
        e.preventDefault();
        recSlider.scrollLeft = recScrollStart - (e.pageX - recSlider.offsetLeft - recStartX);
    });
});
</script>
@endsection
