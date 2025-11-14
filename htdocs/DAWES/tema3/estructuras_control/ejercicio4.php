<?php
$numero = rand(1,10);
$mensaje = "El numero aleatorio es ";
switch ($numero) {
    case 1:
        echo "$mensaje uno";
        break;
    case 2:
        echo "$mensaje dos";
        break;
    case 3:
        echo "$mensaje tres";
        break;
    case 4:
        echo "$mensaje cuatro";
        break;
    case 5:
        echo "$mensaje cinco";
        break;
    case 6:
        echo "$mensaje seis";
        break;
    case 7:
        echo "$mensaje siete";
        break;
    case 8:
        echo "$mensaje ocho";
        break;
    case 9:
        echo "$mensaje nueve";
        break;
    case 10:
        echo "$mensaje diez";
        break;
    default:
        echo "El numero aleatorio no es un numero valido";
}

