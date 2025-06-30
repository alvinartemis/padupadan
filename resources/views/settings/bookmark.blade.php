@extends('layouts.settings')

@section('title', 'Bookmark')

@section('content')

<style>
    /* CSS yang sudah ada (tidak berubah signifikan kecuali penyesuaian untuk kartu item) */
    .bookmark-header {
        position: relative;
        text-align: center;
        margin-bottom: 30px;
    }

    .bookmark-header h1 {
        color: black;
        font-size: 32px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        line-height: 33.60px;
        margin-top: 0;
        margin-bottom: 0;
    }

    #bookmark-toggle {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #E1E1E1;
        box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25) inset;
        border-radius: 38px;
        width: 331px;
        height: 47px;
        margin: 0 auto 30px auto;
        position: relative;
    }

    .toggle-button {
        flex-grow: 1;
        padding: 10px 0;
        border: none;
        background-color: transparent;
        color: #939393;
        font-size: 16px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        line-height: 16px;
        cursor: pointer;
        transition: color 0.3s ease;
        text-align: center;
        z-index: 2;
    }

    .toggle-background {
        position: absolute;
        top: 0;
        height: 100%;
        background: var(--Indigo-dye, #173F63);
        box-shadow: 0px 0px 2.299999952316284px rgba(0, 0, 0, 0.25);
        border-radius: 43px;
        transition: left 0.3s ease, width 0.3s ease;
        z-index: 1;
    }

    .toggle-button.active {
        color: white;
        font-weight: 700;
        font-size: 17px;
        line-height: 17px;
    }

    .content-section {
        display: none;
        padding: 20px 0;
    }

    .content-section.active {
        display: block;
    }

    .fashion-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Responsive grid */
        gap: 20px;
        padding: 0 20px;
    }

    /* Styling untuk kartu fashion item yang di-bookmark */
    .fashion-item-card {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Untuk mendorong tombol 'View Detail' ke bawah */
    }

    .fashion-item-card img {
        width: 100%;
        height: 180px; /* Tinggi gambar tetap */
        object-fit: cover; /* Pastikan gambar mengisi area */
        display: block;
        border-bottom: 1px solid #eee;
    }

    .fashion-item-card .item-info-text {
        padding: 10px;
        text-align: left;
    }

    .fashion-item-card .item-info-text h3 {
        font-size: 1.1em;
        margin: 0 0 5px 0;
        color: #333;
        font-weight: 600;
    }

    .fashion-item-card .item-info-text p {
        font-size: 0.9em;
        margin: 0;
        color: #666;
    }

    .fashion-item-card .view-detail-button {
        display: block;
        width: 100%;
        padding: 10px 0;
        background-color: #173F63; /* Warna Indigo Dye */
        color: white;
        text-decoration: none;
        font-weight: 600;
        border: none;
        border-radius: 0 0 8px 8px; /* Hanya sudut bawah */
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .fashion-item-card .view-detail-button:hover {
        background-color: #102e48; /* Warna yang lebih gelap saat hover */
    }

    .video-placeholder {
        text-align: center;
        padding: 40px;
        background-color: #f2f2f2;
        border-radius: 10px;
        margin: 20px auto;
        max-width: 600px;
        color: #555;
    }

    .video-placeholder p {
        font-size: 18px;
        font-family: 'Montserrat', sans-serif;
    }

    @media (max-width: 400px) {
        #bookmark-toggle {
            width: 90%;
        }
        .toggle-button {
            font-size: 14px;
        }
        .toggle-button.active {
            font-size: 15px;
        }
    }
</style>

