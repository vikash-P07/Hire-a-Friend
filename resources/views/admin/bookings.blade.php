@extends('layouts.admin')
@section('title', 'Booking Management - Admin Console')

@section('content')
<div class="portal-card">
                <h4 class="fw-bold mb-4 text-theme-primary">Platform Bookings Directory</h4>

                <!-- Advanced Filters -->
                <form action="{{ route('admin.bookings') }}" method="GET" class="row g-2 mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-theme-card border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Client, Companion or ID..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Companion</th>
                                <th>Meetup Date</th>
                                <th>Duration</th>
                                <th>Gross Total</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td>#{{ $b->id }}</td>
                                    <td><div class="fw-bold text-theme-primary">{{ $b->customer->name }}</div></td>
                                    <td>{{ $b->partner->name }}</td>
                                    <td>{{ $b->booking_date->format('M d, Y') }}</td>
                                    <td>{{ $b->duration_hours }} hrs</td>
                                    <td class="fw-bold text-success">₹{{ number_format($b->total_amount) }}</td>
                                    <td>
                                        <span class="badge-status badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $b->id }}" style="font-size: 0.75rem;">
                                                Details
                                            </button>
                                            
                                            @if($b->status === 'pending')
                                                <form action="{{ route('admin.bookings.action', [$b->id, 'confirm']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-2.5" style="font-size: 0.75rem;">Approve</button>
                                                </form>
                                            @endif

                                            @if(in_array($b->status, ['pending', 'approved']))
                                                <form action="{{ route('admin.bookings.action', [$b->id, 'cancel']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" style="font-size: 0.75rem;">Cancel</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">No booking reservations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Professional Custom Pagination -->
                @if ($bookings->hasPages())
                    <nav class="d-flex justify-content-center mt-4" aria-label="Table navigation">
                        <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                            {{-- Previous Page Link --}}
                            @if ($bookings->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $bookings->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                                </li>
                            @endif

                            {{-- Pagination Pages --}}
                            @foreach ($bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)) as $page => $url)
                                @if ($page == $bookings->currentPage())
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
                            @if ($bookings->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $bookings->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
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

<!-- Modal Dialogs for details and dispute processing -->
@foreach($allBookingsList as $b)
    <div class="modal fade" id="bookingModal{{ $b->id }}" tabindex="-1" aria-labelledby="bookingModalLabel{{ $b->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="bookingModalLabel{{ $b->id }}"><i class="bi bi-calendar-check text-primary me-2"></i>Booking Details (#{{ $b->id }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold mb-3 text-theme-primary">Meetup Logistics</h6>
                            <table class="table table-sm table-borderless small">
                                <tr><td class="text-muted">Client Name:</td><td class="text-theme-primary fw-medium">{{ $b->customer->name }}</td></tr>
                                <tr><td class="text-muted">Companion Name:</td><td class="text-theme-primary">{{ $b->partner->name }}</td></tr>
                                <tr><td class="text-muted">Meetup Date:</td><td class="text-theme-primary">{{ $b->booking_date->format('M d, Y') }}</td></tr>
                                <tr><td class="text-muted">Start Time:</td><td class="text-theme-primary">{{ $b->start_time }}</td></tr>
                                <tr><td class="text-muted">Location:</td><td class="text-theme-primary">{{ $b->location_address }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-theme-primary">Pricing &amp; Commission</h6>
                            <table class="table table-sm table-borderless small">
                                <tr><td class="text-muted">Hourly Rate:</td><td class="text-theme-primary">₹{{ number_format($b->hourly_rate) }}/hr</td></tr>
                                <tr><td class="text-muted">Duration:</td><td class="text-theme-primary">{{ $b->duration_hours }} hours</td></tr>
                                <tr><td class="text-muted">Total Amount Charged:</td><td class="text-theme-primary fw-bold text-success">₹{{ number_format($b->total_amount) }}</td></tr>
                                <tr><td class="text-muted">Platform Commission (15%):</td><td class="text-theme-primary fw-bold text-primary">₹{{ number_format($b->total_amount * 0.15) }}</td></tr>
                                <tr><td class="text-muted">Partner Share:</td><td class="text-theme-primary">₹{{ number_format($b->total_amount * 0.85) }}</td></tr>
                            </table>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <h6 class="fw-bold mb-2 text-theme-primary">Booking Progress Timeline</h6>
                            <div class="timeline-steps">
                                <div class="timeline-step">
                                    <i class="bi bi-circle-fill text-success"></i>
                                    <div class="small fw-bold">Pending</div>
                                </div>
                                <div class="timeline-step">
                                    <i class="bi {{ in_array($b->status, ['approved', 'completed']) ? 'bi-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                    <div class="small fw-bold">Confirmed</div>
                                </div>
                                <div class="timeline-step">
                                    <i class="bi {{ $b->status === 'completed' ? 'bi-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                    <div class="small fw-bold">Completed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if($b->status === 'completed')
                        <form action="{{ route('admin.bookings.action', [$b->id, 'refund']) }}" method="POST" onsubmit="return confirm('Process a full refund for this completed booking?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning rounded-pill px-3"><i class="bi bi-arrow-counterclockwise me-1"></i>Issue Full Refund</button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
