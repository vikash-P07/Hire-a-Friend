@extends('layouts.customer')
@section('title', 'Safety Center | Hire-a-Friend')

@section('styles')
<style>
    .safety-hero {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        border-radius: var(--radius-lg);
        padding: 2rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .safety-hero::before {
        content: '';
        position: absolute;
        right: -60px; top: -60px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }

    .safety-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        height: 100%;
    }
    .safety-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-shadow-hover);
    }
    .safety-card-icon {
        width: 52px; height: 52px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        margin-bottom: 1rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        transition: var(--transition);
        margin-bottom: 0.75rem;
    }
    .contact-item:hover {
        border-color: var(--brand-purple);
        background: var(--surface-2);
    }
    .contact-icon {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .tip-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-light);
    }
    .tip-item:last-child { border-bottom: none; }
    .tip-num {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: rgba(124,58,237,0.1);
        color: var(--brand-purple);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Safety Center</h1>
    <p class="page-subtitle">Your safety is our top priority. Access emergency tools and safety resources</p>
</div>

<!-- Emergency SOS Banner -->
<div class="safety-hero">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:2;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-shield-fill-check" style="font-size:1.5rem;"></i>
                <h4 class="fw-bold mb-0">Emergency Assistance</h4>
            </div>
            <p style="font-size:0.9rem;opacity:0.85;margin:0;">In case of an emergency during your meetup, use the SOS button to alert authorities and share your live location.</p>
        </div>
        <button class="btn px-4 py-3" style="background:rgba(255,255,255,0.2);color:#fff;border:2px solid rgba(255,255,255,0.4);border-radius:var(--radius-md);font-weight:800;font-size:1rem;" onclick="showComingSoon('SOS Emergency')">
            <i class="bi bi-sos me-2" style="font-size:1.2rem;"></i>SOS Emergency
        </button>
    </div>
</div>

<!-- Safety Features Grid -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="safety-card">
            <div class="safety-card-icon" style="background:rgba(239,68,68,0.1);">
                <i class="bi bi-geo-alt-fill" style="color:#ef4444;"></i>
            </div>
            <h6 class="fw-bold" style="color:var(--text-primary);">Live Location Sharing</h6>
            <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;">Share your real-time location with trusted contacts during meetups.</p>
            <button class="btn btn-sm btn-outline-brand" onclick="showComingSoon('Live Location')">Enable</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="safety-card">
            <div class="safety-card-icon" style="background:rgba(124,58,237,0.1);">
                <i class="bi bi-shield-check" style="color:#7c3aed;"></i>
            </div>
            <h6 class="fw-bold" style="color:var(--text-primary);">Safety Check-In</h6>
            <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;">Automatic safety check-in reminders during your sessions.</p>
            <button class="btn btn-sm btn-outline-brand" onclick="showComingSoon('Safety Check-In')">Set Up</button>
        </div>
    </div>
    <div class="col-md-4">
        <div class="safety-card">
            <div class="safety-card-icon" style="background:rgba(245,158,11,0.1);">
                <i class="bi bi-exclamation-triangle-fill" style="color:#d97706;"></i>
            </div>
            <h6 class="fw-bold" style="color:var(--text-primary);">Report a Problem</h6>
            <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;">Report inappropriate behavior, harassment, or safety concerns.</p>
            <button class="btn btn-sm btn-outline-brand" onclick="showComingSoon('Report')">Report</button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Emergency Contacts -->
    <div class="col-lg-6">
        <div class="card-glass-static p-4 h-100">
            <h5 class="fw-bold mb-3" style="color:var(--text-primary);"><i class="bi bi-telephone-fill me-2" style="color:var(--brand-purple);"></i>Emergency Contacts</h5>

            <div class="contact-item">
                <div class="contact-icon" style="background:rgba(239,68,68,0.1);">
                    <i class="bi bi-telephone-fill" style="color:#ef4444;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold" style="font-size:0.9rem;">Police Emergency</div>
                    <div style="font-size:0.8rem;color:var(--text-muted);">Dial 100</div>
                </div>
                <a href="tel:100" class="btn btn-sm" style="background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.2);border-radius:var(--radius-sm);font-weight:600;">Call</a>
            </div>

            <div class="contact-item">
                <div class="contact-icon" style="background:rgba(236,72,153,0.1);">
                    <i class="bi bi-heart-fill" style="color:#ec4899;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold" style="font-size:0.9rem;">Women Helpline</div>
                    <div style="font-size:0.8rem;color:var(--text-muted);">Dial 1091</div>
                </div>
                <a href="tel:1091" class="btn btn-sm" style="background:rgba(236,72,153,0.1);color:#ec4899;border:1px solid rgba(236,72,153,0.2);border-radius:var(--radius-sm);font-weight:600;">Call</a>
            </div>

            <div class="contact-item">
                <div class="contact-icon" style="background:rgba(16,185,129,0.1);">
                    <i class="bi bi-hospital" style="color:#059669;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold" style="font-size:0.9rem;">Ambulance</div>
                    <div style="font-size:0.8rem;color:var(--text-muted);">Dial 102</div>
                </div>
                <a href="tel:102" class="btn btn-sm" style="background:rgba(16,185,129,0.1);color:#059669;border:1px solid rgba(16,185,129,0.2);border-radius:var(--radius-sm);font-weight:600;">Call</a>
            </div>

            <!-- Booked Partners - Block/Report -->
            @if($bookedPartners->isNotEmpty())
            <h6 class="fw-bold mt-4 mb-3" style="color:var(--text-primary);">Recent Companions</h6>
            @foreach($bookedPartners->take(3) as $bp)
            <div class="contact-item">
                <div class="contact-icon" style="background:rgba(124,58,237,0.1);">
                    @if($bp->partner->profile_picture)
                        <img src="{{ $bp->partner->profile_picture_url }}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;" alt="">
                    @else
                        <span style="color:var(--brand-purple);font-weight:700;">{{ strtoupper(substr($bp->partner->name,0,1)) }}</span>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold" style="font-size:0.9rem;">{{ $bp->partner->name }}</div>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm" style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:0.78rem;color:var(--text-secondary);" onclick="showComingSoon('Block User')">
                        <i class="bi bi-slash-circle"></i>
                    </button>
                    <button class="btn btn-sm" style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:0.78rem;color:var(--text-secondary);" onclick="showComingSoon('Report User')">
                        <i class="bi bi-flag"></i>
                    </button>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <!-- Safety Tips -->
    <div class="col-lg-6">
        <div class="card-glass-static p-4 h-100">
            <h5 class="fw-bold mb-3" style="color:var(--text-primary);"><i class="bi bi-lightbulb me-2" style="color:var(--brand-purple);"></i>Safety Tips</h5>

            <div class="tip-item">
                <div class="tip-num">1</div>
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">Meet in Public Places</div>
                    <div style="font-size:0.82rem;color:var(--text-muted);">Always choose well-lit, public places for your first meetup.</div>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-num">2</div>
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">Share Your Plans</div>
                    <div style="font-size:0.82rem;color:var(--text-muted);">Let a friend or family member know your plans, including where and when.</div>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-num">3</div>
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">Check Reviews & Ratings</div>
                    <div style="font-size:0.82rem;color:var(--text-muted);">Always check the companion's profile, reviews, and verification status before booking.</div>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-num">4</div>
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">Trust Your Instincts</div>
                    <div style="font-size:0.82rem;color:var(--text-muted);">If something feels wrong, leave immediately. Your safety comes first.</div>
                </div>
            </div>
            <div class="tip-item">
                <div class="tip-num">5</div>
                <div>
                    <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">Keep Personal Info Private</div>
                    <div style="font-size:0.82rem;color:var(--text-muted);">Never share your home address, financial details, or personal documents.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
