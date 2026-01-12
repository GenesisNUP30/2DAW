<?php

// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

// Incluir el archivo de conexión
include 'conexion.php'; // o require 'conexion.php';

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$id_modelo = $_GET['id_modelo'];
$precio = $_GET['precio'];

$sql = "UPDATE modelos SET precio = '$precio' WHERE id = '$id_modelo'";
$resultado = mysqli_query($conexion, $sql);

if($resultado){
    echo json_encode([
        "status" => "success",
        "mensaje" => "Datos actualizados correctamente",
        "data" => [
            "precio" => $precio
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al actualizar datos"
    ]);
}

// Cerrar conexión
$conexion->close();
?>