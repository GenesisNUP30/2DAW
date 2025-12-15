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
$marca = $_GET["marca"];
$modelo = $_GET["modelo"];
$precio = $_GET["precio"];
$provincia = $_GET["provincia"];
$poblacion = $_GET["poblacion"];

$sql = "UPDATE coches SET marca = '$marca', modelo = '$modelo', precio = '$precio', provincia = '$provincia', poblacion = '$poblacion' WHERE codigo = '$codigo'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "mensaje" => "Coche modificado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al modificar el coche."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>