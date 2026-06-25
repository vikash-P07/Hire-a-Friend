@extends('layouts.customer')
@section('title', 'Payment Receipt | Hire-a-Friend')

@section('styles')
<style>
    .receipt-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow);
        margin: 0 auto;
        max-width: 650px;
        overflow: hidden;
        padding: 3rem 2.5rem;
    }
    .receipt-header {
        border-bottom: 2px dashed var(--border);
        padding-bottom: 2rem;
        position: relative;
        text-align: center;
    }
    .receipt-header::before, .receipt-header::after {
        content: '';
        position: absolute;
        bottom: -9px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--bg-dashboard);
    }
    .receipt-header::before { left: -34px; }
    .receipt-header::after { right: -34px; }

    .receipt-badge {
        background: rgba(16, 185, 129, 0.1);
        border-radius: 50px;
        color: #059669;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        font-size: 0.88rem;
        padding: 0.5rem 1.5rem;
        margin-top: 1rem;
    }
    .receipt-badge.refunded {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .receipt-details {
        padding-top: 2rem;
    }
    .receipt-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        font-size: 0.95rem;
    }
    .receipt-label {
        color: var(--text-muted);
        font-weight: 500;
    }
    .receipt-value {
        color: var(--text-primary);
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="page-header text-center">
    <h1 class="page-title">Transaction Invoice</h1>
    <p class="page-subtitle">Thank you for booking with Hire-a-Friend</p>
</div>

<div class="receipt-card">
    <div class="receipt-header">
        <div class="d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;border-radius:50%;background:rgba(124,58,237,0.1);font-size:2rem;color:var(--brand-purple);">
            <i class="bi bi-patch-check-fill"></i>
        </div>
        <h4 class="fw-bold mt-3 mb-1 text-theme-primary">Payment Confirmed</h4>
        <p class="text-muted small mb-0">Booking ID: #{{ $booking->id }}</p>
        
        @if ($payment && $payment->payment_status === 'refunded')
            <div class="receipt-badge refunded">
                <i class="bi bi-arrow-counterclockwise"></i> Refunded
            </div>
        @else
            <div class="receipt-badge">
                <i class="bi bi-shield-check"></i> Paid successfully
            </div>
        @endif
    </div>

    <div class="receipt-details">
        <div class="receipt-row">
            <span class="receipt-label">Transaction Reference:</span>
            <span class="receipt-value">{{ $payment->transaction_id ?? 'N/A' }}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Payment Date & Time:</span>
            <span class="receipt-value">{{ $payment ? $payment->created_at->format('M d, Y - h:i A') : $booking->created_at->format('M d, Y - h:i A') }}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Payment Method:</span>
            <span class="receipt-value" style="text-transform: uppercase;">{{ str_replace('_', ' ', $payment->payment_method ?? 'UPI') }}</span>
        </div>
        
        <hr class="my-4" style="border-color:var(--border);">

        <h6 class="fw-bold mb-3 text-theme-primary">Service & Companion Details</h6>
        <div class="receipt-row">
            <span class="receipt-label">Companion:</span>
            <span class="receipt-value">{{ $booking->partner->name }}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Appointment date:</span>
            <span class="receipt-value">{{ $booking->booking_date->format('l, d M Y') }}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Start Time:</span>
            <span class="receipt-value">{{ date('h:i A', strtotime($booking->start_time)) }}</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Duration:</span>
            <span class="receipt-value">{{ $booking->duration_hours }} hours</span>
        </div>
        <div class="receipt-row">
            <span class="receipt-label">Hourly Rate:</span>
            <span class="receipt-value">₹{{ number_format($booking->hourly_rate, 2) }}/hr</span>
        </div>

        <hr class="my-4" style="border-color:var(--border);">

        <div class="receipt-row">
            <span class="receipt-label">Subtotal Amount:</span>
            <span class="receipt-value">₹{{ number_format($booking->total_amount, 2) }}</span>
        </div>
        @if ($booking->discount_amount > 0)
            <div class="receipt-row text-success">
                <span class="receipt-label text-success">Discount:</span>
                <span class="fw-bold">-₹{{ number_format($booking->discount_amount, 2) }}</span>
            </div>
        @endif

        <div class="receipt-row mb-0 mt-3 pt-3 border-top" style="border-top-color:var(--border)!important;">
            <span class="fw-bold text-theme-primary fs-5">Total Paid Amount:</span>
            <span class="fw-bold fs-4" style="color:var(--brand-purple);">₹{{ number_format($booking->final_amount, 2) }}</span>
        </div>

        @if ($payment && $payment->payment_status === 'refunded')
            @php $refund = $booking->refund; @endphp
            @if ($refund)
                <div class="p-3 mt-4 rounded-3 border border-danger bg-theme-secondary-danger" style="background:rgba(239,68,68,0.03);">
                    <div class="fw-bold text-danger mb-1 small"><i class="bi bi-info-circle me-1"></i>Refund Details</div>
                    <div class="small text-muted mb-1">Refund Txn: <strong>{{ $refund->refund_transaction_id }}</strong></div>
                    <div class="small text-muted">Reason: <em>{{ $refund->reason ?? 'Canceled / Rejected' }}</em></div>
                </div>
            @endif
        @endif

        <div class="text-center mt-5 d-flex gap-2 justify-content-center">
            <a href="{{ route('customer.dashboard') }}" class="btn-brand px-4 py-2 text-decoration-none small" style="border-radius:10px;font-weight:600;display:inline-block;">
                <i class="bi bi-grid me-2"></i>Go to Dashboard
            </a>
            <button onclick="window.print()" class="btn btn-surface px-4 py-2 small" style="border-radius:10px;">
                <i class="bi bi-printer me-2"></i>Print Receipt
            </button>
        </div>
    </div>
</div>
@endsection
