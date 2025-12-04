<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$sql = "SELECT DISTINCT categoria FROM palabras";
$resultado = mysqli_query($conexion, $sql);

$categorias = [];
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $categorias[] = $fila['categoria'];
    }
}

echo json_encode($categorias);
?>