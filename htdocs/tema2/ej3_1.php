<?php
$a=10;
$b=20;
if ($a > $b) {
    $v=1234;
    echo $v;
} 

{
    $v=1234;
    var_dump($v);
}

if ($cond): 
    //verdadero
endif;

$arr = ['zero', 'one', 'two', 'three', 'four', 'five', 'six'];
foreach ($arr as $key => $value) {
    if (0 === ($key % 2)) { // elude los miembros pares
        continue;
    }
    echo $value . "\n";
}
