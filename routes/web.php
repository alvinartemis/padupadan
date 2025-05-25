<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DigitalWardrobeController;

// Digital Wardrobe Route
Route::get('/digital-wardrobe', [DigitalWardrobeController::class, 'index'])
    ->name('digital.wardrobe.index')
    ->middleware('auth'); // Assuming only authenticated users can access their wardrobe

Route::get('/digital-wardrobe/create', [DigitalWardrobeController::class, 'create'])
    ->name('digital.wardrobe.create')
    ->middleware('auth');

// Menangani submit foto dan menampilkan form detail
Route::post('/digital-wardrobe/process-photo', [DigitalWardrobeController::class, 'processPhoto'])
    ->name('digital.wardrobe.processPhoto')
    ->middleware('auth');

// Menyimpan detail item ke database (ini akan jadi target form detail)
Route::post('/digital-wardrobe/store', [DigitalWardrobeController::class, 'storeDetails'])
    ->name('digital.wardrobe.storeDetails')
    ->middleware('auth');

Route::post('/digital-wardrobe/discard-temporary-photo', [DigitalWardrobeController::class, 'discardTemporaryPhoto'])
    ->name('digital.wardrobe.discardTemporaryPhoto')
    ->middleware('auth');

Route::get('/digital-wardrobe/{koleksiPakaian}', [DigitalWardrobeController::class, 'show'])
    ->name('digital.wardrobe.show')
    ->middleware('auth');

// Route untuk menampilkan form edit item pakaian (persiapan)
Route::get('/digital-wardrobe/{koleksiPakaian}/edit', [DigitalWardrobeController::class, 'edit'])
    ->name('digital.wardrobe.edit')
    ->middleware('auth');

Route::put('/digital-wardrobe/{koleksiPakaian}', [DigitalWardrobeController::class, 'update'])
    ->name('digital.wardrobe.update')
    ->middleware('auth');

Route::delete('/digital-wardrobe/{koleksiPakaian}', [DigitalWardrobeController::class, 'destroy'])
    ->name('digital.wardrobe.destroy')
    ->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
