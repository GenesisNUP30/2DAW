<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión
include "conexion.php";

// Consulta simple para el listado principal
$sql = "SELECT id, tipo_cliente, nombre, apellidos, email, telefono FROM clientes ORDER BY id DESC";
$resultado = $conexion->query($sql);

$clientes = [];

if ($resultado && $resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $clientes[] = $fila;
    }
    echo json_encode([
        "status" => true,
        "data" => $clientes
    ]);
} else {
    echo json_encode([
        "status" => false,
        "mensaje" => "No hay clientes registrados",
        "data" => []
    ]);
}