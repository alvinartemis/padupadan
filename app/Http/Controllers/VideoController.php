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
            $bookmarkedVideoIds = Auth::user()->videoBookmarks()->pluck('idVideoFashion')->toArray();
        }

        $formattedVideos = $videos->map(function ($video) use ($bookmarkedVideoIds) {
            return [
                'id' => $video->idVideoFashion,
                'src' => Storage::url($video->pathFile),
                'username' => $video->user->nama ?? 'Unknown User',
                'profile_picture' => $video->user->profilepicture ? Storage::url($video->user->profilepicture) : asset('images/default_avatar.jpg'), // Added this line
                'description' => $video->deskripsi,
                'likes' => '199.7K', // Dummy
                'comments_count' => $video->comments_count,
                'is_bookmarked' => in_array($video->idVideoFashion, $bookmarkedVideoIds),
                'outfit_link' => $video->outfitLink,
                'comments' => $video->comments->map(function ($comment) {
                    return [
                        'id' => $comment->idKomentar,
                        'author' => $comment->user->nama ?? 'Anonim',
                        'avatar' => $comment->user->profilepicture ? Storage::url($comment->user->profilepicture) : asset('images/default_avatar.jpg'),
                        'text' => $comment->isiKomentar,
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
