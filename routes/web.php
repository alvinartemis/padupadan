<?php

use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StylistAuthController;
use App\Http\Controllers\SetPreferenceController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\DigitalWardrobeController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Ubah rute home yang lama ini, atau tambahkan yang baru jika Anda ingin tetap mempertahankan yang lama
// Jika Anda ingin VideoController yang menangani halaman home utama untuk video:
Route::get('/home', [VideoController::class, 'home'])->name('home'); // <-- UBAH KE INI

// Tambahkan rute API untuk mengambil data video
Route::get('/api/videos', [VideoController::class, 'index'])->name('api.videos'); // <-- TAMBAH BARIS INI


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


Route::get('/settings/editprofile', [EditProfileController::class, 'editprofile'])->name('profile.edit');
Route::put('/profile/update', [EditProfileController::class, 'update'])->name('profile.update');
Route::get('/settings/bookmark', [BookmarkController::class, 'bookmark'])->name('bookmark');
Route::get('/settings/bookmark/item/{id}', [BookmarkController::class, 'showItem'])->name('bookmark.show_item');
Route::post('/logout', [Controller::class, 'logout'])->name('logout');

// Digital Wardrobe Route
Route::get('/digital-wardrobe', [App\Http\Controllers\DigitalWardrobeController::class, 'index'])
    ->name('digital.wardrobe.index')
    ->middleware('auth'); // Assuming only authenticated users can access their wardrobe

Route::get('/digital-wardrobe/create', [App\Http\Controllers\DigitalWardrobeController::class, 'create'])
    ->name('digital.wardrobe.create')
    ->middleware('auth');

// Menangani submit foto dan menampilkan form detail
Route::post('/digital-wardrobe/process-photo', [App\Http\Controllers\DigitalWardrobeController::class, 'processPhoto'])
    ->name('digital.wardrobe.processPhoto')
    ->middleware('auth');

 // Menyimpan detail item ke database (ini akan jadi target form detail)
Route::post('/digital-wardrobe/store', [App\Http\Controllers\DigitalWardrobeController::class, 'storeDetails'])
    ->name('digital.wardrobe.storeDetails')
    ->middleware('auth');

Route::post('/digital-wardrobe/discard-temporary-photo', [App\Http\Controllers\DigitalWardrobeController::class, 'discardTemporaryPhoto'])
    ->name('digital.wardrobe.discardTemporaryPhoto')
    ->middleware('auth');

Route::get('/digital-wardrobe/{koleksiPakaian}', [App\Http\Controllers\DigitalWardrobeController::class, 'show'])
    ->name('digital.wardrobe.show')
    ->middleware('auth');

// Route untuk menampilkan form edit item pakaian (persiapan)
Route::get('/digital-wardrobe/{koleksiPakaian}/edit', [App\Http\Controllers\DigitalWardrobeController::class, 'edit'])
    ->name('digital.wardrobe.edit')
    ->middleware('auth');

Route::put('/digital-wardrobe/{koleksiPakaian}', [App\Http\Controllers\DigitalWardrobeController::class, 'update'])
    ->name('digital.wardrobe.update')
    ->middleware('auth');

Route::delete('/digital-wardrobe/{koleksiPakaian}', [App\Http\Controllers\DigitalWardrobeController::class, 'destroy'])
    ->name('digital.wardrobe.destroy')
    ->middleware('auth');
