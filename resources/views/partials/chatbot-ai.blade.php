<!-- ===== CHATBOT AI WIDGET ===== -->
<style>
/* ── Chatbot Variables ── */
:root {
    --cb-primary: #4ade80;
    --cb-primary-dark: #22c55e;
    --cb-primary-light: rgba(74, 222, 128, 0.1);
    --cb-bg: rgba(255, 255, 255, 0.03);
    --cb-bg-dark: rgba(255, 255, 255, 0.08);
    --cb-text: #fff;
    --cb-text-muted: rgba(255, 255, 255, 0.5);
    --cb-border: rgba(255, 255, 255, 0.15);
    --cb-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    --cb-radius: 28px;
    --cb-msg-radius: 18px;
}

/* ── Chatbot Toggle Button ── */
#chatbot-toggle {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
    border: none;
    cursor: pointer;
    box-shadow: 0 8px 28px rgba(5,150,105,0.45), 0 2px 8px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    outline: none;
}
#chatbot-toggle:hover {
    transform: scale(1.12) translateY(-3px);
    box-shadow: 0 14px 36px rgba(5,150,105,0.55), 0 4px 12px rgba(0,0,0,0.2);
}
#chatbot-toggle:active { transform: scale(0.97); }
#chatbot-toggle svg { width: 28px; height: 28px; fill: white; transition: transform 0.35s ease; }
#chatbot-toggle.is-open svg.icon-chat { display: none; }
#chatbot-toggle:not(.is-open) svg.icon-close { display: none; }

/* Pulse animation badge */
#chatbot-toggle::before {
    content: '';
    position: absolute;
    top: -3px; right: -3px;
    width: 16px; height: 16px;
    background: #f59e0b;
    border-radius: 50%;
    border: 2px solid white;
    animation: cbPulse 2s ease-in-out infinite;
}
@keyframes cbPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.8; }
}
#chatbot-toggle.is-open::before { display: none; }

/* ── Chatbot Window ── */
#chatbot-window {
    position: fixed;
    bottom: 108px;
    right: 28px;
    width: 390px;
    height: 580px;
    background: rgba(15, 23, 42, 0.2) !important;
    backdrop-filter: blur(40px) saturate(200%) !important;
    -webkit-backdrop-filter: blur(40px) saturate(200%) !important;
    border-radius: var(--cb-radius);
    box-shadow: var(--cb-shadow);
    display: flex;
    flex-direction: column;
    z-index: 9998;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    transform: scale(0.85) translateY(30px);
    opacity: 0;
    pointer-events: none;
    transition: all 0.4s cubic-bezier(0.34, 1.3, 0.64, 1);
    transform-origin: bottom right;
}
#chatbot-window.is-open {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: all;
}

/* ── Chatbot Header ── */
#chatbot-header {
    background: rgba(255, 255, 255, 0.01) !important;
    padding: 22px 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
.cb-avatar {
    width: 44px; height: 44px;
    background: rgba(74, 222, 128, 0.15);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    position: relative;
    border: 1px solid rgba(74, 222, 128, 0.3);
}
.cb-avatar::after {
    content: '';
    position: absolute;
    bottom: 1px; right: 1px;
    width: 12px; height: 12px;
    background: #4ade80;
    border-radius: 50%;
    border: 2px solid #0f172a;
    box-shadow: 0 0 10px #4ade80;
}
.cb-header-info { flex: 1; }
.cb-header-name {
    font-weight: 700;
    font-size: 15px;
    color: #fff;
    font-family: 'Outfit', sans-serif;
    line-height: 1.2;
}
.cb-header-status {
    font-size: 12px;
    color: rgba(255,255,255,0.8);
    display: flex; align-items: center; gap: 5px;
    margin-top: 2px;
}
.cb-status-dot {
    width: 7px; height: 7px;
    background: #4ade80;
    border-radius: 50%;
    animation: cbBlink 1.5s ease-in-out infinite;
}
@keyframes cbBlink { 0%,100%{opacity:1} 50%{opacity:0.4} }
#chatbot-close-btn {
    width: 32px; height: 32px;
    background: rgba(255,255,255,0.15);
    border: none; border-radius: 50%; cursor: pointer;
    color: white; font-size: 18px; line-height: 1;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.2s;
}
#chatbot-close-btn:hover { background: rgba(255,255,255,0.3); }

