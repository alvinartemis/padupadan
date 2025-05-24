@extends('settings') {{-- Pastikan ini mengacu pada nama file layout utama Anda, misal: 'layouts.settings' atau 'setting' saja jika langsung di root views --}}

@section('title', $item['name'] ?? 'Fashion Item Detail') {{-- Menggunakan nama item sebagai judul --}}

@section('content')

<div class="item-detail-container">
    {{-- Tombol Close --}}
    <button class="close-button" onclick="history.back()">
        &times;
    </button>

    <div class="item-image-wrapper">
        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
    </div>

    <div class="item-info">
        <div class="user-info">
            <img src="{{ $item['user_avatar'] }}" alt="{{ $item['user'] }} Avatar">
            <span>{{ $item['user'] }}</span>
            <span class="bookmark-icon">
                🔖 {{-- Unicode untuk ikon bookmark --}}
            </span>
        </div>

        <h1 class="item-title">{{ $item['name'] }}</h1>
        <p class="item-description">{{ $item['description'] }}</p>

        <div class="item-hashtags">
            @foreach($item['hashtags'] as $hashtag)
                <span>{{ $hashtag }}</span>
            @endforeach
        </div>

        <a href="https://www.uniqlo.com/id/en/products/E464027-000?colorCode=COL57&sizeCode=SMA007" class="continue-shopping-button">Continue Shopping</a>
    </div>
</div>
@endsection
