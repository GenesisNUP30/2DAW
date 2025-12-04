<?php
// conexion.php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "ahorcado";

$conexion = mysqli_connect($host, $usuario, $clave, $bd);
$conexion->set_charset("utf8mb4");
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>