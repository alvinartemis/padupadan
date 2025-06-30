<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Details - Padu Padan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Pastikan meta csrf-token ada dan benar --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            background-color: #f4f4f4;
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
            color: #173F63;
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
            <img src="{{ asset('img/logoy.png') }}" alt="Logo Padu Padan">
        </div>
        <div class="search-box" id="sidebarSearchBox">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Search" id="sidebarSearchInput" readonly>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path fill="currentColor" d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>
                            </svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('upload') }}" class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path fill="currentColor" d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3 192 320c0 17.7 14.3 32 32 32s32-14.3 32-32l0-210.7 73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-64z"/>
                            </svg>
                        Upload
                    </a>
                </li>
                <li>
                    <a href="{{ route('digital.wardrobe.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                             <path fill="currentColor" d="M211.8 0c7.8 0 14.3 5.7 16.7 13.2C240.8 51.9 277.1 80 320 80s79.2-28.1 91.5-66.8C413.9 5.7 420.4 0 428.2 0l12.6 0c22.5 0 44.2 7.9 61.5 22.3L628.5 127.4c6.6 5.5 10.7 13.5 11.4 22.1s-2.1 17.1-7.8 23.6l-56 64c-11.4 13.1-31.2 14.6-44.6 3.5L480 197.7 480 448c0 35.3-28.7 64-64 64l-192 0c-35.3 0-64-28.7-64-64l0-250.3-51.5 42.9c-13.3 11.1-33.1 9.6-44.6-3.5l-56-64c-5.7-6.5-8.5-15-7.8-23.6s4.8-16.6 11.4-22.1L137.7 22.3C155 7.9 176.7 0 199.2 0l12.6 0z"/>
                             </svg>
                         Digital Wardrobe
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.lookbook.index')}}" class=>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path fill="currentColor" d="M249.6 471.5c10.8 3.8 22.4-4.1 22.4-15.5l0-377.4c0-4.2-1.6-8.4-5-11C247.4 52 202.4 32 144 32C93.5 32 46.3 45.3 18.1 56.1C6.8 60.5 0 71.7 0 83.8L0 454.1c0 11.9 12.8 20.2 24.1 16.5C55.6 460.1 105.5 448 144 448c33.9 0 79 14 105.6 23.5zm76.8 0C353 462 398.1 448 432 448c38.5 0 88.4 12.1 119.9 22.6c11.3 3.8 24.1-4.6 24.1-16.5l0-370.3c0-12.1-6.8-23.3-18.1-27.6C529.7 45.3 482.5 32 432 32c-58.4 0-103.4 20-123 35.6c-3.3 2.6-5 6.8-5 11L304 456c0 11.4 11.7 19.3 22.4 15.5z"/>
                            </svg>
                        Lookbook
                    </a>
                </li>
                <li>
                    <a href="{{ route('chat.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path fill="currentColor" d="M208 352c114.9 0 208-78.8 208-176S322.9 0 208 0S0 78.8 0 176c0 38.6 14.7 74.3 39.6 103.4c-3.5 9.4-8.7 17.7-14.2 24.7c-4.8 6.2-9.7 11-13.3 14.3c-1.8 1.6-3.3 2.9-4.3 3.7c-.5 .4-.9 .7-1.1 .8l-.2 .2s0 0 0 0s0 0 0 0C1 327.2-1.4 334.4 .8 340.9S9.1 352 16 352c21.8 0 43.8-5.6 62.1-12.5c9.2-3.5 17.8-7.4 25.2-11.4C134.1 343.3 169.8 352 208 352zM448 176c0 112.3-99.1 196.9-216.5 207C255.8 457.4 336.4 512 432 512c38.2 0 73.9-8.7 104.7-23.9c7.5 4 16 7.9 25.2 11.4c18.3 6.9 40.3 12.5 62.1 12.5c6.9 0 13.1-4.5 15.2-11.1c2.1-6.6-.2-13.8-5.8-17.9c0 0 0 0 0 0s0 0 0 0l-.2-.2c-.2-.2-.6-.4-1.1-.8c-1-.8-2.5-2-4.3-3.7c-3.6-3.3-8.5-8.1-13.3-14.3c-5.5-7-10.7-15.4-14.2-24.7c24.9-29 39.6-64.7 39.6-103.4c0-92.8-84.9-168.9-192.6-175.5c.4 5.1 .6 10.3 .6 15.5z"/>
                            </svg>
                        Chat
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path fill="#a3a3a3" d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/>
                            </svg>
                        Profile
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

        <form id="postDetailsForm"> {{-- action dan method="POST" dihapus --}}
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
                <button type="button" class="post-button" id="postButton">Post</button> {{-- type diubah menjadi button --}}
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const descriptionTextarea = document.getElementById('description');
            const descriptionCharCount = document.getElementById('descriptionCharCount');
            const postDetailsForm = document.getElementById('postDetailsForm');
            const postButton = document.getElementById('postButton');
            const discardButton = document.getElementById('discardButton');

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

            // Event Listener untuk tombol Post (Sekarang menggunakan AJAX fetch)
            postButton.addEventListener('click', async (e) => {
                e.preventDefault(); // Mencegah submit form biasa

                console.log("Post button clicked! Initiating AJAX save.");

                const formData = new FormData(postDetailsForm);
                // Pastikan videoId ini sesuai dengan yang diharapkan di backend jika Anda memiliki logic update berdasarkan ID temp
                const videoId = {{ $videoFashion->idVideoFashion ?? '0' }};
                if (videoId && videoId !== 0) {
                    formData.append('idVideoFashion', videoId); // Jika Anda perlu mengirim ID dummy ke backend
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                try {
                    const response = await fetch('{{ route('upload.final.post', ['id' => $videoFashion->idVideoFashion ?? 0]) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    console.log("Fetch response received:", response);

                    if (!response.ok) {
                        const errorData = await response.json().catch(() => ({}));
                        console.error("Server responded with error:", errorData);
                        let errorMessage = errorData.message || `Terjadi kesalahan server: ${response.status} ${response.statusText}`;

                        if (response.status === 419) {
                            errorMessage = 'Sesi Anda telah berakhir. Mohon refresh halaman dan coba lagi.';
                            window.location.reload();
                        } else if (response.status === 403) {
                            errorMessage = 'Akses ditolak atau CSRF token tidak valid. Mohon refresh halaman.';
                            window.location.reload();
                        }
                        alert(errorMessage); // Tetap tampilkan alert untuk error
                        return;
                    }

                    const data = await response.json();

                    console.log("Fetch data received:", data);

                    if (data.success) {
                        // HAPUS BARIS INI: alert(data.message);
                        console.log("Redirecting to:", data.redirect_url);
                        window.location.href = data.redirect_url; // Langsung redirect ke halaman beranda
                    } else {
                        alert('Gagal mengunggah file: ' + (data.message || 'Unknown error.')); // Tetap tampilkan alert untuk error
                    }
                } catch (error) {
                    console.error('Error saat mengunggah file:', error);
                    alert('Terjadi kesalahan saat mengunggah file. Silakan coba lagi. Detil error di konsol.'); // Tetap tampilkan alert untuk error
                }
            });

            // Event Listener untuk tombol Discard
            if (discardButton) {
                discardButton.addEventListener('click', () => {
                    // Anda bisa menambahkan AJAX request untuk route discard jika Anda ingin menanganinya dengan backend
                    // Jika tidak, redirect langsung ke halaman upload awal (atau halaman home)
                    if (confirm('Apakah Anda yakin ingin membatalkan upload ini? File akan dihapus dari penyimpanan sementara.')) {
                        // Jika Anda memiliki route /upload/discard untuk menghapus file sementara
                        fetch('{{ route('upload.discard') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json' // Jika tidak ada body, ini bisa dihilangkan
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log("Discard successful, redirecting to:", data.redirect_url);
                                window.location.href = data.redirect_url; // Redirect ke halaman upload setelah discard
                            } else {
                                alert('Gagal membatalkan upload: ' + (data.message || 'Unknown error.'));
                            }
                        })
                        .catch(error => {
                            console.error('Error discarding file:', error);
                            alert('Terjadi kesalahan saat membatalkan upload.');
                        });
                    }
                });
            }
        });

        // Helper function for file size formatting (Optional, Anda bisa taruh di helper PHP atau biarkan seperti ini)
        // Jika formatBytes sudah ada di PHP Helper, Anda tidak perlu ini di JS
        // function formatBytes(bytes, decimals = 2) {
        //     if (bytes === 0) return '0 Bytes';
        //     const k = 1024;
        //     const dm = decimals < 0 ? 0 : decimals;
        //     const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        //     const i = Math.floor(Math.log(bytes) / Math.log(k));
        //     return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        // }
    </script>
</body>
</html>
