@extends('layouts.stylist')

@section('title', 'Create Lookbook - Padu Padan')

@section('content')
    <style>
        /* Styles umum yang mungkin ada di layout atau di sini */
        .content-block {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 24px;
            min-height: calc(100vh - 40px); /* Adjust to fill height */
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center content horizontally */
        }

        .form-control-custom {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0; /* Default border color */
            border-radius: 0.5rem; /* More rounded */
            width: 100%;
            padding: 0.75rem 1rem; /* Adjust padding */
            color: #4a5568;
            line-height: 1.25;
            outline: none;
            transition: all 0.15s ease-in-out; /* Smooth transition */
            background-color: #f8f8f8; /* Light background for inputs */
        }
        .form-control-custom:focus {
            border-color: #f4bc43; /* Yellow border on focus */
            box-shadow: 0 0 0 3px rgba(244, 188, 67, 0.3); /* Yellow focus ring */
        }

        /* NEW STYLES for the layout based on the image */
        .create-lookbook-container {
            display: grid;
            grid-template-columns: 1fr 1.5fr; /* Preview 1 bagian, Form 1.5 bagian */
            gap: 40px; /* Jarak antar kolom */
            width: 100%;
            max-width: 900px; /* Lebar maksimal container */
            margin-top: 20px; /* Jarak dari judul */
        }

        .lookbook-preview-section {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Adjust main-preview-box to be the clickable area */
        .main-preview-box {
            width: 100%;
            height: 400px;
            background-color: #f0f2f5;
            border-radius: 8px;
            display: flex;
            flex-direction: column; /* To stack icon and text */
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 0;
            border: 1px solid #e0e0e0;
            position: relative;
            cursor: pointer; /* Indicate it's clickable */
            text-align: center;
        }

        .main-preview-box img#lookbookImagePreview {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: none; /* Hide image by default until file is chosen */
            position: static; /* Ensure it behaves normally within flexbox */
            transform: none; /* Remove any previous transforms */
        }

        .main-preview-box #previewPlaceholderContainer {
            /* This div contains the icon and text */
            position: absolute; /* Position over the image area */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex; /* Flex to center content */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: #b0b0b0;
            line-height: 1.4;
            pointer-events: none; /* Allow clicks to pass through to main-preview-box */
        }
        .main-preview-box #previewPlaceholderContainer img {
            max-width: 80px;
            height: auto;
            margin-bottom: 10px;
            display: block;
        }

        /* Hide the default file input styling */
        .hidden-file-input {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .form-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-section label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-section input[type="text"],
        .form-section textarea {
            background-color: #f8f8f8;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            width: 100%;
            box-sizing: border-box;
            outline: none;
            transition: all 0.15s ease-in-out;
        }

        .form-section input::placeholder,
        .form-section textarea::placeholder {
            color: #a0a0a0;
        }
        .form-section input:focus,
        .form-section textarea:focus {
            border-color: #f4bc43;
            box-shadow: 0 0 0 3px rgba(244, 188, 67, 0.3);
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .button-group button {
            padding: 10px 25px;
            border-radius: 9999px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1em;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .discard-button {
            background-color: #e0e0e0;
            color: #555;
            border: none;
        }
        .discard-button:hover {
            background-color: #d0d0d0;
        }

        .post-button {
            background-color: #f4bc43;
            color: #fff;
            border: none;
        }
        .post-button:hover {
            background-color: #e0a830;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .create-lookbook-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            .main-preview-box {
                height: 300px;
            }
            .button-group {
                justify-content: center;
            }
        }
    </style>

    <div class="content-block">
        <h2 class="font-extrabold text-[24px] text-black mb-6">Create Lookbook</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="createLookbookForm" action="{{ route('lookbook.store') }}" method="POST" enctype="multipart/form-data" class="create-lookbook-container">
            @csrf

            <div class="lookbook-preview-section">
                <h3 class="font-bold text-gray-700 mb-3">Lookbook Preview</h3>
                <div class="main-preview-box" id="mainPreviewBox">
                    <img id="lookbookImagePreview" src="" alt="Lookbook Preview">
                    <div id="previewPlaceholderContainer">
                        <span>Lookbook Preview</span>
                        <span>Click to Upload</span>
                    </div>
                </div>
                <input type="file" id="imgLookbook" name="imgLookbook" accept="image/*" class="hidden-file-input" required>
            </div>

            <div class="form-section">
                <h3 class="font-bold text-gray-700 mb-1">Title</h3>
                <input type="text" id="nama" name="nama" placeholder="Title" class="form-control-custom" required value="{{ old('nama') }}">

                <h3 class="font-bold text-gray-700 mb-1">Description (Optional)</h3>
                <textarea id="description" name="description" placeholder="Add a description about your collection" rows="4" class="form-control-custom">{{ old('description') }}</textarea>

                <h3 class="font-bold text-gray-700 mb-1">Tag</h3>
                <input type="text" id="kataKunci" name="kataKunci" placeholder="Search Tag" class="form-control-custom" value="{{ old('kataKunci') }}">
            </div>

            <div class="button-group col-span-full">
                <button type="button" class="discard-button">Discard</button>
                <button type="submit" class="post-button">Post</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        const imgLookbookInput = document.getElementById('imgLookbook');
        const mainPreviewBox = document.getElementById('mainPreviewBox');
        const lookbookImagePreview = document.getElementById('lookbookImagePreview');
        const previewPlaceholderContainer = document.getElementById('previewPlaceholderContainer');
        const createLookbookForm = document.getElementById('createLookbookForm');
        const discardButton = document.querySelector('.discard-button');

        // Function to update preview state
        function updatePreview(imageUrl = null) {
            if (imageUrl) {
                lookbookImagePreview.src = imageUrl;
                lookbookImagePreview.style.display = 'block'; // Show the image
                previewPlaceholderContainer.style.display = 'none'; // Hide placeholder
            } else {
                lookbookImagePreview.src = ''; // Clear image source
                lookbookImagePreview.style.display = 'none'; // Hide the image
                previewPlaceholderContainer.style.display = 'flex'; // Show placeholder
            }
        }

        // Initial state: show placeholder
        updatePreview(null);

        // Click handler for preview box to trigger file input
        mainPreviewBox.addEventListener('click', function() {
            imgLookbookInput.click(); // Trigger the hidden file input click
        });

        // Event listener for file input change
        imgLookbookInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    updatePreview(e.target.result); // Update preview with new image
                }
                reader.readAsDataURL(file);
            } else {
                // If no file selected (e.g., dialog cancelled), reset to placeholder
                updatePreview(null);
            }
        });

        // Discard button logic: clear form and reset preview, then redirect
        discardButton.addEventListener('click', function() {
            createLookbookForm.reset(); // Reset form fields
            updatePreview(null); // Reset image preview to placeholder
            // Redirect to stylist/lookbook page
            window.location.href = "{{ route('lookbook.index') }}"; // Redirect to the stylist's lookbook list
        });
    </script>
    @endpush
@endsection