/* ── Messages Area ── */
#chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    background: transparent;
    scroll-behavior: smooth;
}
#chatbot-messages::-webkit-scrollbar { width: 4px; }
#chatbot-messages::-webkit-scrollbar-track { background: transparent; }
#chatbot-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

/* ── Message Bubbles ── */
.cb-msg {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    animation: cbMsgIn 0.35s cubic-bezier(0.34,1.2,0.64,1) forwards;
}
@keyframes cbMsgIn {
    from { opacity:0; transform: translateY(12px) scale(0.95); }
    to { opacity:1; transform: translateY(0) scale(1); }
}
.cb-msg.user { flex-direction: row-reverse; }

.cb-msg-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg,#059669,#0d9488);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(5,150,105,0.3);
}
.cb-msg.user .cb-msg-avatar {
    background: linear-gradient(135deg,#3b82f6,#8b5cf6);
    box-shadow: 0 2px 8px rgba(59,130,246,0.3);
}

.cb-bubble {
    max-width: 82%;
    padding: 12px 18px;
    border-radius: var(--cb-msg-radius);
    font-size: 14px;
    line-height: 1.6;
    color: #fff;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.05);
    word-break: break-word;
}
.cb-msg.user .cb-bubble {
    background: linear-gradient(135deg, rgba(74, 222, 128, 0.8), rgba(34, 197, 94, 0.8));
    color: #000;
    font-weight: 700;
    border: none;
    box-shadow: 0 8px 32px rgba(74, 222, 128, 0.2);
    border-radius: 18px 4px 18px 18px;
}
.cb-msg.bot .cb-bubble {
    border-radius: 4px 18px 18px 18px;
}
.cb-bubble strong { color: inherit; }
.cb-bubble ul { padding-left: 18px; margin: 6px 0; }
.cb-bubble li { margin-bottom: 3px; }
.cb-bubble h4 { font-size: 14px; font-weight: 700; margin: 8px 0 4px; }

/* ── Typing Indicator ── */
.cb-typing {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}
.cb-typing-dots {
    background: white;
    padding: 12px 16px;
    border-radius: 4px 16px 16px 16px;
    border: 1px solid var(--cb-border);
    display: flex;
    gap: 5px;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
}
.cb-typing-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #059669;
    animation: cbTyping 1.2s ease-in-out infinite;
}
.cb-typing-dot:nth-child(2) { animation-delay: 0.2s; }
.cb-typing-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes cbTyping {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
    30% { transform: translateY(-6px); opacity: 1; }
}

/* ── Quick Suggestions ── */
#chatbot-suggestions {
    padding: 12px 18px 8px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    background: rgba(255, 255, 255, 0.02);
    border-top: 1px solid var(--cb-border);
    flex-shrink: 0;
}
.cb-chip {
    padding: 7px 14px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(74, 222, 128, 0.2);
    color: var(--cb-primary);
    border-radius: 999px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    backdrop-filter: blur(5px);
}
.cb-chip:hover {
    background: var(--cb-primary);
    color: #000;
    border-color: var(--cb-primary);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(74, 222, 128, 0.4);
}

/* ── Input Area ── */
#chatbot-input-area {
    padding: 15px 18px 20px;
    background: transparent;
    border-top: 1px solid var(--cb-border);
    display: flex;
    gap: 10px;
    align-items: flex-end;
    flex-shrink: 0;
}
#chatbot-input {
    flex: 1;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    padding: 12px 18px;
    font-size: 14px;
    font-family: inherit;
    resize: none;
    outline: none;
    transition: all 0.25s;
    max-height: 100px;
    min-height: 48px;
    color: #fff;
    line-height: 1.5;
    background: rgba(255, 255, 255, 0.03);
}
#chatbot-input:focus {
    border-color: var(--cb-primary);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.15);
}
#chatbot-input::placeholder { color: #94a3b8; }
#chatbot-send {
    width: 46px; height: 46px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--cb-primary), #22c55e);
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.25s ease;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(74, 222, 128, 0.35);
}
#chatbot-send:hover { transform: scale(1.08) translateY(-2px); box-shadow: 0 8px 20px rgba(74, 222, 128, 0.5); }
#chatbot-send:active { transform: scale(0.95); }
#chatbot-send:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
#chatbot-send svg { width: 18px; height: 18px; fill: white; }

/* ── Powered by ── */
.cb-powered {
    text-align: center;
    font-size: 11px;
    color: rgba(255, 255, 255, 0.3);
    padding: 0 16px 15px;
    background: transparent;
}
.cb-powered span { color: var(--cb-primary); font-weight: 700; }

