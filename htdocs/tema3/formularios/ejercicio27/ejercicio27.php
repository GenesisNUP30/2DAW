<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = strtoupper($_POST['nombre']);
    $apellidos = strtoupper($_POST['apellidos']);
}

echo "<h1>Datos recibidos: </h1>";
echo $nombre . " " . $apellidos;
echo "<br><br>";

echo "<a href='ejercicio27.html'>Volver al formulario</a>";