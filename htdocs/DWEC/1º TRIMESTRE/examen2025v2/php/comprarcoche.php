<?php

// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

// Incluir el archivo de conexión
include 'conexion.php'; // o require 'conexion.php';

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$id_marca = $_GET['id_marca'];
$id_modelo = $_GET['id_modelo'];
$precio = $_GET['precio'];

$sql = "INSERT INTO compras (id_marca, id_modelo, precio) VALUES ('$id_marca', '$id_modelo', '$precio')";
$resultado = mysqli_query($conexion, $sql);

if($resultado){
    echo json_encode([
        "status" => "success",
        "mensaje" => "Datos insertados correctamente",
        "data" => [
            "id_marca" => $id_marca,
            "id_modelo" => $id_modelo,
            "precio" => $precio
        ]
    ]);
}