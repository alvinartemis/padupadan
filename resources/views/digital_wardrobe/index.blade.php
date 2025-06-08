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
        margin-bottom: 20px;
        text-align: center;
    }
    .wardrobe-title {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #0C2842;
    }

    /* --- Tombol Section --- */
    .section-tabs {
        margin-bottom: 25px;
        display: flex;
        gap: 0;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        max-width: 250px;
        margin-left: auto;
        margin-right: auto;
    }
    .section-tabs .btn {
        flex: 1;
        padding: 10px 20px;
        border: none;
        border-radius: 0;
        background-color: #fff;
        color: #555;
        font-weight: 500;
        text-decoration: none;
        font-size: 15px;
        transition: background-color 0.3s, color 0.3s;
        text-align: center; /* <<< PERUBAHAN DI SINI: Menambahkan text-align center */
    }
    .section-tabs .btn:not(:last-child) {
        border-right: 1px solid #ddd;
    }
    .section-tabs .btn.active {
        background-color: #173F63;
        color: white;
    }
    .section-tabs .btn:not(.active):hover {
        background-color: #f0f2f5;
    }

    /* --- Tombol Category --- */
    .category-filters {
        margin-bottom: 15px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .category-filters .btn {
        padding: 6px 18px;
        border: 1px solid #ddd;
        background-color: #f9f9f9;
        color: #333;
        text-decoration: none;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 400;
        transition: background-color 0.3s, color 0.3s, border-color 0.3s;
    }
    .category-filters .btn.active,
    .category-filters .btn:hover {
        background-color: #173F63;
        color: white;
        border-color: #173F63;
    }

    /* --- Tombol Aksi (Add, Delete) --- */
    .action-buttons-container {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
        margin-bottom: 25px;
    }
    .action-btn-icon {
        background-color: #f0f0f0;
        color: #555;
        padding: 6px;
        text-decoration: none;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #e0e0e0;
        transition: background-color 0.3s, color 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    .action-btn-icon:hover {
        background-color: #e0e0e0;
        color: #007bff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .action-btn-icon svg {
        width: 18px;
        height: 18px;
    }

    /* --- Card Pakaian --- */
    .clothing-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
    }
    .clothing-item-link {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.2s ease-out;
    }
    .clothing-item-link:hover {
        transform: translateY(-5px);
    }
    .clothing-item {
        border: none;
        border-radius: 12px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.08);
        overflow: hidden;
        padding: 0;
    }
    .clothing-item img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .clothing-item-info {
        padding: 12px 15px;
        text-align: center;
    }
    .clothing-item-name {
        font-weight: 600;
        font-size: 16px;
        color: #333;
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .clothing-item-visibility {
        font-size: 12px;
        font-weight: 500;
        padding: 2px 8px;
        border-radius: 10px;
        display: inline-block;
        text-transform: capitalize;
    }
    .visibility-public {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    .visibility-private {
        background-color: #f8d7da;
        color: #58151c;
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
                <a href="{{ route('digital.wardrobe.show', $item->idPakaian) }}" class="clothing-item-link">
                    <div class="clothing-item">
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
                        <div class="clothing-item-info">
                            <div class="clothing-item-name" title="{{ $item->nama }}">{{ $item->nama }}</div>
                            <span class="clothing-item-visibility {{ $item->visibility == 'Public' ? 'visibility-public' : 'visibility-private' }}">
                                {{ ucfirst($item->visibility) }}
                            </span>
                        </div>
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