<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

//Incluir el archivo de conexión
include 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$nombre = $_GET["nombre"];
$apellidos = $_GET["apellidos"];
$telefono = $_GET["telefono"];
$email = $_GET["email"];
$direccion = $_GET["direccion"];

$sql = "INSERT INTO clientes (nombre, apellidos, telefono, email, direccion) VALUES ('$nombre', '$apellidos', '$telefono', '$email', '$direccion')";

if (mysqli_query($conexion, $sql)) {
    echo json_encode([
        "status" => "success",
        "message" => "Cliente creado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error al crear el cliente."
    ]);
}

mysqli_close($conexion);
?>