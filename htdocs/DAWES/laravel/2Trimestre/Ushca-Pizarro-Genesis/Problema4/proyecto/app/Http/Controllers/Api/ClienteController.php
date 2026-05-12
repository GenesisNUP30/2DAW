<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Rules\ValidarCif;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Centralizamos los mensajes para no repetirlos
     */
    private function getMensajes()
    {
        return [
            'cif.required' => 'El CIF es obligatorio',
            'cif.unique' => 'Ya existe un cliente con ese CIF',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'El correo electrónico no es válido',
            'correo.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'cuenta_corriente.required' => 'La cuenta corriente es obligatoria',
            'cuenta_corriente.max' => 'La cuenta corriente no puede tener más de 100 caracteres',
            'pais.required' => 'El país es obligatorio',
            'pais.exists' => 'El país seleccionado no es válido',
            'fecha_alta.required' => 'La fecha de alta es obligatoria',
            'fecha_alta.date' => 'La fecha de alta debe ser una fecha válida',
            'importe_cuota_mensual.required' => 'El importe de la cuota es obligatorio',
            'importe_cuota_mensual.numeric' => 'El importe de la cuota debe ser numérico',
            'importe_cuota_mensual.min' => 'El importe de la cuota debe ser mayor o igual a 0',
        ];
    }

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
            'cif' => ['required', 'string', 'unique:clientes,cif', new ValidarCif],
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:100',
            'cuenta_corriente' => 'required|string|max:100',
            'pais' => 'required|string|exists:paises,iso2',
            'fecha_alta' => 'required|date',
            'importe_cuota_mensual' => 'required|numeric|min:0',
        ], $this->getMensajes());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY); // 422 Unprocessable Entity
        }

        $data = $validator->validated();
        $data['cif'] = strtoupper(trim($data['cif']));

        // Lógica de moneda automática
        $pais = Pais::where('iso2', $data['pais'])->first();
        $data['moneda'] = $pais->iso_moneda ?? '--';

        $cliente = Cliente::create($data);
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
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:clientes,correo,' . $cliente->id,
            'cuenta_corriente' => 'required|string|max:100',
            'pais' => 'required|string|exists:paises,iso2',
            'importe_cuota_mensual' => 'required|numeric|min:0',
        ], $this->getMensajes());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY); // 422 Unprocessable Entity
        }

        $data = $validator->validated();
        $data['cif'] = strtoupper(trim($data['cif']));

        // Recalcular moneda si el país cambió
        if (isset($data['pais'])) {
            $pais = Pais::where('iso2', $data['pais'])->first();
            $data['moneda'] = $pais->iso_moneda ?? '--';
        }

        $cliente->update($data);

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