<div class="container mt-4">
    <div class="bookmark-header">
        <h1>Bookmark</h1> {{-- Menggunakan h1 sesuai design --}}
    </div>

    <div id="bookmark-toggle">
        <div class="toggle-background"></div> {{-- Latar belakang biru yang bergerak --}}
        <button class="toggle-button" id="videoToggle">Video</button>
        <button class="toggle-button active" id="fashionItemToggle">Lookbook</button>
    </div>

    <div id="videoContent" class="content-section">
        @if($bookmarkedVideos->isEmpty())
            <div class="video-placeholder">
                <p>You haven't bookmarked any videos yet!</p>
            </div>
        @else
            <div class="fashion-items-grid"> {{-- Anda bisa menggunakan grid yang sama atau buat style baru --}}
                @foreach($bookmarkedVideos as $bookmark)
                    @if($bookmark->video) {{-- Pastikan relasi video ada --}}
                        <div class="fashion-item-card">
                            <video width="100%" height="180" controls muted>
                                <source src="{{ asset('storage/' . $bookmark->video->pathFile) }}" type="{{ $bookmark->video->mimeType }}">
                                Your browser does not support the video tag.
                            </video>
                            <div class="item-info-text">
                                <h3>{{ Str::limit($bookmark->video->deskripsi, 20) ?? 'Video' }}</h3>
                                <p>by {{ $bookmark->video->user->nama ?? 'Unknown User' }}</p>
                            </div>
                            {{-- Arahkan ke halaman detail video jika ada, jika tidak, cukup tampilkan di sini --}}
                            {{-- <a href="#" class="view-detail-button">View Detail</a> --}}
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- Fashion Item Content --}}
    <div id="fashionItemContent" class="content-section active">
        <div class="fashion-items-grid">
            {{-- Perubahan Utama: Looping melalui $bookmarkedLookbooks dari controller --}}
            @forelse($bookmarkedLookbooks as $lookbook)
                <div class="fashion-item-card">
                    {{-- Pastikan imgLookbook adalah kolom yang benar di model Lookbook Anda --}}
                    <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama ?? 'Lookbook Image' }}">
                    <div class="item-info-text">
                        <h3>{{ $lookbook->nama ?? 'Nama Lookbook' }}</h3>
                        {{-- Memastikan relasi stylist ada dan nama stylist tersedia --}}
                        <p>by {{ $lookbook->stylist->nama ?? 'Unknown Stylist' }}</p>
                    </div>
                    {{-- Arahkan ke halaman detail lookbook menggunakan idLookbook --}}
                    <a href="{{ route('lookbook.show', $lookbook->idLookbook) }}" class="view-detail-button">View Detail</a>
                </div>
            @empty
                {{-- Tampilkan pesan jika tidak ada lookbook yang di-bookmark --}}
                <div class="video-placeholder" style="grid-column: 1 / -1;">
                    <p>You haven't bookmarked any fashion items yet!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const videoToggle = document.getElementById('videoToggle');
        const fashionItemToggle = document.getElementById('fashionItemToggle');
        const videoContent = document.getElementById('videoContent');
        const fashionItemContent = document.getElementById('fashionItemContent');
        const toggleBackground = document.querySelector('.toggle-background');
        // const toggleContainer = document.getElementById('bookmark-toggle'); // Tidak digunakan secara langsung di sini

        function updateToggleBackground() {
            const activeToggle = document.querySelector('.toggle-button.active');
            if (activeToggle) {
                toggleBackground.style.width = activeToggle.offsetWidth + 'px';
                toggleBackground.style.left = activeToggle.offsetLeft + 'px';
            }
        }

        // Inisialisasi posisi background saat halaman dimuat
        updateToggleBackground();
        window.addEventListener('resize', updateToggleBackground); // Perbarui saat ukuran jendela berubah

        videoToggle.addEventListener('click', function() {
            videoToggle.classList.add('active');
            fashionItemToggle.classList.remove('active');
            videoContent.classList.add('active');
            fashionItemContent.classList.remove('active');
            updateToggleBackground();
        });

        fashionItemToggle.addEventListener('click', function() {
            fashionItemToggle.classList.add('active');
            videoToggle.classList.remove('active');
            fashionItemContent.classList.add('active');
            videoContent.classList.remove('active');
            updateToggleBackground();
        });

        // Tidak perlu memicu klik saat DOMContentLoaded jika sudah ada kelas 'active' di HTML
    });
</script>

@endsection
