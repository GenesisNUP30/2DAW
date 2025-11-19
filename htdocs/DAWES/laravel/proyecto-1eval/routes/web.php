<?php

use App\Http\Controllers\AltaCtrl;
use App\Http\Controllers\InicioCtrl;
use App\Http\Controllers\ModificarCtrl;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::any('/', [InicioCtrl::class, 'index']);

Route::any('/alta', [AltaCtrl::class, 'alta']);

Route::any('/modificar/{id}', [ModificarCtrl::class, 'modificar']);