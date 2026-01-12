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
$vendido = $_GET["vendido"];

$sql = "UPDATE coches SET vendido = $vendido WHERE codigo = '$codigo'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "mensaje" => "Coche actualizado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al actualizar el coche."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>