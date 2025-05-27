<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LookbookController extends Controller
{
    public function create()
    {
        return view('createlookbook');
    }
}
