<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$intento = strtolower(trim($_GET['palabra']));
$palabra_real = strtolower($_SESSION['palabra_secreta']);

if ($intento === $palabra_real) {
    $_SESSION['estado'] = 'ganada';
    $_SESSION['puntos'] += 10;
    $resultado = 'ganada';
} else {
    $_SESSION['estado'] = 'perdida';
    $_SESSION['puntos'] -= 5;
    $resultado = 'perdida';
}

echo json_encode([
    'resultado' => $resultado,
    'puntos' => $_SESSION['puntos']
], JSON_UNESCAPED_UNICODE);
?>