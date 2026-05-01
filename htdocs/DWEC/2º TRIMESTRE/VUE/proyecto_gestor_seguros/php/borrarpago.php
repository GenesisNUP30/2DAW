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

// Modo borrado: elimina el pago
$sql = "DELETE FROM gestor_pagos WHERE id = $id";

if ($conexion->query($sql)) {
    echo json_encode(["status" => true, "mensaje" => "Pago eliminado correctamente"]);
} else {
    echo json_encode(["status" => false, "mensaje" => "Error al eliminar el pago"]);
}
