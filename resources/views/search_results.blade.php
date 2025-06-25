<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result - Padu Padan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
            align-items: flex-start;
        }
        .sidebar .logo {
            margin-bottom: 30px;
        }
        .sidebar .logo img {
            height: 40px;
            width: auto;
        }
        .sidebar .search-box {
            display: flex;
            align-items: center;
            background-color: #e0e0e0;
            border-radius: 20px;
            padding: 8px 15px;
            margin-bottom: 30px;
            width: 100%;
            box-sizing: border-box;
        }
        .sidebar .search-box input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 14px;
            margin-left: 10px;
        }
        .sidebar .search-box svg {
            width: 18px;
            height: 18px;
            color: #A3A3A3;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .sidebar nav ul li {
            margin-bottom: 15px;
        }
        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #A3A3A3;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }
        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            color: #173F63;
            /background-color: #e0f7fa;
        }
        .sidebar nav ul li a svg {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            color: #888;
        }
        .sidebar nav ul li a.active svg {
            color: #173F63;
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
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
            <input type="text" placeholder="Search" id="sidebarSearchInput" readonly value="{{ $query ?? '' }}">
        </div>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('upload') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3 192 320c0 17.7 14.3 32 32 32s32-14.3 32-32l0-210.7 73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-64z"/></svg>
                        Upload
                    </a>
                </li>
                <li>
                    <a href="{{ route('digital.wardrobe.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M211.8 0c7.8 0 14.3 5.7 16.7 13.2C240.8 51.9 277.1 80 320 80s79.2-28.1 91.5-66.8C413.9 5.7 420.4 0 428.2 0l12.6 0c22.5 0 44.2 7.9 61.5 22.3L628.5 127.4c6.6 5.5 10.7 13.5 11.4 22.1s-2.1 17.1-7.8 23.6l-56 64c-11.4 13.1-31.2 14.6-44.6 3.5L480 197.7 480 448c0 35.3-28.7 64-64 64l-192 0c-35.3 0-64-28.7-64-64l0-250.3-51.5 42.9c-13.3 11.1-33.1 9.6-44.6-3.5l-56-64c-5.7-6.5-8.5-15-7.8-23.6s4.8-16.6 11.4-22.1L137.7 22.3C155 7.9 176.7 0 199.2 0l12.6 0z"/></svg>
                        Digital Wardrobe
                    </a>
                </li>
                <li>
                    <a href="#" class=>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M249.6 471.5c10.8 3.8 22.4-4.1 22.4-15.5l0-377.4c0-4.2-1.6-8.4-5-11C247.4 52 202.4 32 144 32C93.5 32 46.3 45.3 18.1 56.1C6.8 60.5 0 71.7 0 83.8L0 454.1c0 11.9 12.8 20.2 24.1 16.5C55.6 460.1 105.5 448 144 448c33.9 0 79 14 105.6 23.5zm76.8 0C353 462 398.1 448 432 448c38.5 0 88.4 12.1 119.9 22.6c11.3 3.8 24.1-4.6 24.1-16.5l0-370.3c0-12.1-6.8-23.3-18.1-27.6C529.7 45.3 482.5 32 432 32c-58.4 0-103.4 20-123 35.6c-3.3 2.6-5 6.8-5 11L304 456c0 11.4 11.7 19.3 22.4 15.5z"/></svg>
                        Lookbook
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M208 352c114.9 0 208-78.8 208-176S322.9 0 208 0S0 78.8 0 176c0 38.6 14.7 74.3 39.6 103.4c-3.5 9.4-8.7 17.7-14.2 24.7c-4.8 6.2-9.7 11-13.3 14.3c-1.8 1.6-3.3 2.9-4.3 3.7c-.5 .4-.9 .7-1.1 .8l-.2 .2s0 0 0 0s0 0 0 0C1 327.2-1.4 334.4 .8 340.9S9.1 352 16 352c21.8 0 43.8-5.6 62.1-12.5c9.2-3.5 17.8-7.4 25.2-11.4C134.1 343.3 169.8 352 208 352zM448 176c0 112.3-99.1 196.9-216.5 207C255.8 457.4 336.4 512 432 512c38.2 0 73.9-8.7 104.7-23.9c7.5 4 16 7.9 25.2 11.4c18.3 6.9 40.3 12.5 62.1 12.5c6.9 0 13.1-4.5 15.2-11.1c2.1-6.6-.2-13.8-5.8-17.9c0 0 0 0 0 0s0 0 0 0l-.2-.2c-.2-.2-.6-.4-1.1-.8c-1-.8-2.5-2-4.3-3.7c-3.6-3.3-8.5-8.1-13.3-14.3c-5.5-7-10.7-15.4-14.2-24.7c24.9-29 39.6-64.7 39.6-103.4c0-92.8-84.9-168.9-192.6-175.5c.4 5.1 .6 10.3 .6 15.5z"/></svg>
                        Chat
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>
                        Profile
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="main-content-search">
        <h1>Search Results for "{{ $query ?? '' }}"</h1>

        @if($videos->isEmpty())
            <p class="no-results">No video"{{ $query ?? '' }}".</p>
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
