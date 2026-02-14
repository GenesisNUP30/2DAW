<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarCif implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cif = strtoupper(trim($value));

        //Verificar que tiene 9 caracteres
        if (!preg_match('/^[A-Z0-9]{9}$/', $cif)) {
            $fail('El CIF debe tener 9 caracteres alfanuméricos.');
            return;
        }

        //Verificar la primera letra válida 
        $primeraLetra = $cif[0];
        $letrasValidas = 'ABCDEFGHJKLMNPQRSUVW';
        if (strpos($letrasValidas, $primeraLetra) === false) {
            $fail('El CIF debe comenzar con una letra válida.');
            return;
        }

        //Verificar que hay 7 dígitos después de la letra
        for ($i = 1; $i <= 7; $i++) {
            if (!ctype_digit($cif[$i])) {
                $fail('El CIF debe tener 7 dígitos.');
                return;
            }
        }

        //Verificar el dígito de control
        $sumaPar = 0;
        $sumaImpar = 0;
        for ($i = 2; $i <= 6; $i += 2) {
            $sumaPar += (int) $cif[$i];
        }

        for ($i = 1; $i <= 7; $i += 2) {
            $doble = (int) $cif[$i] * 2;
            $sumaImpar += $doble > 9 ? $doble - 9 : $doble;
        }

        $sumaTotal = $sumaPar + $sumaImpar;
        $control = (10 - ($sumaTotal % 10)) % 10;
        $letrasControl = 'JABCDEFGHI';
        $controlEsperado = $cif[8];

        if (ctype_alpha($controlEsperado)) {
            if ($controlEsperado !== $letrasControl[$control]) {
                $fail('El dígito de control del CIF es incorrecto.');
            }
        } else {
            if ((string) $control !== $controlEsperado) {
                $fail('El dígito de control del CIF es incorrecto.');
            }
        }

    }
}
