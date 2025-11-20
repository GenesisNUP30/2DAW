<?php

use App\Http\Controllers\AltaCtrl;
use App\Http\Controllers\EliminarCtrl;
use App\Http\Controllers\InicioCtrl;
use App\Http\Controllers\ModificarCtrl;
use Illuminate\Support\Facades\Route;


Route::any('/', [InicioCtrl::class, 'index']);

Route::any('/alta', [AltaCtrl::class, 'alta']);

Route::get('/modificar/{id}', [ModificarCtrl::class, 'mostrarFormulario'])->name('modificar.form');
Route::post('/modificar/{id}', [ModificarCtrl::class, 'actualizar']);

Route::get('/eliminar/{id}', [EliminarCtrl::class, 'confirmar'])->name('eliminar.confirmar');
Route::delete('/eliminar/{id}', [EliminarCtrl::class, 'eliminar']);
