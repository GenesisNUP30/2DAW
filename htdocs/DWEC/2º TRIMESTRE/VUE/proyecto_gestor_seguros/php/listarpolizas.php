<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión
include "conexion.php";

// Consulta simple para el listado principal
$sql = "SELECT id, cliente_id, numero_poliza, importe_total, fecha, estado, observaciones FROM polizas ORDER BY fecha DESC";
$resultado = $conexion->query($sql);

$polizas = [];

if ($resultado && $resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $polizas[] = $fila;
    }
    echo json_encode([
        "status" => true,
        "data" => $polizas
    ]);
} else {
    echo json_encode([
        "status" => false,
        "mensaje" => "No hay pólizas registradas",
        "data" => []
    ]);
}