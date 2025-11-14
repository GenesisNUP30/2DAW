<?php
//Realiza una página en PHP que nos diga cuantas provincias tiene cada CCAA.
include "conexion.php";

$consulta = "SELECT c.nombre as CA, COUNT(p.cod) as num_prov
FROM tbl_comunidadesautonomas c
LEFT JOIN tbl_provincias p ON c.id = p.comunidad_id
GROUP BY c.nombre
ORDER BY c.nombre";

$resultado = mysqli_query($conexion, $consulta) or die("Error en la consulta: " . mysqli_error($conexion));

while ($fila = mysqli_fetch_assoc($resultado)) {
    echo "<p>" . $fila['CA'] . ": " . $fila['num_prov'] . " provincias</p>";
}

mysqli_close($conexion);




