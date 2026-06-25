@extends('layouts.admin')
@section('title', 'Partner Details - Admin Console')

@section('styles')
<style>
    /* Scrollable table with hover scrollbar */
    .table-responsive {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 60vh;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 0.5rem;
        scrollbar-width: thin;
        scrollbar-color: transparent transparent;
    }
    .table-responsive:hover {
        scrollbar-color: rgba(124, 58, 237, 0.45) transparent;
    }
    .table-responsive::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: transparent;
        border-radius: 8px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: transparent;
        border-radius: 8px;
    }
    .table-responsive:hover::-webkit-scrollbar-thumb {
        background: rgba(124, 58, 237, 0.45);
    }
    .table-responsive:hover::-webkit-scrollbar-thumb:hover {
        background: rgba(124, 58, 237, 0.7);
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-theme-primary mb-1">Partner Details: {{ $partner->name }}</h3>
            <p class="text-muted mb-0">Review details, transactions, active status, and KYC verification documents.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Edit Details Button -->
            <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#editPartnerDetailsModal">
                <i class="bi bi-pencil-square me-1"></i> Edit Details
            </button>
            <!-- Status Toggle -->
            <form action="{{ route('admin.users.toggle', $partner->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn {{ $partner->is_active ? 'btn-danger' : 'btn-success' }} rounded-pill px-4">
                    <i class="bi {{ $partner->is_active ? 'bi-slash-circle' : 'bi-check-circle' }} me-1"></i>
                    {{ $partner->is_active ? 'Suspend Account' : 'Activate Account' }}
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Profile & Stats Info -->
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="portal-card mb-4 text-center">
            <div class="mb-3">
                @if($partner->profile_picture)
                    <img src="{{ $partner->profile_picture_url }}" class="rounded-circle border border-primary border-3" style="width: 120px; height: 120px; object-fit: cover;" alt="Avatar">
                @else
                    <div class="avatar-img-placeholder mx-auto rounded-circle d-flex align-items-center justify-content-center border border-primary border-3" style="width: 120px; height: 120px; font-size: 3rem;">
                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <h4 class="fw-bold text-theme-primary mb-1">{{ $partner->name }}</h4>
            <span class="badge bg-secondary-subtle text-secondary-emphasis mb-3 text-uppercase">PT-{{ $partner->id }}</span>
            
            <div class="text-start border-top pt-3 mt-3">
                <table class="table table-borderless table-sm small">
                    <tr><td class="text-muted">Email:</td><td class="text-theme-primary fw-medium text-break">{{ $partner->email }}</td></tr>
                    <tr><td class="text-muted">Phone:</td><td class="text-theme-primary fw-medium">{{ $partner->phone ?? 'Not Provided' }}</td></tr>
                    <tr><td class="text-muted">Directory City:</td><td class="text-theme-primary fw-medium">{{ $partner->city->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Country:</td><td class="text-theme-primary fw-medium">{{ $profile?->country ?? 'India' }}</td></tr>
                    <tr><td class="text-muted">State:</td><td class="text-theme-primary fw-medium">{{ $profile?->state ?? 'Madhya Pradesh' }}</td></tr>
                    <tr><td class="text-muted">GPS City:</td><td class="text-theme-primary fw-medium">{{ $profile?->city ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Area / Locality:</td><td class="text-theme-primary fw-medium">{{ $profile?->area ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Coordinates:</td><td class="text-theme-primary fw-medium">{{ ($profile?->latitude && $profile?->longitude) ? ($profile->latitude . ', ' . $profile->longitude) : '-' }}</td></tr>
                    <tr><td class="text-muted">Gender:</td><td class="text-theme-primary fw-medium text-capitalize">{{ $partner->gender ?? 'Other' }}</td></tr>
                    <tr><td class="text-muted">Hourly Rate:</td><td class="text-primary fw-bold">₹{{ number_format($profile?->hourly_rate ?? 0) }}/hr</td></tr>
                    <tr><td class="text-muted">Rating:</td><td class="text-warning fw-bold">⭐ {{ number_format($profile?->rating ?? 0.0, 1) }}</td></tr>
                    <tr><td class="text-muted">Joined:</td><td class="text-theme-primary fw-medium">{{ $partner->created_at->format('M d, Y') }}</td></tr>
                </table>
            </div>

            <div class="text-start border-top pt-3 mt-3">
                <h6 class="fw-bold text-theme-primary mb-2">Short Bio</h6>
                <p class="text-muted small mb-0">{{ $profile?->bio ?? 'No bio description provided.' }}</p>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="portal-card">
            <h5 class="fw-bold text-theme-primary mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Performance & Stats</h5>
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="p-3 bg-theme-secondary rounded border">
                        <div class="text-muted small">Total Bookings</div>
                        <div class="fs-4 fw-bold text-theme-primary">{{ $bookingsCount }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-theme-secondary rounded border">
                        <div class="text-muted small">Total Earnings</div>
                        <div class="fs-4 fw-bold text-primary">₹{{ number_format($totalEarnings) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Documents & Actions -->
    <div class="col-lg-8">
        <div class="portal-card mb-4">
            <h5 class="fw-bold text-theme-primary mb-4"><i class="bi bi-shield-check text-primary me-2"></i>KYC Verification Documents</h5>
            
            @if($document)
                <div class="row g-4">
                    <!-- Aadhaar Card Column -->
                    <div class="col-md-4">
                        <div class="p-3 rounded border bg-theme-secondary-subtle h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-theme-primary">Aadhaar Card</span>
                                @if($document->aadhaar_status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($document->aadhaar_status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-theme-primary">Pending</span>
                                @endif
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <span class="small text-muted d-block mb-1">Front</span>
                                    @if($document->aadhaar_front)
                                        @if(Str::endsWith($document->aadhaar_front, '.pdf'))
                                            <a href="{{ asset('storage/' . $document->aadhaar_front) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100 py-3"><i class="bi bi-file-earmark-pdf fs-4"></i><br>View PDF</a>
                                        @else
                                            <a href="{{ asset('storage/' . $document->aadhaar_front) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $document->aadhaar_front) }}" class="img-fluid rounded border" style="height: 80px; width: 100%; object-fit: cover;" alt="Aadhaar Front">
                                            </a>
                                        @endif
                                    @else
                                        <div class="bg-theme-secondary text-center py-3 rounded text-muted small">No front image</div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <span class="small text-muted d-block mb-1">Back</span>
                                    @if($document->aadhaar_back)
                                        @if(Str::endsWith($document->aadhaar_back, '.pdf'))
                                            <a href="{{ asset('storage/' . $document->aadhaar_back) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100 py-3"><i class="bi bi-file-earmark-pdf fs-4"></i><br>View PDF</a>
                                        @else
                                            <a href="{{ asset('storage/' . $document->aadhaar_back) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $document->aadhaar_back) }}" class="img-fluid rounded border" style="height: 80px; width: 100%; object-fit: cover;" alt="Aadhaar Back">
                                            </a>
                                        @endif
                                    @else
                                        <div class="bg-theme-secondary text-center py-3 rounded text-muted small">No back image</div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($document->aadhaar_status === 'pending')
                                <div class="mt-auto pt-3 d-flex gap-2">
                                    <form action="{{ route('admin.kyc.action', [$document->id, 'approve']) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="document_type" value="aadhaar">
                                        <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill"><i class="bi bi-check-circle"></i> Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-grow-1 rounded-pill" data-bs-toggle="modal" data-bs-target="#rejectAadhaarModal">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- PAN Card Column -->
                    <div class="col-md-4">
                        <div class="p-3 rounded border bg-theme-secondary-subtle h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-theme-primary">PAN Card</span>
                                @if($document->pan_status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($document->pan_status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-theme-primary">Pending</span>
                                @endif
                            </div>
                            
                            <div class="mb-3 text-center">
                                <span class="small text-muted d-block text-start mb-1">PAN Card Image</span>
                                @if($document->pan_card)
                                    @if(Str::endsWith($document->pan_card, '.pdf'))
                                        <a href="{{ asset('storage/' . $document->pan_card) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100 py-3"><i class="bi bi-file-earmark-pdf fs-4"></i><br>View PDF</a>
                                    @else
                                        <a href="{{ asset('storage/' . $document->pan_card) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $document->pan_card) }}" class="img-fluid rounded border" style="height: 80px; width: 100%; object-fit: cover;" alt="PAN Card">
                                        </a>
                                    @endif
                                @else
                                    <div class="bg-theme-secondary text-center py-3 rounded text-muted small">No PAN image</div>
                                @endif
                            </div>
                            
                            @if($document->pan_status === 'pending')
                                <div class="mt-auto pt-3 d-flex gap-2">
                                    <form action="{{ route('admin.kyc.action', [$document->id, 'approve']) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="document_type" value="pan">
                                        <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill"><i class="bi bi-check-circle"></i> Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-grow-1 rounded-pill" data-bs-toggle="modal" data-bs-target="#rejectPanModal">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Selfie Column -->
                    <div class="col-md-4">
                        <div class="p-3 rounded border bg-theme-secondary-subtle h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-theme-primary">Selfie</span>
                                @if($document->selfie_status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($document->selfie_status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-theme-primary">Pending</span>
                                @endif
                            </div>
                            
                            <div class="mb-3 text-center">
                                <span class="small text-muted d-block text-start mb-1">Selfie Verification</span>
                                @if($document->selfie)
                                    @if(Str::endsWith($document->selfie, '.pdf'))
                                        <a href="{{ asset('storage/' . $document->selfie) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100 py-3"><i class="bi bi-file-earmark-pdf fs-4"></i><br>View PDF</a>
                                    @else
                                        <a href="{{ asset('storage/' . $document->selfie) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $document->selfie) }}" class="img-fluid rounded border" style="height: 80px; width: 100%; object-fit: cover;" alt="Selfie">
                                        </a>
                                    @endif
                                @else
                                    <div class="bg-theme-secondary text-center py-3 rounded text-muted small">No selfie image</div>
                                @endif
                            </div>
                            
                            @if($document->selfie_status === 'pending')
                                <div class="mt-auto pt-3 d-flex gap-2">
                                    <form action="{{ route('admin.kyc.action', [$document->id, 'approve']) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="document_type" value="selfie">
                                        <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill"><i class="bi bi-check-circle"></i> Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-grow-1 rounded-pill" data-bs-toggle="modal" data-bs-target="#rejectSelfieModal">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Rejection Modals -->
                <!-- Aadhaar Reject Modal -->
                <div class="modal fade" id="rejectAadhaarModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 p-3 shadow-lg text-start">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-theme-primary">Reject Aadhaar Card</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.kyc.action', [$document->id, 'reject']) }}" method="POST">
                                @csrf
                                <input type="hidden" name="document_type" value="aadhaar">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Rejection Reason</label>
                                        <textarea name="kyc_notes" class="form-control" rows="4" placeholder="Describe why the Aadhaar Card was rejected..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Submit Rejection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PAN Reject Modal -->
                <div class="modal fade" id="rejectPanModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 p-3 shadow-lg text-start">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-theme-primary">Reject PAN Card</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.kyc.action', [$document->id, 'reject']) }}" method="POST">
                                @csrf
                                <input type="hidden" name="document_type" value="pan">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Rejection Reason</label>
                                        <textarea name="kyc_notes" class="form-control" rows="4" placeholder="Describe why the PAN Card was rejected..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Submit Rejection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Selfie Reject Modal -->
                <div class="modal fade" id="rejectSelfieModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 p-3 shadow-lg text-start">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold text-theme-primary">Reject Selfie</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.kyc.action', [$document->id, 'reject']) }}" method="POST">
                                @csrf
                                <input type="hidden" name="document_type" value="selfie">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Rejection Reason</label>
                                        <textarea name="kyc_notes" class="form-control" rows="4" placeholder="Describe why the Selfie was rejected..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Submit Rejection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-4 text-muted small">
                    <i class="bi bi-file-earmark-x fs-1 mb-2 d-block"></i>
                    No KYC documents have been uploaded by this companion partner yet.
                </div>
            @endif
        </div>

        <!-- Booking Log -->
        <div class="portal-card">
            <h5 class="fw-bold text-theme-primary mb-4"><i class="bi bi-calendar3 text-primary me-2"></i>Recent Bookings Log</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover small">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $b)
                            <tr>
                                <td class="fw-bold">#{{ $b->id }}</td>
                                <td>{{ $b->customer->name ?? 'N/A' }}</td>
                                <td>{{ $b->booking_date->format('M d, Y') }}</td>
                                <td class="fw-bold text-primary">₹{{ number_format($b->total_amount) }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary-emphasis text-uppercase" style="font-size: 0.65rem;">
                                        {{ $b->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">No booking logs recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Details Modal -->
<div class="modal fade" id="editPartnerDetailsModal" tabindex="-1" aria-labelledby="editPartnerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editPartnerDetailsModalLabel"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Partner Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $partner->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ $partner->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone / Mobile</label>
                            <input type="text" name="phone" class="form-control" value="{{ $partner->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="male" {{ $partner->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $partner->gender === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $partner->gender === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Directory City <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select" required>
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}" {{ $partner->city_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hourly Rate (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="hourly_rate" class="form-control" value="{{ intval($profile?->hourly_rate ?? 0) }}" required min="0">
                        </div>

                        <!-- GPS Location Info -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" class="form-control" value="{{ $profile?->country ?? 'India' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" class="form-control" value="{{ $profile?->state ?? 'Madhya Pradesh' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City (GPS) <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ $profile?->city ?? ($partner->city->name ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Area / Locality <span class="text-danger">*</span></label>
                            <input type="text" name="area" class="form-control" value="{{ $profile?->area ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Latitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="latitude" class="form-control" value="{{ $profile?->latitude ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Longitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="longitude" class="form-control" value="{{ $profile?->longitude ?? '' }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Short Bio</label>
                            <textarea name="bio" class="form-control" rows="3">{{ $profile?->bio ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
