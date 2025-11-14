<?php
$numero = rand(1,10);
$i = 1;
while ($i <= 10) {
    echo "$numero * $i = " . $numero*$i;
    echo "<br>";
    $i++;
}

echo "El numero aleatorio es $numero";
