@extends('layouts.admin')
@section('title', 'Global Platform Settings - Admin Console')

@section('content')
            <div class="portal-card">
                <h4 class="fw-bold mb-4 text-theme-primary">Global Configuration Console</h4>
                
                @php
                    $site_name = \App\Models\Setting::get('site_name', 'Companion');
                    $contact_email = \App\Models\Setting::get('contact_email', 'admin@companion.com');
                    $platform_commission = \App\Models\Setting::get('platform_commission', '10');
                    $currency = \App\Models\Setting::get('currency', '₹');
                    $payment_gateway = \App\Models\Setting::get('payment_gateway', 'stripe');
                    $smtp_host = \App\Models\Setting::get('smtp_host', 'smtp.mailtrap.io');
                @endphp

                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Part 1: General Settings -->
                        <div class="col-12 border-bottom pb-2"><h5 class="fw-bold mb-0 text-primary">General Settings</h5></div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">Site / Brand Name</label>
                                <input type="text" name="site_name" class="form-control form-control-sm" value="{{ $site_name }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">System Support Email</label>
                                <input type="email" name="contact_email" class="form-control form-control-sm" value="{{ $contact_email }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">Default Platform Currency</label>
                                <select name="currency" class="form-select form-select-sm" required>
                                    <option value="₹" {{ $currency == '₹' ? 'selected' : '' }}>Indian Rupee (₹)</option>
                                    <option value="$" {{ $currency == '$' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="€" {{ $currency == '€' ? 'selected' : '' }}>Euro (€)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">Platform Commission Fee (%)</label>
                                <input type="number" step="0.1" name="platform_commission" class="form-control form-control-sm" value="{{ $platform_commission }}" min="0" max="100" required>
                            </div>
                        </div>

                        <!-- Part 2: Gateway Configuration -->
                        <div class="col-12 border-bottom pb-2 mt-4"><h5 class="fw-bold mb-0 text-primary">Payment &amp; SMTP Credentials</h5></div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">Payment Provider Gateway</label>
                                <select name="payment_gateway" class="form-select form-select-sm" required>
                                    <option value="stripe" {{ $payment_gateway === 'stripe' ? 'selected' : '' }}>Stripe Integration</option>
                                    <option value="razorpay" {{ $payment_gateway === 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                    <option value="paypal" {{ $payment_gateway === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">SMTP Server Host</label>
                                <input type="text" name="smtp_host" class="form-control form-control-sm" value="{{ $smtp_host }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">SMS Gateway Provider</label>
                                <select name="sms_gateway" class="form-select form-select-sm">
                                    <option value="twilio">Twilio API</option>
                                    <option value="nexmo">Vonage / Nexmo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">Platform Tax / GST Rate (%)</label>
                                <input type="number" name="tax_rate" class="form-control form-control-sm" value="18" required>
                            </div>
                        </div>

                        <!-- Part 3: Social logins configurations -->
                        <div class="col-12 border-bottom pb-2 mt-4"><h5 class="fw-bold mb-0 text-primary">Social Login Credentials</h5></div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">Google Client ID</label>
                                <input type="text" class="form-control form-control-sm" value="google-client-oauth-key-placeholder" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-theme-primary">Google Client Secret</label>
                                <input type="password" class="form-control form-control-sm" value="••••••••••••••••••••••••" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold">Save Settings Configurations</button>
                    </div>

                </form>
            </div>
@endsection
