<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$username = $_GET['username'];

// Buscar usuario
$query = "SELECT id, username, admin FROM usuarios WHERE username='$username'";
$result = mysqli_query($conexion, $query);

if (!$fila = mysqli_fetch_assoc($result)) {
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit();
}

$usuario_id = $fila['id'];
$es_admin = $fila['admin'] ? 'Sí' : 'No';

// Buscar partidas del usuario
$query2 = "
    SELECT p.palabra, par.estado, par.puntos, par.fecha
    FROM partidas par
    JOIN palabras p ON par.palabra_id = p.id
    WHERE par.usuario_id = $usuario_id
    ORDER BY par.fecha DESC
";

$result2 = mysqli_query($conexion, $query2);
$partidas = [];
while ($row = mysqli_fetch_assoc($result2)) {
    $partidas[] = $row;
}

echo json_encode([
    "usuario" => [
        "username" => $fila['username'],
        "admin" => $es_admin
    ],
    "partidas" => $partidas
], JSON_UNESCAPED_UNICODE);
?>