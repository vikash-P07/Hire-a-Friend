@extends('layouts.partner')
@section('title', 'Dashboard | Companion Partner')

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

    /* Subscription Summary */
    .subscription-mini {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    html.dark .subscription-mini {
        background: linear-gradient(135deg, #312e81 0%, #1e1b4b 100%);
    }
    .subscription-mini::before {
        content: '';
        position: absolute;
        right: -30px; top: -30px;
        width: 120px; height: 120px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* Stats Dashboard */
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
            margin-bottom: 1.25rem !important;
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
            height: 100%;
        }
        .stat-card-icon {
            width: 40px !important;
            height: 40px !important;
            border-radius: 10px !important;
            font-size: 1.1rem !important;
            margin-bottom: 0.25rem !important;
        }
        .stat-card-val {
            font-size: 1.25rem !important;
        }
        .stat-card-lbl {
            font-size: 0.72rem !important;
        }
    }

    /* Upcoming Booking Card Responsive Adjustments */
    @media (max-width: 575.98px) {
        .upcoming-card {
            padding: 1rem !important;
        }
        .upcoming-card .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.75rem !important;
        }
        .upcoming-card .text-end {
            text-align: left !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            border-top: 1px solid var(--border-light);
            padding-top: 0.75rem;
            margin-top: 0.25rem;
        }
        .upcoming-card .text-end form {
            margin-top: 0 !important;
        }
    }

    /* Section Header */
    .section-hd { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
    .section-hd h5 { font-weight: 800; font-size: 1.1rem; color: var(--text-primary); margin: 0; }
    .section-hd a { font-size: 0.85rem; font-weight: 600; color: var(--brand-purple); text-decoration: none; }
    .section-hd a:hover { text-decoration: underline; }
</style>
@endsection

@section('content')

<!-- KYC Status Alerts -->
@if($stats['kyc_status'] === 'pending')
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 d-flex align-items-center mb-4" role="alert" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.1) !important;">
        <i class="bi bi-clock-history fs-2 text-warning me-3"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-1 text-theme-primary">Profile Verification Pending Approval</h6>
            <p class="mb-0 small text-muted">Your profile is currently waiting for administrators to verify your uploaded Aadhaar, PAN, and Selfie verification files. Your listing is hidden from customer searches until approved.</p>
        </div>
    </div>
@elseif($stats['kyc_status'] === 'rejected')
    <div class="alert alert-danger border-0 rounded-4 shadow-sm p-4 d-flex align-items-center mb-4" role="alert" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.1) !important;">
        <i class="bi bi-x-octagon-fill fs-2 text-danger me-3"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-1 text-theme-primary">KYC Validation Rejected</h6>
            <p class="mb-1 small text-muted">Reason: <strong>{{ $profile->kyc_notes ?? 'Incomplete details' }}</strong></p>
            <p class="mb-0 small text-muted">Please update your documents in the <a href="{{ route('partner.profile') }}" class="fw-bold text-danger">Profile Settings</a> to submit for re-review.</p>
        </div>
    </div>
@endif

<!-- ══ WELCOME BANNER ═════════════════════════════════ -->
<div class="welcome-banner">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3" style="position:relative;z-index:2;">
        <div class="d-flex align-items-center gap-3">
            @if($user->profile_picture)
                <img src="{{ $user->profile_picture_url }}" class="welcome-avatar" alt="">
            @else
                <div class="welcome-avatar-placeholder">{{ strtoupper(substr($user->name,0,1)) }}</div>
            @endif
            <div>
                <div style="font-size:0.8rem;opacity:0.8;font-weight:500;">Companion Portal 👋</div>
                <h2 style="font-weight:800;font-size:1.5rem;margin:2px 0 4px;letter-spacing:-0.5px;">{{ $user->name }}</h2>
                <div style="font-size:0.82rem;opacity:0.75;">
                    <i class="bi bi-geo-alt me-1"></i>{{ $user->city->name ?? 'Anywhere' }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('D, d M Y') }}
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-wrapper">
            <a href="{{ route('partner.profile') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-person-gear text-white"></i>
                </div>
                <span>Edit Profile</span>
            </a>
            <a href="{{ route('partner.bookings') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-calendar-check text-white"></i>
                </div>
                <span>Bookings</span>
            </a>
            <a href="{{ route('partner.earnings') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-wallet2 text-white"></i>
                </div>
                <span>Earnings</span>
            </a>
            <a href="{{ route('partner.availability') }}" class="quick-action">
                <div class="quick-action-icon" style="background:rgba(255,255,255,0.2);">
                    <i class="bi bi-clock text-white"></i>
                </div>
                <span>Schedule</span>
            </a>
        </div>
    </div>