/* ── Mobile Responsive ── */
@media (max-width: 480px) {
    #chatbot-window {
        width: calc(100vw - 24px);
        height: calc(100vh - 140px);
        right: 12px;
        bottom: 96px;
    }
    #chatbot-toggle { bottom: 20px; right: 16px; }
}
</style>

<!-- Chatbot Toggle Button -->
<button id="chatbot-toggle" aria-label="Mở chatbot tư vấn tour" title="Tư vấn tour du lịch">
    <!-- Chat icon -->
    <svg class="icon-chat" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12zM7 9h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2z"/>
    </svg>
    <!-- Close icon -->
    <svg class="icon-close" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
    </svg>
</button>

<!-- Chatbot Window -->
<div id="chatbot-window" role="dialog" aria-label="VietGo AI Chatbot">
    <!-- Header -->
    <div id="chatbot-header">
        <div class="cb-avatar">🤖</div>
        <div class="cb-header-info">
            <div class="cb-header-name">VietGo AI</div>
            <div class="cb-header-status">
                <div class="cb-status-dot"></div>
                Trợ lý tư vấn tour du lịch
            </div>
        </div>
        <button id="chatbot-close-btn" aria-label="Đóng chatbot">✕</button>
    </div>

    <!-- Messages -->
    <div id="chatbot-messages"></div>

    <!-- Quick Suggestions -->
    <div id="chatbot-suggestions">
        <button class="cb-chip" data-msg="Tour Hà Nội có gì đặc sắc?">🏯 Hà Nội</button>
        <button class="cb-chip" data-msg="Tư vấn tour Đà Nẵng cho gia đình">🌊 Đà Nẵng</button>
        <button class="cb-chip" data-msg="Địa điểm du lịch đẹp ở Phú Quốc">🏝️ Phú Quốc</button>
        <button class="cb-chip" data-msg="Tour Sapa leo núi giá rẻ">⛰️ Sapa</button>
    </div>

    <!-- Input Area -->
    <div id="chatbot-input-area">
        <textarea id="chatbot-input" placeholder="Nhập địa điểm bạn muốn đến..." rows="1" maxlength="800"></textarea>
        <button id="chatbot-send" aria-label="Gửi tin nhắn" disabled>
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
    <div class="cb-powered">Powered by <span>Groq AI ⚡</span></div>
</div>

