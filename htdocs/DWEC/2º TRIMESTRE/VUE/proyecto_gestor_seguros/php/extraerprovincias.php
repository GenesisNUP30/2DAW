<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión
include "conexion.php";

// Consulta simple para el listado principal
$sql = "SELECT id, nombre FROM gestor_provincias ORDER BY nombre ASC";
$resultado = $conexion->query($sql);

$provincias = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $provincias[] = $fila;
    }
}

echo json_encode($provincias);