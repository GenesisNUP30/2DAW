<?php 
$host = "localhost";
$user = "root";
$pass = "";
$db = "bdprovincias";

try {
    $conexion = mysqli_connect($host, $user, $pass, $db);
    mysqli_set_charset($conexion, "utf8");
} catch (Exception $e) {
    echo "Ha fallado la conexión: " . $e->getMessage();
    exit;
}
