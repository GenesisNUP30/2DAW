<?php
$foo = 'Bob';                // Asigna el valor 'Bob' a $foo
$bar = &$foo;                // Referenciar $foo vía $bar.
$bar = "Mi nombre es $bar";  // Modifica $bar...
echo $bar;
echo $foo;                   // $foo también se modifica.

$a='pepe';
echo "<br>";
$$a='25';
echo "<br>";

$a='juan';
echo $pepe;
echo "<br>";

$$a='33';
echo "<br>";

echo $pepe;

unset($juan);

if (isset($juan)){
	echo "hola";
}


?>