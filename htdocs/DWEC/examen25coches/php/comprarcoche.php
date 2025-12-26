<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$marca = $_GET['marcanombre'];
$modelo = $_GET['modelonombre'];
$precio = $_GET['precio'];

$sql = "INSERT INTO compras (marca, modelo, precio_final) VALUES ('$marca', '$modelo', '$precio')";

if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Compra registrada correctamente.",
        "data" => [
            "marca" => $marca,
            "modelo" => $modelo,
            "precio_final" => $precio
        ] 
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error al registrar la compra."
    ]);
}

mysqli_close($conexion);
?>