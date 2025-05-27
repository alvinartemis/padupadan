<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LookbookController;

Route::get('/lookbook/create', [LookbookController::class, 'create'])->name('lookbook.create');
