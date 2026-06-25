@extends('layouts.customer')
@section('title', 'Discover Companions | Hire-a-Friend')

@php
    $favIds = auth()->check() ? auth()->user()->favorites()->pluck('users.id')->toArray() : [];
@endphp

@section('styles')
<style>
    .filter-panel { background:var(--surface); border:1px solid var(--border-light); border-radius:16px; padding:1.5rem; position:sticky; top:80px; }
    .filter-section-title { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:0.75rem; margin-top:1.25rem; }
    .filter-section-title:first-child { margin-top:0; }
    .filter-chip { display:inline-flex; align-items:center; gap:6px; padding:0.4rem 0.85rem; border-radius:99px; border:1.5px solid var(--border); background:var(--surface-2); color:var(--text-secondary); font-size:0.82rem; font-weight:600; cursor:pointer; transition:all 0.2s; }
    .filter-chip:hover, .filter-chip.active { background:var(--brand-gradient); color:#fff; border-color:transparent; box-shadow:0 4px 12px rgba(124,58,237,0.3); }
    .range-row { display:flex; align-items:center; gap:0.5rem; }
    .range-input { flex:1; -webkit-appearance:none; height:4px; border-radius:99px; background:linear-gradient(to right,#7c3aed 0%,#7c3aed var(--val,50%),var(--border) var(--val,50%),var(--border) 100%); outline:none; cursor:pointer; }
    .range-input::-webkit-slider-thumb { -webkit-appearance:none; width:18px; height:18px; border-radius:50%; background:#7c3aed; box-shadow:0 2px 8px rgba(124,58,237,0.4); }

    .c-card { background:var(--surface); border:1px solid var(--border-light); border-radius:16px; overflow:hidden; box-shadow:var(--card-shadow); transition:all 0.25s; }
    .c-card:hover { transform:translateY(-5px); box-shadow:var(--card-shadow-hover); }
    .c-card-img { width:100%; height:220px; object-fit:cover; display:block; position:relative; }
    .c-card-img-wrap { position:relative; overflow:hidden; }
    .c-card-img-wrap img { transition:transform 0.4s; }
    .c-card:hover .c-card-img-wrap img { transform:scale(1.05); }
    .c-card-badge { position:absolute; top:10px; left:10px; font-size:0.72rem; font-weight:700; padding:4px 10px; border-radius:99px; }
    .c-card-fav { position:absolute; top:10px; right:10px; width:34px; height:34px; border-radius:50%; background:rgba(255,255,255,0.85); border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; backdrop-filter:blur(4px); }
    .c-card-fav:hover { background:#fff; transform:scale(1.1); }
    .c-card-fav.active i { color:#ef4444; }
    .c-card-body { padding:1.1rem; }
    .c-card-name { font-weight:700; font-size:1rem; color:var(--text-primary); margin-bottom:2px; display:flex; align-items:center; gap:5px; }
    .c-card-sub { font-size:0.8rem; color:var(--text-muted); margin-bottom:0.65rem; }
    .c-card-price { font-weight:800; font-size:1rem; color:var(--brand-purple); }
    .c-card-rating { font-size:0.8rem; font-weight:600; color:var(--text-secondary); display:flex; align-items:center; gap:3px; }
    .c-card-tags { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:0.75rem; }
    .c-card-tag { font-size:0.7rem; font-weight:600; padding:2px 8px; border-radius:99px; background:rgba(124,58,237,0.08); color:var(--brand-purple); }
    .online-dot { width:9px; height:9px; background:#10b981; border-radius:50%; display:inline-block; border:2px solid var(--surface); }
    .sort-bar { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; }
    .view-toggle { display:flex; border:1.5px solid var(--border); border-radius:10px; overflow:hidden; }
    .view-toggle-btn { padding:0.4rem 0.75rem; background:transparent; border:none; color:var(--text-muted); cursor:pointer; transition:all 0.2s; }
    .view-toggle-btn.active { background:var(--brand-gradient); color:#fff; }
    .list-companion { display:flex; align-items:center; gap:1.25rem; background:var(--surface); border:1px solid var(--border-light); border-radius:16px; padding:1.1rem 1.25rem; box-shadow:var(--card-shadow); transition:all 0.25s; text-decoration:none; color:inherit; }
    .list-companion:hover { transform:translateY(-2px); box-shadow:var(--card-shadow-hover); color:inherit; }
    .list-companion img { width:80px; height:80px; border-radius:14px; object-fit:cover; flex-shrink:0; }
    .results-count { font-size:0.88rem; color:var(--text-muted); font-weight:500; }

    /* Custom Modern Pagination */
    .pagination-custom .page-item .page-link {
        color: var(--text-secondary);
        background-color: var(--surface);
        border: 1px solid var(--border);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pagination-custom .page-item.active .page-link {
        background-color: var(--brand-purple);
        border-color: var(--brand-purple);
        color: #ffffff;
        box-shadow: 0 4px 10px var(--brand-glow);
    }
    .pagination-custom .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: var(--surface-2);
        border-color: var(--brand-purple);
        color: var(--brand-purple);
        transform: translateY(-1px);
    }
    .pagination-custom .page-item.disabled .page-link {
        color: var(--text-muted);
        opacity: 0.5;
        background-color: var(--bg);
        border-color: var(--border);
    }
    .pagination-custom .page-link.rounded-circle {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 50% !important;
    }
</style>
@endsection

@section('content')

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

<div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">Discover Companions</h1>
        <p class="page-subtitle">Find verified companions for social meetups, café visits, coworking, city walks & more</p>
    </div>
    <a href="{{ route('companions.index') }}" class="btn-brand px-4 py-2" style="border-radius:12px;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-search-heart"></i> Find Companions
    </a>
</div>

<div class="row g-4">
    <!-- COMPANION GRID -->
    <div class="col-12">
        <div class="sort-bar">
            <span class="results-count">Showing <strong>{{ $companions->total() }}</strong> companions</span>
            <div class="d-flex align-items-center gap-3">
                <select class="form-select form-select-sm" style="width:auto;border-radius:10px;font-size:0.85rem;">
                    <option>Sort: Recommended</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Rating: Highest</option>
                    <option>Newest First</option>
                </select>
                <div class="view-toggle">
                    <button class="view-toggle-btn active" id="gridBtn" onclick="setView('grid')"><i class="bi bi-grid-3x3-gap"></i></button>
                    <button class="view-toggle-btn" id="listBtn" onclick="setView('list')"><i class="bi bi-list-ul"></i></button>
                </div>
            </div>
        </div>

        <!-- GRID VIEW -->
        <div id="gridView" class="row g-3">
            @forelse($companions as $c)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('companions.show', $c->id) }}" class="c-card d-block text-decoration-none reveal-up skeleton-layer">
                    <div class="c-card-img-wrap">
                        @if($c->profile_picture)
                            <img src="{{ $c->profile_picture_url }}" class="c-card-img" alt="{{ $c->name }}">
                        @else
                            <div class="c-card-img d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#7c3aed,#ec4899);">
                                <span style="font-size:3.5rem;font-weight:800;color:rgba(255,255,255,0.8);">{{ strtoupper(substr($c->name,0,1)) }}</span>
                            </div>
                        @endif
                        @if($c->partnerProfile?->rating >= 4.5)
                            <span class="c-card-badge" style="background:rgba(16,185,129,0.9);color:#fff;"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                        @endif
                        <button type="button" class="c-card-fav {{ in_array($c->id, $favIds) ? 'active' : '' }}" data-companion-id="{{ $c->id }}" onclick="event.preventDefault(); toggleFavorite({{ $c->id }}, this);" title="{{ in_array($c->id, $favIds) ? 'Remove from favorites' : 'Add to favorites' }}">
                            <i class="bi {{ in_array($c->id, $favIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}" style="font-size:0.9rem;{{ in_array($c->id, $favIds) ? 'color:#ef4444;' : '' }}"></i>
                        </button>
                    </div>
                    <div class="c-card-body">
                        <div class="c-card-name">{{ $c->name }}</div>
                        <div class="c-card-sub"><i class="bi bi-geo-alt me-1"></i>{{ $c->city?->name ?? 'India' }}</div>
                        
                        <div class="c-card-tags">
                            @foreach($c->services->take(2) as $service)
                                <span class="c-card-tag">{{ $service->name }}</span>
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <div class="c-card-price">₹{{ number_format($c->partnerProfile->hourly_rate ?? 0) }}/hr</div>
                            @if($c->partnerProfile?->rating)
                                <div class="c-card-rating">
                                    <i class="bi bi-star-fill" style="color:#f59e0b;font-size:0.8rem;"></i>
                                    {{ number_format($c->partnerProfile->rating, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-people d-block fs-1 mb-2"></i>
                No companion partners found matching the filter criteria.
            </div>
            @endforelse
        </div>

        <!-- LIST VIEW -->
        <div id="listView" class="d-none">
            <div class="d-flex flex-column gap-3">
                @forelse($companions as $c)
                <a href="{{ route('companions.show', $c->id) }}" class="list-companion reveal-up skeleton-layer">
                    @if($c->profile_picture)
                        <img src="{{ $c->profile_picture_url }}" alt="{{ $c->name }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center" style="width:80px;height:80px;border-radius:14px;background:linear-gradient(135deg,#7c3aed,#ec4899);color:#fff;font-weight:bold;font-size:1.5rem;flex-shrink:0;">
                            {{ strtoupper(substr($c->name,0,1)) }}
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold" style="color:var(--text-primary);font-size:1rem;">{{ $c->name }}</span>
                            @if($c->partnerProfile?->rating >= 4.5)
                                <i class="bi bi-patch-check-fill" style="color:#7c3aed;"></i>
                            @endif
                        </div>
                        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:6px;"><i class="bi bi-geo-alt me-1"></i>{{ $c->city?->name ?? 'India' }}</div>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($c->services->take(3) as $service)
                                <span class="c-card-tag">{{ $service->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold" style="color:var(--brand-purple);font-size:1.05rem;">₹{{ number_format($c->partnerProfile->hourly_rate ?? 0) }}/hr</div>
                        @if($c->partnerProfile?->rating)
                            <div style="font-size:0.82rem;color:var(--text-muted);"><i class="bi bi-star-fill" style="color:#f59e0b;"></i> {{ number_format($c->partnerProfile->rating, 1) }}</div>
                        @endif
                        <button class="btn-brand mt-2 py-1 px-3" style="border-radius:8px;border:none;font-size:0.8rem;cursor:pointer;">Book Now</button>
                    </div>
                </a>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-people d-block fs-1 mb-2"></i>
                    No companion partners found matching the filter criteria.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($companions->hasPages())
            <nav class="d-flex justify-content-center mt-5" aria-label="Table navigation">
                <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                    {{-- Previous Page Link --}}
                    @if ($companions->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link rounded-pill px-3" href="{{ $companions->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                        </li>
                    @endif

                    {{-- Pagination Pages --}}
                    @foreach ($companions->getUrlRange(max(1, $companions->currentPage() - 2), min($companions->lastPage(), $companions->currentPage() + 2)) as $page => $url)
                        @if ($page == $companions->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-circle">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-circle" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($companions->hasMorePages())
                        <li class="page-item">
                            <a class="page-link rounded-pill px-3" href="{{ $companions->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link rounded-pill px-3">Next<i class="bi bi-chevron-right ms-1"></i></span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function setView(v) {
    if(v==='grid') {
        document.getElementById('gridView').classList.remove('d-none');
        document.getElementById('listView').classList.add('d-none');
        document.getElementById('gridBtn').classList.add('active');
        document.getElementById('listBtn').classList.remove('active');
    } else {
        document.getElementById('gridView').classList.add('d-none');
        document.getElementById('listView').classList.remove('d-none');
        document.getElementById('listBtn').classList.add('active');
        document.getElementById('gridBtn').classList.remove('active');
    }
}
</script>
@endsection
