@extends('layouts.customer')
@section('title', 'Wallet | Hire-a-Friend')

@section('styles')
<style>
    .wallet-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-lg);
        padding: 2rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    html.dark .wallet-hero {
        background: linear-gradient(135deg, #312e81 0%, #1e1b4b 100%);
    }
    .wallet-hero::before {
        content: '';
        position: absolute;
        right: -60px; top: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .wallet-hero::after {
        content: '';
        position: absolute;
        right: 100px; bottom: -40px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }

    .wallet-stat {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }
    .wallet-stat:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-shadow-hover);
    }
    .wallet-stat-icon {
        width: 48px; height: 48px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 0.75rem;
    }
    .wallet-stat-val {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }
    .wallet-stat-lbl {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.35rem;
    }

    .txn-icon {
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    @media (max-width: 575.98px) {
        .wallet-hero {
            padding: 1.5rem 1.25rem !important;
        }
        .wallet-hero .d-flex.gap-2 {
            width: 100% !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        .wallet-hero .btn {
            width: 100% !important;
        }
        .wallet-stat {
            padding: 1rem !important;
            text-align: center;
        }
        .wallet-stat-icon {
            margin: 0 auto 0.5rem !important;
        }
        .wallet-stat-val {
            font-size: 1.35rem !important;
        }
    }

    .mobile-txn-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-md);
        padding: 1rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Wallet</h1>
    <p class="page-subtitle">Track your spending, transactions, and savings</p>
</div>

<!-- Wallet Hero Card -->
<div class="wallet-hero">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3" style="position:relative;z-index:2;">
        <div>
            <div style="font-size:0.75rem;opacity:0.65;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">Available Balance</div>
            <div style="font-size:2.8rem;font-weight:800;margin:8px 0;line-height:1;">₹0.00</div>
            <div style="font-size:0.82rem;opacity:0.6;margin-top:0.5rem;">
                <i class="bi bi-info-circle me-1"></i>Wallet system coming soon
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.2);border-radius:var(--radius-md);font-weight:600;font-size:0.88rem;padding:0.6rem 1.5rem;" onclick="showComingSoon('Add Money')">
                <i class="bi bi-plus-circle me-2"></i>Add Money
            </button>
            <button class="btn" style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.8);border:1px solid rgba(255,255,255,0.12);border-radius:var(--radius-md);font-size:0.88rem;padding:0.6rem 1.25rem;" onclick="showComingSoon('Withdraw')">
                <i class="bi bi-arrow-down-circle me-2"></i>Withdraw
            </button>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="wallet-stat h-100">
            <div class="wallet-stat-icon" style="background:rgba(124,58,237,0.1);">
                <i class="bi bi-credit-card" style="color:#7c3aed;"></i>
            </div>
            <div class="wallet-stat-val">₹{{ number_format($totalSpent) }}</div>
            <div class="wallet-stat-lbl">Total Spent</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="wallet-stat h-100">
            <div class="wallet-stat-icon" style="background:rgba(16,185,129,0.1);">
                <i class="bi bi-piggy-bank" style="color:#059669;"></i>
            </div>
            <div class="wallet-stat-val">₹{{ number_format($totalSaved) }}</div>
            <div class="wallet-stat-lbl">Total Saved</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="wallet-stat h-100">
            <div class="wallet-stat-icon" style="background:rgba(245,158,11,0.1);">
                <i class="bi bi-receipt" style="color:#d97706;"></i>
            </div>
            <div class="wallet-stat-val">{{ $bookings->where('status','completed')->count() }}</div>
            <div class="wallet-stat-lbl">Transactions</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="wallet-stat h-100">
            <div class="wallet-stat-icon" style="background:rgba(6,182,212,0.1);">
                <i class="bi bi-gift" style="color:#0891b2;"></i>
            </div>
            <div class="wallet-stat-val">0</div>
            <div class="wallet-stat-lbl">Reward Points</div>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div class="card-glass-static p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold mb-0" style="color:var(--text-primary);"><i class="bi bi-clock-history me-2" style="color:var(--brand-purple);"></i>Transaction History</h5>
    </div>

    @if($bookings->where('status','completed')->isEmpty() && $bookings->where('status','cancelled')->isEmpty())
        <div class="text-center py-5" style="color:var(--text-muted);">
            <i class="bi bi-wallet2 d-block mb-2" style="font-size:2.5rem;"></i>
            <div class="fw-semibold mb-1">No transactions yet</div>
            <div style="font-size:0.85rem;">Your booking payments will appear here</div>
            <a href="{{ route('companions.index') }}" class="btn-brand px-4 py-2 mt-3" style="border-radius:10px;border:none;cursor:pointer;display:inline-block;text-decoration:none;">
                Find a Companion
            </a>
        </div>
    @else
        <!-- Desktop Layout -->
        <div class="table-responsive d-none d-md-block">
            <table class="c-table">
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Discount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings->whereIn('status', ['completed','cancelled'])->take(20) as $b)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="txn-icon" style="background:{{ $b->status === 'completed' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }};">
                                    <i class="bi {{ $b->status === 'completed' ? 'bi-check-circle' : 'bi-x-circle' }}" style="color:{{ $b->status === 'completed' ? '#059669' : '#ef4444' }};"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.9rem;color:var(--text-primary);">Booking with {{ $b->partner->name }}</div>
                                    <div style="font-size:0.78rem;color:var(--text-muted);">{{ $b->duration_hours }}h session</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:0.88rem;color:var(--text-secondary);">{{ $b->booking_date->format('d M Y') }}</td>
                        <td><span class="fw-bold" style="color:var(--brand-purple);">₹{{ number_format($b->total_amount) }}</span></td>
                        <td>
                            @if($b->discount_amount > 0)
                                <span style="color:#059669;font-weight:600;font-size:0.88rem;">-₹{{ number_format($b->discount_amount) }}</span>
                            @else
                                <span style="color:var(--text-muted);font-size:0.85rem;">—</span>
                            @endif
                        </td>
                        <td><span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Layout -->
        <div class="d-md-none">
            @foreach($bookings->whereIn('status', ['completed','cancelled'])->take(20) as $b)
            <div class="mobile-txn-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="txn-icon" style="width:32px; height:32px; background:{{ $b->status === 'completed' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }};">
                            <i class="bi {{ $b->status === 'completed' ? 'bi-check-circle' : 'bi-x-circle' }}" style="color:{{ $b->status === 'completed' ? '#059669' : '#ef4444' }}; font-size: 0.85rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-theme-primary" style="font-size:0.85rem;">With {{ $b->partner->name }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $b->booking_date->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold" style="color:var(--brand-purple); font-size:0.95rem;">₹{{ number_format($b->total_amount) }}</div>
                        @if($b->discount_amount > 0)
                            <div style="color:#059669; font-weight:600; font-size:0.75rem;">-₹{{ number_format($b->discount_amount) }}</div>
                        @endif
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2" style="font-size:0.75rem;">
                    <span class="text-secondary">{{ $b->duration_hours }}h session</span>
                    <span class="booking-badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
