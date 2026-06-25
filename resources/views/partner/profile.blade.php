@extends('layouts.partner')
@section('title', 'Profile & Onboarding | Companion Partner')

@section('styles')
<style>
    .onboard-step {
        display: none;
    }
    .onboard-step.active {
        display: block;
    }
    .wizard-tab {
        flex: 1;
        padding: 1rem;
        border-radius: 12px;
        background: var(--surface-2);
        border: 1.5px solid var(--border);
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--text-secondary);
        text-align: center;
        transition: all 0.2s;
        cursor: default;
    }
    .wizard-tab.active {
        background: var(--brand-gradient);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 12px var(--brand-glow);
    }
    .wizard-tab.completed {
        background: rgba(16, 185, 129, 0.08);
        border-color: var(--success);
        color: var(--success);
    }
    .avatar-preview-container {
        position: relative;
        width: 100px;
        height: 100px;
    }
    .avatar-preview {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--brand-purple);
    }
    .avatar-preview-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--brand-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.2rem;
        font-weight: 800;
    }

    /* Mobile Responsive Optimizations */
    @media (max-width: 767.98px) {
        .wizard-tab {
            width: 100% !important;
            flex: none !important;
            padding: 0.75rem !important;
            font-size: 0.85rem !important;
            text-align: left !important;
            margin-bottom: 0.5rem;
        }
        .wizard-tab:last-child {
            margin-bottom: 0;
        }
        .d-flex.justify-content-between button,
        .d-flex.justify-content-end button,
        .d-flex.justify-content-between a,
        .d-flex.justify-content-between form button {
            width: 100% !important;
            margin-bottom: 0.5rem;
        }
        .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
    }

    @media (max-width: 575.98px) {
        .avatar-preview-container {
            margin: 0 auto 1rem !important;
        }
        .row.align-items-center.mb-4 {
            flex-direction: column !important;
            text-align: center !important;
        }
        
        /* Stack Availability Table */
        .c-table.align-middle, 
        .c-table.align-middle thead, 
        .c-table.align-middle tbody, 
        .c-table.align-middle tr, 
        .c-table.align-middle th, 
        .c-table.align-middle td {
            display: block !important;
            width: 100% !important;
        }
        .c-table.align-middle thead {
            display: none !important;
        }
        .c-table.align-middle tr {
            margin-bottom: 1rem;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 0.75rem !important;
            background: var(--surface-2);
        }
        .c-table.align-middle td {
            padding: 0.35rem 0 !important;
            border-bottom: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        .c-table.align-middle td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .c-table.align-middle td input[type="time"] {
            max-width: 150px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Profile Settings & Onboarding</h1>
    <p class="page-subtitle">Configure your companion profile, verification documents, and scheduling</p>
</div>

<!-- Wizard Header Tabs -->
<div class="d-flex gap-3 mb-4 flex-wrap">
    <div class="wizard-tab active" id="tabHeader1" onclick="switchStep(1)">
        <i class="bi bi-shield-check me-2"></i>Step 1: Verification & Payout
    </div>
    <div class="wizard-tab" id="tabHeader2" onclick="switchStep(2)">
        <i class="bi bi-person-lines-fill me-2"></i>Step 2: Profile Details
    </div>
    <div class="wizard-tab" id="tabHeader3" onclick="switchStep(3)">
        <i class="bi bi-clock-history me-2"></i>Step 3: Availability Schedule
    </div>
</div>

<form action="{{ route('partner.onboarding.save') }}" method="POST" enctype="multipart/form-data" id="onboardingForm">
    @csrf

    <!-- STEP 1: VERIFICATION & PAYOUT -->
    <div class="onboard-step active" id="step1">
        <div class="card-glass-static p-4 mb-4">
            <h5 class="fw-bold mb-4" style="color:var(--text-primary);"><i class="bi bi-shield-lock me-2" style="color:var(--brand-purple);"></i>KYC Identity Verifications</h5>
            
            <div class="row g-4">
                <!-- Aadhaar Front -->
                <div class="col-md-6">
                    <label class="form-label">Aadhaar Card Front Side</label>
                    @if($verification && $verification->aadhaar_front)
                        <div class="mb-2 small text-success"><i class="bi bi-file-earmark-check me-1"></i>Aadhaar Front Uploaded</div>
                        <input type="hidden" name="existing_aadhaar_front" value="1">
                    @endif
                    <input type="file" name="aadhaar_front" class="form-control" {{ ($verification && $verification->aadhaar_front) ? '' : 'required' }}>
                    <small class="text-muted">Upload a clear photo of your Aadhaar Card Front (max 5MB)</small>
                </div>

                <!-- Aadhaar Back -->
                <div class="col-md-6">
                    <label class="form-label">Aadhaar Card Back Side</label>
                    @if($verification && $verification->aadhaar_back)
                        <div class="mb-2 small text-success"><i class="bi bi-file-earmark-check me-1"></i>Aadhaar Back Uploaded</div>
                        <input type="hidden" name="existing_aadhaar_back" value="1">
                    @endif
                    <input type="file" name="aadhaar_back" class="form-control" {{ ($verification && $verification->aadhaar_back) ? '' : 'required' }}>
                    <small class="text-muted">Upload Aadhaar Back Side (max 5MB)</small>
                </div>

                <!-- PAN Card -->
                <div class="col-md-6">
                    <label class="form-label">PAN Card (Front Side)</label>
                    @if($verification && $verification->pan_card)
                        <div class="mb-2 small text-success"><i class="bi bi-file-earmark-check me-1"></i>PAN Card Uploaded</div>
                        <input type="hidden" name="existing_pan_card" value="1">
                    @endif
                    <input type="file" name="pan_card" class="form-control" {{ ($verification && $verification->pan_card) ? '' : 'required' }}>
                    <small class="text-muted">Upload PAN Card photo (max 5MB)</small>
                </div>

                <!-- Selfie -->
                <div class="col-md-6">
                    <label class="form-label">Live Selfie Verification Image</label>
                    @if($verification && $verification->selfie)
                        <div class="mb-2 small text-success"><i class="bi bi-camera me-1"></i>Selfie Uploaded</div>
                        <input type="hidden" name="existing_selfie" value="1">
                    @endif
                    <input type="file" name="selfie" class="form-control" {{ ($verification && $verification->selfie) ? '' : 'required' }}>
                    <small class="text-muted">Upload a recent clear close-up face selfie (max 5MB)</small>
                </div>
            </div>
        </div>

        <div class="card-glass-static p-4 mb-4">
            <h5 class="fw-bold mb-4" style="color:var(--text-primary);"><i class="bi bi-credit-card-2-front me-2" style="color:var(--brand-purple);"></i>Bank Payout Credentials</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Bank Account Holder Name</label>
                    <input type="text" name="bank_holder_name" class="form-control" value="{{ old('bank_holder_name', $profile->bank_holder_name ?? '') }}" placeholder="Aarav Sharma" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $profile->bank_name ?? '') }}" placeholder="State Bank of India" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Account Number</label>
                    <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $profile->bank_account_number ?? '') }}" placeholder="30012345678" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">IFSC Code</label>
                    <input type="text" name="bank_ifsc" class="form-control" value="{{ old('bank_ifsc', $profile->bank_ifsc ?? '') }}" placeholder="SBIN0001234" required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" class="btn-brand py-2 px-4" onclick="switchStep(2)">Next: Profile Details <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
    </div>

    <!-- STEP 2: PROFILE DETAILS -->
    <div class="onboard-step" id="step2">
        <div class="card-glass-static p-4 mb-4">
            <h5 class="fw-bold mb-4" style="color:var(--text-primary);"><i class="bi bi-person me-2" style="color:var(--brand-purple);"></i>Public Companion Profile</h5>
            
            <div class="row align-items-center mb-4">
                <div class="col-auto">
                    <div class="avatar-preview-container">
                        @if($user->profile_picture)
                            <img src="{{ $user->profile_picture_url }}" class="avatar-preview" id="profilePicPreview" alt="">
                        @else
                            <div class="avatar-preview-placeholder" id="profilePicPreviewPlaceholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <label class="form-label">Upload Profile Photo</label>
                    <input type="file" name="profile_picture" class="form-control" onchange="previewImage(this)">
                    <small class="text-muted">JPEG or PNG. High resolution portrait photos recommended</small>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Display Name / Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">City Location</label>
                    <select name="city_id" class="form-select" required>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ $user->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 mt-4 border-top pt-3">
                    <h6 class="fw-bold mb-3 text-theme-primary"><i class="bi bi-geo-alt-fill text-primary me-2"></i>GPS Location Details (Blinkit-style)</h6>
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="detectGPSLocation()">
                            <i class="bi bi-crosshair me-1"></i> Detect Current Location (GPS)
                        </button>
                        <span class="small text-muted ms-2" id="gps-status"></span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" id="loc_country" class="form-control" value="{{ old('country', $profile->country ?? 'India') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" id="loc_state" class="form-control" value="{{ old('state', $profile->state ?? '') }}" placeholder="Madhya Pradesh" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City Name</label>
                            <input type="text" name="city" id="loc_city" class="form-control" value="{{ old('city', $profile->city ?? '') }}" placeholder="Bhopal" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Area / Locality</label>
                            <input type="text" name="area" id="loc_area" class="form-control" value="{{ old('area', $profile->area ?? '') }}" placeholder="Arera Colony" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" name="latitude" id="loc_lat" class="form-control" value="{{ old('latitude', $profile->latitude ?? '') }}" placeholder="23.2599" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" name="longitude" id="loc_lng" class="form-control" value="{{ old('longitude', $profile->longitude ?? '') }}" placeholder="77.4126" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Languages Spoken (Comma-separated)</label>
                    <input type="text" name="languages[]" class="form-control" value="{{ old('languages', isset($profile->languages) ? implode(', ', (array)$profile->languages) : 'English, Hindi') }}" placeholder="English, Hindi, German" required>
                    <small class="text-muted">Provide languages you are comfortable chatting in</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Interests & Hobbies (Comma-separated)</label>
                    <input type="text" name="interests[]" class="form-control" value="{{ old('interests', isset($profile->interests) ? implode(', ', (array)$profile->interests) : 'Cafes, Reading, Board Games') }}" placeholder="Hiking, Food, Movies" required>
                    <small class="text-muted">Helps customer companions match hobbies with you</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Hourly Rate (₹ per hour)</label>
                    <input type="number" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $profile->hourly_rate ?? 500) }}" min="0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Years of Experience</label>
                    <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', $profile->experience_years ?? 1) }}" min="0" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Bio (about yourself)</label>
                    <textarea name="bio" class="form-control" rows="4" placeholder="Hello! I am Aarav. I love checking out local coffee houses, reading novels, and walking..." required>{{ old('bio', $profile->bio ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card-glass-static p-4 mb-4">
            <h5 class="fw-bold mb-4" style="color:var(--text-primary);"><i class="bi bi-stars me-2" style="color:var(--brand-purple);"></i>Select Offered Services</h5>
            
            <div class="row g-2">
                @foreach($services as $service)
                    <div class="col-md-6">
                        <div class="form-check p-2 border rounded-3 d-flex align-items-center gap-2" style="background:var(--surface-2);border-color:var(--border)!important;">
                            <input class="form-check-input ms-0 mt-0" type="checkbox" name="services[]" value="{{ $service->id }}" id="service-{{ $service->id }}" {{ in_array($service->id, $selectedServices) ? 'checked' : '' }}>
                            <label class="form-check-label text-theme-primary small" for="service-{{ $service->id }}">
                                <strong>{{ $service->name }}</strong> <span class="text-muted">({{ $service->category->name }})</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-surface py-2 px-4" onclick="switchStep(1)"><i class="bi bi-arrow-left me-2"></i>Back</button>
            <button type="button" class="btn-brand py-2 px-4" onclick="switchStep(3)">Next: Availability <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
    </div>

    <!-- STEP 3: AVAILABILITY SCHEDULE -->
    <div class="onboard-step" id="step3">
        <div class="card-glass-static p-4 mb-4">
            <h5 class="fw-bold mb-4" style="color:var(--text-primary);"><i class="bi bi-calendar-range me-2" style="color:var(--brand-purple);"></i>Weekly Available Time Slots</h5>
            <p class="text-muted small">Select the days of the week you are available for companion bookings, and define the start/end working hours for those days.</p>
            
            <div class="table-responsive">
                <table class="c-table align-middle">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Availability Status</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday'] as $day => $label)
                            @php
                                $avail = $availabilities->get($day);
                                $isAvail = $avail ? $avail->is_available : false;
                                $start = $avail ? $avail->start_time : '09:00';
                                $end = $avail ? $avail->end_time : '17:00';
                            @endphp
                            <tr>
                                <td data-label="Day"><strong class="text-theme-primary">{{ $label }}</strong></td>
                                <td data-label="Status">
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="availabilities[{{ $day }}][is_available]" value="0">
                                        <input class="form-check-input" type="checkbox" name="availabilities[{{ $day }}][is_available]" value="1" id="avail-switch-{{ $day }}" {{ $isAvail ? 'checked' : '' }} onchange="toggleHoursRow('{{ $day }}')">
                                        <label class="form-check-label text-muted small" for="avail-switch-{{ $day }}" id="lbl-{{ $day }}">{{ $isAvail ? 'Available' : 'Unavailable' }}</label>
                                    </div>
                                </td>
                                <td data-label="Start Time">
                                    <input type="time" name="availabilities[{{ $day }}][start_time]" class="form-control form-control-sm" id="start-{{ $day }}" value="{{ $start }}" style="max-width: 140px;" {{ $isAvail ? '' : 'disabled' }}>
                                </td>
                                <td data-label="End Time">
                                    <input type="time" name="availabilities[{{ $day }}][end_time]" class="form-control form-control-sm" id="end-{{ $day }}" value="{{ $end }}" style="max-width: 140px;" {{ $isAvail ? '' : 'disabled' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-surface py-2 px-4" onclick="switchStep(2)"><i class="bi bi-arrow-left me-2"></i>Back</button>
            <button type="submit" class="btn-brand py-2 px-4"><i class="bi bi-check-circle me-2"></i>Save & Submit Profile</button>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
    function switchStep(step) {
        // Hide all steps
        document.querySelectorAll('.onboard-step').forEach(el => el.classList.remove('active'));
        // Show selected step
        document.getElementById('step' + step).classList.add('active');

        // Update headers
        document.querySelectorAll('.wizard-tab').forEach((el, index) => {
            el.classList.remove('active');
            if (index + 1 < step) {
                el.classList.add('completed');
            } else {
                el.classList.remove('completed');
            }
        });
        document.getElementById('tabHeader' + step).classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleHoursRow(day) {
        const check = document.getElementById('avail-switch-' + day).checked;
        document.getElementById('start-' + day).disabled = !check;
        document.getElementById('end-' + day).disabled = !check;
        document.getElementById('lbl-' + day).textContent = check ? 'Available' : 'Unavailable';
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePicPreview');
                const placeholder = document.getElementById('profilePicPreviewPlaceholder');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (placeholder) {
                    const parent = placeholder.parentNode;
                    parent.innerHTML = `<img src="${e.target.result}" class="avatar-preview" id="profilePicPreview" alt="">`;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function detectGPSLocation() {
        const status = document.getElementById('gps-status');
        status.className = "small text-info ms-2";
        status.innerHTML = "<i class='bi bi-arrow-repeat spin'></i> Getting coordinates...";
        
        if (!navigator.geolocation) {
            status.className = "small text-danger ms-2";
            status.textContent = "Geolocation is not supported by your browser.";
            return;
        }
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('loc_lat').value = lat;
                document.getElementById('loc_lng').value = lng;
                
                status.innerHTML = "Coordinates detected. Reverse geocoding...";
                
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&addressdetails=1`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.address) {
                            const addr = data.address;
                            const country = addr.country || 'India';
                            const state = addr.state || '';
                            const city = addr.city || addr.town || addr.village || addr.state_district || '';
                            const area = addr.suburb || addr.neighbourhood || addr.county || '';
                            
                            document.getElementById('loc_country').value = country;
                            document.getElementById('loc_state').value = state;
                            document.getElementById('loc_city').value = city;
                            document.getElementById('loc_area').value = area;
                            
                            status.className = "small text-success ms-2";
                            status.textContent = "Location detected successfully!";
                        } else {
                            status.className = "small text-warning ms-2";
                            status.textContent = "GPS coordinates saved, but reverse geocoding failed. Please fill text fields manually.";
                        }
                    })
                    .catch(err => {
                        status.className = "small text-warning ms-2";
                        status.textContent = "GPS coordinates saved, geocoding timed out. Please fill text fields manually.";
                    });
            },
            function(error) {
                status.className = "small text-danger ms-2";
                status.textContent = "Permission denied or GPS error: " + error.message;
            }
        );
    }
</script>
@endsection
