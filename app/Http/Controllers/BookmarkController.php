<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Lookbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\VideoFashion;
use App\Models\VideoBookmark;

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

        // Ambil Lookbook yang di-bookmark
        $bookmarkedLookbooks = $user->wishlistItems()
                                ->select('lookbook.*')
                                ->orderBy('wishlistitem.tanggal_ditambahkan', 'desc')
                                ->get();

        // Ambil Video yang di-bookmark
        $bookmarkedVideos = VideoBookmark::where('idPengguna', $user->idPengguna)
                                        ->with('video.user') // Eager load relasi video dan user dari video
                                        ->latest() // Urutkan berdasarkan timestamp pembuatan bookmark
                                        ->get();

        return view('settings.bookmark', compact('bookmarkedLookbooks', 'bookmarkedVideos'));
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

        // Log::info('Debug User Object for Lookbook Bookmark', [
        //     'auth_check' => Auth::check(), // Cek apakah user sedang login
        //     'user_id' => $user->id ?? 'NULL_from_id', // Nilai properti 'id'
        //     'user_idPengguna' => $user->idPengguna ?? 'NULL_from_idPengguna', // Nilai properti 'idPengguna'
        //     'user_class' => get_class($user), // Kelas dari objek user
        //     'user_is_null' => ($user === null), // Cek apakah objek user benar-benar NULL
        //     'user_attributes' => $user ? $user->getAttributes() : 'NULL_attributes', // Semua atribut user
        //     'lookbook_id' => $lookbook->idLookbook,
        // ]);

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

    /* @param  \App\Models\VideoFashion  $video
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleVideoBookmark(VideoFashion $video)
    {
        $user = Auth::user();

        $bookmark = VideoBookmark::where('idPengguna', $user->idPengguna)
                                 ->where('idVideoFashion', $video->idVideoFashion)
                                 ->first();

        if ($bookmark) {
            // Jika sudah ada, hapus.
            $bookmark->delete();
            $status = 'unbookmarked';
            $message = 'Video berhasil dihapus dari markah.';
        } else {
            // Jika belum ada, tambahkan.
            VideoBookmark::create([
                'idPengguna' => $user->idPengguna,
                'idVideoFashion' => $video->idVideoFashion,
            ]);
            $status = 'bookmarked';
            $message = 'Video berhasil ditambahkan ke markah!';
        }

        return response()->json([
            'status' => 'success',
            'bookmark_status' => $status,
            'message' => $message
        ]);
    }
}
