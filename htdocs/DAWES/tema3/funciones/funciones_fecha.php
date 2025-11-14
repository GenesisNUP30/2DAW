<?php

function nombreMes(int $num_mes): string
{
    switch ($num_mes) {
        case 1:
            return "Enero";
        case 2:
            return "Febrero";
        case 3:
            return "Marzo";
        case 4:
            return "Abril";
        case 5:
            return "Mayo";
        case 6:
            return "Junio";
        case 7:
            return "Julio";
        case 8:
            return "Agosto";
        case 9:
            return "Septiembre";
        case 10:
            return "Octubre";
        case 11:
            return "Noviembre";
        case 12:
            return "Diciembre";
        default:
            return "Error";
    }
}

$mes_actual = date("m");


function NombreDiaSemana($numero_dia) {
    switch ($numero_dia) {
        case 0: return "domingo";
        case 1: return "lunes";
        case 2: return "martes";
        case 3: return "miércoles";
        case 4: return "jueves";
        case 5: return "viernes";
        case 6: return "sábado";
    }
    return "";
}

function MuestraFecha($dia, $mes, $anyo) {
    $numero_dia_semana = date("w", mktime(0, 0, 0, $mes, $dia, $anyo));
    $nombre_dia_semana = NombreDiaSemana($numero_dia_semana);
    $nombre_mes = NombreMes($mes);

    echo $nombre_dia_semana . " " . $dia . " de " . $nombre_mes . " de " . $anyo;
}
?>

