<?php
function DiasMes(int $num_mes) : int {
    switch ($num_mes) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            return 31;
        case 4:
        case 6:
        case 9:
        case 11:
            return 30;
        case 2:
            return 28;
        default:
            return 0;
    }
}

// for ($i = 1999; $i <= 2012; $i++) {
//     for ($j = 1; $j <= 12; $j++) {
//         $dias = DiasMes($j);
//         for ($dia = 1; $dia <= $dias; $dia++) {
//             echo "$i-$j-$dia ";
//             if ($dia < $dias) {
//                 echo ",";
//             }
//         }
//         echo "<br><br>";

//     }
// }
for ($anio = 1999; $anio <= 2012; $anio++) {
    for ($mes = 1; $mes <= 12; $mes++) {
        $dias = DiasMes($mes);

        for ($dia = 1; $dia <= $dias; $dia++) {
            echo $dia . "/" . $mes . "/" . $anio;

            if ($dia < $dias) {
                echo ", "; // separador dentro del mes
            }
        }

        echo "<br>"; // salto de línea al terminar el mes
    }
}
