<?php

namespace App\Http\Controllers;

use App\Mail\FacturaMail;
use App\Models\Cuota;
use App\Models\Factura;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class FacturaController extends Controller
{
    // PASO 1: Vista previa antes de crear nada
    public function confirmar($cuota_id)
    {
        $cuota = Cuota::with('cliente')->findOrFail($cuota_id);
        return view('facturas.confirmar', compact('cuota'));
    }

    // Crear registro y guardar PDF físicamente
    public function generar(Request $request, $cuota_id)
    {
        // Cargamos la cuota con su cliente
        $cuota = Cuota::with('cliente')->findOrFail($cuota_id);
        $cliente = $cuota->cliente;

        // Generamos un número de factura único (FAC-Año-ID de cuota con ceros)
        $numero = 'FAC-' . date('Y') . '-' . str_pad($cuota->id, 4, '0', STR_PAD_LEFT);

        // 2. CREAR EL MODELO FACTURA (Congelamos datos legales)
        $factura = Factura::create([
            'cuota_id'       => $cuota->id,
            'numero_factura' => $numero,
            'cliente_nombre' => $cliente->nombre,
            'cliente_cif'    => $cliente->cif,
            'concepto'       => $cuota->concepto,
            'importe'        => $cuota->importe,
            'moneda'         => $cliente->moneda ?? 'EUR',
            'enviada'        => false,
        ]);

        try {
            // Generar el PDF
            $pdf = PDF::loadView('pdf.factura', ['factura' => $factura]);

            // Definir nombre y ruta
            $nombreFichero = "facturas/Factura_{$factura->id}.pdf";

            // Guardar en el disco 'private' (storage/app/private/)
            Storage::disk('private')->put($nombreFichero, $pdf->output());

            // Actualizar la ruta en la base de datos
            $factura->update(['ruta_pdf' => $nombreFichero]);

            return redirect()->route('facturas.confirmar', $cuota->id)
                ->with('success', 'Factura generada con éxito. Ahora puede enviarla.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    // Solo enviar (para facturas ya generadas)
    public function enviar(Factura $factura)
    {
        if (!$factura->cuota->cliente->correo) {
            return back()->with('error', 'El cliente no tiene correo.');
        }

        try {
            Mail::to($factura->cuota->cliente->correo)->send(new FacturaMail($factura));
            $factura->update(['enviada' => true]);

            return back()->with('success', 'Correo enviado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Permite al administrador descargar el PDF generado.
     */
    public function descargar(Factura $factura)
    {
        if (!Storage::disk('private')->exists($factura->ruta_pdf)) {
            abort(404, 'El archivo PDF no se encuentra en el servidor.');
        }

        return Storage::disk('private')->download($factura->ruta_pdf, "{$factura->numero_factura}.pdf");
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
