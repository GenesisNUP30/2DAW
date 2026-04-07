<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include "conexion.php";

// Verificamos que venga el ID de la provincia
$provincia_id = isset($_GET['provincia_id']) ? intval($_GET['provincia_id']) : 0;

$municipios = [];

if ($provincia_id > 0) {
    // Consulta preparada para evitar inyecciones SQL
    $stmt = $conexion->prepare("SELECT id, nombre FROM municipios WHERE provincia_id = ? ORDER BY nombre ASC");
    $stmt->bind_param("i", $provincia_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        $municipios[] = $fila;
    }
    $stmt->close();
}

echo json_encode($municipios);