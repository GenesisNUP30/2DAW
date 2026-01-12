<?php
include 'conexion.php';

$usuario_id = $_GET['usuario_id'];
$nueva = $_GET['nueva'];

$query = "UPDATE usuarios SET password='$nueva' WHERE id=$usuario_id";

if (mysqli_query($conexion, $query)) {
    echo "ok";
} else {
    echo "error";
}
?>