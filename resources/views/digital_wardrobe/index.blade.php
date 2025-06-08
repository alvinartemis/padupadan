@extends('layouts.app') {{-- Make sure this path is correct for your layout file --}}

@section('title', 'Padu Padan')

@section('content')
<style>
    .wardrobe-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    .wardrobe-header {
        margin-bottom: 20px; /* Increased margin for spacing after title */
        text-align: center;
    }
    .wardrobe-title {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    /* Removed .filter-group and .filter-label as per request */

    .section-tabs, .category-filters {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .section-tabs .btn, .category-filters .btn {
        padding: 8px 15px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        color: #333;
        text-decoration: none;
        border-radius: 20px;
        font-size: 14px;
        transition: background-color 0.3s, color 0.3s;
    }
    .section-tabs .btn.active, .category-filters .btn.active,
    .section-tabs .btn:hover, .category-filters .btn:hover {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .action-buttons-container {
        display: flex;
        justify-content: flex-end; /* Align buttons to the right */
        gap: 10px; /* Space between buttons */
        margin-top: 10px; /* Space after category filters */
        margin-bottom: 25px; /* Space before the grid */
    }
    .action-btn-icon {
        background-color: #f0f0f0;
        color: #555;
        padding: 6px; /* Further reduced padding */
        text-decoration: none;
        border-radius: 6px; /* Rounded square shape */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;  /* Further reduced width (e.g., 32px + 2px border) */
        height: 34px; /* Further reduced height */
        border: 1px solid #e0e0e0;
        transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
    }
    .action-btn-icon:hover {
        background-color: #e0e0e0;
        color: #007bff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .action-btn-icon svg {
        width: 18px; /* Further reduced icon size */
        height: 18px;
    }

    .clothing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
    }
    .clothing-item {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: transform 0.2s;
        position: relative; /* Untuk positioning badge visibilitas */
    }
    .clothing-item:hover {
        transform: translateY(-5px);
    }
    .clothing-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    .clothing-item-name {
        font-weight: 500;
        font-size: 15px;
        color: #333;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .clothing-item-link {
        text-decoration: none;
        color: inherit; /* Mewarisi warna teks dari parent */
        display: block; /* Membuat link mengambil ruang dari div .clothing-item */
    }
    .clothing-item-link:hover {
        text-decoration: none;
        color: inherit;
    }
    .visibility-badge {
        font-size: 11px;
        font-weight: 500;
        padding: 3px 8px;
        border-radius: 10px;
        position: absolute;
        top: 15px; /* Sesuaikan posisi */
        right: 15px; /* Sesuaikan posisi */
        text-transform: capitalize;
    }
    .visibility-public {
        background-color: #d1e7dd; /* Bootstrap success light */
        color: #0f5132;    /* Bootstrap success dark */
        border: 1px solid #badbcc;
    }
    .visibility-private {
        background-color: #f8d7da; /* Bootstrap danger light */
        color: #58151c;    /* Bootstrap danger dark */
        border: 1px solid #f5c2c7;
    }

    /* Perbedaan visual untuk item private secara keseluruhan (opsional, contoh) */
    .clothing-item.private-item {
        /* border-left: 5px solid #6c757d; /* Contoh: border abu-abu di kiri */
        /* opacity: 0.85; */ /* Contoh: sedikit transparan */
    }
    .empty-wardrobe {
        text-align: center;
        padding: 40px 20px;
        color: #777;
        background-color: #f9f9f9;
        border-radius: 8px;
        margin-top: 20px;
    }
    .empty-wardrobe p {
        margin-bottom: 15px;
        font-size: 16px;
    }
    .empty-wardrobe .add-initial-item-btn {
        background-color: #F7D46C;
        color: black;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 500;
        font-size: 14px;
        display: inline-block;
    }
    .empty-wardrobe .add-initial-item-btn:hover {
        background-color: #F4BC43;
    }

</style>

<div class="wardrobe-container">
    <div class="wardrobe-header">
        <h1 class="wardrobe-title">Digital Wardrobe</h1>
    </div>

    <div class="section-tabs">
        @foreach($sections as $sectionValue)
            <a href="{{ route('digital.wardrobe.index', ['section' => $sectionValue, 'category' => 'All']) }}"
               class="btn {{ $selectedSection == $sectionValue ? 'active' : '' }}">
                {{ ucfirst($sectionValue) }}
            </a>
        @endforeach
    </div>

    <div class="category-filters">
        @foreach($categories as $categoryValue)
            <a href="{{ route('digital.wardrobe.index', ['section' => $selectedSection, 'category' => $categoryValue]) }}"
               class="btn {{ $selectedCategory == $categoryValue ? 'active' : '' }}">
                {{ ucfirst($categoryValue) }}
            </a>
        @endforeach
    </div>

    <div class="action-buttons-container">
        <a href="{{ route('digital.wardrobe.create') }}" class="action-btn-icon add-item-btn" title="Add New Item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </a>
        <a href="#" id="deleteSelectedBtn" class="action-btn-icon delete-item-btn" title="Delete Selected">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.56 0c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; border:1px solid #badbcc; padding: 10px; border-radius: 5px; margin: 15px 0; text-align:center;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('status'))
        <div style="background-color: #cfe2ff; color: #052c65; border:1px solid #b6d4fe; padding: 10px; border-radius: 5px; margin: 15px 0; text-align:center;">
            {{ session('status') }}
        </div>
    @endif

    @if($koleksiPakaian->count() > 0)
        <div class="clothing-grid">
            @foreach($koleksiPakaian as $item)
                {{-- Tambahkan anchor tag yang mengelilingi item atau bagian kontennya --}}
                <a href="{{ route('digital.wardrobe.show', $item->idPakaian) }}" class="clothing-item-link">
                    <div class="clothing-item {{ $item->visibility == 'Private' ? 'private-item' : '' }}">
                        <span class="visibility-badge {{ $item->visibility == 'Public' ? 'visibility-public' : 'visibility-private' }}">
                            {{ ucfirst($item->visibility) }}
                        </span>
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
                        <div class="clothing-item-name" title="{{ $item->nama }}">{{ $item->nama }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-wardrobe">
            <p>Your collection in '{{ ucfirst($selectedSection) }} / {{ ucfirst($selectedCategory) }}' is empty.</p>
            <p>
                <a href="{{ route('digital.wardrobe.create') }}" class="add-initial-item-btn">Add your first clothing item now!</a>
            </p>
        </div>
    @endif
</div>
@endsection