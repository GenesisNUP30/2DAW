<?php

namespace App\Http\Controllers;

use App\Mail\FacturaMail;
use App\Models\Cuota;
use App\Models\Factura;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

/**
 * @class FacturaController
 * * @brief Controlador encargado de la facturación, generación de PDF y envío de correos.
 * * Este controlador gestiona el proceso de convertir una cuota en una factura legal.
 * Incluye una lógica de "congelación de datos": al generar la factura, se copian 
 * los datos del cliente y la cuota en ese momento para que, si el cliente cambia 
 * de nombre o el importe de la cuota varía en el futuro, la factura original 
 * permanezca inalterada según la normativa legal.
 * * @package App\Http\Controllers
 */
class FacturaController extends Controller
{
    /**
     * @brief Paso 1: Muestra una vista previa de la factura antes de su generación definitiva.
     * * Permite al administrador revisar los datos de la cuota y del cliente vinculados 
     * antes de crear el registro físico y contable de la factura.
     * * @param int $cuota_id ID de la cuota de la cual se desea generar factura.
     * @return \Illuminate\View\View Vista de confirmación con los datos de la cuota.
     */
    public function confirmar($cuota_id)
    {
        $cuota = Cuota::with('cliente')->findOrFail($cuota_id);
        return view('facturas.confirmar', compact('cuota'));
    }

    /**
     * @brief Genera el registro de la factura y su archivo PDF físico.
     * * Este proceso realiza las siguientes acciones:
     * 1. Genera un número de factura único con formato `FAC-AÑO-ID`.
     * 2. Crea el registro en la base de datos "congelando" nombre, CIF e importe.
     * 3. Renderiza una vista Blade a PDF utilizando el motor `DomPDF`.
     * 4. Almacena el PDF en un disco privado (`storage/app/private/`) para mayor seguridad.
     * 5. Actualiza la ruta del fichero en el modelo recién creado.
     * * @param Request $request
     * @param int $cuota_id ID de la cuota origen.
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito o error.
     */
    public function generar(Request $request, $cuota_id)
    {
        // Cargamos la cuota con su cliente
        $cuota = Cuota::with('cliente')->findOrFail($cuota_id);
        $cliente = $cuota->cliente;

        // Generamos un número de factura único (FAC-Año-ID de cuota con ceros)
        $numero = 'FAC-' . date('Y') . '-' . str_pad($cuota->id, 4, '0', STR_PAD_LEFT);

        // CREAR EL MODELO FACTURA (Congelamos datos legales)
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

    /**
     * @brief Envía la factura generada por correo electrónico al cliente.
     * * Utiliza la clase `FacturaMail` para adjuntar el PDF almacenado en el disco privado.
     * Tras el envío exitoso, marca la factura como `enviada = true`.
     * * @param Factura $factura Instancia de la factura a enviar.
     * @return \Illuminate\Http\RedirectResponse
     */
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
     * @brief Permite la descarga directa del PDF de la factura.
     * * Busca el archivo en el disco 'private'. Si el archivo no existe por alguna 
     * razón técnica, devuelve un error 404.
     * * @param Factura $factura
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function descargar(Factura $factura)
    {
        if (!Storage::disk('private')->exists($factura->ruta_pdf)) {
            abort(404, 'El archivo PDF no se encuentra en el servidor.');
        }

        return Storage::disk('private')->download($factura->ruta_pdf, "{$factura->numero_factura}.pdf");
    }
}
