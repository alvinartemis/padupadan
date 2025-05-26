<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - Padu Padan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #fff;
            padding: 20px;
            border-right: 1px solid #eee;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 100vh; /* Fixed height for sidebar */
            position: sticky;
            top: 0;
        }
        .sidebar .logo {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }
        .sidebar .logo img {
            height: 40px;
            width: auto;
            margin-left: 0;
        }
        .sidebar .search-box {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 20px;
            padding: 8px 15px;
            margin-bottom: 30px;
            cursor: pointer;
        }
        .sidebar .search-box input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 14px;
            margin-left: 10px;
            cursor: pointer;
        }
        .sidebar .search-box svg {
            width: 18px;
            height: 18px;
            color: #888;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar nav ul li {
            margin-bottom: 15px;
        }
        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }
        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            background-color: #e0f7fa;
            color: #007bff;
        }
        .sidebar nav ul li a svg {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            color: #888;
        }
        .sidebar nav ul li a.active svg {
            color: #007bff;
        }

        .main-content-search {
            flex-grow: 1;
            padding: 30px;
            box-sizing: border-box;
        }
        .search-results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .video-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .video-card:hover {
            transform: translateY(-5px);
        }
        .video-card img {
            width: 100%;
            height: 250px; /* Tinggi thumbnail */
            object-fit: cover;
            display: block;
        }
        .video-card .info {
            padding: 10px;
        }
        .video-card .info .username {
            font-weight: 600;
            font-size: 0.9em;
            color: #333;
            margin-bottom: 5px;
        }
        .video-card .info .description {
            font-size: 0.8em;
            color: #666;
            line-height: 1.3;
            max-height: 3.9em; /* Menampilkan sekitar 3 baris */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Batasi hingga 3 baris */
            -webkit-box-orient: vertical;
        }
        .no-results {
            text-align: center;
            color: #888;
            margin-top: 50px;
            font-size: 1.1em;
        }

        /* Gaya baru untuk overlay pencarian (diperlukan juga di halaman hasil pencarian) */
        .search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparan overlay */
            display: flex;
            justify-content: flex-start; /* Untuk menempatkan konten di kiri */
            align-items: flex-start;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .search-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .search-content {
            background-color: #fff;
            width: 500px; /* Lebar konten pencarian */
            height: 100%;
            padding: 30px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transform: translateX(-100%); /* Sembunyikan ke kiri */
            transition: transform 0.3s ease-out;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .search-overlay.active .search-content {
            transform: translateX(0); /* Munculkan dari kiri */
        }

        .search-header-overlay { /* Mengganti .search-header */
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-header-overlay h2 {
            margin: 0;
            font-size: 1.8em;
            color: #333;
            font-weight: 600;
        }

        .search-header-overlay .close-search {
            background: none;
            border: none;
            font-size: 2em;
            cursor: pointer;
            color: #888;
        }

        .search-input-overlay {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 20px;
            padding: 8px 15px;
            margin-bottom: 30px;
        }

        .search-input-overlay input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 16px;
            margin-left: 10px;
            padding: 0; /* Hapus padding default */
        }

        .search-input-overlay svg {
            width: 20px;
            height: 20px;
            color: #888;
        }

        .search-section-title {
            font-size: 1.1em;
            font-weight: 600;
            color: #555;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .search-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .search-list li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.95em;
            color: #444;
            cursor: pointer;
            padding: 8px 0;
            transition: background-color 0.2s ease;
        }

        .search-list li:hover {
            background-color: #f9f9f9;
        }

        .search-list li svg {
            margin-right: 15px;
            width: 18px;
            height: 18px;
            color: #888;
        }

        .search-list li .clear-search-item {
            margin-left: auto;
            color: #bbb;
            cursor: pointer;
        }
        .search-list li .clear-search-item:hover {
            color: #888;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logoy.png') }}" alt="Logo Padu Padan">
        </div>
        <div class="search-box" id="sidebarSearchBox">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Search" id="sidebarSearchInput" readonly value="{{ $query ?? '' }}">
        </div>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h3v-3m0 0h3m-3 0h-3v-3m9 2h3a1 1 0 001-1v-3m-4-7v4m-3 0h-4a1 2 0 00-1 1v3" />
                        </svg>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('upload') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Upload</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('digital.wardrobe.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m-8-4v10l8 4 8-4V7m-4 5v6m-4-6v6m-4-6v6" />
                        </svg>
                        <span>Digital Wardrobe</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Lookbook</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>Chat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="main-content-search">
        <h1>Search Results for "{{ $query ?? '' }}"</h1>

        @if($videos->isEmpty())
            <p class="no-results">Tidak ada video yang ditemukan untuk kata kunci "{{ $query ?? '' }}".</p>
        @else
            <div class="search-results-grid">
                @foreach($videos as $video)
                    <div class="video-card">
                        <img src="{{ $video['thumbnail'] }}" alt="{{ $video['description'] }}">
                        <div class="info">
                            <div class="username">{{ $video['username'] }}</div>
                            <div class="description">{{ $video['description'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div id="searchOverlay" class="search-overlay">
        <div class="search-content">
            <div class="search-header-overlay">
                <h2>Search</h2>
                <button class="close-search" id="closeSearchButton">&times;</button>
            </div>
            <form id="searchFormOverlay" action="{{ route('search.index') }}" method="GET">
                <div class="search-input-overlay">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="outfits for school" id="overlaySearchInput" name="query">
                    <button type="button" style="background: none; border: none; cursor: pointer; padding: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px; color: #aaa;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="recent-searches">
                <div class="search-section-title">Recent searches</div>
                <ul class="search-list" id="recentSearchesList">
                    </ul>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarSearchBox = document.getElementById('sidebarSearchBox');
            const sidebarSearchInput = document.getElementById('sidebarSearchInput');
            const searchOverlay = document.getElementById('searchOverlay');
            const closeSearchButton = document.getElementById('closeSearchButton');
            const overlaySearchInput = document.getElementById('overlaySearchInput');
            const recentSearchesList = document.getElementById('recentSearchesList');
            const searchFormOverlay = document.getElementById('searchFormOverlay');
            const clearOverlaySearchInputButton = searchFormOverlay.querySelector('button'); // Tombol 'x' di overlay

            // Event Listener for sidebar search box to open overlay
            sidebarSearchBox.addEventListener('click', () => {
                searchOverlay.classList.add('active');
                overlaySearchInput.focus();
                fetchRecentSearches(); // Muat pencarian terkini saat overlay dibuka
            });

            // Event Listener for close search overlay button
            closeSearchButton.addEventListener('click', () => {
                searchOverlay.classList.remove('active');
            });

            // Handle submission from the overlay search input
            searchFormOverlay.addEventListener('submit', (event) => {
                // Biarkan form disubmit secara normal ke route search.index
            });

            // Handle clear button for overlay search input
            clearOverlaySearchInputButton.addEventListener('click', (event) => {
                event.preventDefault(); // Mencegah submit form
                overlaySearchInput.value = '';
            });


            // Function to fetch and display recent searches
            async function fetchRecentSearches() {
                try {
                    const response = await fetch('{{ route('api.search.recent') }}');
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    recentSearchesList.innerHTML = ''; // Clear existing list
                    if (data.length > 0) {
                        data.forEach(item => {
                            const li = document.createElement('li');
                            li.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="search-text">${item}</span>
                                <span class="clear-search-item" data-keyword="${item}">&times;</span>
                            `;
                            // Event listener for clicking on recent search item to perform search
                            li.querySelector('.search-text').addEventListener('click', () => {
                                overlaySearchInput.value = item;
                                searchFormOverlay.submit(); // Submit form with the clicked keyword
                            });
                            // Event listener for clearing individual recent search item (future enhancement if needed, currently client-side only)
                            li.querySelector('.clear-search-item').addEventListener('click', (e) => {
                                e.stopPropagation(); // Prevent li click
                                // Here you would ideally make an API call to delete the recent search from DB
                                li.remove(); // Remove from UI
                            });
                            recentSearchesList.appendChild(li);
                        });
                    } else {
                        recentSearchesList.innerHTML = '<p style="text-align: center; color: #888;">Belum ada pencarian terkini.</p>';
                    }
                } catch (error) {
                    console.error('Error fetching recent searches:', error);
                    recentSearchesList.innerHTML = '<p style="text-align: center; color: #888;">Gagal memuat pencarian terkini.</p>';
                }
            }

            // Inisialisasi input pencarian di sidebar dengan query saat ini jika ada
            @if(isset($query))
                sidebarSearchInput.value = "{{ $query }}";
                overlaySearchInput.value = "{{ $query }}";
            @endif
        });
    </script>
</body>
</html>
