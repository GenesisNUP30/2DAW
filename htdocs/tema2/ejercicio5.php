<?php
$a = 5;
$b = 20;
$c = 25;
$d = 42;

// $dividendo = 10;
// $divisor = 3;
// $resto2 = $dividendo % $divisor;
// ;
// echo "Resto de $dividendo dividido por $divisor es $resto2<br>";

$suma = $a + $b + $c;
$resta = $a - $b -$c;
$multiplicacion = $a * $b * $c;
$division = $b / $a;
$resto = $a % $b;
echo "<h3>Operaciones aritmeticas simples</h3>";
echo "Suma 1: 10 + 20 + 25 = $suma<br>";
echo "Resta 1: 10 - 20 - 25 = $resta<br>";
echo "Multiplicacion 1: 10 * 20 * 25 = $multiplicacion<br>";
echo "Division 1: 20 / 5 = $division<br>";
echo "<br<br>";
echo "<h3>Resto de una division</h3>";
echo "Resto de dividir 5 por 20 es $resto<br>";
echo "<br>";

$num=3;
$den=10;
$resultado = $num/$den;
echo "<br<br>";
echo "<h3>Numerador menor que denominador</h3>";
echo "Resultado de dividir $num por $den es $resultado<br>";
echo "<br>";
$comb1 = $a - ($b + $c);
$comb2 = ($a - $b) + $c;
$comb3 = ($a + $b) * $c;
$comb4 = $a + $b * $c;
$comb5 = $d + $b / $a;
$comb6 = ($d + $b) / $a;

echo "<br<br>";
echo "<h3>Operaciones compuestas sin y con paréntesis</h3>";
echo "Combinacion 1: 5 - (20 + 25) = $comb1<br>";
echo "Combinacion 2: (5 - 20) + 25 = $comb2<br>";
echo "Combinacion 3: (5 + 20) * 25 = $comb3<br>";
echo "Combinacion 4: 5 + 20 * 25 = $comb4<br>";
echo "Combinacion 5: 42 + 20 / 5 = $comb5<br>";
echo "Combinacion 6: (42 + 20) / 5 = $comb6<br>";
echo "<br>";

echo "<br<br>";
echo "<h3>Concatenar cadenas</h3>";
$cadena1 = "Hola";
$cadena2 = $cadena1 . " Mundo";
echo $cadena2;
echo "<br>";

echo "<br<br>";
echo "<h3>Operadores lógicos</h3>";
$ver = true;
$fal = false;

echo "ver && fal = ". ($ver && $fal ?  "true" : "false")."<br>";
echo "ver || fal = ". ($ver || $fal ?  "true" : "false")."<br>";
echo "!ver = ". !$ver . "<br>";
echo "!fal = ". !$fal . "<br>";
