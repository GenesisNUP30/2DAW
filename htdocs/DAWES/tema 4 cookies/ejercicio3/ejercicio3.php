<?php
include_once "conexion.php";


$sql1 = "SELECT * FROM tbl_comunidadesautonomas";
$resultado = mysqli_query($conexion, $sql1);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 3 - F</title>
</head>

<body>
    <button onclick="cerrarSesion()">Cerrar sesión</button>
    <form action="ejercicio3.php" method="post">
        Elige una comunidad autonoma:
        <select name="comunidad">
            <?php
            while ($fila = mysqli_fetch_array($resultado)) {
                echo "<option value='" . $fila['cod'] . "'>" . $fila['nombre'] . "</option>";
            }
            ?>
        </select>

    </form>
</body>

</html>