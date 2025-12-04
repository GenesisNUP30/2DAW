<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

$categoria_id = $_GET['categoria_id'];

$query = "SELECT id, palabra FROM palabras WHERE categoria_id=$categoria_id ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conexion, $query);
$palabra = mysqli_fetch_assoc($result);

if ($palabra) {
    $_SESSION['palabra_id'] = $palabra['id'];
    $_SESSION['palabra_secreta'] = $palabra['palabra'];
    $_SESSION['letras_jugadas'] = [];
    $_SESSION['errores'] = 0;
    $_SESSION['aciertos'] = 0;
    $_SESSION['estado'] = 'en_progreso';
    $_SESSION['puntos'] = 0;

    echo json_encode([
        'palabra_oculta' => str_repeat('_', strlen($palabra['palabra'])),
        'longitud' => strlen($palabra['palabra'])
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['error' => 'No hay palabras en esta categoría']);
}
?>