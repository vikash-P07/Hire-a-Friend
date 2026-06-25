@extends('layouts.partner')
@section('title', 'Earnings & Payouts | Companion Partner')

@section('styles')
<style>
    .earnings-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-lg);
        padding: 2rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    html.dark .earnings-hero {
        background: linear-gradient(135deg, #312e81 0%, #1e1b4b 100%);
    }
    .earnings-hero::before {
        content: '';
        position: absolute;
        right: -60px; top: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .earnings-hero::after {
        content: '';
        position: absolute;
        right: 100px; bottom: -40px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }

    .earnings-stat {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }
    .earnings-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-shadow-hover);
    }
    .earnings-stat-icon {
        width: 48px; height: 48px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 0.75rem;
    }
    .earnings-stat-val {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }
    .earnings-stat-lbl {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.35rem;
    }
    .payment-tab-content {
        display: none;
    }
    .payment-tab-content.active {
        display: block;
    }

    @media (max-width: 575.98px) {
        .earnings-hero {
            padding: 1.5rem 1.25rem !important;
            margin-bottom: 1.25rem !important;
        }
        .earnings-hero .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 1.25rem !important;
        }
        .earnings-hero .btn-brand, .earnings-hero .btn {
            width: 100% !important;
            text-align: center !important;
        }
        .earnings-hero div[style*="font-size:2.8rem"] {
            font-size: 2.2rem !important;
        }
    }

    @media (max-width: 767.98px) {
        .earnings-stat {
            padding: 1rem !important;
            height: 100%;
        }
        .earnings-stat-icon {
            width: 40px !important;
            height: 40px !important;
            border-radius: 10px !important;
            font-size: 1.1rem !important;
            margin-bottom: 0.5rem !important;
        }
        .earnings-stat-val {
            font-size: 1.25rem !important;
        }
        .earnings-stat-lbl {
            font-size: 0.72rem !important;
        }
    }
</style>
@endsection

@section('content')
@php $profile = $user->companionProfile; @endphp
<div class="page-header">
    <h1 class="page-title">Earnings & Payouts</h1>
    <p class="page-subtitle">Track your companion income, platform fees, and withdrawal submissions</p>
</div>

<!-- Earnings Hero Card -->
<div class="earnings-hero">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:2;">
        <div>
            <div style="font-size:0.75rem;opacity:0.65;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Withdrawable Balance</div>
            <div style="font-size:2.8rem;font-weight:800;margin:8px 0;line-height:1;">₹{{ number_format($stats['withdrawable'], 2) }}</div>
            <div style="font-size:0.82rem;opacity:0.65;margin-top:0.5rem;">
                <i class="bi bi-bank me-1"></i> Prefilled to: **{{ $profile->bank_name ?? 'Not set' }}** ({{ $profile->bank_account_number ? substr($profile->bank_account_number, -4) : '...' }})
            </div>
        </div>
        <div>
            @if($stats['withdrawable'] >= 500)
                <button class="btn btn-brand" style="background:#fff;color:var(--brand-purple)!important;font-weight:700;border:none;border-radius:var(--radius-md);padding:0.75rem 1.75rem;" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                    <i class="bi bi-arrow-down-circle me-2"></i>Withdraw Earnings
                </button>
            @else
                <button class="btn btn-brand" style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.5)!important;border:none;border-radius:var(--radius-md);padding:0.75rem 1.75rem;cursor:not-allowed;" disabled title="Minimum withdrawal amount is ₹500">
                    <i class="bi bi-arrow-down-circle me-2"></i>Withdraw (Min ₹500)
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-4">
        <div class="earnings-stat">
            <div class="earnings-stat-icon" style="background:rgba(16,185,129,0.1);">
                <i class="bi bi-wallet2" style="color:#059669;"></i>
            </div>
            <div class="earnings-stat-val">₹{{ number_format($stats['total_earnings'], 2) }}</div>
            <div class="earnings-stat-lbl">Total Net Revenue</div>
        </div>
    </div>
    <div class="col-6 col-lg-4">
        <div class="earnings-stat">
            <div class="earnings-stat-icon" style="background:rgba(124,58,237,0.1);">
                <i class="bi bi-cash-stack" style="color:#7c3aed;"></i>
            </div>
            <div class="earnings-stat-val">₹{{ number_format($stats['withdrawn'], 2) }}</div>
            <div class="earnings-stat-lbl">Total Withdrawn</div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="earnings-stat">
            <div class="earnings-stat-icon" style="background:rgba(245,158,11,0.1);">
                <i class="bi bi-hourglass-split" style="color:#d97706;"></i>
            </div>
            <div class="earnings-stat-val">₹{{ number_format($stats['pending_earnings'], 2) }}</div>
            <div class="earnings-stat-lbl">Pending Earnings</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-glass-static p-4 h-100">
            <h5 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Monthly Cleared Earnings Growth</h5>
            <div style="position: relative; height: 260px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-glass-static p-4 h-100">
            <h5 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-pie-chart me-2 text-primary"></i>Earnings Breakdown</h5>
            <div style="position: relative; height: 260px; display: flex; justify-content: center; align-items: center;">
                <canvas id="statusChart" style="max-height: 220px; max-width: 220px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Earnings Breakdown Table -->
