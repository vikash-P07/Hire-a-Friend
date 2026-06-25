@extends('layouts.admin')
@section('title', 'Notifications Center - Admin Console')

@section('content')
            <div class="row g-4">
                
                <!-- Notification Analytics -->
                <div class="col-12">
                    <div class="row g-3">
        <div class="col-md-4">
                            <div class="card p-3 border shadow-sm text-center">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Total Notifications Sent</small>
                                <div class="fs-4 fw-bold text-success">{{ \DB::table('notifications')->where('type','App\Notifications\AdminBroadcast')->count() }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 border shadow-sm text-center">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Notifications Read</small>
                                <div class="fs-4 fw-bold text-primary">{{ \DB::table('notifications')->whereNotNull('read_at')->count() }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 border shadow-sm text-center">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Unread Notifications</small>
                                <div class="fs-4 fw-bold text-info">{{ \DB::table('notifications')->whereNull('read_at')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compose Center -->
                <div class="col-md-7">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-send text-primary me-2"></i>Dispatch Global Broadcast</h5>
                        
                        <form action="{{ route('admin.notifications.send') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Broadcast Subject / Title</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Scheduled Platform Downtime Notice..." required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Broadcast Body Message</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="Type notification details here..." required></textarea>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Target Audience</label>
                                    <select name="target" class="form-select form-select-sm" required>
                                        <option value="all">Send to All Accounts</option>
                                        <option value="partners">Send to Companion Partners Only</option>
                                        <option value="customers">Send to Clients/Customers Only</option>
                                        @foreach($cities as $city)
                                            <option value="city-{{ $city->id }}">Send to users in {{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Delivery Channel</label>
                                    <div class="d-flex gap-2 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="channels[]" value="push" id="chPush" checked>
                                            <label class="form-check-label small" for="chPush">Push</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="channels[]" value="email" id="chEmail" checked>
                                            <label class="form-check-label small" for="chEmail">Email</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="channels[]" value="sms" id="chSms">
                                            <label class="form-check-label small" for="chSms">SMS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary rounded-pill w-100 mt-2 py-2">Dispatch Broadcast</button>
                        </form>
                    </div>
                </div>

                <!-- Simulation Info Logs -->
                <div class="col-md-5">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary">Simulated Delivery Channels</h5>
                        <p class="text-muted small">Notifications are pushed in real-time. In this sandbox session, all channels are simulated and recorded directly inside the admin audit logs.</p>
                        <div class="alert alert-info border-start border-4 small py-3">
                            <i class="bi bi-info-circle-fill me-2"></i> Pushed notifications use the Firebase Web-Push API and local SMTP email services if configured.
                        </div>
                    </div>
                </div>

            </div>
@endsection
