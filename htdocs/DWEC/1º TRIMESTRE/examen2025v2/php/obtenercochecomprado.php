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

$sql = "SELECT modelos.nombre, modelos.precio marcas.nombre FROM modelos JOIN marcas ON modelos.id_marca = marcas.id ";

$resultado = mysqli_query($conexion, $sql);

$coches = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $coches[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($coches, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
}

// Cerrar conexión
$conexion->close();
?>