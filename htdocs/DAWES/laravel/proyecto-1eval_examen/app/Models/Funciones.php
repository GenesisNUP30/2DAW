<?php

namespace App\Models;

/**
 * Clase Funciones
 *
 * Contiene funciones utilitarias y validaciones comunes para el sistema de gestión de tareas.
 * Incluye validaciones de NIF/CIF, teléfonos, contraseñas, formato de fechas,
 * manejo de errores y lista de provincias españolas.
 *
 * @package App\Models
 */
class Funciones
{
    /**
     * Validar un NIF o CIF.
     *
     * Comprueba si el valor introducido es un NIF o CIF válido. 
     * Para NIF verifica la letra final según el algoritmo español. 
     * Para CIF calcula el dígito/letra de control.
     *
     * @param string $dni El NIF o CIF a validar.
     * @return true|string Devuelve true si es válido, de lo contrario devuelve un mensaje de error.
     */
    public static function validarNif($dni)
    {
        $dni = strtoupper($dni); // Convertir a mayúsculas

        if (!preg_match('/^[A-Z0-9]{9}$/', $dni)) {
            return 'El NIF/CIF debe tener 9 caracteres';
        }

        $letrasNif = 'TRWAGMYFPDXBNJZSQVHLCKE';

        if (preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            $numero = substr($dni, 0, 8);
            $letra = substr($dni, -1);
            if ($letra !== $letrasNif[$numero % 23]) {
                return "La letra del NIF no es correcta.";
            }
            return true;
        }

        if (preg_match('/^[ABCDEFGHJNPQRSUVW][0-9]{7}[A-Z0-9]$/', $dni)) {
            $sumaPar = 0;
            $sumaImpar = 0;

            for ($i = 1; $i <= 6; $i += 2) {
                $sumaPar += (int) $dni[$i];
            }

            for ($i = 0; $i <= 6; $i += 2) {
                $doble = (int) $dni[$i] * 2;
                $sumaImpar += $doble > 9 ? $doble - 9 : $doble;
            }

            $sumaTotal = $sumaPar + $sumaImpar;
            $control = (10 - ($sumaTotal % 10)) % 10;
            $letrasControl = "JABCDEFGHI";
            $controlEsperado = $dni[8];

            if (ctype_alpha($controlEsperado)) {
                if ($controlEsperado === $letrasControl[$control]) {
                    return true;
                } else {
                    return "La letra de control del CIF no es correcto.";
                }
            } else {
                if ((string)$control === $controlEsperado) {
                    return true;
                } else {
                    return "El número de control del CIF no es correcto.";
                }
            }
        }

        return "El NIF/CIF no es válido.";
    }

    /**
     * Valida un número de teléfono.
     *
     * Permite caracteres: +, (), números, espacios, guiones y puntos.
     * Además comprueba que la longitud en dígitos esté entre 7 y 15.
     *
     * @param string $telefono Teléfono a validar.
     * @return true|string True si válido, o mensaje de error.
     */
    public static function telefonoValido($telefono)
    {
        if (!preg_match("/^[+()0-9\s\-.]+$/", $telefono)) {
            return "El teléfono no es válido, solo se pemiten números, espacios, guiones y +.";
        }

        $soloDigitos = preg_replace('/[^0-9]/', '', $telefono);
        $long = strlen($soloDigitos);
        if ($long < 7) {
            return "El teléfono debe tener al menos 7 dígitos.";
        }
        if ($long > 15) {
            return "El teléfono no puede tener más de 15 dígitos.";
        }
        return true;
    }

