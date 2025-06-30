<?php

namespace App\Http\Controllers;

use App\Models\VideoFashion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class VideoController extends Controller
{
    public function index()
    {
        $videos = VideoFashion::withCount('comments')
            ->with(['user', 'comments.user'])
            ->get();

        $bookmarkedVideoIds = [];
        if (Auth::check()) {
            // Ambil semua ID video yang sudah di-bookmark oleh user
            $bookmarkedVideoIds = Auth::user()->videoBookmarks()->pluck('idVideoFashion')->toArray();
        }

        $formattedVideos = $videos->map(function ($video) use ($bookmarkedVideoIds) { // <-- Tambahkan use
            // ... (kode map yang sudah ada)

            return [
                'id' => $video->idVideoFashion,
                'src' => Storage::url($video->pathFile),
                'username' => $video->user->nama ?? 'Unknown User',
                'description' => $video->deskripsi,
                'likes' => '199.7K', // Dummy
                'comments_count' => $video->comments_count,
                'is_bookmarked' => in_array($video->idVideoFashion, $bookmarkedVideoIds), // <-- TAMBAHKAN INI
                'comments' => $video->comments->map(function ($comment) {
                    // ... (kode comments map)
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
