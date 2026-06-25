@extends('layouts.admin')

@section('title', 'Chat Moderation')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 fw-bold mb-0 text-theme-primary">Chat Moderation</h2>
        <p class="text-muted mb-0">Monitor and manage platform conversations.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        
        <form method="GET" action="{{ route('conversations') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" style="background: var(--primary-color); border-color: var(--primary-color);">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle text-nowrap mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="fw-semibold">Conversation ID</th>
                        <th class="fw-semibold">Customer</th>
                        <th class="fw-semibold">Companion Partner</th>
                        <th class="fw-semibold">Messages</th>
                        <th class="fw-semibold">Status</th>
                        <th class="fw-semibold">Last Message At</th>
                        <th class="fw-semibold text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($conversations as $conv)
                    <tr>
                        <td><span class="text-muted fw-semibold">#{{ $conv->id }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($conv->customer->profile_picture)
                                    <img src="{{ $conv->customer->profile_picture_url }}" class="rounded-circle me-2" width="35" height="35" style="object-fit:cover;">
                                @else
                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center text-white" style="width:35px;height:35px;background:var(--primary-color);">
                                        {{ substr($conv->customer->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $conv->customer->name }}</div>
                                    <div class="small text-muted">{{ $conv->customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($conv->companion->profile_picture)
                                    <img src="{{ $conv->companion->profile_picture_url }}" class="rounded-circle me-2" width="35" height="35" style="object-fit:cover;">
                                @else
                                    <div class="rounded-circle me-2 d-flex align-items-center justify-content-center text-white" style="width:35px;height:35px;background:#ec4899;">
                                        {{ substr($conv->companion->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $conv->companion->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary rounded-pill">{{ $conv->messages_count }}</span>
                        </td>
                        <td>
                            @if($conv->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2">Active</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2">Blocked</span>
                            @endif
                        </td>
                        <td>
                            @if($conv->last_message_at)
                                {{ \Carbon\Carbon::parse($conv->last_message_at)->format('d M Y, h:i A') }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('conversations.show', $conv->id) }}" class="btn btn-sm btn-light border" title="View Chat">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-chat-square-dots fs-1 d-block mb-3 opacity-50"></i>
                                No conversations found.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Showing {{ $conversations->firstItem() ?? 0 }} to {{ $conversations->lastItem() ?? 0 }} of {{ $conversations->total() }} conversations
            </div>
            <div>
                {{ $conversations->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
</div>
@endsection
