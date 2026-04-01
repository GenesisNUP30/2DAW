<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Pais extends Model
{
    use HasFactory;
    /**
     * Tabla asociada con el modelo en la base de datos.
     */
    protected $table = 'paises';

    /**
     * Campos que se guardarán en la base de datos cuando se
     * haga un create o update.
     */
    protected $fillable = [
        'id',
        'iso2',
        'iso3',
        'prefijo',
        'nombre',
        'continente',
        'subcontinente',
        'iso_moneda',
        'nombre_moneda',
    ];

    /**
     * Scope: Ordenar paises por nombre
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('nombre');
    }

    /**
     * Scope: Filtrar países por continente
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $continente
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeDeContinente($query, $continente)
    {
        return $query->where('continente', $continente);
    }
}