<div class="card-glass-static p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold mb-0" style="color:var(--text-primary);"><i class="bi bi-receipt me-2" style="color:var(--brand-purple);"></i>Earnings Log</h5>
    </div>

    @if($earnings->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-wallet2 d-block mb-2 fs-2"></i>
            <span>No earnings recorded yet. Booking completions will trigger earnings payout.</span>
        </div>
    @else
        <div class="table-responsive d-none d-md-block">
            <table class="c-table">
                <thead>
                    <tr>
                        <th>Client / Booking</th>
                        <th>Session Date</th>
                        <th>Gross Payout</th>
                        <th>Commission</th>
                        <th>Net Earning</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($earnings as $e)
                        <tr>
                            <td>
                                <div class="fw-semibold text-theme-primary">{{ $e->booking->customer->name }}</div>
                                <small class="text-muted">{{ $e->booking->duration_hours }} hr session</small>
                            </td>
                            <td>{{ $e->booking->booking_date->format('d M Y') }}</td>
                            <td>₹{{ number_format($e->total_amount, 2) }}</td>
                            <td class="text-danger">-₹{{ number_format($e->commission_amount, 2) }}</td>
                            <td><span class="fw-bold text-success">₹{{ number_format($e->net_amount, 2) }}</span></td>
                            <td><span class="booking-badge badge-{{ $e->status }}">{{ ucfirst($e->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile list cards for Earnings Log -->
        <div class="d-md-none px-2 pb-2">
            @foreach($earnings as $e)
                <div class="pb-3 mb-3 border-bottom" style="border-color:var(--border-light)!important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold text-theme-primary" style="font-size:0.9rem;">{{ $e->booking->customer->name }}</span>
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $e->booking->duration_hours }} hr session</div>
                        </div>
                        <span class="booking-badge badge-{{ $e->status }}">{{ ucfirst($e->status) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary">
                        <div>
                            <span class="text-muted">Session Date:</span> {{ $e->booking->booking_date->format('d M Y') }}
                        </div>
                        <div>
                            <span class="text-muted">Net:</span> <strong class="text-success">₹{{ number_format($e->net_amount, 2) }}</strong>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1">
                        <span>Gross: ₹{{ number_format($e->total_amount, 2) }}</span>
                        <span class="text-danger">Fee: -₹{{ number_format($e->commission_amount, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Withdrawal History Table -->
<div class="card-glass-static p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold mb-0" style="color:var(--text-primary);"><i class="bi bi-clock-history me-2" style="color:var(--brand-purple);"></i>Withdrawal Payout History</h5>
    </div>

    @if($withdrawals->isEmpty())
        <div class="text-center py-4 text-muted">
            <i class="bi bi-arrow-left-right d-block mb-2 fs-3"></i>
            <span>No withdrawal history found.</span>
        </div>
    @else
        <div class="table-responsive d-none d-md-block">
            <table class="c-table">
                <thead>
                    <tr>
                        <th>Requested On</th>
                        <th>Amount</th>
                        <th>Payout Details</th>
                        <th>Status</th>
                        <th>Processed On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $w)
                        <tr>
                            <td>{{ $w->created_at->format('d M Y H:i') }}</td>
                            <td><span class="fw-bold text-theme-primary">₹{{ number_format($w->amount, 2) }}</span></td>
                            <td class="small">
                                @if($w->payout_method === 'upi')
                                    <div>UPI Transfer</div>
                                    <div class="text-muted">ID: {{ $w->upi_id }}</div>
                                @else
                                    <div>{{ $w->bank_name ?? 'Bank Transfer' }}</div>
                                    <div class="text-muted">A/c: ****{{ $w->bank_account_number ? substr($w->bank_account_number, -4) : '...' }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="booking-badge badge-{{ $w->status }}">{{ ucfirst($w->status) }}</span>
                            </td>
                            <td class="small text-muted">
                                @if($w->status === 'approved' && $w->processed_at)
                                    {{ $w->processed_at->format('d M Y') }}
                                @elseif($w->status === 'rejected' && $w->notes)
                                    <span class="text-danger" title="{{ $w->notes }}">{{ \Illuminate\Support\Str::limit($w->notes, 30) }}</span>
                                @else
                                    —
                                	@endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile list cards for Payout History -->
        <div class="d-md-none px-2 pb-2">
            @foreach($withdrawals as $w)
                <div class="pb-3 mb-3 border-bottom" style="border-color:var(--border-light)!important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold text-theme-primary" style="font-size:0.9rem;">₹{{ number_format($w->amount, 2) }}</span>
                            <div class="text-muted small" style="font-size:0.75rem;">{{ $w->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <span class="booking-badge badge-{{ $w->status }}">{{ ucfirst($w->status) }}</span>
                    </div>
                    <div class="small text-secondary mt-1">
                        @if($w->payout_method === 'upi')
                            <div><i class="bi bi-wallet2 me-1"></i>UPI Transfer &middot; <span class="text-muted">{{ $w->upi_id }}</span></div>
                        @else
                            <div><i class="bi bi-bank me-1"></i>{{ $w->bank_name ?? 'Bank Transfer' }} &middot; <span class="text-muted">A/c ****{{ $w->bank_account_number ? substr($w->bank_account_number, -4) : '...' }}</span></div>
                        @endif
                    </div>
                    @if($w->status === 'approved' && $w->processed_at)
                        <div class="small text-muted mt-1">Processed: {{ $w->processed_at->format('d M Y') }}</div>
                    @elseif($w->status === 'rejected' && $w->notes)
                        <div class="small text-danger mt-1">Reason: {{ $w->notes }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:var(--surface);border:1px solid var(--border);border-radius:20px;text-align:left;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color:var(--text-primary);"><i class="bi bi-bank me-2 text-primary"></i>Withdraw Funds</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('partner.earnings.withdraw') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-theme-primary">Payout Option</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payout_method" id="method_bank" value="bank_transfer" checked onclick="togglePayoutFields('bank')">
                                <label class="form-check-label text-theme-primary" for="method_bank">
                                    Bank Transfer
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payout_method" id="method_upi" value="upi" onclick="togglePayoutFields('upi')">
                                <label class="form-check-label text-theme-primary" for="method_upi">
                                    UPI Transfer
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details Panel -->
                    <div id="bank_details_panel">
                        <p class="text-muted small mb-2">Your funds will be deposited to your verified onboarding bank details:</p>
                        <div class="p-3 mb-4 rounded-3 border" style="background:var(--surface-2);border-color:var(--border)!important;font-size:0.88rem;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Holder:</span>
                                <strong class="text-theme-primary">{{ $profile->bank_holder_name ?? 'Not set' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Bank Name:</span>
                                <strong class="text-theme-primary">{{ $profile->bank_name ?? 'Not set' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Account:</span>
                                <strong class="text-theme-primary">{{ $profile->bank_account_number ?? 'Not set' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">IFSC:</span>
                                <strong class="text-theme-primary">{{ $profile->bank_ifsc ?? 'Not set' }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- UPI Details Panel -->
                    <div id="upi_details_panel" class="payment-tab-content">
                        <p class="text-muted small mb-3">Your funds will be processed using your digital UPI Address:</p>
                        <div class="mb-4">
                            <label class="form-label text-theme-primary small fw-semibold">UPI ID / VPA</label>
                            <input type="text" name="upi_id" class="form-control" placeholder="e.g. mobile@ybl or username@paytm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-theme-primary small fw-semibold">Withdrawal Amount (₹)</label>
                        <input type="number" name="amount" class="form-control" min="500" max="{{ $stats['withdrawable'] }}" placeholder="Enter amount to withdraw (Min ₹500)" required>
                        <div class="form-text text-muted">Maximum withdrawable: ₹{{ number_format($stats['withdrawable'], 2) }}</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-brand px-4 py-2" style="border-radius:10px;border:none;cursor:pointer;">Confirm Withdrawal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function togglePayoutFields(type) {
        if (type === 'bank') {
            document.getElementById('bank_details_panel').style.display = 'block';
            document.getElementById('upi_details_panel').classList.remove('active');
        } else {
            document.getElementById('bank_details_panel').style.display = 'none';
            document.getElementById('upi_details_panel').classList.add('active');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const isDark = document.documentElement.classList.contains('dark');
        const labelColor = isDark ? '#94a3b8' : '#64748b';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)';

        // 1. Monthly Revenue Chart (Bar & Line Combo)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [
                    {
                        label: 'Cleared Revenue (₹)',
                        data: {!! json_encode($monthlyRevenue) !!},
                        borderColor: '#7c3aed',
                        backgroundColor: 'rgba(124, 58, 237, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: labelColor, font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: labelColor, font: { family: 'Plus Jakarta Sans' } }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: { color: labelColor, font: { family: 'Plus Jakarta Sans' } }
                    }
                }
            }
        });

        // 2. Earnings Status Ratio Chart (Pie/Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($statusCounts)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($statusCounts)) !!},
                    backgroundColor: [
                        '#10b981', // Cleared (green)
                        '#f59e0b', // Pending (yellow)
                        '#ef4444'  // Cancelled (red)
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: labelColor, font: { family: 'Plus Jakarta Sans', weight: '600' } }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
