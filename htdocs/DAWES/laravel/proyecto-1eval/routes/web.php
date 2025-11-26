<?php

use App\Http\Controllers\AltaCtrl;
use App\Http\Controllers\EliminarCtrl;
use App\Http\Controllers\InicioCtrl;
use App\Http\Controllers\ModificarCtrl;
use App\Http\Controllers\LoginCtrl;
use Illuminate\Support\Facades\Route;

// Login de usuario
Route::any('/login', [LoginCtrl::class, 'login']);

// Página principal
Route::get('/', [InicioCtrl::class, 'index']);

// Alta de tarea
Route::get('/alta', [AltaCtrl::class, 'alta']);
Route::post('/alta', [AltaCtrl::class, 'alta']);

// Modificar tarea
Route::get('/modificar/{id}', [ModificarCtrl::class, 'mostrarFormulario']);
Route::post('/modificar/{id}', [ModificarCtrl::class, 'actualizar']);

// Eliminar tarea
Route::get('/eliminar/{id}', [EliminarCtrl::class, 'confirmar']);
Route::post('/eliminar/{id}', [EliminarCtrl::class, 'eliminar']);