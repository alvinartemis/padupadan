<div style="overflow-y: auto; padding: 15px;">
    @foreach ($messages as $message)
        <div style="margin-bottom: 15px; display: flex; flex-direction: column;
            align-items: {{ $message->sender_type == 'user' ? 'flex-end' : 'flex-start' }};">
            <div style="background-color: {{ $message->sender_type == 'user' ? '#F4BC43' : '#0C2A42' }};
                color: {{ $message->sender_type == 'user' ? 'white' : '#FFFFFF' }};
                padding: 10px 15px; border-radius: 10px; max-width: 80%; word-wrap: break-word;">

                @if ($message->lampiranPesan)
                    @php
                        $extension = pathinfo($message->lampiranPesan, PATHINFO_EXTENSION);
                    @endphp
                    @if (in_array(strtolower($extension), ['png', 'jpg', 'jpeg', 'heic']))
                        <img src="{{ asset('storage/' . $message->lampiranPesan) }}" alt="Lampiran Gambar" style="max-width: 100%; border-radius: 5px; margin-bottom: 5px;">
                    @else
                        <a href="{{ asset('storage/' . $message->lampiranPesan) }}" target="_blank" style="display: block; margin-top: 5px; font-size: 0.9rem; color: #007bff;">Lihat Lampiran</a>
                    @endif
                @endif

                @if ($message->isiPesan)
                    <p style="margin-top: 5px;">{{ $message->isiPesan }}</p>
                @endif
            </div>
            <div style="font-size: 0.7rem; color: #0C2A42; margin-top: 3px;">
                @if ($message->waktukirim)
                    {{ \Carbon\Carbon::parse($message->waktukirim)->setTimezone('Asia/Jakarta')->format('H:i') }}
                @else
                    Waktu tidak tersedia
                @endif
            </div>
        </div>
    @endforeach
</div>
