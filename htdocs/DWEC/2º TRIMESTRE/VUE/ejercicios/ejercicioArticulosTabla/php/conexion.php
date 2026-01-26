<?php
$host = "localhost";
$user = "root";
$pass="";
$db = "articulos_tabla";

$conexion = new mysqli($host, $user, $pass, $db);

$conexion->set_charset("utf8mb4");
if ($conexion->connect_error) {
    echo "No se pudo conectar a la base de datos: " . $conexion->connect_error;
}

