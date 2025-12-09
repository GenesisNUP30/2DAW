<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "repaso_gestion";

$conexion = new mysqli($host, $user, $password, $db);

$conexion->set_charset("utf8mb4");
if ($conexion->connect_error) {
    die("No se pudo conectar a la base de datos: " . mysqli_connect_error());
}
