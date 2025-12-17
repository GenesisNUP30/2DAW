<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$texto = $_GET["texto"];

$sql = "SELECT *, COUNT(*) AS cantidad FROM modelos WHERE nombre LIKE '%$texto%'";
$resultado = $conexion->query($sql);

$modelos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $modelos[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($modelos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// Cerrar conexión
$conexion->close();
?>