</div>

<!-- ══ PROFILE COMPLETION CARD ════════════════════════ -->
@if($completionPercentage < 100)
<div class="card-glass-static mb-4 p-4 shadow-sm" style="border-left: 4px solid var(--brand-purple) !important; background: var(--surface);">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="flex-grow-1" style="max-width: 680px;">
            <h5 class="fw-bold mb-1 text-theme-primary" style="font-size: 1.05rem;"><i class="bi bi-person-fill-check text-primary me-2"></i>Complete Your Profile</h5>
            <p class="text-muted small mb-3">Please fill in your companion profile details (bio, rates, languages, interests, services offered, document verification, and availability schedule) to activate your account and appear in customer searches.</p>
            <div class="d-flex align-items-center gap-3">
                <div class="progress flex-grow-1 shadow-sm" style="height: 10px; border-radius: 5px; background: rgba(124, 58, 237, 0.08);">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $completionPercentage }}%; border-radius: 5px; background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);" aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="fw-bold text-primary small" style="color: var(--brand-purple) !important; font-size: 0.9rem;">{{ $completionPercentage }}% Complete</span>
            </div>
        </div>
        <div>
            <a href="{{ route('partner.profile') }}" class="btn btn-brand rounded-pill px-4 py-2" style="font-size: 0.85rem; background: var(--brand-purple); color: white; border: none;">
                <i class="bi bi-pencil-square me-1"></i>Complete Profile
            </a>
        </div>
    </div>
</div>
@endif

<!-- ══ STATS GRID ═════════════════════════════════════ -->
<div class="row g-3 mt-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card-dash reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(124,58,237,0.1);">
                <i class="bi bi-wallet2" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate" data-prefix="₹">{{ $stats['total_earnings'] }}</div>
                <div class="stat-card-lbl">Net Revenue</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card-dash reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(245,158,11,0.1);">
                <i class="bi bi-calendar-check" style="color:#d97706;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate">{{ $stats['pending_bookings'] }}</div>
                <div class="stat-card-lbl">Pending Bookings</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card-dash reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(16,185,129,0.1);">
                <i class="bi bi-star-fill" style="color:#059669;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate" data-suffix=" ★" data-target="{{ $stats['rating'] }}">{{ number_format($stats['rating'], 1) }} ★</div>
                <div class="stat-card-lbl">Average Rating</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card-dash reveal-up dashboard-card-hover tilt-3d-card">
            <div class="stat-card-icon" style="background:rgba(6,182,212,0.1);">
                <i class="bi bi-eye" style="color:#0891b2;"></i>
            </div>
            <div>
                <div class="stat-card-val counter-animate">{{ $stats['profile_views'] }}</div>
                <div class="stat-card-lbl">Profile Views</div>
            </div>
        </div>
    </div>
</div>

