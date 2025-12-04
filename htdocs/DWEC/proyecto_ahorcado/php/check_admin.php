<?php
session_start();

// Si no hay sesión, no es admin
if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    echo "no_autorizado";
    exit();
}

// Si es admin (1 o true), permitir
if ($_SESSION['admin'] == 1) {
    echo "ok";
} else {
    echo "no_autorizado";
}
?>