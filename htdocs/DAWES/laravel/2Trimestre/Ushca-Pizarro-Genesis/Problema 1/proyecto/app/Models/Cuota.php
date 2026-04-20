<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @class Cuota
 * @brief Modelo que representa las obligaciones de pago de los clientes.
 * * Gestiona tanto las cuotas mensuales recurrentes como las excepcionales.
 * Incorpora el uso de **Soft Deletes** para permitir una "papelera de reciclaje"
 * y mantener la trazabilidad contable.
 * * @property int $id ID único de la cuota.
 * @property int $cliente_id Relación con el cliente deudor.
 * @property float $importe Cuantía económica de la cuota.
 * @property string $tipo Tipo de cuota ('mensual' o 'excepcional').
 * @property \Carbon\Carbon|null $fecha_pago Fecha en la que el cliente abonó la cuota.
 * @property \Carbon\Carbon|null $deleted_at Fecha de eliminación lógica del registro.
 */
class Cuota extends Model
{
     /** @use SoftDeletes<\Database\Eloquent\SoftDeletes> */
    use HasFactory, SoftDeletes;

    /**
     * @brief Atributos asignables de forma masiva.
     * @var array<int, string>
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
     * @brief Desactiva el control automático de timestamps (created_at/updated_at).
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @brief Conversión de atributos a tipos nativos de PHP.
     * * Incluye 'deleted_at' para el correcto funcionamiento de SoftDeletes.
     * @return array<string, string>
     */
    protected $casts = [
        'fecha_emision' => 'date',
        'importe' => 'float',
        'fecha_pago' => 'date',
        'deleted_at' => 'datetime',
    ];

    // ======================== RELACIONES ========================

    /**
     * @brief Relación Many-to-One con Cliente.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * @brief Relación One-to-One con Factura.
     * Una cuota puede dar lugar a una única factura física/legal.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function factura()
    {
        return $this->hasOne(Factura::class);
    }

    /**
     * @brief Comprueba si la cuota ha sido abonada.
     * @return bool
     */
    public function isPagada(): bool
    {
        return $this->fecha_pago !== null;
    }

    /**
     * @brief Comprueba si la cuota está aún pendiente de cobro.
     * @return bool
     */
    public function isPendiente(): bool
    {
        return $this->fecha_pago === null;
    }

    // ==================== SCOPES ====================
    
    /**
     * @brief Ordena las cuotas por fecha de emisión (de la más reciente a la más antigua).
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOrdenadasPorFecha($query)
    {
        return $query->orderByDesc('fecha_emision');
    }

    /**
     * @brief Carga la relación con el cliente para optimizar las consultas (Eager Loading).
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeConRelaciones($query)
    {
        return $query->with(['cliente']);
    }

    /**
     * @brief Filtra la consulta para mostrar solo cuotas impagadas.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendientes($query)
    {
        return $query->whereNull('fecha_pago');
    }

    /**
     * @brief Filtra la consulta para mostrar solo cuotas ya cobradas.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePagadas($query)
    {
        return $query->whereNotNull('fecha_pago');
    }

    /**
     * @brief Filtra solo las cuotas de tipo ordinario/mensual.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeMensuales($query)
    {
        return $query->where('tipo', 'mensual');
    }

    /**
     * @brief Filtra solo las cuotas de tipo extra u ocasional.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeExcepcionales($query)
    {
        return $query->where('tipo', 'excepcional');
    }
}
