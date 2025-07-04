<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Penting: Import fasad Storage
use App\Models\User;
use App\Models\VideoFashion;

class ProfileController extends Controller
{
    /**
     * Konstruktor untuk mengaplikasikan middleware 'auth'.
     * Pastikan pengguna sudah login sebelum mengakses method di controller ini.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan halaman profil pengguna.
     * Mengambil data pengguna yang sedang login dan postingan terkait.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Mendapatkan instance pengguna yang sedang login
        $user = Auth::user();

        // Jika user tidak ditemukan (meskipun ada middleware 'auth', ini sebagai fallback/safety check)
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat profil.');
        }

        // Siapkan data profil yang akan dikirim ke view
        $profileData = [
            'nama' => $user->nama ?? 'Nama Lengkap',
            'username' => $user->username ?? 'username_anda',
            'bodytype' => $user->bodytype ?? 'N/A',
            'skintone' => $user->skintone ?? 'N/A',
            'style' => $user->style ?? 'N/A',
            'profilepicture' => $user->profilepicture,
        ];

        // Mengambil semua postingan (VideoFashion) yang dibuat oleh pengguna ini
        $posts = VideoFashion::where('idPengguna', $user->idPengguna)->get();

        // Mengirimkan data profil dan postingan ke view 'profile'
        return view('profile', compact('profileData', 'posts'));
    }

}
