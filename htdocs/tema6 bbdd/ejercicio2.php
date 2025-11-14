<?php
include_once "conexion.php";

$consulta = "SELECT * FROM tbl_provincias";
$resultado = mysqli_query($conexion, $consulta);


$nfilas = mysqli_num_rows($resultado);
echo "Nº de provincias: " . $nfilas;

while ($reg = mysqli_fetch_array($resultado)) {
    echo "<p>" . $reg["cod"] . " " . $reg["nombre"] . "</p>";
}

// echo "Campo id: " . $reg["cod"];

mysqli_close($conexion);
