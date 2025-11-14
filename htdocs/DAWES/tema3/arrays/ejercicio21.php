<?php

$coches = [
    ["matricula" => "1234DRF", "marca" => "Toyota", "modelo" => "Camry", "color" => "Blanco"],
    ["matricula" => "9876YHC", "marca" => "Ford", "modelo" => "Mustang", "color" => "Amarillo"],
    ["matricula" => "3456MJZ", "marca" => "Honda", "modelo" => "Civic", "color" => "Rojo"],
    ["matricula" => "5678LPR", "marca" => "Chevrolet", "modelo" => "Corvette", "color" => "Azul"],
    ["matricula" => "7890HNM", "marca" => "Toyota", "modelo" => "Camry", "color" => "Blanco"]
];


function MuestraCoche(array $coche) {
    echo "Matricula: " . $coche["matricula"] . "<br>";
    echo "Marca: " . $coche["marca"] . "<br>";
    echo "Modelo: " . $coche["modelo"] . "<br>";
    echo "Color: " . $coche["color"] . "<br>";
}

function MuestraCoches($lista) {
    foreach ($lista as $coche) {
        MuestraCoche($coche);
    }
}

echo "Lista inicial:<br>";
MuestraCoches($coches);

$coches[] = ["matricula" => "5678DWF", "color" => "Azul", "modelo" => "Ibiza", "marca" => "Seat"];
$coches[] = ["matricula" => "9999GPV", "color" => "Negro", "modelo" => "Golf", "marca" => "Volkswagen"];

echo "<br>Lista modificada:<br>";
MuestraCoches($coches);
