<?php
// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json; charset=utf-8');

//Incluir el archivo de conexión
include 'conexion.php'; //o require 'conexion.php';

//Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$id_nota = $_GET['id_nota'];
$asignatura = $_GET['asignatura'];
$nota = $_GET['nota'];

//Consulta sql
$sql = "UPDATE notas SET asignatura='$asignatura', nota='$nota' WHERE id=". $id_nota;
$resultado = mysqli_query($conexion, $sql);

//Ejecutar
if ($resultado) {
    echo json_encode([
        "status" => "success",
        "message" => "Nota modificada correctamente",
        "data" => [
            "id" => $id_nota,
            "asignatura" => $asignatura,
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
