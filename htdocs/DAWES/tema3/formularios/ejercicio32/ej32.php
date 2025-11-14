<?php
$errores = "";

if (!isset($_POST['nombre'])) {
    include "ej32_form.php";
} else {
    // Validaciones básicas
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
    }

    // Si hay errores, mostramos el formulario otra vez
    if ($errores != "") {
        echo "<p style='color:red'>$errores</p>";
        include "ej32_form.php";
    } else {
        // Calculamos la edad
        $fechaNac = new DateTime($_POST['fecha']);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNac)->y;

        // Convertimos saltos de línea
        $observaciones = nl2br($_POST['observaciones']);

        echo "<h2>Datos recibidos:</h2>";
        echo "<table border='1' cellpadding='5'>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Sexo</th>
                    <th>Curso</th>
                    <th>Fecha nacimiento</th>
                    <th>Edad</th>
                    <th>Observaciones</th>
                </tr>
                <tr>
                    <td>{$_POST['nombre']}</td>
                    <td>{$_POST['apellidos']}</td>
                    <td>" . ($_POST['sexo'] == 'm' ? 'Masculino' : 'Femenino') . "</td>
                    <td>{$_POST['curso']}</td>
                    <td>{$_POST['fecha']}</td>
                    <td>$edad años</td>
                    <td>$observaciones</td>
                </tr>
            </table>";
    }
}
?>
