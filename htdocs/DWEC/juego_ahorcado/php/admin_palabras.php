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
    $palabra = trim($_GET['palabra']);
    $cat_id = trim($_GET['categoria_id']);

    if ($palabra === '' || $cat_id === '' || !is_numeric($cat_id)) {
        echo "error_datos";
        exit();
    }

    // Insertar
    $query = "INSERT INTO palabras (palabra, categoria_id) VALUES ('$palabra', $cat_id)";
    if (mysqli_query($conexion, $query)) {
        echo "ok";
    } else {
        echo "error_sql: " . mysqli_error($conexion);
    }
}
?>