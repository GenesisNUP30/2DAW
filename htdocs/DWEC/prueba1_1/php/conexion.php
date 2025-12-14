<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "prueba1_1";

$conexion = new mysqli($host, $user, $pass, $db);

$conexion->set_charset("utf8mb4");
if ($conexion->connect_error) {
    echo "No se pudo conectar a la base de datos: " . mysqli_connect_error();
}
