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

$sql = "SELECT * FROM clientes WHERE id = $codigo";
$resultado = mysqli_query($conexion, $sql);

$cliente = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $cliente[] = $fila;
    }
    // Devolver los datos como JSON
    echo json_encode($cliente, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "No hay registros en la tabla clientes."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>