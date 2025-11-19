<?php
// Encabezado para indicar que la respuesta es JSON
header("Content-Type: application/json; charset=utf-8");

include "conexion.php";

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT * FROM alumnos";
$resultado = $conexion->query($sql);

$alumnos = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $alumnos[] = $fila;
    }
    echo json_encode($alumnos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode(["mensaje" => "No hay registros en la tabla alumnos."]);
}

$conexion->close();
?>