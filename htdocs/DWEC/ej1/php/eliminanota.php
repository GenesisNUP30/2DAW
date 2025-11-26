<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

//Incluir el archivo de conexión
include 'conexion.php'; //o require 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$id_nota = $_GET['id_nota'];

//Consulta sql
$sql = "DELETE FROM notas WHERE id=". $id_nota;

//Ejecutar
if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Nota borrada correctamente",
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conexion)
    ]);
}

// Cerrar conexión
$conexion->close();