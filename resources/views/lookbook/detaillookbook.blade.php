@extends('layouts.app')

@section('title', $lookbook->nama ?? 'Lookbook Detail')

@section('content')

<style>
    /* ... (Semua CSS Anda yang sudah ada) ... */

    /* Tambahkan atau perbarui CSS untuk tombol bookmark */
    .bookmark-button {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        transition: background-color 0.2s ease;
        position: relative;
    }

    .bookmark-button:hover {
        background-color: #f0f0f0;
    }

    .bookmark-icon {
        width: 24px;
        height: 24px;
        transition: opacity 0.2s ease, color 0.2s ease;
        position: absolute;
    }

    .bookmark-icon-outline {
        color: #A3A3A3;
        opacity: 1;
    }

    .bookmark-icon-filled {
        color: #173F63;
        opacity: 0;
    }

    .bookmark-button.bookmarked .bookmark-icon-outline {
        opacity: 0;
    }

    .bookmark-button.bookmarked .bookmark-icon-filled {
        opacity: 1;
    }
</style>

<div class="item-detail-container">
    {{-- Tombol Close --}}
    <button class="close-button" onclick="history.back()">
        &times;
    </button>

    <div class="item-image-wrapper">
        <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}">
    </div>

    <div class="item-info">
        <div class="user-info">
            @if($lookbook->stylist)
                <img src="{{ asset('storage/' . $lookbook->stylist->avatar_url) }}" alt="{{ $lookbook->stylist->nama }} Avatar">
                <span>{{ $lookbook->stylist->nama }}</span>
            @else
                <img src="https://via.placeholder.com/40?text=S" alt="Stylist Avatar">
                <span>Stylist ID: {{ $lookbook->idStylist }}</span>
            @endif

             <span class="bookmark-icon">🔖</span>
            {{-- TOMBOL MARKAH --}}
            {{-- <button type="button" class="bookmark-button @if($isBookmarked) bookmarked @endif" data-lookbook-id="{{ $lookbook->idLookbook }}">
                <svg class="bookmark-icon bookmark-icon-outline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor">
                    <path d="M0 48V487.7C0 501.9 14.2 512 28.3 512c6.7 0 13.3-2.6 18.3-7.4L192 376.1 337.4 504.6c5 4.8 11.6 7.4 18.3 7.4c14.1 0 28.3-10.1 28.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"/>
                </svg>
                <svg class="bookmark-icon bookmark-icon-filled" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor">
                    <path d="M0 48V487.7c0 18.8 20.6 31.7 37 22.9L192 421.1 347 510.6c16.3 8.9 37-3.9 37-22.9V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"/>
                </svg>
            </button> --}}
        </div>

        <h1 class="item-title">{{ $lookbook->nama }}</h1>
        <p class="item-description">{{ $lookbook->description }}</p>

        <div class="item-hashtags">
            @php
                $rawHashtags = explode(',', $lookbook->kataKunci ?? '');
                $hashtags = array_filter(array_map('trim', $rawHashtags));
            @endphp
            @foreach($hashtags as $hashtag)
                <span>#{{ $hashtag }}</span>
            @endforeach
        </div>

        <a href="https://www.uniqlo.com/id/en/products/E464027-000?colorCode=COL57&sizeCode=SMA007" class="continue-shopping-button">Continue Shopping</a>
    </div>
</div>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const bookmarkButtons = document.querySelectorAll('.bookmark-button');

        bookmarkButtons.forEach(button => {
            button.addEventListener('click', function () {
                const lookbookId = this.dataset.lookbookId;
                const isBookmarked = this.classList.contains('bookmarked');
                const url = isBookmarked ? '{{ route("item-fashion.remove") }}' : '{{ route("item-fashion.add") }}';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ lookbook_id: lookbookId })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Server error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        this.classList.toggle('bookmarked'); // Ini yang mengubah warna/ikon!
                        console.log(data.message);
                    } else {
                        console.error('Backend reported an error:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('Terjadi kesalahan saat menyimpan item: ' + error.message);
                });
            });
        });
    });
</script> --}}
@endsection
