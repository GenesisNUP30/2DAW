<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarea extends Model
{
    use HasFactory;

    /**
     * Tabla asociada con el modelo en la base de datos.
     *
     * @var string
     */
    protected $table = 'tareas';

    /**
     * Campos que se guardarán en la base de datos cuando se
     * haga un create o update.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'cliente_id',
        'operario_id',
        'persona_contacto',
        'telefono_contacto',
        'operario_id',
        'descripcion',
        'correo_contacto',
        'direccion',
        'poblacion',
        'codigo_postal',
        'provincia',
        'estado',
        'fecha_realizacion',
        'anotaciones_anteriores'
    ];

    /**
     * Evitamos que se actualicen automáticamente las fechas
     *
     * @var boolean
     */
    public $timestamps = false; 

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_realizacion' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function operario()
    {
        return $this->belongsTo(User::class, 'operario_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'P');
    }
}
