<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;
use App\Models\ConfigAvanzada;
use App\Models\Pais;
use App\Models\User;
use App\Rules\ValidarCif;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        // Capturamos los filtros
        $estado = $request->get('estado'); // 'activos', 'baja' o null
        $pago = $request->get('pago');     // 'pendiente' o null

        $query = Cliente::ordenadosPorNombre()
            ->conPais();

        // Filtro por Estado
        if ($estado === 'activos') {
            $query->activos();
        } elseif ($estado === 'baja') {
            $query->whereNotNull('fecha_baja');
        }

        // Filtro por Cuotas Pendientes
        if ($pago === 'pendiente') {
            // Usamos whereHas para filtrar clientes que tengan al menos una cuota sin fecha_pago
            $query->whereHas('cuotas', function ($q) {
                $q->whereNull('fecha_pago');
            });
        }

        // withQueryString() mantiene los filtros al cambiar de página
        $clientes = $query->paginate(3)->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $paises = Pais::ordenadosPorNombre()->get();

        return view('clientes.create', compact('paises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'cif' => ['required', 'string', 'unique:clientes,cif', new ValidarCif],
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
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

        $validated['cif'] = strtoupper(trim($validated['cif']));

        $pais = Pais::where('iso2', $validated['pais'])->first();
        $validated['moneda'] = $pais->iso_moneda;

        Cliente::create($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Cliente $cliente)
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     if (!$user->isAdmin()) {
    //         abort(403);
    //     }

    //     $paises = Pais::ordenadosPorNombre()->get();

    //     return view('clientes.edit', compact('cliente', 'paises'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Cliente $cliente)
    // {
    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

    //     if (!$user->isAdmin()) {
    //         abort(403);
    //     }

    //     $validated = $request->validate([
    //         'cif' => 'required|string|max:20|unique:clientes,cif,' . $cliente->id,
    //         'nombre' => 'required|string|max:100',
    //         'telefono' => 'required|string|max:20',
    //         'correo' => 'required|email|max:100',
    //         'cuenta_bancaria' => 'required|string|max:50',
    //         'pais' => 'required|string|exists:paises,iso2',
    //         'moneda' => 'required|exists:paises,iso_moneda',
    //         'importe_cuota' => 'required|numeric|min:0',
    //     ], [
    //         'cif.required' => 'El CIF es obligatorio',
    //         'cif.unique' => 'Ya existe un cliente con ese CIF',
    //         'cif.max' => 'El CIF no puede tener más de 10 caracteres',
    //         'nombre.required' => 'El nombre es obligatorio',
    //         'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
    //         'telefono.required' => 'El teléfono es obligatorio',
    //         'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
    //         'correo.required' => 'El correo electrónico es obligatorio',
    //         'correo.email' => 'El correo electrónico no es válido',
    //         'correo.max' => 'El correo electrónico no puede tener más de 100 caracteres',
    //         'cuenta_bancaria.required' => 'La cuenta bancaria es obligatoria',
    //         'cuenta_bancaria.max' => 'La cuenta bancaria no puede tener más de 50 caracteres',
    //         'pais.required' => 'El país es obligatorio',
    //         'pais.in' => 'El país seleccionado no es válido',
    //         'importe_cuota.required' => 'El importe de la cuota es obligatorio',
    //         'importe_cuota.numeric' => 'El importe de la cuota debe ser numérico',
    //         'importe_cuota.min' => 'El importe de la cuota debe ser mayor o igual a 0',
    //     ]);

    //     $cliente->update($validated);

    //     return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    // }

    public function confirmBaja(Cliente $cliente)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('clientes.confirmBaja', compact('cliente'));
    }

    public function baja(Cliente $cliente)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        if ($cliente->isBaja()) {
            return redirect()->route('clientes.index')
                ->with('error', 'Este cliente ya está dado de baja.');
        }

        if ($cliente->tieneCuotasPendientes()) {
            $pendientes = $cliente->cuotas()->where('fecha_pago', null)->count();
            return redirect()->route('clientes.index')
                ->with('error', "No se puede dar de baja este cliente porque tiene {$pendientes} cuota(s) pendiente(s) de pago.
                Debes marcar las cuotas como pagadas o eliminarlas antes.");
        }
        $cliente->update([
            'fecha_baja' => now(),
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente dado de baja correctamente.');
    }

    public function confirmAlta(Cliente $cliente)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        if ($cliente->isActivo()) {
            return redirect()->route('clientes.index')
                ->with('error', 'Este cliente ya está activo.');
        }

        return view('clientes.confirmAlta', compact('cliente'));
    }

    public function alta(Cliente $cliente)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $cliente->update([
            'fecha_baja' => null,
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente dado de alta correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
