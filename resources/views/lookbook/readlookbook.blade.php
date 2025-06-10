@extends('layouts.app') {{-- Meng-extend dari layout app.blade.php --}}

@section('title', 'Lookbook - Padu Padan')

@section('content')
    <style>
        /* CSS Khusus untuk Halaman Read Lookbook Pengguna Biasa */
        .content-block { /* A wrapper for content, similar to your old .content */
            background-color: #fff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            min-height: calc(100vh - 40px); /* Sesuaikan berdasarkan padding dari main-content-wrapper */
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .lookbook-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-right: 20px;
        }

        .lookbook-header h1 {
            font-size: 1.8em;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .lookbook-search-main { /* Search bar di bagian konten utama */
            display: flex;
            align-items: center;
            background-color: #fff;
            border-radius: 25px;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            width: 400px;
            max-width: 100%;
        }

        .lookbook-search-main input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 16px;
            margin-left: 10px;
        }

        .lookbook-search-main svg {
            width: 20px;
            height: 20px;
            color: #888;
        }

        .lookbook-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Menggunakan minmax 250px untuk 3-4 kolom */
            gap: 20px;
            padding-top: 10px;
            padding-bottom: 20px;
            flex-grow: 1;
        }

        .outfit-card {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            border: 1px solid #eee; /* Default border */
            position: relative; /* Untuk garis pink */
        }

        .outfit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        /* Garis border pink */
        .outfit-card::before,
        .outfit-card::after {
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px; /* Ketebalan garis */
            background-color: #f4bc43; /* Warna kuning, sesuaikan ke pink jika diinginkan */
        }

        .outfit-card::before {
            top: 0;
        }

        .outfit-card::after {
            bottom: 0;
        }

        .outfit-card img {
            width: 100%;
            height: 280px;
            object-fit: contain; /* Menggunakan contain untuk tampilan gambar penuh */
            display: block;
            padding: 10px; /* Padding untuk gambar di dalam kartu */
            box-sizing: border-box; /* Sertakan padding dalam lebar/tinggi */
        }

        .outfit-card .outfit-info {
            padding: 15px;
            text-align: center;
        }

        .outfit-card .outfit-info .designer-name {
            font-weight: 600;
            color: #333;
            margin-top: 5px;
            font-size: 1em;
        }

        /* Penyesuaian responsif */
        @media (max-width: 768px) {
            .lookbook-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 15px;
            }
            .outfit-card img {
                height: 180px;
            }
        }

        @media (max-width: 480px) {
            .lookbook-grid {
                grid-template-columns: 1fr;
            }
            .outfit-card img {
                height: 150px;
            }
        }
    </style>

        <div class="lookbook-header">
            <h1>Lookbook</h1>
            <div class="lookbook-search-main">
                <input type="text" placeholder="search">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="lookbook-grid">
            @forelse($lookbooks as $lookbook)
            <div class="outfit-card">
                @if($lookbook->imgLookbook)
                    <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}">
                @else
                    <img src="https://via.placeholder.com/300x280?text=No+Image" alt="No Image Available">
                @endif
                <div class="outfit-info">
                    <div class="designer-name">{{ $lookbook->nama }}</div>
                </div>
            </div>
            @empty
                <p>Belum ada lookbook yang tersedia.</p>
            @endforelse
        </div>
@endsection
