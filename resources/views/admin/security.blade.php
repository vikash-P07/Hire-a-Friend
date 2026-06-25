@extends('layouts.admin')
@section('title', 'Security Console - Admin Console')

@section('content')
            <!-- 2FA and Roles overview -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary"><i class="bi bi-shield-lock text-primary me-2"></i>Two-Factor Authentication (2FA)</h5>
                        <p class="text-muted small">Enforce Two-Factor Authentication security for all administrative roles to protect listings, payouts, and customer details.</p>
                        
                        <form action="{{ route('admin.security.toggle-2fa') }}" method="POST" class="mt-4">
                            @csrf
                            <div class="d-flex justify-content-between align-items-center bg-theme-secondary p-3 rounded">
                                <span class="fw-bold small text-theme-primary">Global Admin 2FA Requirement</span>
                                <button type="submit" class="btn btn-sm {{ $is2FAEnabled ? 'btn-danger' : 'btn-success' }} rounded-pill px-4">
                                    {{ $is2FAEnabled ? 'Disable 2FA' : 'Enforce 2FA' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="portal-card h-100">
                        <h5 class="fw-bold mb-3 text-theme-primary"><i class="bi bi-key text-primary me-2"></i>System Account Permissions</h5>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-theme-primary fw-bold">Customer Account Role</span>
                                <span class="badge bg-secondary">Book meetups, post reviews, chat</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-theme-primary fw-bold">Partner Account Role</span>
                                <span class="badge bg-secondary">Offer companionship, KYC, earnings</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-theme-primary fw-bold">Super Admin Role</span>
                                <span class="badge bg-primary">Full system override capabilities</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Audit Logs list -->
            <div class="portal-card">
                <h5 class="fw-bold mb-4 text-theme-primary"><i class="bi bi-list-columns-reverse text-primary me-2"></i>System Security &amp; Audit Trail</h5>
                
                <div class="table-responsive">
                    <table class="table align-middle table-hover small">
                        <thead class="table-light">
                            <tr>
                                <th>Admin User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td class="fw-bold text-theme-primary">{{ $log->user->name ?? 'System' }}</td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $log->action }}</span></td>
                                    <td class="text-muted">{{ $log->description }}</td>
                                    <td class="text-muted">{{ $log->ip_address }}</td>
                                    <td class="text-muted">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">No audit trails recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($auditLogs->hasPages())
                    <nav class="d-flex justify-content-center mt-4" aria-label="Table navigation">
                        <ul class="pagination pagination-custom d-flex align-items-center gap-1">
                            {{-- Previous Page Link --}}
                            @if ($auditLogs->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link rounded-pill px-3"><i class="bi bi-chevron-left me-1"></i>Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $auditLogs->previousPageUrl() }}" rel="prev"><i class="bi bi-chevron-left me-1"></i>Previous</a>
                                </li>
                            @endif

                            {{-- Pagination Pages --}}
                            @foreach ($auditLogs->getUrlRange(max(1, $auditLogs->currentPage() - 2), min($auditLogs->lastPage(), $auditLogs->currentPage() + 2)) as $page => $url)
                                @if ($page == $auditLogs->currentPage())
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
                            @if ($auditLogs->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link rounded-pill px-3" href="{{ $auditLogs->nextPageUrl() }}" rel="next">Next<i class="bi bi-chevron-right ms-1"></i></a>
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
@endsection
