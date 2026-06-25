@extends('layouts.admin')
@section('title', 'Partner Management - Admin Console')

@section('styles')
<style>
    .stat-card {
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: var(--shadow-sm);
        height: 100%;
    }
    .clickable-card {
        cursor: pointer;
        transition: var(--transition);
    }
    .clickable-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-color) !important;
        box-shadow: var(--shadow-md);
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .clickable-row {
        cursor: pointer;
        transition: var(--transition);
    }
    .clickable-row:hover {
        background-color: var(--sidebar-hover-bg) !important;
    }
    /* Scrollable table container with visible scrollbar */
    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 0.5rem;
    }
    .table-container::-webkit-scrollbar {
        height: 6px;
    }
    .table-container::-webkit-scrollbar-track {
        background: var(--bg-color);
        border-radius: 4px;
    }
    .table-container::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
    }
    .table-container::-webkit-scrollbar-thumb:hover {
        background: var(--primary-hover);
    }
    /* Ensure the table is compact and fits fully on one screen */
    .table-container table {
        width: 100%;
        max-width: 900px;
    }
    .table-container th,
    .table-container td {
        padding: 0.75rem 0.6rem;
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .actions-cell {
        min-width: 250px;
        white-space: nowrap;
    }
    /* Custom Modern Pagination */
    .pagination-custom .page-item .page-link {
        color: var(--text-muted);
        background-color: var(--card-bg);
        border: 1px solid var(--card-border);
        font-weight: 500;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pagination-custom .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
        box-shadow: 0 4px 10px var(--primary-glow);
    }
    .pagination-custom .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: var(--sidebar-hover-bg);
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-1px);
    }
    .pagination-custom .page-item.disabled .page-link {
        color: var(--text-muted);
        opacity: 0.5;
        background-color: var(--bg-color);
        border-color: var(--card-border);
    }
    .pagination-custom .page-link.rounded-circle {
        width: 34px;
        height: 34px;
        padding: 0;
    }
</style>
@endsection

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h3 class="fw-bold text-theme-primary mb-1">Partner Management</h3>
        <p class="text-muted mb-0">Monitor and manage companionship partners, verification status, subscriptions, and accounts.</p>
    </div>
</div>




<!-- Search & Filters Section -->
<div class="portal-card mb-4 py-3 px-4">
    <form action="{{ route('admin.kyc') }}" method="GET" id="filterForm">
        <input type="hidden" name="kyc_status" id="kycStatusInput" value="{{ request('kyc_status', 'all') }}">
        
        <!-- Search input box -->
        <div class="mb-3">
            <label for="searchInput" class="form-label small fw-bold text-muted mb-1">Search Partner</label>
            <div class="input-group">
                <span class="input-group-text bg-theme-card border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Search by Partner Name, Email, Mobile or Partner ID..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- KYC Status selector buttons -->
        <div>
            <label class="form-label small fw-bold text-muted mb-2">KYC Status</label>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <button type="button" class="btn btn-sm btn-filter px-3 py-1.5 rounded-pill {{ !request('kyc_status') || request('kyc_status') === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}" data-status="all">
                    All
                </button>
                <button type="button" class="btn btn-sm btn-filter px-3 py-1.5 rounded-pill {{ request('kyc_status') === 'pending' ? 'btn-warning text-theme-primary' : 'btn-outline-secondary' }}" data-status="pending">
                    Pending
                </button>
                <button type="button" class="btn btn-sm btn-filter px-3 py-1.5 rounded-pill {{ request('kyc_status') === 'approved' ? 'btn-success' : 'btn-outline-secondary' }}" data-status="approved">
                    Approved
                </button>
                <button type="button" class="btn btn-sm btn-filter px-3 py-1.5 rounded-pill {{ request('kyc_status') === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}" data-status="rejected">
                    Rejected
                </button>
                
                <div class="ms-auto d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-3 py-1.5 rounded-pill"><i class="bi bi-search me-1"></i>Search</button>
                    <a href="{{ route('admin.kyc') }}" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Partners Table -->
