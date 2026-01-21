<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\UserController;

/* Rutas públicas */

Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas protegidas (usuarios autenticados)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    ====== LISTADO PRINCIPAL (HOME REAL DE LA APP) ======
    */
    Route::get('/', [TareaController::class, 'index'])->name('tareas.index');

    /*
    ====== ADMINISTRADOR ======
    */
    Route::middleware('role:administrador')->group(function () {

        // CRUD TAREAS: crear, leer, modificar, eliminar
        Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
        Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');

        Route::get('/tareas/{tarea}/editar', [TareaController::class, 'edit'])->name('tareas.edit');
        Route::put('/tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update');

        Route::get('/tareas/{tarea}/eliminar', [TareaController::class, 'confirmDelete'])->name('tareas.confirmDelete');
        Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');

        // USUARIOS (empleados)
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');

        Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');

        Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');

        Route::get('/usuarios/{usuario}/eliminar', [UserController::class, 'confirmDelete'])->name('usuarios.confirmDelete');
        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // CLIENTES
        Route::resource('/clientes', ClienteController::class)->except(['show']);

        // CUOTAS
        Route::resource('/cuotas', CuotaController::class)->except(['show']);

        // FACTURAS
        Route::get('/facturas/{cuota}/crear', [FacturaController::class, 'create'])->name('facturas.create');
        Route::get('/facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
        Route::post('/facturas/{factura}/enviar', [FacturaController::class, 'enviar'])->name('facturas.enviar');
    });

    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
    Route::get('/tareas/{tarea}/descargar', [TareaController::class, 'downloadFile'])->name('tareas.downloadFile');

    /*
    ====== OPERARIO ======
    */
    Route::middleware('role:operario')->group(function () {
        Route::get('/tareas/{tarea}/completar', [TareaController::class, 'completeForm'])->name('tareas.completeForm');
        Route::post('/tareas/{tarea}/completar', [TareaController::class, 'complete'])->name('tareas.complete');
    });

    /*
    ====== PERFIL EMPLEADO ======
    */
    Route::get('/perfil', [UserController::class, 'profile'])->name('perfil');
    Route::post('/perfil', [UserController::class, 'updateProfile'])->name('perfil.update');
});

Route::get('/incidencia', [TareaController::class, 'createFromCliente'])->name('incidencia.create');
Route::post('/incidencia', [TareaController::class, 'storeFromCliente'])->name('incidencia.store');
