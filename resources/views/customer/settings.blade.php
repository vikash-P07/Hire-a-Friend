@extends('layouts.customer')
@section('title', 'Settings | Hire-a-Friend')

@section('styles')
<style>
    .settings-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    .profile-upload-zone {
        border: 2px dashed var(--border);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        text-align: center;
        position: relative;
        cursor: pointer;
        transition: var(--transition);
        background: var(--surface-2);
    }
    .profile-upload-zone:hover {
        border-color: var(--brand-purple);
        background: rgba(124, 58, 237, 0.02);
    }
    .settings-nav {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .settings-nav-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        border: none;
        background: transparent;
        color: var(--text-secondary);
        font-weight: 500;
        text-align: left;
        width: 100%;
        transition: var(--transition);
    }
    .settings-nav-btn:hover {
        background: var(--surface-2);
        color: var(--brand-purple);
    }
    .settings-nav-btn.active {
        background: var(--sidebar-link-hover);
        color: var(--brand-purple);
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .settings-card {
            padding: 1.25rem !important;
        }
        .profile-upload-row {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            gap: 1rem !important;
        }
        .profile-upload-row img, .profile-upload-row .avatar-placeholder {
            width: 80px !important;
            height: 80px !important;
            margin: 0 auto;
        }
        .settings-card .btn-brand {
            width: 100% !important;
        }
        .settings-card .d-flex.justify-content-end {
            justify-content: center !important;
        }
        .settings-nav {
            flex-direction: row !important;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .settings-nav-btn {
            width: auto !important;
            flex: 1;
            text-align: center;
            justify-content: center;
            font-size: 0.8rem;
            padding: 0.6rem 0.85rem;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Settings</h1>
    <p class="page-subtitle">Manage your profile details and preferences</p>
</div>

<div class="row g-4">
    <!-- Sidebar Navigation -->
    <div class="col-12 col-md-3">
        <div class="settings-card p-3">
            <div class="settings-nav" role="tablist">
                <button class="settings-nav-btn active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-panel" role="tab" aria-selected="true">
                    <i class="bi bi-person-circle"></i>
                    <span>Edit Profile</span>
                </button>
                <button class="settings-nav-btn" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-panel" role="tab" aria-selected="false">
                    <i class="bi bi-shield-lock"></i>
                    <span>Password & Security</span>
                </button>
                <button class="settings-nav-btn" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences-panel" role="tab" aria-selected="false" onclick="showComingSoon('Preferences Settings')">
                    <i class="bi bi-sliders"></i>
                    <span>Preferences</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Panel -->
    <div class="col-12 col-md-9">
        <div class="settings-card p-4">
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="tab-content">
                    <!-- Edit Profile Tab -->
                    <div class="tab-pane fade show active" id="profile-panel" role="tabpanel">
                        <h4 class="fw-bold mb-4" style="color:var(--text-primary);">Edit Profile Information</h4>
                        
                        <div class="row g-4">
                            <!-- Profile Photo -->
                            <div class="col-12 d-flex flex-wrap align-items-center gap-4 border-bottom pb-4 mb-2 profile-upload-row">
                                @if($user->profile_picture)
                                    <img src="{{ $user->profile_picture_url }}" alt="Profile Picture" class="avatar" style="width: 100px; height: 100px; border: 3px solid var(--brand-purple);">
                                @else
                                    <div class="avatar-placeholder" style="width: 100px; height: 100px; font-size: 2.5rem; border: 3px solid var(--brand-purple);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-2">Profile Picture</h6>
                                    <input type="file" name="profile_picture" id="profile_picture" class="form-control form-control-sm" accept="image/*">
                                    <div style="font-size: 0.75rem; color: var(--text-muted);" class="mt-1">PNG, JPG or JPEG. Max 2MB.</div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="phone">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="transgender" {{ old('gender', $user->gender) === 'transgender' ? 'selected' : '' }}>Transgender</option>
                                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- City -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="city_id">Location (City)</label>
                                    <select name="city_id" id="city_id" class="form-select" required>
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn-brand">Save Profile Changes</button>
                        </div>
                    </div>

                    <!-- Password & Security Tab -->
                    <div class="tab-pane fade" id="security-panel" role="tabpanel">
                        <h4 class="fw-bold mb-4" style="color:var(--text-primary);">Change Password</h4>
                        
                        <div class="row g-4">
                            <!-- Password -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="password">New Password</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password">
                                </div>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Leave blank to keep current password">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn-brand">Update Password</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
