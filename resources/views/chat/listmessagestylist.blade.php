<div class="chat-messages-content">
    @foreach($messages as $message)
        <div class="message-bubble {{ $message->idStylist == Auth::id() ? 'sent' : 'received' }}">
            <p>{{ $message->isiPesan }}</p>
            @if($message->lampiranPesan)
                <div class="message-attachment">
                    <img src="{{ asset('storage/' . $message->lampiranPesan) }}" alt="Attachment">
                </div>
            @endif
            <span class="message-time">{{ $message->waktukirim->format('H:i') }}</span>
        </div>
    @endforeach
</div>

<style>
    /* Styles for message bubbles are already in showstylist.blade.php,
       but you can add any specific overrides here if needed */
</style>
