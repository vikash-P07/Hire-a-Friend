@extends('layouts.admin')

@section('title', 'View Conversation')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('conversations') }}" class="btn btn-light border rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="h3 fw-bold mb-0 text-theme-primary">Conversation #{{ $conversation->id }}</h2>
            <p class="text-muted mb-0">Between {{ $conversation->customer->name }} and {{ $conversation->companion->name }}</p>
        </div>
    </div>
    
    @if($conversation->status === 'active')
    <form action="{{ route('conversations.block', $conversation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to block this conversation? No further messages can be sent.')">
        @csrf
        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2 px-4 py-2" style="border-radius: var(--radius-md);">
            <i class="bi bi-slash-circle"></i> Block Conversation
        </button>
    </form>
    @else
        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 fs-6 rounded-pill">
            <i class="bi bi-shield-lock me-1"></i> Blocked
        </span>
    @endif
</div>

<div class="row">
    <!-- Chat History -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="height: 600px; display: flex; flex-direction: column;">
            <div class="card-header bg-theme-card border-bottom py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold">Chat History</span>
                <span class="text-muted small">{{ $conversation->messages->count() }} messages</span>
            </div>
            <div class="card-body p-4" style="overflow-y: auto; flex: 1; background: #f8f9fa;">
                @forelse($conversation->messages as $msg)
                    @php
                        $isCustomer = $msg->sender_id === $conversation->customer_id;
                        $sender = $isCustomer ? $conversation->customer : $conversation->companion;
                    @endphp
                    
                    <div class="d-flex mb-4 {{ $isCustomer ? 'justify-content-start' : 'justify-content-end' }}">
                        @if($isCustomer)
                            <img src="{{ $sender->profile_picture_url ?? asset('assets/images/default-avatar.png') }}" class="rounded-circle me-2 flex-shrink-0" width="36" height="36" style="object-fit:cover;">
                        @endif
                        
                        <div class="d-flex flex-column {{ $isCustomer ? 'align-items-start' : 'align-items-end' }}" style="max-width: 75%;">
                            <div class="small text-muted mb-1 px-1">
                                <span class="fw-bold">{{ $sender->name }}</span> 
                                <span style="font-size: 0.75rem;">{{ $msg->created_at->format('M d, h:i A') }}</span>
                            </div>
                            
                            <div class="p-3 shadow-sm" style="border-radius: 1rem; {{ $isCustomer ? 'background: #ffffff; border-top-left-radius: 0;' : 'background: #e0e7ff; border-top-right-radius: 0;' }}">
                                @if($msg->type === 'text')
                                    <div style="word-break: break-word;">{!! nl2br(e($msg->message)) !!}</div>
                                @elseif($msg->type === 'image')
                                    <div class="mb-2 text-muted small"><i class="bi bi-image"></i> Image Attached</div>
                                    <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank">
                                        <img src="{{ Storage::url($msg->attachment_path) }}" class="img-fluid rounded border" style="max-height: 200px;">
                                    </a>
                                @elseif($msg->type === 'pdf')
                                    <a href="{{ Storage::url($msg->attachment_path) }}" target="_blank" class="d-flex align-items-center gap-2 text-decoration-none p-2 border rounded bg-theme-card">
                                        <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                        <span class="text-theme-primary text-truncate" style="max-width: 200px;">Document.pdf</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        @if(!$isCustomer)
                            <img src="{{ $sender->profile_picture_url ?? asset('assets/images/default-avatar.png') }}" class="rounded-circle ms-2 flex-shrink-0" width="36" height="36" style="object-fit:cover;">
                        @endif
                    </div>
                @empty
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                        <i class="bi bi-chat-square opacity-25" style="font-size: 3rem;"></i>
                        <p class="mt-2">No messages in this conversation yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Info Sidebar -->
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    @if($conversation->customer->profile_picture)
                        <img src="{{ $conversation->customer->profile_picture_url }}" class="rounded-circle shadow-sm" width="80" height="80" style="object-fit:cover;">
                    @else
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white shadow-sm" style="width:80px;height:80px;background:var(--primary-color);font-size: 1.5rem;">
                            {{ substr($conversation->customer->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h5 class="fw-bold mb-1">{{ $conversation->customer->name }}</h5>
                <p class="text-muted small mb-2">Customer</p>
                <div class="d-flex flex-column text-start mt-4 bg-theme-secondary p-3 rounded-3">
                    <div class="mb-2"><i class="bi bi-envelope text-muted me-2"></i> {{ $conversation->customer->email }}</div>
                    <div class="mb-2"><i class="bi bi-telephone text-muted me-2"></i> {{ $conversation->customer->phone ?? 'N/A' }}</div>
                    <div><i class="bi bi-geo-alt text-muted me-2"></i> {{ $conversation->customer->city->name ?? 'Unknown City' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Companion Info -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 text-center">
                <div class="mb-3">
                    @if($conversation->companion->profile_picture)
                        <img src="{{ $conversation->companion->profile_picture_url }}" class="rounded-circle shadow-sm" width="80" height="80" style="object-fit:cover;">
                    @else
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white shadow-sm" style="width:80px;height:80px;background:#ec4899;font-size: 1.5rem;">
                            {{ substr($conversation->companion->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <h5 class="fw-bold mb-1">{{ $conversation->companion->name }}</h5>
                <p class="text-muted small mb-2">Companion Partner</p>
                <div class="d-flex flex-column text-start mt-4 bg-theme-secondary p-3 rounded-3">
                    <div class="mb-2"><i class="bi bi-envelope text-muted me-2"></i> {{ $conversation->companion->email }}</div>
                    <div class="mb-2"><i class="bi bi-telephone text-muted me-2"></i> {{ $conversation->companion->phone ?? 'N/A' }}</div>
                    <div><i class="bi bi-geo-alt text-muted me-2"></i> {{ $conversation->companion->city->name ?? 'Unknown City' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
