<?php
$error = "";
if ($_POST['numero'] == '') {
    $error = "Debe introducir un numero";
}
else if (!is_numeric($_POST['numero']) || $_POST['numero'] <= 0 || $_POST['numero'] > 10) {
    $error = "El numero debe ser numérico y entre 1 y 10";
}

if ($error == "") {
    echo "Tabla de multiplicar de $_POST[numero]<br>";
    for ($i = 1; $i <= 10; $i++) {
        echo "$_POST[numero] x $i = " . ($_POST['numero'] * $i) . "<br>";
    }
} else {
    echo "<p style='color:red'>$error</p>";
    include "formulario.php";
}

