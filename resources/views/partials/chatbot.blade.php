<!-- AI Customer Support Chatbot Widget -->
<div id="chatbot-widget" class="chatbot-widget">
    <!-- Chat Window -->
    <div id="chatbot-window" class="chatbot-window d-none">
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <div class="chatbot-avatar">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="ms-2">
                    <h6 class="mb-0 text-white">AI Support Assistant</h6>
                    <small class="text-white-50">Online</small>
                </div>
            </div>
            <button id="chatbot-close-btn" class="btn btn-sm text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div id="chatbot-messages" class="chatbot-messages">
            <!-- Welcome Message -->
            <div class="chat-message ai-message">
                <div class="message-content">
                    Hello! I'm your AI Support Assistant. How can I help you today?
                </div>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="chatbot-typing" class="chatbot-typing d-none">
            <div class="chat-message ai-message mb-0">
                <div class="message-content typing-indicator">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>

        <div class="chatbot-footer">
            <form id="chatbot-form" class="d-flex align-items-center w-100 m-0">
                <input type="text" id="chatbot-input" class="form-control chatbot-input" placeholder="Type your message..." autocomplete="off">
                <button type="submit" class="btn chatbot-send-btn ms-2">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Floating Button -->
    <button id="chatbot-toggle-btn" class="chatbot-toggle-btn shadow-lg">
        <i class="bi bi-chat-dots-fill"></i>
    </button>
</div>

