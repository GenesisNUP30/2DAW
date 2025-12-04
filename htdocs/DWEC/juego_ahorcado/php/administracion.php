<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h1>Panel de Administración</h1>
    <div id="admin">
        <h2>Gestión de Palabras</h2>
        <button onclick="listarPalabras()">Listar Palabras</button>
        <button onclick="agregarPalabra()">Agregar Palabra</button>
        <button onclick="modificarPalabra()">Modificar Palabra</button>
        <button onclick="eliminarPalabra()">Eliminar Palabra</button>
        <div id="palabras"></div>

        <h2>Gestión de Usuarios</h2>
        <button onclick="listarUsuarios()">Listar Usuarios</button>
        <div id="usuarios"></div>
    </div>
    <script src="../js/utiles.js"></script>
</body>
</html>