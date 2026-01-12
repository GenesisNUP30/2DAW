<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo "error";
    exit();
}

$user_id = $_SESSION['user_id'];
$nueva = $_GET['nueva'];

// Actualizar contraseña en texto plano
$query = "UPDATE usuarios SET password='$nueva' WHERE id=$user_id";

if (mysqli_query($conexion, $query)) {
    echo "ok";
} else {
    echo "error";
}
?>