<?php

function suma1( $n) : void
{
    $n++;
}

$numero =23;
suma1($numero);
echo $numero;
echo "<br>";

function unNumero($num = 4) : int
{
    return $num;
}

echo unNumero();

function foo($primero, $segundo)
{
    return;
}

foo(segundo:3, primero:43);


function cube($n) : int {
    return $n * $n * $n;
}
