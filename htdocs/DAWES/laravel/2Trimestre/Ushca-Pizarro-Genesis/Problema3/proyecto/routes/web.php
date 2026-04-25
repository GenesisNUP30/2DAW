<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteInertiaController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClienteJsController;
/* Rutas públicas */

Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas protegidas (usuarios autenticados)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('v2')->group(function () {
    Route::get('clientes', [ClienteJsController::class, 'index'])->name('v2.clientes.index');
    Route::get('api/clientes', [ClienteJsController::class, 'listado'])->name('v2.clientes.listado');
    Route::post('api/clientes', [ClienteJsController::class, 'store'])->name('v2.clientes.store');
    Route::get('api/clientes/{id}', [ClienteJsController::class, 'show'])->name('v2.clientes.show');
    Route::put('api/clientes/{id}', [ClienteJsController::class, 'update'])->name('v2.clientes.update');
    Route::delete('api/clientes/{id}', [ClienteJsController::class, 'destroy'])->name('v2.clientes.destroy');

    Route::get('clientes-vue', [ClienteJsController::class, 'indexVue'])->name('v2.clientes.vue');
});

// Problema 3.3  Inertia + Vite + Tailwind
Route::middleware(['auth'])->prefix('v3')->group(function () {
    
    // Listado principal y renderizado de la página (Inertia)
    Route::get('/clientes', [ClienteInertiaController::class, 'index'])
        ->name('clientes.v3.index');

    // Guardar nuevo cliente
    Route::post('/clientes', [ClienteInertiaController::class, 'store'])
        ->name('clientes.v3.store');

    // Actualizar cliente existente
    // Usamos PUT o PATCH para ediciones
    Route::put('/clientes/{cliente}', [ClienteInertiaController::class, 'update'])
        ->name('clientes.v3.update');

    // Eliminar cliente (Borrado lógico o físico según tu modelo)
    Route::delete('/clientes/{cliente}', [ClienteInertiaController::class, 'destroy'])
        ->name('clientes.v3.destroy');
});

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return redirect()->route('tareas.index');
    });
    /*
    ====== ADMINISTRADOR ======
    */
    Route::middleware('role:administrador')->group(function () {
        //TODO: Comprobar la ruta otra vez
        Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

        // CRUD TAREAS: crear, leer, modificar, eliminar
        // Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
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
        // Route::get('/tareas/index', [TareaController::class, 'index'])->name('tareas.index');
        Route::get('/tareas/{tarea}/completar', [TareaController::class, 'completeForm'])->name('tareas.completeForm');
        Route::post('/tareas/{tarea}/completar', [TareaController::class, 'complete'])->name('tareas.complete');
    });

    /*
    ====== EMPLEADOS ======
    */
    Route::middleware('role:administrador')->group(function () {
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

    Route::middleware('role:administrador')->group(function () {
        // CUOTAS
        Route::get('/cuotas', [CuotaController::class, 'index'])->name('cuotas.index');

        Route::get('/cuotas/remesa', [CuotaController::class, 'generarRemesa'])->name('cuotas.generarRemesa');
        Route::get('/cuotas/crear', [CuotaController::class, 'create'])->name('cuotas.create');
        Route::post('/cuotas', [CuotaController::class, 'store'])->name('cuotas.store');

        Route::get('/cuotas/{cuota}/editar', [CuotaController::class, 'edit'])->name('cuotas.edit');
        Route::put('/cuotas/{cuota}', [CuotaController::class, 'update'])->name('cuotas.update');

        Route::get('/cuotas/{cuota}/eliminar', [CuotaController::class, 'confirmDelete'])->name('cuotas.confirmDelete');
        Route::delete('/cuotas/{cuota}', [CuotaController::class, 'destroy'])->name('cuotas.destroy');

        Route::get('/cuotas/papelera', [CuotaController::class, 'papelera'])->name('cuotas.papelera');
        Route::post('/cuotas/{id}/restore', [CuotaController::class, 'restore'])->name('cuotas.restore');
    });

    /*
|--------------------------------------------------------------------------
| ADMINISTRACIÓN DE FACTURAS
|--------------------------------------------------------------------------
*/
    Route::middleware('role:administrador')->group(function () {

        // 1. Vista de gestión/confirmación (pasamos el ID de la cuota)
        Route::get('/facturas/gestionar/{cuota}', [FacturaController::class, 'confirmar'])
            ->name('facturas.confirmar');

        // 2. Acción de generar el registro y el PDF físico
        Route::post('/facturas/generar/{cuota}', [FacturaController::class, 'generar'])
            ->name('facturas.generar');

        // 3. Descarga del PDF (usamos el ID de la factura ya creada)
        Route::get('/facturas/descargar/{factura}', [FacturaController::class, 'descargar'])
            ->name('facturas.descargar');

        // 4. Envío por email (POST por seguridad al disparar correos)
        Route::post('/facturas/enviar/{factura}', [FacturaController::class, 'enviar'])
            ->name('facturas.enviar');
    });

    /*
    ====== PERFIL DE USUARIO ======
    */
    Route::get('/perfil', [UserController::class, 'profile'])->name('perfil');
    Route::put('/perfil', [UserController::class, 'updateProfile'])->name('perfil.update');

    /*
    ====== LISTADO PRINCIPAL ======
    */
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
    Route::get('/tareas/{tarea}/descargar', [TareaController::class, 'downloadFile'])->name('tareas.downloadFile');
});

Route::get('/incidencia', [TareaController::class, 'createFromCliente'])->name('incidencia.create');
Route::post('/incidencia', [TareaController::class, 'storeFromCliente'])->name('incidencia.store');
