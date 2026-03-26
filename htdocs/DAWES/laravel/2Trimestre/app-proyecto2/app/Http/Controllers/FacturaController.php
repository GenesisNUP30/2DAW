<?php

namespace App\Http\Controllers;

use App\Mail\FacturaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function enviar($id)
    {
        $factura = FacturaMail::with('cuota.cliente')->findOrFail($id);

        if (!$factura->cuota->cliente->correo) {
            return back()->withErrors('El cliente no tiene email');
        }

        try {
            // 2. Enviar el correo
            Mail::to($factura->cuota->cliente->correo)->send(new FacturaMail($factura));

            // 3. Marcar como enviada (opcional, si tienes ese campo)
            // $factura->update(['enviada' => true]);

            return back()->with('success', 'Factura enviada correctamente al cliente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
