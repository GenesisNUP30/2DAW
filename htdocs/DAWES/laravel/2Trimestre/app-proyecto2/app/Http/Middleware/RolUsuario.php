<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class RolUsuario
 * @brief Middleware para el control de acceso basado en roles (RBAC).
 * * Este middleware intercepta las peticiones HTTP y verifica si el usuario 
 * autenticado posee el rol necesario para acceder a un recurso específico.
 * * **Funcionamiento**: 
 * Si el usuario no está autenticado o su atributo `tipo` no coincide con el 
 * parámetro `$rol`, la petición se interrumpe con un error 403 (Forbidden).
 * * @package App\Http\Middleware
 */
class RolUsuario
{
    /**
     * @brief Gestiona la petición entrante.
     * * @param Request $request Objeto de la petición actual.
     * @param Closure $next Siguiente middleware o controlador en el pipeline.
     * @param string $rol El rol requerido para pasar el filtro (ej: 'administrador', 'operario').
     * @return Response Retorna la respuesta de la siguiente capa o aborta con 403.
     * * @exception \Symfony\Component\HttpKernel\Exception\HttpException Lanza 403 si el acceso es denegado.
     */
    public function handle(Request $request, Closure $next, string $rol): Response
    {
        $user = Auth::user();

        /**
         * Verificación 1: Existencia de sesión.
         * Aunque suele ir precedido por el middleware 'auth', esta comprobación 
         * añade una capa extra de seguridad.
         */
        if (!$user) {
            abort(403);
        }

       /**
         * Verificación 2: Coincidencia de rol.
         * Compara el campo 'tipo' del modelo User con el rol exigido en la ruta.
         */
        if ($user->tipo !== $rol) {
            abort(403);
        }

        return $next($request);
    }
}
