<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuota extends Model
{
    use HasFactory, SoftDeletes;

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
        'fecha_pago',
        'tipo',
        'notas'
    ];

    /**
     * Evitamos que se actualicen automáticamente las fechas
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Convertir automáticamente los atributos a tipos de datos específicos
     *
     * @return array<string, string>
     */
    protected $casts = [
        'fecha_emision' => 'date',
        'importe' => 'float',
        'fecha_pago' => 'date',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación: Una cuota pertenece a un cliente
     *
     * @return void
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: Una cuota tiene una factura (o ninguna)
     */
    public function factura()
    {
        return $this->hasOne(Factura::class);
    }

    // ==================== MÉTODOS DE ACCESO ====================
    /**
     * Saber si una cuota está pagada o no
     */
    public function isPagada(): bool
    {
        return $this->fecha_pago !== null;
    }

    /**
     * Saber si una cuota está pendiente
     *
     * @return boolean
     */
    public function isPendiente(): bool
    {
        return $this->fecha_pago === null;
    }

    // ==================== SCOPES ====================
    /**
     * Scope: Ordenar cuotas por fecha de emisión descendente
     *
     * @param [type] $query
     * @return void
     */
    public function scopeOrdenadasPorFecha($query)
    {
        return $query->orderByDesc('fecha_emision');
    }

    /**
     * Scope: Cargar las relaciones cliente en la consulta
     * Beneficio: Evita el problema N+1 al mostrar cuotas con sus relaciones
     * @param [type] $query
     * @return void
     */
    public function scopeConRelaciones($query)
    {
        return $query->with(['cliente']);
    }

    /**
     * Scope: Filtrar cuotas pendientes de pagar
     *
     * @param [type] $query
     * @return void
     */
    public function scopePendientes($query)
    {
        return $query->whereNull('fecha_pago');
    }

    /**
     * Scope: Filtrar cuotas ya pagadas
     *
     * @param [type] $query
     * @return void
     */
    public function scopePagadas($query)
    {
        return $query->whereNotNull('fecha_pago');
    }

    /**
     * Scope: cuotas mensuales
     */
    public function scopeMensuales($query)
    {
        return $query->where('tipo', 'mensual');
    }

    /**
     * Scope: cuotas excepcionales
     */
    public function scopeExcepcionales($query)
    {
        return $query->where('tipo', 'excepcional');
    }
}
