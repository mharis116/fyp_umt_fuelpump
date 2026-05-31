<div id="ai-agent">
    <!-- Toggle Button -->
    <button id="ai-toggle" class="btn btn-primary rounded-circle shadow">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    </button>

    <!-- Chat Box -->
    <div id="ai-box" class="card shadow d-none">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div class="bg-info rounded-circle shadow p-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            </div>
            <h4 class="text-shadow">AI Assistant</h4>
            <svg  id="ai-close"  xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minimize-2 cursor-pointer"><polyline points="4 14 10 14 10 20"></polyline><polyline points="20 10 14 10 14 4"></polyline><line x1="14" y1="10" x2="21" y2="3"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg>
        </div>

        <div class="card-body" id="ai-messages">
            <div class="text-muted small">Ask about this report...</div>
        </div>

        <div class="card-footer">
            <div class="input-group">
                <input type="text" id="ai-input" class="form-control" placeholder="Type your question...">
                <div class="input-group-append">
                    <button class="btn btn-primary" id="ai-send">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#ai-agent {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

#ai-toggle {
    width: 60px;
    height: 60px;
    font-size: 22px;
}

#ai-box {
    width: 340px;
    position: absolute;
    bottom: 70px;
    right: 0;
    border-radius: 10px;
    overflow: hidden;
}

#ai-messages {
    height: 300px;
    overflow-y: auto;
    background: #f8f9fa;
    padding: 10px;
}

/* Chat bubbles */
.ai-msg {
    max-width: 80%;
    padding: 8px 12px;
    border-radius: 10px;
    margin-bottom: 8px;
    font-size: 14px;
    line-height: 1.4;
}

.ai-user {
    background: #007bff;
    color: #fff;
    margin-left: auto;
    text-align: right;
}

.ai-bot {
    background: #e9ecef;
    color: #000;
    margin-right: auto;
}

/* Typing indicator */
.typing {
    display: inline-block;
}

.typing span {
    display: inline-block;
    width: 6px;
    height: 6px;
    margin: 0 2px;
    background: #666;
    border-radius: 50%;
    animation: bounce 1.2s infinite;
}

.typing span:nth-child(2) { animation-delay: 0.2s; }
.typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

/* Markdown styling */
.ai-bot pre {
    background: #212529;
    color: #fff;
    padding: 8px;
    border-radius: 5px;
    overflow-x: auto;
}

.ai-bot code {
    background: #dee2e6;
    padding: 2px 4px;
    border-radius: 4px;
}
</style>

<script>
$(document).ready(function () {

    let reportName = "{{$report_identifier??''}}";
    let loadingMsgId = null;

    $('#ai-toggle').click(() => $('#ai-box').removeClass('d-none'));
    $('#ai-close').click(() => $('#ai-box').addClass('d-none'));

    $('#ai-send').click(sendMessage);
    $('#ai-input').keypress(function (e) {
        if (e.which === 13) sendMessage();
    });

    function sendMessage() {
        let question = $('#ai-input').val().trim();
        if (!question) return;

        appendMessage('user', question);
        $('#ai-input').val('');
        $('#ai-send').prop('disabled', true);

        showTyping();
        let data = {
            _token: "{{ csrf_token() }}",
            question: question,
            report: reportName,
        };

        @if($filters??null)
            data['filters'] = `{!! json_encode($filters)??[] !!}`;
        @endif

        $.ajax({
            url: "{{ route('ai.ask') }}",
            method: "POST",
            data: data,
            success: function (res) {
                removeTyping();
                appendMessage('ai', res.answer);
            },
            error: function () {
                removeTyping();
                appendMessage('ai', '⚠️ Something went wrong.');
            },
            complete: function () {
                $('#ai-send').prop('disabled', false);
            }
        });
    }

    function appendMessage(type, text) {
        let className = type === 'user' ? 'ai-msg ai-user' : 'ai-msg ai-bot';

        // Markdown support for AI
        let content = type === 'ai' ? marked.parse(text) : escapeHtml(text);

        $('#ai-messages').append(`
            <div class="${className}">
                ${content}
            </div>
        `);

        scrollToBottom();
    }

    function showTyping() {
        loadingMsgId = 'typing-' + Date.now();

        $('#ai-messages').append(`
            <div id="${loadingMsgId}" class="ai-msg ai-bot">
                <div class="typing">
                    <span></span><span></span><span></span>
                </div>
            </div>
        `);

        scrollToBottom();
    }

    function removeTyping() {
        if (loadingMsgId) {
            $('#' + loadingMsgId).remove();
            loadingMsgId = null;
        }
    }

    function scrollToBottom() {
        $('#ai-messages').scrollTop($('#ai-messages')[0].scrollHeight);
    }

    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

});
</script>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>