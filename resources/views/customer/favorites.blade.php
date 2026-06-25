@extends('layouts.customer')
@section('title', 'Favorites | Hire-a-Friend')

@section('styles')
<style>
    /* Companion Card */
    .companion-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.25s;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
        position: relative;
    }
    .companion-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        color: inherit;
    }
    .companion-card-img {
        width: 100%; height: 220px;
        object-fit: cover;
        display: block;
    }
    .companion-card-body { padding: 1rem 1.1rem 1.1rem; }
    .companion-card-name { font-weight: 700; font-size: 1rem; color: var(--text-primary); margin-bottom: 2px; }
    .companion-card-city { font-size: 0.8rem; color: var(--text-muted); }
    .companion-card-price { font-weight: 700; font-size: 0.95rem; color: var(--brand-purple); }
    .companion-rating { font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); }
    
    .favorite-overlay-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--danger);
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: var(--transition);
        z-index: 10;
        cursor: pointer;
    }
    .favorite-overlay-btn:hover {
        transform: scale(1.1);
        background: #fff;
    }
    html.dark .favorite-overlay-btn {
        background: rgba(15, 23, 42, 0.85);
        color: #ef4444;
    }

    @media (max-width: 575.98px) {
        .companion-card-img {
            height: 130px !important;
        }
        .companion-card-body {
            padding: 0.75rem !important;
        }
        .companion-card-name {
            font-size: 0.85rem !important;
        }
        .companion-card-city {
            font-size: 0.72rem !important;
            margin-bottom: 0.25rem !important;
        }
        .companion-card-price, .companion-rating {
            font-size: 0.78rem !important;
        }
        .favorite-overlay-btn {
            width: 28px !important;
            height: 28px !important;
            font-size: 0.85rem !important;
            top: 8px !important;
            right: 8px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Favorites</h1>
        <p class="page-subtitle">Quickly access and book your preferred companion profiles</p>
    </div>
    <a href="{{ route('companions.index') }}" class="btn-brand">
        <i class="bi bi-search me-2"></i>Explore Companions
    </a>
</div>

<div class="card-glass-static p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold mb-0" style="color:var(--text-primary);">
            <i class="bi bi-heart-fill me-2 text-danger"></i>My Saved Companions
        </h5>
    </div>

    @if($companions->isEmpty())
        <div class="text-center py-5" style="color:var(--text-muted);">
            <i class="bi bi-heart d-block mb-3" style="font-size:3rem; color:var(--text-muted);"></i>
            <h5 class="fw-semibold">No favorites yet</h5>
            <p style="font-size:0.88rem; max-width: 360px; margin: 0 auto;" class="mb-4">Bookmark companion profiles during discovery to quickly access them here next time.</p>
            <a href="{{ route('companions.index') }}" class="btn btn-outline-brand px-4">
                Browse Companions
            </a>
        </div>
    @else
        <div class="row g-3 g-md-4">
            @foreach($companions as $c)
                <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                    <div class="companion-card h-100 d-flex flex-column justify-content-between">
                        <div style="position:relative;">
                            <!-- Favorite Button Overlay -->
                            <button type="button" class="favorite-overlay-btn active" data-companion-id="{{ $c->id }}" onclick="event.preventDefault(); toggleFavorite({{ $c->id }}, this);" title="Remove from favorites">
                                <i class="bi bi-heart-fill text-danger" style="color:#ef4444;"></i>
                            </button>
                            
                            <a href="{{ route('companions.show', $c->id) }}" class="text-decoration-none text-reset d-block">
                                @if($c->profile_picture)
                                    <img src="{{ $c->profile_picture_url }}" class="companion-card-img" alt="{{ $c->name }}">
                                @else
                                    <div class="companion-card-img d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#7c3aed,#ec4899);">
                                        <span style="font-size:3.5rem;font-weight:800;color:#fff;">{{ strtoupper(substr($c->name,0,1)) }}</span>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <a href="{{ route('companions.show', $c->id) }}" class="text-decoration-none text-reset d-block flex-grow-1">
                            <div class="companion-card-body pb-0">
                                <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
                                    <span class="companion-card-name text-truncate" style="max-width:110px;">{{ $c->name }}</span>
                                    @if($c->partnerProfile?->rating >= 4.5)
                                        <i class="bi bi-patch-check-fill" style="color:#7c3aed;font-size:0.85rem;" title="Top Rated"></i>
                                    @endif
                                </div>
                                <div class="companion-card-city mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $c->city->name ?? 'India' }}
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="companion-card-price text-nowrap">
                                        ₹{{ number_format($c->partnerProfile->hourly_rate ?? 0) }}/hr
                                    </div>
                                    @if($c->partnerProfile?->rating)
                                        <div class="companion-rating text-nowrap">
                                            <i class="bi bi-star-fill" style="color:#f59e0b;"></i> {{ number_format($c->partnerProfile->rating,1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                        
                        <div class="px-3 pb-3">
                            <a href="{{ route('companions.show', $c->id) }}#book" class="btn btn-sm btn-brand w-100 py-1.5" style="border-radius: 8px; font-size: 0.8rem;">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
