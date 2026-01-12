<?php
header("Content-Type: application/json; charset=utf-8");
include "conexion.php";

if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

$sql = "SELECT DISTINCT provincia FROM coches ORDER BY provincia";
$result = $conexion->query($sql);

$provincias = [];
if ($result && $result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $provincias[] = $fila['provincia'];
    }
}

echo json_encode($provincias);
$conexion->close();
?>