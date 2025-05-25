<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment; // Import model Comment
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang sedang login

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input dari frontend
        $request->validate([
            'video_id' => 'required|exists:videofashion,idVideoFashion',
            'comment' => 'required|string|max:1000', // Sesuaikan max length komentar
        ]);

        // Pastikan user sudah login sebelum bisa berkomentar
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Please log in.'], 401);
        }

        try {
            $comment = new Comment();
            $comment->idVideoFashion = $request->video_id;
            $comment->idPengguna = Auth::id(); // Ambil ID pengguna yang sedang login
            $comment->tanggalKomentar = now(); // Timestamp saat ini
            $comment->isiKomentar = $request->comment; // <<< SESUAIKAN DENGAN NAMA KOLOM ISI KOMENTAR
            $comment->save();

            // Opsional: Jika Anda ingin mengembalikan komentar yang baru ditambahkan
            // beserta detail user-nya, Anda bisa memuat ulang komentar tersebut.
            // $newComment = Comment::with('user')->find($comment->idKomentar);
            // return response()->json([
            //     'success' => true,
            //     'message' => 'Comment added successfully!',
            //     'comment' => [
            //         'id' => $newComment->idKomentar,
            //         'author' => $newComment->user ? $newComment->user->name : 'Anonim',
            //         'avatar' => $newComment->user ? $newComment->user->avatar_url : 'https://i.imgur.com/S2i43eS.jpg',
            //         'text' => $newComment->tanggaLKomenta,
            //         'time' => $newComment->tanggalKomentar,
            //     ]
            // ]);

            return response()->json(['success' => true, 'message' => 'Comment added successfully!']);

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error adding comment: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add comment. Please try again.'], 500);
        }
    }
}
