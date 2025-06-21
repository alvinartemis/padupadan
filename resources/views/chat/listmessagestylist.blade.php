{{-- <style>
    .chat-messages-content {
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
        align-self: flex-end; /* Sent messages align to the right */
        background-color: #FFD700; /* Yellow for sent messages */
        color: #333;
        border-bottom-right-radius: 5px;
    }

    .message-bubble.received {
        align-self: flex-start; /* Received messages align to the left */
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
</style> --}}

<div class="chat-messages-content">
    @foreach ($messages as $message)
        <div class="message-bubble {{ $message->sender_type == 'stylist' ? 'sent' : 'received' }}">
            <p>{{ $message->isiPesan }}</p>
            @if ($message->lampiranPesan)
                <div class="message-attachment">
                    <img src="{{ Storage::url($message->lampiranPesan) }}" alt="Lampiran Pesan">
                </div>
            @endif
            <span class="message-time">{{ \Carbon\Carbon::parse($message->waktukirim)->format('H:i') }}</span>
        </div>
    @endforeach
</div>
