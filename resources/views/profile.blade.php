@extends('layouts.app') {{-- Meng-extend dari layout app.blade.php --}}

@section('title', 'Profile - Padu Padan')

@section('content')
    <style>
        /* CSS yang spesifik untuk konten halaman ini */
        /* Hapus CSS yang menduplikasi style sidebar dan body yang ada di layouts/app.blade.php */

        .main-content-profile-page {
            flex-grow: 1;
            padding: 40px;
            background-color: #f0f2f5;
            overflow-y: auto;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-header {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            text-align: left;
            margin-bottom: 30px;
            gap: 30px;
        }

        .profile-avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: block;
        }

        .profile-info {
            /* Flex-grow untuk profile-info agar mengambil sisa ruang */
            flex-grow: 1;
        }

        .profile-info h1 {
            font-size: 2em;
            color: #333;
            margin: 0;
            font-weight: 700;
        }

        .profile-info p.username {
            font-size: 1.5em;
            color: #484848;
            margin: 0 0 5px 0 /* Kurangi margin untuk mendekatkan info tambahan */
        }

        .profile-info p.info-detail { /* Kelas baru untuk bodytype, skintone, style */
            font-size: 0.95em;
            color: #555;
            line-height: 1.4;
            margin: 0; /* Hapus margin default */
        }

        .profile-info p.bio {
            font-size: 0.95em;
            color: #555;
            line-height: 1.5;
            max-width: 600px; /* Lebar maksimum untuk bio jika terpisah */
            margin: 15px 0 20px 0; /* Beri margin atas dan bawah */
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
            .main-content-profile-page {
                padding: 20px;
            }
            .profile-header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            .profile-avatar img {
                width: 100px;
                height: 100px;
            }
            .profile-info h1 {
                font-size: 1.8em;
            }
            .profile-content-grid {
                grid-template-columns: 1fr;
            }
            /* Jika bio tidak perlu max-width di mobile atau perlu diatur ulang */
            .profile-info p.bio {
                margin: 15px auto 20px auto; /* Pusat bio di mobile */
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
            height: 250px;
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
            color: #e53e3e;
            fill: currentColor;
        }
    </style>

    <div class="main-content-profile-page">
        <div class="profile-header">
            <div class="profile-avatar">
                @if(isset($profileData['profilepicture']) && $profileData['profilepicture'])
                    <img src="{{ Storage::url($profileData['profilepicture']) }}" alt="User Avatar">
                @else
                    <img src="{{ asset('images/default_profile.png') }}" alt="Default Avatar">
                @endif
            </div>
            <div class="profile-info">
                <h1>{{ $profileData['nama'] }}</h1>
                <p class="username">@<span>{{ $profileData['username'] }}</span></p>
                {{-- Menampilkan bodytype, skintone, dan style di sini --}}
                @if($profileData['bodytype'] && $profileData['bodytype'] !== 'N/A')
                    <p class="info-detail">{{ $profileData['bodytype'] }}</p>
                @endif
                @if($profileData['skintone'] && $profileData['skintone'] !== 'N/A')
                    <p class="info-detail">{{ $profileData['skintone'] }}</p>
                @endif
                @if($profileData['style'] && $profileData['style'] !== 'N/A')
                    <p class="info-detail">{{ $profileData['style'] }} Outfits</p>
                @endif
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
