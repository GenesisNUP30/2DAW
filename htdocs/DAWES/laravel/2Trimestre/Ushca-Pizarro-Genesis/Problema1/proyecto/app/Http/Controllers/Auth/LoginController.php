<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * @class LoginController
 * @brief Controlador encargado de gestionar la autenticación de usuarios.
 * * Este controlador utiliza el trait AuthenticatesUsers para proporcionar 
 * de forma automática las funcionalidades de:
 * - Mostrar el formulario de acceso.
 * - Validar credenciales (email y password).
 * - Controlar el número de intentos fallidos (Throttling).
 * - Gestionar el cierre de sesión (Logout).
 * * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

   /**
     * @brief Ruta de redirección tras un inicio de sesión exitoso.
     * * Una vez que el usuario se autentica correctamente, el sistema lo 
     * enviará automáticamente a la ruta definida aquí.
     * * @var string
     */
    protected $redirectTo = '/home';

   /**
     * @brief Constructor del controlador.
     * * Configura los middlewares de seguridad:
     * - 'guest': Solo permite el acceso al formulario de login si el usuario no está ya autenticado.
     * - 'except logout': El método de cierre de sesión es la única excepción para usuarios autenticados.
     * - 'auth': Asegura que solo usuarios con sesión activa puedan ejecutar el método logout.
     * * @return void
     */
    public function __construct()
    {
        // El middleware 'guest' redirige al usuario al home si ya está logueado
        $this->middleware('guest')->except('logout');

        // Refuerza que para desloguearse el usuario debe estar realmente dentro
        $this->middleware('auth')->only('logout');
    }
}
