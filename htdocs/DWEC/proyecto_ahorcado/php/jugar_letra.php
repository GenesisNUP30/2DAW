<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$letra = strtolower($_GET['letra']);
$palabra = strtolower($_SESSION['palabra_secreta']);
$letras_jugadas = $_SESSION['letras_jugadas'] ?? [];

if (in_array($letra, $letras_jugadas)) {
    echo json_encode(['repetida' => true]);
    exit();
}

$letras_jugadas[] = $letra;
$_SESSION['letras_jugadas'] = $letras_jugadas;

$posiciones = [];
for ($i = 0; $i < strlen($palabra); $i++) {
    if ($palabra[$i] === $letra) {
        $posiciones[] = $i;
    }
}

if (count($posiciones) > 0) {
    $_SESSION['aciertos'] += count($posiciones);
    $_SESSION['puntos'] += 2 * count($posiciones);
    $acierto = true;
} else {
    $_SESSION['errores']++;
    $_SESSION['puntos'] -= 1;
    $acierto = false;
}

$palabra_oculta = '';
for ($i = 0; $i < strlen($palabra); $i++) {
    if (in_array($palabra[$i], $letras_jugadas)) {
        $palabra_oculta .= $palabra[$i];
    } else {
        $palabra_oculta .= '_';
    }
}

if ($palabra_oculta === $palabra) {
    $_SESSION['estado'] = 'ganada';
    $_SESSION['puntos'] += 10;
} elseif ($_SESSION['errores'] >= 6) {
    $_SESSION['estado'] = 'perdida';
    $_SESSION['puntos'] -= 5;
}

echo json_encode([
    'acierto' => $acierto,
    'posiciones' => $posiciones,
    'errores' => $_SESSION['errores'],
    'palabra_oculta' => $palabra_oculta,
    'estado' => $_SESSION['estado'],
    'puntos' => $_SESSION['puntos']
], JSON_UNESCAPED_UNICODE);
?>