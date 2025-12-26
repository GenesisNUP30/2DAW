<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$texto = $_GET['texto'];

$sql = "SELECT modelos.nombre AS modelo, marcas.nombre AS marca FROM modelos JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.nombre LIKE '%$texto%'";
$resultado = mysqli_query($conexion, $sql);

$modelos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $modelos[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($modelos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "No hay registros para el modelo proporcionado."]);
}

// Cerrar conexión
mysqli_close($conexion);
?>