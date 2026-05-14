<?php

use App\Http\Controllers\Api\ClienteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth', 'role:administrador'])->group(function () {

    Route::apiResource('clientes', ClienteController::class)->names([
        'index'   => 'api.clientes.index',
        'store'   => 'api.clientes.store',
        'show'    => 'api.clientes.show',
        'update'  => 'api.clientes.update',
        'destroy' => 'api.clientes.destroy',
    ]);
});
