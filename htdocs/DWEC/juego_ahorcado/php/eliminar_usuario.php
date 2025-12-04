<?php
include 'conexion.php';

$usuario_id = $_GET['usuario_id'];

// Evitar eliminar al usuario actual (aunque ya está bloqueado en JS)
session_start();
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $usuario_id) {
    echo "error";
    exit();
}

// Eliminar partidas del usuario (opcional, pero limpia)
mysqli_query($conexion, "DELETE FROM partidas WHERE usuario_id=$usuario_id");

// Eliminar usuario
$query = "DELETE FROM usuarios WHERE id=$usuario_id";
if (mysqli_query($conexion, $query)) {
    echo "ok";
} else {
    echo "error";
}
?>