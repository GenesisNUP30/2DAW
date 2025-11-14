<?php

function Intercambia(int $v1, int $v2): void
{
    $temp = $v1;
    $v1 = $v2;
    $v2 = $temp;
}

$num1 = 5;
$num2 = 10;

echo "Antes: " . $num1 . ", " . $num2 . "<br>"; 
Intercambia($num1, $num2);
echo "Despues: " . $num1 . ", " . $num2 . "<br>";





