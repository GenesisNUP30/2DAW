<?php
    echo "entero: ";
    echo (int) -5.8;

    echo "entero: ";
    echo (int) 5.3;
    $v ="a";

    $var = NULL;

    $a = 1; // ámbito global

function test()
{
    echo $a; // La variable $a no está definida ya que se refiere a una versión local de $a
}

