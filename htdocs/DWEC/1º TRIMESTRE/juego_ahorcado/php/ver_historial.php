<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "no_logueado"]);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT p.palabra, par.estado, par.puntos, par.fecha
    FROM partidas par
    JOIN palabras p ON par.palabra_id = p.id
    WHERE par.usuario_id = $user_id
    ORDER BY par.fecha DESC
";

$result = mysqli_query($conexion, $query);
$partidas = [];

while ($row = mysqli_fetch_assoc($result)) {
    $partidas[] = $row;
}

if (count($partidas) > 0) {
    echo json_encode($partidas, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "no_hay_partidas"]);
}
?>