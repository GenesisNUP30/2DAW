<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cuota;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $cuotas = Cuota::conRelaciones()
            ->ordenadasPorFecha()
            ->get();

        return view('cuotas.index', compact('cuotas'));
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

        $clientes = Cliente::ordenadosPorNombre()->get();

        return view('cuotas.create', compact('clientes'));
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
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:50',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0.01',
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:255',
        ], [
            'concepto.required' => 'El concepto es obligatorio',
            'concepto.max' => 'El concepto no puede tener más de 50 caracteres',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria',
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser numérico',
            'importe.min' => 'El importe debe ser mayor o igual a 0',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha valida',
            'fecha_pago.after_or_equal' => 'La fecha de pago debe ser igual o posterior a la fecha de emisión',
            'notas.max' => 'Las notas no pueden superar los 255 caracteres',
        ]);

        Cuota::create($validated);


        return redirect()->route('cuotas.index')->with('success', 'Cuota creada correctamente.');
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
    public function edit(Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $clientes = Cliente::ordenadosPorNombre()->get();

        return view('cuotas.edit', compact('cuota', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:50',
            'fecha_emision' => 'required|date',
            'importe' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0.01'
            ],
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_emision',
            'notas' => 'nullable|string|max:255',
        ], [
            'concepto.required' => 'El concepto es obligatorio',
            'concepto.max' => 'El concepto no puede tener más de 50 caracteres',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria',
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser un número válido (usa punto para decimales, ej: 500.50)',
            'importe.min' => 'El importe debe ser mayor o igual a 0',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha valida',
            'fecha_pago.after_or_equal' => 'La fecha de pago debe ser igual o posterior a la fecha de emisión',
            'notas.max' => 'Las notas no pueden superar los 255 caracteres',
        ]);

        $cuota->update($validated);

        return redirect()->route('cuotas.index')->with('success', 'Cuota actualizada correctamente.');
    }

    /**
     * Confirmar la eliminación de una cuota
     *
     * @param Cuota $cuota
     * @return void
     */
    public function confirmDelete(Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('cuotas.confirmDelete', compact('cuota'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cuota $cuota)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $cuota->delete();

        return redirect()->route('cuotas.index')->with('success', 'Cuota eliminada correctamente.');
    }
}
