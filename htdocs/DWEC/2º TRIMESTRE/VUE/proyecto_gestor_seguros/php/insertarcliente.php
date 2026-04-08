<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include "conexion.php";

// Leemos el JSON enviado por Vue
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["status" => false, "mensaje" => "No se recibieron datos"]);
    exit;
}

// Extraemos y saneamos (evitar inyección SQL básica)
$tipo         = $conexion->real_escape_string($data['tipo_cliente']);
$nombre       = $conexion->real_escape_string($data['nombre']);
$apellidos    = isset($data['apellidos']) ? $conexion->real_escape_string($data['apellidos']) : '';
$dni          = strtoupper($conexion->real_escape_string($data['dni']));
$email        = $conexion->real_escape_string($data['email']);
$telefono     = $conexion->real_escape_string($data['telefono']);
$cp           = $conexion->real_escape_string($data['cp']);
$provincia_id = intval($data['provincia_id']);
$municipio_id = intval($data['municipio_id']);
$direccion    = $conexion->real_escape_string($data['direccion']);

// 1. Verificar si el DNI ya existe
$checkDni = $conexion->query("SELECT id FROM clientes WHERE dni = '$dni'");
if ($checkDni->num_rows > 0) {
    echo json_encode(["status" => false, "mensaje" => "Ya existe un cliente con ese DNI/NIF/CIF"]);
    exit;
}

// 2. Insertar
$sql = "INSERT INTO clientes (tipo_cliente, nombre, apellidos, dni, email, telefono, cp, provincia_id, localidad_id, direccion) 
        VALUES ('$tipo', '$nombre', '$apellidos', '$dni', '$email', '$telefono', '$cp', $provincia_id, $municipio_id, '$direccion')";

if ($conexion->query($sql)) {
    echo json_encode(["status" => true, "mensaje" => "Cliente creado correctamente", "id" => $conexion->insert_id]);
} else {
    echo json_encode(["status" => false, "mensaje" => "Error al guardar: " . $conexion->error]);
}