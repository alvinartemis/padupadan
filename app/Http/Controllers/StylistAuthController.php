<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Stylist;
use App\Models\Lookbook;

class StylistAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('stylistlogin');
    }

    /**
     * Memproses upaya login stylist.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari stylist berdasarkan username
        $stylist = Stylist::where('username', $request->username)->first();

        // Cek apakah stylist ditemukan DAN password plain text cocok
        if ($stylist && $request->password === $stylist->password) {
            // Login stylist secara manual
            Auth::guard('stylist')->login($stylist);
            $request->session()->regenerate();
            return redirect()->intended(route('stylist.homestylist'));
        }

        return redirect()->back()
            ->withErrors(['login' => 'Kredensial stylist tidak valid.'])
            ->withInput($request->only('username'));
    }

    /**
     * Menampilkan dashboard stylist setelah login berhasil.
     */
    public function dashboard()
    {
        if (!Auth::guard('stylist')->check()) {
            return redirect()->route('stylist.login');
        }

        $stylist = Auth::guard('stylist')->user();
        $lookbooks = Lookbook::where('idStylist', $stylist->idStylist)->get();

        return view('homestylist', compact('stylist', 'lookbooks'));
    }

    /**
     * Melakukan logout stylist.
     */
    public function logout(Request $request)
    {
        Auth::guard('stylist')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
