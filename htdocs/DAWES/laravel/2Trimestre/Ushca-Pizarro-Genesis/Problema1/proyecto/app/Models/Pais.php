<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

/**
 * @class Pais
 * @brief Modelo de referencia para la gestión de países y divisas.
 * * Este modelo proporciona una base de datos estandarizada de países siguiendo los 
 * estándares ISO. Se utiliza principalmente para:
 * - Localización geográfica de los clientes.
 * - Determinación de la moneda por defecto en la facturación.
 * - Gestión de prefijos telefónicos internacionales.
 * * @property int $id ID interno.
 * @property string $iso2 Código de dos letras (Ej: ES).
 * @property string $iso3 Código de tres letras (Ej: ESP).
 * @property string $prefijo Prefijo telefónico (Ej: 34).
 * @property string $nombre Nombre oficial del país.
 * @property string $iso_moneda Código de la moneda (Ej: EUR).
 * @property string $nombre_moneda Nombre de la moneda (Ej: Euro).
 */
class Pais extends Model
{
    use HasFactory;
    
    /**
     * @brief Tabla asociada en la base de datos.
     * @var string
     */
    protected $table = 'paises';

    /**
     * @brief Atributos asignables de forma masiva.
     * @var array<int, string>
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

    // ==================== SCOPES (FILTROS DE CONSULTA) ====================

    /**
     * @brief Ordena los países alfabéticamente por su nombre común.
     * * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('nombre');
    }

    /**
     * @brief Filtra la lista de países según el continente al que pertenecen.
     * * Útil para segmentar mercados o aplicar reglas de negocio por región.
     * * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $continente Nombre del continente (Ej: 'Europe', 'Africa').
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeContinente($query, $continente)
    {
        return $query->where('continente', $continente);
    }
}
