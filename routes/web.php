<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StylistAuthController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/upload', function () {
        return view('upload');
    })->name('upload');
    Route::post('/upload-video', [UploadVideoController::class, 'uploadAndRedirect'])->name('upload.video');
    Route::get('/upload/detail/{id}', [UploadVideoController::class, 'showVideoDetail'])->name('upload.detail');
    Route::post('/upload/post-final/{id}', [UploadVideoController::class, 'finalPost'])->name('upload.final.post');
    Route::post('/upload/discard', [UploadVideoController::class, 'discardUpload'])->name('upload.discard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile'); // Route untuk halaman profil
    Route::get('/set-preference', [SetPreferenceController::class, 'index'])->name('set_preference.index');
    Route::get('/set-preference/{step}', [SetPreferenceController::class, 'showStep'])->name('set_preference.step');
    Route::post('/set-preference/{step}', [SetPreferenceController::class, 'saveStep'])->name('set_preference.save_step');
    Route::get('/quiz/countdown', [SetPreferenceController::class, 'showCountdown'])->name('set_preference.countdown');
    Route::get('/set-preference/result', [SetPreferenceController::class, 'showResult'])->name('set_preference.result');
    Route::post('/set-preference/complete', [SetPreferenceController::class, 'complete'])->name('set_preference.complete');
});


Route::get('/stylist/login', [StylistAuthController::class, 'showLoginForm'])->name('stylist.login');
Route::post('/stylist/login', [StylistAuthController::class, 'login'])->name('stylist.login.submit');
Route::post('/stylist/logout', [StylistAuthController::class, 'logout'])->name('stylist.logout');

Route::middleware('auth:stylist')->group(function () {
    Route::get('/stylist/dashboard', [StylistAuthController::class, 'dashboard'])->name('stylist.dashboard');
});
