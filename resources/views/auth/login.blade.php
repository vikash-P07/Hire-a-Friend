@extends('layouts.app')
@section('title', 'Sign In | Hire-a-Friend')

@section('styles')
<style>
body { min-height:100vh; display:flex; flex-direction:column; background:var(--bg-color,#f1f5f9); }

.auth-page {
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:2rem 1rem;
    background: radial-gradient(at 20% 20%, rgba(124,58,237,0.08) 0%, transparent 50%),
                radial-gradient(at 80% 80%, rgba(236,72,153,0.08) 0%, transparent 50%),
                var(--bg-color,#f1f5f9);
}

.auth-box {
    width:100%;
    max-width:980px;
    display:flex;
    background:var(--card-bg,#fff);
    border:1px solid var(--card-border,rgba(0,0,0,0.06));
    border-radius:24px;
    box-shadow:0 20px 60px rgba(0,0,0,0.08);
    overflow:hidden;
}

.auth-form-panel {
    flex:1;
    padding:3.5rem;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.auth-visual-panel {
    flex:1;
    position:relative;
    display:flex;
    flex-direction:column;
    justify-content:flex-end;
    overflow:hidden;
    min-height:520px;
}

.auth-visual-bg {
    position:absolute; inset:0;
    background:linear-gradient(160deg, #7c3aed 0%, #ec4899 100%);
}

.auth-visual-img {
    position:absolute; inset:0;
    width:100%; height:100%;
    object-fit:cover;
    mix-blend-mode:luminosity;
    opacity:0.35;
}

.auth-visual-content {
    position:relative; z-index:2;
    padding:3rem;
    color:#fff;
}

.auth-brand {
    display:inline-flex; align-items:center; gap:10px;
    text-decoration:none;
    margin-bottom:2rem;
}
.auth-brand-icon {
    width:36px; height:36px; border-radius:10px;
    background:linear-gradient(135deg,#7c3aed,#ec4899);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:1rem; box-shadow:0 4px 12px rgba(124,58,237,0.4);
}
.auth-brand-text { font-weight:800; font-size:1.1rem; color:var(--text-primary,#0f172a); }

.auth-title { font-size:1.7rem; font-weight:800; color:var(--text-primary,#0f172a); margin-bottom:4px; letter-spacing:-0.5px; }
.auth-subtitle { font-size:0.9rem; color:var(--text-muted,#94a3b8); margin-bottom:2rem; }

/* Tab switcher */
.auth-tabs { display:flex; gap:2px; background:var(--bg-color,#f1f5f9); border-radius:12px; padding:4px; margin-bottom:1.75rem; }
.auth-tab { flex:1; padding:0.55rem; border-radius:9px; border:none; background:transparent; font-weight:600; font-size:0.88rem; color:var(--text-muted,#94a3b8); cursor:pointer; transition:all 0.2s; }
.auth-tab.active { background:#fff; color:#7c3aed; box-shadow:0 1px 4px rgba(0,0,0,0.1); }
html.dark .auth-tab.active { background:var(--card-bg,#1e293b); }

/* Form elements */
.auth-label { display:block; font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-muted,#94a3b8); margin-bottom:0.4rem; }
.auth-input { width:100%; padding:0.75rem 1rem; border-radius:12px; border:1.5px solid var(--card-border,#e2e8f0); background:var(--input-bg,#f8fafc); color:var(--text-primary,#0f172a); font-size:0.93rem; transition:all 0.2s; outline:none; }
.auth-input:focus { border-color:#7c3aed; box-shadow:0 0 0 3px rgba(124,58,237,0.12); background:var(--card-bg,#fff); }
.auth-input::placeholder { color:var(--text-muted,#94a3b8); }
html.dark .auth-input { background:#1e293b; border-color:#334155; color:#f8fafc; }
html.dark .auth-input:focus { background:#0f172a; }

/* Buttons */
.btn-auth-primary {
    width:100%;
    padding:0.8rem;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#7c3aed,#ec4899);
    color:#fff;
    font-weight:700;
    font-size:0.95rem;
    cursor:pointer;
    box-shadow:0 4px 16px rgba(124,58,237,0.3);
    transition:all 0.2s;
    display:flex; align-items:center; justify-content:center; gap:8px;
}
.btn-auth-primary:hover { transform:translateY(-2px); box-shadow:0 6px 24px rgba(124,58,237,0.4); }
.btn-auth-primary:disabled { opacity:0.7; cursor:not-allowed; transform:none; }

.btn-google {
    width:100%;
    padding:0.75rem;
    border:1.5px solid var(--card-border,#e2e8f0);
    border-radius:12px;
    background:var(--card-bg,#fff);
    color:var(--text-primary,#0f172a);
    font-weight:600;
    font-size:0.93rem;
    cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:10px;
    transition:all 0.2s;
    position:relative; overflow:hidden;
}
.btn-google:hover { background:var(--bg-color,#f8fafc); border-color:#7c3aed; transform:translateY(-1px); box-shadow:0 4px 12px rgba(0,0,0,0.08); }
html.dark .btn-google { background:#1e293b; border-color:#334155; color:#f8fafc; }
html.dark .btn-google:hover { background:#0f172a; }
.btn-google.loading { pointer-events:none; opacity:0.8; }

.or-divider {
    display:flex; align-items:center; gap:1rem;
    font-size:0.8rem; color:var(--text-muted,#94a3b8); font-weight:600;
    margin:1.25rem 0;
}
.or-divider::before, .or-divider::after { content:''; flex:1; height:1px; background:var(--card-border,#e2e8f0); }

.auth-link { color:#7c3aed; font-weight:600; text-decoration:none; }
.auth-link:hover { text-decoration:underline; }

/* OTP section */
.otp-inputs { display:flex; gap:10px; justify-content:center; margin:1.5rem 0; }
.otp-input { width:52px; height:56px; text-align:center; font-size:1.4rem; font-weight:700; border-radius:12px; border:2px solid var(--card-border,#e2e8f0); background:var(--input-bg,#f8fafc); color:var(--text-primary,#0f172a); outline:none; transition:all 0.2s; }
.otp-input:focus { border-color:#7c3aed; box-shadow:0 0 0 3px rgba(124,58,237,0.12); }

/* Toast */
#authToast { position:fixed; bottom:2rem; right:2rem; z-index:9999; min-width:300px; }

/* Google sign-in loading spinner */
.spinner { width:18px; height:18px; border:2.5px solid rgba(255,255,255,0.4); border-top-color:#fff; border-radius:50%; animation:spin 0.8s linear infinite; display:none; }
@keyframes spin { to { transform:rotate(360deg); } }

@media(max-width:768px) {
    .auth-visual-panel { display:none; }
    .auth-form-panel { padding:2rem; }
}
@media(max-width:375px) {
    .auth-form-panel { padding:1.25rem 1rem; }
}
</style>
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-box">

        <!-- FORM PANEL -->
        <div class="auth-form-panel">

            <a href="{{ route('home') }}" class="auth-brand">
                <div class="auth-brand-icon"><i class="bi bi-people-fill"></i></div>
                <span class="auth-brand-text">Hire-a-Friend</span>
            </a>

            <h1 class="auth-title">Welcome back 👋</h1>
            <p class="auth-subtitle">Sign in to manage your bookings and companion experiences.</p>

            <!-- Tab Switcher -->
            <div class="auth-tabs" id="loginTabs">
                <button class="auth-tab active" onclick="switchTab('email')">Email</button>
                <button class="auth-tab" onclick="switchTab('otp')">Mobile OTP</button>
            </div>

            <!-- EMAIL LOGIN TAB -->
            <div id="emailTab">
                <!-- Google Login Button -->
                <button class="btn-google mb-3" id="googleLoginBtn" onclick="signInWithGoogle()">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20" height="20" alt="Google">
                    <span id="googleBtnText">Continue with Google</span>
                    <div class="spinner" id="googleSpinner"></div>
                </button>

                <div class="or-divider">OR SIGN IN WITH EMAIL</div>

                @if($errors->any())
                    <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-left:4px solid #ef4444;border-radius:10px;padding:0.85rem 1rem;margin-bottom:1.25rem;font-size:0.87rem;color:var(--text-primary,#0f172a);">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="auth-label">Email Address</label>
                        <input type="email" name="email" class="auth-input" value="{{ old('email') }}" placeholder="yourname@example.com" required autofocus>
                    </div>
                    <div class="mb-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="auth-label mb-0">Password</label>
                            <a href="{{ route('password.request') }}" class="auth-link" style="font-size:0.8rem;">Forgot password?</a>
                        </div>
                        <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3 mb-4">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input mt-0" style="width:16px;height:16px;accent-color:#7c3aed;">
                        <label for="remember" style="font-size:0.85rem;color:var(--text-muted,#94a3b8);cursor:pointer;">Keep me signed in</label>
                    </div>
                    <button type="submit" class="btn-auth-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </button>
                </form>

                <div class="text-center mt-4" style="font-size:0.88rem;">
                    <span style="color:var(--text-muted,#94a3b8);">Don't have an account? </span>
                    <a href="{{ route('register') }}" class="auth-link">Create one free</a>
                </div>
            </div>

            <!-- OTP LOGIN TAB -->
            <div id="otpTab" class="d-none">
                <div id="otpStep1">
                    <label class="auth-label">Mobile Number</label>
                    <div class="d-flex gap-2 mb-4">
                        <select class="auth-input" style="width:90px;flex-shrink:0;">
                            <option>🇮🇳 +91</option>
                            <option>🇺🇸 +1</option>
                            <option>🇬🇧 +44</option>
                        </select>
                        <input type="tel" id="otpPhone" class="auth-input" placeholder="9876543210" maxlength="10">
                    </div>
                    <button type="button" class="btn-auth-primary" onclick="sendOTP()">
                        <i class="bi bi-send-fill"></i> Send OTP
                    </button>
                </div>

                <div id="otpStep2" class="d-none text-center">
                    <div style="font-size:0.9rem;color:var(--text-muted,#94a3b8);margin-bottom:0.5rem;">OTP sent to <span id="otpPhoneDisplay" style="color:#7c3aed;font-weight:700;"></span></div>
                    <div class="otp-inputs">
                        <input type="text" class="otp-input" maxlength="1" oninput="otpNext(this,0)">
                        <input type="text" class="otp-input" maxlength="1" oninput="otpNext(this,1)">
                        <input type="text" class="otp-input" maxlength="1" oninput="otpNext(this,2)">
                        <input type="text" class="otp-input" maxlength="1" oninput="otpNext(this,3)">
                        <input type="text" class="otp-input" maxlength="1" oninput="otpNext(this,4)">
                        <input type="text" class="otp-input" maxlength="1" oninput="otpNext(this,5)">
                    </div>
                    <button type="button" class="btn-auth-primary mb-3" onclick="verifyOTP()">
                        <i class="bi bi-shield-check-fill"></i> Verify OTP
                    </button>
                    <div>
                        <button type="button" style="background:none;border:none;color:#7c3aed;font-size:0.85rem;font-weight:600;cursor:pointer;" onclick="document.getElementById('otpStep1').classList.remove('d-none');document.getElementById('otpStep2').classList.add('d-none');">← Change Number</button>
                        &nbsp;·&nbsp;
                        <button type="button" style="background:none;border:none;color:var(--text-muted,#94a3b8);font-size:0.85rem;cursor:pointer;" onclick="sendOTP()">Resend OTP</button>
                    </div>
                </div>

                <div class="or-divider mt-4">OR</div>
                <button class="btn-google" onclick="switchTab('email')">
                    <i class="bi bi-envelope" style="color:#7c3aed;"></i> Sign in with Email instead
                </button>
            </div>

        </div>

        <!-- VISUAL PANEL -->
        <div class="auth-visual-panel d-none d-md-flex">
            <div class="auth-visual-bg"></div>
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=700&h=900&fit=crop&q=80" class="auth-visual-img" alt="">
            <div class="auth-visual-content">
                <div class="d-flex gap-3 mb-4">
                    @foreach([
                        ['img'=>'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=60&h=60&fit=crop','name'=>'Priya S.'],
                        ['img'=>'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?w=60&h=60&fit=crop','name'=>'Arjun M.'],
                        ['img'=>'https://images.unsplash.com/photo-1524250502761-1ac6f2e30d43?w=60&h=60&fit=crop','name'=>'Kavya N.'],
                    ] as $f)
                    <div style="text-align:center;">
                        <img src="{{ $f['img'] }}" style="width:48px;height:48px;border-radius:50%;border:3px solid rgba(255,255,255,0.4);object-fit:cover;display:block;margin-bottom:4px;" alt="">
                        <div style="font-size:0.7rem;opacity:0.8;">{{ $f['name'] }}</div>
                    </div>
                    @endforeach
                </div>
                <h3 style="font-size:1.6rem;font-weight:800;margin-bottom:0.5rem;line-height:1.3;">Real Connections,<br>Real Experiences</h3>
                <p style="opacity:0.8;font-size:0.9rem;margin:0;">Join 50,000+ users who found their perfect companion for café visits, city walks, coworking & more.</p>
                <div class="d-flex gap-3 mt-3">
                    <div style="text-align:center;">
                        <div style="font-size:1.3rem;font-weight:800;">500+</div>
                        <div style="font-size:0.72rem;opacity:0.75;">Companions</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:1.3rem;font-weight:800;">4.9★</div>
                        <div style="font-size:0.72rem;opacity:0.75;">Avg Rating</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:1.3rem;font-weight:800;">50K+</div>
                        <div style="font-size:0.72rem;opacity:0.75;">Users</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Toast for feedback -->
<div id="authToast">
    <div id="toastBox" style="display:none;padding:0.9rem 1.25rem;border-radius:14px;color:#fff;font-weight:600;font-size:0.9rem;box-shadow:0 8px 24px rgba(0,0,0,0.2);display:flex;align-items:center;gap:10px;max-width:340px;"></div>
</div>
@endsection

@section('scripts')
{{-- Firebase SDK --}}
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import { getAuth, signInWithPopup, GoogleAuthProvider, signOut } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

    const firebaseConfig = {
        apiKey:            "{{ config('services.firebase.api_key') }}",
        authDomain:        "{{ config('services.firebase.auth_domain') }}",
        projectId:         "{{ config('services.firebase.project_id') }}",
        storageBucket:     "{{ config('services.firebase.storage_bucket') }}",
        messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
        appId:             "{{ config('services.firebase.app_id') }}",
    };

    // Initialize Firebase only if config is set
    const hasConfig = firebaseConfig.apiKey && firebaseConfig.apiKey !== 'YOUR_FIREBASE_API_KEY';

    let auth = null;
    let provider = null;

    if (hasConfig) {
        const app = initializeApp(firebaseConfig);
        auth = getAuth(app);
        provider = new GoogleAuthProvider();
        provider.addScope('email');
        provider.addScope('profile');
        window.__firebaseReady = true;
    } else {
        window.__firebaseReady = false;
        console.warn('Firebase not configured. Set FIREBASE_* keys in .env to enable Google login.');
    }

    window.signInWithGoogle = async function() {
        if (!window.__firebaseReady) {
            showToast('Firebase not configured yet. Please set up Firebase credentials first.', 'warning');
            return;
        }

        const btn        = document.getElementById('googleLoginBtn');
        const btnText    = document.getElementById('googleBtnText');
        const spinner    = document.getElementById('googleSpinner');

        btn.classList.add('loading');
        btnText.textContent = 'Signing in...';
        spinner.style.display = 'block';

        try {
            const result = await signInWithPopup(auth, provider);
            const user   = result.user;
            const token  = await user.getIdToken();

            // Send token to Laravel backend
            const res = await fetch('/auth/google/firebase', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ firebase_token: token, role: 'customer' }),
            });

            const data = await res.json();

            if (data.success) {
                showToast('✓ Signed in successfully! Redirecting...', 'success');
                setTimeout(() => { window.location.href = data.redirect_url; }, 800);
            } else {
                showToast(data.error || 'Login failed. Try again.', 'error');
                resetGoogleBtn();
            }

        } catch (error) {
            console.error('Google sign-in error:', error);
            if (error.code === 'auth/popup-closed-by-user') {
                showToast('Sign-in cancelled.', 'info');
            } else if (error.code === 'auth/popup-blocked') {
                showToast('Popup blocked by browser. Please allow popups for this site.', 'warning');
            } else {
                showToast('Google sign-in failed: ' + (error.message || 'Unknown error'), 'error');
            }
            resetGoogleBtn();
        }
    };

    function resetGoogleBtn() {
        const btn     = document.getElementById('googleLoginBtn');
        const btnText = document.getElementById('googleBtnText');
        const spinner = document.getElementById('googleSpinner');
        btn.classList.remove('loading');
        btnText.textContent = 'Continue with Google';
        spinner.style.display = 'none';
    }
</script>

<script>
function switchTab(tab) {
    const emailTab  = document.getElementById('emailTab');
    const otpTab    = document.getElementById('otpTab');
    const tabs      = document.querySelectorAll('.auth-tab');

    if (tab === 'email') {
        emailTab.classList.remove('d-none');
        otpTab.classList.add('d-none');
        tabs[0].classList.add('active');
        tabs[1].classList.remove('active');
    } else {
        otpTab.classList.remove('d-none');
        emailTab.classList.add('d-none');
        tabs[1].classList.add('active');
        tabs[0].classList.remove('active');
    }
}

function sendOTP() {
    const phone = document.getElementById('otpPhone').value.trim();
    if (!phone || phone.length < 10) {
        showToast('Enter a valid 10-digit mobile number.', 'warning');
        return;
    }
    document.getElementById('otpPhoneDisplay').textContent = '+91 ' + phone;
    document.getElementById('otpStep1').classList.add('d-none');
    document.getElementById('otpStep2').classList.remove('d-none');
    showToast('OTP sent to +91 ' + phone + ' (Demo mode)', 'success');
    document.querySelector('.otp-input').focus();
}

function otpNext(input, index) {
    input.value = input.value.replace(/[^0-9]/g, '');
    if (input.value.length === 1) {
        const inputs = document.querySelectorAll('.otp-input');
        if (index < inputs.length - 1) inputs[index + 1].focus();
    }
}

function verifyOTP() {
    const inputs = document.querySelectorAll('.otp-input');
    const otp    = Array.from(inputs).map(i => i.value).join('');
    if (otp.length < 6) {
        showToast('Enter all 6 OTP digits.', 'warning');
        return;
    }
    showToast('OTP Login will be available when backend SMS is configured.', 'info');
}

function showToast(msg, type) {
    const colors = {
        success: 'linear-gradient(135deg,#059669,#10b981)',
        error:   'linear-gradient(135deg,#dc2626,#ef4444)',
        warning: 'linear-gradient(135deg,#d97706,#f59e0b)',
        info:    'linear-gradient(135deg,#7c3aed,#ec4899)',
    };
    const icons = { success:'bi-check-circle-fill', error:'bi-x-circle-fill', warning:'bi-exclamation-triangle-fill', info:'bi-info-circle-fill' };
    const box  = document.getElementById('toastBox');
    box.style.background = colors[type] || colors.info;
    box.innerHTML = `<i class="bi ${icons[type] || icons.info}"></i> ${msg}`;
    box.style.display = 'flex';
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => { box.style.display = 'none'; }, 4000);
}
</script>
@endsection
