<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'jugador') {
    header("Location: ../index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego del Ahorcado</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Juego del Ahorcado</h1>
    <div id="seleccion-categoria">
        <label for="categoria">Selecciona una categoría:</label>
        <select id="categoria">
            <option value="">Cargando categorías...</option>
        </select>
        <button onclick="iniciarJuego()">Comenzar Juego</button>
    </div>
    <div id="juego" style="display: none;">
        <p>Categoría: <span id="categoria-seleccionada"></span></p>
        <p>Palabra: <span id="palabra">_ _ _ _ _</span></p>
        <p>Intentos restantes: <span id="intentos">6</span></p>
        <p>Letras usadas: <span id="letras-usadas"></span></p>
        <input type="text" id="letra" maxlength="1" placeholder="Introduce una letra">
        <button onclick="validarLetra()">Probar letra</button>
        <button onclick="resolverPalabra()">Resolver palabra</button>
        <p id="mensaje"></p>
    </div>
    <script src="../js/utiles.js"></script>
</body>
</html>