<div class="portal-card">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h5 class="fw-bold mb-0 text-theme-primary">Partners Directory</h5>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
            <i class="bi bi-person-plus me-1"></i> Add Partner
        </button>
    </div>
    
    <div class="table-container">
        <table class="table align-middle table-hover text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>Photo</th>
                    <th>Partner ID</th>
                    <th>Name</th>
                    <th class="text-end actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $p)
                    <tr class="clickable-row" onclick="window.location='{{ route('admin.partners.show', $p->id) }}'">
                        <td>
                            @if($p->profile_picture)
                                <img src="{{ $p->profile_picture_url }}" class="avatar-img" alt="Avatar" style="width: 40px; height: 40px;">
                            @else
                                <div class="avatar-img-placeholder" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold text-muted small">PT-{{ $p->id }}</td>
                        <td>
                            <div class="fw-bold text-theme-primary">{{ $p->name }}</div>
                        </td>
                        <td class="text-end actions-cell" onclick="event.stopPropagation();">
                            <div class="d-inline-flex gap-1 align-items-center flex-nowrap">
                                <a href="{{ route('admin.partners.show', $p->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2" style="font-size: 0.73rem;">
                                    <i class="bi bi-eye me-1"></i>Details
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2" style="font-size: 0.73rem;" data-bs-toggle="modal" data-bs-target="#editPartnerModal-{{ $p->id }}">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                                <form action="{{ route('admin.users.toggle', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $p->is_active ? 'btn-outline-warning' : 'btn-success' }} rounded-pill px-2" style="font-size: 0.73rem;">
                                        <i class="bi {{ $p->is_active ? 'bi-slash-circle' : 'bi-check-circle' }} me-1"></i>{{ $p->is_active ? 'Block' : 'Unblock' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.delete', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this partner account? This cannot be undone.');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2" style="font-size: 0.73rem;">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted text-wrap">
                            <i class="bi bi-people d-block fs-1 mb-2"></i>
                            No companion partners found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Professional Custom Pagination -->
    @if ($partners->hasPages())
        <nav class="d-flex justify-content-center mt-4" aria-label="Table navigation">
            <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($partners->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link rounded-pill px-3" href="{{ $partners->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                    </li>
                @endif

                {{-- Pagination Pages --}}
                @foreach ($partners->getUrlRange(max(1, $partners->currentPage() - 2), min($partners->lastPage(), $partners->currentPage() + 2)) as $page => $url)
                    @if ($page == $partners->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link rounded-circle">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link rounded-circle" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($partners->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-pill px-3" href="{{ $partners->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link rounded-pill px-3">Next<i class="bi bi-chevron-right ms-1"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>

<!-- Modal Definitions -->
<!-- Add Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-labelledby="addPartnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addPartnerModalLabel"><i class="bi bi-person-plus text-primary me-2"></i>Add New Companion Partner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.partners.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone / Mobile</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City / Location <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select" required>
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hourly Rate (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="hourly_rate" class="form-control" placeholder="e.g. 1500" required min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" class="form-control" value="India" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" class="form-control" value="Madhya Pradesh" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City (GPS) <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. Bhopal" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Area / Locality <span class="text-danger">*</span></label>
                            <input type="text" name="area" class="form-control" placeholder="e.g. MP Nagar" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Latitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="latitude" class="form-control" placeholder="e.g. 23.2599" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Longitude <span class="text-danger">*</span></label>
                            <input type="number" step="any" name="longitude" class="form-control" placeholder="e.g. 77.4126" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Short Bio</label>
                            <textarea name="bio" class="form-control" rows="3" placeholder="Enter short bio profile description..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Partner Modals -->
@foreach($partners as $p)
    <div class="modal fade" id="editPartnerModal-{{ $p->id }}" tabindex="-1" aria-labelledby="editPartnerModalLabel-{{ $p->id }}" aria-hidden="true" onclick="event.stopPropagation();">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editPartnerModalLabel-{{ $p->id }}"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Partner Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.partners.update', $p->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $p->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ $p->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone / Mobile</label>
                                <input type="text" name="phone" class="form-control" value="{{ $p->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="male" {{ $p->gender === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $p->gender === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $p->gender === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">City / Location <span class="text-danger">*</span></label>
                                <select name="city_id" class="form-select" required>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->id }}" {{ $p->city_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hourly Rate (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="hourly_rate" class="form-control" value="{{ intval($p->hourly_rate) }}" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                                <input type="text" name="country" class="form-control" value="{{ $p->country ?? 'India' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control" value="{{ $p->state ?? 'Madhya Pradesh' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">City (GPS) <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control" value="{{ $p->profile_city ?? '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Area / Locality <span class="text-danger">*</span></label>
                                <input type="text" name="area" class="form-control" value="{{ $p->area ?? '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Latitude <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="latitude" class="form-control" value="{{ $p->latitude ?? '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Longitude <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="longitude" class="form-control" value="{{ $p->longitude ?? '' }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Short Bio</label>
                                <textarea name="bio" class="form-control" rows="3">{{ $p->bio }}</textarea>
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
@endforeach

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kycStatusInput = document.getElementById('kycStatusInput');
        const filterForm = document.getElementById('filterForm');
        
        // Handle filter buttons
        document.querySelectorAll('#filterForm .btn-filter').forEach(button => {
            button.addEventListener('click', function () {
                const status = this.getAttribute('data-status');
                kycStatusInput.value = status;
                filterForm.submit();
            });
        });

        // Handle clickable statistics cards
        document.querySelectorAll('.clickable-card').forEach(card => {
            card.addEventListener('click', function () {
                const status = this.getAttribute('data-status');
                kycStatusInput.value = status;
                filterForm.submit();
            });
        });
    });
</script>
@endsection
