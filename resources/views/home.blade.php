<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padu Padan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden; /* Mencegah scrollbar muncul di seluruh halaman */
        }


        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .container {
            display: flex;
            width: 100%;
            height: 100%;
            max-width: none;
            background-color: #fff;
            box-shadow: none;
            border-radius: 0;
            overflow: hidden;
            margin: 0;
            position: relative; /* Untuk positioning di dalamnya */
        }


        .sidebar {
            width: 250px;
            background-color: #fff;
            padding: 20px;
            border-right: 1px solid #eee;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-shrink: 0;
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


        .main-content-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center; /* Memusatkan main-content (video + ikon interaksi) */
            align-items: center;
            height: 100%;
            overflow: hidden;
            position: relative;
        }


        .main-content {
            display: flex;
            align-items: center;
            height: 100%;
            flex-shrink: 0;
        }


        .video-column {
            position: relative;
            height: 100%;
            width: auto;
            aspect-ratio: 9 / 16;
            background-color: #000;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }


        .video-column video,
        .video-column img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .post-info-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);
            padding: 20px 15px 10px;
            box-sizing: border-box;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            pointer-events: none;
        }


        .post-info-overlay .username {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1.1em;
        }


        .post-info-overlay .description {
            font-size: 0.9em;
            line-height: 1.4;
        }


        .interaction-sidebar {
            width: 80px;
            background-color: transparent;
            padding-top: 50px;
            padding-bottom: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            box-sizing: border-box;
            flex-shrink: 0;
            margin-left: 15px; /* Jarak antara video dan sidebar interaksi */
        }


        .interaction-sidebar .user-avatar-single img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }


        .interaction-sidebar .interaction-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
            width: 100%;
        }


        .interaction-sidebar .icon-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            color: #555;
            font-size: 13px;
        }


        .interaction-sidebar .icon-group .like-button {
            width: 48px;
            height: 48px;
            background-color: #f0f2f5;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: background-color 0.2s ease, transform 0.2s ease;
        }


        .interaction-sidebar .icon-group .like-button:hover {
            background-color: #e0e2e5;
        }


        .interaction-sidebar .icon-group .like-button svg {
            width: 24px;
            height: 24px;
            color: #555;
            transition: color 0.2s ease;
        }


        .interaction-sidebar .icon-group .like-button.liked svg {
            fill: #FFD700;
            color: #FFD700;
        }


        .interaction-sidebar .interaction-buttons svg {
            width: 24px;
            height: 24px;
            color: #555;
            cursor: pointer;
            transition: color 0.2s ease;
        }


        .interaction-sidebar .interaction-buttons svg:hover {
            color: #007bff;
        }


        /* CSS untuk Scroll Arrows yang diposisikan di tengah area kosong di kanan tampilan */
        .scroll-arrows-right-centered {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            right: 120px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 100;
        }


        .scroll-arrows-right-centered .arrow-button {
            width: 48px;
            height: 48px;
            background-color: #f0f2f5;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: background-color 0.2s ease, transform 0.2s ease;
        }


        .scroll-arrows-right-centered .arrow-button:hover {
            background-color: #e0e2e5;
            transform: translateY(-2px);
        }
        .scroll-arrows-right-centered .arrow-button:last-child:hover {
            transform: translateY(2px);
        }


        .scroll-arrows-right-centered .arrow-button svg {
            width: 24px;
            height: 24px;
            color: #888;
        }


        /* ----- CSS untuk Comments Section (Gaya Baru: Tanpa Bubble) ----- */
        .comments-section {
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 100%;
            background-color: #fff;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-out;
            display: flex;
            flex-direction: column;
            z-index: 101;
            box-sizing: border-box; /* Pastikan padding masuk hitungan width */
        }


        .comments-section.active {
            transform: translateX(0);
        }


        .comments-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .comments-header h3 {
            margin: 0;
            font-size: 1.1em;
            font-weight: 600;
            color: #333;
        }


        .comments-header .close-button {
            background: none;
            border: none;
            font-size: 1.5em;
            cursor: pointer;
            color: #888;
        }


        .comments-list {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
        }


        /* Gaya Komentar Baru (Seperti Chat Inbox, Tapi Tanpa Bubble Background) */
        .comment-item {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
            gap: 10px; /* Jarak antara avatar dan teks */
        }


        .comment-item .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0; /* Pastikan avatar tidak mengecil */
        }


        .comment-item .comment-text-wrapper { /* Nama baru untuk mengganti .comment-bubble-wrapper */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }


        .comment-item .comment-meta-info {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }


        .comment-item .comment-author {
            font-weight: 600;
            font-size: 0.9em;
            color: #333;
        }


        .comment-item .comment-time {
            font-size: 0.75em;
            color: #999;
        }


        .comment-item .comment-message { /* Nama baru untuk mengganti .comment-bubble */
            /* Hapus semua gaya bubble background, padding, border-radius */
            /* background-color: transparent; */ /* Tidak perlu karena default transparan */
            padding: 0; /* Hapus padding bubble */
            border-radius: 0; /* Hapus border-radius */
            font-size: 0.9em;
            color: #444;
            max-width: 100%; /* Lebar penuh */
            word-wrap: break-word; /* Memastikan teks wrap */
        }

        .comment-input-area {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px; /* Jarak antara input dan tombol */
        }


        .comment-input-area input {
            flex-grow: 1;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px 15px;
            outline: none;
            font-size: 0.9em;
            /* margin-right: 10px; */ /* Dihapus karena ada gap */
        }


        .comment-input-area .send-button {
            background: none;
            border: none;
            color: #007bff;
            font-size: 1.2em;
            cursor: pointer;
            padding: 5px; /* Tambahkan padding untuk area klik */
            display: flex; /* Untuk memusatkan SVG */
            align-items: center;
            justify-content: center;
        }


        .comment-input-area .send-button svg {
            width: 24px; /* Sesuaikan ukuran SVG */
            height: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
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
                        <a href="{{ route('home') }}" class="active">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h3v-3m0 0h3m-3 0h-3v-3m9 2h3a1 1 0 001-1v-3m-4-7v4m-3 0h-4a1 2 0 00-1 1v3" />
                            </svg>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('upload') }}">
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
                        <a href="{{ route('profile') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>


        <div class="main-content-wrapper">
            <div class="main-content">
                <div class="video-column">
                    <video id="mainVideo" controls autoplay loop muted>
                        <source src="" type="video/mp4"> Your browser does not support the video tag.
                    </video>
                    <div class="post-info-overlay">
                        <div class="username" id="videoUsername"></div>
                        <div class="description" id="videoDescription"></div>
                    </div>
                </div>


                <div class="interaction-sidebar">
                    <div class="user-avatar-single">
                        <img src="https://i.imgur.com/S2i43eS.jpg" alt="User Avatar">
                    </div>


                    <div class="interaction-buttons">
                        <div class="icon-group">
                            <div class="like-button" id="likeButton">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span id="likeCount"></span>
                        </div>
                        <div class="icon-group">
                            <svg id="commentIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span id="commentCount"></span>
                        </div>
                        <div class="icon-group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span></span>
                        </div>
                        <div class="icon-group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="scroll-arrows-right-centered">
            <div class="arrow-button" id="arrowUp">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </div>
            <div class="arrow-button" id="arrowDown">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
        <div id="commentsSection" class="comments-section">
            <div class="comments-header">
                <h3 id="commentsHeaderCount">Comments (765)</h3>
                <button class="close-button" id="closeComments">&times;</button>
            </div>
            <div class="comments-list">
                </div>
            <div class="comment-input-area">
                <input type="text" placeholder="Add comment...">
                <button class="send-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Hapus array 'videos' statis yang lama
            // const videos = [
            //     { /* ... data video statis ... */ },
            //     { /* ... data video statis ... */ },
            //     { /* ... data video statis ... */ }
            // ];

            let videos = []; // Inisialisasi array videos kosong, akan diisi dari API

            let currentVideoIndex = 0;


            // Dapatkan elemen-elemen yang relevan
            const mainVideo = document.getElementById('mainVideo');
            const videoUsername = document.getElementById('videoUsername');
            const videoDescription = document.getElementById('videoDescription');
            const likeCountSpan = document.getElementById('likeCount');
            const commentCountSpan = document.getElementById('commentCount');
            const commentsList = document.querySelector('.comments-list');
            const commentsHeaderCount = document.getElementById('commentsHeaderCount');




            const arrowUp = document.getElementById('arrowUp');
            const arrowDown = document.getElementById('arrowDown');
            const commentIcon = document.getElementById('commentIcon');
            const commentsSection = document.getElementById('commentsSection');
            const closeCommentsButton = document.getElementById('closeComments');
            const likeButton = document.getElementById('likeButton');


            // Fungsi untuk memuat video dan info terkait
            function loadVideo(index) {
                const videoData = videos[index];
                if (!videoData) return; // Hentikan jika indeks tidak valid


                mainVideo.src = videoData.src;
                mainVideo.load(); // Memuat video baru
                mainVideo.play(); // Memulai pemutaran


                videoUsername.textContent = videoData.username;
                videoDescription.textContent = videoData.description;
                likeCountSpan.textContent = videoData.likes;
                commentCountSpan.textContent = videoData.commentsCount;
                commentsHeaderCount.textContent = `Comments (${videoData.commentsCount})`;


                // Reset like state saat video berganti
                likeButton.classList.remove('liked');
                // Asumsi status like awal adalah false untuk setiap video baru
                likeButton.dataset.isLiked = 'false';


                // Muat komentar untuk video ini
                loadComments(videoData.comments);
            }


            // Fungsi untuk memuat komentar
            function loadComments(comments) {
                commentsList.innerHTML = ''; // Bersihkan komentar yang ada
                comments.forEach(comment => {
                    const commentItem = document.createElement('div');
                    commentItem.classList.add('comment-item');
                    commentItem.innerHTML = `
                        <img src="<span class="math-inline">\{comment\.avatar\}" alt\="</span>{comment.author}" class="avatar">
                        <div class="comment-text-wrapper"> <div class="comment-meta-info">
                                <div class="comment-author"><span class="math-inline">\{comment\.author\}</div\>
<div class\="comment\-time"\></span>{comment.time}</div>
                            </div>
                            <div class="comment-message">${comment.text}</div> </div>
                    `;
                    commentsList.appendChild(commentItem);
                });
            }




            // Fungsi baru untuk mengambil data video dari API Laravel
            async function fetchVideos() {
                try {
                    // Gunakan helper route() Laravel untuk mendapatkan URL API yang benar
                    const response = await fetch('{{ route('api.videos') }}');
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();
                    videos = data; // Mengisi array 'videos' dengan data dari database

                    if (videos.length > 0) {
                        loadVideo(currentVideoIndex); // Muat video pertama setelah data diambil
                    } else {
                        console.warn("Tidak ada video yang ditemukan dari database.");
                    }
                } catch (error) {
                    console.error("Error fetching videos:", error);
                    // Tampilkan pesan kesalahan kepada pengguna jika terjadi masalah
                    alert("Gagal memuat video. Silakan coba lagi nanti.");
                }
            }

            // Panggil fungsi untuk mengambil video saat halaman dimuat
            fetchVideos();

            // Event Listener untuk tombol panah bawah
            arrowDown.addEventListener('click', () => {
                if (videos.length === 0) return; // Tambahkan cek untuk memastikan ada video
                currentVideoIndex = (currentVideoIndex + 1) % videos.length;
                loadVideo(currentVideoIndex);
            });

            // Event Listener untuk tombol panah atas
            arrowUp.addEventListener('click', () => {
                if (videos.length === 0) return; // Tambahkan cek untuk memastikan ada video
                currentVideoIndex = (currentVideoIndex - 1 + videos.length) % videos.length;
                loadVideo(currentVideoIndex);
            });

            // Event Listener untuk Komentar
            commentIcon.addEventListener('click', () => {
                commentsSection.classList.add('active');
            });

            closeCommentsButton.addEventListener('click', () => {
                commentsSection.classList.remove('active');
            });

            // Event Listener untuk Like
            likeButton.addEventListener('click', () => {
                let isLiked = likeButton.classList.toggle('liked'); // Menggunakan toggle untuk menambah/menghapus kelas 'liked'
                let currentLikesText = likeCountSpan.textContent; // Ambil teks "199.7K"
                let currentLikes = parseFloat(currentLikesText.replace('K', '')) * 1000; // Konversi ke angka murni (misal: 199700)


                if (isLiked) {
                    currentLikes += 100; // Tambah 100 like
                } else {
                    currentLikes -= 100; // Kurangi 100 like
                }
                // Format kembali ke "XX.X K"
                likeCountSpan.textContent = (currentLikes / 1000).toFixed(1) + 'K';
            });
            // Hapus baris ini: loadVideo(currentVideoIndex);
            // Karena kita sekarang memanggil loadVideo di dalam fetchVideos() setelah data diterima.
        });
    </script>
</body>
</html>
