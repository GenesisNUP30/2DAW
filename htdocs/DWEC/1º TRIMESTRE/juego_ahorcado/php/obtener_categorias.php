<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$query = "SELECT * FROM categorias";
$result = mysqli_query($conexion, $query);

$categorias = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categorias[] = $row;
}

echo json_encode($categorias, JSON_UNESCAPED_UNICODE);
?>