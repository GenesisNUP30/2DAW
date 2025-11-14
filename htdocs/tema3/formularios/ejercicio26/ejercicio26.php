<?php

$numero = $_POST['numero'];

for ($i = 1; $i <= 10; $i++) {
    echo "$i * $numero = " . ($i * $numero) . "<br>";
}
