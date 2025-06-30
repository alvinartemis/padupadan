@extends('layouts.app') {{-- Pastikan ini mengacu pada nama file layout utama Anda --}}

@section('title', $lookbook->nama ?? 'Lookbook Detail') {{-- Menggunakan nama 'nama' dari lookbook sebagai judul --}}

@section('content')

<style>
    /* Styling untuk ikon bookmark */
    .bookmark-icon-container {
        cursor: pointer;
        display: inline-block;
        vertical-align: middle;
        margin-left: auto; /* Dorong bookmark ke kanan, menggantikan margin-left: auto; di .bookmark-icon sebelumnya */
        font-size: 1.5em; /* Ukuran ikon bookmark */
        transition: color 0.2s ease; /* Transisi untuk perubahan warna */
    }

    .bookmark-icon-container svg {
        width: 30px; /* Sesuaikan ukuran ikon */
        height: 30px; /* Sesuaikan ukuran ikon */
        transition: fill 0.3s ease, stroke 0.3s ease; /* Transisi untuk perubahan warna */
    }

    /* Warna untuk status belum di-bookmark (putih/abu-abu terang) */
    .bookmark-icon-container svg.unbookmarked {
        fill: #ffffff; /* Putih */
        stroke: #888; /* Outline abu-abu seperti gambar */
        stroke-width: 1.5px; /* Ketebalan outline */
    }

    /* Warna untuk status sudah di-bookmark (kuning/orange) */
    .bookmark-icon-container svg.bookmarked {
        fill: #FFC107; /* Orange/Kuning dari gambar Anda */
        stroke: #FFC107; /* Warna stroke sama dengan fill untuk tampilan solid */
        stroke-width: 1.5px;
    }

    /* Hanya CSS yang spesifik untuk item-detail-container dan isinya */
    /* Hapus atau sesuaikan CSS global body jika sudah ada di layouts.app */
    .item-detail-container {
        display: flex; /* Menggunakan flexbox untuk layout utama */
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        max-width: 900px; /* Lebar maksimum container */
        margin: 0 auto; /* Cukup 0 auto; untuk memusatkan di dalam flex-grow:1 parent. */
        overflow: hidden; /* Penting untuk radius border */
        position: relative; /* Untuk posisi tombol close */
        padding: 30px; /* Padding di dalam container */
        box-sizing: border-box; /* Include padding in element's total width and height */
    }

    /* Close Button */
    .close-button {
        position: absolute;
        top: 20px; /* Jarak dari atas */
        left: 20px; /* Pindah ke kiri */
        background: none;
        border: none;
        font-size: 2.5em;
        color: #888;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 5px; /* Memberikan area klik yang lebih besar */
        line-height: 1; /* Menjaga ukuran ikon */
        z-index: 10; /* Pastikan tombol di atas konten lain */
    }

    .close-button:hover {
        color: #333;
    }

    /* Image Wrapper */
    .item-image-wrapper {
        flex: 1; /* Ambil setengah lebar */
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f8f8; /* Background untuk area gambar */
        border-radius: 10px; /* Border radius untuk wrapper gambar */
        padding: 20px; /* Padding di sekitar gambar */
    }

    .item-image-wrapper img {
        max-width: 100%;
        max-height: 450px; /* Batasi tinggi gambar */
        object-fit: contain; /* Memastikan gambar terlihat penuh tanpa terpotong */
        border-radius: 8px; /* Border radius untuk gambar itu sendiri */
    }

    /* Item Info (Right Column) */
    .item-info {
        flex: 1; /* Ambil setengah lebar */
        padding-left: 40px; /* Jarak antara gambar dan info */
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* Pastikan konten dimulai dari atas */
    }

    /* User Info (Stylist) */
    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 20px; /* Jarak antara user info dan judul */
    }

    .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%; /* Membuat avatar bulat */
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #eee; /* Border tipis untuk avatar */
    }

    .user-info span {
        font-weight: 600;
        color: #333;
        font-size: 1.1em;
    }

    /* Item Title */
    .item-title {
        font-size: 2.2em; /* Ukuran judul lebih besar */
        color: #333;
        margin: 0 0 15px 0; /* Margin bawah untuk judul */
        font-weight: 700;
        line-height: 1.2;
    }

    /* Item Description */
    .item-description {
        font-size: 1em;
        color: #555;
        line-height: 1.6;
        margin-bottom: 20px; /* Jarak bawah untuk deskripsi */
        white-space: pre-wrap; /* Mempertahankan spasi dan line break dari database */
    }

    /* Hashtags */
    .item-hashtags {
        display: flex;
        flex-wrap: wrap; /* Izinkan hashtag untuk wrap ke baris berikutnya */
        gap: 8px; /* Jarak antar hashtag */
        margin-top: 15px; /* Jarak atas dari deskripsi */
        margin-bottom: 30px; /* Jarak bawah dari tombol */
    }

    .item-hashtags span {
        background-color: #e0e0e0;
        color: #666;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
    }

    /* Continue Shopping Button */
    .continue-shopping-button {
        display: inline-flex; /* Gunakan flexbox untuk menengahkan ikon */
        align-items: center; /* Tengahkan vertikal */
        justify-content: center; /* Tengahkan horizontal */
        background-color: #FFC107; /* Warna biru, sesuaikan dengan branding Anda */
        color: #fff;
        padding: 12px 25px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.05em;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: auto; /* Dorong tombol ke bagian bawah item-info jika ada ruang */
        align-self: flex-start; /* Posisikan tombol di kiri jika flex-direction adalah column */
    }

    .continue-shopping-button:hover {
        background-color: #e0a800; /* Warna hover yang lebih gelap */
        transform: translateY(-2px);
    }

    /* Tambahkan ikon keranjang belanja kecil jika diperlukan */
    .continue-shopping-button::after {
        content: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/></svg>');
        margin-left: 8px;
        display: inline-block;
        vertical-align: middle;
    }


    /* Responsive adjustments */
    @media (max-width: 768px) {
        .item-detail-container {
            flex-direction: column; /* Ubah menjadi satu kolom di layar kecil */
            padding: 20px;
        }

        .item-image-wrapper {
            padding: 15px;
            margin-bottom: 20px; /* Jarak antara gambar dan info */
        }

        .item-info {
            padding-left: 0; /* Hapus padding kiri */
        }

        .user-info {
            margin-bottom: 15px;
        }

        .item-title {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .item-description {
            font-size: 0.95em;
            margin-bottom: 15px;
        }

        .item-hashtags {
            gap: 6px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .item-hashtags span {
            padding: 5px 10px;
            font-size: 0.8em;
        }

        .continue-shopping-button {
            padding: 10px 20px;
            font-size: 0.95em;
            width: 100%; /* Buat tombol full width */
            box-sizing: border-box;
        }

        .close-button {
            font-size: 2em;
            top: 15px;
            left: 15px; /* Pindah ke kiri di layar kecil */
        }
    }
</style>

<div class="item-detail-container">
    {{-- Tombol Close --}}
    <button class="close-button" onclick="history.back()">
        &times;
    </button>

    <div class="item-image-wrapper">
        {{-- Pastikan asset() mengarah ke lokasi gambar yang benar.
            Jika 'lookbooks/' adalah subfolder di 'public/storage', maka ini sudah benar.
            Jika gambar disimpan di lokasi lain, sesuaikan path.
        --}}
        <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}">
    </div>

    <div class="item-info">
        <div class="user-info">
            @if($lookbook->stylist) {{-- Cek apakah objek stylist ada --}}
                {{-- Asumsi model Stylist memiliki kolom 'profilepicture' (seperti User model) dan 'nama' --}}
                <img src="{{ asset('stylist/' . $lookbook->stylist->profilepicture) }}" alt="{{ $lookbook->stylist->nama }} Avatar">
                <span>{{ $lookbook->stylist->nama }}</span>
            @else
                {{-- Fallback jika data stylist tidak ada atau tidak dimuat --}}
                <img src="https://via.placeholder.com/40?text=S" alt="Stylist Avatar"> {{-- Placeholder avatar --}}
                <span>Stylist ID: {{ $lookbook->idStylist }}</span> {{-- Tampilkan ID stylist jika nama tidak tersedia --}}
            @endif

            {{-- Perubahan Utama: Integrasi ikon bookmark SVG --}}
            {{-- Mengubah $lookbook->id menjadi $lookbook->idLookbook --}}
            <div class="bookmark-icon-container" id="bookmarkIcon" data-lookbook-id="{{ $lookbook->idLookbook }}">
                @if(Auth::check() && $isBookmarked) {{-- Cek apakah user login dan lookbook di-bookmark --}}
                    {{-- Ikon terisi (bookmarked) --}}
                    <svg class="bookmarked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                @else
                    {{-- Ikon kosong (unbookmarked) --}}
                    <svg class="unbookmarked" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                @endif
            </div>
        </div>

        <h1 class="item-title">{{ $lookbook->nama }}</h1>
        <p class="item-description">{{ $lookbook->description }}</p>

        <div class="item-hashtags">
            @php
                // Pisahkan string 'kataKunci' menjadi array.
                // Asumsi dipisahkan koma. Jika spasi, gunakan explode(' ', ...).
                // Filter array untuk menghapus string kosong yang mungkin muncul dari koma berturut-turut.
                $rawHashtags = explode(',', $lookbook->kataKunci ?? ''); // Gunakan null coalescing operator untuk safety
                $hashtags = array_filter(array_map('trim', $rawHashtags));
            @endphp
            @foreach($hashtags as $hashtag)
                <span>#{{ $hashtag }}</span> {{-- Tambahkan '#' di depan setiap hashtag --}}
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookmarkIconContainer = document.getElementById('bookmarkIcon');

    if (bookmarkIconContainer) {
        console.log('Bookmark icon container found.'); // Debug: pastikan elemen ditemukan
        bookmarkIconContainer.addEventListener('click', function() {
            console.log('Bookmark icon clicked!'); // Debug: pastikan event listener aktif

            // Pastikan pengguna sudah login sebelum memungkinkan bookmarking
            @guest
                alert('Anda harus login untuk menggunakan fitur bookmark.'); // Atau tampilkan modal login
                window.location.href = '{{ route('login') }}'; // Redirect ke halaman login
                console.log('User not logged in, redirecting...');
                return; // Hentikan eksekusi lebih lanjut
            @endguest

            const lookbookId = this.dataset.lookbookId; // Mendapatkan ID lookbook dari atribut data
            const currentSvg = this.querySelector('svg'); // Mendapatkan elemen SVG di dalam container

            // PERBAIKAN: Periksa apakah lookbookId valid
            if (!lookbookId || lookbookId === 'undefined' || lookbookId === 'null') {
                console.error('Error: lookbookId is missing or invalid in data-lookbook-id attribute. Value:', lookbookId); // Tambahkan nilai untuk debugging
                alert('Tidak dapat menandai lookbook. ID tidak ditemukan.');
                return; // Hentikan eksekusi jika ID tidak valid
            }

            console.log('Lookbook ID:', lookbookId); // Debug: pastikan ID lookbook diambil
            console.log('Current SVG classes:', currentSvg.classList); // Debug: pastikan kelas SVG

            // Menentukan URL untuk request AJAX
            const url = `{{ route('bookmark.lookbook.toggle', ['lookbook' => ':lookbookId']) }}`;
            const finalUrl = url.replace(':lookbookId', lookbookId);
            console.log('AJAX URL:', finalUrl); // Debug: pastikan URL AJAX benar

            fetch(finalUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Mengirim CSRF token untuk keamanan Laravel
                },
            })
            .then(response => {
                console.log('AJAX response received. Status:', response.status); // Debug: respons awal
                if (!response.ok) {
                    // Handle HTTP errors
                    if (response.status === 401) {
                         alert('Sesi Anda telah habis atau tidak terotorisasi. Mohon login kembali.');
                         window.location.href = '{{ route('login') }}'; // Redirect ke halaman login
                         console.error('Unauthorized, redirecting to login.');
                    }
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Server responded with an error');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('AJAX data received:', data); // Debug: data respons JSON
                if (data.status === 'success') {
                    // Memperbarui UI berdasarkan status bookmark yang baru
                    if (data.bookmark_status === 'bookmarked') {
                        currentSvg.classList.remove('unbookmarked');
                        currentSvg.classList.add('bookmarked');
                        console.log('SVG class changed to bookmarked.');
                    } else {
                        currentSvg.classList.remove('bookmarked');
                        currentSvg.classList.add('unbookmarked');
                        console.log('SVG class changed to unbookmarked.');
                    }
                    console.log(data.message); // Log pesan sukses
                } else {
                    console.error('Failed to toggle bookmark:', data.message);
                }
            })
            .catch(error => {
                console.error('Error toggling bookmark (catch block):', error);
                // Opsional: tampilkan pesan error generik kepada pengguna
            });
        });
    } else {
        console.error('Bookmark icon container (ID: bookmarkIcon) not found in DOM.'); // Debug: elemen tidak ditemukan
    }
});
</script>
@endsection
