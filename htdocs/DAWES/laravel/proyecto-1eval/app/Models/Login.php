<?php

namespace App\Models;

class Login 
{
    private static $instance = null;

    private $usuarios =[
        'admin' => ['password' => 'admin123', 'rol' => 'administrador'],
        'operario1' => ['password' => 'operario123', 'rol' => 'operario'],
    ];

    public function __construct()
    {
        // Iniciar sesión
        session_start();
        // Constructor privado para evitar instanciación externa
    }

    public function __clone() {
        throw new \Exception("No se permite la clonación de esta clase.");
    }

    public static function getInstance() : Login
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function validarLogin(string $usuario, string $password): bool
    {
       if (isset($this->usuarios[$usuario])) {
            if ($this->usuarios[$usuario]['password'] === $password) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = $this->usuarios[$usuario]['rol'];
                $_SESSION['logado'] = true;
                $_SESSION['hora_logado'] = date('Y-m-d H:i:s');
                return true;
            }
            return false;
        }
        return false;
    }

    public function logout() : void
    {
        $_SESSION = [];
        session_destroy();
    }
  
    public function isLogged() : bool
    {
        return !empty($_SESSION['logado']);
    }

    public function onlyLogged() : void
    {
        if (!$this->isLogged())
        {
            header('Location: /DAWES/laravel/proyecto-1eval/public/login');
            exit();
        }
    }

    public function getUsuario() : string
    {
        return $_SESSION['usuario'];
    }

    public function getRol() : string
    {
        return $_SESSION['rol'];
    }

    public function getHoraLogeada() : string
    {
        return $_SESSION['hora_logeada'];
    }

}