<!-- ══ MAIN GRID ══════════════════════════════════════ -->
<div class="row g-4 mt-1">

    <!-- LEFT COLUMN -->
    <div class="col-lg-8">

        <!-- Upcoming Booking -->
        @php
            $upcoming = $upcomingBookings->first();
        @endphp
        @if($upcoming)
        <div class="card-glass-static mb-4">
            <div class="p-4">
                <div class="section-hd">
                    <h5><i class="bi bi-calendar-event me-2" style="color:var(--brand-purple);"></i>Upcoming Companion Session</h5>
                    <span class="booking-badge badge-{{ $upcoming->status }}">{{ ucfirst($upcoming->status) }}</span>
                </div>
                <div class="upcoming-card dashboard-card-hover tilt-3d-card">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        @if($upcoming->customer->profile_picture)
                            <img src="{{ $upcoming->customer->profile_picture_url }}" class="avatar" style="width:56px;height:56px;" alt="">
                        @else
                            <div class="avatar-placeholder" style="width:56px;height:56px;font-size:1.4rem;">
                                {{ strtoupper(substr($upcoming->customer->name,0,1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="color:var(--text-primary);font-size:1.05rem;">{{ $upcoming->customer->name }}</div>
                            <div style="font-size:0.85rem;color:var(--text-muted);">
                                <i class="bi bi-calendar3 me-1"></i>{{ $upcoming->booking_date->format('D, d M Y') }}
                                &nbsp;·&nbsp;
                                <i class="bi bi-clock me-1"></i>{{ date('h:i A', strtotime($upcoming->start_time)) }}
                                &nbsp;·&nbsp;
                                {{ $upcoming->duration_hours }}h session
                            </div>
                            <div style="font-size:0.82rem;color:var(--text-muted);margin-top:2px;">
                                <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $upcoming->location_address }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" style="font-size:1.2rem;color:var(--brand-purple);">₹{{ number_format($upcoming->total_amount) }}</div>
                            <form action="{{ route('partner.booking.action', [$upcoming->id, 'complete']) }}" method="POST" class="mt-2" onsubmit="return confirm('Mark this booking as completed?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-brand px-3 py-1.5" style="border-radius:8px;font-size:0.8rem;">Complete Session</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Booking Requests -->
        <div class="card-glass-static mb-4">
            <div class="p-4 pb-0">
                <div class="section-hd">
                    <h5><i class="bi bi-list-check me-2" style="color:var(--brand-purple);"></i>Pending Reservation Requests</h5>
                    <a href="{{ route('partner.bookings') }}">View all</a>
                </div>
            </div>
            <div class="table-responsive d-none d-md-block">
                <table class="c-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Amount</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings->where('status', 'pending')->take(5) as $b)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($b->customer->profile_picture)
                                        <img src="{{ $b->customer->profile_picture_url }}" class="avatar" style="width:34px;height:34px;" alt="">
                                    @else
                                        <div class="avatar-placeholder" style="width:34px;height:34px;font-size:0.85rem;">{{ strtoupper(substr($b->customer->name,0,1)) }}</div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">{{ $b->customer->name }}</div>
                                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ $b->customer->city->name ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:0.88rem;color:var(--text-secondary);">{{ $b->booking_date->format('d M Y') }}</td>
                            <td style="font-size:0.88rem;color:var(--text-secondary);">{{ $b->duration_hours }}h</td>
                            <td><span class="fw-bold" style="color:var(--brand-purple);">₹{{ number_format($b->total_amount) }}</span></td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <form action="{{ route('partner.booking.action', [$b->id, 'accept']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-brand px-3 py-1" style="font-size:0.75rem;">Accept</button>
                                    </form>
                                    <form action="{{ route('partner.booking.action', [$b->id, 'reject']) }}" method="POST" onsubmit="return confirm('Reject request?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-surface px-2 py-1" style="font-size:0.75rem;">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5" style="color:var(--text-muted);">
                                <i class="bi bi-calendar-x d-block fs-2 mb-2"></i>
                                No pending booking requests.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile List Cards View (Visible on Mobile Only) -->
            <div class="d-md-none px-4 pb-4">
                @forelse($bookings->where('status', 'pending')->take(5) as $b)
                    <div class="pb-3 mb-3 border-bottom" style="border-color:var(--border-light)!important;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            @if($b->customer->profile_picture)
                                <img src="{{ $b->customer->profile_picture_url }}" class="avatar" style="width:34px;height:34px;" alt="">
                            @else
                                <div class="avatar-placeholder" style="width:34px;height:34px;font-size:0.85rem;">{{ strtoupper(substr($b->customer->name,0,1)) }}</div>
                            @endif
                            <div>
                                <div class="fw-semibold text-theme-primary" style="font-size:0.9rem;">{{ $b->customer->name }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $b->customer->city->name ?? '' }}</div>
                            </div>
                            <span class="fw-bold ms-auto" style="color:var(--brand-purple); font-size:0.95rem;">₹{{ number_format($b->total_amount) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted small">
                            <div>
                                <i class="bi bi-calendar3 me-1"></i>{{ $b->booking_date->format('d M Y') }}
                            </div>
                            <div>
                                <i class="bi bi-clock me-1"></i>{{ $b->duration_hours }}h duration
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('partner.booking.action', [$b->id, 'accept']) }}" method="POST" class="flex-grow-1 mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-brand w-100 py-2" style="font-size:0.8rem;">Accept</button>
                            </form>
                            <form action="{{ route('partner.booking.action', [$b->id, 'reject']) }}" method="POST" onsubmit="return confirm('Reject request?')" class="flex-grow-1 mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-surface w-100 py-2" style="font-size:0.8rem;">Reject</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted" style="font-size:0.85rem;">
                        <i class="bi bi-calendar-x d-block fs-3 mb-1"></i>
                        No pending booking requests.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-4">

        <!-- Active Subscription Summary -->
        <div class="subscription-mini mb-4">
            @php
                $sub = $user->activeSubscription;
            @endphp
            <div style="font-size:0.75rem;opacity:0.7;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Active Plan Status</div>
            @if($sub)
                <div style="font-size:1.8rem;font-weight:800;margin:8px 0;">{{ $sub->plan->name }}</div>
                <div style="font-size:0.75rem;opacity:0.8;margin-top:0.25rem;">
                    Valid until: {{ $sub->ends_at->format('d M Y') }}
                </div>
            @else
                <div style="font-size:1.8rem;font-weight:800;margin:8px 0;">Free Listing</div>
                <div style="font-size:0.72rem;opacity:0.7;margin-top:0.25rem;">Get better search rankings by upgrading.</div>
            @endif
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('partner.subscription') }}" class="btn btn-sm text-decoration-none" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.2);border-radius:8px;font-size:0.8rem;font-weight:600;">
                    <i class="bi bi-stars me-1"></i>Upgrade Package
                </a>
            </div>
        </div>

        <!-- Vacation Mode Toggle -->
        <div class="card-glass-static p-4 mb-4">
            <h6 class="fw-bold mb-3" style="color:var(--text-primary);">Vacation & Availability Status</h6>
            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border mb-3" style="background: var(--surface-2); border-color: var(--border)!important;">
                <div>
                    <div class="fw-bold" style="font-size:0.85rem; color:var(--text-primary);">Vacation Mode</div>
                    <div style="font-size:0.72rem; color:var(--text-muted);">Temporarily hide your listing</div>
                </div>
                <form action="{{ route('partner.availability.vacation') }}" method="POST" id="vacationForm">
                    @csrf
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" role="switch" id="vacationSwitch" {{ $profile->vacation_mode ? 'checked' : '' }} onchange="document.getElementById('vacationForm').submit()">
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Customer Reviews -->
        <div class="card-glass-static p-4">
            <div class="section-hd">
                <h6 class="fw-bold mb-0" style="color:var(--text-primary);">Latest Client Reviews</h6>
            </div>
            @forelse($latestReviews as $review)
                <div class="activity-item">
                    <div class="activity-icon" style="background:rgba(245,158,11,0.1);">
                        <i class="bi bi-star-fill" style="color:#d97706;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size:0.85rem;color:var(--text-primary);font-weight:600;">{{ $review->customer->name }}</span>
                            <span style="font-size:0.72rem;color:#f59e0b;">{{ $review->rating }}★</span>
                        </div>
                        <p class="mb-0 text-muted" style="font-size:0.8rem;line-height:1.4;">"{{ Str::limit($review->comment, 80) }}"</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted" style="font-size:0.85rem;">
                    <i class="bi bi-chat-left-text d-block mb-1 fs-5"></i>No reviews received yet.
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection
