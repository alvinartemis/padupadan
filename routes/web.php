<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StylistAuthController;
use App\Http\Controllers\SetPreferenceController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/upload', function () {
    return view('upload');
})->name('upload');

Route::get('/stylist/login', [StylistAuthController::class, 'showLoginForm'])->name('stylist.login');
Route::post('/stylist/login', [StylistAuthController::class, 'login'])->name('stylist.login.submit');
Route::post('/stylist/logout', [StylistAuthController::class, 'logout'])->name('stylist.logout');

Route::middleware('auth:stylist')->group(function () {
    Route::get('/stylist/dashboard', [StylistAuthController::class, 'dashboard'])->name('stylist.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/set-preference', [SetPreferenceController::class, 'index'])->name('set_preference.index');
    Route::get('/set-preference/{step}', [SetPreferenceController::class, 'showStep'])->name('set_preference.step');
    Route::post('/set-preference/{step}', [SetPreferenceController::class, 'saveStep'])->name('set_preference.save_step');
    Route::get('/quiz/countdown', [SetPreferenceController::class, 'showCountdown'])->name('set_preference.countdown');
    Route::get('/set-preference/result', [SetPreferenceController::class, 'showResult'])->name('set_preference.result');
    Route::post('/set-preference/complete', [SetPreferenceController::class, 'complete'])->name('set_preference.complete');
});
