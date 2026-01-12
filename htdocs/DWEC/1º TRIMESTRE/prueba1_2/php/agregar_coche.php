<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$marca = $_GET["marca"];
$modelo = $_GET["modelo"];
$precio = $_GET["precio"];
$provincia = $_GET["provincia"];
$poblacion = $_GET["poblacion"];

$sql = "INSERT INTO coches (marca, modelo, precio, provincia, poblacion, vendido) VALUES ('$marca', '$modelo', '$precio', '$provincia', '$poblacion', 0)";
$resultado = $conexion->query($sql);

if ($resultado) {
    // Devolver los datos como JSON
    echo json_encode([
        "status" => "success",
        "data" => [
            "marca" => $marca,
            "modelo" => $modelo,
            "precio" => $precio,
            "provincia" => $provincia,
            "poblacion" => $poblacion
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al insertar el coche."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>