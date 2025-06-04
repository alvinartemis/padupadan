<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LookbookController extends Controller
{
    public function create()
    {
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
