<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Factura
 * @brief Modelo que representa el documento legal de facturación emitido.
 * * Este modelo actúa como un "snapshot" o captura de datos. Almacena de forma 
 * redundante los datos del cliente (nombre, CIF) y de la cuota (importe, concepto) 
 * para garantizar la integridad histórica: si un cliente cambia su CIF hoy, las 
 * facturas emitidas ayer deben conservar el CIF antiguo.
 * * @property int $id ID único de la factura.
 * @property int $cuota_id ID de la cuota origen.
 * @property string $numero_factura Código único de factura (Ej: FAC-2024-0001).
 * @property string $cliente_nombre Nombre del cliente en el momento de la emisión.
 * @property string $cliente_cif CIF del cliente en el momento de la emisión.
 * @property string $ruta_pdf Ruta física del archivo generado en el almacenamiento privado.
 * @property bool $enviada Indica si el PDF ha sido enviado por correo al cliente.
 */
class Factura extends Model
{
    use HasFactory;

    /**
     * @brief Tabla asociada en la base de datos.
     * @var string
     */
    protected $table = 'facturas';

    /**
     * @brief Atributos asignables de forma masiva.
     * * Nota: Se incluyen campos del cliente y la cuota para evitar 
     * inconsistencias legales por cambios posteriores en otros modelos.
     * @var array<int, string>
     */
    protected $fillable = [
        'cuota_id',
        'numero_factura',
        'cliente_nombre',
        'cliente_cif',
        'concepto',
        'importe',
        'moneda',
        'enviada',
        'ruta_pdf',
    ];

    /**
     * @brief Relación Many-to-One con la Cuota de origen.
     * * **Uso de `withTrashed()`**: Es crítico mantener la relación incluso si la 
     * cuota ha sido eliminada lógicamente (Soft Delete), ya que la factura 
     * sigue existiendo a efectos contables y fiscales.
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cuota()
    {
        return $this->belongsTo(Cuota::class, 'cuota_id')->withTrashed();
    }
}
