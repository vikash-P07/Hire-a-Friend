@extends('layouts.partner')
@section('title', 'Booking Requests | Companion Partner')

@section('styles')
<style>
    .booking-filter-tabs .nav-link {
        background: var(--surface-2);
        color: var(--text-secondary);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.5rem 1.1rem;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .booking-filter-tabs .nav-link.active {
        background: var(--brand-gradient);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 12px var(--brand-glow);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Booking Requests</h1>
    <p class="page-subtitle">Accept, reject, or coordinate date & times for companion bookings</p>
</div>

<div class="card-glass-static p-4">
    <!-- Filter Tabs -->
    <ul class="nav nav-pills gap-2 mb-4 flex-wrap booking-filter-tabs" id="bookingFilter">
        @foreach([
            'all' => 'All Requests',
            'pending' => 'Pending',
            'approved' => 'Accepted',
            'rescheduled' => 'Rescheduled',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ] as $status => $label)
            @php
                $count = $status === 'all' ? $bookings->count() : $bookings->where('status', $status)->count();
            @endphp
            <li class="nav-item">
                <button class="nav-link {{ $status === 'all' ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#tab-{{ $status }}">
                    {{ $label }}
                    @if($count > 0)
                        <span class="badge ms-1" style="background: rgba(255,255,255,0.25); font-size: 0.72rem;">{{ $count }}</span>
                    @endif
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content">
        @foreach(['all', 'pending', 'approved', 'rescheduled', 'completed', 'cancelled'] as $status)
            <div class="tab-pane fade {{ $status === 'all' ? 'show active' : '' }}" id="tab-{{ $status }}">
                @php
                    $filteredBookings = $status === 'all' ? $bookings : $bookings->where('status', $status);
                @endphp

                @if($filteredBookings->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x d-block fs-1 mb-2"></i>
                        <span>No requests found for this status.</span>
                    </div>
                @else
                    <div class="table-responsive d-none d-md-block">
                        <table class="c-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Date & Time</th>
                                    <th>Duration</th>
                                    <th>Location</th>
                                    <th>Payout (Total)</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($filteredBookings as $b)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($b->customer->profile_picture)
                                                    <img src="{{ $b->customer->profile_picture_url }}" class="avatar" style="width:38px;height:38px;" alt="">
                                                @else
                                                    <div class="avatar-placeholder" style="width:38px;height:38px;">{{ strtoupper(substr($b->customer->name,0,1)) }}</div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold" style="color:var(--text-primary);font-size:0.9rem;">{{ $b->customer->name }}</div>
                                                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $b->customer->phone ?? 'No phone' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size:0.88rem;color:var(--text-primary);font-weight:600;">{{ $b->booking_date->format('d M Y') }}</div>
                                            <div style="font-size:0.78rem;color:var(--text-muted);">{{ date('h:i A', strtotime($b->start_time)) }}</div>
                                        </td>
                                        <td style="color:var(--text-secondary);font-size:0.88rem;">{{ $b->duration_hours }} hr{{ $b->duration_hours > 1 ? 's' : '' }}</td>
                                        <td style="font-size:0.82rem;color:var(--text-muted);max-width:160px;" title="{{ $b->location_address }}">
                                            <i class="bi bi-geo-alt me-1 text-danger"></i>{{ Str::limit($b->location_address, 25) }}
                                        </td>
                                        <td><span class="fw-bold" style="color:var(--brand-purple);">₹{{ number_format($b->total_amount) }}</span></td>
                                        <td><span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                                @if($b->status === 'pending' || $b->status === 'rescheduled')
                                                    <form action="{{ route('partner.booking.action', [$b->id, 'accept']) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-brand py-1 px-3" style="font-size:0.75rem;"><i class="bi bi-check-circle me-1"></i>Accept</button>
                                                    </form>
                                                    
                                                    <!-- Trigger Reschedule Modal -->
                                                    <button type="button" class="btn btn-sm btn-outline-brand py-1 px-2" style="font-size:0.75rem;" data-bs-toggle="modal" data-bs-target="#rescheduleModal-{{ $b->id }}"><i class="bi bi-calendar-event me-1"></i>Reschedule</button>
                                                    
                                                    <form action="{{ route('partner.booking.action', [$b->id, 'reject']) }}" method="POST" onsubmit="return confirm('Reject this booking?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-surface py-1 px-2" style="font-size:0.75rem;"><i class="bi bi-x-circle me-1"></i>Reject</button>
                                                    </form>
                                                @elseif($b->status === 'approved')
                                                    <form action="{{ route('partner.booking.action', [$b->id, 'complete']) }}" method="POST" onsubmit="return confirm('Mark this booking session as completed?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-brand py-1 px-3" style="font-size:0.75rem;"><i class="bi bi-patch-check me-1"></i>Complete</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                                
                                                @if(in_array($b->status, ['approved', 'ongoing', 'completed', 'paid', 'confirmed', 'rescheduled']))
                                                    <a href="{{ route('chat.start', $b->customer->id) }}" class="btn btn-sm btn-light border py-1 px-2" style="font-size:0.75rem;" title="Message Customer">
                                                        <i class="bi bi-chat-text"></i> Chat
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Reschedule Modal -->
                                            @if($b->status === 'pending' || $b->status === 'rescheduled')
                                                <div class="modal fade" id="rescheduleModal-{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);border-radius:20px;text-align:left;">
                                                            <div class="modal-header border-0 pb-0">
                                                                 <h5 class="modal-title fw-bold" style="color:var(--text-primary);">Reschedule Booking Request</h5>
                                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('partner.bookings.reschedule', $b->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Suggested Date</label>
                                                                        <input type="date" name="booking_date" class="form-control" value="{{ $b->booking_date->format('Y-m-d') }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Suggested Start Time</label>
                                                                        <input type="time" name="start_time" class="form-control" value="{{ date('H:i', strtotime($b->start_time)) }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-0 pt-0">
                                                                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;">Send Suggestion</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile card view visible on mobile only (<768px) -->
                    <div class="d-md-none">
                        @foreach($filteredBookings as $b)
                            <div class="card-glass p-3 mb-3" style="border: 1px solid var(--border-light); border-radius: var(--radius-md);">
                                <div class="d-flex align-items-center gap-3 border-bottom pb-3 mb-3">
                                    @if($b->customer->profile_picture)
                                        <img src="{{ $b->customer->profile_picture_url }}" class="avatar" style="width:48px;height:48px;" alt="">
                                    @else
                                        <div class="avatar-placeholder" style="width:48px;height:48px;font-size:1.15rem;border-radius:50%;background:var(--brand-gradient);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">{{ strtoupper(substr($b->customer->name,0,1)) }}</div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold" style="color:var(--text-primary);font-size:0.95rem;">{{ $b->customer->name }}</div>
                                        <div style="font-size:0.78rem;color:var(--text-muted);"><i class="bi bi-telephone me-1"></i>{{ $b->customer->phone ?? 'No phone' }}</div>
                                    </div>
                                    <span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                                </div>

                                <div class="row g-2 mb-3 small text-secondary">
                                    <div class="col-6">
                                        <i class="bi bi-calendar3 me-1 text-primary"></i><strong>Date:</strong><br>
                                        {{ $b->booking_date->format('d M Y') }}<br>({{ date('h:i A', strtotime($b->start_time)) }})
                                    </div>
                                    <div class="col-6">
                                        <i class="bi bi-clock me-1 text-primary"></i><strong>Duration & Payout:</strong><br>
                                        {{ $b->duration_hours }} hrs &middot; <span class="fw-bold text-success">₹{{ number_format($b->total_amount) }}</span>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <i class="bi bi-geo-alt me-1 text-danger"></i><strong>Location:</strong><br>
                                        {{ $b->location_address }}
                                    </div>
                                </div>

                                <!-- Actions row -->
                                @if($b->status === 'pending' || $b->status === 'rescheduled')
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('partner.booking.action', [$b->id, 'accept']) }}" method="POST" class="flex-grow-1 mb-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-brand w-100 py-2" style="font-size:0.8rem;"><i class="bi bi-check-circle me-1"></i>Accept</button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-brand flex-grow-1 py-2" style="font-size:0.8rem;" data-bs-toggle="modal" data-bs-target="#mobile-rescheduleModal-{{ $b->id }}"><i class="bi bi-calendar-event me-1"></i>Reschedule</button>
                                        
                                        <form action="{{ route('partner.booking.action', [$b->id, 'reject']) }}" method="POST" onsubmit="return confirm('Reject this booking?')" class="flex-grow-1 mb-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-surface w-100 py-2" style="font-size:0.8rem;"><i class="bi bi-x-circle me-1"></i>Reject</button>
                                        </form>
                                    </div>
                                @elseif($b->status === 'approved')
                                    <form action="{{ route('partner.booking.action', [$b->id, 'complete']) }}" method="POST" onsubmit="return confirm('Mark this booking session as completed?')" class="mb-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-brand w-100 py-2.5" style="font-size:0.8rem;"><i class="bi bi-patch-check me-1"></i>Complete Session</button>
                                    </form>
                                @endif

                                @if(in_array($b->status, ['approved', 'ongoing', 'completed', 'paid', 'confirmed', 'rescheduled']))
                                    <a href="{{ route('chat.start', $b->customer->id) }}" class="btn btn-light border w-100 py-2 mt-2" style="font-size:0.8rem; border-radius:8px; display:block; text-align:center;">
                                        <i class="bi bi-chat-text"></i> Message Customer
                                    </a>
                                @endif

                                <!-- Include Modal for Reschedule inside card loop so it is accessible on mobile too -->
                                @if($b->status === 'pending' || $b->status === 'rescheduled')
                                    <div class="modal fade" id="mobile-rescheduleModal-{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);border-radius:20px;text-align:left;">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-bold" style="color:var(--text-primary);">Reschedule Booking Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('partner.bookings.reschedule', $b->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Suggested Date</label>
                                                            <input type="date" name="booking_date" class="form-control" value="{{ $b->booking_date->format('Y-m-d') }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Suggested Start Time</label>
                                                            <input type="time" name="start_time" class="form-control" value="{{ date('H:i', strtotime($b->start_time)) }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;">Send Suggestion</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
    // Tab pill active styles handling
    document.querySelectorAll('#bookingFilter .nav-link').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#bookingFilter .nav-link').forEach(b => {
                b.style.background = 'var(--surface-2)';
                b.style.color = 'var(--text-secondary)';
                b.style.border = '1px solid var(--border)';
            });
            this.style.background = 'linear-gradient(135deg, #7c3aed 0%, #ec4899 100%)';
            this.style.color = '#fff';
            this.style.border = 'none';
        });
    });
</script>
@endsection
