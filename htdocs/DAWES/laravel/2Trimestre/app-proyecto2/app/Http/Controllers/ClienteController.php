<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;
use App\Models\Pais;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::ordenadosPorNombre()
        ->conPais()
        ->get();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $paises = Pais::ordenadosPorNombre()->get();

        return view('clientes.create', [
            'paises' => $paises,
            'monedas' => $this->monedas(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'cif' => 'required|string|max:20|unique:clientes',
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:100',
            'cuenta_bancaria' => 'required|string|max:50',
            'pais' => 'required|string|exists:paises,iso2',
            'moneda' => 'required|in:' . implode(',', array_keys($this->monedas())),
            'importe_cuota' => 'required|numeric|min:0',
        ], [
            'cif.required' => 'El CIF es obligatorio',
            'cif.unique' => 'Ya existe un cliente con ese CIF',
            'cif.max' => 'El CIF no puede tener más de 20 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'El correo electrónico no es válido',
            'correo.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'cuenta_bancaria.required' => 'La cuenta bancaria es obligatoria',
            'cuenta_bancaria.max' => 'La cuenta bancaria no puede tener más de 50 caracteres',
            'pais.required' => 'El país es obligatorio',
            'pais.in' => 'El país seleccionado no es válido',
            'moneda.required' => 'La moneda es obligatoria',
            'moneda.in' => 'La moneda seleccionada no es válida',
            'importe_cuota.required' => 'El importe de la cuota es obligatorio',
            'importe_cuota.numeric' => 'El importe de la cuota debe ser numérico',
            'importe_cuota.min' => 'El importe de la cuota debe ser mayor o igual a 0',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $paises = Pais::ordenadosPorNombre()->get();

        return view('clientes.edit', [
            'cliente' => $cliente,
            'paises' => $paises,
            'monedas' => $this->monedas(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'cif' => 'required|string|max:20|unique:clientes,cif,' . $cliente->id,
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:100',
            'cuenta_bancaria' => 'required|string|max:50',
            'pais' => 'required|string|exists:paises,iso2',
            'moneda' => 'required|in:' . implode(',', array_keys($this->monedas())),
            'importe_cuota' => 'required|numeric|min:0',
        ], [
            'cif.required' => 'El CIF es obligatorio',
            'cif.unique' => 'Ya existe un cliente con ese CIF',
            'cif.max' => 'El CIF no puede tener más de 10 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'El correo electrónico no es válido',
            'correo.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'cuenta_bancaria.required' => 'La cuenta bancaria es obligatoria',
            'cuenta_bancaria.max' => 'La cuenta bancaria no puede tener más de 50 caracteres',
            'pais.required' => 'El país es obligatorio',
            'pais.in' => 'El país seleccionado no es válido',
            'moneda.required' => 'La moneda es obligatoria',
            'moneda.in' => 'La moneda seleccionada no es válida',
            'importe_cuota.required' => 'El importe de la cuota es obligatorio',
            'importe_cuota.numeric' => 'El importe de la cuota debe ser numérico',
            'importe_cuota.min' => 'El importe de la cuota debe ser mayor o igual a 0',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function monedas(): array
    {
        return [
            'EUR' => 'Euro (€)',
            'USD' => 'Dólar estadounidense ($)',
            'GBP' => 'Libra esterlina (£)',
            'JPY' => 'Yen japonés (¥)',
            'CHF' => 'Franco suizo (CHF)',
            'CAD' => 'Dólar canadiense (CA$)',
            'AUD' => 'Dólar australiano (AU$)',
            'CNY' => 'Yuan chino (¥)',
            'MXN' => 'Peso mexicano ($)',
            'BRL' => 'Real brasileño (R$)',
        ];
    }
}
