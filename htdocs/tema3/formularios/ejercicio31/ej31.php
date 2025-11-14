<?php
$errores = "";

if (!isset($_POST['nombre'])) {
    include "ej31_form.php";
} else {
    // Validaciones
    if ($_POST['nombre'] == "") {
        $errores .= "Debe introducir un nombre.<br>";
    }
    if ($_POST['apellidos'] == "") {
        $errores .= "Debe introducir apellidos.<br>";
    }
    if (!isset($_POST['sexo'])) {
        $errores .= "Debe seleccionar el sexo.<br>";
    }
    if ($_POST['curso'] == "") {
        $errores .= "Debe seleccionar un curso.<br>";
    }
    if ($_POST['fecha'] == "") {
        $errores .= "Debe introducir una fecha.<br>";
    } else {
        // Validamos la fecha
        $fecha = $_POST['fecha'];
        $partes = explode("-", $fecha);
        if (!checkdate($partes[1], $partes[2], $partes[0])) {
            $errores .= "La fecha no es válida.<br>";
        } else {
            // Calculamos la edad
            $fechaNac = new DateTime($fecha);
            $hoy = new DateTime();
            $edad = $hoy->diff($fechaNac)->y;
        }
    }

    // Mostrar errores o datos
    if ($errores != "") {
        echo "<p style='color:red'>$errores</p>";
        include "ej31_form.php";
    } else {
        // Mostramos los datos en una tabla
        echo "<h2>Datos recibidos:</h2>";
        echo "<table border='1' cellpadding='5'>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Sexo</th>
                    <th>Curso</th>
                    <th>Fecha de nacimiento</th>
                    <th>Edad</th>
                </tr>
                <tr>
                    <td>{$_POST['nombre']}</td>
                    <td>{$_POST['apellidos']}</td>
                    <td>" . ($_POST['sexo'] == 'm' ? 'Masculino' : 'Femenino') . "</td>
                    <td>" . $_POST['curso'] . "</td>
                    <td>{$_POST['fecha']}</td>
                    <td>$edad años</td>
                </tr>
            </table>";
    }
}
?>
