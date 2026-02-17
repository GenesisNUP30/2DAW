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
        'cuenta_corriente',
        'pais',
        'moneda',
        'importe_cuota_mensual',
    ];

    public $timestamps = false;

    protected $casts = [
        'importe_cuota_mensual' => 'decimal:2',
    ];

    /**
     * Relación: Un cliente puede tener muchas tareas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Relación: Un cliente puede tener muchas cuotas
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }

    /**
     * Relación: Un cliente pertenece a un país
     */
    public function paisRelacion()
    {
        return $this->belongsTo(Pais::class, 'pais', 'iso2');
    }

    // Verificar si está dado de baja
    public function isBaja(): bool
    {
        return $this->fecha_baja !== null;
    }

    // Verificar si está activo
    public function isActivo(): bool
    {
        return $this->fecha_baja === null;
    }

    // ==================== SCOPES ====================
    /**
     * Scope: Ordenar clientes por nombre
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('nombre');
    }

    /**
     * Scope: Cargar la relación del país
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConPais($query)
    {
        return $query->with('paisRelacion');
    }
}
