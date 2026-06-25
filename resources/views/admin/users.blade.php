@extends('layouts.admin')
@section('title', 'User Accounts Directory - Admin Console')

@section('styles')
<style>
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
<div class="portal-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0 text-theme-primary">User Accounts Directory</h4>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-person-plus me-1"></i> Add User
                    </button>
                </div>

                <!-- Advanced Filters -->
                <form action="{{ route('admin.users') }}" method="GET" class="row g-2 mb-4">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-theme-card border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email or phone..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="partner" {{ request('role') == 'partner' ? 'selected' : '' }}>Partner</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended Only</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i></button>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>City</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-theme-primary">{{ $u->name }}</div>
                                        <small class="text-muted">{{ $u->phone ?? 'No Phone' }}</small>
                                    </td>
                                    <td class="text-muted small">{{ $u->email }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary text-uppercase" style="font-size: 0.7rem;">{{ $u->role }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $u->city->name ?? 'Anywhere' }}</td>
                                    <td>
                                        @if($u->is_active)
                                            <span class="badge bg-success-subtle text-success py-1 px-2.5 rounded-pill small">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger py-1 px-2.5 rounded-pill small">Suspended</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1 align-items-center">
                                            <!-- View details modal trigger -->
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#userModal{{ $u->id }}" style="font-size: 0.75rem;">
                                                Details
                                            </button>

                                            <!-- Edit user details modal trigger -->
                                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2.5" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}" style="font-size: 0.75rem;">
                                                Edit
                                            </button>

                                            @if($u->id !== auth()->id())
                                                <!-- Dropdown to choose among 3 roles -->
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 dropdown-toggle" type="button" id="roleDropdown{{ $u->id }}" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.75rem;">
                                                        Change Role
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="roleDropdown{{ $u->id }}" style="border-radius: 12px; font-size: 0.8rem;">
                                                        <li>
                                                            <form action="{{ route('admin.users.toggle-role', [$u->id, 'customer']) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item py-1.5 {{ $u->role === 'customer' ? 'active bg-primary text-white' : '' }}">
                                                                    <i class="bi bi-person me-1"></i>Customer / User
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.users.toggle-role', [$u->id, 'partner']) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item py-1.5 {{ $u->role === 'partner' ? 'active bg-primary text-white' : '' }}">
                                                                    <i class="bi bi-people me-1"></i>Companion Partner
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.users.toggle-role', [$u->id, 'admin']) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item py-1.5 {{ $u->role === 'admin' ? 'active bg-primary text-white' : '' }}" onclick="return confirm('Are you sure you want to promote this user to System Admin?');">
                                                                    <i class="bi bi-shield-lock me-1"></i>System Admin
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- Status Toggle -->
                                                <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-danger' : 'btn-success' }} rounded-pill px-2.5" style="font-size: 0.75rem;">
                                                        {{ $u->is_active ? 'Suspend' : 'Activate' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No user accounts found matching criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Professional Custom Pagination -->
                @if ($users->hasPages())
                    <nav class="d-flex justify-content-center mt-4" aria-label="Table navigation">
                        <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                            {{-- Previous Page Link --}}
                            @if ($users->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $users->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                                </li>
                            @endif

                            {{-- Pagination Pages --}}
                            @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                                @if ($page == $users->currentPage())
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
                            @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $users->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
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

<!-- Modal Definitions for details -->
@foreach($allUsersList as $u)
    <div class="modal fade" id="userModal{{ $u->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $u->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 18px;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="userModalLabel{{ $u->id }}"><i class="bi bi-person-circle text-primary me-2"></i>User Profile Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold mb-3 text-theme-primary">Profile Details</h6>
                            <table class="table table-sm table-borderless small">
                                <tr><td class="text-muted">Full Name:</td><td class="text-theme-primary fw-medium">{{ $u->name }}</td></tr>
                                <tr><td class="text-muted">Email:</td><td class="text-theme-primary">{{ $u->email }}</td></tr>
                                <tr><td class="text-muted">Phone:</td><td class="text-theme-primary">{{ $u->phone ?? 'Not provided' }}</td></tr>
                                <tr><td class="text-muted">Role:</td><td><span class="badge bg-secondary text-uppercase">{{ $u->role }}</span></td></tr>
                                <tr><td class="text-muted">Gender:</td><td class="text-theme-primary">{{ ucfirst($u->gender ?? 'Not stated') }}</td></tr>
                                <tr><td class="text-muted">Registered:</td><td class="text-theme-primary">{{ $u->created_at->format('M d, Y') }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3 text-theme-primary">Wallet &amp; Platform Activity</h6>
                            <table class="table table-sm table-borderless small">
                                <tr><td class="text-muted">Account Status:</td><td>{!! $u->is_active ? '<span class="text-success fw-bold">Active</span>' : '<span class="text-danger fw-bold">Suspended</span>' !!}</td></tr>
                                <tr><td class="text-muted">Wallet Balance:</td><td class="text-theme-primary fw-bold">₹0.00</td></tr>
                                <tr><td class="text-muted">Total Meetups Booked:</td><td class="text-theme-primary">{{ $u->bookingsAsCustomer->count() }}</td></tr>
                                <tr><td class="text-muted">Total Meetups Completed:</td><td class="text-theme-primary">{{ $u->bookingsAsPartner->count() }}</td></tr>
                            </table>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <h6 class="fw-bold mb-2 text-theme-primary">Recent Transactions / Booking Log</h6>
                            <div class="table-responsive">
                                <table class="table table-sm small">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Booking ID</th>
                                            <th>Date</th>
                                            <th>Gross Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $allBookings = $u->role === 'partner' ? $u->bookingsAsPartner : $u->bookingsAsCustomer;
                                        @endphp
                                        @forelse($allBookings->take(5) as $b)
                                            <tr>
                                                <td>#{{ $b->id }}</td>
                                                <td>{{ $b->booking_date->format('Y-m-d') }}</td>
                                                <td class="fw-bold">₹{{ number_format($b->total_amount) }}</td>
                                                <td><span class="badge-status badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center text-muted small py-2">No transaction logs recorded.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if($u->role !== 'admin')
                        <!-- Danger Delete Action -->
                        <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="me-auto" onsubmit="return confirm('Are you sure you want to permanently delete this user account? This cannot be undone.');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3"><i class="bi bi-trash me-1"></i>Delete User Account</button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addUserModalLabel"><i class="bi bi-person-plus text-primary me-2"></i>Add New User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
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
                                <option value="transgender">Transgender</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City / Location <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select" required>
                                @php
                                    $cities = \App\Models\City::all();
                                @endphp
                                @foreach($cities as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="customer">Customer</option>
                                <option value="partner">Companion Partner</option>
                                <option value="admin">System Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modals -->
@foreach($allUsersList as $u)
    <div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $u->id }}" aria-hidden="true" onclick="event.stopPropagation();">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 18px;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editUserModalLabel{{ $u->id }}"><i class="bi bi-pencil-square text-primary me-2"></i>Edit User Account Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.update', $u->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone / Mobile</label>
                                <input type="text" name="phone" class="form-control" value="{{ $u->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="male" {{ $u->gender === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $u->gender === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="transgender" {{ $u->gender === 'transgender' ? 'selected' : '' }}>Transgender</option>
                                    <option value="other" {{ $u->gender === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">City / Location <span class="text-danger">*</span></label>
                                <select name="city_id" class="form-select" required>
                                    @php
                                        $cities = \App\Models\City::all();
                                    @endphp
                                    @foreach($cities as $c)
                                        <option value="{{ $c->id }}" {{ $u->city_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select" required>
                                    <option value="customer" {{ $u->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="partner" {{ $u->role === 'partner' ? 'selected' : '' }}>Companion Partner</option>
                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>System Admin</option>
                                </select>
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
