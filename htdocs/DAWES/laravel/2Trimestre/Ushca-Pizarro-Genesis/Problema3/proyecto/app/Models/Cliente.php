<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @class Cliente
 * @brief Modelo que representa a los clientes de la plataforma.
 * * Gestiona la información fiscal, de contacto y de facturación de los clientes.
 * Actúa como nodo central conectando las tareas realizadas y las cuotas generadas.
 * * @property int $id ID único del cliente.
 * @property string $cif Código de Identificación Fiscal.
 * @property string $nombre Nombre o razón social.
 * @property float $importe_cuota_mensual Importe base para las remesas automáticas.
 * @property string $pais Código ISO2 del país vinculado.
 */
class Cliente extends Model
{
    use HasFactory;

    /**
     * @brief Tabla asociada en la base de datos.
     * @var string
     */
    protected $table = 'clientes';

    /**
     * @brief Atributos asignables de forma masiva.
     * @var array<int, string>
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
        'fecha_alta',
        'fecha_baja',
    ];

    /**
     * @brief Desactiva las columnas 'created_at' y 'updated_at' de Eloquent.
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Convertir automáticamente los atributos a tipos de datos específicos
     *
     * @return array<string, string>
     */
    protected $casts = [
        'importe_cuota_mensual' => 'float',
        'fecha_alta' => 'date',
        'fecha_baja' => 'date',
    ];

    /**
     * @brief Relación One-to-Many con Tarea.
     * Un cliente puede tener múltiples solicitudes de trabajo o incidencias.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * @brief Relación One-to-Many con Cuota.
     * Un cliente acumula un histórico de cuotas (mensuales y excepcionales).
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }

    /**
     * @brief Relación Many-to-One con Pais.
     * Vincula el código 'pais' del cliente con la columna 'iso2' de la tabla paises.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paisRelacion()
    {
        return $this->belongsTo(Pais::class, 'pais', 'iso2');
    }

    /**
     * @brief Relación filtrada para obtener solo cuotas impagadas.
     * Útil para optimizar consultas de morosidad.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuotasPendientes()
    {
        return $this->cuotas()->where('fecha_pago', null);
    }

    // ==================== MÉTODOS DE ESTADO ====================

    /**
     * @brief Determina si el cliente ha sido dado de baja.
     * @return bool
     */
    public function isBaja(): bool
    {
        return $this->fecha_baja !== null;
    }

    /**
     * @brief Determina si el cliente está actualmente activo.
     * @return bool
     */
    public function isActivo(): bool
    {
        return $this->fecha_baja === null;
    }

    /**
     * @brief Verifica si existen cuotas sin fecha de pago asociadas al cliente.
     * Este método es crucial antes de procesar bajas para evitar deudas perdidas.
     * @return bool True si tiene al menos una cuota pendiente.
     */
    public function tieneCuotasPendientes(): bool
    {
        return $this->cuotasPendientes()->count() > 0;
    }
    
    // ==================== SCOPES ====================
    
    /**
     * @brief Ordena los clientes alfabéticamente por su nombre comercial.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('nombre');
    }

   /**
     * @brief Eager Loading para la relación del país.
     * Mejora el rendimiento al listar clientes con su bandera o moneda.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeConPais($query)
    {
        return $query->with('paisRelacion');
    }

    /**
     * @brief Filtra la consulta para devolver únicamente clientes sin fecha de baja.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeActivos($query)
    {
        return $query->whereNull('fecha_baja');
    }
    
}
