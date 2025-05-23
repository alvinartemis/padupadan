<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan ini menunjuk ke model pengguna Anda (misal App\Models\User)
use App\Models\VideoFashion; // Pastikan ini menunjuk ke model video Anda

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Halaman profil membutuhkan pengguna yang terautentikasi
    }

    public function index()
    {
        $user = Auth::user(); // Ambil objek pengguna yang sedang login

        if (!$user) {
            // Seharusnya tidak terjadi karena ada middleware 'auth', tapi sebagai fallback
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat profil.');
        }

        // Ambil data profil dari objek $user
        $profileData = [
            'nama' => $user->nama ?? 'Nama Lengkap', // Mengambil 'nama' dari DB
            'username' => $user->username ?? 'username_anda', // Mengambil 'username' dari DB
            'bio' => $user->bio ?? 'Tulis bio Anda di sini...', // Asumsi ada kolom 'bio' di tabel 'pengguna'
            'avatar_url' => $user->profilepicture ?? 'https://i.imgur.com/S2i43eS.jpg', // Asumsi ada kolom 'profilepicture' di tabel 'pengguna'
        ];

        // Untuk daftar video, ambil semua postingan user ini
        $posts = VideoFashion::where('idPengguna', $user->idPengguna)->get();

        // Statistik tidak akan digunakan di view, tapi tetap bisa dihitung jika perlu
        // $stats = [
        //     'posts_count' => $posts->count(),
        //     'followers_count' => '0',
        //     'following_count' => '0',
        // ];

        // Hapus $stats dari compact jika tidak akan digunakan di view
        return view('profile', compact('profileData', 'posts'));
    }
}
