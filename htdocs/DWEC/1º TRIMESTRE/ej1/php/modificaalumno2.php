<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

//Incluir el archivo de conexión
include 'conexion.php'; //o require 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$codigo = $_GET['codigo'];
$nombre = $_GET['nombre'];
$apellidos = $_GET['apellidos'];
$nota = $_GET['nota'];

//Consulta sql
$sql = "UPDATE alumnos SET nombre='$nombre', apellidos='$apellidos', nota='$nota' WHERE codigo=$codigo";
$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo json_encode([
        "status" => "success",
        "message" => "Alumno modificado correctamente.",
        "data" => [
            "nombre" => $nombre,
            "apellidos" => $apellidos,
            "nota" => $nota
        ]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conexion)
    ]);
}
?>
