@extends('layouts.settings')

@section('title', 'Bookmark')

@section('content')
<div class="container mt-4">
    <div class="bookmark-header">
        <h2 style="text-align: center; margin-bottom: 2rem;">Bookmark</h2>
    </div>

    <div id="bookmark-toggle">
        <div class="toggle-background"></div> {{-- Latar belakang biru yang bergerak --}}
        <button class="toggle-button" data-target="video-content" data-width="146px" data-left="0px">Video</button>
        <button class="toggle-button active" data-target="fashion-item-content" data-width="185px" data-left="146px">Fashion Item</button>
    </div>

    {{-- Fashion Item Content --}}
    <div id="fashion-item-content" class="content-section active">
        <div class="fashion-items-grid">
{{-- DATA DUMMY UNTUK FASHION ITEMS --}}
            {{-- Dalam aplikasi nyata, data ini akan diambil dari database dan diteruskan dari controller --}}
            @php
                $fashionItems = [
                    ['id' => 1, 'img' => asset('greenjacket.png'), 'alt' => 'Green Jacket'],
                    ['id' => 2, 'img' => asset('skirt.png'), 'alt' => 'Brown Plaid Skirt'],
                    ['id' => 3, 'img' => asset('cargoshorts.png'), 'alt' => 'Olive Cargo Shorts'],
                    ['id' => 4, 'img' => asset('bluesneakers.png'), 'alt' => 'Blue Sneakers'],
                ];
            @endphp

            @foreach($fashionItems as $item)
                <div class="fashion-item">
                    {{-- INI ADALAH BAGIAN KUNCI: Menambahkan tag <a> yang mengarah ke rute detail item --}}
                    <a href="{{ route('bookmark.show_item', ['id' => $item['id']]) }}">
                        <img src="{{ $item['img'] }}" alt="{{ $item['alt'] }}">
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Video Content --}}
    <div id="video-content" class="content-section">
        <div class="video-placeholder">
            <p>Bagian ini akan menampilkan video yang Anda bookmark.</p>
            <p>Anda bisa menambahkan thumbnail video, pemutar video, atau tautan di sini.</p>
            {{-- Contoh: Embed video YouTube --}}
            {{--
            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%; background: #000;">
                <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        src="https://www.youtube.com/embed/YOUR_VIDEO_ID"
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                </iframe>
            </div>
            --}}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll('.toggle-button');
        const contentSections = document.querySelectorAll('.content-section');
        const toggleBackground = document.querySelector('.toggle-background');

        // Fungsi untuk mengupdate posisi dan ukuran latar belakang biru
        function updateToggleBackground(activeButton) {
            const buttonWidth = activeButton.offsetWidth;
            const buttonLeft = activeButton.offsetLeft;

            // Jika Anda ingin menggunakan data-width dan data-left dari tombol,
            // pastikan nilainya diatur dengan benar di HTML untuk setiap tombol.
            // Misalnya:
            // const targetWidth = activeButton.dataset.width;
            // const targetLeft = activeButton.dataset.left;
            // toggleBackground.style.width = targetWidth;
            // toggleBackground.style.left = targetLeft;

            // Untuk saat ini, kita akan menghitungnya secara dinamis
            toggleBackground.style.width = `${buttonWidth}px`;
            toggleBackground.style.left = `${buttonLeft}px`;
        }

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove 'active' class from all buttons and reset font styles
                toggleButtons.forEach(btn => {
                    btn.classList.remove('active');
                    // Reset CSS properties if they were directly set by 'active' class
                    // btn.style.fontWeight = '600';
                    // btn.style.fontSize = '16px';
                    // btn.style.lineHeight = '16px';
                    // btn.style.color = '#939393';
                });

                // Add 'active' class to the clicked button
                this.classList.add('active');
                // updateToggleBackground(this); // Update background position

                // Mengupdate posisi dan ukuran latar belakang biru secara dinamis
                // Menggunakan offsetLeft dan offsetWidth dari tombol yang aktif
                updateToggleBackground(this);


                // Hide all content sections
                contentSections.forEach(section => section.classList.remove('active'));

                // Show the target content section
                const targetId = this.dataset.target;
                document.getElementById(targetId).classList.add('active');
            });
        });

        // Set initial active state and background position based on the default active button
        const initialActiveButton = document.querySelector('.toggle-button.active');
        if (initialActiveButton) {
            updateToggleBackground(initialActiveButton);
        }
    });
</script>
@endsection
