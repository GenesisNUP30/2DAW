<?php

use App\Http\Controllers\InicioCtrl;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::any('/', [InicioCtrl::class, 'index']);