<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario  = "root";
$password = ""; 
$basedatos = "gestor_seguros";

// Crear la conexión usando la extensión mysqli
$conexion = new mysqli($servidor, $usuario, $password, $basedatos);

// Comprobar si hay errores de conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres a utf8 para que las tildes y ñ se vean bien
$conexion->set_charset("utf8mb4");
