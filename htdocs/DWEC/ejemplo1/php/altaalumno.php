<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$nombre =  $_GET['nombre'];
$apellidos = $_GET['apellidos'];
$nota = $_GET['nota'];

// Consulta SQL
$sql = "INSERT INTO alumnos (nombre, apellidos, nota) VALUES ('$nombre', '$apellidos', '$nota')";

//Ejecutar
if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        "status" => "ok",
        "message" => "Alumno guardado correctamente.",
        "data" => [
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "nota" => $nota
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error al guardar el alumno."
    ]);
}
