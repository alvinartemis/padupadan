@extends('layouts.stylist')

@section('title', $lookbook->nama ?? 'Lookbook Detail Anda')

@section('content')

<style>
    .item-detail-container {
        display: flex;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        padding: 30px;
        box-sizing: border-box;
    }

    /* Close Button */
    .close-button {
        position: absolute;
        top: 20px;
        left: 20px;
        background: none;
        border: none;
        font-size: 2.5em;
        color: #888;
        cursor: pointer;
        transition: color 0.2s ease;
        padding: 5px;
        line-height: 1;
        z-index: 10;
    }

    .close-button:hover {
        color: #333;
    }

    /* Image Wrapper */
    .item-image-wrapper {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f8f8;
        border-radius: 10px;
        padding: 20px;
    }

    .item-image-wrapper img {
        max-width: 100%;
        max-height: 450px;
        object-fit: contain;
        border-radius: 8px;
    }

    /* Item Info (Right Column) */
    .item-info {
        flex: 1;
        padding-left: 40px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    /* User Info (Stylist) */
    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #eee;
    }

    .user-info span {
        font-weight: 600;
        color: #333;
        font-size: 1.1em;
    }

    /* Item Title */
    .item-title {
        font-size: 2.2em;
        color: #333;
        margin: 0 0 15px 0;
        font-weight: 700;
        line-height: 1.2;
    }

    /* Item Description */
    .item-description {
        font-size: 1em;
        color: #555;
        line-height: 1.6;
        margin-bottom: 20px;
        white-space: pre-wrap;
    }

    /* Hashtags */
    .item-hashtags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 15px;
        margin-bottom: 30px;
    }

    .item-hashtags span {
        background-color: #e0e0e0;
        color: #666;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 500;
    }

    /* Continue Shopping Button (disini akan jadi "Kembali ke Daftar Lookbook") */
    .continue-shopping-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #FFC107;
        color: #fff;
        padding: 12px 25px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.05em;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: auto;
        align-self: flex-start;
    }

    .continue-shopping-button:hover {
        background-color: #e0a800;
        transform: translateY(-2px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .item-detail-container {
            flex-direction: column;
            padding: 20px;
        }

        .item-image-wrapper {
            padding: 15px;
            margin-bottom: 20px;
        }

        .item-info {
            padding-left: 0;
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
            width: 100%;
            box-sizing: border-box;
        }

        .close-button {
            font-size: 2em;
            top: 15px;
            left: 15px;
        }
    }
</style>

<div class="item-detail-container">
    <button class="close-button" onclick="history.back()">
        &times;
    </button>

    <div class="item-image-wrapper">
        <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}">
    </div>

    <div class="item-info">
        <div class="user-info">
            @if($lookbook->stylist)
                @if($lookbook->stylist->profilepicture)
                    <img src="{{ asset('stylist/' . $lookbook->stylist->profilepicture) }}" alt="{{ $lookbook->stylist->nama }} Avatar">
                @else
                    <img src="https://via.placeholder.com/40?text=S" alt="Stylist Avatar"> {{-- Placeholder avatar --}}
                @endif
                <span>{{ $lookbook->stylist->nama }}</span>
            @else
                <img src="https://via.placeholder.com/40?text=S" alt="Stylist Avatar"> {{-- Placeholder avatar --}}
                <span>Stylist ID: {{ $lookbook->idStylist }}</span> {{-- Tampilkan ID stylist jika nama tidak tersedia --}}
            @endif

        </div>

        <h1 class="item-title">{{ $lookbook->nama }}</h1>
        <p class="item-description">{{ $lookbook->description }}</p>

        <div class="item-hashtags">
            @php
                $rawHashtags = explode(',', $lookbook->kataKunci ?? '');
                $hashtags = array_filter(array_map('trim', $rawHashtags));
            @endphp
            @foreach($hashtags as $hashtag)
                <span>{{ $hashtag }}</span>
            @endforeach
        </div>
    </div>
</div>

@endsection
