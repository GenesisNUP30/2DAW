<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$accion = $_GET['accion'];

if ($accion == 'listar') {
    $res = mysqli_query($conexion, "SELECT * FROM categorias");
    $cats = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $cats[] = $r;
    }
    echo json_encode($cats, JSON_UNESCAPED_UNICODE);
} elseif ($accion == 'agregar') {
    $nombre = $_GET['nombre'];
    mysqli_query($conexion, "INSERT INTO categorias (nombre) VALUES ('$nombre')");
    echo "ok";
}
?>