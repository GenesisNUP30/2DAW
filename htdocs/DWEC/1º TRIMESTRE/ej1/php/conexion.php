<?php
$host = "localhost";
$usuario = "root";
$contraseña = "";
$base_de_datos = "alumnos";

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

$conexion->set_charset("utf8mb4");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
