<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @class Tarea
 * @brief Modelo que representa una orden de trabajo o incidencia en el sistema.
 * * Gestiona la logística de las intervenciones técnicas, vinculando a los clientes
 * con los operarios. Incluye información de contacto, ubicación geográfica y 
 * control de estados (Pendiente, Realizada, etc.).
 * * @property int $id ID de la tarea.
 * @property string $estado Código del estado ('P' para pendiente, 'R' para realizada, etc.).
 * @property int|null $operario_id ID del usuario tipo operario asignado.
 * @property \Carbon\Carbon $fecha_realizacion Fecha prevista para la intervención.
 */
class Tarea extends Model
{
    use HasFactory;

   /**
     * @brief Tabla asociada en la base de datos.
     * @var string
     */
    protected $table = 'tareas';

    /**
     * @brief Atributos asignables de forma masiva.
     * Incluye datos de contacto directo para que el operario pueda comunicarse
     * sin necesidad de acceder a la ficha global del cliente.
     * @var array<int, string>
     */
    protected $fillable = [
        'cliente_id',
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
     * @brief Desactiva el manejo automático de las columnas 'created_at' y 'updated_at'.
     * @var boolean
     */
    public $timestamps = false;

   /**
     * @brief Casting de atributos a tipos nativos de PHP o Carbon.
     * @return array<string, string>
     */
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_realizacion' => 'date',
    ];

    // ======================== RELACIONES ========================
    /**
     * @brief Relación Many-to-One con Cliente.
     * Una tarea siempre pertenece a un cliente específico.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

/**
     * @brief Relación Many-to-One con User (Operario).
     * Vincula la tarea con el empleado encargado de ejecutarla.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operario()
    {
        return $this->belongsTo(User::class, 'operario_id');
    }

    // ==================== SCOPES ( DE CONSULTA) ====================

    /**
     * @brief Scope para cargar relaciones y evitar el problema de consultas N+1.
     * Recomendado para listados generales de tareas.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeConRelaciones($query)
    {
        return $query->with(['cliente', 'operario']);
    }

    /**
     * @brief Filtra las tareas que pertenecen a un operario en particular.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $operarioId ID del usuario.
     */
    public function scopeParaOperario($query, $operarioId)
    {
        return $query->where('operario_id', $operarioId);
    }

    /**
     * @brief Ordena cronológicamente las tareas (de más reciente a más antigua).
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOrdenadasPorFecha($query)
    {
        return $query->orderByDesc('fecha_realizacion');
    }

    /**
     * @brief Filtra solo las tareas con estado 'P' (Pendiente).
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'P');
    }

    /**
     * @brief Obtiene las tareas cuya fecha de realización está en los próximos 5 días.
     * Ideal para widgets de "Próximas Tareas" en el Dashboard.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeTareasProximas($query)
    {
        $hoy = Carbon::today();
        $limite = Carbon::today()->addDays(5);

        return $query
            ->whereBetween('fecha_realizacion', [$hoy, $limite])
            ->orderBy('fecha_realizacion', 'asc');
    }

    /**
     * @brief Identifica incidencias que aún no han sido asignadas a ningún operario.
     * Filtra estados 'B' (Borrador/Nueva) o 'P' (Pendiente) sin responsable.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeIncidenciasSinAsignar($query)
    {
        return $query->whereNull('operario_id')
            ->where(function ($q) {
                $q->where('estado', 'B')
                    ->orWhere('estado', 'P');
            })
            ->orderBy('fecha_realizacion', 'asc');
    }
}
