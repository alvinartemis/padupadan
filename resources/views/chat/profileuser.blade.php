@extends('layouts.stylist')

@section('title', $user->nama . ' Profile')

@section('content')
    <div class="profile-container">
        <div class="profile-header-section">
            <button onclick="window.history.back()" class="back-button">&times;</button>
        </div>

        <div class="user-info-section">
            <div class="user-avatar-wrapper">
                <img src="{{ asset('user/' . $user->profilepicture) }}" alt="{{ $user->nama }}" class="user-avatar">
            </div>
            <div>
                <h2 class="user-name">{{ $user->nama }}</h2>
                <p class="user-username">{{ '@' . $user->username }}</p>
            </div>
        </div>

        <div class="user-details-grid">
            <div class="detail-item">
                <p class="detail-label">Gender</p>
                <p class="detail-value">{{ $user->gender ?? '-' }}</p>
            </div>
            <div class="detail-item">
                <p class="detail-label">Body Type</p>
                <p class="detail-value">{{ $user->bodytype ?? '-' }}</p>
            </div>
            <div class="detail-item">
                <p class="detail-label">Skintone</p>
                <p class="detail-value">{{ $user->skintone ?? '-' }}</p>
            </div>
            <div class="detail-item">
                <p class="detail-label">Style Preference</p>
                {{-- Menampilkan preferences. Asumsi preferences adalah array JSON yang di-cast --}}
                <p class="detail-value">{{ implode(', ', (array)($user->preferences ?? [])) ?: '-' }}</p>
            </div>
        </div>

        <div class="wardrobe-section-title">
            <h3 class="wardrobe-heading">
                <img src="{{ asset('img/war.png') }}" alt="Wardrobe Icon" class="wardrobe-icon">
                Wardrobe
            </h3>
        </div>

        {{-- Digital Wardrobe Content (Hanya yang Public) --}}
        <div class="wardrobe-container">
            {{-- Tabs untuk Items dan Outfits --}}
            <div class="section-tabs">
                @foreach($sections as $sectionValue)
                    <a href="{{ route('chat.profileuser', ['user' => $user->idPengguna, 'section' => $sectionValue, 'category' => $selectedCategory]) }}"
                       class="btn {{ $selectedSection == $sectionValue ? 'active' : '' }}">
                        {{ ucfirst($sectionValue) }}
                    </a>
                @endforeach
            </div>

            {{-- Filter Kategori (hanya muncul untuk section 'items') --}}
            @if($selectedSection == 'items')
                <div class="category-filters">
                    @foreach($categories as $categoryValue)
                        <a href="{{ route('chat.profileuser', ['user' => $user->idPengguna, 'section' => $selectedSection, 'category' => $categoryValue]) }}"
                           class="btn {{ $selectedCategory == $categoryValue ? 'active' : '' }}">
                            {{ ucfirst($categoryValue) }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Tampilkan Koleksi Pakaian/Outfit --}}
            @if($koleksiPakaian->count() > 0)
                <div class="clothing-grid">
                    @foreach($koleksiPakaian as $item)
                        {{-- Link ke detail item/outfit. Sesuaikan jika ada route detail untuk stylist --}}
                        <a href="#" class="clothing-item-link">
                            <div class="clothing-item">
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
                                <div class="clothing-item-info">
                                    <div class="clothing-item-name" title="{{ $item->nama }}">{{ $item->nama }}</div>
                                    {{-- Kolom visibility tidak perlu ditampilkan karena sudah difilter public --}}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-wardrobe">
                    <p>Koleksi '{{ ucfirst($selectedSection) }} @if($selectedSection == 'items') / {{ ucfirst($selectedCategory) }} @endif' milik user ini masih kosong atau tidak ada yang bersifat publik.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    :root {
        --primary-color: #F4BC43;
        --secondary-color: #0C2A42;
        --light-grey: #eee;
        --text-dark: #333;
        --text-medium: #777;
        --text-light: #999;
        --shadow-light: rgba(0, 0, 0, 0.1);
        --border-radius: 15px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }

    .profile-container {
        background-color: #ffffff;
        border-radius: var(--border-radius);
        box-shadow: 0 5px 15px var(--shadow-light);
        max-width: 700px;
        margin: 30px auto;
        padding: 30px;
    }

    .profile-header-section {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 25px;
    }

    .back-button {
        background: none;
        border: none;
        color: var(--text-light);
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .back-button:hover {
        color: var(--text-dark);
    }

    .user-info-section {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 1px solid var(--light-grey);
        padding-bottom: 30px;
    }

    .user-avatar-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .user-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        margin-top: 0;
        margin-bottom: 5px;
        color: var(--text-dark);
        font-weight: 600;
        font-size: 1.8rem;
    }

    .user-username {
        margin-top: 0;
        margin-bottom: 10px;
        color: var(--text-medium);
        font-size: 1rem;
    }

    .user-details-grid {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--light-grey);
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 10px 15px;
        text-align: center;
    }

    .detail-label {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-medium);
    }

    .detail-value {
        margin: 2px 0 0 0;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .wardrobe-section-title {
        margin-bottom: 20px;
    }

    .wardrobe-heading {
        margin-top: 0;
        font-size: 1.3rem;
        color: var(--text-dark);
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .wardrobe-icon {
        width: 24px;
        height: 24px;
        margin-right: 8px;
    }

    .wardrobe-container {
        padding-top: 20px;
    }

    .wardrobe-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .wardrobe-title {
        color: var(--secondary-color);
        font-size: 2em;
        margin-bottom: 10px;
    }

    .section-tabs, .category-filters {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .btn {
        background-color: #e0e0e0;
        color: var(--text-dark);
        padding: 8px 15px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.9em;
        font-weight: 500;
        transition: background-color 0.3s ease, color 0.3s ease;
        white-space: nowrap;
    }

    .btn.active {
        background-color: var(--primary-color);
        color: white;
    }

    .btn:hover:not(.active) {
        background-color: #d0d0d0;
    }

    .action-buttons-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .action-btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .action-btn-icon svg {
        width: 24px;
        height: 24px;
    }

    .action-btn-icon:hover {
        background-color: #e0ac3a;
    }

    .clothing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 20px;
        padding-top: 10px;
    }

    .clothing-item-link {
        text-decoration: none;
        color: inherit;
    }

    .clothing-item {
        background-color: #f9f9f9;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .clothing-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .clothing-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .clothing-item-info {
        padding: 10px;
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .clothing-item-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.95em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }

    .clothing-item-visibility {
        font-size: 0.75em;
        padding: 3px 8px;
        border-radius: 10px;
        font-weight: 500;
        display: inline-block;
        align-self: center;
    }

    .visibility-public {
        background-color: #e6ffe6;
        color: #008000;
        border: 1px solid #008000;
    }

    .visibility-private {
        background-color: #fff0f0;
        color: #d9534f;
        border: 1px solid #d9534f;
    }

    .empty-wardrobe {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-medium);
        font-style: italic;
        background-color: #fdfdfd;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .empty-wardrobe p {
        margin-bottom: 10px;
    }

    .add-initial-item-btn {
        background-color: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
        display: inline-block;
        margin-top: 15px;
    }

    .add-initial-item-btn:hover {
        background-color: #e0ac3a;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .profile-container {
            margin: 15px;
            padding: 20px;
        }

        .user-info-section {
            flex-direction: column;
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .user-avatar-wrapper {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .user-name {
            font-size: 1.5rem;
        }

        .user-username {
            font-size: 0.9rem;
        }

        .user-details-grid {
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
        }

        .detail-item {
            margin: 8px 0;
            width: 100%;
        }

        .wardrobe-heading {
            font-size: 1.2rem;
        }

        .section-tabs, .category-filters {
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .btn {
            width: 80%;
            max-width: 200px;
        }

        .clothing-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
        }

        .clothing-item img {
            height: 120px;
        }
    }
</style>
@endpush
