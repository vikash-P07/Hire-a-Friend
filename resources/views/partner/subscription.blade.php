@extends('layouts.partner')
@section('title', 'Subscription Plans | Companion Partner')

@section('styles')
<style>
    .pricing-card {
        background: var(--surface);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-xl);
        padding: 2.5rem 2rem;
        text-align: center;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .pricing-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
        border-color: var(--brand-purple);
    }
    .pricing-card.featured {
        border-color: var(--brand-purple);
        box-shadow: var(--card-shadow-hover);
    }
    .pricing-card.featured::before {
        content: 'POPULAR CHOICE';
        position: absolute;
        top: -12px; left: 50%;
        transform: translateX(-50%);
        background: var(--brand-gradient);
        color: #fff;
        font-size: 0.68rem;
        font-weight: 800;
        padding: 4px 14px;
        border-radius: 50px;
        letter-spacing: 0.05em;
        box-shadow: 0 4px 10px var(--brand-glow);
    }
    .price-value {
        font-size: 2.8rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin: 1.5rem 0 0.5rem;
    }
    .price-interval {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .features-list {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
        text-align: left;
    }
    .features-list li {
        margin-bottom: 0.75rem;
        font-size: 0.88rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .features-list li i {
        color: var(--success);
        font-size: 1.05rem;
    }

    @media (max-width: 575.98px) {
        .pricing-card {
            padding: 1.75rem 1.25rem !important;
        }
        .price-value {
            font-size: 2.2rem !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Subscription Packages</h1>
    <p class="page-subtitle">Boost your search visibility rank and reach more customers by upgrading your listing package</p>
</div>

<!-- Active Subscription Status Banner -->
@if($activeSubscription)
    <div class="card-glass-static p-4 mb-5" style="border-left: 5px solid var(--brand-purple);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary mb-2" style="font-weight:700;font-size:0.75rem;">CURRENTLY ACTIVE</span>
                <h4 class="fw-bold mb-1" style="color:var(--text-primary);">{{ $activeSubscription->plan->name }} Plan</h4>
                <p class="text-muted mb-0 small">Your subscription was activated on {{ $activeSubscription->starts_at->format('M d, Y') }} and is valid until <strong class="text-theme-primary">{{ $activeSubscription->ends_at->format('M d, Y') }}</strong>.</p>
            </div>
            <div class="text-end">
                <span class="text-muted small">Subscription Price</span>
                <h3 class="fw-bold mb-0 text-primary">₹{{ number_format($activeSubscription->plan->price, 2) }}</h3>
            </div>
        </div>
    </div>
@endif

<!-- Pricing Grid -->
<div class="row g-4 justify-content-center">
    @forelse($plans as $plan)
        @php
            $isCurrent = $activeSubscription && $activeSubscription->plan_id === $plan->id;
            $isPro = $plan->slug === 'pro-partner';
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="pricing-card {{ $isPro ? 'featured' : '' }}">
                <div>
                    <h5 class="fw-bold mb-1 text-theme-primary" style="font-size: 1.15rem;">{{ $plan->name }}</h5>
                    <p class="text-muted small mb-0">{{ $plan->description ?? 'Boost your companion listings' }}</p>
                    
                    <div class="price-value">₹{{ number_format($plan->price, 0) }}</div>
                    <div class="price-interval">per {{ $plan->interval }}</div>

                    <!-- Features checklist -->
                    <ul class="features-list">
                        <li><i class="bi bi-patch-check-fill"></i> Higher booking priority</li>
                        <li><i class="bi bi-patch-check-fill"></i> Increased profile view limits</li>
                        @if($plan->slug === 'pro-partner')
                            <li><i class="bi bi-patch-check-fill"></i> <strong>Featured Partner Badge</strong></li>
                            <li><i class="bi bi-patch-check-fill"></i> <strong>Top Ranking in Search Results</strong></li>
                            <li><i class="bi bi-patch-check-fill"></i> Zero capping on monthly bookings</li>
                        @else
                            <li class="text-muted"><i class="bi bi-check" style="color:var(--text-muted);"></i> Normal search visibility</li>
                            <li class="text-muted"><i class="bi bi-check" style="color:var(--text-muted);"></i> Up to 10 bookings per month</li>
                        @endif
                    </ul>
                </div>

                <div>
                    @if($isCurrent)
                        <button class="btn btn-brand w-100 py-2.5" style="background:var(--success);box-shadow:none;cursor:default;" disabled>
                            <i class="bi bi-check-circle-fill me-2"></i>Active Package
                        </button>
                    @else
                        <form action="{{ route('partner.subscription.subscribe', $plan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-brand w-100 py-2.5">
                                @if($plan->price == 0)
                                    Activate Free Plan
                                @else
                                    Subscribe Now
                                @endif
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-slash-circle d-block fs-1 mb-2"></i>
            <span>No subscription packages configured by administrators.</span>
        </div>
    @endforelse
</div>

@endsection
