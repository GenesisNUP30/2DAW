<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{
    use HasFactory;

    /**
     * Tabla asociada con el modelo en la base de datos.
     *
     * @var string
     */
    protected $table = 'clientes';

    /**
     * Campos que se guardarán en la base de datos cuando se
     * haga un create o update.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'cif',
        'nombre',
        'telefono',
        'correo',
        'cuenta_bancaria',
        'pais',
        'moneda',
        'importe_cuota',
    ];

    protected $casts = [
        'importe_cuota' => 'decimal:2',
    ];

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }
}
