@extends('layouts.app') {{-- SESUAIKAN DENGAN PATH LAYOUT ANDA --}}

@section('title', $show_details_form ? 'Add Item Details' : 'Add New Item - Upload Photo')

@section('content')
<style>
    .upload-container { display: flex; flex-direction: column; align-items: center; padding: 20px; text-align: center; }
    .upload-container h2 { margin-bottom: 5px; }
    .upload-container p.subtitle { margin-bottom: 25px; font-size: 1em; color: #666; }

    .upload-box { border: 2px dashed #ddd; border-radius: 10px; padding: 50px 40px; width: 100%; max-width: 600px; min-height: 250px; background-color: #f9f9f9; cursor: pointer; transition: background-color 0.2s ease, border-color 0.2s ease; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .upload-box.dragover { background-color: #e9f5ff; border-color: #007bff; }
    .upload-box:hover:not(.dragover) { background-color: #f1f1f1; }
    .upload-box .upload-icon svg { width: 60px; height: 60px; color: #007bff; margin-bottom: 15px; }
    .upload-box h3 { margin-bottom: 10px; font-size: 20px; color: #333; }
    .upload-box p { margin-bottom: 20px; font-size: 14px; color: #777; }
    .select-photo-btn { background-color: #007bff; color: white; padding: 12px 30px; border-radius: 5px; border: none; font-size: 16px; cursor: pointer; transition: background-color 0.2s ease; }
    .select-photo-btn:hover { background-color: #0056b3; }

    #fileErrorPhoto { color: red; margin-top: 15px; font-size: 14px; font-weight: bold; min-height: 20px; text-align:center; width:100%;}
    .server-error-container { width:100%; max-width:600px; margin-bottom:15px;}
    .server-error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px 15px; border-radius: .25rem; width:100%; box-sizing: border-box;}
    .validation-errors ul { list-style-type: none; padding-left: 0; margin-bottom:0; }
    .validation-errors ul li { margin-bottom: .25rem; }

    #imagePreviewContainer, #finalImagePreviewContainer { margin-top: 20px; width: 100%; max-width: 250px; aspect-ratio: 1 / 1; border: 1px solid #ddd; padding: 5px; border-radius: 4px; background-color: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-left:auto; margin-right:auto;}
    #imagePreview, #finalImagePreview { max-width: 100%; max-height: 100%; display: block; border-radius: 2px; object-fit: cover; }

    .details-form-container { width:100%; max-width:600px; margin-top: 20px; text-align:left; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #333; }
    .form-group input[type="text"], .form-group input[type="url"], .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; }
    .form-group input:focus, .form-group select:focus { border-color: #007bff; outline: none; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
    .form-group .optional-label { font-size: 0.85em; color: #777; margin-left: 5px;}
    .form-group.has-error input, .form-group.has-error select { border-color: red; }

    .form-actions { margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px; }
    .submit-btn, .discard-btn-style { padding: 10px 20px; border-radius: 5px; border: none; font-size: 16px; font-weight:500; cursor: pointer; transition: background-color 0.3s ease, opacity 0.3s ease; } /* Ganti nama discard-btn ke discard-btn-style */
    .submit-btn { background-color: #28a745; color: white; }
    .submit-btn:hover:not(:disabled) { background-color: #218838; }
    .submit-btn:disabled { background-color: #aaa; cursor: not-allowed; opacity: 0.7; }
    .discard-btn-style { background-color: #6c757d; color: white; } /* Style untuk tombol Discard */
    .discard-btn-style:hover { background-color: #545b62; }

    .file-info { margin-top: 25px; font-size: 14px; color: #555; max-width: 600px; width: 100%; text-align: left; background-color: #f0f0f0; padding: 15px; border-radius: 8px; }
    .file-info ul { list-style: none; padding: 0; } .file-info ul li { margin-bottom: 10px; } .file-info ul li strong { color: #333; } .file-info .recommended { font-weight: bold; color: #198754; }

    /* Custom Modal Styles */
    .custom-modal {
        display: none; /* Sembunyikan secara default */
        position: fixed;
        z-index: 1050; /* Di atas elemen lain, di bawah navbar jika ada yang lebih tinggi */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
        display: flex; /* Defaultnya none, diubah JS jadi flex */
        align-items: center;
        justify-content: center;
    }
    .custom-modal-content {
        background-color: #fff;
        margin: auto;
        padding: 25px 30px;
        border: 1px solid #e0e0e0;
        width: 90%;
        max-width: 450px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        position: relative;
        text-align: center;
    }
    .custom-modal-close {
        color: #aaa;
        float: right; /* Pindahkan ke kanan atas */
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 15px;
    }
    .custom-modal-close:hover,
    .custom-modal-close:focus {
        color: #333;
        text-decoration: none;
        cursor: pointer;
    }
    .custom-modal-content h3 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 20px; /* Sesuaikan ukuran font judul modal */
        color: #333;
    }
    .custom-modal-content p {
        margin-bottom: 25px;
        font-size: 15px; /* Sesuaikan ukuran font pesan modal */
        line-height: 1.6;
        color: #555;
    }
    .custom-modal-actions {
        display: flex;
        justify-content: flex-end; /* Tombol rata kanan */
        gap: 10px;
    }
    .custom-modal-btn {
        padding: 10px 18px; /* Sedikit lebih kecil dari tombol form utama */
        border-radius: 5px;
        border: none;
        font-size: 15px; /* Sesuaikan */
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-modal-btn.btn-primary { background-color: #007bff; color: white; }
    .custom-modal-btn.btn-primary:hover { background-color: #0056b3; }
    .custom-modal-btn.btn-danger { background-color: #dc3545; color: white; }
    .custom-modal-btn.btn-danger:hover { background-color: #c82333; }
    .custom-modal-btn.btn-secondary { background-color: #6c757d; color: white; }
    .custom-modal-btn.btn-secondary:hover { background-color: #545b62; }

</style>

<div class="upload-container">

    @if ($show_details_form)
        {{-- TAHAP 2: FORM DETAIL ITEM --}}
        <h2>Add Item Details</h2>
        <p class="subtitle">Provide details for your uploaded item.</p>

        <div class="server-error-container">
            @if(session('error'))
                <div class="server-error">{{ session('error') }}</div>
            @endif
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

        <form action="{{ route('digital.wardrobe.storeDetails') }}" method="POST" class="details-form-container" id="detailsForm">
            @csrf
            <input type="hidden" name="uploaded_photo_path" id="uploaded_photo_path_details" value="{{ $uploaded_photo_path ?? '' }}">

            @if($uploaded_photo_url)
            <div id="finalImagePreviewContainer">
                <img id="finalImagePreview" src="{{ $uploaded_photo_url }}" alt="Uploaded Photo Preview" />
            </div>
            <p style="text-align:center; font-size:0.9em; color:#555; margin-top:5px; margin-bottom:20px;">Photo Selected</p>
            @else
             <p style="text-align:center; color:red; margin-bottom:20px;">⚠️ Photo preview is unavailable. Please re-upload.</p>
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
            <input type="hidden" name="uploaded_photo_path" id="uploaded_photo_path_discard" value="{{ $uploaded_photo_path ?? '' }}">
        </form>

    @else
        {{-- TAHAP 1: FORM UPLOAD FOTO --}}
        <h2>Add New Clothing Item</h2>
        <p class="subtitle">Start by uploading a photo of your item.</p>

        <div class="server-error-container">
             @if(session('error'))
                <div class="server-error">{{ session('error') }}</div>
            @endif
            @if($errors && $errors->has('photo'))
                 <div class="validation-errors server-error">
                    <ul>
                        @foreach ($errors->get('photo') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <form action="{{ route('digital.wardrobe.processPhoto') }}" method="POST" enctype="multipart/form-data" style="width:100%; max-width:600px; display: flex; flex-direction: column; align-items: center;">
            @csrf
            <div id="uploadBox" class="upload-box">
                <div class="upload-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                </div>
                <h3>Select photo to upload</h3>
                <p>Or drag and drop it here</p>
                <button type="button" id="selectPhotoButton" class="select-photo-btn">Select photo</button>
                <input type="file" name="photo" id="photoFile" accept=".jpg,.jpeg,.png" style="display: none;">
            </div>
            <div id="fileErrorPhoto"></div>

            <div id="imagePreviewContainer" style="display: none;">
                <img id="imagePreview" src="#" alt="Image Preview" />
            </div>

            <div class="file-info">
                <ul>
                    <li><strong>Size:</strong> Max 10MB</li>
                    <li><strong>File formats:</strong> <span class="recommended">.jpg, .jpeg, .png</span> Recommended</li>
                    <li><strong>Aspect ratios:</strong> Square or Portrait (example)</li>
                </ul>
            </div>

            <div class="form-actions" style="justify-content: center;">
                <button type="submit" id="submitPhotoButton" class="submit-btn">Next: Add Details</button>
            </div>
        </form>
    @endif
</div>

{{-- Modal Konfirmasi untuk Simpan --}}
<div id="saveConfirmationModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="closeSaveModal">&times;</span>
        <h3>Save Item?</h3>
        <p>Are you sure you want to save this item to your digital wardrobe?</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelSaveButton">Cancel</button>
            <button type="button" class="custom-modal-btn btn-primary" id="confirmSaveButton">Yes, Save</button>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi untuk Discard --}}
<div id="discardConfirmationModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="closeDiscardModal">&times;</span>
        <h3>Discard Changes?</h3>
        <p>Are you sure you want to discard all changes and the uploaded photo? This action cannot be undone.</p>
        <div class="custom-modal-actions">
            <button type="button" class="custom-modal-btn btn-secondary" id="cancelDiscardButton">No, Keep Editing</button>
            <button type="button" class="custom-modal-btn btn-danger" id="confirmDiscardButton">Yes, Discard</button>
        </div>
    </div>
</div>


<script>
    const categoriesData = {
        Items: ["Tops", "Bottoms", "Footwears", "Others"],
        Outfits: ["Casual", "Formal", "Party", "Others"]
    };

    const photoUploadSectionActive = {{ Js::from(!$show_details_form) }};
    const detailsFormSectionActive = {{ Js::from($show_details_form) }};
    const oldInputs = @json($old_input ?? old());

    if (photoUploadSectionActive) {
        const uploadBox = document.getElementById('uploadBox');
        const selectPhotoButton = document.getElementById('selectPhotoButton');
        const photoFile = document.getElementById('photoFile');
        const fileErrorPhoto = document.getElementById('fileErrorPhoto');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const submitPhotoButton = document.getElementById('submitPhotoButton');

        if(submitPhotoButton) submitPhotoButton.disabled = true;

        selectPhotoButton.addEventListener('click', function() { photoFile.click(); });
        uploadBox.addEventListener('click', function(e) {
            if (e.target !== selectPhotoButton && !selectPhotoButton.contains(e.target)) {
                photoFile.click();
            }
        });
        uploadBox.addEventListener('dragover', function(event) { event.preventDefault(); uploadBox.classList.add('dragover');});
        uploadBox.addEventListener('dragleave', function(event) { event.preventDefault(); uploadBox.classList.remove('dragover');});
        uploadBox.addEventListener('drop', function(event) {
            event.preventDefault();
            uploadBox.classList.remove('dragover');
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                photoFile.files = files;
                handlePhotoFileChange({ target: photoFile });
            }
        });
        photoFile.addEventListener('change', handlePhotoFileChange);

        function handlePhotoFileChange(event) {
            const file = event.target.files[0];
            fileErrorPhoto.textContent = '';
            imagePreviewContainer.style.display = 'none';
            imagePreview.src = '#';
            if(submitPhotoButton) submitPhotoButton.disabled = true;

            if (file) {
                const allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
                const maxFileSize = 10 * 1024 * 1024;

                if (!allowedExtensions.exec(file.name)) {
                    fileErrorPhoto.textContent = '⚠️ Invalid file format. Please upload JPG, JPEG, or PNG.';
                    event.target.value = ''; return;
                }
                if (file.size > maxFileSize) {
                    fileErrorPhoto.textContent = `⚠️ File is too large (${(file.size / 1024 / 1024).toFixed(2)} MB). Max 10MB.`;
                    event.target.value = ''; return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                    if(submitPhotoButton) submitPhotoButton.disabled = false;
                }
                reader.onerror = function() {
                    fileErrorPhoto.textContent = '⚠️ Could not read file.'; event.target.value = '';
                }
                reader.readAsDataURL(file);
            }
        }
    }

    if (detailsFormSectionActive) {
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
        
        const initialSection = oldInputs.section || sectionSelect.value || '';
        const initialCategory = oldInputs.category || '';

        function populateCategories(selectedSectionValue, currentCategoryValue) {
            categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
            if (selectedSectionValue && categoriesData[selectedSectionValue]) {
                categoriesData[selectedSectionValue].forEach(function(category) {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    if (category === currentCategoryValue) {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                });
                categorySelect.disabled = false;
            } else {
                categorySelect.disabled = true;
            }
        }

        if (sectionSelect) { // Pastikan elemen ada sebelum menambah event listener
            sectionSelect.addEventListener('change', function() {
                populateCategories(this.value, '');
            });
        }

        if (initialSection && sectionSelect) {
            if (sectionSelect.value !== initialSection) {
                 sectionSelect.value = initialSection;
            }
            populateCategories(initialSection, initialCategory);
        } else if (sectionSelect) {
             populateCategories(sectionSelect.value, '');
        }

        function showModal(modalElement) {
            if(modalElement) modalElement.style.display = 'flex';
        }
        function hideModal(modalElement) {
            if(modalElement) modalElement.style.display = 'none';
        }

        if (detailsForm) {
            detailsForm.addEventListener('submit', function(event) {
                event.preventDefault(); 
                showModal(saveModal);
            });
        }

        if (confirmSaveBtn) {
            confirmSaveBtn.addEventListener('click', function() {
                hideModal(saveModal);
                if(detailsForm) detailsForm.submit();
            });
        }
        if (cancelSaveBtn) {
            cancelSaveBtn.addEventListener('click', function() { hideModal(saveModal); });
        }
        if (closeSaveModalBtn) {
             closeSaveModalBtn.addEventListener('click', function() { hideModal(saveModal); });
        }

        if (discardButton) {
            discardButton.addEventListener('click', function(event) {
                event.preventDefault();
                showModal(discardModal);
            });
        }

        if (confirmDiscardBtn) {
            confirmDiscardBtn.addEventListener('click', function() {
                hideModal(discardModal);
                const discardPhotoPathInput = document.getElementById('uploaded_photo_path_discard');
                const currentPhotoPathInput = document.getElementById('uploaded_photo_path_details');
                if(discardPhotoPathInput && currentPhotoPathInput) {
                    discardPhotoPathInput.value = currentPhotoPathInput.value;
                }
                if(discardForm) discardForm.submit();
            });
        }
        if (cancelDiscardBtn) {
            cancelDiscardBtn.addEventListener('click', function() { hideModal(discardModal); });
        }
        if (closeDiscardModalBtn) {
            closeDiscardModalBtn.addEventListener('click', function() { hideModal(discardModal); });
        }

        window.addEventListener('click', function(event) {
            if (event.target == saveModal) { hideModal(saveModal); }
            if (event.target == discardModal) { hideModal(discardModal); }
        });
    }
</script>
@endsection