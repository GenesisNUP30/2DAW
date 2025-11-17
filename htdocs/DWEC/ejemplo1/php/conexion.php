<?php

$host = "localhost";
$usuario = "genesisnatalya";
$password = "jy*fD4@i3CWlgi4h";
$base_de_datos = "genesisnatalya";
// $host = "localhost";
// $usuario = "root";
// $password = "";
// $base_de_datos = "instituto";

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>