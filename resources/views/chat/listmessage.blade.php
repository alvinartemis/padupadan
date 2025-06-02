<div style="overflow-y: auto; padding: 15px;">
    @foreach ($messages as $message)
        <div style="margin-bottom: 15px; display: flex; flex-direction: column; align-items: {{ $message->idPengguna == Auth::id() ? 'flex-end' : 'flex-start' }};">
            <div style="background-color: {{ $message->idPengguna == Auth::id() ? '#F4BC43' : '#e0e0e0' }}; color: {{ $message->idPengguna == Auth::id() ? 'white' : '#333' }}; padding: 10px 15px; border-radius: 10px; max-width: 80%; word-wrap: break-word;">
                {{ $message->isiPesan }}
                @if ($message->lampiranPesan)
                    <a href="{{ asset('storage/' . $message->lampiranPesan) }}" target="_blank" style="display: block; margin-top: 5px; font-size: 0.9rem; color: #007bff;">Lihat Lampiran</a>
                @endif
            </div>
            <div style="font-size: 0.7rem; color: #999; margin-top: 3px;">
                @if ($message->waktukirim)
                    {{ $message->waktukirim->diffForHumans() }}
                @else
                    Waktu tidak tersedia
                @endif
            </div>
        </div>
    @endforeach
</div>
