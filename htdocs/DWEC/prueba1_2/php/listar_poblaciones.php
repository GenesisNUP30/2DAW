<?php

header("Content-Type: application/json; charset=utf-8");

include "conexion.php";

if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

$provincia = $_GET['provincia'] ?? '';
if (empty($provincia)) {
    echo json_encode([]);
    exit;
}

$provincia = $conexion->real_escape_string($provincia);
$sql = "SELECT DISTINCT poblacion FROM coches WHERE provincia = '$provincia' ORDER BY poblacion";
$result = $conexion->query($sql);

$poblaciones = [];
if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $poblaciones[] = $fila['poblacion'];
    }
}

echo json_encode($poblaciones);
$conexion->close();
?>