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

// Modo consulta: solo devuelve cuántas pólizas y pagos tiene el cliente
if (isset($datos['consultar']) && $datos['consultar'] === true) {

    $stmtPolizas = $conexion->prepare(
        "SELECT COUNT(*) as total FROM gestor_polizas WHERE cliente_id = ?"
    );
    $stmtPolizas->bind_param("i", $id);
    $stmtPolizas->execute();
    $totalPolizas = intval($stmtPolizas->get_result()->fetch_assoc()['total']);
    $stmtPolizas->close();

    $stmtPagos = $conexion->prepare(
        "SELECT COUNT(*) as total FROM gestor_pagos p
         INNER JOIN gestor_polizas pol ON p.poliza_id = pol.id
         WHERE pol.cliente_id = ?"
    );
    $stmtPagos->bind_param("i", $id);
    $stmtPagos->execute();
    $totalPagos = intval($stmtPagos->get_result()->fetch_assoc()['total']);
    $stmtPagos->close();

    echo json_encode([
        "status"       => true,
        "totalPolizas" => $totalPolizas,
        "totalPagos"   => $totalPagos,
    ]);
    exit;
}

// Modo borrado: elimina el cliente (pólizas y pagos caen por CASCADE)
$stmt = $conexion->prepare("DELETE FROM gestor_clientes WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode([
        "status"  => true,
        "mensaje" => "Cliente eliminado correctamente"
    ]);
} else {
    echo json_encode([
        "status"  => false,
        "mensaje" => "Error al eliminar el cliente: " . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();