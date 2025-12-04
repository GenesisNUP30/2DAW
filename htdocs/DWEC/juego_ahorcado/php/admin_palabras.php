<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$accion = $_GET['accion'];

if ($accion == 'listar') {
    $res = mysqli_query($conexion, "SELECT p.*, c.nombre as categoria FROM palabras p JOIN categorias c ON p.categoria_id = c.id");
    $palabras = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $palabras[] = $r;
    }
    echo json_encode($palabras, JSON_UNESCAPED_UNICODE);
} elseif ($accion == 'agregar') {
    $palabra = $_GET['palabra'];
    $cat_id = $_GET['categoria_id'];
    mysqli_query($conexion, "INSERT INTO palabras (palabra, categoria_id) VALUES ('$palabra', $cat_id)");
    echo "ok";
}
?>