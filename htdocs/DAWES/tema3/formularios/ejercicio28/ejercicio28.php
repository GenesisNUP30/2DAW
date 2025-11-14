<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = strtoupper($_POST["nombre"]);
    $apellidos = strtoupper($_POST["apellidos"]);
    echo "<h1>Datos recibidos: </h1>";
    echo "Nombre: $nombre<br>";
    echo "Apellidos: $apellidos<br>";
    echo "Nombre completo: " . $nombre . " " . $apellidos;
} else {
?>
    <form action="ejercicio28.php" method="post">
        Introduzca su nombre:<input type="text" name="nombre">
        <br>
        Introduzca sus apellidos:<input type="text" name="apellidos">
        <br>
        <button type="submit">Enviar</button>
    </form>
<?php
}
