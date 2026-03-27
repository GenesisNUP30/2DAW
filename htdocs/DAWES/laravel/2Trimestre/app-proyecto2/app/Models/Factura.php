<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';

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
     * Relación con la cuota de origen.
     * Usamos withTrashed porque la factura debe ser accesible 
     * incluso si la cuota ha sido desactivada (borrado lógico).
     */
    public function cuota()
    {
        return $this->belongsTo(Cuota::class, 'cuota_id')->withTrashed();
    }
}
