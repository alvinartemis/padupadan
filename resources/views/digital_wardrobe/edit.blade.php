@extends('layouts.app') {{-- SESUAIKAN DENGAN PATH LAYOUT ANDA --}}

@section('title', 'Edit Item: ' . $item->nama)

@section('content')
<style>
    /* Salin semua style dari create.blade.php ATAU idealnya pindahkan ke file CSS terpusat */
    /* Untuk contoh ini, saya akan salin sebagian style yang relevan */
    .upload-container { display: flex; flex-direction: column; align-items: center; padding: 20px; text-align: center; }
    .upload-container h2 { margin-bottom: 5px; }
    .upload-container p.subtitle { margin-bottom: 25px; font-size: 1em; color: #666; }

    #finalImagePreviewContainer { margin-top: 20px; width: 100%; max-width: 250px; aspect-ratio: 1 / 1; border: 1px solid #ddd; padding: 5px; border-radius: 4px; background-color: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-left:auto; margin-right:auto;}
    #finalImagePreview { max-width: 100%; max-height: 100%; display: block; border-radius: 2px; object-fit: cover; }

    .details-form-container { width:100%; max-width:600px; margin-top: 20px; text-align:left; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
    .form-group input[type="text"], .form-group input[type="url"], .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; }
    .form-group input:focus, .form-group select:focus { border-color: #007bff; outline: none; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
    .form-group .optional-label { font-size: 0.85em; color: #777; margin-left: 5px;}
    .form-group.has-error input, .form-group.has-error select { border-color: red; }

    .form-actions { margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px; }
    .submit-btn, .discard-btn-style { padding: 10px 20px; border-radius: 5px; border: none; font-size: 16px; font-weight:500; cursor: pointer; transition: background-color 0.3s ease, opacity 0.3s ease; }
    .submit-btn { background-color: #198754; color: white; } /* Warna update/save */
    .submit-btn:hover:not(:disabled) { background-color: #157347; }
    .discard-btn-style { background-color: #6c757d; color: white; }
    .discard-btn-style:hover { background-color: #5a6268; }

    .server-error-container { width:100%; max-width:600px; margin-bottom:15px;}
    .server-error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px 15px; border-radius: .25rem; width:100%; box-sizing: border-box;}
    .validation-errors ul { list-style-type: none; padding-left: 0; margin-bottom:0; }
    .validation-errors ul li { margin-bottom: .25rem; }

    /* Custom Modal Styles (sama seperti di create.blade.php) */
    .custom-modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; }
    .custom-modal-content { background-color: #fff; margin: auto; padding: 25px 30px; border: 1px solid #e0e0e0; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); position: relative; text-align: center; }
    .custom-modal-close { color: #aaa; float: right; font-size: 28px; font-weight: bold; position: absolute; top: 10px; right: 15px; }
    .custom-modal-close:hover, .custom-modal-close:focus { color: #333; text-decoration: none; cursor: pointer; }
    .custom-modal-content h3 { margin-top: 0; margin-bottom: 15px; font-size: 20px; color: #333; }
    .custom-modal-content p { margin-bottom: 25px; font-size: 15px; line-height: 1.6; color: #555; }
    .custom-modal-actions { display: flex; justify-content: flex-end; gap: 10px; }
    .custom-modal-btn { padding: 10px 18px; border-radius: 5px; border: none; font-size: 15px; font-weight: 500; cursor: pointer; transition: background-color 0.2s ease, box-shadow 0.2s ease; }
    .custom-modal-btn.btn-primary { background-color: #007bff; color: white; }
    .custom-modal-btn.btn-primary:hover { background-color: #0056b3; }
    .custom-modal-btn.btn-danger { background-color: #dc3545; color: white; }
    .custom-modal-btn.btn-danger:hover { background-color: #c82333; }
    .custom-modal-btn.btn-secondary { background-color: #6c757d; color: white; }
    .custom-modal-btn.btn-secondary:hover { background-color: #545b62; }
</style>

<div class="upload-container">
    <h2>Edit Item: {{ $item->nama }}</h2>
    <p class="subtitle">Update the details for your clothing item.</p>

    <div class="server-error-container">
        @if($errors && $errors->any())
            <div class="validation-errors server-error">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <form action="{{ route('digital.wardrobe.update', $item->idPakaian) }}" method="POST" class="details-form-container" id="editItemForm">
        @csrf
        @method('PUT') {{-- Method spoofing untuk request PUT --}}

        @if($uploaded_photo_url)
        <div id="finalImagePreviewContainer">
            <img id="finalImagePreview" src="{{ $uploaded_photo_url }}" alt="{{ $item->nama }} Preview" />
        </div>
        <p style="text-align:center; font-size:0.9em; color:#555; margin-top:5px; margin-bottom:20px;">Current Photo</p>
        {{-- Tambahkan input file untuk mengubah foto jika diperlukan di masa mendatang --}}
        {{--
        <div class="form-group">
            <label for="new_photo">Change Photo <span class="optional-label">(Optional)</span></label>
            <input type="file" name="new_photo" id="new_photo" class="form-control-file" accept=".jpg,.jpeg,.png">
             <small id="photoHelp" class="form-text text-muted">Max 10MB. JPG, JPEG, PNG. Leave empty to keep current photo.</small>
        </div>
        --}}
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
                <option value="Public" {{ old('visibility', $item->visibility) == 'Public' ? 'selected' : '' }}>Public</option>
                <option value="Private" {{ old('visibility', $item->visibility) == 'Private' ? 'selected' : '' }}>Private</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="button" class="discard-btn-style" id="discardEditButton">Discard</button>
            <button type="submit" class="submit-btn" id="updateItemButton">Save Changes</button>
        </div>
    </form>
</div>

{{-- Modal Konfirmasi untuk Update/Save --}}
<div id="updateConfirmModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="closeUpdateModal">&times;</span>
        <h3>Update Item?</h3>
        <p>Are you sure you want to save these changes?</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelUpdateButton">Cancel</button>
            <button type="button" class="custom-modal-btn btn-primary" id="confirmUpdateButton">Yes, Update</button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi untuk Discard Edit --}}
<div id="discardEditConfirmationModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="closeDiscardEditModal">&times;</span>
        <h3>Discard Changes?</h3>
        <p>Are you sure you want to discard your changes? This action cannot be undone.</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelDiscardEditButton">No, Keep Editing</button>
            <button type="button" class="custom-modal-btn btn-danger" id="confirmDiscardEditButton">Yes, Discard</button>
        </div>
    </div>
</div>

<script>
    const categoriesData = {
        Items: ["Tops", "Bottoms", "Footwears", "Others"],
        Outfits: ["Casual", "Formal", "Party", "Others"]
    };

    const sectionSelectEdit = document.getElementById('sectionEdit');
    const categorySelectEdit = document.getElementById('categoryEdit');
    const editItemForm = document.getElementById('editItemForm'); // Form utama
    const discardEditButton = document.getElementById('discardEditButton');

    // Modal untuk Update
    const updateConfirmModal = document.getElementById('updateConfirmModal');
    const confirmUpdateBtn = document.getElementById('confirmUpdateButton');
    const cancelUpdateBtn = document.getElementById('cancelUpdateButton');
    const closeUpdateModalBtn = document.getElementById('closeUpdateModal');

    // Modal untuk Discard Edit
    const discardEditModal = document.getElementById('discardEditConfirmationModal');
    const confirmDiscardEditBtn = document.getElementById('confirmDiscardEditButton');
    const cancelDiscardEditBtn = document.getElementById('cancelDiscardEditButton');
    const closeDiscardEditModalBtn = document.getElementById('closeDiscardEditModal');

    // Mengambil nilai section dan category awal dari Blade (sudah di-render dengan old() atau $item)
    const initialEditSection = sectionSelectEdit.value;
    const initialEditCategory = "{{ old('category', $item->category) }}";

    function populateCategoriesEdit(selectedSectionValue, currentCategoryValue) {
        categorySelectEdit.innerHTML = '<option value="">-- Select Category --</option>'; // Reset
        if (selectedSectionValue && categoriesData[selectedSectionValue]) {
            categoriesData[selectedSectionValue].forEach(function(category) {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                if (category === currentCategoryValue) {
                    option.selected = true;
                }
                categorySelectEdit.appendChild(option);
            });
            categorySelectEdit.disabled = false;
        } else {
            categorySelectEdit.disabled = true;
        }
    }

    if (sectionSelectEdit) {
        sectionSelectEdit.addEventListener('change', function() {
            populateCategoriesEdit(this.value, ''); // Saat section berubah, category belum dipilih
        });
        // Populate categories on page load
        populateCategoriesEdit(initialEditSection, initialEditCategory);
    }


    function showModal(modalElement) {
        if(modalElement) modalElement.style.display = 'flex';
    }
    function hideModal(modalElement) {
        if(modalElement) modalElement.style.display = 'none';
    }

    // Event listener untuk form update
    if (editItemForm) {
        editItemForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Selalu cegah submit default
            showModal(updateConfirmModal);
        });
    }

    // Tombol di dalam modal update
    if (confirmUpdateBtn) {
        confirmUpdateBtn.addEventListener('click', function() {
            hideModal(updateConfirmModal);
            if(editItemForm) editItemForm.submit(); // Lanjutkan submit form
        });
    }
    if (cancelUpdateBtn) {
        cancelUpdateBtn.addEventListener('click', function() { hideModal(updateConfirmModal); });
    }
    if (closeUpdateModalBtn) {
        closeUpdateModalBtn.addEventListener('click', function() { hideModal(updateConfirmModal); });
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
            // Arahkan kembali ke halaman detail item atau index
            window.location.href = "{{ route('digital.wardrobe.show', $item->idPakaian) }}";
        });
    }
    if (cancelDiscardEditBtn) {
        cancelDiscardEditBtn.addEventListener('click', function() { hideModal(discardEditModal); });
    }
    if (closeDiscardEditModalBtn) {
        closeDiscardEditModalBtn.addEventListener('click', function() { hideModal(discardEditModal); });
    }

    // Menutup modal jika user klik di luar konten modal
    window.addEventListener('click', function(event) {
        if (event.target == updateConfirmModal) { hideModal(updateConfirmModal); }
        if (event.target == discardEditModal) { hideModal(discardEditModal); }
    });

</script>
@endsection