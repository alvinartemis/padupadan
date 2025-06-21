<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VideoFashion;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat profil.');
        }

        $profileData = [
            'nama' => $user->nama ?? 'Nama Lengkap',
            'username' => $user->username ?? 'username_anda',
            'bio' => $user->bio ?? 'Tulis bio Anda di sini...',
            'avatar_url' => $user->profilepicture ?? 'https://i.imgur.com/S2i43eS.jpg',
        ];

        $posts = VideoFashion::where('idPengguna', $user->idPengguna)->get();

        return view('profile', compact('profileData', 'posts'));
    }

}
