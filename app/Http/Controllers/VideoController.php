<?php

namespace App\Http\Controllers;

use App\Models\VideoFashion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Pastikan ini di-import

class VideoController extends Controller
{
    public function index()
    {
        $videos = VideoFashion::withCount('comments')
            ->with(['user', 'comments.user'])
            ->get();

        $formattedVideos = $videos->map(function ($video) {
            $likes = '199.7K'; // Masih dummy

            $videoSrc = Storage::url($video->pathFile);

            $uploaderName = $video->user->nama ?? ($video->user->username ?? 'Unknown User');

            return [
                'id' => $video->idVideoFashion,
                'src' => $videoSrc,
                'username' => $uploaderName,
                'description' => $video->deskripsi,
                'likes' => $likes,
                'comments_count' => $video->comments_count,
                'comments' => $video->comments->map(function ($comment) {
                    // Pastikan $comment->user tidak null sebelum mengakses propertinya
                    $commentAuthor = $comment->user ? ($comment->user->nama ?? $comment->user->username) : 'Anonim';

                    // Pastikan profilepicture ada dan bentuknya URL yang benar
                    $commentAvatar = 'https://i.imgur.com/S2i43eS.jpg'; // Fallback default
                    if ($comment->user && !empty($comment->user->profilepicture)) {
                        // Asumsi profilepicture menyimpan path relatif dari storage/app/public
                        $commentAvatar = Storage::url($comment->user->profilepicture);
                    }

                    return [
                        'id' => $comment->idKomentar,
                        'author' => $commentAuthor, // <<< PERBAIKAN DI SINI
                        'avatar' => $commentAvatar, // <<< PERBAIKAN DI SINI
                        'text' => $comment->isiKomentar, // Pastikan ini nama kolom teks komentar yang benar
                        'time' => $comment->tanggalKomentar,
                    ];
                })->sortByDesc('time')->values()->all()
            ];
        });

        return response()->json($formattedVideos);
    }

    public function home()
    {
        return view('home');
    }
}
