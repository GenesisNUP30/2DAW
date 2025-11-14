<?php

$host = "localhost";
$user = "root";
$password = "";
$base_de_datos = "instituto";

try {
    $conexion = mysqli_connect($host, $user, $password, $base_de_datos);
    mysqli_set_charset($conexion, 'utf8');
} catch (Exception $ex) {
    echo "Ha  fallado la conexión <br>";
    echo $ex->getMessage();
    exit;
}