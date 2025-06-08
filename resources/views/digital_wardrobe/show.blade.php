@extends('layouts.app') {{-- Menggunakan layout utama Anda --}}

@section('title', 'Item Details: ' . $item->nama)

@section('content')
<style>
    /* Menggunakan .content dari app.blade.php sebagai container utama */
    .content {
        padding: 30px 40px;
        position: relative;
    }

    /* Header Halaman (Tombol Back, Judul, Tombol Aksi) */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        width: 100%;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
        position: relative;
    }
    .page-header h2 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin: 0;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        color: #0C2A42;
    }
    .page-header .action-icon-btn, .page-header .back-icon-btn {
        background-color: #f0f2f5;
        color: #555;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background-color 0.2s, color 0.2s, box-shadow 0.2s;
        border: none;
        cursor: pointer;
    }
    .page-header .action-icon-btn:hover, .page-header .back-icon-btn:hover {
        background-color: #e0e2e5;
        color: #007bff;
    }
    .page-header .header-actions {
        display: flex;
        gap: 10px; /* Jarak antara tombol Edit dan Delete */
    }
    .page-header .btn-delete-item {
        color: #dc3545; /* Warna merah untuk ikon delete */
    }
    /*.page-header .btn-delete-item:hover {
        background-color: #f8d7da;
    }*/
    .page-header svg {
        width: 20px;
        height: 20px;
    }

    /* --- Tata Letak Dua Kolom --- */
    .detail-main-content {
        display: flex;
        flex-wrap: wrap; /* Agar responsif di layar kecil */
        gap: 40px; /* Jarak antara gambar dan info */
        align-items: flex-start; /* Sejajarkan dari atas */
        width: 100%;
        margin-top: 20px;
        justify-content: center;
    }
    .detail-image-container {
        flex-basis: 300px; /* Lebar dasar untuk gambar */
        flex-shrink: 0; /* Jangan biarkan gambar menyusut */
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        /* Background abu-abu dihapus */
    }
    .detail-image-container img {
        max-width: 100%;
        max-height: 500px;
        border-radius: 8px;
        object-fit: contain; /* Memastikan foto tidak terpotong */
        border: 1px solid #eee; /* Beri border tipis */
    }
    .detail-info {
        flex-basis: 500px; /* Lebar dasar untuk info */
        flex-grow: 1; /* Biarkan bisa membesar jika ada ruang */
        text-align: left;
        color: #0C2A42; 
    }
    .detail-info h1.item-name { /* Nama item sebagai judul utama */
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-top: 0;
        margin-bottom: 25px;
        line-height: 1.2;
    }
    .detail-info .info-item {
        display: flex;
        justify-content: space-between;
        padding: 15px 10px;
        border-bottom: 1px solid #f0f2f5;
        font-size: 1rem;
        color: #0C2A42;
    }
    .detail-info .info-item:first-of-type {
        border-top: 1px solid #f0f2f5;
    }
    .detail-info .info-item strong {
        color: #173F63;;
        font-weight: 500;
    }
    .detail-info .info-item span,
    .detail-info .info-item a {
        color: #0C2A42;;
        font-weight: 600;
        text-align: right;
    }
    .detail-info .info-item a {
        color: #F4BC43;
        text-decoration: underline;
        word-break: break-all;
    }

    /* Notifikasi Sukses */
    .success-message {
        width: 100%;
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 16px;
    }

    /* --- Style untuk Modal Konfirmasi --- */
    .custom-modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif;}
    .custom-modal-content { background-color: #fff; margin: auto; padding: 30px; border: none; width: 90%; max-width: 400px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.25); position: relative; text-align: center; }
    .custom-modal-content h3 { margin-top: 0; margin-bottom: 10px; font-size: 20px; color: #333; font-weight:600; }
    .custom-modal-content p { margin-bottom: 25px; font-size: 15px; line-height: 1.6; color: #555; }
    .custom-modal-actions { display: flex; justify-content: center; gap: 10px; }
    .custom-modal-btn { padding: 10px 18px; border-radius: 8px; border: 1px solid transparent; font-size: 15px; font-weight: 500; cursor: pointer; flex: 1; font-family: 'Poppins', sans-serif;}
    .custom-modal-btn.btn-danger { background-color: #F4BC43; color: white; }
    .custom-modal-btn.btn-danger:hover { background-color: #173F63; }
    .custom-modal-btn.btn-secondary { background-color: #f0f2f5; color: #555; border-color: #ddd; }
    .custom-modal-btn.btn-secondary:hover { background-color: #e2e8f0; }
</style>

@if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
@endif

<div class="page-header">
    @php
        $backParams = [];
        if ($item->section === 'Outfits') {
            $backParams = ['section' => 'Outfits', 'category' => 'All'];
        }
    @endphp
    <a href="{{ route('digital.wardrobe.index', $backParams) }}" class="back-icon-btn" title="Back to Wardrobe">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
    </a>
    <h2>Detail Collection</h2>
    <div class="header-actions">
        <a href="{{ route('digital.wardrobe.edit', $item->idPakaian) }}" class="action-icon-btn" title="Edit Item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
        </a>
        <button type="button" id="deleteItemBtn" class="action-icon-btn btn-delete-item" title="Delete Item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.56 0c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
        </button>
    </div>
</div>

<div class="detail-main-content">
    <div class="detail-image-container">
        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
    </div>
    <div class="detail-info">
        <div class="info-item">
            <strong>Name:</strong>
            <span>{{ ucfirst($item->nama) }}</span>
        </div>
        <div class="info-item">
            <strong>Section:</strong>
            <span>{{ ucfirst($item->section) }}</span>
        </div>
        <div class="info-item">
            <strong>Category:</strong>
            <span>{{ ucfirst($item->category) }}</span>
        </div>
        <div class="info-item">
            <strong>Visibility:</strong>
            <span>{{ ucfirst($item->visibility) }}</span>
        </div>
        @if($item->linkItem)
        <div class="info-item">
            <strong>Link:</strong>
            <span><a href="{{ $item->linkItem }}" target="_blank" rel="noopener noreferrer">View Item Link</a></span>
        </div>
        @endif
    </div>
</div>

{{-- Form tersembunyi dan Modal Konfirmasi --}}
<form id="deleteItemForm" action="{{ route('digital.wardrobe.destroy', $item->idPakaian) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<div id="deleteItemConfirmationModal" class="custom-modal">
    <div class="custom-modal-content">
        <h3>Delete Item</h3>
        <p>Are you sure you want to delete "<strong>{{ $item->nama }}</strong>"? This action cannot be undone.</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelDeleteItemButton">Cancel</button>
            <button type="button" class="custom-modal-btn btn-danger" id="confirmDeleteItemButton">Yes, Delete</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteItemBtn = document.getElementById('deleteItemBtn');
    const deleteItemForm = document.getElementById('deleteItemForm');
    const deleteModal = document.getElementById('deleteItemConfirmationModal');

    // Pastikan semua elemen ada sebelum memasang event listener
    if (deleteModal && deleteItemBtn && deleteItemForm) {
        const cancelBtn = document.getElementById('cancelDeleteItemButton');
        const confirmBtn = document.getElementById('confirmDeleteItemButton');
        const closeBtn = deleteModal.querySelector('.custom-modal-close'); // Jika Anda ingin menambahkannya lagi

        function showModal() { deleteModal.style.display = 'flex'; }
        function hideModal() { deleteModal.style.display = 'none'; }
        
        // Pastikan modal tersembunyi saat awal
        hideModal();

        // Event listeners
        deleteItemBtn.addEventListener('click', showModal);
        cancelBtn.addEventListener('click', hideModal);
        // if(closeBtn) closeBtn.addEventListener('click', hideModal); // Jika tombol close (x) ada
        
        confirmBtn.addEventListener('click', function() {
            deleteItemForm.submit();
        });

        // Menutup modal jika user klik di luar area konten modal
        window.addEventListener('click', function(event) {
            if (event.target == deleteModal) {
                 hideModal();
            }
        });
    }
});
</script>
@endpush
