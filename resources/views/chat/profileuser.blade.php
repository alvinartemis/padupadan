@extends('layouts.stylist')

@section('title', $user->nama . ' profile')

@section('content')
    <div style="background-color: #ffffff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); max-width: 700px; margin: 30px auto; padding: 30px;">
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 25px;">
            <button onclick="window.history.back()" style="background: none; border: none; color: #999; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 30px;">
            <div style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; margin-right: 30px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);">
                <img src="{{ asset('storage/' . $user->profilepicture) }}" alt="{{ $user->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
                <h2 style="margin-top: 0; margin-bottom: 5px; color: #333; font-weight: 600; font-size: 1.8rem;">{{ $user->nama }}</h2>
                <p style="margin-top: 0; margin-bottom: 10px; color: #777; font-size: 1rem;">{{ '@' . $user->username }}</p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-around; flex-wrap: wrap; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
            <div style="display: flex; align-items: center; margin: 10px 15px;">
                <div>
                    <p style="margin: 0; font-size: 0.85rem; color: #777;">Gender</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $user->gender ?? '-' }}</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 15px;">
                <div>
                    <p style="margin: 0; font-size: 0.85rem; color: #777;">Body Type</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $user->bodytype ?? '-' }}</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 15px;">
                <div>
                    <p style="margin: 0; font-size: 0.85rem; color: #777;">Skintone</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ $user->skintone ?? '-' }}</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; margin: 10px 15px;">
                <div>
                    <p style="margin: 0; font-size: 0.85rem; color: #777;">Style Preference</p>
                    <p style="margin: 2px 0 0 0; font-weight: 600; color: #333; font-size: 0.9rem;">{{ implode(', ', (array)($user->preferences ?? [])) ?: '-' }}</p>
                </div>
            </div>
        </div>

        <div>
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.2rem; color: #333; font-weight: 600; display: flex; align-items: center;">
                <img src="{{ asset('img/war.png') }}" alt="Wardrobe Icon" style="width: 20px; height: 20px; margin-right: 8px;">
                Wardrobe
            </h3>
        </div>

        <div class="wardrobe-container">
            @if($koleksiOutfits->count() > 0)
                <div class="outfit-slider-wrapper">
                    <button class="slider-arrow slider-arrow-left" onclick="moveSlider(-1)">&lt;</button>
                    <div class="outfit-slider" id="outfitSlider">
                        @foreach($koleksiOutfits as $outfit)
                            <a href="#" class="outfit-card-link">
                                <div class="outfit-card">
                                    <img src="{{ asset('storage/' . $outfit->foto) }}" alt="{{ $outfit->nama }}" class="outfit-image">
                                    <p class="outfit-name">{{ $outfit->nama }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <button class="slider-arrow slider-arrow-right" onclick="moveSlider(1)">&gt;</button>
                </div>
            @else
                <div class="empty-wardrobe">
                    <p>Koleksi outfit milik user ini masih kosong atau tidak ada yang bersifat publik.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

<style>
        .wardrobe-container {
        padding-top: 20px;
    }

    .outfit-slider-wrapper {
        position: relative;
        overflow: hidden;
        margin: 0 auto;
        padding: 0 40px;
    }

    .outfit-slider {
        display: flex;
        overflow-x: scroll;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        gap: 20px;
        padding-bottom: 15px;
    }

    .outfit-slider::-webkit-scrollbar {
        display: none;
    }

    .outfit-card-link {
        text-decoration: none;
        color: inherit;
        flex-shrink: 0;
        scroll-snap-align: start;
        width: 150px;
        box-sizing: border-box;
    }

    .outfit-card {
        background-color: #f9f9f9;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 100%;
    }

    .outfit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .outfit-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }

    .outfit-name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.9em;
        text-align: center;
        padding: 10px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
    }

    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid var(--light-grey);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--text-medium);
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 5px var(--shadow-light);
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .slider-arrow:hover {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .slider-arrow-left {
        left: 0;
    }

    .slider-arrow-right {
        right: 0;
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
</style>
