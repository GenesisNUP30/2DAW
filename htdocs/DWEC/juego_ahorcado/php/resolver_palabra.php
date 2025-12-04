<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$palabra = $_GET['palabra'];
$respuesta = $_GET['respuesta'];

if ($palabra === $respuesta) {
    echo json_encode([
        "status" => "success",
        "message" => "¡Correcto! Has adivinado la palabra."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Respuesta incorrecta."
    ]);
}
?>