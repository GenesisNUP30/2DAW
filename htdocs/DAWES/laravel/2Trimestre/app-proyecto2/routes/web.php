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
    });

    /*
    ====== OPERARIO ======
    */
    Route::middleware(['role:operario'])->group(function () {

        Route::get('/tareas/{tarea}/completar', [TareaController::class, 'completeForm'])->name('tareas.completeForm');
        Route::post('/tareas/{tarea}/completar', [TareaController::class, 'complete'])->name('tareas.complete');
    });


    Route::middleware('role:administrador')->group(function () {
        // EMPLEADOS
        Route::get('/empleados', [UserController::class, 'index'])->name('empleados.index');

        Route::get('/empleados/crear', [UserController::class, 'create'])->name('empleados.create');
        Route::post('/empleados', [UserController::class, 'store'])->name('empleados.store');

        Route::get('/empleados/{empleado}/editar', [UserController::class, 'edit'])->name('empleados.edit');
        Route::put('/empleados/{empleado}', [UserController::class, 'update'])->name('empleados.update');

        Route::get('/empleados/{empleado}/eliminar', [UserController::class, 'confirmDelete'])->name('empleados.confirmDelete');
        Route::delete('/empleados/{empleado}', [UserController::class, 'destroy'])->name('empleados.destroy');

        Route::get('/empleados/{empleado}/baja', [UserController::class, 'confirmBaja'])->name('empleados.confirmBaja');
        Route::post('/empleados/{empleado}/baja', [UserController::class, 'baja'])->name('empleados.baja');

        Route::get('/empleados/{empleado}/alta', [UserController::class, 'confirmAlta'])->name('empleados.confirmAlta');
        Route::post('/empleados/{empleado}/alta', [UserController::class, 'alta'])->name('empleados.alta');


        // CUOTAS
        Route::resource('/cuotas', CuotaController::class)->except(['show']);

        // FACTURAS
        Route::get('/facturas/{cuota}/crear', [FacturaController::class, 'create'])->name('facturas.create');
        Route::get('/facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
        Route::post('/facturas/{factura}/enviar', [FacturaController::class, 'enviar'])->name('facturas.enviar');
    });

    Route::middleware('role:administrador')->group(function () {
        // CLIENTES
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');

        Route::get('/clientes/crear', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');

        Route::get('/clientes/{cliente}/baja', [ClienteController::class, 'confirmBaja'])->name('clientes.confirmBaja');
        Route::post('/clientes/{cliente}/baja', [ClienteController::class, 'baja'])->name('clientes.baja');

        Route::get('/clientes/{cliente}/alta', [ClienteController::class, 'confirmAlta'])->name('clientes.confirmAlta');
        Route::post('/clientes/{cliente}/alta', [ClienteController::class, 'alta'])->name('clientes.alta');

        Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
        
    });


    /*
    ====== PERFIL DE USUARIO ======
    */
    Route::get('/perfil', [UserController::class, 'profile'])->name('perfil');
    Route::put('/perfil', [UserController::class, 'updateProfile'])->name('perfil.update');

    /*
    ====== LISTADO PRINCIPAL ======
    */
    Route::get('/', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
    Route::get('/tareas/{tarea}/descargar', [TareaController::class, 'downloadFile'])->name('tareas.downloadFile');
});

Route::get('/incidencia', [TareaController::class, 'createFromCliente'])->name('incidencia.create');
Route::post('/incidencia', [TareaController::class, 'storeFromCliente'])->name('incidencia.store');
