<?php

try {
    $conexion = mysqli_connect("localhost", "root", "", "bdprovincias");
    mysqli_set_charset($conexion, 'utf8');
} catch (Exception $ex) {
    echo "Ha  fallado la conexión";
    echo $ex->getMessage();
    exit;
}

