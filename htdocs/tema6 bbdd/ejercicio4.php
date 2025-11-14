<?php
// Realiza una página en PHP que muestre las provincias que tiene cada comunidad autónoma, con el siguiente formato: CCAA1 : prov1, prov2, … CCAA2: …..

include "conexion.php";

$consulta = "SELECT c.nombre as CA, GROUP_CONCAT(p.nombre SEPARATOR ', ') as provincias
FROM tbl_comunidadesautonomas c
JOIN tbl_provincias p ON c.id = p.comunidad_id
GROUP BY c.nombre
ORDER BY c.nombre";

$resultado = mysqli_query($conexion, $consulta) or die("Error en la consulta: " . mysqli_error($conexion));

while ($fila = mysqli_fetch_assoc($resultado)) {
    echo "<p> ". $fila['CA'] . " : " . $fila['provincias'] . "</p>";
}

mysqli_close($conexion);
