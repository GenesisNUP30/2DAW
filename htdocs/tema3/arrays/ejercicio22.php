<?php
function ModificaArray(array $arr) {
    $arr[0] = 999;
}

function ModificaArrayRef(array &$arr) {
    $arr[0] = 999;
}

$valores = [1, 2, 3];
ModificaArray($valores);
echo "Después de ModificaArray: " . $valores[0] . "<br>"; 

ModificaArrayRef($valores);
echo "Después de ModificaArrayRef: " . $valores[0] . "<br>";


