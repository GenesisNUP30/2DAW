<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

$datos = json_decode(file_get_contents("php://input"), true);

// Comprobaciones básicas
if (!$datos || !isset($datos['id'])) {
    echo json_encode(["status" => false, "mensaje" => "Datos inválidos"]);
    exit;
}

$id          = intval($datos['id']);
$tipo        = $datos['tipo_cliente'] ?? '';
$nombre      = trim($datos['nombre'] ?? '');
$apellidos   = trim($datos['apellidos'] ?? '');
$dni         = trim($datos['dni'] ?? '');
$email       = trim($datos['email'] ?? '');
$telefono    = trim($datos['telefono'] ?? '');
$direccion   = trim($datos['direccion'] ?? '');
$cp          = trim($datos['cp'] ?? '');
$provincia   = intval($datos['provincia_id'] ?? 0);
$municipio   = intval($datos['municipio_id'] ?? 0);

// Comprobar que el DNI no esté en uso por OTRO cliente
$checkDni = $conexion->prepare(
    "SELECT id FROM clientes WHERE dni = ? AND id != ?"
);
$checkDni->bind_param("si", $dni, $id);
$checkDni->execute();
$checkDni->store_result();

if ($checkDni->num_rows > 0) {
    echo json_encode([
        "status" => false,
        "mensaje" => "El DNI/NIF ya está registrado en otro cliente"
    ]);
    exit;
}
$checkDni->close();

// UPDATE
$sql = "UPDATE clientes SET
            tipo_cliente = ?,
            nombre       = ?,
            apellidos    = ?,
            dni          = ?,
            email        = ?,
            telefono     = ?,
            direccion    = ?,
            cp           = ?,
            provincia_id = ?,
            localidad_id = ?
        WHERE id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "ssssssssiii",  // 8 strings + 3 integers  (provincia, municipio, id)
    $tipo,
    $nombre,
    $apellidos,
    $dni,
    $email,
    $telefono,
    $direccion,
    $cp,
    $provincia,
    $municipio,
    $id
);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "status"  => true,
            "mensaje" => "Cliente actualizado correctamente"
        ]);
    } else {
        // La query fue bien pero no cambió nada (mismos datos)
        echo json_encode([
            "status"  => true,
            "mensaje" => "No se realizaron cambios"
        ]);
    }
} else {
    echo json_encode([
        "status"  => false,
        "mensaje" => "Error al actualizar: " . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();