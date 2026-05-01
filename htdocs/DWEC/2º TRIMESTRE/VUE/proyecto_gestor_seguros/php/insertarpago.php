<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$datos = json_decode(file_get_contents("php://input"), true);

if (!$datos || !isset($datos['poliza_id']) || !isset($datos['importe'])) {
    echo json_encode(["status" => false, "mensaje" => "Datos incompletos"]);
    exit;
}

$poliza_id = intval($datos['poliza_id']);
$fecha     = $datos['fecha'];
$importe_nuevo = floatval($datos['importe']);

// 1. Obtener el importe total de la póliza y lo que ya se ha pagado
$sqlCheck = "SELECT 
                p.importe_total, 
                IFNULL(SUM(pg.importe), 0) as total_pagado 
             FROM gestor_polizas p
             LEFT JOIN gestor_pagos pg ON p.id = pg.poliza_id
             WHERE p.id = ?
             GROUP BY p.id";

$stmtCheck = $conexion->prepare($sqlCheck);
$stmtCheck->bind_param("i", $poliza_id);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result()->fetch_assoc();

if (!$resCheck) {
    echo json_encode(["status" => false, "mensaje" => "Póliza no encontrada"]);
    exit;
}

$limite_maximo = floatval($resCheck['importe_total']);
$ya_pagado = floatval($resCheck['total_pagado']);
$restante = $limite_maximo - $ya_pagado;

// 2. Validar si el nuevo pago se pasa del límite
// Usamos una pequeña tolerancia para errores de redondeo de coma flotante (0.01)
if ($importe_nuevo > ($restante + 0.01)) {
    echo json_encode([
        "status" => false, 
        "mensaje" => "Error: El importe excede el total de la póliza. Máximo permitido: " . number_format($restante, 2) . "€"
    ]);
    exit;
}

// 3. Insertar el pago si todo es correcto
$sqlInsert = "INSERT INTO gestor_pagos (poliza_id, fecha, importe) VALUES (?, ?, ?)";
$stmtInsert = $conexion->prepare($sqlInsert);
$stmtInsert->bind_param("isd", $poliza_id, $fecha, $importe_nuevo);

if ($stmtInsert->execute()) {
    echo json_encode([
        "status" => true,
        "mensaje" => "Pago registrado correctamente",
        "id" => $stmtInsert->insert_id
    ]);
} else {
    echo json_encode(["status" => false, "mensaje" => "Error al registrar pago: " . $conexion->error]);
}

$stmtCheck->close();
$stmtInsert->close();
$conexion->close();