<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoFashion;
use App\Models\PencarianVideoFashion; // Pastikan model ini sudah dibuat
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan pengguna terautentikasi
    }

    /**
     * Menampilkan halaman hasil pencarian.
     * Menerima query pencarian dari request.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $videos = collect(); // Inisialisasi koleksi kosong

        if ($query) {
            // Cari video berdasarkan deskripsi atau tag
            $videos = VideoFashion::where('deskripsi', 'like', '%' . $query . '%')
                                  ->orWhere('tag', 'like', '%' . $query . '%')
                                  ->with('user') // Load relasi user untuk mendapatkan username
                                  ->get();

            // Simpan kata kunci pencarian ke tabel pencarianvideofashion
            if (Auth::check()) {
                PencarianVideoFashion::create([
                    'idPengguna' => Auth::id(),
                    'kataKunci' => $query,
                    // 'waktuPencarian' => now(), // Jika ada kolom waktuPencarian
                ]);
            }
        }

        // Format ulang data video untuk tampilan
        $formattedVideos = $videos->map(function ($video) {
            $videoSrc = Storage::url($video->pathFile);
            $uploaderName = $video->user->nama ?? ($video->user->username ?? 'Unknown User');
            $thumbnailPath = str_replace(pathinfo($video->pathFile, PATHINFO_EXTENSION), 'png', $video->pathFile); // Asumsi thumbnail .png dengan nama yang sama

            return [
                'id' => $video->idVideoFashion,
                'src' => $videoSrc,
                'thumbnail' => Storage::url($thumbnailPath), // Path thumbnail
                'username' => $uploaderName,
                'description' => $video->deskripsi,
                'tag' => $video->tag,
                // Anda bisa menambahkan 'likes' atau 'comments_count' di sini jika ingin menampilkannya di hasil pencarian
            ];
        });

        return view('search_results', [
            'query' => $query,
            'videos' => $formattedVideos,
        ]);
    }

    /**
     * Mengambil daftar pencarian terkini untuk user yang sedang login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentSearches(Request $request)
    {
        if (Auth::check()) {
            $recentSearches = PencarianVideoFashion::where('idPengguna', Auth::id())
                                                    ->distinct('kataKunci') // Ambil kata kunci unik
                                                    ->orderByDesc('idPencarian') // Urutkan berdasarkan yang terbaru
                                                    ->limit(5) // Ambil 5 pencarian terakhir
                                                    ->pluck('kataKunci'); // Hanya ambil kolom kataKunci
            return response()->json($recentSearches);
        }

        return response()->json([]);
    }
}
