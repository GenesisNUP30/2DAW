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

$sql = "DELETE FROM clientes WHERE id = $codigo";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "mensaje" => "Cliente eliminado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al eliminar el cliente."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>