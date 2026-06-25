@extends('layouts.app')
@section('title', $companion->name . ' | Hire-a-Friend')

@section('styles')
<style>
:root { --brand-purple:#7c3aed; --brand-pink:#ec4899; --brand-glow:rgba(124,58,237,0.25); }
.profile-hero { position:relative; background:linear-gradient(180deg,rgba(15,23,42,0.7) 0%,rgba(15,23,42,0.4) 100%); border-radius:24px; overflow:hidden; margin-bottom:2rem; }
.profile-hero-bg { width:100%; height:320px; object-fit:cover; display:block; }
.profile-hero-body { position:absolute; bottom:0; left:0; right:0; padding:2rem; color:#fff; }
.profile-avatar { width:90px; height:90px; border-radius:20px; border:4px solid rgba(255,255,255,0.5); object-fit:cover; }
.profile-avatar-placeholder { width:90px; height:90px; border-radius:20px; border:4px solid rgba(255,255,255,0.5); background:linear-gradient(135deg,#7c3aed,#ec4899); display:flex; align-items:center; justify-content:center; font-size:2.5rem; font-weight:800; color:#fff; }
.profile-card { background:var(--card-bg,#fff); border:1px solid rgba(0,0,0,0.06); border-radius:20px; padding:2rem; box-shadow:0 2px 12px rgba(0,0,0,0.06); margin-bottom:1.5rem; }
html.dark .profile-card { background:#0f172a; border-color:#1e293b; }
.rating-stars { color:#f59e0b; font-size:1rem; }
.tag-pill { display:inline-flex; align-items:center; gap:5px; padding:5px 14px; border-radius:99px; font-size:0.8rem; font-weight:600; background:rgba(124,58,237,0.08); color:#7c3aed; margin:3px; }
.gallery-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
.gallery-img { width:100%; height:110px; object-fit:cover; border-radius:14px; cursor:pointer; transition:transform 0.2s; }
.gallery-img:hover { transform:scale(1.03); }
.booking-card { background:var(--card-bg,#fff); border:1px solid rgba(0,0,0,0.06); border-radius:20px; padding:2rem; box-shadow:0 4px 24px rgba(0,0,0,0.08); position:sticky; top:80px; max-height: calc(100vh - 100px); overflow-y: auto; }
html.dark .booking-card { background:#0f172a; border-color:#1e293b; }
.calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; text-align:center; }
.cal-day { padding:8px 4px; border-radius:8px; font-size:0.8rem; cursor:pointer; transition:all 0.2s; font-weight:500; }
.cal-day:hover { background:rgba(124,58,237,0.1); color:#7c3aed; }
.cal-day.available { color:var(--text-primary,#0f172a); }
.cal-day.today { border: 2px solid #7c3aed; color: #7c3aed; font-weight: 700; border-radius: 8px; }
.cal-day.selected { background: #7c3aed !important; color: #fff !important; }
.cal-day.unavailable { color:rgba(0,0,0,0.2); cursor:not-allowed; text-decoration:line-through; }
.time-slot { padding:8px 14px; border-radius:10px; border:1.5px solid rgba(0,0,0,0.1); font-size:0.82rem; font-weight:600; cursor:pointer; transition:all 0.2s; background:transparent; color:var(--text-secondary,#475569); }
.time-slot:hover, .time-slot.active { background:#7c3aed; color:#fff; border-color:#7c3aed; }
.time-slot.booked { background:rgba(0,0,0,0.05); color:rgba(0,0,0,0.3); cursor:not-allowed; }
html.dark .time-slot { border-color:rgba(255,255,255,0.15); color:#94a3b8; }
.review-card { padding:1.25rem; background:var(--surface-2,#f8fafc); border-radius:14px; margin-bottom:0.85rem; }
html.dark .review-card { background:#1e293b; }
.similar-card { border-radius:14px; overflow:hidden; background:var(--card-bg,#fff); border:1px solid rgba(0,0,0,0.06); box-shadow:0 2px 8px rgba(0,0,0,0.05); text-decoration:none; display:block; color:inherit; transition:all 0.2s; }
.similar-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,0.1); color:inherit; }
.btn-book-main { background:linear-gradient(135deg,#7c3aed,#ec4899); color:#fff; border:none; border-radius:14px; padding:0.9rem 2rem; font-weight:700; font-size:1rem; width:100%; cursor:pointer; box-shadow:0 6px 20px rgba(124,58,237,0.35); transition:all 0.2s; }
.btn-book-main:hover { transform:translateY(-2px); box-shadow:0 8px 28px rgba(124,58,237,0.45); }
.step-indicator { display:flex; gap:6px; margin-bottom:1.5rem; }
.step-dot { height:4px; flex:1; border-radius:99px; background:rgba(0,0,0,0.1); transition:background 0.3s; }
.step-dot.active { background:linear-gradient(90deg,#7c3aed,#ec4899); }
html.dark .step-dot { background:rgba(255,255,255,0.1); }

@media (max-width: 480px) {
    .profile-hero-body .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1rem !important;
    }
}
</style>
@endsection

@section('content')
@php
    $isFav = auth()->check() && auth()->user()->favorites()->where('companion_id', $companion->id)->exists();
    $similarCompanions = \App\Models\User::where('role','partner')
        ->where('is_active',true)
        ->where('id','!=',$companion->id)
        ->whereHas('partnerProfile',fn($q)=>$q->where('kyc_status','approved'))
        ->with(['partnerProfile','city'])
        ->when($companion->city_id, fn($q)=>$q->where('city_id',$companion->city_id))
        ->inRandomOrder()->limit(3)->get();
    $galleryImgs = [
        'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=400&h=300&fit=crop',
        'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400&h=300&fit=crop',
        'https://images.unsplash.com/photo-1497366216548-37526070297c?w=400&h=300&fit=crop',
        'https://images.unsplash.com/photo-1521017432531-fbd92d768814?w=400&h=300&fit=crop',
        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop',
        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=300&fit=crop',
    ];
    $timeSlots = ['09:00 AM','10:00 AM','11:00 AM','12:00 PM','01:00 PM','02:00 PM','03:00 PM','04:00 PM','05:00 PM','06:00 PM','07:00 PM','08:00 PM'];
@endphp

<div class="container-lg py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:var(--brand-purple);text-decoration:none;">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companions.index') }}" style="color:var(--brand-purple);text-decoration:none;">Companions</a></li>
            <li class="breadcrumb-item active" style="color:var(--text-muted,#64748b);">{{ $companion->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- LEFT: Profile info -->
        <div class="col-lg-7">

            <!-- Hero -->
            <div class="profile-hero">
                <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=900&h=400&fit=crop&q=80" class="profile-hero-bg" alt="">
                <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,0.1) 0%,rgba(0,0,0,0.7) 100%);"></div>
                <div class="profile-hero-body">
                    <div class="d-flex align-items-end gap-3">
                        @if($companion->profile_picture)
                            <img src="{{ $companion->profile_picture_url }}" class="profile-avatar" alt="">
                        @else
                            <div class="profile-avatar-placeholder">{{ strtoupper(substr($companion->name,0,1)) }}</div>
                        @endif
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h2 class="fw-bold mb-0" style="font-size:1.6rem;">{{ $companion->name }}</h2>
                                @if($companion->partnerProfile?->kyc_status === 'approved')
                                    <span style="background:rgba(16,185,129,0.9);color:#fff;font-size:0.72rem;font-weight:700;padding:3px 10px;border-radius:99px;"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
                                @endif
                                <span style="background:rgba(16,185,129,0.8);color:#fff;font-size:0.7rem;font-weight:700;padding:3px 8px;border-radius:99px;display:flex;align-items:center;gap:4px;"><span style="width:7px;height:7px;background:#fff;border-radius:50%;display:inline-block;"></span>Online</span>
                            </div>
                            <div style="font-size:0.87rem;opacity:0.85;">
                                <i class="bi bi-geo-alt me-1"></i>{{ $companion->city?->name ?? 'India' }} &nbsp;·&nbsp;
                                <i class="bi bi-briefcase me-1"></i>{{ $companion->partnerProfile?->experience_years ?? 1 }} yrs exp &nbsp;·&nbsp;
                                <i class="bi bi-gender-ambiguous me-1"></i>{{ ucfirst($companion->gender ?? 'Other') }}
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <div class="rating-stars">
                                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i <= round($companion->partnerProfile?->rating ?? 0) ? '-fill' : '' }}"></i>@endfor
                                </div>
                                <span style="font-weight:700;font-size:0.9rem;">{{ number_format($companion->partnerProfile?->rating ?? 0,1) }}</span>
                                <span style="font-size:0.82rem;opacity:0.75;">({{ $reviews->count() }} reviews)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About -->
            <div class="profile-card">
                <h5 class="fw-bold mb-3" style="font-size:1.05rem;">About {{ $companion->name }}</h5>
                <p style="line-height:1.8;color:var(--text-secondary,#475569);font-size:0.93rem;">{{ $companion->partnerProfile?->bio ?? 'This companion has not added a bio yet.' }}</p>

                <div class="row g-3 mt-2">
                    <div class="col-6 col-md-3">
                        <div style="text-align:center;padding:1rem;background:rgba(124,58,237,0.06);border-radius:14px;">
                            <div style="font-size:1.5rem;font-weight:800;color:#7c3aed;">{{ $companion->partnerProfile?->experience_years ?? 0 }}+</div>
                            <div style="font-size:0.75rem;color:var(--text-muted,#94a3b8);font-weight:600;">Years Exp</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="text-align:center;padding:1rem;background:rgba(236,72,153,0.06);border-radius:14px;">
                            <div style="font-size:1.5rem;font-weight:800;color:#ec4899;">{{ $reviews->count() }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted,#94a3b8);font-weight:600;">Reviews</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="text-align:center;padding:1rem;background:rgba(16,185,129,0.06);border-radius:14px;">
                            <div style="font-size:1.5rem;font-weight:800;color:#059669;">{{ number_format($companion->partnerProfile?->rating ?? 0,1) }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted,#94a3b8);font-weight:600;">Rating</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div style="text-align:center;padding:1rem;background:rgba(245,158,11,0.06);border-radius:14px;">
                            <div style="font-size:1.5rem;font-weight:800;color:#d97706;">98%</div>
                            <div style="font-size:0.75rem;color:var(--text-muted,#94a3b8);font-weight:600;">Response</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services & Tags -->
            <div class="profile-card">
                <h5 class="fw-bold mb-3" style="font-size:1.05rem;">Activities & Interests</h5>
                <div>
                    @forelse($companion->services as $svc)
                        <span class="tag-pill"><i class="bi bi-check-circle-fill"></i>{{ $svc->name }}</span>
                    @empty
                        @foreach(['Café Meetups','City Walks','Coworking','Shopping','Dining','Events','Travel','Photography'] as $tag)
                            <span class="tag-pill">{{ $tag }}</span>
                        @endforeach
                    @endforelse
                </div>

                <div class="mt-3">
                    <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted,#94a3b8);margin-bottom:8px;">Languages</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['Hindi','English','Marathi'] as $lang)
                            <span style="padding:4px 14px;border-radius:99px;border:1.5px solid rgba(124,58,237,0.2);font-size:0.8rem;font-weight:600;color:#7c3aed;"><i class="bi bi-translate me-1"></i>{{ $lang }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="profile-card">
                <h5 class="fw-bold mb-3" style="font-size:1.05rem;">Lifestyle Gallery</h5>
                <div class="gallery-grid">
                    @foreach($galleryImgs as $img)
                        <img src="{{ $img }}" class="gallery-img" alt="Gallery" onclick="openLightbox('{{ $img }}')">
                    @endforeach
                </div>
            </div>

            <!-- Reviews -->
            <div class="profile-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="fw-bold mb-0" style="font-size:1.05rem;">Reviews ({{ $reviews->count() }})</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="rating-stars" style="font-size:0.9rem;">
                            @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=round($companion->partnerProfile?->rating??0) ? '-fill' : '' }}"></i>@endfor
                        </span>
                        <span class="fw-bold" style="font-size:1rem;">{{ number_format($companion->partnerProfile?->rating??0,1) }}</span>
                    </div>
                </div>

                @forelse($reviews as $r)
                <div class="review-card">
                    <div class="d-flex align-items-start gap-3">
                        <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#ec4899);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0;">
                            {{ strtoupper(substr($r->customer?->name??'U',0,1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div class="fw-bold" style="font-size:0.9rem;">{{ $r->customer?->name ?? 'Anonymous' }}</div>
                                <div class="d-flex align-items-center gap-1">
                                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$r->rating ? '-fill' : '' }}" style="color:#f59e0b;font-size:0.75rem;"></i>@endfor
                                </div>
                            </div>
                            <div style="font-size:0.75rem;color:var(--text-muted,#94a3b8);margin-bottom:6px;">{{ $r->created_at->format('d M Y') }}</div>
                            <p style="font-size:0.88rem;margin:0;line-height:1.6;color:var(--text-secondary,#475569);">"{{ $r->comment }}"</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4" style="color:var(--text-muted,#94a3b8);">
                    <i class="bi bi-chat-heart d-block fs-2 mb-2"></i>
                    No reviews yet. Be the first to review {{ $companion->name }}!
                </div>
                @endforelse
            </div>

            <!-- Similar Companions -->
            @if($similarCompanions->count())
            <div class="profile-card">
                <h5 class="fw-bold mb-3" style="font-size:1.05rem;">Similar Companions in {{ $companion->city?->name ?? 'Your City' }}</h5>
                <div class="row g-3">
                    @foreach($similarCompanions as $sc)
                    <div class="col-4">
                        <a href="{{ route('companions.show',$sc->id) }}" class="similar-card">
                            @if($sc->profile_picture)
                                <img src="{{ $sc->profile_picture_url }}" style="width:100%;height:100px;object-fit:cover;" alt="">
                            @else
                                <div style="width:100%;height:100px;background:linear-gradient(135deg,#7c3aed,#ec4899);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:#fff;">{{ strtoupper(substr($sc->name,0,1)) }}</div>
                            @endif
                            <div style="padding:0.65rem;">
                                <div class="fw-bold" style="font-size:0.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $sc->name }}</div>
                                <div style="font-size:0.72rem;color:var(--text-muted,#94a3b8);">₹{{ number_format($sc->partnerProfile?->hourly_rate??0) }}/hr</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- RIGHT: Booking card -->
        <div class="col-lg-5">
            <div class="booking-card">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <span style="font-size:2rem;font-weight:800;color:#7c3aed;">₹{{ number_format($companion->partnerProfile?->hourly_rate??0) }}</span>
                        <span style="font-size:0.88rem;color:var(--text-muted,#94a3b8);"> / hour</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="fav-btn {{ $isFav ? 'active' : '' }}" data-companion-id="{{ $companion->id }}" onclick="toggleFavorite({{ $companion->id }}, this);" style="width:36px;height:36px;border-radius:50%;border:1.5px solid rgba(239,68,68,0.3);background:rgba(239,68,68,0.06);color:#ef4444;cursor:pointer;" title="{{ $isFav ? 'Remove from favorites' : 'Add to favorites' }}">
                            <i class="bi {{ $isFav ? 'bi-heart-fill text-danger' : 'bi-heart' }}" style="{{ $isFav ? 'color:#ef4444;' : '' }}"></i>
                        </button>
                        <button style="width:36px;height:36px;border-radius:50%;border:1.5px solid rgba(124,58,237,0.3);background:rgba(124,58,237,0.06);color:#7c3aed;cursor:pointer;" title="Share" onclick="navigator.share && navigator.share({title:'{{ $companion->name }}',url:window.location.href})">
                            <i class="bi bi-share"></i>
                        </button>
                        @auth
                            @if(Auth::user()->role === 'customer')
                                <a href="{{ route('chat.start', $companion->id) }}" style="width:36px;height:36px;border-radius:50%;border:1.5px solid rgba(16,185,129,0.3);background:rgba(16,185,129,0.06);color:#059669;display:flex;align-items:center;justify-content:center;text-decoration:none;" title="Message {{ $companion->name }}">
                                    <i class="bi bi-chat-text"></i>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="rating-stars mb-3">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=round($companion->partnerProfile?->rating??0) ? '-fill' : '' }}" style="font-size:0.85rem;"></i>@endfor
                    <span style="font-size:0.82rem;color:var(--text-muted,#94a3b8);margin-left:4px;">{{ $reviews->count() }} reviews</span>
                </div>

                @guest
                    <div style="text-align:center;padding:2rem;background:rgba(124,58,237,0.05);border-radius:16px;border:1.5px dashed rgba(124,58,237,0.2);">
                        <i class="bi bi-person-lock d-block fs-2 mb-2" style="color:#7c3aed;"></i>
                        <div class="fw-bold mb-1" style="font-size:0.95rem;">Login to Book</div>
                        <div style="font-size:0.83rem;color:var(--text-muted,#94a3b8);margin-bottom:1rem;">Sign in to schedule a session with {{ $companion->name }}</div>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('login') }}" class="btn-book-main" style="width:auto;padding:0.6rem 1.5rem;font-size:0.9rem;text-decoration:none;display:inline-block;">Login</a>
                            <a href="{{ route('register') }}" style="padding:0.6rem 1.5rem;border-radius:12px;border:1.5px solid rgba(124,58,237,0.3);color:#7c3aed;font-weight:600;font-size:0.9rem;text-decoration:none;">Register</a>
                        </div>
                    </div>
                @else
                @if(Auth::user()->role === 'customer')

                <!-- Booking wizard steps -->
                <div class="step-indicator" id="stepDots">
                    <div class="step-dot active" id="dot1"></div>
                    <div class="step-dot" id="dot2"></div>
                    <div class="step-dot" id="dot3"></div>
                    <div class="step-dot" id="dot4"></div>
                </div>

                <form action="{{ route('customer.book', $companion->id) }}" method="POST" id="bookingForm">
                    @csrf

                    <!-- STEP 1: Date -->
                    <div id="bookStep1">
                        <div class="fw-bold mb-3" style="font-size:0.95rem;">📅 Select a Date</div>
                        <!-- Calendar Header -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bold" style="font-size:0.9rem;" id="calMonth">{{ now()->format('F Y') }}</span>
                        </div>
                        <div class="calendar-grid mb-2">
                            @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d)
                                <div style="font-size:0.72rem;font-weight:700;color:var(--text-muted,#94a3b8);text-align:center;padding:4px;">{{ $d }}</div>
                            @endforeach
                            @for($d=1;$d<=now()->daysInMonth;$d++)
                                @php $isToday = $d === (int)now()->format('j'); $isPast = $d < (int)now()->format('j'); @endphp
                                <div class="cal-day {{ $isToday ? 'today' : ($isPast ? 'unavailable' : 'available') }}"
                                     onclick="{{ $isPast ? '' : "selectDate(this,'".(now()->format('Y-m-').str_pad($d,2,'0',STR_PAD_LEFT))."')" }}"
                                     @if($isPast) style="pointer-events:none;" @endif>{{ $d }}</div>
                            @endfor
                        </div>
                        <input type="hidden" name="booking_date" id="selectedDate" required>
                        <button type="button" class="btn-book-main mt-3" onclick="nextStep(1)">Continue →</button>
                    </div>

                    <!-- STEP 2: Time Slot -->
                    <div id="bookStep2" class="d-none">
                        <div class="fw-bold mb-3" style="font-size:0.95rem;">🕐 Select Time Slot</div>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach($timeSlots as $ts)
                                <button type="button" class="time-slot" onclick="selectTime(this,'{{ $ts }}')">{{ $ts }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="start_time" id="selectedTime" required>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm flex-shrink-0" style="border-radius:10px;border:1.5px solid var(--border,#e2e8f0);padding:0.65rem 1rem;font-weight:600;" onclick="prevStep(2)">← Back</button>
                            <button type="button" class="btn-book-main" onclick="nextStep(2)">Continue →</button>
                        </div>
                    </div>

                    <!-- STEP 3: Duration & Location -->
                    <div id="bookStep3" class="d-none">
                        <div class="fw-bold mb-3" style="font-size:0.95rem;">⏱ Duration & Location</div>
                        <label class="form-label" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted,#94a3b8);">Duration (hours)</label>
                        <div class="d-flex gap-2 mb-3">
                            @foreach([1,2,3,4,5,6] as $h)
                                <button type="button" class="time-slot {{ $h===1 ? 'active' : '' }}" onclick="selectHours(this,{{ $h }})">{{ $h }}h</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="duration_hours" id="selectedHours" value="1" required>

                        <label class="form-label mt-2" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted,#94a3b8);">Meetup Location</label>
                        <input type="text" name="location_address" class="form-control mb-3" placeholder="e.g. Starbucks, MG Road, Bangalore" style="border-radius:12px;" required>

                        <label class="form-label" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted,#94a3b8);">Activity / Notes (optional)</label>
                        <textarea name="description" class="form-control mb-3" rows="2" placeholder="What would you like to do?" style="border-radius:12px;"></textarea>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm flex-shrink-0" style="border-radius:10px;border:1.5px solid var(--border,#e2e8f0);padding:0.65rem 1rem;font-weight:600;" onclick="prevStep(3)">← Back</button>
                            <button type="button" class="btn-book-main" onclick="nextStep(3)">Continue →</button>
                        </div>
                    </div>

                    <!-- STEP 4: Summary & Confirm -->
                    <div id="bookStep4" class="d-none">
                        <div class="fw-bold mb-3" style="font-size:0.95rem;">✅ Booking Summary</div>
                        <div style="background:rgba(124,58,237,0.05);border:1.5px solid rgba(124,58,237,0.15);border-radius:16px;padding:1.25rem;margin-bottom:1rem;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                @if($companion->profile_picture)
                                    <img src="{{ $companion->profile_picture_url }}" style="width:42px;height:42px;border-radius:10px;object-fit:cover;" alt="">
                                @else
                                    <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#ec4899);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">{{ strtoupper(substr($companion->name,0,1)) }}</div>
                                @endif
                                <div>
                                    <div class="fw-bold" style="font-size:0.9rem;">{{ $companion->name }}</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted,#94a3b8);">{{ $companion->city?->name }}</div>
                                </div>
                            </div>
                            <table style="width:100%;font-size:0.85rem;">
                                <tr><td style="color:var(--text-muted,#94a3b8);padding:3px 0;">Date</td><td style="text-align:right;font-weight:600;" id="summaryDate">—</td></tr>
                                <tr><td style="color:var(--text-muted,#94a3b8);padding:3px 0;">Time</td><td style="text-align:right;font-weight:600;" id="summaryTime">—</td></tr>
                                <tr><td style="color:var(--text-muted,#94a3b8);padding:3px 0;">Duration</td><td style="text-align:right;font-weight:600;" id="summaryHours">—</td></tr>
                                <tr><td style="padding:3px 0;"></td></tr>
                                <tr style="border-top:1px solid rgba(0,0,0,0.08);">
                                    <td style="padding-top:8px;font-weight:700;">Total</td>
                                    <td style="padding-top:8px;text-align:right;font-weight:800;color:#7c3aed;font-size:1.1rem;" id="summaryTotal">—</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Coupon -->
                        <div class="mb-3">
                            <div class="input-group" style="border-radius:12px;overflow:hidden;">
                                <input type="text" class="form-control" name="coupon_code" id="coupon_code" placeholder="Coupon code (optional)" style="border-radius:12px 0 0 12px;">
                                <button class="btn" type="button" style="background:#7c3aed;color:#fff;font-weight:600;border-radius:0 12px 12px 0;" onclick="applyCoupon()">Apply</button>
                            </div>
                            <div id="coupon_msg" style="font-size:0.78rem;margin-top:4px;"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm flex-shrink-0" style="border-radius:10px;border:1.5px solid var(--border,#e2e8f0);padding:0.65rem 1rem;font-weight:600;" onclick="prevStep(4)">← Back</button>
                            <button type="submit" class="btn-book-main"><i class="bi bi-calendar-plus me-2"></i>Confirm Booking</button>
                        </div>
                    </div>

                </form>

                @else
                <div style="text-align:center;padding:1.5rem;background:rgba(245,158,11,0.05);border-radius:16px;border:1.5px dashed rgba(245,158,11,0.3);">
                    <i class="bi bi-exclamation-triangle-fill d-block fs-3 mb-2" style="color:#d97706;"></i>
                    <span style="font-size:0.9rem;color:var(--text-secondary,#475569);">Only customer accounts can book companions.</span>
                </div>
                @endif
                @endguest

                <!-- Trust badges -->
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(0,0,0,0.06);">
                    <div class="d-flex justify-content-around">
                        <div style="text-align:center;font-size:0.72rem;color:var(--text-muted,#94a3b8);">
                            <i class="bi bi-shield-check-fill d-block mb-1" style="font-size:1.2rem;color:#059669;"></i>Verified
                        </div>
                        <div style="text-align:center;font-size:0.72rem;color:var(--text-muted,#94a3b8);">
                            <i class="bi bi-lock-fill d-block mb-1" style="font-size:1.2rem;color:#7c3aed;"></i>Secure
                        </div>
                        <div style="text-align:center;font-size:0.72rem;color:var(--text-muted,#94a3b8);">
                            <i class="bi bi-arrow-counterclockwise d-block mb-1" style="font-size:1.2rem;color:#0891b2;"></i>Refundable
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div id="lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.9);z-index:9999;align-items:center;justify-content:center;cursor:pointer;" onclick="this.style.display='none'">
    <img id="lightboxImg" src="" style="max-width:90vw;max-height:90vh;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.5);" alt="">
</div>
@endsection

@section('scripts')
<script>
const RATE = {{ $companion->partnerProfile?->hourly_rate ?? 0 }};
let currentStep = 1;

function updateDots(step) {
    for(let i=1;i<=4;i++) {
        document.getElementById('dot'+i).classList.toggle('active', i<=step);
    }
}

function nextStep(from) {
    const validations = {
        1: () => { if(!document.getElementById('selectedDate').value) { alert('Please select a date'); return false; } return true; },
        2: () => { if(!document.getElementById('selectedTime').value) { alert('Please select a time slot'); return false; } return true; },
        3: () => { if(!document.querySelector('[name="location_address"]').value) { alert('Please enter a meetup location'); return false; } return true; },
    };
    if(validations[from] && !validations[from]()) return;
    document.getElementById('bookStep'+from).classList.add('d-none');
    document.getElementById('bookStep'+(from+1)).classList.remove('d-none');
    currentStep = from+1;
    updateDots(currentStep);
    if(from === 3) updateSummary();
}

function prevStep(current) {
    document.getElementById('bookStep'+current).classList.add('d-none');
    document.getElementById('bookStep'+(current-1)).classList.remove('d-none');
    currentStep = current-1;
    updateDots(currentStep);
}

function selectDate(el, date) {
    document.querySelectorAll('.cal-day.available, .cal-day.today').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedDate').value = date;
}

function selectTime(el, time) {
    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('selectedTime').value = time;
}

function selectHours(el, h) {
    document.querySelectorAll('#bookStep3 .time-slot').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('selectedHours').value = h;
}

function updateSummary() {
    const d = document.getElementById('selectedDate').value;
    const t = document.getElementById('selectedTime').value;
    const h = parseInt(document.getElementById('selectedHours').value) || 1;
    const total = RATE * h;
    document.getElementById('summaryDate').textContent = d ? new Date(d).toLocaleDateString('en-IN', {weekday:'short',day:'numeric',month:'short'}) : '—';
    document.getElementById('summaryTime').textContent = t || '—';
    document.getElementById('summaryHours').textContent = h + (h>1?' hours':' hour');
    document.getElementById('summaryTotal').textContent = '₹' + total.toLocaleString('en-IN');
}

function applyCoupon() {
    const code = document.getElementById('coupon_code').value.trim();
    if(!code) { document.getElementById('coupon_msg').textContent = 'Enter a coupon code'; document.getElementById('coupon_msg').style.color='#ef4444'; return; }
    const h = parseInt(document.getElementById('selectedHours').value) || 1;
    const subtotal = RATE * h;
    fetch('{{ route("coupons.validate") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({code, subtotal})
    }).then(r=>r.json()).then(d=>{
        const msg = document.getElementById('coupon_msg');
        if(d.valid) {
            msg.textContent = '✓ ' + d.message;
            msg.style.color = '#059669';
            document.getElementById('summaryTotal').textContent = '₹' + (subtotal - d.discount).toLocaleString('en-IN');
        } else {
            msg.textContent = '✗ ' + d.message;
            msg.style.color = '#ef4444';
        }
    }).catch(()=>{ document.getElementById('coupon_msg').textContent='Error validating coupon'; document.getElementById('coupon_msg').style.color='#ef4444'; });
}

function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    document.getElementById('lightboxImg').src = src;
    lb.style.display = 'flex';
}
</script>
@endsection
