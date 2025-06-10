<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lookbook;
use Illuminate\Support\Facades\Storage;

class LookbookController extends Controller
{
    // Tentukan ID Stylist yang akan digunakan untuk semua lookbook ini
    private $fixedStylistId = 1; // <-- Ini harus SAMA DENGAN ID YANG ADA DI TABEL STYLIST ANDA (misal, 1)

    // Method untuk Stylist (tampilkan daftar lookbook stylist)
    public function index() // Ini sekarang adalah lookbookstylist.blade.php
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil lookbook yang dibuat oleh stylist dengan ID $fixedStylistId
        // Ini berarti halaman stylist HANYA akan menampilkan lookbook dari stylist ID 1.
        $lookbooks = Lookbook::where('idStylist', $this->fixedStylistId) // <-- Menggunakan fixed ID
                             ->orderBy('created_at', 'desc') // Tetap pakai ini jika sudah tambahkan timestamps
                             ->get();

        // Path view: 'lookbook.lookbookstylist' (sesuai yang Anda sebutkan)
        return view('lookbook.lookbookstylist', compact('lookbooks'));
    }

    // Method untuk Pengguna Biasa (tampilkan semua lookbook dari semua stylist)
    public function userIndex() // Ini adalah readlookbook.blade.php Anda
    {
        // Ambil semua lookbook dari semua stylist, diurutkan
        $lookbooks = Lookbook::orderBy('created_at', 'desc')->get(); // Tetap pakai ini jika sudah tambahkan timestamps

        return view('lookbook.readlookbook', compact('lookbooks'));
    }

    // Method untuk menampilkan form create lookbook (createlookbook.blade.php)
    public function create()
    {
        // Pastikan pengguna login bisa mengakses form ini
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk membuat lookbook.');
        }
        // Path view: 'lookbook.createlookbook' (sesuai yang Anda sebutkan)
        return view('lookbook.createlookbook');
    }

    // Method untuk menyimpan lookbook yang diupload
    public function store(Request $request)
    {
        // Pastikan pengguna login bisa menyimpan
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk menyimpan lookbook.');
        }

        $request->validate([
            'nama'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'kataKunci'       => 'nullable|string',
            'imgLookbook'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('imgLookbook')) {
            $imagePath = $request->file('imgLookbook')->store('lookbooks', 'public');
        }

        Lookbook::create([
            'idStylist'   => $this->fixedStylistId, // <-- PERBAIKAN: Gunakan fixed ID 1 yang ada di tabel stylist
            'nama'        => $request->input('nama'),
            'description' => $request->input('description'),
            'kataKunci'   => $request->input('kataKunci'),
            'imgLookbook' => $imagePath,
        ]);

        return redirect()->route('lookbook.index')->with('success', 'Lookbook berhasil disimpan!');
    }
}
