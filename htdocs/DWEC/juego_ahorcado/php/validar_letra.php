<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$palabra = $_GET['palabra'];
$letra = $_GET['letra'];

$posiciones = [];
for ($i = 0; $i < strlen($palabra); $i++) {
    if ($palabra[$i] === $letra) {
        $posiciones[] = $i;
    }
}

if (!empty($posiciones)) {
    echo json_encode([
        "status" => "success",
        "posiciones" => $posiciones
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "La letra no está en la palabra."
    ]);
}
?>