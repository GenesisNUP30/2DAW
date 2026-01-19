<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Model
{
    use HasFactory;

    /**
     * Tabla asociada con el modelo en la base de datos.
     *
     * @var string
     */
    protected $table = 'empleados';

    /**
     * Campos que se guardarán en la base de datos cuando se
     * haga un create o update.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'dni',
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'fecha_alta',
        'tipo',
    ];

    /**
     * 
     * 
     */

    protected $casts = [
        'fecha_alta' => 'date',
    ];

    public function usuario()
    {
        return $this->hasOne(User::class);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'operario_id');
    }
}
