<div class="chat-header">
    @if(isset($user))
        <img src="{{ asset('user/' . $user->profilepicture) }}" alt="{{ $user->nama }}" class="chat-header-avatar">
        <div class="chat-header-info">
            <h4>{{ $user->nama }}</h4>
            <p>{{ $user->username }}</p>
        </div>
    @endif
</div>

<div class="chat-messages" id="chatMessages">
    @include('stylist.chat.listmessagestylist', ['messages' => $messages])
</div>

<div class="chat-input-area">
    <form action="{{ route('stylist.chat.sendMessage', $user->idPengguna) }}" method="POST" enctype="multipart/form-data" id="chatForm">
        @csrf
        <textarea name="isiPesan" placeholder="Type your message..." required></textarea>
        <label for="file-upload" class="file-upload-label">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L21 17" />
            </svg>
            <input type="file" name="lampiranPesan" id="file-upload" style="display: none;">
        </label>
        <button type="submit">Send</button>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom on load
        }

        // Auto-refresh messages
        setInterval(function() {
            if (window.location.pathname.includes('/stylist/chat/')) { // Only refresh if on a chat page
                fetch("{{ route('stylist.chat.getMessages', $user->idPengguna) }}")
                    .then(response => response.text())
                    .then(html => {
                        const newDiv = document.createElement('div');
                        newDiv.innerHTML = html;
                        const newMessages = newDiv.querySelector('.chat-messages-content').innerHTML;

                        const currentMessages = document.getElementById('chatMessages').innerHTML;
                        if (newMessages !== currentMessages) {
                            const isScrolledToBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 1;
                            document.getElementById('chatMessages').innerHTML = newMessages;
                            if (isScrolledToBottom) {
                                chatMessages.scrollTop = chatMessages.scrollHeight; // Keep scrolled to bottom
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching messages:', error));
            }
        }, 3000); // Refresh every 3 seconds
    });
</script>
@endpush

<style>
    .chat-header {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #eee;
        background-color: #f9f9f9;
    }

    .chat-header-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }

    .chat-header-info h4 {
        margin: 0;
        color: #333;
        font-size: 1.2rem;
    }

    .chat-header-info p {
        margin: 2px 0 0;
        color: #777;
        font-size: 0.9rem;
    }

    .chat-messages {
        flex-grow: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 10px;
        position: relative;
        word-wrap: break-word;
    }

    .message-bubble.sent {
        align-self: flex-end;
        background-color: #FFD700; /* Yellow for sent messages */
        color: #333;
        border-bottom-right-radius: 5px;
    }

    .message-bubble.received {
        align-self: flex-start;
        background-color: #e0e0e0;
        color: #333;
        border-bottom-left-radius: 5px;
    }

    .message-bubble p {
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.4;
    }

    .message-time {
        font-size: 0.75rem;
        color: #666;
        margin-top: 5px;
        display: block;
        text-align: right; /* For sent messages */
    }
    .message-bubble.received .message-time {
        text-align: left; /* For received messages */
    }

    .message-attachment img {
        max-width: 100%;
        border-radius: 8px;
        margin-top: 10px;
        display: block;
    }

    .chat-input-area {
        padding: 20px;
        border-top: 1px solid #eee;
        background-color: #f9f9f9;
    }

    .chat-input-area form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-input-area textarea {
        flex-grow: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 20px;
        resize: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        min-height: 40px; /* Adjust as needed */
        max-height: 100px; /* Prevent it from growing too large */
        overflow-y: auto;
    }

    .chat-input-area button {
        background-color: #173F63;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        transition: background-color 0.2s ease;
    }

    .chat-input-area button:hover {
        background-color: #112F4B;
    }

    .file-upload-label {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        border-radius: 50%;
        background-color: #f0f0f0;
        transition: background-color 0.2s ease;
    }

    .file-upload-label svg {
        color: #777;
    }

    .file-upload-label:hover {
        background-color: #e0e0e0;
    }
</style>
