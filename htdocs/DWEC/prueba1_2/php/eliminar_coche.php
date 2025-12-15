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

$sql = "DELETE FROM coches WHERE codigo = '$codigo'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "mensaje" => "Coche eliminado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al eliminar el coche."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>