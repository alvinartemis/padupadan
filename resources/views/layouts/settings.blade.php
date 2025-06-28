template

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Padu Padan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            align-items: flex-start; /* Membuat item rata kiri */
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

        .sidebar .search-box svg {
            width: 18px;
            height: 18px;
            color: #888;
        }

        .sidebar .search-box input {
            border: none;
            background: transparent;
            outline: none;
            flex-grow: 1;
            font-size: 14px;
            margin-left: 10px;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%; /* Memastikan nav ul mengisi lebar sidebar */
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
            width: 100%; /* Memastikan link mengisi lebar nav item */
            box-sizing: border-box; /* Memastikan padding tidak menambah lebar */
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li a.active {
            color: #007bff;
            /background-color: #e0f7fa; / Warna hover/active disesuaikan agar terlihat */
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

        .content-area {
            flex-grow: 1;
            padding: 20px;
            margin-left: 250px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
        }
    /* Styling untuk Judul Bookmark */
    .bookmark-header {
        position: relative;
        text-align: center;
        margin-bottom: 30px; /* Memberi sedikit ruang di bawah judul */
    }

    .bookmark-header h1 {
        color: black;
        font-size: 32px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        line-height: 33.60px;
        margin-top: 0;
        margin-bottom: 0;
    }

    /* Styling untuk Toggle Button */
    #bookmark-toggle {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #E1E1E1;
        box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25) inset;
        border-radius: 38px;
        width: 331px; /* Sesuai lebar dari desain Figma */
        height: 47px;
        margin: 0 auto 30px auto; /* Tengahkan dan beri margin bawah */
        position: relative; /* Penting untuk posisi absolut inner element */
    }

    .toggle-button {
        flex-grow: 1; /* Agar tombol mengisi ruang yang sama */
        padding: 10px 0; /* Padding vertikal, horizontal akan menyesuaikan */
        border: none;
        background-color: transparent;
        color: #939393;
        font-size: 16px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        line-height: 16px;
        cursor: pointer;
        transition: color 0.3s ease;
        text-align: center;
        z-index: 2; /* Pastikan teks di atas latar belakang biru */
    }

    /* Latar belakang biru yang bergerak */
    .toggle-background {
        position: absolute;
        top: 0;
        height: 100%;
        background: var(--Indigo-dye, #173F63);
        box-shadow: 0px 0px 2.299999952316284px rgba(0, 0, 0, 0.25);
        border-radius: 43px; /* Sedikit lebih membulat */
        transition: left 0.3s ease, width 0.3s ease; /* Transisi untuk pergerakan dan ukuran */
        z-index: 1; /* Di bawah teks tombol */
    }

    /* Warna teks untuk tombol aktif */
    .toggle-button.active {
        color: white;
        font-weight: 700; /* Tebal saat aktif */
        font-size: 17px; /* Sedikit lebih besar saat aktif */
        line-height: 17px;
    }


    /* Basic styling for content sections */
    .content-section {
        display: none; /* Hidden by default */
        padding: 20px 0;
    }

    .content-section.active {
        display: block; /* Show when active */
    }

    .fashion-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); /* Responsive grid */
        gap: 20px;
        padding: 0 20px; /* Padding samping untuk grid */
    }

    .fashion-item {
        background-color: #f9f9f9;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: center;
    }

    .fashion-item img {
        width: 100%;
        height: auto;
        display: block;
        border-bottom: 1px solid #eee; /* Sedikit pemisah visual */
    }

    .video-placeholder {
        text-align: center;
        padding: 40px;
        background-color: #f2f2f2;
        border-radius: 10px;
        margin: 20px auto;
        max-width: 600px;
        color: #555;
    }

    .video-placeholder p {
        font-size: 18px;
        font-family: 'Montserrat', sans-serif;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 400px) {
        #bookmark-toggle {
            width: 90%; /* Buat toggle lebih responsif */
        }

        .toggle-button {
            font-size: 14px;
        }

        .toggle-button.active {
            font-size: 15px;
        }
    }
    /* Import Font Montserrat jika belum diimpor secara global */
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap');

    /* Kontainer utama untuk detail item, ini akan berada di dalam .main-content dari setting.blade.php */
    .item-detail-container {
        display: flex;
        flex-direction: column; /* Default ke kolom untuk mobile */
        align-items: flex-start; /* Sejajarkan item ke kiri */
        padding: 20px;
        /* max-width: 900px;  Ini akan diatur oleh lebar main-content, bisa dihilangkan atau disesuaikan */
        margin: 40px auto; /* Tengahkan container dalam main-content */
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        position: relative; /* Untuk tombol close */
    }

    .close-button {
        position: absolute;
        top: 15px;
        left: 15px;
        background: none;
        border: none;
        font-size: 30px;
        cursor: pointer;
        color: #333;
        z-index: 10;
        padding: 0;
        line-height: 1;
    }

    .item-image-wrapper {
        flex: 1;
        width: 100%; /* Lebar penuh di mobile */
        padding: 20px;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .item-image-wrapper img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .item-info {
        flex: 1;
        width: 100%; /* Lebar penuh di mobile */
        padding: 20px;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
    }

    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }

    .user-info span {
        font-weight: 600;
        color: #333;
    }

    .bookmark-icon {
        color: orange;
        margin-left: auto;
        font-size: 24px;
    }

    .item-title {
        font-size: 28px;
        font-weight: 700;
        color: #173F63; /* Warna Indigo Dye */
        margin-bottom: 15px;
    }

    .item-description {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 20px;
    }

    .item-hashtags span {
        display: inline-block;
        background-color: #e0e0e0;
        color: #666;
        padding: 5px 10px;
        border-radius: 5px;
        margin-right: 8px;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 500;
    }

    .continue-shopping-button {
        display: block;
        width: 100%;
        padding: 12px 25px;
        background-color: #FFC107;
        color: #333;
        border: none;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: 30px;
    }

    .continue-shopping-button:hover {
        background-color: #e0a800;
        transform: translateY(-2px);
    }

    /* Media queries for larger screens */
    @media (min-width: 768px) {
        .item-detail-container {
            flex-direction: row;
            align-items: stretch;
        }
        .item-image-wrapper {
            padding: 30px;
        }
        .item-info {
            padding: 30px;
        }
        .continue-shopping-button {
            width: auto;
            align-self: flex-start;
        }
    }
