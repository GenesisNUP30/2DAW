<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Http\Request;
use App\Rules\ValidarCif;
use App\Rules\ValidarTelefono;
use Illuminate\Support\Facades\Validator;

class ClienteJsController extends Controller
{
    // Carga la página principal (la carcasa vacía)
    public function index()
    {
        $paises = Pais::all(); // Los necesitaremos para el modal de creación
        return view('clientes_js.index', compact('paises'));
    }

    // Devuelve todos los clientes en formato JSON para DataTable
    public function listado()
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $clientes = Cliente::with('paisRelacion')->get();
        return response()->json($clientes);
    }

    // Guarda un cliente vía AJAX
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Usamos Validator manualmente para poder devolver JSON en caso de error
        $validator = Validator::make($request->all(), [
            'cif' => ['required', 'string', 'unique:clientes,cif', new ValidarCif],
            'nombre' => 'required|string|max:100',
            'telefono' => ['required', 'string', 'max:20', new ValidarTelefono],
            'correo' => 'required|email|max:100',
            'cuenta_corriente' => 'required|string|max:50',
            'pais' => 'required|string|exists:paises,iso2',
            'fecha_alta' => 'required|date',
            'importe_cuota_mensual' => 'required|numeric|min:1',
        ], [
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
            'cuenta_corriente.max' => 'La cuenta corriente no puede tener más de 50 caracteres',
            'pais.required' => 'El país es obligatorio',
            'pais.exists' => 'El país seleccionado no es válido',
            'fecha_alta.required' => 'La fecha de alta es obligatoria',
            'fecha_alta.date' => 'La fecha de alta debe ser una fecha válida',
            'importe_cuota_mensual.required' => 'El importe de la cuota es obligatorio',
            'importe_cuota_mensual.numeric' => 'El importe de la cuota debe ser numérico',
            'importe_cuota_mensual.min' => 'El importe de la cuota debe ser mayor o igual a 0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // 422 es el código estándar para errores de validación
        }

        $data = $validator->validated();
        $pais = Pais::where('iso2', $data['pais'])->first();
        $data['moneda'] = $pais->iso_moneda;

        $cliente = Cliente::create($data);

        return response()->json([
            'message' => 'Cliente creado con éxito',
            'cliente' => $cliente
        ]);
    }

    public function show($id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    /**
     * Actualiza el cliente.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $cliente = Cliente::findOrFail($id);

        // Reutilizamos la misma lógica de validación que en el Store
        // Pero ojo: en el CIF permitimos el del propio cliente (ignore)
        $validator = Validator::make($request->all(), [
            'cif' => ['required', 'string', 'unique:clientes,cif,' . $id, new ValidarCif],
            'nombre' => 'required|string|max:100',
            'telefono' => ['required', 'string', 'max:20', new ValidarTelefono],
            'correo' => 'required|email|max:100',
            'cuenta_corriente' => 'required|string|max:50',
            'pais' => 'required|string|exists:paises,iso2',
            'fecha_alta' => 'required|date',
            'importe_cuota_mensual' => 'required|numeric|min:1',
        ], [
            'cif.unique' => 'Ya existe un cliente con ese CIF',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'El correo electrónico no es válido',
            'correo.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'cuenta_corriente.required' => 'La cuenta corriente es obligatoria',
            'cuenta_corriente.max' => 'La cuenta corriente no puede tener más de 50 caracteres',
            'pais.required' => 'El país es obligatorio',
            'pais.exists' => 'El país seleccionado no es válido',
            'fecha_alta.required' => 'La fecha de alta es obligatoria',
            'fecha_alta.date' => 'La fecha de alta debe ser una fecha válida',
            'importe_cuota_mensual.required' => 'El importe de la cuota es obligatorio',
            'importe_cuota_mensual.numeric' => 'El importe de la cuota debe ser numérico',
            'importe_cuota_mensual.min' => 'El importe de la cuota debe ser mayor o igual a 0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Ajustar moneda según el país por si ha cambiado
        $pais = Pais::where('iso2', $data['pais'])->first();
        $data['moneda'] = $pais->iso_moneda;

        $cliente->update($data);

        return response()->json(['message' => 'Cliente actualizado con éxito']);
    }

    /**
     * Eliminación lógica 
     */
    public function destroy($id)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $cliente = Cliente::findOrFail($id);
        // Borrado en cascada manual de las cuotas 
        $cliente->cuotas()->delete();

        // Borrado del cliente
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado correctamente']);
    }

    // Problema 3.2: Vue/Quasar
    public function indexVue()
    {
        $paises = Pais::all();
        return view('clientes_vue.index', compact('paises'));
    }
}
