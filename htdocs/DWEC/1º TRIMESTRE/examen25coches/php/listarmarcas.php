<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$sql = "SELECT * FROM marcas";
$resultado = $conexion->query($sql);

// Array para guardar los resultados
$marcas = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $marcas[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($marcas, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
}

// Cerrar conexión
$conexion->close();