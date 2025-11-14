<?php
//Realiza una página que permita seleccionar la comunidad autónoma en un formulario  y nos muestre las provincias que hay en la CCAA seleccionada.
//Después de enviar el formulario se mostrará el nombre de la comunidad autónoma seleccionada y las provincias que la conforman.

include "conexion.php";

$elegir_comunidadesautonomas = "SELECT nombre FROM tbl_comunidadesautonomas ORDER BY nombre";
$resultado_comunidadesautonomas = mysqli_query($conexion, $elegir_comunidadesautonomas) or die("Error en la consulta: " . mysqli_error($conexion));

if (isset($_POST['comunidad'])) {
    $id_comunidad = $_POST['comunidad'];

    $buscar_comunidad = "SELECT nombre FROM tbl_comunidadesautonomas WHERE id = '$id_comunidad'";

    $resultado_comunidad = mysqli_query($conexion, $buscar_comunidad) or die("Error en la consulta: " . mysqli_error($conexion));
    $fila_comunidad = mysqli_fetch_assoc($resultado_comunidad);
    $nombre_comunidad = $fila_comunidad['nombre'];

    echo "<p>Provincias de " . $nombre_comunidad . "</p>";

    $consulta_provincias = "SELECT nombre 
    FROM tbl_provincias 
    WHERE comunidad_id = $id_comunidad";

    $resultado_provincias = mysqli_query($conexion, $consulta_provincias) or die("Error en la consulta: " . mysqli_error($conexion));

    while ($fila = mysqli_fetch_assoc($resultado_provincias)) {
        echo $fila_provincias['nombre'] . "<br>";
    }
}


// echo "<form action='ejercicio6.php' method='post'>
// <select name='comunidad'>";

// while ($fila = mysqli_fetch_assoc($resultado_comunidadesautonomas)) {
//     echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
// }

// echo "</select>
// <input type='submit' value='Enviar'>
// </form>";

mysqli_close($conexion);
