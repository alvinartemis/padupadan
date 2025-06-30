PaduPadan adalah sebuah platform web inovatif yang menghubungkan pecinta fashion dengan para stylist profesional. Aplikasi ini dirancang untuk membantu pengguna menemukan inspirasi gaya, mengelola lemari pakaian digital mereka, dan berinteraksi langsung dengan stylist untuk mendapatkan saran personal. Bagi stylist, PaduPadan menyediakan alat untuk menampilkan karya mereka melalui lookbook dan berinteraksi dengan klien.

## Fitur Utama

### Untuk Pengguna:

  * Feeds Video Fashion: Jelajahi berbagai video fashion inspiratif yang diunggah oleh pengguna dan stylist.
  * Komentar Video: Berinteraksi dengan konten video melalui sistem komentar.
  * Lookbook: Telusuri koleksi lookbook yang dibuat oleh stylist untuk mendapatkan ide gaya.
  * Bookmark / Wishlist: Simpan video dan lookbook favorit Anda ke daftar bookmark pribadi.
  * Digital Wardrobe: Kelola dan atur koleksi pakaian Anda secara digital.
  * Chat dengan Stylist: Berkomunikasi langsung dan dapatkan saran personal dari stylist.
  * Profil Pengguna: Kelola informasi profil, gambar avatar, dan preferensi gaya.
  * Pencarian Lanjutan: Cari video, lookbook, dan stylist berdasarkan kata kunci atau tag.
  * Pengaturan Preferensi: Sesuaikan preferensi gaya untuk mendapatkan rekomendasi yang lebih relevan.

### Untuk Stylist:

  * Dashboard Stylist: Tampilan khusus untuk stylist dengan akses ke fitur-fitur manajemen.
  * Manajemen Lookbook: Buat, edit, dan kelola lookbook untuk menampilkan karya Anda.
  * Interaksi dengan Klien: Berkomunikasi dengan pengguna melalui fitur chat.
  * Profil Stylist: Kelola informasi profil stylist Anda.

## Teknologi yang Digunakan

  * Backend Framework: Laravel 10.x (PHP)
  * Database: MySQL
  * Frontend Tools: Vite, JavaScript, CSS (dengan Tailwind CSS)
  * Templating Engine: Blade
  * Penyimpanan File: Laravel Storage 

## Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek PaduPadan di lingkungan lokal Anda.

1.  Clone Repositori:

    bash
    git clone https://github.com/alvinartemis/padupadan.git
    cd padupadan
    

2.  Instal Dependensi Composer:

    bash
    composer install
    

3.  Instal Dependensi Node.js & NPM:

    bash
    npm install
    

4.  *Konfigurasi Environment (.env):*

      * Salin file .env.example menjadi .env:
        bash
        cp .env.example .env
        
      * Buka file .env dan konfigurasikan detail database Anda (DB\_DATABASE, DB\_USERNAME, DB\_PASSWORD).
      * Buat database baru di MySQL Anda dengan nama yang sama seperti yang Anda konfigurasikan di .env (misal: padupadan).
      * Generate kunci aplikasi:
        bash
        php artisan key:generate
        

5.  Jalankan Migrasi Database:

      * Pastikan database Anda kosong atau Anda ingin menghapus semua data yang ada (hanya untuk pengembangan).
      * Jalankan migrasi untuk membuat tabel-tabel database:
        bash
        php artisan migrate
        
      * Jika Anda memiliki seeder data, Anda dapat menjalankannya juga:
        bash
        php artisan db:seed
       

6.  Buat Symlink Penyimpanan Publik:

    bash
    php artisan storage:link

8.  Jalankan Aplikasi Laravel:

    bash
    php artisan serve
    

Aplikasi akan berjalan di http://127.0.0.1:8000 (atau port lain yang ditampilkan).

## Penggunaan

  * Akses aplikasi di http://127.0.0.1:8000.
  * Register sebagai pengguna baru atau Login jika sudah memiliki akun.
  * Untuk login stylist, akses http://127.0.0.1:8000/stylist/login dengan credentials ada pada database
  * Jelajahi berbagai fitur yang tersedia seperti melihat video, lookbook, mengelola digital wardrobe, atau chat.

## Kontribusi

Kami menyambut kontribusi\! Jika Anda ingin berkontribusi pada proyek ini, silakan ikuti langkah-langkah berikut:

1.  Fork repositori ini.
2.  Buat branch baru (git checkout -b feature/nama-fitur-anda).
3.  Lakukan perubahan dan commit (git commit -m 'Tambahkan fitur baru').
4.  Push ke branch (git push origin feature/nama-fitur-anda).
5.  Buat Pull Request baru.

## Lisensi

Proyek ini dilisensikan di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).
