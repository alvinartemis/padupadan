<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padu Padan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* ... (CSS Anda, tidak ada perubahan di sini) ... */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden; /* Mencegah scrollbar muncul di seluruh halaman */
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
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
            position: relative;
            /* Untuk positioning di dalamnya */
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
            box-sizing: border-box;
            /* Pastikan padding masuk hitungan width */
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

        /* Gaya baru untuk overlay pencarian */
        .search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparan overlay */
            display: flex;
            justify-content: flex-start; /* Untuk menempatkan konten di kiri */
            align-items: flex-start;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .search-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .search-content {
            background-color: #fff;
            width: 500px; /* Lebar konten pencarian */
            height: 100%;
            padding: 30px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transform: translateX(-100%); /* Sembunyikan ke kiri */
            transition: transform 0.3s ease-out;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .search-overlay.active .search-content {
            transform: translateX(0); /* Munculkan dari kiri */
        }

        .search-header-overlay { /* Mengganti .search-header */
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-header-overlay h2 {
            margin: 0;
            font-size: 1.8em;
            color: #333;
            font-weight: 600;
        }

        .search-header-overlay .close-search {
            background: none;
            border: none;
            font-size: 2em;
            cursor: pointer;
            color: #888;
        }

        .search-input-overlay {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 20px;
            padding: 8px 15px;
            margin-bottom: 30px;
        }

        .search-input-overlay input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 16px;
            margin-left: 10px;
            padding: 0; /* Hapus padding default */
        }

        .search-input-overlay svg {
            width: 20px;
            height: 20px;
            color: #888;
        }

        .search-section-title {
            font-size: 1.1em;
            font-weight: 600;
            color: #555;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .search-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .search-list li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.95em;
            color: #444;
            cursor: pointer;
            padding: 8px 0;
            transition: background-color 0.2s ease;
        }

        .search-list li:hover {
            background-color: #f9f9f9;
        }

        .search-list li svg {
            margin-right: 15px;
            width: 18px;
            height: 18px;
            color: #888;
        }

        .search-list li .clear-search-item {
            margin-left: auto;
            color: #bbb;
            cursor: pointer;
        }
        .search-list li .clear-search-item:hover {
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
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
                        <a href="{{ route('home') }}" class="active">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#0C2842" d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('upload') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="#a3a3a3" d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3 192 320c0 17.7 14.3 32 32 32s32-14.3 32-32l0-210.7 73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-64z"/></svg>
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
                            <span>Chat</span>
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

        <div class="main-content-wrapper">
            <div class="main-content">
                <div class="video-column">
                    <video id="mainVideo" controls autoplay loop muted>
                        <source src="" type="video/mp4"> Your browser does not support the video tag.</video>
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
                <h3 id="commentsHeaderCount">Comments (0)</h3>
                <button class="close-button" id="closeComments">&times;</button>
            </div>
            <div class="comments-list">
                </div>
            <div class="comment-input-area">
                <input type="text" id="commentInput" placeholder="Add comment...">
                <button class="send-button" id="sendCommentButton">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="searchOverlay" class="search-overlay">
        <div class="search-content">
            <div class="search-header-overlay">
                <h2>Search</h2>
                <button class="close-search" id="closeSearchButton">&times;</button>
            </div>
            <form id="searchFormOverlay" action="{{ route('search.index') }}" method="GET">
                <div class="search-input-overlay">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="outfits for school" id="overlaySearchInput" name="query">
                    <button type="button" style="background: none; border: none; cursor: pointer; padding: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 18px; height: 18px; color: #aaa;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="recent-searches">
                <div class="search-section-title">Recent searches</div>
                <ul class="search-list" id="recentSearchesList">
                    </ul>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let videos = [];
            let currentVideoIndex = 0;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Get relevant elements
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

            // Elements for comment input
            const commentInput = document.getElementById('commentInput');
            const sendCommentButton = document.getElementById('sendCommentButton');

            // New elements for search overlay
            const sidebarSearchBox = document.getElementById('sidebarSearchBox');
            const sidebarSearchInput = document.getElementById('sidebarSearchInput'); // added this
            const searchOverlay = document.getElementById('searchOverlay');
            const closeSearchButton = document.getElementById('closeSearchButton');
            const overlaySearchInput = document.getElementById('overlaySearchInput');
            const recentSearchesList = document.getElementById('recentSearchesList'); // added this
            const searchFormOverlay = document.getElementById('searchFormOverlay'); // added this
            const clearOverlaySearchInputButton = searchFormOverlay.querySelector('button'); // Tombol 'x' di overlay

            // --- Helper function to format likes (e.g., 100000 -> 100K) ---
            function formatNumberToK(num) {
                if (num >= 1000) {
                    return (num / 1000).toFixed(1) + 'K';
                }
                return num.toString();
            }

            // --- Helper function to format comment time (e.g., "2023-05-25 10:30:00" -> "2 jam lalu") ---
            function formatTimeAgo(timestamp) {
                const now = new Date();
                const commentDate = new Date(timestamp);
                const seconds = Math.floor((now - commentDate) / 1000);
                if (seconds < 60) return `${seconds} detik lalu`;
                const minutes = Math.floor(seconds / 60);
                if (minutes < 60) return `${minutes} menit lalu`;
                const hours = Math.floor(minutes / 60);
                if (hours < 24) return `${hours} jam lalu`;
                const days = Math.floor(hours / 24);
                if (days < 30) return `${days} hari lalu`;
                const months = Math.floor(days / 30);
                if (months < 12) return `${months} bulan lalu`;
                const years = Math.floor(months / 12);
                return `${years} tahun lalu`;
            }

            // --- Main Logic Functions (Definisikan sebelum mereka dipanggil pertama kali) ---

            // Fungsi loadComments (Dipindahkan ke atas)
            function loadComments(comments) {
                console.log("DEBUG: --- Memulai loadComments ---");
                console.log("DEBUG: loadComments dipanggil dengan array:", comments);
                console.log("DEBUG: Panjang array komentar:", comments.length);

                commentsList.innerHTML = ''; // Bersihkan komentar yang ada

                if (comments.length === 0) {
                    commentsList.innerHTML = '<p id="noCommentsMessage" style="text-align: center; color: #888;">Belum ada komentar.</p>';
                    console.log("DEBUG: Array komentar kosong, menampilkan pesan 'Belum ada komentar'.");
                    console.log("DEBUG: --- loadComments selesai (array kosong) ---");
                    return;
                }

                comments.forEach(comment => {
                    console.log("DEBUG: Memproses komentar:", comment);
                    const commentItem = document.createElement('div');
                    commentItem.classList.add('comment-item');

                    const displayAuthor = comment.author || 'Anonim';
                    // Menggunakan asset lokal untuk default avatar
                    const displayAvatar = comment.avatar || '{{ asset('images/default_avatar.jpg') }}';

                    commentItem.innerHTML = `
                        <img src="${displayAvatar}" alt="${displayAuthor}" class="avatar">
                        <div class="comment-text-wrapper">
                            <div class="comment-meta-info">
                                <div class="comment-author">${displayAuthor}</div>
                                <div class="comment-time">${formatTimeAgo(comment.time)}</div>
                            </div>
                            <div class="comment-message">${comment.text}</div>
                        </div>
                    `;
                    commentsList.appendChild(commentItem);
                    console.log("DEBUG: Komentar ditambahkan ke DOM:", commentItem);
                });
                console.log("DEBUG: Komentar selesai dimuat ke DOM.");
                console.log("DEBUG: --- loadComments selesai ---");
            }

            // Fungsi loadVideo (Dipindahkan ke atas, karena dipanggil oleh fetchVideos)
            function loadVideo(index) {
                const videoData = videos[index];
                if (!videoData) {
                    console.warn(`Video data at index ${index} is undefined or null.`);
                    // Tampilkan pesan placeholder jika tidak ada data video
                    mainVideo.src = '';
                    videoUsername.textContent = 'Tidak Ada Video';
                    videoDescription.textContent = 'Mohon periksa database atau respon API.';
                    likeCountSpan.textContent = '0';
                    commentCountSpan.textContent = '0';
                    commentsHeaderCount.textContent = 'Comments (0)';
                    commentsList.innerHTML = '<p id="noCommentsMessage" style="text-align: center; color: #888;">Tidak ada komentar.</p>';
                    return;
                }

                mainVideo.src = videoData.src;
                mainVideo.load();
                mainVideo.play();

                videoUsername.textContent = videoData.username;
                videoDescription.textContent = videoData.description;
                likeCountSpan.textContent = videoData.likes;
                const currentCommentsCount = parseInt(videoData.comments_count) || 0;
                commentCountSpan.textContent = currentCommentsCount;
                commentsHeaderCount.textContent = `Comments (${currentCommentsCount})`;

                likeButton.classList.remove('liked');
                likeButton.dataset.isLiked = 'false';

                console.log("Di loadVideo: videoData.comments =", videoData.comments);
                loadComments(videoData.comments || []); // Panggilan ini sekarang akan menemukan loadComments
            }

            // Fungsi fetchVideos (Dipindahkan ke atas, karena dipanggil saat DOMContentLoaded)
            async function fetchVideos() {
                try {
                    const response = await fetch('{{ route('api.videos') }}');
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
                    }
                    const data = await response.json();
                    videos = data.map(video => {
                        return {
                            ...video,
                            comments_count: parseInt(video.comments_count) || 0
                        };
                    });
                    if (videos.length > 0) {
                        loadVideo(currentVideoIndex);
                    } else {
                        console.warn("Tidak ada video yang ditemukan dari database atau API mengembalikan array kosong.");
                        loadVideo(0); // Panggil loadVideo dengan index 0 untuk menampilkan pesan "Tidak Ada Video"
                    }
                } catch (error) {
                    console.error("Error fetching videos:", error);
                    loadVideo(0); // Panggil loadVideo dengan index 0 untuk menampilkan pesan "Tidak Ada Video"
                }
            }

            // --- 4. Panggilan Awal & Event Listener (di bagian paling bawah script) ---

            // Panggil fungsi untuk mengambil video saat halaman dimuat
            fetchVideos();

            // Event Listener for scroll arrows (down)
            arrowDown.addEventListener('click', () => {
                if (videos.length === 0) return;
                currentVideoIndex = (currentVideoIndex + 1) % videos.length;
                loadVideo(currentVideoIndex);
            });

            // Event Listener for scroll arrows (up)
            arrowUp.addEventListener('click', () => {
                if (videos.length === 0) return;
                currentVideoIndex = (currentVideoIndex - 1 + videos.length) % videos.length;
                loadVideo(currentVideoIndex);
            });

            // Event Listener for Comment Icon
            commentIcon.addEventListener('click', () => {
                commentsSection.classList.add('active');
            });

            closeCommentsButton.addEventListener('click', () => {
                commentsSection.classList.remove('active');
            });

            // Event Listener for Like Button
            likeButton.addEventListener('click', () => {
                // This is still client-side only. For persistent likes, you'd send an API request.
                let isLiked = likeButton.classList.toggle('liked');
                let currentLikes = parseInt(likeCountSpan.textContent.replace('K', '')) * (likeCountSpan.textContent.includes('K') ? 1000 : 1);

                if (isLiked) {
                    currentLikes += 1; // Increment by 1 for actual like
                } else {
                    currentLikes -= 1; // Decrement by 1
                }
                videos[currentVideoIndex].likes = currentLikes; // Update local video data
                likeCountSpan.textContent = formatNumberToK(currentLikes);

                // TODO: Send API request to update like count in database for videos[currentVideoIndex].id
                // fetch('/api/videos/' + videos[currentVideoIndex].id + '/like', { /* ... */ });
            });

            // Event Listener for sending a comment
            sendCommentButton.addEventListener('click', async () => {
                const commentText = commentInput.value.trim();
                const videoId = videos[currentVideoIndex] ? videos[currentVideoIndex].id : null;

                if (commentText === '' || videoId === null) {
                    alert('Komentar tidak boleh kosong atau video tidak ditemukan.');
                    return;
                }

                try {
                    const response = await fetch('{{ route('api.comments.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // Use the token from meta tag
                        },
                        body: JSON.stringify({
                            video_id: videoId,
                            comment: commentText,
                            // Anda mungkin perlu mengirim idPengguna dari sesi atau autentikasi Laravel
                            // Untuk demo, asumsikan user saat ini adalah 'Anda'
                            // user_id: {{ Auth::check() ? Auth::id() : 'null' }} // Gunakan Auth::id() jika ada
                        })
                    });
                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Hapus pesan "Belum ada komentar." jika ada
                        const noCommentsMessage = document.getElementById('noCommentsMessage');
                        if (noCommentsMessage) {
                            noCommentsMessage.remove();
                        }

                        // Buat elemen komentar baru
                        const newCommentItem = document.createElement('div');
                        newCommentItem.classList.add('comment-item');
                        newCommentItem.innerHTML = `
                            <img src="{{ Auth::check() && Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default_avatar.jpg') }}" alt="Anda" class="avatar">
                            <div class="comment-text-wrapper">
                                <div class="comment-meta-info">
                                    <div class="comment-author">{{ Auth::check() ? Auth::user()->name : 'Anda' }}</div>
                                    <div class="comment-time">Baru Saja</div>
                                </div>
                                <div class="comment-message">${commentText}</div>
                            </div>
                        `;
                        commentsList.prepend(newCommentItem); // Tambahkan ke atas daftar

                        // Perbarui jumlah komentar di UI dan data lokal
                        videos[currentVideoIndex].comments_count = parseInt(videos[currentVideoIndex].comments_count) || 0;
                        videos[currentVideoIndex].comments_count++;
                        commentsHeaderCount.textContent = `Comments (${videos[currentVideoIndex].comments_count})`;
                        commentCountSpan.textContent = videos[currentVideoIndex].comments_count;

                        commentInput.value = ''; // Kosongkan input
                        commentsList.scrollTop = 0; // Gulir ke atas daftar komentar
                    } else {
                        alert('Gagal mengirim komentar: ' + (data.message || 'Unknown error'));
                        console.error('Comment submission error:', data);
                    }
                } catch (error) {
                    console.error('Error submitting comment:', error);
                    alert('Terjadi kesalahan saat mengirim komentar.');
                }
            });

            // Event Listeners for Search Overlay
            sidebarSearchBox.addEventListener('click', () => {
                searchOverlay.classList.add('active');
                overlaySearchInput.focus(); // Fokuskan input di overlay
                fetchRecentSearches(); // Muat pencarian terkini saat overlay dibuka
            });

            closeSearchButton.addEventListener('click', () => {
                searchOverlay.classList.remove('active');
            });

            // Handle submission from the overlay search input
            searchFormOverlay.addEventListener('submit', (event) => {
                // Form akan disubmit secara normal ke route search.index
            });

            // Handle clear button for overlay search input
            clearOverlaySearchInputButton.addEventListener('click', (event) => {
                event.preventDefault(); // Mencegah submit form
                overlaySearchInput.value = '';
            });

            // Function to fetch and display recent searches
            async function fetchRecentSearches() {
                try {
                    const response = await fetch('{{ route('api.search.recent') }}');
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    recentSearchesList.innerHTML = ''; // Clear existing list
                    if (data.length > 0) {
                        data.forEach(item => {
                            const li = document.createElement('li');
                            li.innerHTML = `
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="search-text">${item}</span>
                                <span class="clear-search-item" data-keyword="${item}">&times;</span>
                            `;
                            // Event listener for clicking on recent search item to perform search
                            li.querySelector('.search-text').addEventListener('click', () => {
                                overlaySearchInput.value = item;
                                searchFormOverlay.submit(); // Submit form with the clicked keyword
                            });
                            // Event listener for clearing individual recent search item (future enhancement if needed, currently client-side only)
                            li.querySelector('.clear-search-item').addEventListener('click', (e) => {
                                e.stopPropagation(); // Prevent li click
                                // Ideally, here you would make an API call to delete the recent search from the database
                                // For now, just remove it from the UI
                                li.remove();
                            });
                            recentSearchesList.appendChild(li);
                        });
                    } else {
                        recentSearchesList.innerHTML = '<p style="text-align: center; color: #888;">Belum ada pencarian terkini.</p>';
                    }
                } catch (error) {
                    console.error('Error fetching recent searches:', error);
                    recentSearchesList.innerHTML = '<p style="text-align: center; color: #888;">Gagal memuat pencarian terkini.</p>';
                }
            }
        });
    </script>
</body>
</html>