/* --- Styling untuk Pop-up Logout --- */
        .logout-popup-overlay {
            position: fixed; /* Penting: agar menutupi seluruh viewport */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Overlay gelap transparan */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999; /* Pastikan di atas semua elemen */
            visibility: hidden; /* Sembunyikan secara default */
            opacity: 0;
            transition: visibility 0s, opacity 0.3s ease;
        }

        .logout-popup-overlay.show {
            visibility: visible;
            opacity: 1;
        }

        .logout-popup-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 90%;
            max-width: 350px; /* Lebar maksimum pop-up */
            transform: translateY(-20px); /* Efek masuk sedikit dari atas */
            transition: transform 0.3s ease;
        }

        .logout-popup-overlay.show .logout-popup-content {
            transform: translateY(0);
        }

        .logout-popup-content p {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }

        .logout-popup-buttons {
            display: flex;
            flex-direction: column; /* Tombol vertikal di mobile */
            gap: 15px; /* Jarak antar tombol */
        }

        .logout-popup-buttons button {
            padding: 12px 25px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .logout-popup-buttons button.yes {
            background-color: #FFC107; /* Warna kuning seperti tombol Continue Shopping */
            color: #333;
        }
        .logout-popup-buttons button.yes:hover {
            background-color: #e0a800;
        }

        .logout-popup-buttons button.no {
            background-color: #e0e0e0; /* Warna abu-abu netral */
            color: #555;
        }
        .logout-popup-buttons button.no:hover {
            background-color: #cccccc;
        }

        /* Media query untuk tombol horizontal di desktop */
        @media (min-width: 576px) {
            .logout-popup-buttons {
                flex-direction: row;
                justify-content: center;
            }
            .logout-popup-buttons button {
                flex: 1; /* Agar tombol mengisi ruang yang sama */
            }
        }

        .logout-button {
        display: flex;
        align-items: center;
        width: 100%; /* Agar tombol selebar container */
        padding: 10px 15px; /* Sesuaikan padding agar mirip dengan item lainnya */
        border: none;
        background: none;
        color: inherit; /* Menggunakan warna teks dari parent */
        font-family: inherit; /* Menggunakan font dari parent */
        font-size: inherit; /* Menggunakan ukuran font dari parent */
        text-align: left;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Efek transisi saat hover */
        color: #dc3545; /* Warna teks merah (contoh warna merah Bootstrap 'danger') */
    }

    .logout-button:hover {
        background-color: #f0f0f0; /* Warna latar belakang saat di-hover */
        /* Sesuaikan dengan warna hover yang Anda gunakan untuk item sidebar lainnya */
    }

    .logout-button svg {
        width: 24px; /* Sesuaikan ukuran ikon */
        height: 24px; /* Sesuaikan ukuran ikon */
        margin-right: 10px; /* Jarak antara ikon dan teks */
        color: inherit; /* Menggunakan warna ikon dari parent */
    }

    /* CSS untuk Modal Pop-up */
    .modal-overlay {
        display: none; /* Sembunyikan secara default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.6); /* Latar belakang semi-transparan */
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 400px; /* Lebar maksimum pop-up */
        text-align: center;
    }

    .modal-content h2 {
        margin-top: 0;
        margin-bottom: 25px;
        font-size: 1.4rem;
        color: #333;
    }

    .modal-buttons button {
        padding: 12px 25px;
        margin: 0 10px;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        min-width: 100px;
    }

    .modal-buttons button:hover {
        transform: translateY(-2px);
    }

    #confirmYesBtn {
        background-color: #F4BC43; /* Warna kuning seperti di gambar */
        color: white;
    }

    #confirmYesBtn:hover {
        background-color: #e0a830;
    }

    #confirmNoBtn {
        background-color: #E9E9E9; /* Warna abu-abu muda */
        color: #333;
    }

    #confirmNoBtn:hover {
        background-color: #dcdcdc;
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logoy.png') }}" alt="Logo Padu Padan">
        </div>
        <div style="display: flex; align-items: center; gap: 50px; color: black; font-size: 20px; font-weight: 700; line-height: 33.6px; margin-bottom: 30px;">
            <a href="{{ route('profile') }}" style="background: none; border: none; cursor: pointer; padding: 0; display: inline-flex; align-items: center; justify-content: center; line-height: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="black">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
            <span>Settings</span>
        </div>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('bookmark') }}" class="{{ request()->routeIs('bookmark') ? 'active' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Bookmark
                    </a>
                </li>
                <li>
                    @csrf
                        <button type="submit" class="logout-button" id="logoutButton">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 22H5a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h5"></path>
                            <polyline points="17 16 21 12 17 8"></polyline>
                            <line x1="21" y1="12" x2="7" y2="12"></line>
                        </svg>
                            Logout
                        </button>
                </li>
            </ul>
        </nav>
    </div>

    <div class="content-area">
        <div class="content">
            @yield('content')
        </div>
    </div>
{{-- Struktur Pop-up Logout --}}
    <div id="logoutPopupOverlay" class="logout-popup-overlay">
        <div class="logout-popup-content">
            <p>Are you sure you want to log out?</p>
            <div class="logout-popup-buttons">
                <button class="yes" id="confirmLogoutButton">Yes</button>
                <button class="no" id="cancelLogoutButton">No</button>
            </div>
        </div>
    </div>

{{-- Form Logout Tersembunyi (INI PENTING: harus di luar tombol pemicu) --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButton = document.getElementById('logoutButton');
            const logoutPopupOverlay = document.getElementById('logoutPopupOverlay');
            const confirmLogoutButton = document.getElementById('confirmLogoutButton');
            const cancelLogoutButton = document.getElementById('cancelLogoutButton');

            // Cek apakah elemen-elemen ditemukan sebelum menambahkan event listener
            if (logoutButton && logoutPopupOverlay && confirmLogoutButton && cancelLogoutButton) {
                // Menampilkan pop-up saat tombol Logout diklik
                logoutButton.addEventListener('click', function() {
                    logoutPopupOverlay.classList.add('show');
                });

                // Menyembunyikan pop-up saat tombol 'No' diklik
                cancelLogoutButton.addEventListener('click', function() {
                    logoutPopupOverlay.classList.remove('show');
                });

                // Menyembunyikan pop-up jika mengklik di luar area pop-up (overlay)
                logoutPopupOverlay.addEventListener('click', function(event) {
                    if (event.target === logoutPopupOverlay) {
                        logoutPopupOverlay.classList.remove('show');
                    }
                });

                // Menangani aksi logout saat tombol 'Yes' diklik
                confirmLogoutButton.addEventListener('click', function() {
                    // Ini akan memicu pengiriman form logout
                    document.getElementById('logout-form').submit();
                });
            } else {
                console.error('Salah satu elemen pop-up logout tidak ditemukan di DOM.');
                // Anda bisa menambahkan fallback atau pesan error lain di sini
            }
        });
    </script>
</body>
</html>
