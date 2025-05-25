<div id="message-container" style="overflow-y: auto; max-height: 400px; padding: 10px;">
    @foreach ($messages as $message)
        <div style="display: flex; flex-direction: column; margin-bottom: 10px; align-items: {{ $message->idPengguna == auth()->id() ? 'flex-end' : 'flex-start' }};">
            <div style="background-color: {{ $message->idPengguna == auth()->id() ? '#e0f7fa' : '#f9f9f9' }}; color: #333; padding: 8px 12px; border-radius: 8px; max-width: 80%;">
                {{ $message->isiPesan }}
                @if ($message->lampiranPesan)
                    <a href="{{ asset('storage/' . $message->lampiranPesan) }}" target="_blank" style="display: block; margin-top: 5px; font-size: 0.9rem; color: #007bff;">Lihat Lampiran</a>
                @endif
            </div>
            <div style="font-size: 0.7rem; color: #999; margin-top: 3px;">{{ $message->waktuKirim->diffForHumans() }}</div>
        </div>
    @endforeach
</div>
