<?php
function esPrimo($numero) : bool
{
    for ($i = 2; $i < $numero; $i++) {
        if ($numero % $i == 0) {
            return false;
        }
    }
    return true;
}

$valor1 = 7;
if (esPrimo($valor1)) {
    echo "El numero $valor1 es primo. <br>";
} else {
    echo "El numero $valor1 no es primo. <br>";
}

$valor2 = 10;
if (esPrimo($valor2)) {
    echo "El numero $valor2 es primo. <br>";
} else {
    echo "El numero $valor2 no es primo. <br>";
}


echo "Numeros primos hasta el 100: <br>";

for ($i = 2; $i < 100; $i++) {
    if (esPrimo($i)) {
        echo "$i <br>";
    }
}


