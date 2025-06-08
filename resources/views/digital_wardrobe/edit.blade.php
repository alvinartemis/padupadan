@extends('layouts.app') {{-- Menggunakan layout utama Anda --}}

@section('title', 'Edit Item: ' . $item->nama)

@section('content')
<style>
    /* Menggunakan .content dari app.blade.php */
    .content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 40px 20px;
    }
    .form-container-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
        max-width: 800px; /* Lebar form disesuaikan */
    }
    .form-container-wrapper h2 { font-size: 24px; font-weight: 600; color: #0C2A42; margin: 0 0 5px 0; }
    .form-container-wrapper p.subtitle { font-size: 1em; color: #173F63; margin: 0 0 25px 0; }

    /* Form & Fields */
    .details-form-container { width:100%; text-align:left; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
    .form-group input, .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 20px;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
    }
    .form-group input:focus, .form-group select:focus { border-color: #173F63; outline: none; box-shadow: 0 0 0 0.2rem rgba(23, 63, 99, 0.15); }
    .form-group.has-error input, .form-group.has-error select { border-color: red; }
    .form-group .optional-label { font-size: 0.85em; color: #777; margin-left: 5px;}
    
    /* Tombol Aksi */
    .form-actions {
        width: 100%;
        max-width: 600px; /* Samakan dengan form container */
        text-align: center;
        margin-top: 30px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    .submit-btn, .discard-btn-style {
        padding: 12px 25px;
        border-radius: 20px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, opacity 0.3s ease;
    }
    .submit-btn { background-color: #F4BC43; color: white; }
    .submit-btn:hover:not(:disabled) { background-color: #173F63; }
    .discard-btn-style { background-color: #f0f2f5; color: #555; border: 1px solid #ddd; }
    .discard-btn-style:hover { background-color: #e2e8f0; }

    /* Preview Foto */
    #finalImagePreviewContainer { display: flex; justify-content: center; margin-bottom: 20px; }
    #finalImagePreview { max-width: 100%; max-height: 250px; object-fit: contain; border-radius: 8px; border: 1px solid #eee; }

    /* Pesan Error */
    .server-error-container { width:100%; max-width:600px; margin-bottom:15px; text-align: left;}
    .server-error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px 15px; border-radius: .25rem; width:100%; box-sizing: border-box;}
    .validation-errors ul { list-style-type: none; padding-left: 0; margin-bottom:0; }
    .validation-errors ul li { margin-bottom: .25rem; }

    /* Style untuk Modal */
    .custom-modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); align-items: center; justify-content: center; }
    .custom-modal-content { background-color: #fff; margin: auto; padding: 30px; border: none; width: 90%; max-width: 400px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.25); position: relative; text-align: center; }
    .custom-modal-content h3 { margin-top: 0; margin-bottom: 10px; font-size: 20px; color: #333; font-weight:600; }
    .custom-modal-content p { margin-bottom: 25px; font-size: 15px; line-height: 1.6; color: #555; }
    .custom-modal-actions { display: flex; justify-content: center; gap: 10px; }
    .custom-modal-btn { padding: 10px 18px; border-radius: 20px; border: 1px solid transparent; font-size: 15px; font-weight: 500; cursor: pointer; flex: 1; font-family: 'Poppins', sans-serif; }
    .custom-modal-btn.btn-primary { background-color: #F4BC43; color: white; }
    .custom-modal-btn.btn-primary:hover { background-color: #173F63; }
    .custom-modal-btn.btn-danger { background-color: #F4BC43; color: white; }
    .custom-modal-btn.btn-danger:hover { background-color: #173F63; }
    .custom-modal-btn.btn-secondary { background-color: #f0f2f5; color: #555; border-color: #ddd; }
    .custom-modal-btn.btn-secondary:hover { background-color: #e2e8f0; }
</style>

<div class="form-container-wrapper">
    <h2>Edit Item: {{ $item->nama }}</h2>
    <p class="subtitle">Update the details for your clothing item.</p>

    @if($errors && $errors->any())
        <div class="server-error-container">
            <div class="validation-errors server-error">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('digital.wardrobe.update', $item->idPakaian) }}" method="POST" class="details-form-container" id="editItemForm">
        @csrf
        @method('PUT')

        @if(isset($uploaded_photo_url))
        <div id="finalImagePreviewContainer">
            <img id="finalImagePreview" src="{{ $uploaded_photo_url }}" alt="{{ $item->nama }} Preview" />
        </div>
        @endif

        <div class="form-group {{ $errors->has('nama') ? 'has-error' : '' }}">
            <label for="nama">Name <span style="color:red;">*</span></label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $item->nama) }}" required>
        </div>

        <div class="form-group {{ $errors->has('linkItem') ? 'has-error' : '' }}">
            <label for="linkItem">Link Item <span class="optional-label">(Optional)</span></label>
            <input type="url" name="linkItem" id="linkItem" value="{{ old('linkItem', $item->linkItem) }}" placeholder="https://example.com/item">
        </div>

        <div class="form-group {{ $errors->has('section') ? 'has-error' : '' }}">
            <label for="sectionEdit">Section <span style="color:red;">*</span></label>
            <select name="section" id="sectionEdit" required>
                <option value="">-- Select Section --</option>
                <option value="Items" {{ old('section', $item->section) == 'Items' ? 'selected' : '' }}>Items</option>
                <option value="Outfits" {{ old('section', $item->section) == 'Outfits' ? 'selected' : '' }}>Outfits</option>
            </select>
        </div>

        <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
            <label for="categoryEdit">Category <span style="color:red;">*</span></label>
            <select name="category" id="categoryEdit" required {{ old('section', $item->section) ? '' : 'disabled' }}>
                <option value="">-- Select Section First --</option>
            </select>
        </div>

        <div class="form-group {{ $errors->has('visibility') ? 'has-error' : '' }}">
            <label for="visibility">Visibility <span style="color:red;">*</span></label>
            <select name="visibility" id="visibility" required>
                <option value="">-- Select Visibility --</option>
                <option value="Public" {{ old('visibility', $item->visibility) == 'Public' ? 'selected' : '' }}>Public</option>
                <option value="Private" {{ old('visibility', $item->visibility) == 'Private' ? 'selected' : '' }}>Private</option>
            </select>
        </div>
    </form>
    {{-- Tombol dipindahkan ke luar form detail agar tidak ter-submit otomatis --}}
    <div class="form-actions">
        <button type="button" class="discard-btn-style" id="discardEditButton">Discard</button>
        <button type="button" class="submit-btn" id="updateItemButton">Save Changes</button>
    </div>
</div>

{{-- Modal Konfirmasi untuk Update/Save --}}
<div id="updateConfirmModal" class="custom-modal">
    <div class="custom-modal-content">
        <h3>Update Item?</h3>
        <p>Are you sure you want to save these changes?</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelUpdateButton">Cancel</button>
            <button type="button" class="custom-modal-btn btn-primary" id="confirmUpdateButton">Yes, Update</button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi untuk Discard Edit --}}
<div id="discardEditConfirmationModal" class="custom-modal">
    <div class="custom-modal-content">
        <h3>Discard Changes?</h3>
        <p>Are you sure you want to discard your changes? This action cannot be undone.</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelDiscardEditButton">No, Keep Editing</button>
            <button type="button" class="custom-modal-btn btn-danger" id="confirmDiscardEditButton">Yes, Discard</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data untuk kategori
    const categoriesData = {
        Items: ["Tops", "Bottoms", "Footwears", "Others"],
        Outfits: ["Casual", "Formal", "Party", "Others"]
    };

    const sectionSelect = document.getElementById('sectionEdit');
    const categorySelect = document.getElementById('categoryEdit');
    const editItemForm = document.getElementById('editItemForm');
    const discardEditButton = document.getElementById('discardEditButton');
    const updateItemButton = document.getElementById('updateItemButton'); // Tombol Save/Update

    // Modal untuk Update
    const updateConfirmModal = document.getElementById('updateConfirmModal');
    const confirmUpdateBtn = document.getElementById('confirmUpdateButton');
    const cancelUpdateBtn = document.getElementById('cancelUpdateButton');

    // Modal untuk Discard Edit
    const discardEditModal = document.getElementById('discardEditConfirmationModal');
    const confirmDiscardEditBtn = document.getElementById('confirmDiscardEditButton');
    const cancelDiscardEditBtn = document.getElementById('cancelDiscardEditButton');

    // Mengambil nilai section dan category awal dari Blade
    const initialSection = sectionSelect ? sectionSelect.value : "{{ old('section', $item->section) }}";
    const initialCategory = "{{ old('category', $item->category) }}";

    function populateCategories(selectedSectionValue, categoryToSelect) {
        if (!categorySelect) return;
        categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
        
        if (selectedSectionValue && categoriesData[selectedSectionValue]) {
            categoriesData[selectedSectionValue].forEach(function(category) {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                if (category === categoryToSelect) {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });
            categorySelect.disabled = false;
        } else {
            categorySelect.disabled = true;
        }
    }

    if (sectionSelect) {
        sectionSelect.addEventListener('change', function() {
            populateCategories(this.value, '');
        });
        populateCategories(initialSection, initialCategory);
    }

    function showModal(modalElement) { if(modalElement) modalElement.style.display = 'flex'; }
    function hideModal(modalElement) { if(modalElement) modalElement.style.display = 'none'; }

    // Event listener untuk tombol update
    if (updateItemButton) {
        updateItemButton.addEventListener('click', function(event) {
            event.preventDefault();
            showModal(updateConfirmModal);
        });
    }

    // Tombol di dalam modal update
    if (confirmUpdateBtn) {
        confirmUpdateBtn.addEventListener('click', function() {
            hideModal(updateConfirmModal);
            if(editItemForm) editItemForm.submit();
        });
    }
    if (cancelUpdateBtn) {
        cancelUpdateBtn.addEventListener('click', function() { hideModal(updateConfirmModal); });
    }

    // Tombol Discard Edit
    if (discardEditButton) {
        discardEditButton.addEventListener('click', function(event) {
            event.preventDefault();
            showModal(discardEditModal);
        });
    }

    // Tombol di dalam modal discard edit
    if (confirmDiscardEditBtn) {
        confirmDiscardEditBtn.addEventListener('click', function() {
            hideModal(discardEditModal);
            window.location.href = "{{ route('digital.wardrobe.show', $item->idPakaian) }}";
        });
    }
    if (cancelDiscardEditBtn) {
        cancelDiscardEditBtn.addEventListener('click', function() { hideModal(discardEditModal); });
    }

    // Menutup modal jika user klik di luar konten modal
    window.addEventListener('click', function(event) {
        if (event.target == updateConfirmModal) { hideModal(updateConfirmModal); }
        if (event.target == discardEditModal) { hideModal(discardEditModal); }
    });
});
</script>
@endpush
