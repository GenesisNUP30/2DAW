<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cuota extends Model
{
    use HasFactory;

    /**
     * Tabla asociada con el modelo en la base de datos.
     *
     * @var string
     */
    protected $table = 'cuotas';

    /**
     * Campos que se guardarán en la base de datos cuando se
     * haga un create o update.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'cliente_id',
        'concepto',
        'fecha_emision',
        'importe',
        'pagada',
        'fecha_pago',
        'notas'
    ];
    
    protected $casts = [
        'fecha_emision' => 'date',
        'importe' => 'decimal:2',
        'fecha_pago' => 'date',
        'pagada' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

}
