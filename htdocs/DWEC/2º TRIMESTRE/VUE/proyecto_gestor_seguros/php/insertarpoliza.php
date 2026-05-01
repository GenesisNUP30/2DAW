<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include "conexion.php";

// Leemos el JSON enviado por Vue
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['cliente_id']) || empty($data['numero_poliza'])) {
    echo json_encode(["status" => false, "mensaje" => "Faltan datos obligatorios"]);
    exit;
}

// Extraemos y saneamos (evitar inyección SQL básica)
$cliente_id    = intval($data['cliente_id']);
$numero_poliza = $conexion->real_escape_string($data['numero_poliza']);
$importe_total = floatval($data['importe_total']);
$fecha         = $conexion->real_escape_string($data['fecha']);
$estado        = $conexion->real_escape_string($data['estado']);
$observaciones = $conexion->real_escape_string($data['observaciones']);

// 1. Verificar si el número de póliza ya existe
$checkNumero = $conexion->query("SELECT id FROM gestor_polizas WHERE numero_poliza = '$numero_poliza'");
if ($checkNumero->num_rows > 0) {
    echo json_encode(["status" => false, "mensaje" => "Ya existe una póliza con ese número"]);
    exit;
}

// 2. Insertar
$sql = "INSERT INTO gestor_polizas (cliente_id, numero_poliza, importe_total, fecha, estado, observaciones) 
        VALUES ($cliente_id, '$numero_poliza', '$importe_total', '$fecha', '$estado', '$observaciones')";

if ($conexion->query($sql)) {
    echo json_encode(["status" => true, "mensaje" => "Póliza creada correctamente", "id" => $conexion->insert_id]);
} else {
    echo json_encode(["status" => false, "mensaje" => "Error al guardar: " . $conexion->error]);
}