<?php
$num = 5;

function EstoNoSeDebeHacer() 
{
    global $num;
    $num = $num * 2;
}


echo "Antes: " . $num . "<br>";
EstoNoSeDebeHacer();
echo "Despues: " . $num . "<br>";
