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
$precio = $_GET['precio'];

$sql = "UPDATE modelos SET precio = '$precio' WHERE id = '$id_modelo'";

if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Precio actualizado correctamente.",
        "precio" => $precio
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error al actualizar el precio."
    ]);
}

mysqli_close($conexion);
?>