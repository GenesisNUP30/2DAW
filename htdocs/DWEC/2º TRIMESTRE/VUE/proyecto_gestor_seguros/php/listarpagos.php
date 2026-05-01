<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$poliza_id = isset($_GET['poliza_id']) ? intval($_GET['poliza_id']) : 0;

if ($poliza_id <= 0) {
    echo json_encode(["status" => false, "mensaje" => "ID de póliza no válido"]);
    exit;
}

$sql = "SELECT id, poliza_id, fecha, importe 
        FROM gestor_pagos 
        WHERE poliza_id = ? 
        ORDER BY fecha DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $poliza_id);
$stmt->execute();
$resultado = $stmt->get_result();

$pagos = [];
while ($fila = $resultado->fetch_assoc()) {
    $pagos[] = $fila;
}

echo json_encode([
    "status" => true,
    "data" => $pagos
]);

$stmt->close();
$conexion->close();