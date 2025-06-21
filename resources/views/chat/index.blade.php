@extends('layouts.chat')

@section('content')
    <div style="background-color: #f4f4f4; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333; margin-bottom: 20px;">Messages</h2>

        @if ($recentChats->isNotEmpty())
            {{-- TAMBAHKAN ID INI: --}}
            <div id="recent-chats-container" style="margin-bottom: 20px;">
                @foreach ($recentChats as $chat)
                    <div style="background-color: white; border-radius: 8px; margin-bottom: 10px; padding: 10px;">
                        <a href="{{ route('chat.show', $chat['stylist']) }}" style="display: flex; align-items: center; text-decoration: none; color: #333;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; margin-right: 15px;">
                                <img src="{{ asset('stylist/' . $chat['stylist']->profilepicture) }}" alt="{{ $chat['stylist']->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="flex-grow: 1;">
                                <div style="font-weight: 600;">{{ $chat['stylist']->nama }}</div>
                                <div style="font-size: 0.9rem; color: #777;">{{ Str::limit(optional($chat['last_message'])->isiPesan ?? '', 50) }}</div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: flex-end; margin-left: 15px;">
                                <div style="font-size: 0.8rem; color: #999;">{{ optional(optional($chat['last_message'])->waktukirim)->diffForHumans() ?? '' }}</div>
                                @if ($chat['unread_count'] > 0)
                                    <div style="background-color: #ffc107; color: #fff; border-radius: 50%; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center; font-size: 0.7rem; margin-top: 5px;">{{ $chat['unread_count'] }}</div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: #777;">No Conversation Yet</p>
        @endif

        <h2 style="color: #333; margin-top: 30px; margin-bottom: 15px;">Our Stylist</h2>
        @include('chat.liststylist', ['stylists' => $stylists])
    </div>
    @push('scripts')
    <script>
        function refreshRecentChats() {
            // Tambahkan cache-busting timestamp ke URL untuk memastikan data terbaru
            const url = "{{ route('chat.index') }}" + "?_t=" + new Date().getTime();

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newRecentChatsContainer = doc.getElementById('recent-chats-container'); // Ambil kontainer yang baru dari response

                    if (newRecentChatsContainer) { // Pastikan kontainer ditemukan
                        const currentRecentChatsContainer = document.getElementById('recent-chats-container');
                        if (currentRecentChatsContainer) { // Pastikan kontainer saat ini ada
                            // Perbarui innerHTML dari kontainer yang ada
                            currentRecentChatsContainer.innerHTML = newRecentChatsContainer.innerHTML;
                        }
                    }
                })
                .catch(error => console.error('Error refreshing recent chats:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Panggil refresh saat halaman dimuat pertama kali
            refreshRecentChats();
        });

        // Gunakan pageshow untuk refresh yang lebih andal saat navigasi mundur/maju
        // Ini sangat berguna jika browser menggunakan back-forward cache (bfcache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) { // Jika halaman dimuat dari bfcache
                refreshRecentChats();
            }
        });

        // Fallback untuk event visibilitychange (ketika tab browser menjadi aktif/fokus kembali)
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                refreshRecentChats();
            }
        });
    </script>
    @endpush
@endsection
