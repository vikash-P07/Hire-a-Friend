@extends('layouts.customer')
@section('title', 'Reviews & Ratings | Hire-a-Friend')

@section('styles')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 0.5rem;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        font-size: 1.75rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #fbbf24;
    }
    .review-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover);
    }
    .stars-display {
        color: #fbbf24;
        display: inline-flex;
        gap: 2px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Reviews & Ratings</h1>
    <p class="page-subtitle">Share your experience with companions and read your past reviews</p>
</div>

<!-- Pending Reviews -->
@if(!$pendingReviews->isEmpty())
    <div class="card-glass-static p-4 mb-4">
        <h5 class="fw-bold mb-3" style="color: var(--text-primary);"><i class="bi bi-patch-exclamation me-2" style="color: var(--brand-purple);"></i>Pending Reviews</h5>
        <p class="text-muted style='font-size: 0.88rem;'">Help the community by sharing your feedback about your recent completed bookings.</p>
        
        <div class="row g-3 mt-1">
            @foreach($pendingReviews as $b)
                <div class="col-12 col-md-6">
                    <div class="review-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($b->partner->profile_picture)
                                    <img src="{{ $b->partner->profile_picture_url }}" alt="" class="avatar" style="width: 48px; height: 48px;">
                                @else
                                    <div class="avatar-placeholder" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                        {{ strtoupper(substr($b->partner->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-theme-primary" style="font-size: 0.95rem;">{{ $b->partner->name }}</div>
                                    <div class="text-muted" style="font-size: 0.78rem;">Session: {{ $b->booking_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            <button class="btn btn-outline-brand btn-sm px-3" data-bs-toggle="collapse" data-bs-target="#reviewForm-{{ $b->id }}">
                                Write Review
                            </button>
                        </div>

                        <!-- Review Form Collapse -->
                        <div class="collapse" id="reviewForm-{{ $b->id }}">
                            <form action="{{ route('customer.booking.review', $b->id) }}" method="POST" class="border-top pt-3 mt-3">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label d-block">Rating</label>
                                    <div class="star-rating">
                                        <input type="radio" id="star5-{{ $b->id }}" name="rating" value="5" required /><label for="star5-{{ $b->id }}"><i class="bi bi-star-fill"></i></label>
                                        <input type="radio" id="star4-{{ $b->id }}" name="rating" value="4" /><label for="star4-{{ $b->id }}"><i class="bi bi-star-fill"></i></label>
                                        <input type="radio" id="star3-{{ $b->id }}" name="rating" value="3" /><label for="star3-{{ $b->id }}"><i class="bi bi-star-fill"></i></label>
                                        <input type="radio" id="star2-{{ $b->id }}" name="rating" value="2" /><label for="star2-{{ $b->id }}"><i class="bi bi-star-fill"></i></label>
                                        <input type="radio" id="star1-{{ $b->id }}" name="rating" value="1" /><label for="star1-{{ $b->id }}"><i class="bi bi-star-fill"></i></label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="comment-{{ $b->id }}">Your Comments</label>
                                    <textarea name="comment" id="comment-{{ $b->id }}" rows="3" class="form-control" placeholder="Describe your experience with {{ $b->partner->name }}..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-surface btn-sm" data-bs-toggle="collapse" data-bs-target="#reviewForm-{{ $b->id }}">Cancel</button>
                                    <button type="submit" class="btn btn-brand btn-sm">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Reviews History -->
<div class="card-glass-static p-4">
    <h5 class="fw-bold mb-4" style="color: var(--text-primary);"><i class="bi bi-chat-left-quote me-2" style="color: var(--brand-purple);"></i>My Reviews History</h5>
    
    @if($reviews->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-star d-block mb-2" style="font-size: 2.5rem;"></i>
            <div class="fw-semibold">No reviews yet</div>
            <div style="font-size: 0.85rem;">Reviews you write will be visible here</div>
        </div>
    @else
        <div class="row g-4">
            @foreach($reviews as $r)
                <div class="col-12 col-md-6">
                    <div class="review-card">
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                            <div class="d-flex align-items-center gap-3">
                                @if($r->partner->profile_picture)
                                    <img src="{{ $r->partner->profile_picture_url }}" alt="" class="avatar" style="width: 40px; height: 40px;">
                                @else
                                    <div class="avatar-placeholder" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                        {{ strtoupper(substr($r->partner->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold" style="font-size: 0.9rem; color: var(--text-primary);">{{ $r->partner->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Reviewed on {{ $r->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="stars-display">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $r->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p style="font-size: 0.88rem; color: var(--text-secondary); margin: 0; font-style: italic;">
                            "{{ $r->comment }}"
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
