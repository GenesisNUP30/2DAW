<?php
echo "<p>Datos recibidos:</p>";
//Filtro los datos recibidos
$errores="";
if ($_POST['nombre'] == '') {
    $errores .= "Introduzca un nombre<br>";
}

if ($_POST['apellidos'] == '')  {
    $errores .= "Introduzca apellidos<br>";
}
if ($_POST['fecha'] == '')  {
    $errores .= "Introduzca una fecha<br>";
}

if ($errores != "") {
    echo '<p style="color:red">' . $errores . '</p>';
    include 'form_f1.php';
} else {
    echo "<h1>Datos recibidos: </h1>";

    echo "<pre>";
    print_r($_POST);
}


