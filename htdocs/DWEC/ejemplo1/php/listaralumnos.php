<?php
//Encabezado para indicar que la respueta es json
header("Content-Type: application/json; charset=utf-8");

include_once "conexion.php";


$sql = "SELECT * FROM alumnos";
$resultado = mysqli_query($conexion, $sql);

$alumnos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $alumnos[] = $fila;
    }
    echo json_encode($alumnos);
}

mysqli_close($conexion);
