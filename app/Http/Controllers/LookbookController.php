<?php

namespace App\Http\Controllers;

use App\Models\Lookbook;
use App\Models\Stylist; // Pastikan model Stylist di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LookbookController extends Controller
{
    /**
     * Menampilkan halaman lookbook untuk STYLIST yang sedang login.
     * Mengambil lookbook yang dibuat oleh stylist itu sendiri.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Pastikan stylist sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // --- PERBAIKAN ---
        // Logika untuk mengambil ID stylist disamakan dengan method store()
        $loggedInUser = Auth::user();
        $stylistProfile = Stylist::where('nama', $loggedInUser->nama)->first();

        // Jika profil stylist tidak ditemukan, tampilkan halaman dengan data kosong.
        if (!$stylistProfile) {
            $lookbooks = collect(); // Membuat koleksi kosong
            return view('lookbook.lookbookstylist', compact('lookbooks'));
        }

        // Gunakan ID dari profil stylist yang benar untuk mencari lookbook.
        $lookbooks = Lookbook::where('idStylist', $stylistProfile->idStylist)
                             ->latest()
                             ->get();

        // Mengirim data ke view khusus untuk stylist
        return view('lookbook.lookbookstylist', compact('lookbooks'));
    }

    /**
     * Menampilkan halaman lookbook untuk PENGGUNA BIASA.
     *
     * @return \Illuminate\View\View
     */
    public function userIndex()
    {
        // Ambil semua lookbook dari semua stylist
        $lookbooks = Lookbook::latest()->get();

        return view('lookbook.readlookbook', compact('lookbooks'));
    }

    /**
     * Menampilkan form untuk membuat lookbook baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('lookbook.createlookbook');
    }

    /**
     * Menyimpan lookbook baru yang di-submit dari form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Menggunakan Route Model Binding untuk keamanan dan kemudahan.
     *
     * @param  \App\Models\Lookbook  $lookbook
     * @return \Illuminate\View\View
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
}