    /**
     * Lista de provincias españolas.
     *
     * @var array
     */
    public static $provincias = [
        "01" => "Araba/Álava",
        "02" => "Albacete",
        "03" => "Alicante/Alacant",
        "04" => "Almería",
        "05" => "Ávila",
        "06" => "Badajoz",
        "07" => "Balears, Illes",
        "08" => "Barcelona",
        "09" => "Burgos",
        "10" => "Cáceres",
        "11" => "Cádiz",
        "12" => "Castellón/Castelló",
        "13" => "Ciudad Real",
        "14" => "Córdoba",
        "15" => "Coruña, A",
        "16" => "Cuenca",
        "17" => "Girona",
        "18" => "Granada",
        "19" => "Guadalajara",
        "20" => "Gipuzkoa",
        "21" => "Huelva",
        "22" => "Huesca",
        "23" => "Jaén",
        "24" => "León",
        "25" => "Lleida",
        "26" => "Rioja, La",
        "27" => "Lugo",
        "28" => "Madrid",
        "29" => "Málaga",
        "30" => "Murcia",
        "31" => "Navarra",
        "32" => "Ourense",
        "33" => "Asturias",
        "34" => "Palencia",
        "35" => "Palmas, Las",
        "36" => "Pontevedra",
        "37" => "Salamanca",
        "38" => "Santa Cruz de Tenerife",
        "39" => "Cantabria",
        "40" => "Segovia",
        "41" => "Sevilla",
        "42" => "Soria",
        "43" => "Tarragona",
        "44" => "Teruel",
        "45" => "Toledo",
        "46" => "Valencia/València",
        "47" => "Valladolid",
        "48" => "Bizkaia",
        "49" => "Zamora",
        "50" => "Zaragoza",
        "51" => "Ceuta",
        "52" => "Melilla",
    ];

    /**
     * Genera el HTML de las opciones de provincias en un select.
     *
     * @param string $provinciaSeleccionada Código de la provincia que se desea marcar como seleccionada.
     * @return void
     */
    public static function mostrarProvincias($provinciaSeleccionada = "")
    {
        foreach (self::$provincias as $codigo => $nombre) {
            $selected = ($codigo == $provinciaSeleccionada) ? 'selected' : '';
            echo "<option value=\"$codigo\" $selected>" . htmlspecialchars($nombre) . "</option>";
        }
    }

    /**
     * Comprueba que las contraseñas cumplan los criterios.
     *
     * @param string $password_antigua Contraseña actual.
     * @param string $password_nueva Nueva contraseña.
     * @param string $password_nueva2 Repetición de la nueva contraseña.
     * @return true|string True si válido, o mensaje de error.
     */
    public static function comprobarPassword($password_antigua, $password_nueva, $password_nueva2)
    {
        if ($password_nueva === "" && $password_nueva2 === "") {
            return true;
        }

        if ($password_antigua === $password_nueva) {
            return "La contraseña antigua no puede ser la misma que la nueva";
        }

        if ($password_nueva !== $password_nueva2) {
            return "Las contraseñas no coinciden";
        }

        return true;
    }

    /**
     * Cambia el formato de fecha de YYYY-MM-DD a DD/MM/YYYY.
     *
     * @param string $fecha Fecha en formato YYYY-MM-DD.
     * @return string Fecha en formato DD/MM/YYYY.
     */
    public static function cambiarFormatoFecha($fecha)
    {
        if (!$fecha) return '';
        $partes = explode('-', substr($fecha, 0, 10));
        if (count($partes) !== 3) return $fecha;
        return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
    }

    /**
     * Formatea fecha y hora de YYYY-MM-DD HH:MM:SS a DD/MM/YYYY HH:MM:SS.
     *
     * @param string $fechaHora Fecha y hora en formato YYYY-MM-DD HH:MM:SS.
     * @return string Fecha y hora en formato DD/MM/YYYY HH:MM:SS.
     */
    public static function formatearFechaHora($fechaHora)
    {
        if (!$fechaHora) return '';
        $fecha = substr($fechaHora, 0, 10);
        $hora = substr($fechaHora, 11);
        $partes = explode('-', $fecha);
        if (count($partes) !== 3) return $fechaHora;
        return $partes[2] . '/' . $partes[1] . '/' . $partes[0] . ' ' . $hora;
    }

    /**
     * Array que almacena errores de validación.
     *
     * @var array
     */
    public static $errores = [];

    /**
     * Muestra un error en HTML para un campo específico.
     *
     * @param string $campo Nombre del campo a mostrar el error.
     * @return string HTML con el mensaje de error o vacío si no hay error.
     */
    public static function verErrores($campo)
    {
        if (isset(self::$errores[$campo])) {
            echo "<div class=\"error\"> " . htmlspecialchars(self::$errores[$campo]) . "</div>";
        } else {
            return "";
        }
    }
}
