<?php
$decimal = 10;
$octal = 0123;
$hexadecimal = 0x1A;

$flotante = 3.14;
$float_cientifica = 1.2e3;

$cadena = "Esto es una cadena";

$verdadero = true;
$falso = false;

echo "Decimal: $decimal<br>";
echo "Octal: $octal<br>";
echo "Hexadecimal: $hexadecimal<br>";
echo "Float: $flotante<br>";
echo "Float cientifica: $float_cientifica<br>";
echo "Cadena: $cadena<br>";
echo "Verdadero: $verdadero<br>";
echo "Falso: $falso<br>";

echo gettype($decimal);
echo ": $decimal";
echo "<br>";
echo gettype($octal);
echo ": $octal";
echo "<br>";
echo gettype($hexadecimal);
echo ": $hexadecimal";
echo "<br>";
echo gettype($flotante);
echo ": $flotante";
echo "<br>";
echo gettype($float_cientifica);
echo ": $float_cientifica";
echo "<br>";
echo gettype($cadena);
echo ": $cadena";
echo "<br>";
echo gettype($verdadero);
echo ": $verdadero";
echo "<br>";
echo gettype($falso);
echo ": $falso";
echo "<br>";