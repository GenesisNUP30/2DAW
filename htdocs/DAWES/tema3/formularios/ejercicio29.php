<?php
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["numero"] <= 0 || $_POST["numero"] > 10) {
        $error = "Debe introducir un número que sea mayor que 0 y menor que 10";
    } else {
        $numero = $_POST["numero"];
        echo "<h2> Tabla de multiplicar de $numero </h2>";
        for ($i = 1; $i <= 10; $i++) {
            echo "$i * $numero = " . ($i * $numero) . "<br>";
        }
    }
}
?>
<form action="ejercicio29.php" method="post">
    Introduzca un numero:<input type="text" name="numero">
    <br>
    <button type="submit">Mostrar tabla</button>
</form>
<?php

if ($error != "") {
    echo '<p style="color: red">' . $error . '</p>';
}
