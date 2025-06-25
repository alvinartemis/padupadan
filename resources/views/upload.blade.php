<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Content - Padu Padan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
            align-items: flex-start;
        }

        .sidebar .logo {
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            height: 40px;
            width: auto;
        }

        .sidebar .search-box {
            display: flex;
            align-items: center;
            background-color: #e0e0e0;
            border-radius: 20px;
            padding: 8px 15px;
            margin-bottom: 30px;
            width: 100%;
            box-sizing: border-box;
        }

        .sidebar .search-box input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 14px;
            margin-left: 10px;
        }

        .sidebar .search-box svg {
            width: 18px;
            height: 18px;
            color: #A3A3A3;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .sidebar nav ul li {
            margin-bottom: 15px;
        }

        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #A3A3A3;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            color: #173F63;
            /background-color: #e0f7fa;
        }

        .sidebar nav ul li a svg {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            color: #888;
        }

        .sidebar nav ul li a.active svg {
            color: #173F63;
        }

        /* Styles for the Upload Content Area */
        .upload-content-area {
            flex-grow: 1;
            padding: 20px;
            border-radius: 5px;
            margin-left: 250px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .upload-content-area h2 {
            display: none;
        }

        .upload-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 1100px; /* Lebar maksimal diperbesar, tapi tetap ada batasan */
            text-align: center;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 20px; /* Untuk jarak antar elemen di dalam card */
        }

        .upload-card form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-box {
            border: 2px dashed #d1d1d1;
            border-radius: 10px;
            padding: 40px 20px;
            cursor: pointer;
            transition: border-color 0.3s ease, background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: calc(100% - 80px); /* Mengurangi lebar dari 100% untuk padding internal */
            max-width: 800px; /* Batasi lebar maksimal upload-box di dalam card */
            height: auto;
            min-height: 250px;
            margin-bottom: 0;
            position: relative;
        }

        .upload-box:hover {
            border-color: #007bff;
            background-color: #f7fcff;
        }

        .upload-box .upload-icon {
            width: 80px;
            height: 80px;
            color: #888;
            margin-bottom: 15px;
        }

        .upload-box p {
            font-size: 1.5em;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .upload-box p.subtitle {
            font-size: 0.9em;
            color: #888;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        .upload-box input[type="file"] {
            display: none;
        }

        .select-video-button {
            background-color: #ffc107;
            color: #333;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .select-video-button:hover {
            background-color: #e0a800;
        }

        /* Gaya untuk tombol Next */
        .next-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, opacity 0.3s ease;
            position: absolute;
            bottom: 20px;
            right: 20px;
            opacity: 0.5;
            pointer-events: none;
            width: fit-content;
            z-index: 10;
        }

        .next-button.active {
            opacity: 1;
            pointer-events: auto;
        }

        .next-button:hover.active {
            background-color: #0056b3;
        }

        /* Informasi tambahan */
        .info-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
            padding-bottom: 0;
        }

        .info-box {
            background-color: #f0f2f5;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .info-box .info-icon {
            width: 40px;
            height: 40px;
            color: #666;
            margin-bottom: 10px;
        }

        .info-box h3 {
            font-size: 1.1em;
            color: #333;
            margin: 0 0 5px 0;
            font-weight: 600;
        }

        .info-box p {
            font-size: 0.85em;
            color: #888;
            margin: 0;
            line-height: 1.4;
        }

        #previewImage, #previewVideo {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            margin-top: 20px;
            border-radius: 8px;
            display: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
                padding: 15px 10px;
            }
            .sidebar .logo {
                justify-content: center;
                margin-bottom: 20px;
            }
            .sidebar .logo img {
                height: 30px;
            }
            .sidebar .search-box,
            .sidebar nav ul li a span {
                display: none;
            }
            .sidebar nav ul li a {
                justify-content: center;
                padding: 10px;
            }
            .sidebar nav ul li a svg {
                margin-right: 0;
            }
            .upload-content-area {
                padding: 20px;
                height: auto;
                align-items: center;
                justify-content: flex-start;
            }
            .upload-card {
                padding: 20px;
                width: 100%;
                height: auto;
                flex-grow: 0;
            }
            .upload-box {
                width: 100%;
                height: auto;
                min-height: 200px;
                padding: 20px;
            }
            .next-button {
                bottom: 15px;
                right: 15px;
                padding: 10px 20px;
                font-size: 1em;
            }
            .info-grid {
                width: 100%;
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logoy.png') }}" alt="Padu Padan Logo">
        </div>
        <div class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
            <input type="text" placeholder="Search">
        </div>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('upload') }}" class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#0C2842" d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3 192 320c0 17.7 14.3 32 32 32s32-14.3 32-32l0-210.7 73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-64z"/></svg>
                        Upload
                    </a>
                </li>
                <li>
                    <a href="{{ route('digital.wardrobe.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M211.8 0c7.8 0 14.3 5.7 16.7 13.2C240.8 51.9 277.1 80 320 80s79.2-28.1 91.5-66.8C413.9 5.7 420.4 0 428.2 0l12.6 0c22.5 0 44.2 7.9 61.5 22.3L628.5 127.4c6.6 5.5 10.7 13.5 11.4 22.1s-2.1 17.1-7.8 23.6l-56 64c-11.4 13.1-31.2 14.6-44.6 3.5L480 197.7 480 448c0 35.3-28.7 64-64 64l-192 0c-35.3 0-64-28.7-64-64l0-250.3-51.5 42.9c-13.3 11.1-33.1 9.6-44.6-3.5l-56-64c-5.7-6.5-8.5-15-7.8-23.6s4.8-16.6 11.4-22.1L137.7 22.3C155 7.9 176.7 0 199.2 0l12.6 0z"/></svg>
                        Digital Wardrobe
                    </a>
                </li>
                <li>
                    <a href="#" class=>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M249.6 471.5c10.8 3.8 22.4-4.1 22.4-15.5l0-377.4c0-4.2-1.6-8.4-5-11C247.4 52 202.4 32 144 32C93.5 32 46.3 45.3 18.1 56.1C6.8 60.5 0 71.7 0 83.8L0 454.1c0 11.9 12.8 20.2 24.1 16.5C55.6 460.1 105.5 448 144 448c33.9 0 79 14 105.6 23.5zm76.8 0C353 462 398.1 448 432 448c38.5 0 88.4 12.1 119.9 22.6c11.3 3.8 24.1-4.6 24.1-16.5l0-370.3c0-12.1-6.8-23.3-18.1-27.6C529.7 45.3 482.5 32 432 32c-58.4 0-103.4 20-123 35.6c-3.3 2.6-5 6.8-5 11L304 456c0 11.4 11.7 19.3 22.4 15.5z"/></svg>
                        Lookbook
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M208 352c114.9 0 208-78.8 208-176S322.9 0 208 0S0 78.8 0 176c0 38.6 14.7 74.3 39.6 103.4c-3.5 9.4-8.7 17.7-14.2 24.7c-4.8 6.2-9.7 11-13.3 14.3c-1.8 1.6-3.3 2.9-4.3 3.7c-.5 .4-.9 .7-1.1 .8l-.2 .2s0 0 0 0s0 0 0 0C1 327.2-1.4 334.4 .8 340.9S9.1 352 16 352c21.8 0 43.8-5.6 62.1-12.5c9.2-3.5 17.8-7.4 25.2-11.4C134.1 343.3 169.8 352 208 352zM448 176c0 112.3-99.1 196.9-216.5 207C255.8 457.4 336.4 512 432 512c38.2 0 73.9-8.7 104.7-23.9c7.5 4 16 7.9 25.2 11.4c18.3 6.9 40.3 12.5 62.1 12.5c6.9 0 13.1-4.5 15.2-11.1c2.1-6.6-.2-13.8-5.8-17.9c0 0 0 0 0 0s0 0 0 0l-.2-.2c-.2-.2-.6-.4-1.1-.8c-1-.8-2.5-2-4.3-3.7c-3.6-3.3-8.5-8.1-13.3-14.3c-5.5-7-10.7-15.4-14.2-24.7c24.9-29 39.6-64.7 39.6-103.4c0-92.8-84.9-168.9-192.6-175.5c.4 5.1 .6 10.3 .6 15.5z"/></svg>
                        Chat
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>
                        Profile
                    </a>
                </li>
            </ul>
        </nav>
    </div>


    <div class="upload-content-area">
        {{-- H2 ini disembunyikan via CSS: display: none; --}}
        <h2>Upload New Content</h2>
        <div class="upload-card">
            <form action="#" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf <div class="upload-box" id="uploadBox">
                    <input type="file" id="mediaUpload" name="media" accept="image/*,video/*">
                    <svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <p id="uploadText">Select video to upload</p>
                    <p class="subtitle">Or drag and drop it here</p>
                    <button type="button" class="select-video-button">Select video</button>
                    <img id="previewImage" src="#" alt="Preview" style="display:none;">
                    <video id="previewVideo" src="#" controls style="display:none;"></video>


                    <button type="button" id="nextButton" class="next-button">Next</button>
                </div>
            </form>


            <div class="info-grid">
                <div class="info-box">
                    <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3>Size and duration</h3>
                    <p>Max file size: 2GB <br> Max duration: 10 min</p>
                </div>
                <div class="info-box">
                    <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 2v-2m-9 1H4a2 2 0 00-2 2v2a2 2 0 002 2h16a2 2 0 002-2v-2a2 2 0 00-2-2h-5l-4-6H9V4a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h2" />
                    </svg>
                    <h3>File formats</h3>
                    <p>MP4, MOV, AVI</p>
                </div>
                <div class="info-box">
                    <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <h3>Video resolutions</h3>
                    <p>Minimum: 720p <br> Recommended: 1080p</p>
                </div>
                <div class="info-box">
                    <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M4 8h16M4 16h16" />
                    </svg>
                    <h3>Aspect ratios</h3>
                    <p>16:9 for landscape <br> 9:16 for portrait</p>
                </div>
            </div>
        </div>
    </div>


    <script>
    const uploadBox = document.getElementById('uploadBox');
    const mediaUpload = document.getElementById('mediaUpload');
    const uploadText = document.getElementById('uploadText');
    const subtitleText = document.querySelector('.upload-box .subtitle');
    const selectVideoButton = document.querySelector('.select-video-button');
    const previewImage = document.getElementById('previewImage');
    const previewVideo = document.getElementById('previewVideo');
    const nextButton = document.getElementById('nextButton');
    const uploadForm = document.getElementById('uploadForm');


    console.log("Script loaded.");


    // Memastikan elemen preview disembunyikan secara default
    previewImage.style.display = 'none';
    previewVideo.style.display = 'none';


    // Fungsi untuk mengupdate status tombol Next
    function updateNextButtonStatus() {
        if (mediaUpload.files.length > 0) {
            nextButton.classList.add('active');
            console.log("File selected, Next button is active.");
        } else {
            nextButton.classList.remove('active');
            console.log("No file selected, Next button is inactive.");
        }
    }


    // Event listener untuk tombol "Select video"
    selectVideoButton.addEventListener('click', (e) => {
        e.stopPropagation();
        mediaUpload.click();
        console.log("Select video button clicked.");
    });


    // Event listener untuk klik pada uploadBox
    uploadBox.addEventListener('click', (e) => {
        if (e.target !== mediaUpload && e.target !== selectVideoButton && e.target !== nextButton) {
            mediaUpload.click();
            console.log("Upload box clicked, media input triggered.");
        }
    });


    mediaUpload.addEventListener('change', function() {
        const file = this.files[0];
        console.log("File input changed. File:", file);
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.style.display = 'none';
                previewVideo.style.display = 'none';
                previewImage.src = '';
                previewVideo.src = '';


                uploadBox.querySelector('.upload-icon').style.display = 'none';
                uploadText.style.display = 'none';
                subtitleText.style.display = 'none';
                selectVideoButton.style.display = 'none';


                if (file.type.startsWith('image/')) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                } else if (file.type.startsWith('video/')) {
                    previewVideo.src = e.target.result;
                    previewVideo.style.display = 'block';
                }
                updateNextButtonStatus();
            };
            reader.readAsDataURL(file);
        } else {
            uploadText.textContent = 'Select video to upload';
            subtitleText.textContent = 'Or drag and drop it here';


            uploadBox.querySelector('.upload-icon').style.display = 'block';
            uploadText.style.display = 'block';
            subtitleText.style.display = 'block';
            selectVideoButton.style.display = 'inline-block';


            previewImage.style.display = 'none';
            previewVideo.style.display = 'none';
            previewImage.src = '';
            previewVideo.src = '';
            updateNextButtonStatus();
        }
    });


    // Drag and drop functionality (tetap sama)
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = '#007bff';
        uploadBox.style.backgroundColor = '#f7fcff';
    });


    uploadBox.addEventListener('dragleave', () => {
        uploadBox.style.borderColor = '#d1d1d1';
        uploadBox.style.backgroundColor = '#fff';
    });


    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = '#d1d1d1';
        uploadBox.style.backgroundColor = '#fff';


        const files = e.dataTransfer.files;
        if (files.length > 0) {
            mediaUpload.files = files;
            mediaUpload.dispatchEvent(new Event('change'));
        }
    });


    updateNextButtonStatus(); // Panggil saat halaman pertama kali dimuat


    // Event listener untuk tombol Next
    nextButton.addEventListener('click', () => {
        console.log("Next button clicked.");
        if (nextButton.classList.contains('active')) {
            console.log("Next button is active. Proceeding with upload.");
            const file = mediaUpload.files[0];
            if (file) {
                console.log("File found:", file.name, file.size);
                let formData = new FormData();
                formData.append('media', file);


                // --- BAGIAN INI KUNCINYA ---
                // Pastikan deklarasi csrfToken ada di sini atau di scope yang bisa dijangkau
                // Jika kamu belum menambahkan meta tag CSRF, pastikan untuk menambahkannya di <head>
                const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';
                // Jika kamu yakin meta tag selalu ada, bisa lebih singkat:
                // const csrfToken = document.querySelector('meta[name="csrf-token"]').content;


                console.log("CSRF Token:", csrfToken); // Debugging token


                fetch('{{ route('upload.video') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Menggunakan variabel csrfToken
                    }
                })
                .then(response => {
                    console.log("Fetch response received:", response);
                    if (!response.ok) {
                        // Laravel 419 Page Expired (saat CSRF token tidak ada/invalid) atau 403 Forbidden
                        // Coba cek status code juga
                        if (response.status === 419) {
                            alert('Session expired. Please refresh the page and try again.');
                            window.location.reload(); // Muat ulang halaman
                            throw new Error('Session expired.');
                        } else if (response.status === 403) {
                            alert('Permission denied or invalid CSRF token. Please refresh the page.');
                            window.location.reload(); // Muat ulang halaman
                            throw new Error('Forbidden request.');
                        }
                        // Untuk error lain, coba parse JSON
                        return response.json().then(err => {
                            console.error("Server responded with error JSON:", err);
                            throw new Error(err.message || `Server error: ${response.status}`);
                        }).catch(() => {
                            // Jika bukan JSON (misal HTML dari error page Laravel)
                            console.error("Server responded with non-JSON error (likely HTML error page):", response.statusText);
                            throw new Error(`Server error: ${response.status} ${response.statusText}. Check server logs for details.`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Fetch data received:", data);
                    if (data.success) {
                        console.log("Redirecting to:", data.redirect_url);
                        window.location.href = data.redirect_url;
                    } else {
                        alert('Gagal mengunggah file: ' + (data.message || 'Unknown error.'));
                    }
                })
                .catch(error => {
                    console.error('Error saat mengunggah file:', error);
                    alert('Terjadi kesalahan saat mengunggah file. Silakan coba lagi. Detil error di konsol.');
                });


            } else {
                alert('Tidak ada file yang dipilih untuk diunggah.');
                console.log("No file selected, but next button was active?");
            }
        } else {
            alert('Silakan unggah video atau gambar terlebih dahulu untuk melanjutkan.');
            console.log("Next button is not active.");
        }
    });
</script>
</body>
</html>
