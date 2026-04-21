<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteApiController extends Controller
{
    /**
     * Listar clientes en JSON (solo admin)
     */
    public function index()
    {
        // Verificar el rol de administrador
        $user = Auth()->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'No estás autorizado a acceder a esta operación'], 403);
        }

        // Obtener los clientes
        $clientes = Cliente::ordenadosPorNombre()
            ->conPais()
            ->activos()
            ->get();

        return response()->json($clientes);
    }
}
