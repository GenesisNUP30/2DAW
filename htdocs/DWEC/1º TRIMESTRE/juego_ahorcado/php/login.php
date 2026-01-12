<?php
session_start();
include 'conexion.php';

$username = $_GET['username'];
$password = $_GET['password'];

$query = "SELECT * FROM usuarios WHERE username='$username' AND password='$password'";
$result = mysqli_query($conexion, $query);

if ($fila = mysqli_fetch_assoc($result)) {
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $fila['id'];
    $_SESSION['admin'] = $fila['admin']; // 1 = admin, 0 = jugador

    // Redirigir según rol
    if ($fila['admin'] == 1) {
        echo "admin";
    } else {
        echo "jugador";
    }
} else {
    echo "error";
}
?>