<style>
    /* Chatbot Variables & Scoping */
    .chatbot-widget {
        --cb-primary: #2563eb;
        --cb-primary-hover: #1d4ed8;
        --cb-bg: #ffffff;
        --cb-text: #0f172a;
        --cb-msg-ai: #f1f5f9;
        --cb-msg-user: #2563eb;
        --cb-border: #e2e8f0;
        
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1050;
        font-family: 'Inter', sans-serif;
    }

    /* Dark Mode Support */
    html.dark .chatbot-widget {
        --cb-bg: #1e293b;
        --cb-text: #f8fafc;
        --cb-msg-ai: #334155;
        --cb-msg-user: #3b82f6;
        --cb-border: #475569;
    }

    /* Floating Toggle Button */
    .chatbot-toggle-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--cb-primary);
        color: white;
        border: none;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), background 0.3s ease;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.5);
    }
    .chatbot-toggle-btn:hover {
        transform: scale(1.1);
        background: var(--cb-primary-hover);
    }

    /* Chat Window */
    .chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        height: 500px;
        max-height: calc(100vh - 120px);
        background: var(--cb-bg);
        border-radius: 16px;
        box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid var(--cb-border);
        /* Glassmorphism effects */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transform-origin: bottom right;
        animation: slideUpFade 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .chatbot-window.closing {
        animation: slideDownFade 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes slideUpFade {
        from { opacity: 0; transform: scale(0.9) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes slideDownFade {
        from { opacity: 1; transform: scale(1) translateY(0); }
        to { opacity: 0; transform: scale(0.9) translateY(20px); }
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, var(--cb-primary), #4f46e5);
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }
    .chatbot-avatar {
        width: 36px;
        height: 36px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    /* Messages Area */
    .chatbot-messages {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: rgba(var(--cb-bg), 0.5);
    }
    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }
    .chatbot-messages::-webkit-scrollbar-thumb {
        background: var(--cb-border);
        border-radius: 3px;
    }

    .chat-message {
        max-width: 85%;
        display: flex;
        flex-direction: column;
    }
    .message-content {
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 0.9rem;
        line-height: 1.4;
        word-wrap: break-word;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* AI Message (Left) */
    .ai-message {
        align-self: flex-start;
    }
    .ai-message .message-content {
        background: var(--cb-msg-ai);
        color: var(--cb-text);
        border-bottom-left-radius: 4px;
    }

    /* User Message (Right) */
    .user-message {
        align-self: flex-end;
    }
    .user-message .message-content {
        background: var(--cb-msg-user);
        color: white;
        border-bottom-right-radius: 4px;
    }

    /* Typing Indicator */
    .chatbot-typing {
        padding: 0 16px 12px;
        background: rgba(var(--cb-bg), 0.5);
    }
    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 12px 14px !important;
    }
    .typing-indicator span {
        width: 6px;
        height: 6px;
        background: var(--cb-text);
        opacity: 0.5;
        border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
    .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* Footer / Input Area */
    .chatbot-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--cb-border);
        background: var(--cb-bg);
    }
    .chatbot-input {
        border-radius: 20px;
        background: var(--cb-msg-ai);
        border: 1px solid transparent;
        color: var(--cb-text);
        padding: 10px 16px;
        box-shadow: none !important;
        transition: border 0.2s;
    }
    .chatbot-input:focus {
        border-color: var(--cb-primary);
        background: var(--cb-bg);
    }
    .chatbot-send-btn {
        background: var(--cb-primary);
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.2s;
    }
    .chatbot-send-btn:hover {
        background: var(--cb-primary-hover);
        color: white;
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
        .chatbot-window {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            max-height: 100vh;
            border-radius: 0;
            z-index: 1060;
            animation: slideUpMobile 0.3s ease forwards;
        }
        .chatbot-window.closing {
            animation: slideDownMobile 0.3s ease forwards;
        }
        @keyframes slideUpMobile {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        @keyframes slideDownMobile {
            from { transform: translateY(0); }
            to { transform: translateY(100%); }
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbot-toggle-btn');
    const closeBtn = document.getElementById('chatbot-close-btn');
    const chatWindow = document.getElementById('chatbot-window');
    const chatForm = document.getElementById('chatbot-form');
    const chatInput = document.getElementById('chatbot-input');
    const messagesContainer = document.getElementById('chatbot-messages');
    const typingIndicator = document.getElementById('chatbot-typing');
    
    let isWindowOpen = false;
    let historyLoaded = false;

    // Toggle Chat Window
    function toggleChat() {
        if (isWindowOpen) {
            chatWindow.classList.add('closing');
            setTimeout(() => {
                chatWindow.classList.add('d-none');
                chatWindow.classList.remove('closing');
            }, 300);
        } else {
            chatWindow.classList.remove('d-none');
            chatInput.focus();
            scrollToBottom();
            
            if (!historyLoaded) {
                loadHistory();
            }
        }
        isWindowOpen = !isWindowOpen;
    }

    toggleBtn.addEventListener('click', toggleChat);
    closeBtn.addEventListener('click', toggleChat);

    // Load Chat History
    async function loadHistory() {
        try {
            const response = await fetch('/chatbot/history', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            if (data.history && data.history.length > 0) {
                // Do not clear the default welcome message, just append history
                data.history.forEach(msg => {
                    appendMessage(msg.content, msg.role);
                });
                scrollToBottom();
            }
            historyLoaded = true;
        } catch (error) {
            console.error("Failed to load history", error);
        }
    }

    // Append Message to UI
    function appendMessage(content, role) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${role === 'user' ? 'user-message' : 'ai-message'}`;
        
        // Escape HTML to prevent XSS
        const escapedContent = document.createElement('div');
        escapedContent.textContent = content;
        
        // Basic Markdown-like formatting (convert newlines to br)
        let formattedContent = escapedContent.innerHTML.replace(/\n/g, '<br>');
        
        messageDiv.innerHTML = `
            <div class="message-content">
                ${formattedContent}
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    // Scroll to latest message
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Handle Form Submit
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (!message) return;
        
        // Add user message to UI
        appendMessage(message, 'user');
        chatInput.value = '';
        
        // Show typing indicator
        typingIndicator.classList.remove('d-none');
        scrollToBottom();
        
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            console.log("Sending AI request...");
            const response = await fetch('/chatbot/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            console.log("AI Response:", data);
            
            // Hide typing indicator
            typingIndicator.classList.add('d-none');
            
            if (data.reply) {
                appendMessage(data.reply, 'assistant');
            } else {
                appendMessage(data.message || 'Error communicating with AI.', 'assistant');
            }
            
        } catch (error) {
            console.error('Error:', error);
            typingIndicator.classList.add('d-none');
            appendMessage('Network error. Please try again.', 'assistant');
        }
    });
});
</script>
