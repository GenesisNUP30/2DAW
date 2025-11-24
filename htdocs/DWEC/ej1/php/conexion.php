<?php
$host = "localhost";
$usuario = "genesisnatalya";
$contraseña = "jy*fD4@i3CWlgi4h";
$base_de_datos = "genesisnatalya";

$conexion = new mysqli($host, $usuario, $contraseña, $base_de_datos);

$conexion->set_charset("utf8mb4");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
