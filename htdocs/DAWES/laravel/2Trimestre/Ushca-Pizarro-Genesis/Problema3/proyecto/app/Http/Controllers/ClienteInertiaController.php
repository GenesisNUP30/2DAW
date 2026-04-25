<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Http\Request;
use App\Rules\ValidarCif;
use App\Rules\ValidarTelefono;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ClienteInertiaController extends Controller
{
    // Carga el componente Vue usando Inertia
    public function index()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return Inertia::render('Clientes/Index', [
            'clientes' => Cliente::with('paisRelacion')->paginate(5),
            'paises' => Pais::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $data = $request->validate([
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

        $pais = Pais::where('iso2', $data['pais'])->first();
        $data['moneda'] = $pais->iso_moneda;

        Cliente::create($data);

        // Volvemos a la lista. Inertia refresca los datos sin recargar la web.
        return redirect()->route('clientes.v3.index')->with('message', 'Cliente creado');
    }

    public function update(Request $request, Cliente $cliente)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $data = $request->validate([
            'cif' => ['required', 'string', 'unique:clientes,cif,' . $cliente->id, new ValidarCif],
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

        $pais = Pais::where('iso2', $data['pais'])->first();
        $data['moneda'] = $pais->iso_moneda;

        $cliente->update($data);

        return redirect()->route('clientes.v3.index');
    }

    public function destroy(Cliente $cliente)
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
        $cliente->cuotas()->delete();
        
        $cliente->delete();
        return redirect()->route('clientes.v3.index');
    }
}
