<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Details - Padu Padan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ... (CSS Anda tetap sama) ... */
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

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 40px; /* Padding di semua sisi */
            background-color: #f0f2f5;
            overflow-y: auto;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .main-content-header {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .main-content-header .file-icon {
            width: 24px;
            height: 24px;
            color: #007bff;
        }

        .main-content-header h2 {
            font-size: 1.4em;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .main-content-header p {
            font-size: 0.9em;
            color: #666;
            margin: 0;
        }

        .main-content-header .status-icon {
            width: 18px;
            height: 18px;
            color: #28a745; /* Green for success */
        }

        .content-grid {
            display: flex; /* Menggunakan flexbox untuk layout 2 kolom utama */
            gap: 30px;
            flex-wrap: wrap; /* Izinkan wrapping di layar kecil */
        }

        .details-section {
            flex: 1; /* Mengambil seluruh ruang yang tersedia di grid */
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            min-width: 300px; /* Lebar minimum untuk responsif */
        }

        .details-section h3 {
            font-size: 1.2em;
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 0.9em;
            color: #555;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group textarea,
        .form-group input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1em;
            box-sizing: border-box;
            resize: vertical; /* Izinkan textarea di-resize vertikal */
        }

        .form-group textarea {
            min-height: 120px;
        }

        .form-group input[type="text"] {
            height: 45px;
        }

        .form-group .char-count {
            font-size: 0.8em;
            color: #888;
            text-align: right;
            margin-top: 5px;
        }

        .outfit-details-group .add-more-button {
            background: none;
            border: none;
            color: #007bff;
            font-size: 0.9em;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 10px;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-start; /* Mengubah justify-content untuk memposisikan tombol ke kiri */
            gap: 15px;
            margin-top: 30px;
            width: 100%;
            padding: 0 30px; /* Tambahkan padding agar tidak terlalu mepet ke kiri/kanan card */
            box-sizing: border-box; /* Pastikan padding dihitung dalam width */
        }

        .action-buttons button {
            padding: 12px 25px;
            border-radius: 8px; /* Sudut sedikit membulat */
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Tambahkan shadow default */
            border: none; /* Pastikan tidak ada border bawaan */
        }

        /* Gaya untuk tombol Post */
        .action-buttons .post-button {
            background-color: #ffc107; /* Warna kuning */
            color: #333; /* Teks gelap */
        }

        .action-buttons .post-button:hover {
            background-color: #e0a800; /* Kuning lebih gelap saat hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Shadow lebih dalam saat hover */
        }

        /* Gaya untuk tombol Discard */
        .action-buttons .discard-button {
            background-color: #e0e0e0; /* Warna abu-abu */
            color: #666; /* Teks abu-abu gelap */
        }

        .action-buttons .discard-button:hover {
            background-color: #c0c0c0; /* Abu-abu lebih gelap saat hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Shadow lebih dalam saat hover */
        }


        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .content-grid {
                flex-direction: column; /* Ubah ke layout kolom di tablet/mobile */
            }
            .details-section { /* Hapus video-preview-section */
                width: 100%; /* Ambil lebar penuh */
                flex: none; /* Hapus flex grow */
            }
        }

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
            .main-content {
                padding: 20px; /* Padding lebih kecil untuk mobile */
            }
            .main-content-header {
                padding: 15px 20px;
                flex-direction: column;
                align-items: flex-start;
            }
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
                padding: 0 20px; /* Sesuaikan padding untuk mobile */
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

    <div class="main-content">
        <div class="main-content-header">
            <svg class="file-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <div>
                {{-- Mengambil nama file dari tempUploadData jika ada, atau dari videoFashion.deskripsi --}}
                <h2 id="videoFileName">{{ $tempUploadData['fileName'] ?? ($videoFashion->deskripsi ?? 'Nama File Tidak Ditemukan') }}</h2>
                <p>
                    {{-- Mengambil ukuran file dari tempUploadData jika ada, atau dari videoFashion.ukuranFile --}}
                    <span id="videoFileSize">{{ isset($tempUploadData['fileSize']) ? formatBytes($tempUploadData['fileSize']) : (isset($videoFashion->ukuranFile) ? formatBytes($videoFashion->ukuranFile) : 'Ukuran Tidak Ditemukan') }}</span>
                    <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Uploaded
                </p>
            </div>
        </div>

        <form id="postDetailsForm" action="{{ route('upload.final.post', ['id' => $videoFashion->idVideoFashion ?? 0]) }}" method="POST">
            @csrf
            <div class="content-grid">
                <div class="details-section">
                    <h3>Details</h3>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Add a description about your video here..." maxlength="500">{{ $videoFashion->deskripsi ?? '' }}</textarea>
                        <div class="char-count" id="descriptionCharCount">0/500</div>
                    </div>
                    <div class="form-group">
                        <label for="hashtags"># Hashtags</label>
                        <input type="text" id="hashtags" name="hashtags" placeholder="Tag people" value="{{ $videoFashion->tag ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="tagPeople">Tag people</label>
                        <input type="text" id="tagPeople" name="tagPeople" placeholder="Tag people">
                    </div>
                    <div class="form-group outfit-details-group">
                        <label for="outfitLink">Outfit Details</label>
                        <input type="text" id="outfitLink" name="outfitLink" placeholder="Outfit link">
                        <button type="button" class="add-more-button">+ Add more outfit details</button>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button type="button" class="discard-button" id="discardButton">Discard</button>
                <button type="submit" class="post-button" id="postButton">Post</button>
            </div>
        </form>
    </div>

    <script>
        // Fungsi formatBytes sekarang ada di PHP Helper, jadi tidak perlu di sini lagi
        document.addEventListener('DOMContentLoaded', () => {
            const descriptionTextarea = document.getElementById('description');
            const descriptionCharCount = document.getElementById('descriptionCharCount');
            const postDetailsForm = document.getElementById('postDetailsForm'); // Dapatkan form baru
            const postButton = document.getElementById('postButton'); // Dapatkan tombol Post

            // Character count untuk description
            if (descriptionTextarea && descriptionCharCount) {
                descriptionTextarea.addEventListener('input', () => {
                    const currentLength = descriptionTextarea.value.length;
                    const maxLength = descriptionTextarea.maxLength;
                    descriptionCharCount.textContent = `${currentLength}/${maxLength}`;
                });

                // Inisialisasi hitungan karakter saat halaman dimuat
                descriptionCharCount.textContent = `${descriptionTextarea.value.length}/${descriptionTextarea.maxLength}`;
            }

            // --- Event Listener untuk Tombol Post (Jika tidak ingin pakai submit form biasa) ---
            // Jika Anda ingin mengontrol pengiriman via AJAX, Anda bisa melakukan ini:
            // postButton.addEventListener('click', (e) => {
            //     e.preventDefault(); // Mencegah submit form biasa
            //     // Lakukan AJAX fetch request di sini
            //     // Anda akan membutuhkan FormData() dari postDetailsForm
            //     // dan mengirimkannya ke route 'upload.final.post'
            //     // Pastikan juga CSRF token terkirim
            //     console.log("Post button clicked! Initiating AJAX save.");
            //     // ... Logika AJAX di sini ...
            // });

            // Event Listener untuk tombol Discard (Opsional)
            const discardButton = document.getElementById('discardButton');
            if (discardButton) {
                discardButton.addEventListener('click', () => {
                    // Logika untuk tombol Discard
                    // Misalnya: konfirmasi, hapus file dari storage jika itu temporary, redirect ke halaman upload
                    if (confirm('Are you sure you want to discard this upload? The file will be removed from temporary storage.')) {
                        // Anda perlu route dan controller method untuk menghapus file sementara
                        // Misalnya: fetch('/upload/discard-temp', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                        // .then(() => window.location.href = '{{ route('upload') }}');
                        window.location.href = '{{ route('upload') }}'; // Sementara, redirect saja
                    }
                });
            }
        });
    </script>
</body>
</html>
