<?php

// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$codigo = $_GET["codigo"];

$sql = "SELECT * FROM coches WHERE codigo = '$codigo'";
$resultado = mysqli_query($conexion, $sql);

$coche = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $coche[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($coche, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "No hay registros en la tabla coches."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>