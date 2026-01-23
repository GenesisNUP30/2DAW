<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ArticuloController;

// Autenticación
Auth::routes();

Route::middleware('auth')->group(function () {

    // Página principal → listado con filtros y ordenación
    Route::get('/', [ArticuloController::class, 'index'])->name('articulos.index');

    // CRUD básico
    Route::get('/articulos/crear', [ArticuloController::class, 'create'])->name('articulos.create');
    Route::post('/articulos', [ArticuloController::class, 'store'])->name('articulos.store');

    Route::get('/articulos/{articulo}/editar', [ArticuloController::class, 'edit'])->name('articulos.edit');
    Route::put('/articulos/{articulo}', [ArticuloController::class, 'update'])->name('articulos.update');

    Route::delete('/articulos/{articulo}', [ArticuloController::class, 'destroy'])->name('articulos.destroy');
});