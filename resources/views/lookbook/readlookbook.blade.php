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
            padding: 0 10px; /* Tambahkan sedikit padding */
        }

        .lookbook-header h1 {
            font-size: 1.8em;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        /* --- PERUBAHAN UTAMA: Form Search --- */
        .search-form {
            display: flex;
            align-items: center;
            background-color: #f5f5f5;
            border-radius: 25px;
            padding: 5px; /* Kurangi padding */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            width: 400px;
            max-width: 100%;
        }

        .search-form input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 16px;
            padding: 5px 15px; /* Sesuaikan padding input */
        }

        .search-form button {
            background-color: #FFC107;
            border: none;
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .search-form button:hover {
            background-color: #e0a800;
        }

        .search-form button svg {
            width: 18px;
            height: 18px;
        }
        /* --- Akhir Perubahan Form Search --- */


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

            display: block; /* Agar seluruh area <a> bisa diklik */
            text-decoration: none; /* Menghilangkan underline default pada link */
            color: inherit; /* Memastikan warna teks tidak berubah */
        }

        .outfit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .outfit-card::before,
        .outfit-card::after {
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #f4bc43;
        }

        .outfit-card::before { top: 0; }
        .outfit-card::after { bottom: 0; }

        .outfit-card img {
            width: 100%;
            height: 280px;
            object-fit: contain;
            display: block;
            padding: 10px;
            box-sizing: border-box;
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

        .outfit-card .outfit-info .stylist-name {
            font-size: 0.9em;
            color: #777;
            margin-top: 2px;
        }

        /* --- PERUBAHAN BARU: Style untuk pesan "No Results" --- */
        .no-results {
            grid-column: 1 / -1; /* Agar pesan mengambil seluruh lebar grid */
            text-align: center;
            padding: 4rem;
            color: #777;
        }

    </style>

    <div class="lookbook-header">
        <h1>Lookbook</h1>
        {{-- ========================================================= --}}
        {{-- =================== PERUBAHAN UTAMA =================== --}}
        {{-- ========================================================= --}}
        <form action="{{ route('user.lookbook.index') }}" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Cari jeans, summer fit..." value="{{ $search ?? '' }}">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="lookbook-grid">
        @forelse($lookbooks as $lookbook)
            {{-- Bagian ini sudah benar, tidak perlu diubah --}}
            <a href="{{ route('lookbook.show', $lookbook->idLookbook) }}" class="outfit-card">
                @if($lookbook->imgLookbook)
                    <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}">
                @else
                    <img src="https://via.placeholder.com/300x280?text=No+Image" alt="No Image Available">
                @endif
                <div class="outfit-info">
                    <div class="designer-name">{{ $lookbook->nama }}</div>
                    @if($lookbook->stylist)
                        <div class="stylist-name">by {{ $lookbook->stylist->nama }}</div>
                    @else
                        <div class="stylist-name">by Unknown Stylist</div>
                    @endif
                </div>
            </a>
        @empty
            {{-- --- PERUBAHAN BARU: Pesan jika tidak ada hasil --- --}}
            <div class="no-results">
                <p>Tidak ada lookbook yang cocok dengan pencarian Anda.</p>
            </div>
        @endforelse
    </div>
@endsection
