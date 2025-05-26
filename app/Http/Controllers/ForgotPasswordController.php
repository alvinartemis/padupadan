<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // GANTI INI dari App\Models\Pengguna menjadi App\Models\User
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash; // Untuk hashing password baru
use Illuminate\Support\Facades\Validator; // Untuk validasi

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan form permintaan link reset password (form input email).
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Memproses email, generate OTP, dan menampilkan form untuk input OTP.
     * OTP akan ditampilkan langsung di halaman berikutnya (sesuai disclaimer).
     */
    public function processEmailAndShowOtpForm(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // GANTI 'Pengguna' menjadi 'User' agar sesuai dengan RegisterController Anda
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email yang Anda masukkan tidak terdaftar.'])
                         ->withInput($request->only('email'));
        }

        // Email ditemukan, generate OTP
        $otp = random_int(100000, 999999);
        $otpExpiry = now()->addMinutes(10); // OTP berlaku selama 10 menit

        Session::put('password_reset_otp', $otp);
        Session::put('password_reset_email', $request->email);
        Session::put('password_reset_otp_expiry', $otpExpiry);
        
        // Simpan juga OTP untuk ditampilkan di view berikutnya (sesuai disclaimer)
        // dan email untuk referensi di form OTP
        Session::flash('otp_to_display', $otp); 
        Session::flash('email_for_otp', $request->email);


        return redirect()->route('password.otp.form')->with([
            'status' => 'Silakan masukkan kode OTP yang ditampilkan.'
            // 'otp_to_display' dan 'email_for_otp' sudah di flash ke session
        ]);
    }

    /**
     * Menampilkan form untuk memasukkan OTP.
     */
    public function showOtpForm(Request $request)
    {
        $email = session('email_for_otp');
        $otpToDisplay = session('otp_to_display');

        if (!$email || !$otpToDisplay || !session()->has('password_reset_otp_expiry') || now()->gt(session('password_reset_otp_expiry'))) {
            session()->forget(['password_reset_otp', 'password_reset_email', 'password_reset_otp_expiry', 'otp_to_display', 'email_for_otp']);
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi permintaan OTP tidak valid atau sudah berakhir. Silakan coba lagi.']);
        }
        
        // Re-flash agar tetap tersedia jika halaman di-refresh (opsional, tergantung kebutuhan)
        // Session::reflash(); 

        return view('auth.otp-input-form', [
            'email' => $email,
            'otp_to_display' => $otpToDisplay
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6', // Validasi OTP 6 digit angka
        ]);

        $sessionOtp = session('password_reset_otp');
        $sessionEmail = session('password_reset_email');
        $sessionOtpExpiry = session('password_reset_otp_expiry');

        if (!$sessionOtp || !$sessionEmail || !$sessionOtpExpiry) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi OTP tidak ditemukan atau tidak valid. Silakan coba lagi.']);
        }

        if (now()->gt($sessionOtpExpiry)) {
            // Hapus session jika OTP sudah kadaluarsa
            session()->forget(['password_reset_otp', 'password_reset_email', 'password_reset_otp_expiry', 'otp_to_display', 'email_for_otp']);
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan minta yang baru.'])->withInput($request->except('otp'));
        }

        if ($request->otp == $sessionOtp && $request->email == $sessionEmail) {
            // OTP dan email cocok, dan belum kadaluarsa
            // Tandai bahwa OTP sudah diverifikasi dan simpan email untuk tahap berikutnya
            Session::put('password_reset_email_verified', $request->email); // Session untuk form reset password
            
            // Hapus session OTP yang sudah digunakan agar tidak bisa dipakai lagi
            session()->forget(['password_reset_otp', 'password_reset_otp_expiry', 'otp_to_display', 'email_for_otp']);

            // Arahkan ke form untuk mengatur password baru
            return redirect()->route('password.reset.form');
        } else {
            // OTP tidak cocok atau email tidak cocok
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.'])->withInput($request->except('otp'));
        }
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = session('password_reset_email_verified');

        if (!$email) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Sesi reset password tidak valid atau sudah berakhir. Silakan ulangi proses lupa password.']);
        }

        return view('auth.reset-password-form', ['email' => $email]);
    }

    /**
     * Memproses dan menyimpan password baru.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pengguna,email', // Sesuaikan nama tabel jika perlu
            'password' => 'required|string|confirmed', // Hapus 'min:8'
            'password_confirmation' => 'required|string', // Hapus 'min:8' (atau tidak perlu jika hanya confirmed di atas)
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                             ->withErrors(['email' => 'Pengguna dengan email ini tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Session::forget(['password_reset_otp', 'password_reset_email', 'password_reset_otp_expiry', 'otp_to_display', 'email_for_otp', 'password_reset_email_verified']);

        return view('auth.password-changed-successfully');
    }
}