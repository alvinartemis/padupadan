<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StylistAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('stylistlogin'); // pastikan nama view sesuai
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

        $credentials = $request->only('username', 'password');

        if (Auth::guard('stylist')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('stylist.dashboard'));
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
        return view('stylist.dashboard');
    }

    /**
     * Melakukan logout stylist.
     */
    public function logout(Request $request)
    {
        Auth::guard('stylist')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('stylist.login'));
    }
}
