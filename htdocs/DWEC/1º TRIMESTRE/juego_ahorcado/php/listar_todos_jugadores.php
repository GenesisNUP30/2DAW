<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$query = "
    SELECT 
        u.id,
        u.username,
        u.admin,
        COUNT(par.id) as total_partidas,
        COALESCE(SUM(par.puntos), 0) as total_puntos
    FROM usuarios u
    LEFT JOIN partidas par ON u.id = par.usuario_id
    GROUP BY u.id, u.username, u.admin
    ORDER BY u.username
";

$result = mysqli_query($conexion, $query);
$jugadores = [];

while ($row = mysqli_fetch_assoc($result)) {
    $row['admin'] = $row['admin'] ? 'Sí' : 'No';
    $jugadores[] = $row;
}

echo json_encode($jugadores, JSON_UNESCAPED_UNICODE);
?>