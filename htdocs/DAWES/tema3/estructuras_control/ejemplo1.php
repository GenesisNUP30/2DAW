<?php

function factorial(int $n) : int 
{
    if ($n ==0) 
        return 1;
    return $n * factorial($n-1);
}

echo "El factorial de 5 es " . factorial(5);


