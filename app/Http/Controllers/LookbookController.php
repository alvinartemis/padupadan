<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\KoleksiPakaian;


class LookbookController extends Controller
{
    public function create()
    {
    //     if (!auth()->check()) {
    //     return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
    // }
        // $items = KoleksiPakaian::where('idPengguna', auth()->id())->get();
        // ambil data user yg login
        return view('createlookbook');

    }


    public function store(Request $request)
    {
        $selectedItems = json_decode($request->input('selected_items'), true);

        // Simpan ke database (nanti kita buat model/migration-nya)
        foreach ($selectedItems as $item) {
            \App\Models\LookbookItem::create([
                'idPengguna' => auth()->id(),
                'video_id' => $item['id'],
                'deskripsi' => $item['description'],
                'thumbnail' => $item['thumbnail'],
            ]);
        }

        return redirect()->route('lookbook.create')->with('success', 'Lookbook berhasil disimpan!');
    }


}
