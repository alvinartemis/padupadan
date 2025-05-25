@extends('layouts.app') {{-- SESUAIKAN DENGAN PATH LAYOUT ANDA --}}

@section('title', 'Item Details: ' . $item->nama)

@section('content')
<style>
    /* ... (Salin semua CSS dari versi show.blade.php sebelumnya yang memiliki .custom-modal) ... */
    .detail-container { max-width: 800px; margin: 20px auto; padding: 25px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .success-message { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-size: 16px; }
    .detail-header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
    .detail-header h2 { font-size: 28px; color: #333; margin-bottom: 5px; }
    .detail-content { display: flex; flex-wrap: wrap; gap: 30px; }
    .detail-image-container { flex: 1 1 300px; max-width: 100%; text-align: center; }
    .detail-image-container img { max-width: 100%; max-height: 400px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover; }
    .detail-info { flex: 2 1 400px; }
    .detail-info .info-item { margin-bottom: 15px; font-size: 16px; }
    .detail-info .info-item strong { color: #555; display: inline-block; width: 100px; }
    .detail-info .info-item span { color: #333; }
    .detail-info .info-item a { color: #007bff; text-decoration: none; }
    .detail-info .info-item a:hover { text-decoration: underline; }
    .detail-actions { margin-top: 30px; display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #eee; }
    .detail-actions .right-actions { display: flex; gap: 10px; }
    .detail-btn, .action-btn-icon-detail { padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 16px; font-weight: 500; transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease; border: 1px solid transparent; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
    .btn-back { background-color: #6c757d; color: white; border-color: #6c757d; }
    .btn-back:hover { background-color: #5a6268; border-color: #545b62; }
    .btn-edit { background-color: #007bff; color: white; border-color: #007bff; }
    .btn-edit:hover { background-color: #0056b3; border-color: #0056b3; }
    .btn-delete-item { background-color: #dc3545; color: white; border-color: #dc3545; width: 44px; height: 44px; padding: 0; }
    .btn-delete-item:hover { background-color: #c82333; border-color: #bd2130;}
    .btn-delete-item svg { width: 20px; height: 20px; }

    /* Custom Modal Styles (SAMA seperti yang ada di create.blade.php) */
    .custom-modal {
        display: none; /* PENTING: Modal disembunyikan secara default */
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }
    .custom-modal-content { background-color: #fff; margin: auto; padding: 25px 30px; border: 1px solid #e0e0e0; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); position: relative; text-align: center; }
    .custom-modal-close { color: #aaa; float: right; font-size: 28px; font-weight: bold; position: absolute; top: 10px; right: 15px; cursor:pointer; }
    .custom-modal-close:hover, .custom-modal-close:focus { color: #333; text-decoration: none; }
    .custom-modal-content h3 { margin-top: 0; margin-bottom: 15px; font-size: 20px; color: #333; }
    .custom-modal-content p { margin-bottom: 25px; font-size: 15px; line-height: 1.6; color: #555; }
    .custom-modal-actions { display: flex; justify-content: flex-end; gap: 10px; }
    .custom-modal-btn { padding: 10px 18px; border-radius: 5px; border: none; font-size: 15px; font-weight: 500; cursor: pointer; transition: background-color 0.2s ease, box-shadow 0.2s ease; }
    .custom-modal-btn.btn-danger { background-color: #dc3545; color: white; }
    .custom-modal-btn.btn-danger:hover { background-color: #c82333; }
    .custom-modal-btn.btn-secondary { background-color: #6c757d; color: white; }
    .custom-modal-btn.btn-secondary:hover { background-color: #545b62; }
</style>

<div class="detail-container">

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="detail-header">
        <h2>{{ $item->nama }}</h2>
    </div>

    <div class="detail-content">
        <div class="detail-image-container">
            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}">
        </div>
        <div class="detail-info">
            <div class="info-item"><strong>Name:</strong> <span>{{ $item->nama }}</span></div>
            <div class="info-item"><strong>Section:</strong> <span>{{ ucfirst($item->section) }}</span></div>
            <div class="info-item"><strong>Category:</strong> <span>{{ ucfirst($item->category) }}</span></div>
            <div class="info-item"><strong>Visibility:</strong> <span>{{ ucfirst($item->visibility) }}</span></div>
            @if($item->linkItem)
            <div class="info-item"><strong>Link:</strong> <span><a href="{{ $item->linkItem }}" target="_blank" rel="noopener noreferrer">{{ $item->linkItem }}</a></span></div>
            @endif
        </div>
    </div>

    <div class="detail-actions">
        @php
            $backParams = [];
            if ($item->section === 'Outfits') {
                $backParams = ['section' => 'Outfits', 'category' => 'All'];
            }
        @endphp
        <a href="{{ route('digital.wardrobe.index', $backParams) }}" class="detail-btn btn-back">&laquo; Back to Wardrobe</a>

        <div class="right-actions">
            <a href="{{ route('digital.wardrobe.edit', $item->idPakaian) }}" class="detail-btn btn-edit">Edit Item</a>
            <button type="button" id="deleteItemBtn" class="action-btn-icon-detail btn-delete-item" title="Delete Item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.56 0c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- Form tersembunyi untuk Delete Item --}}
<form id="deleteItemForm" action="{{ route('digital.wardrobe.destroy', $item->idPakaian) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Modal Konfirmasi Hapus Item --}}
<div id="deleteItemConfirmationModal" class="custom-modal"> {{-- Pastikan display: none; di CSS --}}
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="closeDeleteItemModal">&times;</span>
        <h3>Delete Item</h3>
        {{-- Pesan akan diisi oleh JavaScript jika ingin dinamis, atau bisa statis --}}
        <p id="deleteItemModalMessage">Are you sure you want to delete the item "<strong>{{ $item->nama }}</strong>"? This action cannot be undone.</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelDeleteItemModalButton">Cancel</button>
            <button type="button" class="custom-modal-btn btn-danger" id="confirmDeleteItemModalButton">Yes, Delete</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('SHOW.BLADE.PHP SCRIPT: DOM Loaded.');

    const deleteItemBtn = document.getElementById('deleteItemBtn');
    const deleteItemForm = document.getElementById('deleteItemForm');

    // Elemen-elemen Modal Konfirmasi Hapus
    const deleteModal = document.getElementById('deleteItemConfirmationModal');
    const closeDeleteModalBtn = document.getElementById('closeDeleteItemModal');
    const cancelDeleteModalBtn = document.getElementById('cancelDeleteItemModalButton'); // ID diperbarui
    const confirmDeleteModalBtn = document.getElementById('confirmDeleteItemModalButton'); // ID diperbarui
    // const deleteItemModalMessage = document.getElementById('deleteItemModalMessage'); // Jika ingin mengubah pesan modal secara dinamis

    // Fungsi untuk menampilkan modal
    function showModal(modalElement) {
        if(modalElement) {
            modalElement.style.display = 'flex';
            console.log('SCRIPT: Modal shown:', modalElement.id);
        } else {
            console.error('SCRIPT ERROR: Attempted to show a null modal element.');
        }
    }
    // Fungsi untuk menyembunyikan modal
    function hideModal(modalElement) {
        if(modalElement) {
            modalElement.style.display = 'none';
            console.log('SCRIPT: Modal hidden:', modalElement.id);
        }
    }

    // Pastikan modal disembunyikan saat halaman dimuat
    if(deleteModal) {
        hideModal(deleteModal); // Panggil sekali untuk memastikan jika CSS belum sepenuhnya diterapkan saat JS berjalan
        console.log('SCRIPT: Initial hideModal called for deleteItemConfirmationModal.');
    } else {
        console.error('SCRIPT ERROR: deleteItemConfirmationModal HTML element not found.');
    }


    if (deleteItemBtn) {
        console.log('SCRIPT: Tombol Delete (id="deleteItemBtn") DITEMUKAN.');
        deleteItemBtn.addEventListener('click', function(event) {
            event.preventDefault();
            console.log('SCRIPT: Tombol Delete (id="deleteItemBtn") DIKLIK!');
            if(deleteModal) {
                // Jika Anda ingin nama item dinamis di modal, Anda bisa set di sini:
                // if(deleteItemModalMessage) deleteItemModalMessage.innerHTML = `Are you sure you want to delete "<strong>{{ addslashes($item->nama) }}</strong>"? This action cannot be undone.`;
                showModal(deleteModal);
            } else {
                // Fallback ke window.confirm jika modal tidak ditemukan (seharusnya tidak terjadi)
                console.error('SCRIPT ERROR: deleteModal is null when deleteItemBtn is clicked. Using window.confirm as fallback.');
                if (window.confirm("Are you sure you want to delete this item?")) {
                    if(deleteItemForm) deleteItemForm.submit();
                }
            }
        });
        console.log('SCRIPT: Event listener click TELAH DIPASANG ke tombol Delete.');
    } else {
        console.error('SCRIPT ERROR: Tombol Delete (id="deleteItemBtn") TIDAK DITEMUKAN!');
    }

    // Event listener untuk tombol "Yes, Delete" di dalam modal
    if (confirmDeleteModalBtn) {
        confirmDeleteModalBtn.addEventListener('click', function() {
            console.log('SCRIPT: "Yes, Delete" clicked in modal. Submitting deleteItemForm.');
            if(deleteItemForm) {
                deleteItemForm.submit();
            } else {
                console.error('SCRIPT ERROR: deleteItemForm not found for submission.');
            }
            hideModal(deleteModal); // Sembunyikan modal setelah aksi
        });
    } else {
        console.error('SCRIPT ERROR: confirmDeleteItemModalButton not found.');
    }

    // Event listener untuk tombol "Cancel" di dalam modal
    if (cancelDeleteModalBtn) {
        cancelDeleteModalBtn.addEventListener('click', function() {
            console.log('SCRIPT: "Cancel" clicked in modal.');
            hideModal(deleteModal);
        });
    } else {
        console.error('SCRIPT ERROR: cancelDeleteItemModalButton not found.');
    }

    // Event listener untuk tombol close (x) di modal
    if (closeDeleteModalBtn) {
        closeDeleteModalBtn.addEventListener('click', function() {
            console.log('SCRIPT: Modal close button (x) clicked.');
            hideModal(deleteModal);
        });
    } else {
        console.error('SCRIPT ERROR: closeDeleteItemModal not found.');
    }

    // Menutup modal jika user klik di luar konten modal
    window.addEventListener('click', function(event) {
        if (deleteModal && event.target == deleteModal) {
             console.log('SCRIPT: Click outside modal content detected.');
             hideModal(deleteModal);
        }
    });
});
</script>
@endpush