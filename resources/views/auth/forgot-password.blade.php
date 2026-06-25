@extends('layouts.app')
@section('title', 'Forgot Password - Companion Platform')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card glass-card shadow p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Reset Password</h3>
                    <p class="text-muted">Enter your email address and we'll send you a link to reset your password.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success small mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-theme-card border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                        </div>
                        @error('email')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-gradient w-100 py-2.5 mb-3">Send Password Reset Link</button>
                    
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none small fw-bold text-primary"><i class="bi bi-arrow-left me-1"></i>Back to Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
