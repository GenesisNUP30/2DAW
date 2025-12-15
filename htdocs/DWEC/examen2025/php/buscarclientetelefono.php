<?php

// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$telefono = $_GET["telefono"];
$sql = "SELECT * FROM clientes WHERE telefono LIKE '%$telefono%'";
$resultado = mysqli_query($conexion, $sql);

$clientes = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $clientes[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($clientes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} 

// Cerrar conexión
mysqli_close($conexion);
?>