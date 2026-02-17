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

    /**
     * Convertir automáticamente los atributos a tipos de datos específicos
     *
     * @return array<string, string>
     */
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_realizacion' => 'date',
    ];

    // ======================== RELACIONES ========================
    /**
     * Relación: Una tarea pertenece a un cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación: Una tarea está asignada a un operario (usuario)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function operario()
    {
        return $this->belongsTo(User::class, 'operario_id');
    }

    // ==================== SCOPES ( DE CONSULTA) ====================

    /**
     * Scope: Cargar las relaciones cliente y operario en la consulta
     * 
     * Uso: Tarea::conRelaciones()->get()
     * 
     * Beneficio: Evita el problema N+1 al mostrar tareas con sus relaciones
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConRelaciones($query)
    {
        return $query->with(['cliente', 'operario']);
    }

    /**
     * Scope: Filtrar tareas asignadas a un operario específico
     * 
     * Uso: Tarea::paraOperario($userId)->get()
     *  
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $operarioId ID del operario
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParaOperario($query, $operarioId)
    {
        return $query->where('operario_id', $operarioId);
    }

    /**
     * Scope: Ordenar tareas por fecha de realización descendente
     * 
     * Beneficio: Las tareas más recientes aparecen primero
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdenadasPorFecha($query)
    {
        return $query->orderByDesc('fecha_realizacion');
    }

    /**
     * Scope: Filtrar tareas con estado 'P' (Pendientes)
     * 
     * Beneficio: Obtiene rápidamente las tareas pendientes
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'P');
    }
}
