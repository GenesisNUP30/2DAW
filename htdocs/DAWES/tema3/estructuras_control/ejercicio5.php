<?php
$i = 1;
while ($i <= 1000) {
    if ($i % 3 == 0 && $i % 4 == 0) {
        echo "$i es divisible por 3 y 4<br>";
    }
    $i++;
}
