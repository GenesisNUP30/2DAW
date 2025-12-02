<?php

namespace App\Models;

use App\Models\DB;

class Sesion
{
    private static $instance = null;

    public function __construct()
    {
        // Iniciar sesión
        session_start();
        // Constructor privado para evitar instanciación externa
    }

    public function __clone()
    {
        throw new \Exception("No se permite la clonación de esta clase.");
    }

    public static function getInstance(): Sesion
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }



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

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public function isLogged(): bool
    {
        return !empty($_SESSION['logado']);
    }

    public function onlyLogged(): void
    {
        if (!$this->isLogged()) {
            miredirect('login');
        }
    }

    public function onlyOperario(): void
    {
        if ($this->getRol() !== 'operario') {
            abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
        }
    }

    public function onlyAdministrador(): void
    {
        if ($this->getRol() !== 'administrador') {
            abort(403, 'No tienes permisos para acceder a esta funcionalidad.');
        }
    }

    public function getUsuario(): string
    {
        return $_SESSION['usuario'];
    }

    public function getRol(): string
    {
        return $_SESSION['rol'];
    }

    public function getHoraLogeada(): string
    {
        return $_SESSION['hora_logado'];
    }
}
