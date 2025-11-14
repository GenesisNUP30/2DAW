<?php

$host = "ieslamarisma.net/phpmyadmin";
$user = "genesisnatalya";
$password = "jy*fD4@i3CWlgi4h";
$base_de_datos = "genesisnatalya";

try {
    $conexion = mysqli_connect($host, $user, $password, $base_de_datos);
    mysqli_set_charset($conexion, 'utf8');
} catch (Exception $ex) {
    echo "Ha  fallado la conexión <br>";
    echo $ex->getMessage();
    exit;
}