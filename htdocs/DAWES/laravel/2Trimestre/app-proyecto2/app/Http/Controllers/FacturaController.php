<?php

namespace App\Http\Controllers;

use App\Mail\FacturaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class FacturaController extends Controller
{
    public function enviar($id)
    {
        $factura = FacturaMail::with('cuota.cliente')->findOrFail($id);

        if (!$factura->cuota->cliente->correo) {
            return back()->withErrors('El cliente no tiene email');
        }

        try {
            // Generar el PDF
            $pdf = PDF::loadView('pdf.factura', ['factura' => $factura]);

            // Definir nombre y ruta
            $nombreFichero = "facturas/Factura_{$factura->id}.pdf";

            // Guardar en el disco 'private' (storage/app/private/)
            Storage::disk('private')->put($nombreFichero, $pdf->output());

            // Enviar el correo
            Mail::to($factura->cuota->cliente->correo)->queue(new FacturaMail($factura));

            // Marcar como enviada (opcional, si existe ese campo)
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
