<?php
if (!isset($_POST['numero'])) {
    // Primera vez: mostramos el formulario
    include "ej33_form.php";
} else {
    // Si ya se envió el formulario
    $numero = $_POST['numero'];
    if (isset($_POST['sumar'])) {
        $numero++;
    }
    // Mostramos el formulario con el número actualizado
    $_POST['numero'] = $numero;
    include "ej33_form.php";
}
?>
