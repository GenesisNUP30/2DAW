<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Rules\ValidarCif;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Listar todos los clientes
     */
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes, Response::HTTP_OK); // 200 OK
    }

    /**
     * Crear un nuevo cliente
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'cif' => ['required', 'string', 'unique:clientes,cif', new ValidarCif],
            'correo' => 'required|email|unique:clientes,correo',
            'pais' => 'required|string|exists:paises,iso2',
            'importe_cuota_mensual' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY); // 422 Unprocessable Entity
        }

        $cliente = Cliente::create($request->all());
        return response()->json($cliente, Response::HTTP_CREATED); // 201 Created
    }

    /**
     * Mostrar un cliente específico
     */
    public function show(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        return response()->json($cliente, Response::HTTP_OK); // 200 OK
    }

    /**
     * Actualizar un cliente
     */
    public function update(Request $request, string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        $validator = Validator::make($request->all(), [
            'cif' => ['required', 'string', 'unique:clientes,cif,' . $cliente->id, new ValidarCif],
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:clientes,correo,' . $cliente->id,
            'pais' => 'required|string|exists:paises,iso2',
            'importe_cuota_mensual' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY); // 422 Unprocessable Entity
        }

        $cliente->update($request->all());
        return response()->json($cliente, Response::HTTP_OK); // 200 OK
    }

    /**
     * Eliminar un cliente
     */
    public function destroy(string $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $cliente->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT); // 204 No Content
    }
}
