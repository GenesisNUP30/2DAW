<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión
include "conexion.php";

// Verificamos si recibimos un ID específico
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Consulta simple para el listado principal
$sql = "SELECT id, tipo_cliente, nombre, apellidos, dni, email, telefono, 
               direccion, cp, provincia_id, localidad_id AS municipio_id FROM clientes";

// Si hay ID, se añade a la consulta; si no, mantenemos el orden habitual
if ($id) {
    $sql .= " WHERE id = $id";
} else {
    $sql .= " ORDER BY id DESC";
}

$resultado = $conexion->query($sql);

if ($id) {
    if ($resultado && $resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
        echo json_encode([
            "status" => true,
            "data" => $cliente
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "mensaje" => "No se encontró el cliente"
        ]);
    }
} else {

    $clientes = [];
    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $clientes[] = $fila;
        }
        echo json_encode([
            "status" => true,
            "data" => $clientes
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "mensaje" => "No hay clientes registrados",
        ]);
    }
}
