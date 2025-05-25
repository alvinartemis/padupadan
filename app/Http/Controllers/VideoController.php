<?php

namespace App\Http\Controllers;

use App\Models\VideoFashion; // Gunakan model VideoFashion Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        // Mengambil semua video dari tabel videofashion, sekaligus memuat data user yang terkait
        // Karena model User Anda terdefinisi dengan baik, relasi 'user' ini akan bekerja
        $videos = VideoFashion::with('user')->get();

        $formattedVideos = $videos->map(function ($video) {
            // Untuk likes dan commentsCount, kita masih akan menggunakan dummy values
            // atau Anda bisa menambahkan kolom ini ke tabel videofashion/pengguna
            $likes = '199.7K';
            $commentsCount = 765;

            // Pastikan pathFile berisi path relatif dari folder public (misal: 'videos/nama_video.mp4')
            $videoSrc = Storage::url($video->pathFile);

            // Mengambil nama pengguna dari relasi user.
            // Gunakan $video->user->nama jika Anda ingin menampilkan nama lengkap.
            // Gunakan $video->user->username jika Anda ingin menampilkan username.
            $uploaderName = $video->user->nama ?? ($video->user->username ?? 'Unknown User');
            // Menambahkan ?? ($video->user->username ?? 'Unknown User')
            // untuk menangani kasus di mana 'nama' mungkin kosong, lalu fallback ke 'username',
            // dan jika keduanya kosong/user tidak ditemukan, fallback ke 'Unknown User'.

            return [
                'id' => $video->idVideoFashion,
                'src' => $videoSrc,
                'username' => $uploaderName, // Menggunakan nama dari tabel pengguna
                'description' => $video->deskripsi,
                'likes' => $likes,
                'commentsCount' => $commentsCount,
                'comments' => [], // Masih dummy untuk komentar
            ];
        });

        return response()->json($formattedVideos);
    }

    public function home()
    {
        return view('home');
    }
}
