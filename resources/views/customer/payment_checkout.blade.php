@extends('layouts.customer')
@section('title', 'Secure Checkout | Hire-a-Friend')

@section('styles')
<style>
    .checkout-container {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }
    @media (max-width: 991px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }
    .payment-method-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--card-shadow);
    }
    .order-summary-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        box-shadow: var(--card-shadow);
        height: fit-content;
        position: sticky;
        top: 2rem;
    }
    .nav-tabs-custom {
        border-bottom: 2px solid var(--border-light);
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .nav-link-custom {
        border: none;
        background: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0.75rem 0.5rem;
        position: relative;
        cursor: pointer;
        transition: var(--transition);
    }
    .nav-link-custom.active {
        color: var(--brand-purple);
    }
    .nav-link-custom.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--brand-purple);
    }
    .payment-tab-content {
        display: none;
    }
    .payment-tab-content.active {
        display: block;
    }
    .form-control-custom {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        color: var(--text-primary);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition);
        width: 100%;
    }
    .form-control-custom:focus {
        border-color: var(--brand-purple);
        outline: none;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.88rem;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Secure Checkout</h1>
    <p class="page-subtitle">Complete your transaction using cards, UPI, net banking, or wallets</p>
</div>

<div class="checkout-container">
    <!-- Left: Payment Form -->
    <div class="payment-method-card">
        <h5 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-shield-lock me-2 text-primary"></i>Choose Payment Method</h5>
        
        <div class="nav-tabs-custom">
            <button class="nav-link-custom active" onclick="switchTab('upi')">
                <i class="bi bi-qr-code me-2"></i>UPI / GPay
            </button>
            <button class="nav-link-custom" onclick="switchTab('card')">
                <i class="bi bi-credit-card me-2"></i>Card
            </button>
            <button class="nav-link-custom" onclick="switchTab('net_banking')">
                <i class="bi bi-bank me-2"></i>Net Banking
            </button>
            <button class="nav-link-custom" onclick="switchTab('wallet')">
                <i class="bi bi-wallet2 me-2"></i>Wallet
            </button>
        </div>

        <form action="{{ route('customer.payment.process', $booking->id) }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="payment_method" id="payment_method" value="upi">

            <!-- UPI Content -->
            <div id="content-upi" class="payment-tab-content active">
                <p class="text-muted small mb-4">Pay securely using any UPI app (GPay, PhonePe, Paytm, BHIM, etc.).</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-theme-primary">UPI ID / VPA</label>
                    <input type="text" name="upi_id" id="upi_id" class="form-control-custom" placeholder="e.g. mobile@ybl or username@okaxis">
                    <div class="form-text text-muted">A payment request will be sent to your UPI app.</div>
                </div>
            </div>

            <!-- Card Content -->
            <div id="content-card" class="payment-tab-content">
                <p class="text-muted small mb-4">We support all major Credit and Debit cards (Visa, Mastercard, RuPay, Maestro).</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-theme-primary">Cardholder Name</label>
                    <input type="text" name="card_name" id="card_name" class="form-control-custom" placeholder="Name as printed on card">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-theme-primary">Card Number</label>
                    <input type="text" name="card_number" id="card_number" class="form-control-custom" placeholder="1234 5678 9101 1121" maxlength="19">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold text-theme-primary">Expiry Date</label>
                        <input type="text" name="card_expiry" id="card_expiry" class="form-control-custom" placeholder="MM/YY" maxlength="5">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold text-theme-primary">CVV / CVC</label>
                        <input type="password" name="card_cvv" id="card_cvv" class="form-control-custom" placeholder="123" maxlength="4">
                    </div>
                </div>
            </div>

            <!-- Net Banking Content -->
            <div id="content-net_banking" class="payment-tab-content">
                <p class="text-muted small mb-4">Select your bank from the list below to complete payment.</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-theme-primary">Select Bank</label>
                    <select name="bank_name" id="bank_name" class="form-select form-control-custom">
                        <option value="">-- Choose Bank --</option>
                        <option value="SBI">State Bank of India (SBI)</option>
                        <option value="HDFC">HDFC Bank</option>
                        <option value="ICICI">ICICI Bank</option>
                        <option value="Axis">Axis Bank</option>
                        <option value="KOTAK">Kotak Mahindra Bank</option>
                        <option value="PNB">Punjab National Bank</option>
                    </select>
                </div>
            </div>

            <!-- Wallet Content -->
            <div id="content-wallet" class="payment-tab-content">
                <p class="text-muted small mb-4">Complete payment using your preferred digital wallet.</p>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-theme-primary">Select Wallet Provider</label>
                    <select name="wallet_provider" id="wallet_provider" class="form-select form-control-custom">
                        <option value="">-- Select Wallet --</option>
                        <option value="Paytm">Paytm Wallet</option>
                        <option value="PhonePe">PhonePe Wallet</option>
                        <option value="AmazonPay">Amazon Pay Wallet</option>
                        <option value="Mobikwik">Mobikwik Wallet</option>
                    </select>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mt-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <hr class="my-4" style="border-color:var(--border);">
            
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-shield-check text-success fs-4 me-2"></i>
                    <span>256-bit SSL encrypted. Safe & Secure.</span>
                </div>
                <button type="submit" class="btn btn-brand px-5 py-2.5" style="border-radius:10px;border:none;cursor:pointer;background:var(--brand-purple);color:#fff;" id="submit-btn">
                    Pay ₹{{ number_format($booking->final_amount, 2) }}
                </button>
            </div>
        </form>
    </div>

    <!-- Right: Order Summary -->
    <div class="order-summary-card">
        <h5 class="fw-bold mb-4 text-theme-primary">Booking Summary</h5>
        
        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background:var(--surface-2);">
            <img src="{{ $partner->profile_picture_url }}" alt="{{ $partner->name }}" style="width: 54px; height: 54px; border-radius: 50%; object-fit: cover;">
            <div>
                <div class="fw-bold text-theme-primary">{{ $partner->name }}</div>
                <div class="small text-muted">{{ $partner->partnerProfile->experience_years }} years experience</div>
                <div class="text-warning small">★ {{ number_format($partner->partnerProfile->rating, 1) }}</div>
            </div>
        </div>

        <div class="summary-row">
            <span class="text-muted">Date:</span>
            <span class="fw-semibold text-theme-primary">{{ $booking->booking_date->format('M d, Y') }}</span>
        </div>
        <div class="summary-row">
            <span class="text-muted">Time slot:</span>
            <span class="fw-semibold text-theme-primary">{{ date('h:i A', strtotime($booking->start_time)) }}</span>
        </div>
        <div class="summary-row">
            <span class="text-muted">Duration:</span>
            <span class="fw-semibold text-theme-primary">{{ $booking->duration_hours }} hours</span>
        </div>
        <div class="summary-row">
            <span class="text-muted">Hourly Rate:</span>
            <span class="fw-semibold text-theme-primary">₹{{ number_format($booking->hourly_rate, 2) }}/hr</span>
        </div>

        <hr style="border-color:var(--border);">

        <div class="summary-row">
            <span class="text-muted">Subtotal:</span>
            <span class="fw-semibold text-theme-primary">₹{{ number_format($booking->total_amount, 2) }}</span>
        </div>
        @if ($booking->discount_amount > 0)
            <div class="summary-row text-success">
                <span>Discount:</span>
                <span class="fw-bold">-₹{{ number_format($booking->discount_amount, 2) }}</span>
            </div>
        @endif

        <hr style="border-color:var(--border);">

        <div class="d-flex justify-content-between align-items-center mb-0">
            <span class="fw-bold text-theme-primary">Total Amount:</span>
            <span class="fs-4 fw-extrabold" style="color:var(--brand-purple);">₹{{ number_format($booking->final_amount, 2) }}</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(method) {
        // Set payment method input
        document.getElementById('payment_method').value = method;

        // Reset tabs
        document.querySelectorAll('.nav-link-custom').forEach(tab => {
            tab.classList.remove('active');
        });
        // Reset contents
        document.querySelectorAll('.payment-tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Set active tab
        const activeTab = Array.from(document.querySelectorAll('.nav-link-custom')).find(tab => 
            tab.getAttribute('onclick').includes(method)
        );
        if (activeTab) {
            activeTab.classList.add('active');
        }

        // Set active content
        const activeContent = document.getElementById('content-' + method);
        if (activeContent) {
            activeContent.classList.add('active');
        }
    }

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        // Prevent duplicate transaction submissions
        const btn = document.getElementById('submit-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';
    });
</script>
@endsection
