<?php

function FechaActual() : array {
    return [
        'dia' => date('d'),
        'mes' => date('m'),
        'anio' => date('Y')
    ];
}

$fecha = FechaActual();
echo $fecha['dia']. '/' . $fecha['mes']. '/' . $fecha['anio'] . "<br>";

