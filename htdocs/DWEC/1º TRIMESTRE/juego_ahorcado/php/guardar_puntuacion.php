<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['palabra_id']) || !isset($_SESSION['user_id'])) {
    echo "error";
    exit();
}

$usuario_id = $_SESSION['user_id'];
$palabra_id = $_SESSION['palabra_id'];
$estado = $_SESSION['estado'];
$puntos = $_SESSION['puntos'];

$query = "INSERT INTO partidas (usuario_id, palabra_id, estado, puntos) 
          VALUES ($usuario_id, $palabra_id, '$estado', $puntos)";

mysqli_query($conexion, $query);

// Limpiar sesión de juego
unset($_SESSION['palabra_id']);
unset($_SESSION['palabra_secreta']);
unset($_SESSION['letras_jugadas']);
unset($_SESSION['errores']);
unset($_SESSION['aciertos']);
unset($_SESSION['estado']);
unset($_SESSION['puntos']);

echo "guardado";
?>