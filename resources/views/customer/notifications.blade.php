@extends('layouts.customer')
@section('title', 'Notifications | Hire-a-Friend')

@section('styles')
<style>
    .notification-item {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-light);
        transition: var(--transition);
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    .notification-item:last-child {
        border-bottom: none;
    }
    .notification-item.unread {
        background: rgba(124, 58, 237, 0.02);
    }
    .notification-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .notification-text {
        flex: 1;
    }
</style>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h1 class="page-title">Notifications</h1>
        <p class="page-subtitle">Stay updated with companion bookings, messages, and platform updates</p>
    </div>
    @if($notifications->whereNull('read_at')->count() > 0)
        <span class="badge" style="background: var(--brand-gradient); color: #fff; padding: 0.5rem 1rem; border-radius: 99px;">
            {{ $notifications->whereNull('read_at')->count() }} unread
        </span>
    @endif
</div>

<div class="card-glass-static">
    @if($notifications->isEmpty())
        <div class="text-center py-5" style="color:var(--text-muted);">
            <i class="bi bi-bell-slash d-block mb-3" style="font-size:3rem; color: var(--text-muted);"></i>
            <h5 class="fw-semibold">All caught up!</h5>
            <p style="font-size:0.88rem; max-width: 360px; margin: 0 auto;">You don't have any notifications at the moment. We'll let you know when something comes up!</p>
        </div>
    @else
        <div class="d-flex flex-column">
            @foreach($notifications as $n)
                @php 
                    $nd = json_decode($n->data, true); 
                    $isUnread = is_null($n->read_at);
                @endphp
                <div class="notification-item {{ $isUnread ? 'unread' : '' }}">
                    <div class="notification-icon" style="background: {{ $isUnread ? 'rgba(124, 58, 237, 0.12)' : 'var(--surface-2)' }};">
                        <i class="bi bi-bell-fill" style="color: {{ $isUnread ? 'var(--brand-purple)' : 'var(--text-muted)' }}; font-size: 1.1rem;"></i>
                    </div>
                    <div class="notification-text">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-1">
                            <span class="fw-semibold" style="font-size: 0.95rem; color: var(--text-primary);">
                                {{ $nd['title'] ?? 'Notification Update' }}
                            </span>
                            <span style="font-size: 0.78rem; color: var(--text-muted);">
                                {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        <p style="font-size: 0.88rem; color: var(--text-secondary); margin: 0;">
                            {{ $nd['message'] ?? 'You have a new notification.' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
