<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StylistAuthController;


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
