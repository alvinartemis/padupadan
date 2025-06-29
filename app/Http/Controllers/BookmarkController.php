<?php

namespace App\Http\Controllers;

use App\Models\Lookbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{
    /**
     * Menampilkan halaman daftar lookbook yang sudah di-bookmark oleh user.
     *
     * @return \Illuminate\View\View
     */
    public function bookmark()
    {
        $user = Auth::user();
        $bookmarkedLookbooks = $user->wishlistItems()
                                 ->select('lookbook.*')
                                 ->orderBy('wishlistitem.tanggal_ditambahkan', 'desc')
                                 ->get();

        return view('settings.bookmark', compact('bookmarkedLookbooks'));
    }

    /**
     * Menangani toggle status bookmark untuk sebuah Lookbook.
     * PERBAIKAN: Nama method diganti menjadi 'toggleLookbookBookmark' agar
     * sesuai dengan error log, yang berarti ini adalah nama yang
     * dipanggil oleh file route Anda.
     *
     * @param  \App\Models\Lookbook  $lookbook
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleLookbookBookmark(Lookbook $lookbook)
    {
        $user = Auth::user();

        // Menggunakan query langsung untuk 100% menghindari ambiguitas.
        $isAlreadyBookmarked = DB::table('wishlistitem')
                                ->where('idPengguna', $user->id)
                                ->where('idLookbook', $lookbook->idLookbook)
                                ->exists();

        if ($isAlreadyBookmarked) {
            // Jika sudah ada, hapus.
            $user->wishlistItems()->detach($lookbook->idLookbook);
            $status = 'unbookmarked';
            $message = 'Item berhasil dihapus dari wishlist.';
        } else {
            // Jika belum ada, tambahkan.
            $user->wishlistItems()->attach($lookbook->idLookbook, ['tanggal_ditambahkan' => now()]);
            $status = 'bookmarked';
            $message = 'Item berhasil ditambahkan ke wishlist!';
        }

        return response()->json([
            'status' => 'success',
            'bookmark_status' => $status,
            'message' => $message
        ]);
    }
}
