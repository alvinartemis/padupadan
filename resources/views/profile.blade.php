<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Padu Padan</title>
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
            height: 100vh;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            left: 0;
            z-index: 100;
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




        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 40px;
            background-color: #f0f2f5;
            overflow-y: auto;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center; /* Memusatkan konten di tengah */
        }




        .profile-header {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 900px; /* Lebar maksimum seperti card lainnya */
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 30px;
        }




        .profile-avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }




        .profile-info h1 {
            font-size: 2em;
            color: #333;
            margin: 0 0 5px 0;
            font-weight: 700;
        }




        .profile-info p.username {
            font-size: 1.1em;
            color: #666;
            margin-bottom: 15px;
        }




        .profile-info p.bio {
            font-size: 0.95em;
            color: #555;
            line-height: 1.5;
            max-width: 600px;
            margin: 0 auto 20px auto;
        }




        .profile-actions button {
            background-color: #007bff;
            color: hsl(0, 0%, 100%);
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 0.9em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }



        .profile-actions .settings-btn {
            display: inline-block; /* Penting agar padding dan border-radius bekerja pada <a> */
            background-color: #F4BC43; /* Warna kuning dari gambar */
            color: #2C3E50; /* Warna teks biru gelap dari gambar */
            border: none;
            padding: 10px 25px; /* Sesuaikan padding agar terlihat proporsional */
            border-radius: 25px; /* Membuat sudut sangat melengkung */
            font-size: 0.9em;
            font-weight: 600; /* Membuat teks tebal */
            text-decoration: none; /* Menghilangkan garis bawah default link */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Tambahkan sedikit bayangan */
        }

        .profile-actions .settings-btn:hover {
            background-color: #e0a830; /* Warna kuning sedikit lebih gelap saat hover */
            transform: translateY(-1px); /* Efek sedikit naik saat hover */
        }


        .profile-content-grid {
            width: 100%;
            max-width: 900px; /* Sama dengan header */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Ubah minmax jadi 200px */
            gap: 15px; /* Perkecil gap agar muat 4 */
            padding-bottom: 40px; /* Padding di bagian bawah */
        }




        @media (min-width: 768px) {
            .profile-content-grid {
                grid-template-columns: repeat(4, 1fr); /* Paksa 4 kolom di layar sedang/besar */
            }
        }




        @media (max-width: 767px) {
            .profile-content-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Lebih responsif di layar kecil */
                gap: 10px;
            }
        }




        .post-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
            cursor: pointer;
        }




        .post-card:hover {
            transform: translateY(-5px);
        }




        .post-card img,
        .post-card video {
            width: 100%;
            height: 250px; /* Tinggi tetap untuk post */
            object-fit: cover;
            display: block;
        }




        .post-card .post-footer {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9em;
            color: #666;
        }




        .post-card .post-footer .likes-count {
            display: flex;
            align-items: center;
            gap: 5px;
        }




        .post-card .post-footer .likes-count svg {
            width: 16px;
            height: 16px;
            color: #e53e3e; /* Warna hati */
        }




        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
                padding: 15px 10px;
            }
            .sidebar .logo {
                justify-content: center;
                margin-bottom: 20px;
            }
            .sidebar .logo img {
                height: 30px;
            }
            .sidebar .search-box,
            .sidebar nav ul li a span {
                display: none;
            }
            .sidebar nav ul li a {
                justify-content: center;
                padding: 10px;
            }
            .sidebar nav ul li a svg {
                margin-right: 0;
            }
            .main-content {
                padding: 20px;
            }
            .profile-header {
                padding: 20px;
            }
            .profile-avatar img {
                width: 100px;
                height: 100px;
            }
            .profile-info h1 {
                font-size: 1.8em;
            }
            .profile-stats {
                gap: 20px;
            }
            .profile-content-grid {
                grid-template-columns: 1fr; /* Satu kolom di mobile */
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="https://i.imgur.com/P4WfI0h.png" alt="Padu Padan Logo">
        </div>
        <div class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Search">
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
                    <a href="#">
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
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>Chat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}" class="active"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>




    <div class="main-content">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="{{ $profileData['avatar_url'] }}" alt="User Avatar">
            </div>
            <div class="profile-info">
                <h1>{{ $profileData['nama'] }}</h1>
                <p class="username">@<span>{{ $profileData['username'] }}</span></p>
                <p class="bio">{{ $profileData['bio'] }}</p>
                {{-- Bagian Statistik Dihapus Total --}}
                <div class="profile-actions">
                    <a href="{{ route('profile.edit') }}" class="settings-btn">Settings</a>
                </div>
            </div>
        </div>




        <div class="profile-content-grid">
            @forelse($posts as $post)
            <div class="post-card">
                @if(Str::startsWith($post->mimeType ?? '', 'video/'))
                    <video controls muted loop poster="{{ asset('images/default-thumbnail.png') }}">
                        <source src="{{ Storage::url($post->pathFile) }}" type="{{ $post->mimeType }}">
                        Your browser does not support the video tag.
                    </video>
                @elseif(Str::startsWith($post->mimeType ?? '', 'image/'))
                    <img src="{{ Storage::url($post->pathFile) }}" alt="{{ $post->deskripsi ?? 'Post Image' }}">
                @else
                    <img src="https://via.placeholder.com/300x250?text=No+Preview" alt="No Preview">
                @endif
                <div class="post-footer">
                    <div class="likes-count">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ number_format(rand(10, 5000), 0, ',', '.') }}</span>
                    </div>
                    <div class="comments-count">
                        <span>Comments</span>
                    </div>
                </div>
            </div>
            @empty
                <p>No posts yet. Start sharing your outfits!</p>
            @endforelse
        </div>
    </div>
</body>
</html>





