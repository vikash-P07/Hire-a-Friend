@extends('layouts.admin')
@section('title', 'Top Profiles Management - Admin Console')

@section('styles')
<style>
    .cursor-move {
        cursor: move;
        font-size: 1.1rem;
    }
    .drag-placeholder {
        background-color: var(--sidebar-hover-bg);
        border: 2px dashed var(--primary-color);
    }
    .table-container {
        width: 100%;
        overflow-x: auto;
    }
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-theme-primary mb-1">Homepage Management</h3>
    <p class="text-muted mb-0">Select, order, and toggle visibility of profiles featured in the "Top Profiles" section on the homepage.</p>
</div>

<div class="row g-4">
    <!-- Left Column: Curated Top Profiles -->
    <div class="col-12 col-xl-7">
        <div class="portal-card h-100">
            <h5 class="fw-bold text-theme-primary mb-3"><i class="bi bi-award-fill text-primary me-2"></i>Current Top Profiles</h5>
            <p class="text-muted small mb-4">Drag the handle <i class="bi bi-grip-vertical"></i> to reorder companions. Display sequence updates automatically in the database.</p>
            
            <div class="table-container">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;"></th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="top-list">
                        @forelse($top as $p)
                            <tr data-id="{{ $p->id }}" class="align-middle">
                                <td>
                                    <div class="py-2 px-1 handle cursor-move text-muted">
                                        <i class="bi bi-grip-vertical"></i>
                                    </div>
                                </td>
                                <td>
                                    @if($p->profile_picture)
                                        <img src="{{ $p->profile_picture_url }}" class="avatar-img" alt="Avatar" style="width: 40px; height: 40px;">
                                    @else
                                        <div class="avatar-img-placeholder" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                            {{ strtoupper(substr($p->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-theme-primary">{{ $p->name }}</div>
                                    <small class="text-muted">PT-{{ $p->id }}</small>
                                </td>
                                <td class="text-muted small">{{ $p->city->name ?? 'Anywhere' }}</td>
                                <td>
                                    <span class="text-warning fw-bold">★ {{ number_format($p->rating, 1) }}</span>
                                </td>
                                <td>
                                    @if($p->is_top_profile_visible)
                                        <span class="badge bg-success-subtle text-success py-1 px-2 rounded-pill small">Visible</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary py-1 px-2 rounded-pill small">Hidden</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form action="{{ route('admin.homepage.top.toggle-visibility', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $p->is_top_profile_visible ? 'btn-outline-secondary' : 'btn-success' }} rounded-pill" style="font-size: 0.75rem;">
                                                {{ $p->is_top_profile_visible ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.homepage.top.remove', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove from Top list?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" style="font-size: 0.75rem;">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="empty-row">
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-award d-block fs-1 mb-2"></i>
                                    No top profiles chosen yet. Add companions from the panel on the right.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Add Companions & Filters -->
    <div class="col-12 col-xl-5">
        <div class="portal-card">
            <h5 class="fw-bold text-theme-primary mb-4"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Add Companions</h5>
            
            <!-- Filters -->
            <form action="{{ route('admin.homepage.top') }}" method="GET" class="row g-2 mb-4">
                <div class="col-7">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name, email, ID..." value="{{ request('search') }}">
                </div>
                <div class="col-5">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Filters</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked Only</option>
                        <option value="approved_kyc" {{ request('status') === 'approved_kyc' ? 'selected' : '' }}>Approved KYC</option>
                        <option value="pending_kyc" {{ request('status') === 'pending_kyc' ? 'selected' : '' }}>Pending KYC</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-3 rounded-pill"><i class="bi bi-funnel"></i> Apply</button>
                    <a href="{{ route('admin.homepage.top') }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill">Reset</a>
                </div>
            </form>

            <!-- Companions Search List -->
            <div class="table-container">
                <table class="table align-middle table-hover text-nowrap" style="font-size: 0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Companion</th>
                            <th>Bookings</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companions as $c)
                            <tr>
                                <td>
                                    @if($c->profile_picture)
                                        <img src="{{ $c->profile_picture_url }}" class="avatar-img" alt="Avatar" style="width: 35px; height: 35px;">
                                    @else
                                        <div class="avatar-img-placeholder" style="width: 35px; height: 35px; font-size: 0.85rem;">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-theme-primary">{{ $c->name }}</div>
                                    <small class="text-muted">{{ $c->city->name ?? 'Anywhere' }} • ★{{ number_format($c->companionProfile->rating ?? 0, 1) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $c->bookingsAsPartner()->count() }} bookings</span>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.homepage.top.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $c->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-2.5" style="font-size: 0.75rem;">
                                            Add
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No companions found matching search.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom pagination links -->
            <div class="d-flex justify-content-center mt-3">
                @if ($companions->hasPages())
                    <nav aria-label="Table navigation">
                        <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                            @if ($companions->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $companions->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                                </li>
                            @endif

                            @foreach ($companions->getUrlRange(max(1, $companions->currentPage() - 2), min($companions->lastPage(), $companions->currentPage() + 2)) as $page => $url)
                                @if ($page == $companions->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link rounded-circle">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link rounded-circle" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($companions->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $companions->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const listEl = document.getElementById('top-list');
        if (listEl && listEl.querySelector('tr[data-id]')) {
            new Sortable(listEl, {
                handle: '.handle',
                animation: 150,
                placeholderClass: 'drag-placeholder',
                onEnd: function () {
                    const ids = [];
                    listEl.querySelectorAll('tr[data-id]').forEach(row => {
                        ids.push(row.getAttribute('data-id'));
                    });

                    fetch('{{ route("admin.homepage.top.reorder") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Order re-synchronized successfully.');
                        } else {
                            alert('Failed to save display order. Please refresh and try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error reordering top profiles:', error);
                    });
                }
            });
        }
    });
</script>
@endsection
