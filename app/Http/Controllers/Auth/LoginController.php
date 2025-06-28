<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Import Request

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Atau rute dashboard setelah login

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // Baris ini sudah ada, pastikan tidak ada duplikasi: $this->middleware('auth')->only('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username'; // Gunakan kolom 'username' untuk login
    }

    /**
     * Override the logout method to customize redirection after logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout(); // Logout pengguna

        $request->session()->invalidate(); // Membatalkan sesi
        $request->session()->regenerateToken(); // Meregenerasi token CSRF

        // Arahkan pengguna ke halaman awal (root URL) setelah logout
        return $this->loggedOut($request) ?: redirect('/');
    }
}
