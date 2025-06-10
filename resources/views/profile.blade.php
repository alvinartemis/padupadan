@extends('layouts.app') {{-- Meng-extend dari layout app.blade.php --}}

@section('title', 'Profile - Padu Padan')

@section('content')
    <style>
        /* CSS yang spesifik untuk konten halaman ini */
        /* Hapus CSS yang menduplikasi style sidebar dan body yang ada di layouts/app.blade.php */
        /* Misalnya, Anda tidak perlu lagi:
           body { font-family: 'Poppins', ...; background-color: #f0f2f5; display: flex; min-height: 100vh; }
           .sidebar { ... }
           .sidebar .logo { ... }
           .sidebar .search-box { ... }
           .sidebar nav ul { ... }
           .sidebar nav ul li { ... }
           .sidebar nav ul li a { ... }
           .sidebar nav ul li a:hover, .sidebar nav ul li a.active { ... }
           .sidebar nav ul li a svg { ... }
           .sidebar nav ul li a.active svg { ... }
           .content-area { ... }
           .content { ... }
        */

        /* Main Content Area - ini harusnya sudah ada di layout utama jika Anda menggunakan main-content-wrapper */
        /* Jika belum, Anda bisa tambahkan ini di layouts/app.blade.php atau di sini sebagai content-block */
        .main-content-profile-page { /* Nama kelas baru agar tidak konflik dengan .main-content umum */
            flex-grow: 1;
            padding: 40px;
            background-color: #f0f2f5; /* Background ini mungkin sudah di body overall, tapi bisa override untuk konten utama */
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
            max-width: 900px;
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
            display: inline-block;
            background-color: #F4BC43;
            color: #2C3E50;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 0.9em;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .profile-actions .settings-btn:hover {
            background-color: #e0a830;
            transform: translateY(-1px);
        }

        .profile-content-grid {
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding-bottom: 40px;
        }

        @media (min-width: 768px) {
            .profile-content-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 767px) {
            .profile-content-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 10px;
            }
            .main-content-profile-page { /* Adjust padding for mobile */
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
            /* .profile-stats { gap: 20px; } */ /* Removed as stats are deleted */
            .profile-content-grid {
                grid-template-columns: 1fr; /* Satu kolom di mobile */
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
            /* Pastikan tidak ada fill="..." hardcode di dalam SVG jika ingin warna ini berlaku */
            fill: currentColor; /* Agar warnanya mengikuti CSS color */
        }
    </style>

    <div class="main-content-profile-page"> {{-- Wrapper untuk konten profil --}}
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="{{ $profileData['avatar_url'] }}" alt="User Avatar">
            </div>
            <div class="profile-info">
                <h1>{{ $profileData['nama'] }}</h1>
                <p class="username">@<span>{{ $profileData['username'] }}</span></p>
                <p class="bio">{{ $profileData['bio'] }}</p>
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
                                {{-- PERBAIKAN: Hapus fill-rule="evenodd" dan clip-rule="evenodd" jika itu bagian dari Font Awesome SVG --}}
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
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
@endsection
