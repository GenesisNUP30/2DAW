<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$id_modelo = $_GET['id_modelo'];

$sql = "SELECT precio FROM modelos WHERE id='$id_modelo'";
$resultado = $conexion->query($sql);

// Array para guardar los resultados
$precio = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $precio[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($precio, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// Cerrar conexión
$conexion->close();