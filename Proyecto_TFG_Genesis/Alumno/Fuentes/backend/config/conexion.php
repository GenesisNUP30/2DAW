<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'proyectotfg_erp');

//Conexión a la base de datos
function conectarDB()
{
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conexion->connect_error) {
        die(json_encode([
            'success' => false,
            'message' => 'Error de conexión a la base de datos: ' . $conexion->connect_error
        ]));
    }

    $conexion->set_charset("utf8mb4");
    
    return $conexion;
}
