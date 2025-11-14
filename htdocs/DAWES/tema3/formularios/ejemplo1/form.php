<?php

if (!isset($_POST['nombre'])) {
    include "form_vista.php";
} else {
    // me han enviado el formulario
    include "form_f1.php";
}
