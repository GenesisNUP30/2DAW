<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Proteger las rutas para que accedan solo los usuarios autenticados
Route::middleware('auth')->group(function () {

    /*
    ====== VER LISTA DE TAREAS/INCIDENCIAS PAGINADAS ======
    */
    Route::get('/', [HomeController::class, 'index'])->name('home');


    /*
        ====== VER TAREAS PAGINADAS Y SU INFORMACIÓN (botón Ver más) ======
    */
    Route::get('tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');

    /*
        ====== EDITAR DATOS DE CONTACTO (empleados) ======
    */
    
    Route::get('/perfil', [UserController::class, 'profile'])->name('perfil');
    Route::post('/perfil', [UserController::class, 'updateProfile'])->name('perfil.update');

    Route::middleware(['role:administrador'])->group(function () {
        /*
        ====== CRUD DE TAREAS: VER, CREAR, MODIFICAR, ELIMINAR (solo administrador) ======
        */

        Route::get('tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
        Route::post('tareas', [TareaController::class, 'store'])->name('tareas.store');

        Route::get('tareas/{tarea}/editar', [TareaController::class, 'edit'])->name('tareas.edit');
        Route::put('tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update');

        Route::get('tareas/{tarea}/eliminar', [TareaController::class, 'confirmDelete'])->name('tareas.confirmDelete');
        Route::delete('tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');

        /*
        ====== CRUD DE USUARIOS: VER, CREAR, MODIFICAR, ELIMINAR (solo administrador) ======
        */

        Route::get('usuarios', [TareaController::class, 'users'])->name('usuarios.index');
        Route::get('usuarios/crear', [TareaController::class, 'createUser'])->name('usuarios.create');
        Route::post('usuarios', [TareaController::class, 'storeUser'])->name('usuarios.store');

        Route::get('usuarios/{usuario}/editar', [TareaController::class, 'editUser'])->name('usuarios.edit');
        Route::put('usuarios/{usuario}', [TareaController::class, 'updateUser'])->name('usuarios.update');

        Route::get('usuarios/{usuario}/eliminar', [TareaController::class, 'confirmDeleteUser'])->name('usuarios.confirmDelete');
        Route::delete('usuarios/{usuario}', [TareaController::class, 'destroyUser'])->name('usuarios.destroy');

        /*
        ====== CLIENTES (solo administradores) ====== 
        */

        Route::resource('clientes', ClienteController::class)->except(['show']);

        /*
        ====== CUOTAS (solo administradores) ======
        */

        Route::resource('cuotas', CuotaController::class)->except(['show']);

        /*
        ====== FACTURAS (solo administradores) ======
        */

        Route::get('facturas/{cuota}/crear', [FacturaController::class, 'create'])->name('facturas.create');
        Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
        Route::post('facturas/{factura}/enviar', [FacturaController::class, 'enviar'])->name('facturas.enviar');


    });


    /*
        ====== COMPLETAR TAREA (solo operario) ======
    */

    Route::middleware(['role:operario'])->group(function () {
        Route::get('tarea/{tarea}/completar', [TareaController::class, 'completeForm'])->name('tareas.completeForm');
        Route::post('tarea/{tarea}/completar', [TareaController::class, 'complete'])->name('tareas.complete');

        Route::post('usuarios', [TareaController::class, 'store'])->name('tareas.store');
    });
});
