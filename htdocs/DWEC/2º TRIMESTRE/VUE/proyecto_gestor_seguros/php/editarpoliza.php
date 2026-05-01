<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos || !isset($datos['id'])) {
    echo json_encode(["status" => false, "mensaje" => "Datos inválidos"]);
    exit;
}

$id = intval($datos['id']);
$cliente_id = intval($datos['cliente_id']);
$numero_poliza = trim($datos['numero_poliza']);
$fecha = trim($datos['fecha']);
$importe_total = $conexion->real_escape_string($datos['importe_total'] ?? '0');
$estado = trim($datos['estado'] ?? '');
$observaciones = trim($datos['observaciones'] ?? '');

// Validacion de numero de póliza repetido
$checkNumeroPoliza = $conexion->prepare(
    "SELECT id FROM gestor_polizas WHERE numero_poliza = ? AND id != ?"
);
$checkNumeroPoliza->bind_param("si", $numero_poliza, $id);
$checkNumeroPoliza->execute();
$checkNumeroPoliza->store_result();

if ($checkNumeroPoliza->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "mensaje" => "El número de póliza ya está registrado en otra póliza"
    ]);
    $checkNumeroPoliza->close();
    exit;
}
$checkNumeroPoliza->close();

// Actualización
$stmt = $conexion->prepare("UPDATE gestor_polizas SET cliente_id = ?, numero_poliza = ?, fecha = ?, importe_total = ?, estado = ?, observaciones = ? WHERE id = ?");
$stmt->bind_param("isssssi", $cliente_id, $numero_poliza, $fecha, $importe_total, $estado, $observaciones, $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => true,
        "mensaje" => "Póliza editada correctamente"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "mensaje" => "Error al editar la póliza: " . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();
