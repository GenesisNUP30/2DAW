<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

include 'conexion.php';

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$nombre    = $_GET['nombre'];
$apellidos = $_GET['apellidos'];
$nota      = $_GET['nota'];

//Consulta sql
$sql = "INSERT INTO alumnos (codigo, nombre, apellidos, nota) 
        VALUES (NULL,'$nombre', '$apellidos', '$nota')";


if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Alumno insertado correctamente",
        "data" => [
            "codigo" => $codigo,
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "nota" => $nota
        ]
    ]);
} else {
    echo json_encode([
        "status" => mysqli_error($conexion)
    ]);
}
?>