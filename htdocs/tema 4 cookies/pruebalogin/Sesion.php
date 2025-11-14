<?php

class Sesion
{
    private static ?self $instance = null;

    private function __construct()
    {
        session_start();
    }
    /**
     * Patron singleton
     * 
     * @return self
     */

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function estaDentro(): bool
    {
        return !empty($_SESSION['dentro']);
    }

    public function salir() : void
    {
        $_SESSION['dentro'] = false;
    }

    public static function registrarUsuario(int $userId)
    {
        $_SESSION['dentro'] = true;
        $_SESSION['user_id'] = $userId;
    }

    public function obligaAQueEsteDentro()
    {
        if (!$this->estaDentro()) {
            header('Location: login_entrar.php');
            exit;
        }
    }

    public function destruir(): void
    {
        session_destroy();
    }
}
