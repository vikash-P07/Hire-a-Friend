@extends('layouts.admin')
@section('title', 'Platform Commissions Console - Admin Console')

@section('content')
<div class="portal-card">
    <h4 class="fw-bold mb-4 text-theme-primary">Platform Commission Settings</h4>

    <form action="{{ route('admin.commissions.update') }}" method="POST">
        @csrf
        
        <!-- Global Commission Config Card -->
        <div class="card p-4 border mb-4 bg-theme-secondary">
            <h5 class="fw-bold text-theme-primary mb-3"><i class="bi bi-gear-fill text-primary me-2"></i>Global Rate Templates</h5>
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label text-theme-primary fw-semibold small">Default Platform Commission Rate (%)</label>
                    <div class="input-group">
                        <input type="number" name="global_commission" step="0.01" min="0" max="100" class="form-control" value="{{ $globalCommission }}" required>
                        <span class="input-group-text bg-theme-card border-start-0">%</span>
                    </div>
                    <div class="form-text text-muted">Applied to all companion split shares unless a partner-specific override is set below.</div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-theme-primary">Companion Partner Custom Overrides</h5>

        <!-- Filter Companion list -->
        <div class="row g-2 mb-3">
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-theme-card border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="partner-search" class="form-control border-start-0" placeholder="Search partners on page...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover" id="partners-table">
                <thead class="table-light">
                    <tr>
                        <th>Companion Name</th>
                        <th>Email Address</th>
                        <th>Current Hourly Rate</th>
                        <th>Global Commission</th>
                        <th>Partner Specific Override (%)</th>
                        <th>Effective Commission Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partners as $p)
                        <tr class="partner-row">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $p->profile_picture_url }}" alt="{{ $p->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    <div class="fw-bold text-theme-primary partner-name">{{ $p->name }}</div>
                                </div>
                            </td>
                            <td>{{ $p->email }}</td>
                            <td>₹{{ number_format($p->partnerProfile->hourly_rate ?? 0.00, 2) }}/hr</td>
                            <td><span class="text-muted">{{ $globalCommission }}%</span></td>
                            <td>
                                <div class="input-group input-group-sm" style="max-width: 140px;">
                                    <input type="number" name="partner_commissions[{{ $p->id }}]" step="0.01" min="0" max="100" class="form-control override-input" placeholder="Inherited" value="{{ $p->commission ? $p->commission->commission_percentage : '' }}">
                                    <span class="input-group-text bg-theme-card">%</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary px-2.5 py-1.5 effective-badge">
                                    {{ $p->commission ? $p->commission->commission_percentage : $globalCommission }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No companion partners registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @php
            $partners->appends(request()->query());
        @endphp
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

        <hr class="my-4" style="border-color:var(--border);">

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary px-5 py-2.5" style="border-radius: 10px;">
                <i class="bi bi-save me-2"></i>Save Commission Configurations
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Search partner on table client-side
    document.getElementById('partner-search').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.partner-row').forEach(row => {
            const name = row.querySelector('.partner-name').textContent.toLowerCase();
            if (name.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Update effective badge real-time on input change
    const globalRate = parseFloat('{{ $globalCommission }}');
    document.querySelectorAll('.override-input').forEach(input => {
        input.addEventListener('input', function(e) {
            const val = e.target.value.trim();
            const badge = e.target.closest('tr').querySelector('.effective-badge');
            if (val !== '') {
                badge.textContent = parseFloat(val).toFixed(2) + '%';
            } else {
                badge.textContent = globalRate.toFixed(2) + '%';
            }
        });
    });
</script>
@endsection
