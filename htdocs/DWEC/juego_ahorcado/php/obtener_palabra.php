<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$categoria = $_GET['categoria'];

$sql = "SELECT palabra FROM palabras WHERE categoria = '$categoria' ORDER BY RAND() LIMIT 1";
$resultado = mysqli_query($conexion, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    echo json_encode([
        "status" => "success",
        "palabra" => $fila['palabra']
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No se encontraron palabras para esta categoría."
    ]);
}
?>