<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @class User
 * @brief Modelo que representa a los usuarios del sistema (empleados).
 * * Gestiona tanto a Administradores como a Operarios, controlando su acceso, 
 * sus datos personales y su relación con las tareas asignadas.
 * * @property int $id ID único del usuario.
 * @property string $name Nombre completo.
 * @property string $tipo Rol del usuario ('administrador' o 'operario').
 * @property \Carbon\Carbon $fecha_alta Fecha en la que empezó en la empresa.
 * @property \Carbon\Carbon|null $fecha_baja Fecha de cese, si existe.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @brief Atributos asignables de forma masiva (Mass Assignment).
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'telefono',
        'direccion',
        'fecha_alta',
        'fecha_baja',
        'tipo',
    ];

    /**
     * @brief Atributos que se ocultan en las respuestas JSON/Serialización.
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'selector_hash',
        'validator_hash',
    ];

    /**
     * @brief Define el casting de tipos para atributos específicos.
     * * Convierte automáticamente strings de la BD a objetos Carbon (fechas) o tipos booleanos.
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'expiry_date' => 'datetime',
        'password' => 'hashed',
        'fecha_alta' => 'date',
        'fecha_baja' => 'date',
    ];

    // ==================== MÉTODOS DE ACCESO ====================

    /**
     * @brief Comprueba si el usuario tiene privilegios de administrador.
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->tipo === 'administrador';
    }

    /**
     * @brief Comprueba si el usuario es un operario.
     * @return bool
     */
    public function isOperario(): bool
    {
        return $this->tipo === 'operario';
    }

    /**
     * @brief Indica si el usuario está actualmente en estado de baja.
     * @return bool True si tiene fecha de baja asignada.
     */
    public function isBaja(): bool
    {
        return $this->fecha_baja !== null;
    }

    /**
     * @brief Indica si el usuario está activo en el sistema.
     * @return bool True si no tiene fecha de baja.
     */
    public function isActivo(): bool
    {
        return $this->fecha_baja === null;
    }

     // ==================== SCOPES (MÉTODOS DE CONSULTA) ====================

   /**
     * @brief Filtra la consulta para obtener solo usuarios activos.
     * * Uso: `User::activos()->get()`
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeActivos($query)
    {
        return $query->whereNull('fecha_baja');
    }

    /**
     * @brief Filtra la consulta para obtener solo usuarios de baja.
     * * Uso: `User::deBaja()->get()`
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeDeBaja($query)
    {
        return $query->whereNotNull('fecha_baja');
    }

    /**
     * @brief Filtra la consulta para obtener solo operarios.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOperarios($query)
    {
        return $query->where('tipo', 'operario');
    }


    /**
     * @brief Filtra la consulta para obtener solo administradores.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeAdministradores($query)
    {
        return $query->where('tipo', 'administrador');
    }

    /**
     * @brief Ordena los resultados alfabéticamente por nombre.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('name');
    }

   /**
     * @brief Excluye a un usuario por ID de la consulta actual.
     * * Muy útil para listados de "asignar a otros compañeros" donde no quieres que aparezca el usuario actual.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId ID a omitir.
     */
    public function scopeExcluyendo($query, $userId)
    {
        return $query->where('id', '!=', $userId);
    }


   // ==================== RELACIONES ====================

    /**
     * @brief Relación One-To-Many con las tareas.
     * * Un operario tiene muchas tareas asignadas a su cargo.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tareasAsignadas()
    {
        return $this->hasMany(Tarea::class, 'operario_id');
    }

    // ==================== MÉTODOS AUXILIARES ====================

   /**
     * @brief Obtiene la fecha y hora de la última actividad del usuario en el sistema.
     * * Consulta la tabla nativa de sesiones de Laravel, ajustando la zona horaria a España.
     * @return string Retorna la fecha formateada o 'Nunca' si no hay registro de sesión.
     */
    public function ultimaSesion()
    {
        $session = \DB::table('sessions')
            ->where('user_id', $this->id)
            ->orderBy('last_activity', 'desc')
            ->first();

        if ($session) {
            return \Carbon\Carbon::createFromTimestamp($session->last_activity)
                ->setTimezone('Europe/Madrid')
                ->format('d/m/Y H:i');
        }

        return 'Nunca';
    }
}
