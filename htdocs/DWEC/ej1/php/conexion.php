<?php
$host = "localhost";
$usuario = "genesisnatalya";
$password = "jy*fD4@i3CWlgi4h";
$base_de_datos = "genesisnatalya";


// Crear conexión
$conexion = new mysqli($host, $usuario, $password, $base_de_datos);

// Verificar conexión
$conexion->set_charset("utf8mb4");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
