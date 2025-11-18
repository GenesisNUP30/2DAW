<?php

namespace App\Models;

class DB {
    private static $instance;
    private $conexion;

    public static function getInstance() 
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
    }
}