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

    public function clone() {
        throw new \Exception("No se permite la clonación de esta clase.");
    }

    public static function getInstance() 
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
                return true;
            }
        }
        return false;
    }

}