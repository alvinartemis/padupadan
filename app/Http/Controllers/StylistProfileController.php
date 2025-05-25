<?php

namespace App\Http\Controllers;

use App\Models\Stylist;
use Illuminate\Http\Request;

class StylistProfileController extends Controller
{
    /**
     * Menampilkan detail profil stylist.
     */
    public function show(Stylist $stylist)
    {
        return view('chat.profilestylist', compact('stylist'));
    }
}
