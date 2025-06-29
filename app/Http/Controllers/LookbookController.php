<?php

namespace App\Http\Controllers;

use App\Models\Lookbook;
use App\Models\Stylist; // Pastikan model Stylist di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // <<< PERUBAHAN BARU

class LookbookController extends Controller
{
    // ... (method index, userIndex, create, store tidak berubah) ...

    /**
     * Menampilkan halaman lookbook untuk STYLIST yang sedang login.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $loggedInUser = Auth::user();
        $stylistProfile = Stylist::where('nama', $loggedInUser->nama)->first();

        if (!$stylistProfile) {
            $lookbooks = collect();
            return view('lookbook.lookbookstylist', compact('lookbooks'));
        }

        $lookbooks = Lookbook::where('idStylist', $stylistProfile->idStylist)
                             ->latest()
                             ->get();

        return view('lookbook.lookbookstylist', compact('lookbooks'));
    }

    /**
     * Menampilkan halaman lookbook untuk PENGGUNA BIASA dengan fitur pencarian.
     */
    public function userIndex(Request $request)
    {
        $search = $request->input('search');
        $query = Lookbook::query()->with('stylist'); // Eager load stylist

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kataKunci', 'like', '%' . $search . '%');
        }

        $lookbooks = $query->latest()->get();

        return view('lookbook.readlookbook', compact('lookbooks', 'search'));
    }

    /**
     * Menampilkan form untuk membuat lookbook baru.
     */
    public function create()
    {
        return view('lookbook.createlookbook');
    }

    /**
     * Menyimpan lookbook baru yang di-submit dari form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'kataKunci'   => 'nullable|string',
            'imgLookbook' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('imgLookbook')->store('lookbooks', 'public');
        $loggedInUser = Auth::user();
        $stylistProfile = Stylist::where('nama', $loggedInUser->nama)->first();

        if (!$stylistProfile) {
            return back()->with('error', 'Profil stylist dengan nama "' . $loggedInUser->nama . '" tidak ditemukan.')->withInput();
        }

        Lookbook::create([
            'idStylist'   => $stylistProfile->idStylist,
            'nama'        => $request->input('nama'),
            'description' => $request->input('description'),
            'kataKunci'   => $request->input('kataKunci'),
            'imgLookbook' => $imagePath,
        ]);

        return redirect()->route('lookbook.index')->with('success', 'Lookbook berhasil disimpan!');
    }

    /**
     * Menampilkan halaman detail untuk satu lookbook.
     */
    public function show(Lookbook $lookbook)
    {
        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = Auth::user()->wishlistItems()
                                ->where('wishlistitem.idLookbook', $lookbook->idLookbook)
                                ->exists();
        }
        $lookbook->load('stylist');
        return view('lookbook.detaillookbook', compact('lookbook', 'isBookmarked'));
    }

    /**
     * <<< METHOD BARU UNTUK SARAN PENCARIAN >>>
     * Merespons request AJAX dari frontend untuk memberikan saran tag.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTagSuggestions(Request $request)
    {
        $query = $request->input('query', '');

        // Jika query kosong, kembalikan array kosong
        if (empty($query)) {
            return response()->json([]);
        }

        // 1. Ambil semua nilai dari kolom 'kataKunci'
        $allKeywords = Lookbook::pluck('kataKunci')->toArray();

        // 2. Proses semua kata kunci menjadi satu array tag yang unik
        $tags = collect($allKeywords)
            ->flatMap(function ($keywordString) {
                // Pisahkan string berdasarkan koma, dan bersihkan spasi
                return array_map('trim', explode(',', $keywordString));
            })
            ->filter() // Hapus tag kosong
            ->unique(); // Hapus tag duplikat

        // 3. Filter tag yang dimulai dengan query dari pengguna
        $suggestions = $tags->filter(function ($tag) use ($query) {
            return Str::startsWith(strtolower($tag), strtolower($query));
        })->values(); // Ambil nilainya saja

        // 4. Kembalikan hasilnya sebagai JSON
        return response()->json($suggestions);
    }
}