<script>
(function () {
    const toggle = document.getElementById('chatbot-toggle');
    const win = document.getElementById('chatbot-window');
    const closeBtn = document.getElementById('chatbot-close-btn');
    const messagesEl = document.getElementById('chatbot-messages');
    const inputEl = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send');
    const chips = document.querySelectorAll('.cb-chip');
    const suggestionsEl = document.getElementById('chatbot-suggestions');

    let conversationHistory = [];
    let isLoading = false;
    let hasWelcomed = false;

    // ── Open / Close ──
    function openChat() {
        if(!toggle || !win) return;
        toggle.classList.add('is-open');
        win.classList.add('is-open');
        if(inputEl) inputEl.focus();
        if (!hasWelcomed) {
            hasWelcomed = true;
            setTimeout(() => appendWelcome(), 300);
        }
    }

    function closeChat() {
        if(!toggle || !win) return;
        toggle.classList.remove('is-open');
        win.classList.remove('is-open');
    }

    if(toggle) toggle.addEventListener('click', () => win.classList.contains('is-open') ? closeChat() : openChat());
    if(closeBtn) closeBtn.addEventListener('click', closeChat);

    // ── Welcome Message ──
    function appendWelcome() {
        const welcomeText = `👋 Xin chào! Tôi là **VietGo AI** - trợ lý tư vấn tour du lịch của bạn!\n\n🗺️ Hãy cho tôi biết bạn muốn đến **địa điểm nào** tại Việt Nam, tôi sẽ gợi ý:\n• 📍 Địa điểm tham quan nổi bật\n• 🍜 Ẩm thực đặc sản\n• 🏨 Thời điểm đẹp để đi\n• 💡 Kinh nghiệm du lịch hữu ích\n\nBắt đầu thôi nào! 🚀`;
        appendMessage('bot', welcomeText);
    }

    // ── Append Message ──
    function appendMessage(role, text) {
        if(!messagesEl) return;
        const wrap = document.createElement('div');
        wrap.className = 'cb-msg ' + role;

        const avatar = document.createElement('div');
        avatar.className = 'cb-msg-avatar';
        avatar.textContent = role === 'bot' ? '🤖' : '👤';

        const bubble = document.createElement('div');
        bubble.className = 'cb-bubble';
        bubble.innerHTML = formatMessage(text);

        wrap.appendChild(avatar);
        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
        scrollToBottom();
        return wrap;
    }

    // ── Format Message (Simple Markdown) ──
    function formatMessage(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.+?)\*/g, '<em>$1</em>')
            .replace(/^#{1,3}\s(.+)$/gm, '<h4>$1</h4>')
            .replace(/^[\-\*]\s(.+)$/gm, '<li>$1</li>')
            .replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>')
            .replace(/\n\n/g, '</p><p>')
            .replace(/\n/g, '<br>')
            .replace(/^(.+)$/, '<p>$1</p>');
    }

    // ── Typing Indicator ──
    function showTyping() {
        if(!messagesEl) return;
        const wrap = document.createElement('div');
        wrap.className = 'cb-typing';
        wrap.id = 'cb-typing-indicator';

        const avatar = document.createElement('div');
        avatar.className = 'cb-msg-avatar';
        avatar.textContent = '🤖';

        const dots = document.createElement('div');
        dots.className = 'cb-typing-dots';
        dots.innerHTML = '<div class="cb-typing-dot"></div><div class="cb-typing-dot"></div><div class="cb-typing-dot"></div>';

        wrap.appendChild(avatar);
        wrap.appendChild(dots);
        messagesEl.appendChild(wrap);
        scrollToBottom();
    }

    function hideTyping() {
        const indicator = document.getElementById('cb-typing-indicator');
        if (indicator) indicator.remove();
    }

    // ── Scroll ──
    function scrollToBottom() {
        if(messagesEl) messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    // ── Send Message ──
    async function sendMessage(text) {
        if (!text.trim() || isLoading) return;

        const userText = text.trim();
        if(inputEl) inputEl.value = '';
        autoResizeInput();
        if(sendBtn) sendBtn.disabled = true;
        isLoading = true;

        // Hide suggestions after first message
        if(suggestionsEl) suggestionsEl.style.display = 'none';

        // Append user message
        appendMessage('user', userText);

        // Show typing
        showTyping();

        try {
            const response = await fetch('{{ route("chatbot.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: userText,
                    history: conversationHistory
                }),
            });

            hideTyping();
            
            const data = await response.json();

            if (!response.ok || !data.success) {
                console.error('Chatbot Error:', data);
                throw new Error(data.message || 'API error');
            }

            const assistantMessage = data.message;

            appendMessage('bot', assistantMessage);
            
            // Update history
            conversationHistory.push({ role: 'user', content: userText });
            conversationHistory.push({ role: 'assistant', content: assistantMessage });
            
            // Trim history to last 20 entries
            if (conversationHistory.length > 20) {
                conversationHistory = conversationHistory.slice(-20);
            }
        } catch (err) {
            console.error('Chatbot Error:', err);
            hideTyping();
            appendMessage('bot', '⚠️ ' + (err.message === 'API error' ? 'Dịch vụ AI đang quá tải. Vui lòng thử lại sau.' : 'Không thể kết nối AI lúc này. Vui lòng kiểm tra kết nối mạng và thử lại!'));
        }

        isLoading = false;
        if(sendBtn && inputEl) sendBtn.disabled = inputEl.value.trim() === '';
        if(inputEl) inputEl.focus();
    }

    // ── Input Events ──
    if(inputEl) {
        inputEl.addEventListener('input', () => {
            autoResizeInput();
            if(sendBtn) sendBtn.disabled = inputEl.value.trim() === '' || isLoading;
        });

        inputEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage(inputEl.value);
            }
        });
    }

    if(sendBtn && inputEl) sendBtn.addEventListener('click', () => sendMessage(inputEl.value));

    // ── Auto-resize textarea ──
    function autoResizeInput() {
        if(!inputEl) return;
        inputEl.style.height = 'auto';
        inputEl.style.height = Math.min(inputEl.scrollHeight, 100) + 'px';
    }

    // ── Quick Chips ──
    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            const msg = chip.dataset.msg;
            if(inputEl) inputEl.value = msg;
            sendMessage(msg);
        });
    });
})();
</script>
