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
$id_cliente = intval($datos['id_cliente'] ?? 0);
$numero_poliza = intval($datos['numero_poliza'] ?? 0);
$fecha = intval($datos['fecha'] ?? 0);
$importe_total = floatval($datos['importe_total'] ?? 0);
$estado = $datos['estado'] ?? '';
$observaciones = $datos['observaciones'] ?? '';

// Validaciones
if (!$id || !$id_cliente || !$numero_poliza || !$fecha || !$importe_total || !$estado || !$observaciones) {
    echo json_encode(["status" => false, "mensaje" => "Datos inválidos"]);
    exit;
}

$checkNumeroPoliza = $conexion->prepare(
    "SELECT id FROM polizas WHERE numero_poliza = ? AND id != ?"
);
$checkNumeroPoliza->bind_param("is", $numero_poliza, $id);
$checkNumeroPoliza->execute();
$checkNumeroPoliza->store_result();

if ($checkNumeroPoliza->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "mensaje" => "El número de póliza ya está registrado en otra póliza"
    ]);
    exit;
}
$checkNumeroPoliza->close();

// Actualización
$stmt = $conexion->prepare(
    "UPDATE polizas SET numero_poliza = ?, fecha = ?, importe_total = ?, estado = ?, observaciones = ? WHERE id = ?"
);
$stmt->bind_param("issssi", $numero_poliza, $fecha, $importe_total, $estado, $observaciones, $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
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