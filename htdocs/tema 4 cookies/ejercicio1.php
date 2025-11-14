<?php 
session_start();
if (!isset($_SESSION['cuenta_paginas'])) {
    $_SESSION['cuenta_paginas'] = 1;
} else {
    $_SESSION['cuenta_paginas']++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1 - Contar páginas visitadas</title>
</head>
<body>
    <?php 
    echo "Desde que entraste has visto " . $_SESSION['cuenta_paginas'] . " páginas";
    ?>
</body>
</html>
