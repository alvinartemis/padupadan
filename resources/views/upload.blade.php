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
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #fff;
            padding: 20px;
            border-right: 1px solid #eee;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 100vh;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            left: 0;
            z-index: 100;
        }

        .sidebar .logo {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .sidebar .logo img {
            height: 40px;
            width: auto;
            margin-left: 0;
        }

        .sidebar .search-box {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 20px;
            padding: 8px 15px;
            margin-bottom: 30px;
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
            color: #888;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar nav ul li {
            margin-bottom: 15px;
        }

        .sidebar nav ul li a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #555;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            background-color: #e0f7fa;
            color: #007bff;
        }

        .sidebar nav ul li a svg {
            margin-right: 15px;
            width: 20px;
            height: 20px;
            color: #888;
        }

        .sidebar nav ul li a.active svg {
            color: #007bff;
        }

        /* Styles for the Upload Content Area */
        .upload-content-area {
            flex-grow: 1;
            padding: 20px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f0f2f5;
            overflow-y: auto;
            min-height: 100vh;
            box-sizing: border-box;
            justify-content: flex-start;
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
            <img src="https://i.imgur.com/P4WfI0h.png" alt="Padu Padan Logo">
        </div>
        <div class="search-box">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Search">
        </div>
        <nav>
            <ul>
                <li>
                    <a href="{{ url('/') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h3v-3m0 0h3m-3 0h-3v-3m9 2h3a1 1 0 001-1v-3m-4-7v4m-3 0h-4a1 2 0 00-1 1v3" />
                        </svg>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/upload') }}" class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Upload</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m-8-4v10l8 4 8-4V7m-4 5v6m-4-6v6m-4-6v6" />
                        </svg>
                        <span>Digital Wardrobe</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Lookbook</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>Chat</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Profile</span>
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
