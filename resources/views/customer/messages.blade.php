@extends('layouts.customer')
@section('title', 'Messages | Hire-a-Friend')

@section('styles')
<style>
.chat-layout { display:flex; height:calc(100vh - 130px); background:var(--surface); border-radius:20px; border:1px solid var(--border-light); overflow:hidden; box-shadow:var(--card-shadow); }
.chat-sidebar { width:300px; border-right:1px solid var(--border-light); display:flex; flex-direction:column; flex-shrink:0; }
.chat-sidebar-header { padding:1.25rem; border-bottom:1px solid var(--border-light); }
.chat-list { flex:1; overflow-y:auto; }
.chat-list-item { display:flex; align-items:center; gap:0.85rem; padding:1rem 1.25rem; cursor:pointer; transition:background 0.15s; border-bottom:1px solid var(--border-light); }
.chat-list-item:hover, .chat-list-item.active { background:rgba(124,58,237,0.06); }
.chat-list-item.active { border-left:3px solid #7c3aed; }
.chat-avatar { width:44px; height:44px; border-radius:50%; object-fit:cover; flex-shrink:0; }
.chat-avatar-placeholder { width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,#7c3aed,#ec4899); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1rem; flex-shrink:0; }
.chat-name { font-weight:600; font-size:0.9rem; color:var(--text-primary); margin-bottom:2px; }
.chat-preview { font-size:0.78rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:140px; }
.chat-meta { display:flex; flex-direction:column; align-items:flex-end; gap:4px; flex-shrink:0; }
.chat-time { font-size:0.7rem; color:var(--text-muted); }
.chat-unread { width:18px; height:18px; background:#7c3aed; color:#fff; border-radius:50%; font-size:0.65rem; font-weight:700; display:flex; align-items:center; justify-content:center; }
.chat-main { flex:1; display:flex; flex-direction:column; min-width:0; }
.chat-header { padding:1rem 1.5rem; border-bottom:1px solid var(--border-light); display:flex; align-items:center; gap:1rem; }
.online-badge { display:flex; align-items:center; gap:4px; font-size:0.75rem; color:#059669; font-weight:600; }
.chat-messages { flex:1; overflow-y:auto; padding:1.5rem; display:flex; flex-direction:column; gap:0.85rem; }
.msg-row { display:flex; gap:0.65rem; align-items:flex-end; }
.msg-row.own { flex-direction:row-reverse; }
.msg-content-wrapper { max-width:75%; display:flex; flex-direction:column; }
.msg-row.own .msg-content-wrapper { align-items:flex-end; }
.msg-row:not(.own) .msg-content-wrapper { align-items:flex-start; }
.msg-bubble { padding:0.6rem 0.85rem; border-radius:12px; font-size:0.9rem; line-height:1.4; position:relative; word-wrap: break-word; overflow-wrap: break-word; white-space: pre-wrap; }
.msg-bubble img { max-width: 100%; border-radius: 8px; margin-bottom: 5px; cursor:pointer;}
.msg-bubble.other { background:var(--surface-2); color:var(--text-primary); border-radius:4px 18px 18px 18px; }
.msg-bubble.own { background:linear-gradient(135deg,#7c3aed,#8b5cf6); color:#fff; border-radius:18px 4px 18px 18px; }
.msg-time { font-size:0.68rem; color:var(--text-muted); margin-top:3px; }
.msg-row.own .msg-time { text-align:right; color:rgba(255,255,255,0.6); }
.chat-input-bar { padding:1rem 1.5rem; border-top:1px solid var(--border-light); display:flex; align-items:center; gap:0.75rem; }
.chat-input { flex:1; background:var(--surface-2); border:1.5px solid var(--border); border-radius:24px; padding:0.65rem 1.25rem; font-size:0.9rem; color:var(--text-primary); outline:none; transition:border-color 0.2s; }
.chat-input:focus { border-color:#7c3aed; }
.chat-input::placeholder { color:var(--text-muted); }
.chat-action-btn { width:40px; height:40px; border-radius:50%; border:none; background:var(--surface-2); color:var(--text-muted); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.2s; position:relative;}
.chat-action-btn:hover { background:rgba(124,58,237,0.1); color:#7c3aed; }
.chat-send-btn { width:40px; height:40px; border-radius:50%; border:none; background:linear-gradient(135deg,#7c3aed,#ec4899); color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(124,58,237,0.3); transition:all 0.2s; }
.chat-send-btn:hover { transform:scale(1.1); }
.chat-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform:none; }
.chat-empty { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--text-muted); }
.attachment-preview { position: absolute; bottom: 60px; left: 1.5rem; background: var(--surface); border: 1px solid var(--border-light); padding: 0.5rem; border-radius: 8px; display: none; box-shadow: var(--card-shadow); z-index: 10; align-items: center; gap: 0.5rem;}
.attachment-preview img { max-width: 80px; max-height: 80px; border-radius: 4px; object-fit: cover;}
.attachment-preview .remove-btn { background: rgba(239,68,68,0.1); color: #ef4444; border: none; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer;}
@media(max-width:768px) { .chat-sidebar { width:100%; } .chat-main { display:none; } .chat-main.active { display:flex; } .chat-sidebar.hidden { display:none; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Messages</h1>
    <p class="page-subtitle">Chat with your companions — available after a confirmed booking</p>
</div>

<div class="chat-layout">
    <!-- Sidebar -->
    <div class="chat-sidebar" id="chatSidebar">
        <div class="chat-sidebar-header">
            <div class="topbar-search" style="width:100%;">
                <i class="bi bi-search topbar-search-icon"></i>
                <input type="text" class="topbar-search-input" id="searchConversations" placeholder="Search conversations..." style="width:100%;" onkeyup="filterChats()">
            </div>
        </div>
        <div class="chat-list" id="conversationList">
            <div class="text-center py-5" style="color:var(--text-muted);"><div class="spinner-border spinner-border-sm" role="status"></div> Loading...</div>
        </div>
        
        <!-- People you can message based on booking -->
        <div id="newChatList" style="display:none;">
            <div class="px-3 py-2 text-muted fw-bold" style="font-size:0.75rem;background:var(--surface-2);">AVAILABLE TO CHAT</div>
            @foreach($bookedPartners as $bp)
            <div class="chat-list-item new-chat-item" onclick="startNewChat({{ $bp->partner->id }}, '{{ addslashes($bp->partner->name) }}', '{{ $bp->partner->profile_picture_url }}')">
                <div class="position-relative">
                    @if($bp->partner->profile_picture)
                        <img src="{{ $bp->partner->profile_picture_url }}" class="chat-avatar" alt="">
                    @else
                        <div class="chat-avatar-placeholder">{{ strtoupper(substr($bp->partner->name,0,1)) }}</div>
                    @endif
                </div>
                <div class="flex-grow-1 min-width-0">
                    <div class="chat-name">{{ $bp->partner->name }}</div>
                    <div class="chat-preview">Tap to start chat</div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    <!-- Main Chat Area -->
    <div class="chat-main" id="chatMain">
        <!-- Initial Empty State -->
        <div class="chat-empty" id="chatEmptyState">
            <i class="bi bi-chat-dots d-block fs-1 mb-3 text-muted"></i>
            <h4 class="fw-bold">Your Messages</h4>
            <p style="font-size:0.9rem;">Select a conversation to start chatting.</p>
        </div>

        <div id="chatActiveState" style="display:none; flex-direction:column; height:100%;">
            <!-- Chat Header -->
            <div class="chat-header">
                <button class="d-md-none chat-action-btn" onclick="backToList()"><i class="bi bi-arrow-left"></i></button>
                <img src="" class="chat-avatar" id="activeChatAvatar" alt="">
                <div class="flex-grow-1">
                    <div class="fw-bold" style="font-size:0.95rem;" id="activeChatName">Name</div>
                </div>
                <div class="d-flex gap-2">
                    <button class="chat-action-btn" title="More options" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end" style="border-radius:12px;border:1px solid var(--border);background:var(--surface);">
                        <li><a class="dropdown-item" href="#" onclick="alert('Block feature coming soon')"><i class="bi bi-slash-circle me-2 text-danger"></i>Block User</a></li>
                        <li><a class="dropdown-item" href="#" onclick="alert('Report feature coming soon')"><i class="bi bi-flag me-2 text-warning"></i>Report Abuse</a></li>
                    </ul>
                </div>
            </div>

            <!-- Messages -->
            <div class="chat-messages" id="chatMessages">
                <!-- Messages will be loaded here via AJAX -->
            </div>

            <!-- Input bar -->
            <div class="chat-input-bar position-relative">
                <div class="attachment-preview" id="attachmentPreview">
                    <img id="previewImg" src="" style="display:none;">
                    <div id="previewPdf" style="display:none;font-size:24px;color:#ef4444;"><i class="bi bi-file-earmark-pdf"></i></div>
                    <span id="previewName" style="font-size:0.75rem; max-width:100px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></span>
                    <button class="remove-btn" onclick="removeAttachment()"><i class="bi bi-x"></i></button>
                </div>

                <input type="file" id="fileInput" style="display:none;" accept="image/*,.pdf" onchange="handleFileSelect(event)">
                <button class="chat-action-btn" title="Attach file" onclick="document.getElementById('fileInput').click()"><i class="bi bi-paperclip"></i></button>
                <input type="text" class="chat-input" id="msgInput" placeholder="Type a message..." onkeydown="if(event.key==='Enter')sendMsg()">
                <button class="chat-send-btn" id="sendBtn" onclick="sendMsg()"><i class="bi bi-send-fill"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let activeConversationId = null;
let activeReceiverId = null;
let lastMessageId = 0;
let pollingInterval = null;
const authUserId = {{ Auth::id() }};

// Fetch conversations
function fetchConversations() {
    return fetch('{{ route("chat.conversations") }}', {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('conversationList');
        list.innerHTML = '';
        
        let hasActive = false;
        
        if(data.conversations.length === 0) {
            document.getElementById('newChatList').style.display = 'block';
        } else {
            document.getElementById('newChatList').style.display = 'none';
            data.conversations.forEach(conv => {
                const isActive = (activeConversationId === conv.id) ? 'active' : '';
                if(isActive) hasActive = true;
                
                const avatar = conv.other_user.avatar || '';
                const avatarHtml = avatar ? `<img src="${avatar}" class="chat-avatar" alt="">` : `<div class="chat-avatar-placeholder">${conv.other_user.name.charAt(0).toUpperCase()}</div>`;
                
                const unreadHtml = conv.unread_count > 0 ? `<div class="chat-unread">${conv.unread_count}</div>` : '';
                
                const item = document.createElement('div');
                item.className = `chat-list-item ${isActive} conv-item`;
                item.dataset.name = conv.other_user.name.toLowerCase();
                item.onclick = () => openExistingChat(conv.id, conv.other_user.id, conv.other_user.name, avatar);
                
                item.innerHTML = `
                    <div class="position-relative">${avatarHtml}</div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="chat-name">${conv.other_user.name}</div>
                        <div class="chat-preview">${conv.last_message || 'Start chatting...'}</div>
                    </div>
                    <div class="chat-meta">
                        <div class="chat-time">${conv.last_message_time || ''}</div>
                        ${unreadHtml}
                    </div>
                `;
                list.appendChild(item);
            });
            document.getElementById('newChatList').style.display = 'block';
        }
        return data.conversations;
    });
}

function filterChats() {
    const term = document.getElementById('searchConversations').value.toLowerCase();
    document.querySelectorAll('.conv-item, .new-chat-item').forEach(item => {
        if(item.dataset.name && item.dataset.name.includes(term)) {
            item.style.display = 'flex';
        } else if(!item.dataset.name && item.querySelector('.chat-name').innerText.toLowerCase().includes(term)){
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function openExistingChat(convId, receiverId, name, avatarUrl) {
    document.querySelectorAll('.chat-list-item').forEach(el => el.classList.remove('active'));
    // we rely on next poll to add active class or do it immediately
    
    activeConversationId = convId;
    activeReceiverId = receiverId;
    lastMessageId = 0;
    
    setupChatUI(name, avatarUrl);
    
    // Clear messages
    document.getElementById('chatMessages').innerHTML = '<div class="text-center py-5 text-muted">Loading messages...</div>';
    
    // Initial fetch
    pollMessages(true);
    
    // Start polling if not started
    if(pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(() => pollMessages(false), 8000);
    
    // Mark as read
    fetch(`/chat/${convId}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    });
}

function startNewChat(receiverId, name, avatarUrl) {
    document.querySelectorAll('.chat-list-item').forEach(el => el.classList.remove('active'));
    activeConversationId = null; // Will be created on first message
    activeReceiverId = receiverId;
    lastMessageId = 0;
    
    setupChatUI(name, avatarUrl);
    document.getElementById('chatMessages').innerHTML = '<div class="text-center py-5 text-muted">Send a message to start the conversation.</div>';
    
    if(pollingInterval) clearInterval(pollingInterval);
}

function setupChatUI(name, avatarUrl) {
    document.getElementById('chatEmptyState').style.display = 'none';
    document.getElementById('chatActiveState').style.display = 'flex';
    document.getElementById('chatMain').classList.add('active');
    document.getElementById('chatSidebar').classList.add('hidden');
    
    document.getElementById('activeChatName').textContent = name;
    if(avatarUrl) {
        document.getElementById('activeChatAvatar').src = avatarUrl;
        document.getElementById('activeChatAvatar').style.display = 'block';
    } else {
        document.getElementById('activeChatAvatar').style.display = 'none';
    }
}

function backToList() {
    document.getElementById('chatMain').classList.remove('active');
    document.getElementById('chatSidebar').classList.remove('hidden');
    if(pollingInterval) clearInterval(pollingInterval);
    activeConversationId = null;
    activeReceiverId = null;
    fetchConversations();
}

function pollMessages(isInitial) {
    if(!activeConversationId) return;
    
    const url = `/chat/${activeConversationId}/messages${lastMessageId > 0 ? '?last_id='+lastMessageId : ''}`;
    
    fetch(url, { headers: { 'Accept': 'application/json' }})
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('chatMessages');
        if(isInitial) container.innerHTML = '';
        
        if(data.messages && data.messages.length > 0) {
            let shouldScroll = isInitial || (container.scrollTop + container.clientHeight >= container.scrollHeight - 50);
            
            data.messages.forEach(msg => {
                appendMessage(msg);
                lastMessageId = Math.max(lastMessageId, msg.id);
            });
            
            if(shouldScroll) scrollBottom();
            
            // Mark read if it's not own message
            fetch(`/chat/${activeConversationId}/read`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
        }
        
        // Refresh sidebar silently
        fetchConversations();
    });
}

function appendMessage(msg) {
    const container = document.getElementById('chatMessages');
    const row = document.createElement('div');
    row.className = `msg-row ${msg.is_own ? 'own' : ''}`;
    
    let contentHtml = '';
    
    if(msg.attachment_url) {
        if(msg.attachment_type === 'image') {
            contentHtml += `<a href="${msg.attachment_url}" target="_blank"><img src="${msg.attachment_url}" alt="Attachment"></a><br>`;
        } else if (msg.attachment_type === 'pdf') {
            contentHtml += `<a href="${msg.attachment_url}" target="_blank" style="color:inherit;text-decoration:underline;"><i class="bi bi-file-earmark-pdf"></i> ${msg.original_filename || 'PDF Document'}</a><br>`;
        }
    }
    
    if(msg.message) {
        // Escape HTML
        const safeMsg = document.createElement('div');
        safeMsg.innerText = msg.message;
        contentHtml += safeMsg.innerHTML;
    }

    const checkIcon = msg.is_own ? (msg.is_read ? `<i class="bi bi-check2-all ms-1" style="color:#10b981;font-size:0.75rem;"></i>` : `<i class="bi bi-check2 ms-1" style="color:rgba(255,255,255,0.6);font-size:0.75rem;"></i>`) : '';

    row.innerHTML = `
        <div class="msg-content-wrapper">
            <div class="msg-bubble ${msg.is_own ? 'own' : 'other'}">${contentHtml}</div>
            <div class="msg-time">${msg.time} ${checkIcon}</div>
        </div>
    `;
    container.appendChild(row);
}

// Attachments
let selectedFile = null;
function handleFileSelect(e) {
    const file = e.target.files[0];
    if(!file) return;
    
    if(file.size > 5 * 1024 * 1024) {
        alert("File size must be less than 5MB");
        return;
    }
    
    selectedFile = file;
    document.getElementById('attachmentPreview').style.display = 'flex';
    document.getElementById('previewName').innerText = file.name;
    
    if(file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            document.getElementById('previewImg').src = evt.target.result;
            document.getElementById('previewImg').style.display = 'block';
            document.getElementById('previewPdf').style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('previewImg').style.display = 'none';
        document.getElementById('previewPdf').style.display = 'block';
    }
}

function removeAttachment() {
    selectedFile = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('attachmentPreview').style.display = 'none';
}

function sendMsg() {
    const input = document.getElementById('msgInput');
    const text = input.value.trim();
    
    if(!text && !selectedFile) return;
    if(!activeReceiverId) return;
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('receiver_id', activeReceiverId);
    if(text) formData.append('message', text);
    if(selectedFile) formData.append('attachment', selectedFile);
    
    // Disable send button
    document.getElementById('sendBtn').disabled = true;

    input.value = '';
    removeAttachment();

    fetch('{{ route("chat.send") }}', {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('sendBtn').disabled = false;
        if(data.success) {
            if(!activeConversationId) {
                activeConversationId = data.conversation_id;
                // Start polling now
                pollingInterval = setInterval(() => pollMessages(false), 8000);
            }
            
            // if we added optimistic text, we could replace it, but simpler to just reload messages
            // actually since lastMessageId wasn't updated, pollMessages(false) will fetch it immediately
            pollMessages(false);
        } else {
            alert(data.error || "Failed to send message.");
        }
    })
    .catch(err => {
        console.error(err);
        document.getElementById('sendBtn').disabled = false;
    });
}

function scrollBottom() {
    const el = document.getElementById('chatMessages');
    el.scrollTop = el.scrollHeight;
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    fetchConversations().then(conversations => {
        const urlParams = new URLSearchParams(window.location.search);
        const autoC = urlParams.get('c');
        if (autoC) {
            const conv = conversations.find(c => c.id == autoC);
            if (conv) {
                openExistingChat(conv.id, conv.other_user.id, conv.other_user.name, conv.other_user.avatar);
            }
        }
    });
    // Auto-refresh sidebar every 15 secs
    setInterval(fetchConversations, 15000);
});
</script>
@endsection
