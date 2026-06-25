@extends('layouts.app')
@section('title', 'Create Account | Hire-a-Friend')

@section('styles')
<style>
    body {
        background-color: var(--bg-color);
        background-image: 
            radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.08) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(244, 63, 94, 0.08) 0px, transparent 50%);
        background-attachment: fixed;
    }

    .auth-container {
        display: flex;
        min-height: calc(100vh - 150px);
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .auth-card {
        background: var(--card-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 100%;
        max-width: 1100px;
        display: flex;
    }

    .auth-form-side {
        flex: 1.2;
        padding: 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth-image-side {
        flex: 0.8;
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.8), rgba(244, 63, 94, 0.8)), url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80') center/cover;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 4rem;
        color: white;
    }

    @media (max-width: 991.98px) {
        .auth-card {
            flex-direction: column;
            max-width: 600px;
        }
        .auth-form-side {
            padding: 2.5rem;
        }
        .auth-image-side {
            display: none;
        }
    }

    @media (max-width: 375px) {
        .auth-form-side {
            padding: 1.25rem 1rem;
        }
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 0.8rem 1.2rem;
        background-color: var(--input-bg);
        border: 1px solid var(--input-border);
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #a855f7;
        box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.15);
        background-color: var(--card-bg);
    }

    .btn-auth-primary {
        background: linear-gradient(135deg, #a855f7, #f43f5e);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-auth-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(168, 85, 247, 0.3);
        color: white;
    }

    .partner-section-box {
        background: rgba(168, 85, 247, 0.05);
        border: 1px solid rgba(168, 85, 247, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container auth-container">
    <div class="auth-card">
        <div class="auth-form-side">
            <div class="mb-4">
                <h2 class="fw-bold text-theme-primary mb-2">Create Account</h2>
                <p class="text-muted">Join the Hire-a-Friend community.</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Full Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required placeholder="John Doe">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Email Address</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Phone Number</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Gender</label>
                        <select class="form-select" name="gender">
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="transgender" {{ old('gender') === 'transgender' ? 'selected' : '' }}>Transgender</option>
                            <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Location</label>
                        <select class="form-select" name="city_id" required>
                            <option value="" disabled selected>Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Account Type</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="customer" {{ old('role', 'customer') === 'customer' ? 'selected' : '' }}>Customer (Book others)</option>
                            <option value="partner" {{ old('role') === 'partner' ? 'selected' : '' }}>Companion (Offer services)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Password</label>
                        <input type="password" class="form-control" name="password" required placeholder="••••••••">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn w-100 btn-auth-primary mt-4">Create Account</button>
                
                <div class="text-center mt-3">
                    <span class="text-muted small">Already have an account? </span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #a855f7;">Sign In</a>
                </div>
            </form>
        </div>

        <div class="auth-image-side">
            <div style="z-index: 10;">
                <h3 class="fw-bold mb-3">Join the Community</h3>
                <p class="mb-0 opacity-75">Connect with amazing people in your city. Whether you want to explore as a client, or earn as a companion, we provide the platform for you.</p>
            </div>
        </div>
    </div>
</div>
@endsection
