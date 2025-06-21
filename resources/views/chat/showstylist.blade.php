@extends('layouts.stylist') {{-- Pastikan ini mengarah ke layout yang benar untuk stylist --}}

@section('content')
{{-- Kontainer utama chat - disesuaikan agar lebih mirip RoomChat.png --}}
<div class="chat-container">
    <div class="chat-header">
        @if(isset($user))
            {{-- Tombol Tutup (X) --}}
            <a href="{{ route('chat.indexstylist') }}" class="close-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20" height="20" fill="currentColor">
                    <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"/>
                </svg>
            </a>

            {{-- Info profil di tengah atau kiri --}}
            <div class="chat-header-info">
                <img src="{{ asset('user/' . $user->profilepicture) }}" alt="{{ $user->nama }}" class="chat-header-avatar">
                <div>
                    <h4>{{ $user->nama }}</h4>
                    <p>{{ '@' . $user->username }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="chat-messages" id="chatMessages">
        @include('chat.listmessagestylist', ['messages' => $messages, 'loggedInStylistId' => $loggedInStylistId])
    </div>

    <div class="chat-input-area">
        <form action="{{ route('chat.sendMessageStylist', ['user' => $user->idPengguna]) }}" method="POST" enctype="multipart/form-data" id="chatForm">
            @csrf
            {{-- Mengubah textarea menjadi input text --}}
            <input type="text" name="isiPesan" placeholder="Ketik Pertanyaan" required class="chat-text-input">

            {{-- Menghilangkan input file dan labelnya jika tidak ingin fitur lampiran --}}
            {{-- Jika ingin tetap ada, sesuaikan styling-nya --}}
            {{-- <label for="file-upload" class="file-upload-label">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L21 17" />
                </svg>
                <input type="file" name="lampiranPesan" id="file-upload" style="display: none;">
            </label> --}}

            {{-- Mengubah button submit menjadi ikon send --}}
            <button type="submit" class="send-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                    <path d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480l0-83.6c0-4 1.5-7.8 4.2-10.8L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/>
                </svg>
            </button>
        </form>
    </div>
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
            const currentPath = window.location.pathname;
            const pathParts = currentPath.split('/');
            if (pathParts.length === 4 && pathParts[1] === 'stylist' && pathParts[2] === 'chat' && !isNaN(pathParts[3])) {
                const userId = pathParts[3];
                fetch("{{ route('chat.getMessagesStylist', ['user' => ':userId']) }}".replace(':userId', userId))
                    .then(response => response.text())
                    .then(html => {
                        const newDiv = document.createElement('div');
                        newDiv.innerHTML = html;
                        const newMessages = newDiv.querySelector('.chat-messages-content').innerHTML;

                        const currentMessagesContainer = document.getElementById('chatMessages');
                        const currentMessages = currentMessagesContainer.innerHTML;

                        if (newMessages !== currentMessages) {
                            const isScrolledToBottom = currentMessagesContainer.scrollHeight - currentMessagesContainer.clientHeight <= currentMessagesContainer.scrollTop + 1;
                            currentMessagesContainer.innerHTML = newMessages;
                            if (isScrolledToBottom) {
                                currentMessagesContainer.scrollTop = currentMessagesContainer.scrollHeight;
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
    /* Kontainer Chat Utama */
    .chat-container {
        background-color: #f9f9f9; /* Atau #fff jika ingin lebih putih seperti RoomChat.png */
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); /* Shadow lebih lembut */
        max-width: 700px;
        margin: 30px auto;
        padding: 0;
        display: flex;
        flex-direction: column;
        height: 600px; /* Tinggi tetap */
    }

    /* Header Chat */
    .chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between; /* Menempatkan item di ujung */
        padding: 15px 20px; /* Padding lebih kecil */
        border-bottom: 1px solid #eee;
        background-color: #f9f9f9;
        position: relative; /* Untuk positioning tombol X */
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        flex-grow: 1; /* Memungkinkan info header mengambil ruang */
        justify-content: center; /* Mempusatkan info profil */
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .chat-header-avatar {
        width: 40px; /* Ukuran avatar lebih kecil */
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px; /* Jarak lebih kecil */
    }

    .chat-header-info h4 {
        margin: 0;
        color: #333;
        font-size: 1rem; /* Ukuran font lebih kecil */
        font-weight: 600;
    }

    .chat-header-info p {
        margin: 2px 0 0;
        color: #777;
        font-size: 0.8rem; /* Ukuran font lebih kecil */
    }

    .close-button {
        background: none;
        border: none;
        color: #999;
        font-size: 1rem; /* Ukuran ikon X */
        cursor: pointer;
        padding: 5px; /* Tambah padding agar mudah diklik */
        position: absolute; /* Posisikan tombol X */
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        display: flex; /* Untuk pusatkan SVG */
        align-items: center;
        justify-content: center;
        z-index: 10; /* Pastikan di atas elemen lain */
    }
    .close-button:hover {
        color: #333;
    }


    /* Area Pesan */
    .chat-messages {
        flex-grow: 1;
        padding: 15px 20px; /* Padding disesuaikan */
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px; /* Jarak antar bubble pesan */
        background-color: #f9f9f9; /* Background kontainer pesan */
    }

    /* Message bubbles styles */
    .message-bubble {
        max-width: 80%; /* Sedikit lebih lebar dari 70% */
        padding: 10px 15px;
        border-radius: 12px; /* Umumnya 12px untuk sudut */
        margin-bottom: 5px; /* Jarak antar bubble lebih sedikit karena ada gap di parent */
        word-wrap: break-word;
        position: relative;
    }

    .message-bubble.sent {
        align-self: flex-end; /* Sent messages align to the right */
        background-color: #173F63; /* Warna biru gelap */
        color: white;
        border-bottom-right-radius: 2px; /* Efek "tail" */
        border-top-right-radius: 12px;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .message-bubble.received {
        align-self: flex-start; /* Received messages align to the left */
        background-color: #e0e0e0; /* Warna abu-abu terang */
        color: #333;
        border-bottom-left-radius: 2px; /* Efek "tail" */
        border-top-right-radius: 12px;
        border-top-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .message-bubble p {
        margin: 0;
        font-size: 0.9rem; /* Ukuran font sedikit lebih kecil */
        line-height: 1.4;
    }

    .message-time {
        font-size: 0.7rem; /* Ukuran font waktu lebih kecil */
        color: rgba(255, 255, 255, 0.7); /* Lebih transparan untuk sent */
        margin-top: 5px;
        display: block;
        text-align: right;
    }
    .message-bubble.received .message-time {
        color: #666; /* Warna gelap untuk received */
        text-align: left;
    }

    .message-attachment img {
        max-width: 100%;
        border-radius: 8px;
        margin-top: 10px;
        display: block;
    }

    /* Area Input Chat */
    .chat-input-area {
        padding: 15px 20px; /* Padding disesuaikan */
        border-top: 1px solid #eee;
        background-color: #f9f9f9;
    }

    .chat-input-area form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-text-input {
        flex-grow: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 25px; /* Lebih bulat */
        resize: none; /* Menghilangkan resize untuk input text */
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem; /* Ukuran font input */
        min-height: 40px; /* Tinggi input */
        max-height: 40px; /* Tinggi input */
        overflow-y: hidden; /* Sembunyikan scrollbar jika tidak perlu */
    }
    .chat-text-input:focus {
        outline: none;
        border-color: #a0a0a0;
    }

    .send-button {
        background-color: transparent; /* Transparan */
        color: #173F63; /* Warna ikon kirim */
        border: none;
        border-radius: 50%; /* Bulat sempurna */
        width: 40px; /* Ukuran tombol */
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.2s ease; /* Transisi warna ikon */
    }

    .send-button:hover {
        color: #0c2842; /* Warna ikon hover */
    }

    /* Menghilangkan file-upload-label dan input file jika tidak diinginkan */
    .file-upload-label {
        display: none;
    }
    #file-upload {
        display: none;
    }
</style>
@endsection
