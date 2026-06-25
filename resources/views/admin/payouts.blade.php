@extends('layouts.admin')
@section('title', 'Partner Withdrawal Payouts - Admin Console')

@section('content')
<div class="portal-card">
    <h4 class="fw-bold mb-4 text-theme-primary">Partner Withdrawal Payouts</h4>

    <!-- Search & Filters -->
    <form action="{{ route('admin.payouts') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="all">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' || !request()->has('status') ? 'selected' : '' }}>Pending Approval</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing / Sent</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved &amp; Cleared</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected / Flagged</option>
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
                    <th>Partner</th>
                    <th>Amount</th>
                    <th>Payout Channel</th>
                    <th>Channel Details</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $p)
                    <tr>
                        <td>#{{ $p->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $p->partner->profile_picture_url }}" alt="{{ $p->partner->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-theme-primary">{{ $p->partner->name }}</div>
                                    <small class="text-muted">{{ $p->partner->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold text-theme-primary">₹{{ number_format($p->amount, 2) }}</td>
                        <td>
                            @if ($p->payout_method === 'upi')
                                <span class="badge bg-info text-theme-primary px-2.5 py-1.5"><i class="bi bi-qr-code me-1"></i>UPI Payout</span>
                            @else
                                <span class="badge bg-secondary px-2.5 py-1.5"><i class="bi bi-bank me-1"></i>Bank Transfer</span>
                            @endif
                        </td>
                        <td class="small">
                            @if ($p->payout_method === 'upi')
                                <div>UPI ID: <code>{{ $p->upi_id }}</code></div>
                            @else
                                <div class="fw-semibold text-theme-primary">{{ $p->bank_name }}</div>
                                <div class="text-muted">A/c: {{ $p->bank_account_number }} ({{ $p->bank_ifsc }})</div>
                                <div class="text-muted">Holder: {{ $p->bank_holder_name }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($p->status === 'pending')
                                <span class="badge bg-warning text-theme-primary px-2.5 py-1.5">Pending</span>
                            @elseif ($p->status === 'processing')
                                <span class="badge bg-primary px-2.5 py-1.5">Processing</span>
                            @elseif ($p->status === 'approved')
                                <span class="badge bg-success px-2.5 py-1.5">Completed</span>
                            @elseif ($p->status === 'rejected')
                                <span class="badge bg-danger px-2.5 py-1.5">Rejected</span>
                            @else
                                <span class="badge bg-secondary px-2.5 py-1.5">{{ ucfirst($p->status) }}</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end text-nowrap">
                            @if ($p->status === 'pending')
                                <form action="{{ route('admin.payouts.action', [$p->id, 'process']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 me-1" style="font-size: 0.78rem;">Process</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" style="font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}">Reject</button>
                            @elseif ($p->status === 'processing')
                                <form action="{{ route('admin.payouts.action', [$p->id, 'approve']) }}" method="POST" class="d-inline" onsubmit="return confirm('Complete this payout? Confirm that funds have been sent successfully.');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-2.5 me-1" style="font-size: 0.78rem;">Approve &amp; Pay</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" style="font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id }}">Reject</button>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No withdrawal payout requests in this queue.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @php
        $payouts->appends(request()->query());
    @endphp
    @if ($payouts->hasPages())
        <nav class="d-flex justify-content-center mt-4" aria-label="Table navigation">
            <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($payouts->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link rounded-pill px-3" href="{{ $payouts->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                    </li>
                @endif

                {{-- Pagination Pages --}}
                @foreach ($payouts->getUrlRange(max(1, $payouts->currentPage() - 2), min($payouts->lastPage(), $payouts->currentPage() + 2)) as $page => $url)
                    @if ($page == $payouts->currentPage())
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
                @if ($payouts->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-pill px-3" href="{{ $payouts->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
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

<!-- Rejection modals for capturing rejection notes -->
@foreach($payouts as $p)
    @if(in_array($p->status, ['pending', 'processing']))
        <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 18px;">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-theme-primary"><i class="bi bi-x-circle text-danger me-2"></i>Reject Withdrawal Payout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.payouts.action', [$p->id, 'reject']) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <p class="text-muted small">Please explain why you are rejecting the withdrawal request of <strong>₹{{ number_format($p->amount, 2) }}</strong> for <strong>{{ $p->partner->name }}</strong>. The partner will be notified of this reason.</p>
                            <div class="mb-3">
                                <label class="form-label text-theme-primary fw-semibold small">Reason / Internal Notes</label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="Enter rejection reason here..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Reject Payout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
