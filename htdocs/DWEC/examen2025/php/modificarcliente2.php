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
$nombre = $_GET["nombre"];
$apellidos = $_GET["apellidos"];
$telefono = $_GET["telefono"];
$email = $_GET["email"];
$direccion = $_GET["direccion"];

$sql = "UPDATE clientes SET nombre = '$nombre', apellidos = '$apellidos', telefono = '$telefono', email = '$email', direccion = '$direccion' WHERE id = '$codigo'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "mensaje" => "Cliente modificado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "mensaje" => "Error al modificar el cliente."
    ]);
}

// Cerrar conexión
mysqli_close($conexion);
?>