<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoFashion;
use App\Models\PencarianVideoFashion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Pastikan ini diimpor

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = $request->input('query');
        $videos = collect();

        if ($query) {
            $videos = VideoFashion::where('deskripsi', 'like', '%' . $query . '%')
                                 ->orWhere('tag', 'like', '%' . $query . '%')
                                 ->with('user')
                                 ->get();

            if (Auth::check()) {
                PencarianVideoFashion::create([
                    'idPengguna' => Auth::id(),
                    'kataKunci' => $query,
                ]);
            }
        }

        // Format ulang data video untuk tampilan
        $formattedVideos = $videos->map(function ($video) {
            $uploaderName = $video->user->nama ?? ($video->user->username ?? 'Unknown User');

            return [
                'id' => $video->idVideoFashion,
                'pathFile' => $video->pathFile, // Pastikan pathFile disertakan
                'mimeType' => $video->mimeType, // Pastikan mimeType disertakan
                'username' => $uploaderName,
                'description' => $video->deskripsi,
                'tag' => $video->tag,
                // Tidak perlu lagi 'thumbnail' karena akan ditangani di Blade
            ];
        });

        return view('search_results', [
            'query' => $query,
            'videos' => $formattedVideos,
        ]);
    }

    public function getRecentSearches(Request $request)
    {
        if (Auth::check()) {
            $recentSearches = PencarianVideoFashion::where('idPengguna', Auth::id())
                                                    ->distinct('kataKunci')
                                                    ->orderByDesc('idPencarian')
                                                    ->limit(5)
                                                    ->pluck('kataKunci');
            return response()->json($recentSearches);
        }

        return response()->json([]);
    }
}
