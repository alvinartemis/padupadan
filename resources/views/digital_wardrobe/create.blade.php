@extends('layouts.app') {{-- Menggunakan layout utama Anda --}}

@section('title', $show_details_form ? 'Add Item Details' : 'Add New Item')

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
        padding: 40px 20px; /* Padding lebih besar untuk ruang */
    }

    /* Kontainer utama untuk semua konten di halaman ini */
    .upload-container {
        display: flex;
        flex-direction: column;
        align-items: center; /* SEMUA KONTEN DI TENGAH */
        text-align: center;
        width: 100%;
        max-width: 800px;
    }
    .upload-container h2 { font-size: 24px; font-weight: 600; color: #333; margin: 0 0 5px 0; }
    .upload-container p.subtitle { font-size: 1em; color: #666; margin: 0 0 25px 0; }

    /* Area untuk upload file */
    .upload-box {
        border: 2px dashed #d1d1d1;
        border-radius: 12px; /* Lebih rounded */
        padding: 40px;
        cursor: pointer;
        transition: border-color 0.3s ease, background-color 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 700px; /* Sedikit lebih kecil agar tidak terlalu lebar */
        min-height: 300px;
    }
    /*.upload-box:hover, .upload-box.dragover { border-color: #007bff; background-color: #f7fcff; }*/
    .upload-icon { width: 60px; height: 60px; color: #888; margin-bottom: 15px; }
    .main-text { font-size: 1.2em; color: #333; margin: 0; font-weight: 600; }
    .sub-text { font-size: 0.9em; color: #888; margin-top: 5px; margin-bottom: 20px; }
    .select-photo-btn {
        background-color: #F4BC43;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 20px;
        font-size: 1em;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .select-photo-btn:hover { background-color: #173F63; }

    #previewContainer, #finalImagePreviewContainer {
        width: 100%;
        display: none;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    #imagePreview, #finalImagePreview {
        max-width: 100%;
        max-height: 250px; /* Ukuran preview diperkecil */
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #eee;
    }
    #fileErrorPhoto { color: red; margin-top: 15px; font-size: 14px; font-weight: bold; min-height: 20px; }

    /* Info Grid */
    .info-grid { width: 100%; max-width: 800px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px; }
    .info-box { background-color: #f0f2f5; border-radius: 10px; padding: 20px; text-align: center; }
    .info-box .info-icon { width: 40px; height: 40px; color: #666; margin-bottom: 10px; }
    .info-box h3 { font-size: 1.1em; color: #333; margin: 0 0 5px 0; font-weight: 600; }
    .info-box p { font-size: 0.85em; color: #888; margin: 0; line-height: 1.4; }

    /* Form Detail dan Tombol Aksi */
    .details-form-container { width:100%; max-width:800px; margin-top: 20px; text-align:left; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
    .form-group input, .form-group select {
        width: 100%;
        padding: 12px; /* Padding lebih nyaman */
        border: 1px solid #ccc;
        border-radius: 8px; /* Lebih rounded */
        box-sizing: border-box;
        font-size: 16px;
        font-family: 'Poppins', sans-serif
    }
    .form-group input:focus, .form-group select:focus { border-color: #173F63; outline: none; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
    .form-group.has-error input, .form-group.has-error select { border-color: red; }
    .form-group .optional-label { font-size: 0.85em; color: #777; margin-left: 5px;}
    
    .form-actions {
        width: 100%;
        max-width: 800px;
        text-align: center; /* <<< PERUBAHAN DI SINI: Tombol di tengah */
        margin-top: 40px;
    }
    .submit-btn, .discard-btn-style {
        padding: 12px 25px; /* Padding lebih besar */
        border-radius: 20px; /* Lebih rounded */
        border: none;
        font-size: 16px;
        font-weight: 600; /* Lebih tebal */
        cursor: pointer;
        transition: background-color 0.3s ease, opacity 0.3s ease;
    }
    .submit-btn { background-color: #F4BC43; color: white; }
    .submit-btn:hover:not(:disabled) { background-color: #173F63; }
    .submit-btn:disabled { background-color: #aaa; cursor: not-allowed; opacity: 0.7; }
    .discard-btn-style { background-color: #f0f2f5; color: #555; border: 1px solid #ddd; }
    .discard-btn-style:hover { background-color: #e2e8f0; }
    
    /* Pesan Error Server */
    .server-error-container { width:100%; max-width:600px; margin-bottom:15px; text-align: left;}
    .server-error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px 15px; border-radius: .25rem; width:100%; box-sizing: border-box;}
    .validation-errors ul { list-style-type: none; padding-left: 0; margin-bottom:0; }
    .validation-errors ul li { margin-bottom: .25rem; }
    
    /* Modal Styles */
    .custom-modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); align-items: center; justify-content: center; }
    .custom-modal-content { background-color: #fff; margin: auto; padding: 30px; border: none; width: 90%; max-width: 400px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.25); position: relative; text-align: center; }
    .custom-modal-close { display: none; } /* Tombol close (x) disembunyikan agar lebih rapi */
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

<div class="upload-container">

    @if ($show_details_form)
        {{-- TAHAP 2: FORM DETAIL ITEM --}}
        <h2>Add Item Details</h2>
        <p class="subtitle">Provide details for your uploaded item.</p>

        <form action="{{ route('digital.wardrobe.storeDetails') }}" method="POST" class="details-form-container" id="detailsForm">
            @csrf
            <input type="hidden" name="uploaded_photo_path" value="{{ $uploaded_photo_path ?? '' }}">

            @if($uploaded_photo_url)
                <div id="finalImagePreviewContainer" style="display: flex;">
                    <img id="finalImagePreview" src="{{ $uploaded_photo_url }}" alt="Uploaded Photo Preview" />
                </div>
            @endif

            <div class="form-group {{ $errors->has('nama') ? 'has-error' : '' }}">
                <label for="nama">Name <span style="color:red;">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ $old_input['nama'] ?? old('nama') }}" required>
            </div>
            <div class="form-group {{ $errors->has('linkItem') ? 'has-error' : '' }}">
                <label for="linkItem">Link Item <span class="optional-label">(Optional)</span></label>
                <input type="url" name="linkItem" id="linkItem" value="{{ $old_input['linkItem'] ?? old('linkItem') }}" placeholder="https://example.com/item">
            </div>
            <div class="form-group {{ $errors->has('section') ? 'has-error' : '' }}">
                <label for="sectionDetails">Section <span style="color:red;">*</span></label>
                <select name="section" id="sectionDetails" required>
                    <option value="">-- Select Section --</option>
                    <option value="Items" {{ ((isset($old_input['section']) && $old_input['section'] == 'Items') || old('section') == 'Items') ? 'selected' : '' }}>Items</option>
                    <option value="Outfits" {{ ((isset($old_input['section']) && $old_input['section'] == 'Outfits') || old('section') == 'Outfits') ? 'selected' : '' }}>Outfits</option>
                </select>
            </div>
            <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
                <label for="categoryDetails">Category <span style="color:red;">*</span></label>
                <select name="category" id="categoryDetails" required disabled>
                    <option value="">-- Select Section First --</option>
                </select>
            </div>
            <div class="form-group {{ $errors->has('visibility') ? 'has-error' : '' }}">
                <label for="visibility">Visibility <span style="color:red;">*</span></label>
                <select name="visibility" id="visibility" required>
                    <option value="">-- Select Visibility --</option>
                    <option value="Public" {{ ((isset($old_input['visibility']) && $old_input['visibility'] == 'Public') || old('visibility') == 'Public') ? 'selected' : '' }}>Public</option>
                    <option value="Private" {{ ((isset($old_input['visibility']) && $old_input['visibility'] == 'Private') || old('visibility') == 'Private') ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="discard-btn-style" id="discardDetailsButton">Discard</button>
                <button type="submit" class="submit-btn" id="saveItemButton">Save Item</button>
            </div>
        </form>

        <form action="{{ route('digital.wardrobe.discardTemporaryPhoto') }}" method="POST" id="discardForm" style="display: none;">
            @csrf
            <input type="hidden" name="uploaded_photo_path" value="{{ $uploaded_photo_path ?? '' }}">
        </form>

        {{-- HTML MODAL HANYA DIRENDER DI SINI --}}
        <div id="saveConfirmationModal" class="custom-modal">
            <div class="custom-modal-content">
                <h3>Save Item?</h3>
                <p>Are you sure you want to save this item?</p>
                <div class="custom-modal-actions">
                    <button type="button" class="custom-modal-btn btn-secondary" id="cancelSaveButton">Cancel</button>
                    <button type="button" class="custom-modal-btn btn-primary" id="confirmSaveButton">Yes, Save</button>
                </div>
            </div>
        </div>
        <div id="discardConfirmationModal" class="custom-modal">
            <div class="custom-modal-content">
                <h3>Discard Changes?</h3>
                <p>Are you sure you want to discard the uploaded photo?</p>
                <div class="custom-modal-actions">
                    <button type="button" class="custom-modal-btn btn-secondary" id="cancelDiscardButton">No, Keep Editing</button>
                    <button type="button" class="custom-modal-btn btn-danger" id="confirmDiscardButton">Yes, Discard</button>
                </div>
            </div>
        </div>

    @else
        {{-- TAHAP 1: FORM UPLOAD FOTO --}}
        <h2>Add New Clothing Item</h2>
        <p class="subtitle">Select a photo from your device to add to your digital wardrobe.</p>

        <form action="{{ route('digital.wardrobe.processPhoto') }}" method="POST" enctype="multipart/form-data" id="uploadForm" style="width:100%;">
            @csrf
            <div class="upload-box" id="uploadBox">
                <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg" style="display:none;">
                <div id="initialContent">
                    <svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                    <p class="main-text">Select photo to upload</p>
                    <p class="sub-text">Or drag and drop it here</p>
                    <button type="button" id="selectPhotoButton" class="select-photo-btn">Select photo</button>
                </div>
                <div id="previewContainer">
                    <img id="imagePreview" src="#" alt="Image Preview">
                </div>
            </div>
            <div id="fileErrorPhoto"></div>
            <div class="info-grid">
                <div class="info-box"><svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg><h3>Size</h3><p>Max file size: 10MB</p></div>
                <div class="info-box"><svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-2m-9 1H4a2 2 0 00-2 2v2a2 2 0 002 2h16a2 2 0 002-2v-2a2 2 0 00-2-2h-5l-4-6H9V4a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2" />
                    </svg><h3>File formats</h3><p>.jpg, .jpeg, .png</p></div>
                <div class="info-box"><svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M4 8h16M4 16h16" />
                    </svg><h3>Aspect ratios</h3><p>Recommended: 1:1 (Square) or 3:4</p></div>
            </div>
            <div class="form-actions">
                <button type="submit" id="submitPhotoButton" class="submit-btn" disabled>Next</button>
            </div>
        </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Definisi data dan variabel state
    const categoriesData = {
        Items: ["Tops", "Bottoms", "Footwears", "Others"],
        Outfits: ["Casual", "Formal", "Party", "Others"]
    };
    const isDetailsFormActive = {{ Js::from($show_details_form) }};
    const oldInputs = @json($old_input ?? old());

    // Fungsi untuk menampilkan dan menyembunyikan modal
    function showModal(modalElement) { if(modalElement) modalElement.style.display = 'flex'; }
    function hideModal(modalElement) { if(modalElement) modalElement.style.display = 'none'; }


    if (!isDetailsFormActive) {
        // --- LOGIKA HANYA UNTUK TAHAP UPLOAD FOTO ---
        const photoInput = document.getElementById('photoInput');
        const selectPhotoButton = document.getElementById('selectPhotoButton');
        const uploadBox = document.getElementById('uploadBox');
        const initialContent = document.getElementById('initialContent');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const submitPhotoButton = document.getElementById('submitPhotoButton');
        const fileErrorPhoto = document.getElementById('fileErrorPhoto');

        if (!photoInput || !selectPhotoButton || !uploadBox || !submitPhotoButton) {
            console.error('One or more critical elements for photo upload are missing!');
            return;
        }

        submitPhotoButton.disabled = true;

        function triggerFileInput() { photoInput.click(); }
        
        selectPhotoButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah event naik ke uploadBox
            triggerFileInput();
        });

        uploadBox.addEventListener('click', function() {
             // Hanya trigger jika preview belum terlihat
            if (previewContainer.style.display === 'none') {
                triggerFileInput();
            }
        });
        
        previewContainer.addEventListener('click', triggerFileInput);

        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            fileErrorPhoto.textContent = '';
            submitPhotoButton.disabled = true;

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'flex';
                    initialContent.style.display = 'none';
                    submitPhotoButton.disabled = false;
                };
                reader.onerror = function() { fileErrorPhoto.textContent = '⚠️ Could not read file.'; };
                reader.readAsDataURL(file);
            }
        });
        
        uploadBox.addEventListener('dragover', (e) => { e.preventDefault(); uploadBox.classList.add('dragover'); });
        uploadBox.addEventListener('dragleave', () => { uploadBox.classList.remove('dragover'); });
        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadBox.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) {
                photoInput.files = e.dataTransfer.files;
                photoInput.dispatchEvent(new Event('change'));
            }
        });

    } else {
        // --- LOGIKA HANYA UNTUK TAHAP FORM DETAIL ---
        const sectionSelect = document.getElementById('sectionDetails');
        const categorySelect = document.getElementById('categoryDetails');
        const detailsForm = document.getElementById('detailsForm');
        const discardButton = document.getElementById('discardDetailsButton');
        const discardForm = document.getElementById('discardForm');

        const saveModal = document.getElementById('saveConfirmationModal');
        const confirmSaveBtn = document.getElementById('confirmSaveButton');
        const cancelSaveBtn = document.getElementById('cancelSaveButton');
        const closeSaveModalBtn = document.getElementById('closeSaveModal');

        const discardModal = document.getElementById('discardConfirmationModal');
        const confirmDiscardBtn = document.getElementById('confirmDiscardButton');
        const cancelDiscardBtn = document.getElementById('cancelDiscardButton');
        const closeDiscardModalBtn = document.getElementById('closeDiscardModal');
        
        const initialSection = oldInputs.section || (sectionSelect ? sectionSelect.value : '') || '';
        const initialCategory = oldInputs.category || '';
        
        function populateCategories(selectedSectionValue, categoryToSelect) {
            if (!categorySelect) return;
            categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
            if (selectedSectionValue && categoriesData[selectedSectionValue]) {
                categoriesData[selectedSectionValue].forEach(function(category) {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    if (category === categoryToSelect) option.selected = true;
                    categorySelect.appendChild(option);
                });
                categorySelect.disabled = false;
            } else {
                categorySelect.disabled = true;
            }
        }

        if (sectionSelect) {
            sectionSelect.addEventListener('change', function() { populateCategories(this.value, ''); });
            if (initialSection) {
                if (sectionSelect.value !== initialSection) sectionSelect.value = initialSection;
                populateCategories(initialSection, initialCategory);
            }
        }

        if (detailsForm) {
            detailsForm.addEventListener('submit', function(event) {
                event.preventDefault(); showModal(saveModal);
            });
        }
        if (confirmSaveBtn) { confirmSaveBtn.addEventListener('click', function() { hideModal(saveModal); if(detailsForm) detailsForm.submit(); }); }
        if (cancelSaveBtn) cancelSaveBtn.addEventListener('click', function() { hideModal(saveModal); });
        if (closeSaveModalBtn) closeSaveModalBtn.addEventListener('click', function() { hideModal(saveModal); });

        if (discardButton) {
            discardButton.addEventListener('click', function(event) {
                event.preventDefault(); showModal(discardModal);
            });
        }
        if (confirmDiscardBtn) { confirmDiscardBtn.addEventListener('click', function() { hideModal(discardModal); if(discardForm) discardForm.submit(); }); }
        if (cancelDiscardBtn) cancelDiscardBtn.addEventListener('click', function() { hideModal(discardModal); });
        if (closeDiscardModalBtn) closeDiscardModalBtn.addEventListener('click', function() { hideModal(discardModal); });

        window.addEventListener('click', function(event) {
            if (saveModal && event.target == saveModal) { hideModal(saveModal); }
            if (discardModal && event.target == discardModal) { hideModal(discardModal); }
        });
    }
});
</script>
@endpush