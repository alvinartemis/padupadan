<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    public function processEmailAndShowOtpForm(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email yang Anda masukkan tidak terdaftar.'])
                         ->withInput($request->only('email'));
        }

        $otp = random_int(100000, 999999);
        $otpExpiry = now()->addMinutes(10); 

        Session::put('password_reset_otp', $otp);
        Session::put('password_reset_email', $request->email);
        Session::put('password_reset_otp_expiry', $otpExpiry);
        
        Session::flash('otp_to_display', $otp); 
        Session::flash('email_for_otp', $request->email);


        return redirect()->route('password.otp.form')->with([
            'status' => 'Please enter the OTP code shown.'
        ]);
    }

    public function showOtpForm(Request $request)
    {
        $email = session('email_for_otp');
        $otpToDisplay = session('otp_to_display');

        if (!$email || !$otpToDisplay || !session()->has('password_reset_otp_expiry') || now()->gt(session('password_reset_otp_expiry'))) {
            session()->forget(['password_reset_otp', 'password_reset_email', 'password_reset_otp_expiry', 'otp_to_display', 'email_for_otp']);
            return redirect()->route('password.request')->withErrors(['email' => 'The OTP request session is invalid or has expired. Please try again.']);
        }
        
        return view('auth.otp-input-form', [
            'email' => $email,
            'otp_to_display' => $otpToDisplay
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $sessionOtp = session('password_reset_otp');
        $sessionEmail = session('password_reset_email');
        $sessionOtpExpiry = session('password_reset_otp_expiry');

        if (!$sessionOtp || !$sessionEmail || !$sessionOtpExpiry) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi OTP tidak ditemukan atau tidak valid. Silakan coba lagi.']);
        }

        if (now()->gt($sessionOtpExpiry)) {
            session()->forget(['password_reset_otp', 'password_reset_email', 'password_reset_otp_expiry', 'otp_to_display', 'email_for_otp']);
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan minta yang baru.'])->withInput($request->except('otp'));
        }

        if ($request->otp == $sessionOtp && $request->email == $sessionEmail) {
            Session::put('password_reset_email_verified', $request->email);
            
            session()->forget(['password_reset_otp', 'password_reset_otp_expiry', 'otp_to_display', 'email_for_otp']);

            return redirect()->route('password.reset.form');
        } else {
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

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pengguna,email',
            'password' => 'required|string|confirmed', 
            'password_confirmation' => 'required|string',
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