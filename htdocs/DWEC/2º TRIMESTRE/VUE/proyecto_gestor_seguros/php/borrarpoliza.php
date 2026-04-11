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

// CASO 1: Solo consultar dependencias (Pagos)
if (isset($data['consultar']) && $data['consultar'] === true) {
    $sql = "SELECT COUNT(*) as total FROM pagos WHERE poliza_id = $id";
    $res = $conexion->query($sql);
    $fila = $res->fetch_assoc();

    echo json_encode([
        "status" => true,
        "totalPagos" => intval($fila['total'])
    ]);
    exit;
}

// CASO 2: Borrar definitivamente
// Nota: Tu DB tiene ON DELETE CASCADE, por lo que borrar la póliza borrará los pagos automáticamente.
$sql = "DELETE FROM polizas WHERE id = $id";

if ($conexion->query($sql)) {
    echo json_encode(["status" => true, "mensaje" => "Póliza eliminada correctamente"]);
} else {
    echo json_encode(["status" => false, "mensaje" => "Error al eliminar: " . $conexion->error]);
}
