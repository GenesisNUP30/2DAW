<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión
include "conexion.php";

// Verificamos si recibimos un cliente_id específico
$cliente_id = isset($_GET['cliente_id']) ? intval($_GET['cliente_id']) : null;

// Seleccionamos campos de póliza y el nombre combinado del cliente
$sql = "SELECT 
    p.*,
    CONCAT_WS(' ', c.nombre, c.apellidos) AS nombre_cliente
FROM polizas p
INNER JOIN clientes c ON p.cliente_id = c.id";

// Si hay cliente_id, filtramos; si no, ordenamos por fecha general
if ($cliente_id) {
    $sql .= " WHERE p.cliente_id = $cliente_id";
}

$sql .= " ORDER BY p.fecha DESC";

$resultado = $conexion->query($sql);
$polizas = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
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
