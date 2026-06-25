@extends('layouts.customer')
@section('title', 'Dashboard | Hire-a-Friend')

@section('styles')
<style>
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        right: -80px; top: -80px;
        width: 280px; height: 280px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        right: 80px; bottom: -60px;
        width: 180px; height: 180px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .welcome-avatar {
        width: 64px; height: 64px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.5);
        object-fit: cover;
    }
    .welcome-avatar-placeholder {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 3px solid rgba(255,255,255,0.5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800; color: #fff;
    }

    /* Quick Action Buttons */
    .quick-action {
        display: flex; flex-direction: column; align-items: center; gap: 0.6rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    .quick-action-icon {
        width: 52px; height: 52px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        transition: all 0.2s;
    }
    .quick-action:hover .quick-action-icon { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
    .quick-action span { font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.9); }

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
    .favorite-overlay-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ef4444;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        transition: var(--transition);
        z-index: 10;
        cursor: pointer;
    }
    .favorite-overlay-btn:hover {
        transform: scale(1.1);
        background: #fff;
    }
    html.dark .favorite-overlay-btn {
        background: rgba(15, 23, 42, 0.9);
        color: #ef4444;
    }
    .companion-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        color: inherit;
    }
    .companion-card-img {
        width: 100%; height: 200px;
        object-fit: cover;
        display: block;
    }
    .companion-card-body { padding: 1rem 1.1rem 1.1rem; }
    .companion-card-name { font-weight: 700; font-size: 1rem; color: var(--text-primary); margin-bottom: 2px; }
    .companion-card-city { font-size: 0.8rem; color: var(--text-muted); }
    .companion-card-price { font-weight: 700; font-size: 0.95rem; color: var(--brand-purple); }
    .companion-verified { display: inline-flex; align-items: center; gap: 3px; font-size: 0.72rem; font-weight: 600; color: #059669; background: rgba(16,185,129,0.1); padding: 2px 8px; border-radius: 99px; }
    .companion-online { display: inline-block; width: 8px; height: 8px; background: #10b981; border-radius: 50%; border: 2px solid var(--surface); }
    .companion-rating { font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); }

    /* Section Header */
    .section-hd { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
    .section-hd h5 { font-weight: 800; font-size: 1.1rem; color: var(--text-primary); margin: 0; }
    .section-hd a { font-size: 0.85rem; font-weight: 600; color: var(--brand-purple); text-decoration: none; }
    .section-hd a:hover { text-decoration: underline; }

    /* Upcoming Booking Card */
    .upcoming-card {
        background: linear-gradient(135deg, rgba(124,58,237,0.06) 0%, rgba(236,72,153,0.04) 100%);
        border: 1.5px solid rgba(124,58,237,0.15);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
    }

    /* Activity Item */
    .activity-item {
        display: flex; align-items: flex-start; gap: 0.85rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid var(--border-light);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon {
        width: 38px; height: 38px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }

    /* Wallet Summary */
    .wallet-mini {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    html.dark .wallet-mini {
        background: linear-gradient(135deg, #312e81 0%, #1e1b4b 100%);
    }
    .wallet-mini::before {
        content: '';
        position: absolute;
        right: -30px; top: -30px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* Stats */
    .stat-card-dash {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: 16px;
        padding: 1rem 0.85rem;
        box-shadow: var(--card-shadow);
        transition: all 0.25s;
        display: flex; align-items: center; gap: 0.6rem;
        overflow: hidden;
    }
    .stat-card-dash:hover { transform: translateY(-3px); box-shadow: var(--card-shadow-hover); }
    .stat-card-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
    .stat-card-val { font-size: 1.4rem; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 3px; }
    .stat-card-lbl { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; line-height: 1.1; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; }

    /* Welcome Banner Responsive Fixes */
    @media (max-width: 767.98px) {
        .welcome-banner {
            padding: 1.5rem 1.25rem !important;
        }
        .welcome-avatar, .welcome-avatar-placeholder {
            width: 50px !important;
            height: 50px !important;
            font-size: 1.2rem !important;
        }
        .welcome-banner h2 {
            font-size: 1.25rem !important;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
        }
    }

    /* Quick Actions Responsive Layout */
    .quick-actions-wrapper {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    @media (min-width: 768px) and (max-width: 1199.98px) {
        .quick-actions-wrapper {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            width: 100%;
            margin-top: 1rem;
        }
        .quick-action {
            width: 100%;
        }
    }
    @media (max-width: 767.98px) {
        .quick-actions-wrapper {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            width: 100%;
            margin-top: 1rem;
        }
        .quick-action {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem 0.5rem;
            border-radius: 12px;
            text-align: center;
            align-items: center;
            justify-content: center;
        }
        .quick-action-icon {
            margin: 0 auto;
        }
    }

    /* Stats Card Responsive Grid */
    @media (max-width: 767.98px) {
        .stat-card-dash {
            padding: 0.85rem !important;
            gap: 0.5rem !important;
            flex-direction: column !important;
            text-align: center !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .stat-card-icon {
            width: 40px !important;
            height: 40px !important;
            font-size: 1.15rem !important;
            margin: 0 auto;
        }
        .stat-card-val {
            font-size: 1.35rem !important;
        }
        .stat-card-lbl {
            font-size: 0.75rem !important;
        }
    }

    /* Recommended Companion Card Styles on Mobile */
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

    /* Upcoming Card Mobile Tweaks */
    @media (max-width: 575.98px) {
        .upcoming-card {
            padding: 1rem !important;
        }
        .upcoming-card .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.85rem !important;
        }
        .upcoming-card .text-end {
            text-align: left !important;
            width: 100% !important;
            border-top: 1px solid var(--border-light);
            padding-top: 0.75rem;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }
        .upcoming-card .text-end form {
            margin-top: 0 !important;
        }
    }

    /* Mobile Booking List Card Styles */
    .mobile-booking-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        padding: 1rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
@php
    $upcomingBooking = $bookings->where('status','approved')->sortBy('booking_date')->first()
                   ?? $bookings->where('status','pending')->sortBy('booking_date')->first();

    $companions = \App\Models\User::where('role','partner')
        ->where('is_active', true)
        ->whereHas('partnerProfile', fn($q) => $q->where('kyc_status','approved'))
        ->with(['partnerProfile','city'])
        ->inRandomOrder()->limit(8)->get();

    $favIds = auth()->check() ? auth()->user()->favorites()->pluck('users.id')->toArray() : [];
@endphp

<!-- ══ WELCOME BANNER ═════════════════════════════════ -->
<div class="welcome-banner mb-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3" style="position:relative;z-index:2;">
        <div class="d-flex align-items-center gap-3">
            @if($user->profile_picture)
                <img src="{{ $user->profile_picture_url }}" class="welcome-avatar" alt="">
            @else
                <div class="welcome-avatar-placeholder">{{ strtoupper(substr($user->name,0,1)) }}</div>
            @endif
            <div>
                <div style="font-size:0.8rem;opacity:0.8;font-weight:500;">Welcome back 👋</div>
                <h2 style="font-weight:800;font-size:1.5rem;margin:2px 0 4px;letter-spacing:-0.5px;">{{ $user->name }}</h2>
                <div style="font-size:0.82rem;opacity:0.75;">
                    <i class="bi bi-geo-alt me-1"></i>{{ $user->city->name ?? 'Set your city' }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('D, d M Y') }}
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions-wrapper">
            <a href="{{ route('companions.index') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-search-heart text-white"></i>
                </div>
                <span>Find Companion</span>
            </a>
            <a href="{{ route('companions.index') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-calendar-plus text-white"></i>
                </div>
                <span>Book Now</span>
            </a>
            <a href="{{ route('customer.messages') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-chat-dots text-white"></i>
                </div>
                <span>Open Chat</span>
            </a>
            <a href="{{ route('customer.wallet') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-wallet-fill text-white"></i>
                </div>
                <span>Add Money</span>
            </a>
        </div>
    </div>
</div>

<!-- ══ STATS GRID ═════════════════════════════════════ -->
<div class="row g-3 mt-4">
    <!-- Total Bookings -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card-dash h-100 reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(124,58,237,0.1);">
                <i class="bi bi-calendar-check" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate">{{ $stats['total_bookings'] }}</div>
                <div class="stat-card-lbl">Total Bookings</div>
            </div>
        </div>
    </div>
    <!-- Completed -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card-dash h-100 reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(16,185,129,0.1);">
                <i class="bi bi-patch-check" style="color:#059669;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate">{{ $stats['completed_bookings'] }}</div>
                <div class="stat-card-lbl">Completed</div>
            </div>
        </div>
    </div>
    <!-- Pending -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card-dash h-100 reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(245,158,11,0.1);">
                <i class="bi bi-clock-history" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate">{{ $stats['pending_bookings'] }}</div>
                <div class="stat-card-lbl">Pending</div>
            </div>
        </div>
    </div>
    <!-- Wallet -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card-dash h-100 reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(6,182,212,0.1);">
                <i class="bi bi-wallet2" style="color:#0891b2;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate" data-prefix="₹">0</div>
                <div class="stat-card-lbl">Wallet</div>
            </div>
        </div>
    </div>
    <!-- Reviews -->
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('customer.reviews') }}" class="text-decoration-none text-reset d-block h-100">
            <div class="stat-card-dash h-100 reveal-up dashboard-card-hover tilt-3d-card">
                <div class="stat-card-icon" style="background:rgba(245,158,11,0.1);">
                    <i class="bi bi-star" style="color:#f59e0b;"></i>
                </div>
                <div>
                    <div class="stat-card-val counter-animate">{{ $stats['reviews_count'] ?? 0 }}</div>
                    <div class="stat-card-lbl">Reviews</div>
                </div>
            </div>
        </a>
    </div>
    <!-- Favorites -->
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('customer.favorites') }}" class="text-decoration-none text-reset d-block h-100">
            <div class="stat-card-dash h-100 reveal-up dashboard-card-hover tilt-3d-card">
                <div class="stat-card-icon" style="background:rgba(239,68,68,0.1);">
                    <i class="bi bi-heart" style="color:#ef4444;"></i>
                </div>
                <div>
                    <div class="stat-card-val counter-animate">{{ $stats['favorites_count'] ?? 0 }}</div>
                    <div class="stat-card-lbl">Favorites</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ══ MAIN GRID ══════════════════════════════════════ -->
<div class="row g-4 mt-1">

    <!-- LEFT COLUMN -->
    <div class="col-lg-8">

        <!-- Upcoming Booking -->
        @if($upcomingBooking)
        <div class="card-glass-static mb-4">
            <div class="p-4">
                <div class="section-hd">
                    <h5><i class="bi bi-calendar-event me-2" style="color:var(--brand-purple);"></i>Upcoming Session</h5>
                    <span class="booking-badge badge-{{ $upcomingBooking->status }}">{{ ucfirst($upcomingBooking->status) }}</span>
                </div>
                <div class="upcoming-card dashboard-card-hover tilt-3d-card">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        @if($upcomingBooking->partner->profile_picture)
                            <img src="{{ $upcomingBooking->partner->profile_picture_url }}" class="avatar" style="width:56px;height:56px;" alt="">
                        @else
                            <div class="avatar-placeholder" style="width:56px;height:56px;font-size:1.4rem;">
                                {{ strtoupper(substr($upcomingBooking->partner->name,0,1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="color:var(--text-primary);font-size:1.05rem;">{{ $upcomingBooking->partner->name }}</div>
                            <div style="font-size:0.85rem;color:var(--text-muted);">
                                <i class="bi bi-calendar3 me-1"></i>{{ $upcomingBooking->booking_date->format('D, d M Y') }}
                                &nbsp;·&nbsp;
                                <i class="bi bi-clock me-1"></i>{{ date('h:i A', strtotime($upcomingBooking->start_time)) }}
                                &nbsp;·&nbsp;
                                {{ $upcomingBooking->duration_hours }}h session
                            </div>
                            <div style="font-size:0.82rem;color:var(--text-muted);margin-top:2px;">
                                <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $upcomingBooking->location_address }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" style="font-size:1.2rem;color:var(--brand-purple);">₹{{ number_format($upcomingBooking->total_amount) }}</div>
                            @if($upcomingBooking->status === 'pending')
                                <form action="{{ route('customer.booking.cancel', $upcomingBooking->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:0.8rem;">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Bookings -->
        <div class="card-glass-static mb-4">
            <div class="p-4 pb-0">
                <div class="section-hd">
                    <h5><i class="bi bi-list-check me-2" style="color:var(--brand-purple);"></i>Recent Bookings</h5>
                    <a href="{{ route('customer.dashboard') }}?tab=bookings">View all</a>
                </div>
            </div>
            <!-- Desktop Layout -->
            <div class="table-responsive d-none d-md-block">
                <table class="c-table">
                    <thead>
                        <tr>
                            <th>Companion</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings->take(5) as $b)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($b->partner->profile_picture)
                                        <img src="{{ $b->partner->profile_picture_url }}" class="avatar" style="width:34px;height:34px;" alt="">
                                    @else
                                        <div class="avatar-placeholder" style="width:34px;height:34px;font-size:0.85rem;">{{ strtoupper(substr($b->partner->name,0,1)) }}</div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">{{ $b->partner->name }}</div>
                                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $b->partner->city->name ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:0.88rem;color:var(--text-secondary);">{{ $b->booking_date->format('d M Y') }}</td>
                            <td style="font-size:0.88rem;color:var(--text-secondary);">{{ $b->duration_hours }}h</td>
                            <td><span class="fw-bold" style="color:var(--brand-purple);">₹{{ number_format($b->total_amount) }}</span></td>
                            <td><span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                            <td>
                                @if(in_array($b->status,['pending','approved']))
                                    <form action="{{ route('customer.booking.cancel', $b->id) }}" method="POST" onsubmit="return confirm('Cancel?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="border-radius:8px;font-size:0.75rem;color:var(--danger);border:1px solid var(--danger);padding:3px 10px;">Cancel</button>
                                    </form>
                                @elseif($b->status === 'completed' && !$b->review)
                                    <button type="button" class="btn btn-sm btn-brand" style="font-size:0.75rem;padding:4px 12px;" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $b->id }}">Review</button>
                                @else
                                    <span style="font-size:0.78rem;color:var(--text-muted);">—</span>
                                @endif
                            </td>
                        </tr>

                        @if($b->status === 'completed' && !$b->review)
                        <!-- Review Modal -->
                        <div class="modal fade" id="reviewModal{{ $b->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);border-radius:20px;">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold" style="color:var(--text-primary);">Rate {{ $b->partner->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('customer.booking.review', $b->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <label class="form-label">Rating</label>
                                            <div class="d-flex gap-2 mb-3" id="stars{{ $b->id }}">
                                                @for($s=1;$s<=5;$s++)
                                                    <i class="bi bi-star fs-4" style="color:#f59e0b;cursor:pointer;" data-star="{{ $s }}" onclick="setRating({{ $b->id }},{{ $s }})"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="ratingInput{{ $b->id }}" value="5">
                                            <label class="form-label">Your Feedback</label>
                                            <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience..."></textarea>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color:var(--text-muted);">
                                <i class="bi bi-calendar-x d-block fs-2 mb-2"></i>
                                No bookings yet. <a href="{{ route('companions.index') }}" style="color:var(--brand-purple);">Find a companion</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Layout -->
            <div class="d-md-none p-3 pt-0">
                @forelse($bookings->take(5) as $b)
                <div class="mobile-booking-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            @if($b->partner->profile_picture)
                                <img src="{{ $b->partner->profile_picture_url }}" class="avatar" style="width:34px;height:34px;" alt="">
                            @else
                                <div class="avatar-placeholder" style="width:34px;height:34px;font-size:0.85rem;">{{ strtoupper(substr($b->partner->name,0,1)) }}</div>
                            @endif
                            <div>
                                <div class="fw-semibold text-theme-primary" style="font-size:0.9rem;">{{ $b->partner->name }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $b->partner->city->name ?? '' }}</div>
                            </div>
                        </div>
                        <span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                    </div>
                    
                    <div class="py-2 border-top border-bottom my-2" style="font-size:0.8rem; color:var(--text-secondary);">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Date:</span>
                            <span class="fw-medium text-theme-primary">{{ $b->booking_date->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Duration:</span>
                            <span>{{ $b->duration_hours }}h</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Amount:</span>
                            <span class="fw-bold" style="color:var(--brand-purple);">₹{{ number_format($b->total_amount) }}</span>
                        </div>
                    </div>
                    
                    @if(in_array($b->status,['pending','approved']))
                        <form action="{{ route('customer.booking.cancel', $b->id) }}" method="POST" onsubmit="return confirm('Cancel?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 py-2" style="border-radius:8px;font-size:0.8rem;">Cancel Booking</button>
                        </form>
                    @elseif($b->status === 'completed' && !$b->review)
                        <button type="button" class="btn btn-brand w-100 py-2" style="font-size:0.8rem;" data-bs-toggle="modal" data-bs-target="#reviewModalMobile{{ $b->id }}">Write Review</button>
                        
                        <!-- Review Modal Mobile -->
                        <div class="modal fade" id="reviewModalMobile{{ $b->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);border-radius:20px;">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold" style="color:var(--text-primary);">Rate {{ $b->partner->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('customer.booking.review', $b->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <label class="form-label">Rating</label>
                                            <div class="d-flex gap-2 mb-3" id="starsMobile{{ $b->id }}">
                                                @for($s=1;$s<=5;$s++)
                                                    <i class="bi bi-star fs-4" style="color:#f59e0b;cursor:pointer;" data-star="{{ $s }}" onclick="setRatingMobile({{ $b->id }},{{ $s }})"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="ratingInputMobile{{ $b->id }}" value="5">
                                            <label class="form-label">Your Feedback</label>
                                            <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience..."></textarea>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-calendar-x d-block fs-3 mb-2"></i>
                    No bookings yet. <a href="{{ route('companions.index') }}" style="color:var(--brand-purple);">Find a companion</a>
                </div>
                @endforelse
            </div>
        </div>
        </div>

        <!-- Recommended Companions -->
        <div class="card-glass-static p-4">
            <div class="section-hd">
                <h5><i class="bi bi-stars me-2" style="color:var(--brand-purple);"></i>Recommended For You</h5>
                <a href="{{ route('companions.index') }}">Explore all</a>
            </div>
            <div class="row g-3">
                @forelse($companions->take(4) as $c)
                <div class="col-6 col-md-3">
                    <div class="companion-card h-100 d-flex flex-column justify-content-between">
                        <div style="position:relative;">
                            <!-- Favorite Button Overlay -->
                            <button type="button" class="favorite-overlay-btn {{ in_array($c->id, $favIds) ? 'active' : '' }}" data-companion-id="{{ $c->id }}" onclick="event.preventDefault(); toggleFavorite({{ $c->id }}, this);" title="{{ in_array($c->id, $favIds) ? 'Remove from favorites' : 'Add to favorites' }}">
                                <i class="bi {{ in_array($c->id, $favIds) ? 'bi-heart-fill text-danger' : 'bi-heart' }}" style="{{ in_array($c->id, $favIds) ? 'color:#ef4444;' : '' }}"></i>
                            </button>

                            <a href="{{ route('companions.show', $c->id) }}" class="text-decoration-none text-reset d-block">
                                @if($c->profile_picture)
                                    <img src="{{ $c->profile_picture_url }}" class="companion-card-img" alt="{{ $c->name }}">
                                @else
                                    <div class="companion-card-img d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#7c3aed,#ec4899);">
                                        <span style="font-size:3rem;font-weight:800;color:#fff;">{{ strtoupper(substr($c->name,0,1)) }}</span>
                                    </div>
                                @endif
                            </a>
                        </div>
                        
                        <a href="{{ route('companions.show', $c->id) }}" class="text-decoration-none text-reset d-block flex-grow-1">
                            <div class="companion-card-body pb-0">
                                <div class="d-flex align-items-center gap-1 mb-1 flex-wrap">
                                    <span class="companion-card-name text-truncate" style="max-width:100px;">{{ $c->name }}</span>
                                    @if($c->partnerProfile?->rating >= 4.5)
                                        <i class="bi bi-patch-check-fill" style="color:#7c3aed;font-size:0.85rem;"></i>
                                    @endif
                                </div>
                                <div class="companion-card-city mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $c->city->name ?? 'India' }}</div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="companion-card-price text-nowrap">₹{{ number_format($c->partnerProfile->hourly_rate ?? 0) }}/hr</div>
                                    @if($c->partnerProfile?->rating)
                                        <div class="companion-rating text-nowrap"><i class="bi bi-star-fill" style="color:#f59e0b;"></i> {{ number_format($c->partnerProfile->rating,1) }}</div>
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
                @empty
                <div class="col-12 text-center py-3" style="color:var(--text-muted);">No companions available yet.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-4">

        <!-- Profile Completion -->
        @php
            $profileScore = 0;
            if($user->name) $profileScore += 20;
            if($user->email) $profileScore += 20;
            if($user->phone) $profileScore += 20;
            if($user->profile_picture) $profileScore += 20;
            if($user->city_id) $profileScore += 20;
        @endphp
        @if($profileScore < 100)
        <div class="card-glass-static p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold mb-0" style="color:var(--text-primary);">Profile Completion</h6>
                <span class="fw-bold" style="color:var(--brand-purple);font-size:1.1rem;">{{ $profileScore }}%</span>
            </div>
            <div style="background:var(--border);border-radius:99px;height:8px;overflow:hidden;">
                <div style="width:{{ $profileScore }}%;height:100%;background:linear-gradient(90deg,#7c3aed,#ec4899);border-radius:99px;transition:width 1s;"></div>
            </div>
            <div class="mt-3" style="font-size:0.82rem;color:var(--text-muted);">Complete your profile to get better companion matches.</div>
            <div class="mt-2">
                @if(!$user->phone)
                    <a href="{{ route('customer.settings') }}" class="d-flex align-items-center gap-2 py-1 text-decoration-none" style="font-size:0.83rem; color:var(--text-secondary);">
                        <i class="bi bi-circle text-warning"></i> Add phone number
                    </a>
                @endif
                @if(!$user->profile_picture)
                    <a href="{{ route('customer.settings') }}" class="d-flex align-items-center gap-2 py-1 text-decoration-none" style="font-size:0.83rem; color:var(--text-secondary);">
                        <i class="bi bi-circle text-danger"></i> Upload profile photo
                    </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Wallet Summary -->
        <div class="wallet-mini mb-4">
            <div style="font-size:0.75rem;opacity:0.7;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Wallet Balance</div>
            <div style="font-size:2.2rem;font-weight:800;margin:8px 0;">₹0.00</div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-sm" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.2);border-radius:8px;font-size:0.8rem;font-weight:600;" onclick="showComingSoon('Add Money')">
                    <i class="bi bi-plus-circle me-1"></i>Add Money
                </button>
                <button class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.15);border-radius:8px;font-size:0.8rem;" onclick="showComingSoon('Transactions')">
                    <i class="bi bi-arrow-left-right me-1"></i>History
                </button>
            </div>
            <div style="font-size:0.72rem;opacity:0.5;margin-top:0.75rem;">Cashback: ₹0 · Coupons: 0</div>
        </div>

        <!-- Notifications -->
        <div class="card-glass-static p-4 mb-4">
            <div class="section-hd">
                <h6 class="fw-bold mb-0" style="color:var(--text-primary);">Notifications</h6>
                @php $unreadNotifs = $unreadNotifs ?? \DB::table('notifications')->where('notifiable_id', Auth::id())->whereNull('read_at')->count(); @endphp
                @if($unreadNotifs > 0)
                    <span class="badge bg-danger rounded-pill" style="font-size:0.7rem;">{{ $unreadNotifs }}</span>
                @endif
            </div>
            @php $recentNotifs = \DB::table('notifications')->where('notifiable_id', Auth::id())->orderByDesc('created_at')->limit(4)->get(); @endphp
            @forelse($recentNotifs as $n)
                @php $nd = json_decode($n->data, true); @endphp
                <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(124,58,237,0.1);">
                        <i class="bi bi-bell-fill" style="color:#7c3aed;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:0.85rem;color:var(--text-primary);font-weight:500;">{{ $nd['message'] ?? 'Notification' }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-3" style="color:var(--text-muted);font-size:0.85rem;">
                    <i class="bi bi-bell-slash d-block mb-1 fs-5"></i>No notifications
                </div>
            @endforelse
        </div>

        <!-- Safety Quick Access -->
        <div class="card-glass-static p-4">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);">Safety Center</h6>
            <button class="btn w-100 mb-2" style="background:rgba(239,68,68,0.1);color:#dc2626;border:1.5px solid rgba(239,68,68,0.2);border-radius:12px;font-weight:700;padding:0.7rem;" onclick="showComingSoon('Safety Center')">
                <i class="bi bi-sos me-2"></i>SOS Emergency
            </button>
            <div class="d-flex gap-2 mt-2">
                <button class="btn btn-sm flex-1 w-100" style="background:var(--surface-2);border:1px solid var(--border);border-radius:10px;font-size:0.82rem;color:var(--text-secondary);" onclick="showComingSoon('Report User')">
                    <i class="bi bi-flag me-1"></i>Report
                </button>
                <button class="btn btn-sm flex-1 w-100" style="background:var(--surface-2);border:1px solid var(--border);border-radius:10px;font-size:0.82rem;color:var(--text-secondary);" onclick="showComingSoon('Block User')">
                    <i class="bi bi-slash-circle me-1"></i>Block
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Full Booking Tabs Section -->
<div class="card-glass-static p-4 mt-4" id="bookingsSection">
    <div class="section-hd">
        <h5><i class="bi bi-journal-text me-2" style="color:var(--brand-purple);"></i>My Bookings</h5>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills gap-2 mb-4 flex-wrap" id="bookingTabs">
        @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Accepted','ongoing'=>'Ongoing','completed'=>'Completed','cancelled'=>'Cancelled'] as $tab => $label)
        @php $cnt = $tab === 'all' ? $bookings->count() : $bookings->where('status',$tab)->count(); @endphp
        <li class="nav-item">
            <button class="nav-link {{ $tab==='all' ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#tab-{{ $tab }}"
                style="{{ $tab==='all' && true ? 'background:var(--brand-gradient);color:#fff;border:none;border-radius:10px;padding:0.45rem 1rem;font-size:0.85rem;font-weight:600;' : 'background:var(--surface-2);color:var(--text-secondary);border:1px solid var(--border);border-radius:10px;padding:0.45rem 1rem;font-size:0.85rem;font-weight:600;' }}">
                {{ $label }}
                @if($cnt > 0)
                    <span class="badge ms-1" style="background:rgba(255,255,255,0.2);font-size:0.7rem;">{{ $cnt }}</span>
                @endif
            </button>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach(['all','pending','approved','ongoing','completed','cancelled'] as $tab)
        <div class="tab-pane fade {{ $tab==='all' ? 'show active' : '' }}" id="tab-{{ $tab }}">
            @php $filteredBookings = $tab==='all' ? $bookings : $bookings->where('status',$tab); @endphp
            @if($filteredBookings->isEmpty())
                <div class="text-center py-5" style="color:var(--text-muted);">
                    <i class="bi bi-calendar-x d-block fs-2 mb-2"></i>
                    No {{ $tab !== 'all' ? $tab : '' }} bookings found.
                    @if($tab === 'all')
                        <div class="mt-2"><a href="{{ route('companions.index') }}" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;display:inline-block;text-decoration:none;">Find a Companion</a></div>
                    @endif
                </div>
            @else
                <!-- Desktop Layout -->
                <div class="table-responsive d-none d-md-block">
                    <table class="c-table">
                        <thead>
                            <tr>
                                <th>Companion</th>
                                <th>Date & Time</th>
                                <th>Duration</th>
                                <th>Location</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($filteredBookings as $b)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($b->partner->profile_picture)
                                            <img src="{{ $b->partner->profile_picture_url }}" class="avatar" style="width:38px;height:38px;" alt="">
                                        @else
                                            <div class="avatar-placeholder" style="width:38px;height:38px;">{{ strtoupper(substr($b->partner->name,0,1)) }}</div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold" style="color:var(--text-primary);font-size:0.9rem;">{{ $b->partner->name }}</div>
                                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $b->partner->city->name ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:0.88rem;color:var(--text-primary);font-weight:600;">{{ $b->booking_date->format('d M Y') }}</div>
                                    <div style="font-size:0.78rem;color:var(--text-muted);">{{ date('h:i A',strtotime($b->start_time)) }}</div>
                                </td>
                                <td style="color:var(--text-secondary);font-size:0.88rem;">{{ $b->duration_hours }} hr{{ $b->duration_hours > 1 ? 's' : '' }}</td>
                                <td style="font-size:0.82rem;color:var(--text-muted);max-width:140px;">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i>{{ Str::limit($b->location_address, 30) }}
                                </td>
                                <td><span class="fw-bold" style="color:var(--brand-purple);">₹{{ number_format($b->total_amount) }}</span></td>
                                <td><span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                                <td>
                                    @if(in_array($b->status,['pending','approved']))
                                        <form action="{{ route('customer.booking.cancel', $b->id) }}" method="POST" onsubmit="return confirm('Cancel this booking?')" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="border-radius:8px;font-size:0.75rem;color:var(--danger);border:1px solid var(--danger);padding:4px 12px;">Cancel</button>
                                        </form>
                                    @elseif($b->status === 'completed' && !$b->review)
                                        <button class="btn btn-sm btn-brand d-inline-block" style="font-size:0.75rem;padding:4px 12px;" data-bs-toggle="modal" data-bs-target="#reviewModalTab{{ $b->id }}">⭐ Review</button>
                                    @elseif($b->status === 'completed' && $b->review)
                                        <span class="d-inline-block" style="font-size:0.78rem;color:#059669;font-weight:600;"><i class="bi bi-check-circle-fill me-1"></i>Reviewed</span>
                                    @else
                                        <span style="color:var(--text-muted);font-size:0.8rem;">—</span>
                                    @endif
                                    
                                    @if(in_array($b->status, ['approved', 'ongoing', 'completed', 'paid', 'confirmed', 'rescheduled']))
                                        <a href="{{ route('chat.start', $b->partner->id) }}" class="btn btn-sm d-inline-block mt-1" style="background:#f3f4f6; color:#374151; border:1px solid #d1d5db; border-radius:8px; font-size:0.75rem; padding:4px 12px;">
                                            <i class="bi bi-chat-text"></i> Chat
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            @if($b->status === 'completed' && !$b->review)
                            <!-- Review Modal Tab -->
                            <div class="modal fade" id="reviewModalTab{{ $b->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);border-radius:20px;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold" style="color:var(--text-primary);">Rate {{ $b->partner->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('customer.booking.review', $b->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <label class="form-label">Rating</label>
                                                <div class="d-flex gap-2 mb-3" id="starsTab{{ $b->id }}">
                                                    @for($s=1;$s<=5;$s++)
                                                        <i class="bi bi-star fs-4" style="color:#f59e0b;cursor:pointer;" data-star="{{ $s }}" onclick="setRatingTab({{ $b->id }},{{ $s }})"></i>
                                                    @endfor
                                                </div>
                                                <input type="hidden" name="rating" id="ratingInputTab{{ $b->id }}" value="5">
                                                <label class="form-label">Your Feedback</label>
                                                <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience..." required></textarea>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;">Submit Review</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Layout -->
                <div class="d-md-none">
                    @foreach($filteredBookings as $b)
                    <div class="mobile-booking-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                @if($b->partner->profile_picture)
                                    <img src="{{ $b->partner->profile_picture_url }}" class="avatar" style="width:38px;height:38px;" alt="">
                                @else
                                    <div class="avatar-placeholder" style="width:38px;height:38px;">{{ strtoupper(substr($b->partner->name,0,1)) }}</div>
                                @endif
                                <div>
                                    <div class="fw-semibold text-theme-primary" style="font-size:0.9rem;">{{ $b->partner->name }}</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $b->partner->city->name ?? '' }}</div>
                                </div>
                            </div>
                            <span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                        </div>
                        
                        <div class="py-2 border-top border-bottom my-2" style="font-size:0.8rem; color:var(--text-secondary);">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Date & Time:</span>
                                <span class="fw-medium text-theme-primary">{{ $b->booking_date->format('d M Y') }} · {{ date('h:i A',strtotime($b->start_time)) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Duration:</span>
                                <span>{{ $b->duration_hours }} hr{{ $b->duration_hours > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Location:</span>
                                <span class="text-truncate text-theme-primary" style="max-width: 180px;">{{ $b->location_address }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Amount:</span>
                                <span class="fw-bold" style="color:var(--brand-purple);">₹{{ number_format($b->total_amount) }}</span>
                            </div>
                        </div>

                        <div class="mt-2">
                            @if(in_array($b->status,['pending','approved']))
                                <form action="{{ route('customer.booking.cancel', $b->id) }}" method="POST" onsubmit="return confirm('Cancel this booking?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100 py-2 mb-2" style="border-radius:8px;font-size:0.8rem;">Cancel Booking</button>
                                </form>
                            @elseif($b->status === 'completed' && !$b->review)
                                <button class="btn btn-brand w-100 py-2 mb-2" style="font-size:0.8rem;" data-bs-toggle="modal" data-bs-target="#reviewModalMobileTab{{ $b->id }}">⭐ Write Review</button>
                                
                                <!-- Review Modal Mobile Tab -->
                                <div class="modal fade" id="reviewModalMobileTab{{ $b->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);border-radius:20px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold" style="color:var(--text-primary);">Rate {{ $b->partner->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('customer.booking.review', $b->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <label class="form-label">Rating</label>
                                                    <div class="d-flex gap-2 mb-3" id="starsMobileTab{{ $b->id }}">
                                                        @for($s=1;$s<=5;$s++)
                                                            <i class="bi bi-star fs-4" style="color:#f59e0b;cursor:pointer;" data-star="{{ $s }}" onclick="setRatingMobileTab({{ $b->id }},{{ $s }})"></i>
                                                        @endfor
                                                    </div>
                                                    <input type="hidden" name="rating" id="ratingInputMobileTab{{ $b->id }}" value="5">
                                                    <label class="form-label">Your Feedback</label>
                                                    <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience..." required></textarea>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;">Submit Review</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($b->status === 'completed' && $b->review)
                                <div class="text-center py-1 text-success fw-semibold mb-2" style="font-size:0.8rem;"><i class="bi bi-check-circle-fill me-1"></i>Reviewed</div>
                            @else
                                <div class="text-center text-muted mb-2" style="font-size:0.8rem;">—</div>
                            @endif
                            
                            @if(in_array($b->status, ['approved', 'ongoing', 'completed', 'paid', 'confirmed', 'rescheduled']))
                                <a href="{{ route('chat.start', $b->partner->id) }}" class="btn btn-light w-100 py-2 border" style="border-radius:8px;font-size:0.8rem;">
                                    <i class="bi bi-chat-text"></i> Chat
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

@endsection

@section('scripts')
<script>
function setRating(bookingId, star) {
    document.getElementById('ratingInput' + bookingId).value = star;
    const stars = document.querySelectorAll('#stars' + bookingId + ' i');
    stars.forEach((s, i) => {
        s.classList.toggle('bi-star-fill', i < star);
        s.classList.toggle('bi-star', i >= star);
    });
}

function setRatingTab(bookingId, star) {
    document.getElementById('ratingInputTab' + bookingId).value = star;
    const stars = document.querySelectorAll('#starsTab' + bookingId + ' i');
    stars.forEach((s, i) => {
        s.classList.toggle('bi-star-fill', i < star);
        s.classList.toggle('bi-star', i >= star);
    });
}

function setRatingMobile(bookingId, star) {
    document.getElementById('ratingInputMobile' + bookingId).value = star;
    const stars = document.querySelectorAll('#starsMobile' + bookingId + ' i');
    stars.forEach((s, i) => {
        s.classList.toggle('bi-star-fill', i < star);
        s.classList.toggle('bi-star', i >= star);
    });
}

function setRatingMobileTab(bookingId, star) {
    document.getElementById('ratingInputMobileTab' + bookingId).value = star;
    const stars = document.querySelectorAll('#starsMobileTab' + bookingId + ' i');
    stars.forEach((s, i) => {
        s.classList.toggle('bi-star-fill', i < star);
        s.classList.toggle('bi-star', i >= star);
    });
}

// Tab nav pill active styling fix
document.querySelectorAll('#bookingTabs .nav-link').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('#bookingTabs .nav-link').forEach(b => {
            b.style.background = 'var(--surface-2)';
            b.style.color = 'var(--text-secondary)';
            b.style.border = '1px solid var(--border)';
        });
        this.style.background = 'linear-gradient(135deg, #7c3aed 0%, #ec4899 100%)';
        this.style.color = '#fff';
        this.style.border = 'none';
    });
});

// Auto open bookings tab if hash
if(window.location.hash === '#bookings' || new URLSearchParams(window.location.search).get('tab') === 'bookings') {
    setTimeout(() => {
        document.getElementById('bookingsSection')?.scrollIntoView({ behavior: 'smooth' });
    }, 300);
}
</script>@endsection
