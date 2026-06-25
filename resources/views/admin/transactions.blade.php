@extends('layouts.admin')
@section('title', 'Platform Transactions Log - Admin Console')

@section('content')
<div class="portal-card">
    <h4 class="fw-bold mb-4 text-theme-primary">Platform Transactions Log</h4>

    <!-- Search & Filters -->
    <form action="{{ route('admin.transactions') }}" method="GET" class="row g-2 mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-theme-card border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search by Transaction ID or Customer Name..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
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
                    <th>Txn ID</th>
                    <th>Customer</th>
                    <th>Payable Booking</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Processed At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td><code class="text-theme-primary fw-bold">{{ $p->transaction_id }}</code></td>
                        <td><div class="fw-bold text-theme-primary">{{ $p->user->name }}</div><small class="text-muted">{{ $p->user->email }}</small></td>
                        <td>
                            @if ($p->payable_type === 'App\Models\Booking' || $p->payable_type === 'Booking')
                                <a href="#" class="text-decoration-none fw-semibold">Booking #{{ $p->payable_id }}</a>
                            @else
                                <span class="text-muted">{{ class_basename($p->payable_type) }} #{{ $p->payable_id }}</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">₹{{ number_format($p->amount, 2) }}</td>
                        <td style="text-transform: uppercase; font-size: 0.85rem;"><span class="badge bg-secondary px-2.5 py-1.5">{{ str_replace('_', ' ', $p->payment_method) }}</span></td>
                        <td>
                            @if ($p->payment_status === 'completed')
                                <span class="badge bg-success px-2.5 py-1.5">Completed</span>
                            @elseif ($p->payment_status === 'refunded')
                                <span class="badge bg-warning text-theme-primary px-2.5 py-1.5">Refunded</span>
                            @elseif ($p->payment_status === 'failed')
                                <span class="badge bg-danger px-2.5 py-1.5">Failed</span>
                            @else
                                <span class="badge bg-secondary px-2.5 py-1.5">{{ ucfirst($p->payment_status) }}</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $p->created_at->format('M d, Y h:i A') }}</td>
                        <td class="text-end">
                            @if ($p->payment_status === 'completed')
                                <form action="{{ route('admin.transactions.refund', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to issue a full manual refund for this transaction?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3" style="font-size: 0.78rem;">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Refund
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" disabled style="font-size: 0.78rem;">Refunded</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No transactions found on the platform.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @php
        $payments->appends(request()->query());
    @endphp
    @if ($payments->hasPages())
        <nav class="d-flex justify-content-center mt-4" aria-label="Table navigation">
            <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($payments->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link rounded-pill px-3" href="{{ $payments->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                    </li>
                @endif

                {{-- Pagination Pages --}}
                @foreach ($payments->getUrlRange(max(1, $payments->currentPage() - 2), min($payments->lastPage(), $payments->currentPage() + 2)) as $page => $url)
                    @if ($page == $payments->currentPage())
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
                @if ($payments->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-pill px-3" href="{{ $payments->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
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
@endsection
