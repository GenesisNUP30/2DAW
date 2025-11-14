<?php
$nombre = "";
$edad = "";
$errores = "";

//Controlamos si el formulario ha sido enviado por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
}
    if ($_POST['nombre'] == '') {
        $errores .= "Introduzca un nombre<br>";
    }

    if ($_POST['edad'] == '') {
        $errores .= "Introduzca una edad<br>";
    }

    //Si hay errores, los mostramos en rojo y mostramos el formulario
    if ($errores != "") {
        echo '<p style="color:red">' . $errores . '</p>';
        include 'form2.php';
    } else {
        //Si no hay errores, mostramos el resultado de los datos enviados del formulario
        echo "<h1>Datos recibidos: </h1>";
        echo "Tu  nombre es $nombre y tu edad es $edad";
        echo "<pre>";
        print_r($_POST);
    }



