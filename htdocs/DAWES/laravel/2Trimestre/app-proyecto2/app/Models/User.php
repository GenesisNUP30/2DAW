<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
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
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'selector_hash',
        'validator_hash',
    ];

    /**
     * Convertir automáticamente los atributos a tipos de datos específicos
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'expiry_date' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== MÉTODOS DE ACCESO ====================

    /**
     * Verificar si el usuario es administrador
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->tipo === 'administrador';
    }

    /**
     * Verificar si el usuario es operario
     * 
     * @return bool
     */
    public function isOperario(): bool
    {
        return $this->tipo === 'operario';
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

     // ==================== SCOPES (MÉTODOS DE CONSULTA) ====================

    /**
     * Scope: Filtrar usuarios activos (sin fecha de baja)
     * 
     * Uso: User::activos()->get()
     * Equivale a: User::whereNull('fecha_baja')->get()
     * 
     * Beneficio: Obtiene solo empleados que están dados de alta
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivos($query)
    {
        return $query->whereNull('fecha_baja');
    }

    /**
     * Scope: Filtrar usuarios dados de baja
     * 
     * Uso: User::deBaja()->get()
     * Beneficio: Obtiene empleados que están dados de baja
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeBaja($query)
    {
        return $query->whereNotNull('fecha_baja');
    }

    /**
     * Scope: Filtrar usuarios de tipo operario
     * 
     * Uso: User::operarios()->get()
     * 
     * Beneficio: Obtiene solo los empleados con rol de operario
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOperarios($query)
    {
        return $query->where('tipo', 'operario');
    }


    /**
     * Scope: Filtrar usuarios de tipo administrador
     * 
     * Uso: User::administradores()->get()
     * Equivale a: User::where('tipo', 'administrador')->get()
     * 
     * Beneficio: Obtiene solo los empleados con rol de administrador
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdministradores($query)
    {
        return $query->where('tipo', 'administrador');
    }

    /**
     * Scope: Ordenar usuarios por nombre
     *
     * @param [type] $query
     * @return void
     */
    public function scopeOrdenadosPorNombre($query)
    {
        return $query->orderBy('name');
    }

    /**
     * Scope: Excluir un usuario específico de los resultados
     * 
     * Uso: User::excluyendo($userId)->get()
     * 
     * Beneficio: Útil para listar empleados excluyendo al usuario actual
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId ID del usuario a excluir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExcluyendo($query, $userId)
    {
        return $query->where('id', '!=', $userId);
    }


   // ==================== RELACIONES ====================

    /**
     * Relación: Un usuario puede tener muchas tareas asignadas
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tareasAsignadas()
    {
        return $this->hasMany(Tarea::class, 'operario_id');
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Obtener la última sesión del usuario
     * 
     * Busca en la tabla 'sessions' la última actividad del usuario
     * y la formatea con la zona horaria de España
     * 
     * @return string Fecha formateada o 'Nunca'
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
