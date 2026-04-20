<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

/**
 * @class FacturaMail
 * @brief Clase encargada de la construcción y envío del correo electrónico de facturación.
 * * Esta clase utiliza el sistema de Mailables de Laravel para:
 * - Definir el asunto dinámico del correo.
 * - Renderizar el cuerpo del mensaje mediante una vista Blade.
 * - Generar "al vuelo" el archivo PDF de la factura y adjuntarlo sin necesidad de 
 * almacenamiento temporal adicional en disco durante el proceso de envío.
 * * @package App\Mail
 */
class FacturaMail extends Mailable
{
    /**
     * @brief Instancia de la factura que se va a enviar.
     * @var \App\Models\Factura
     */
    public $factura;

    use Queueable, SerializesModels;

    /**
     * @brief Crea una nueva instancia del mensaje.
     * * @param \App\Models\Factura $factura Objeto factura con todos los datos legales ya congelados.
     */
    public function __construct($factura)
    {
        $this->factura = $factura;
    }

    /**
     * @brief Configura el sobre (envelope) del mensaje.
     * * Define el remitente (si no se usa el global) y el asunto del correo electrónico 
     * utilizando el ID de la factura para una mejor identificación por parte del cliente.
     * * @return Envelope Objeto con la configuración del encabezado del correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura de servicio #' . $this->factura->id,
        );
    }

    /**
     * @brief Define el contenido del cuerpo del mensaje.
     * * Utiliza la vista `emails.factura` para maquetar el texto que leerá el cliente 
     * (saludo, resumen de importe, etc.).
     * * @return Content Objeto con la referencia a la vista Blade.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.factura',
        );
    }

    /**
     * @brief Genera y define los archivos adjuntos del correo.
     * * Este método es clave:
     * 1. Toma la vista PDF de la factura.
     * 2. Utiliza la fachada de `DomPDF` para generar el flujo de datos (output).
     * 3. Adjunta el contenido binario directamente con el tipo MIME adecuado.
     * * @return array<int, \Illuminate\Mail\Mailables\Attachment> Lista de adjuntos.
     */
    public function attachments(): array
    {
        // Generamos el PDF dinámicamente para asegurar que el adjunto 
        // coincida exactamente con el registro de la base de datos.
        $pdf = PDF::loadView('pdf.factura', ['factura' => $this->factura]);

        return [
            Attachment::fromData(fn() => $pdf->output(), "Factura_{$this->factura->id}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
