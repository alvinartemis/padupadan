<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StylistAuthController;
use App\Http\Controllers\SetPreferenceController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/upload', function () {
        return view('upload');
    })->name('upload');
    Route::post('/upload-video', [App\Http\Controllers\UploadVideoController::class, 'uploadAndRedirect'])->name('upload.video');
    Route::get('/upload/detail/{id}', [App\Http\Controllers\UploadVideoController::class, 'showVideoDetail'])->name('upload.detail');
    Route::post('/upload/post-final/{id}', [App\Http\Controllers\UploadVideoController::class, 'finalPost'])->name('upload.final.post');
    Route::post('/upload/discard', [App\Http\Controllers\UploadVideoController::class, 'discardUpload'])->name('upload.discard');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile'); // Route untuk halaman profil
});


Route::get('/stylist/login', [App\Http\Controllers\StylistAuthController::class, 'showLoginForm'])->name('stylist.login');
Route::post('/stylist/login', [App\Http\Controllers\StylistAuthController::class, 'login'])->name('stylist.login.submit');
Route::post('/stylist/logout', [App\Http\Controllers\StylistAuthController::class, 'logout'])->name('stylist.logout');

Route::middleware('auth:stylist')->group(function () {
    Route::get('/stylist/dashboard', [App\Http\Controllers\StylistAuthController::class, 'dashboard'])->name('stylist.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/set-preference', [App\Http\Controllers\SetPreferenceController::class, 'index'])->name('set_preference.index');
    Route::get('/set-preference/{step}', [App\Http\Controllers\SetPreferenceController::class, 'showStep'])->name('set_preference.step');
    Route::post('/set-preference/{step}', [App\Http\Controllers\SetPreferenceController::class, 'saveStep'])->name('set_preference.save_step');
    Route::get('/quiz/countdown', [App\Http\Controllers\SetPreferenceController::class, 'showCountdown'])->name('set_preference.countdown');
    Route::get('set-preference/result', [App\Http\Controllers\SetPreferenceController::class, 'showResult'])->name('set_preference.result');
    Route::post('/set-preference/complete', [App\Http\Controllers\SetPreferenceController::class, 'complete'])->name('set_preference.complete');
});
