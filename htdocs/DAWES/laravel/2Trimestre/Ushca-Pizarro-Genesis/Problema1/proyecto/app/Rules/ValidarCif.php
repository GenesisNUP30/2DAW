<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @class ValidarCif
 * @brief Regla de validación para el Código de Identificación Fiscal (CIF) español.
 * * Verifica que el valor proporcionado cumpla con el formato legal:
 * - 1 letra inicial (tipo de organización).
 * - 7 dígitos centrales.
 * - 1 dígito o letra de control final.
 * * @package App\Rules
 */
class ValidarCif implements ValidationRule
{
    /**
     * @brief Ejecuta la validación del CIF.
     * * Implementa el algoritmo de validación:
     * 1. Verifica longitud y caracteres permitidos.
     * 2. Comprueba la letra inicial contra el set legal (A-W).
     * 3. Calcula el dígito de control mediante la suma de posiciones pares e impares.
     * 4. Valida el carácter final (numérico o alfabético según el tipo de CIF).
     * * @param string $attribute Nombre del campo bajo validación.
     * @param mixed $value Valor del campo.
     * @param Closure $fail Callback para reportar errores.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cif = strtoupper(trim($value));

        // 1. Verificar formato básico (9 caracteres alfanuméricos)
        if (!preg_match('/^[A-Z0-9]{9}$/', $cif)) {
            $fail('El CIF debe tener 9 caracteres alfanuméricos.');
            return;
        }

       // 2. Verificar la primera letra válida (Letras de organizaciones españolas)
        $primeraLetra = $cif[0];
        $letrasValidas = 'ABCDEFGHJKLMNPQRSUVW';
        if (strpos($letrasValidas, $primeraLetra) === false) {
            $fail('El CIF debe comenzar con una letra válida.');
            return;
        }

        // 3. Verificar que los 7 caracteres centrales son dígitos
        for ($i = 1; $i <= 7; $i++) {
            if (!ctype_digit($cif[$i])) {
                $fail('El CIF debe tener 7 dígitos.');
                return;
            }
        }

        // 4. Algoritmo de verificación del dígito de control
        $sumaPar = 0;
        $sumaImpar = 0;

        // Suma de posiciones pares
        for ($i = 2; $i <= 6; $i += 2) {
            $sumaPar += (int) $cif[$i];
        }

        // Suma de posiciones impares (multiplicadas por 2)
        for ($i = 1; $i <= 7; $i += 2) {
            $doble = (int) $cif[$i] * 2;
            $sumaImpar += $doble > 9 ? $doble - 9 : $doble;
        }

        $sumaTotal = $sumaPar + $sumaImpar;
        $control = (10 - ($sumaTotal % 10)) % 10;
        $letrasControl = 'JABCDEFGHI';
        $controlEsperado = $cif[8];

        // 5. Validación del último carácter (Dígito de control)
        if (ctype_alpha($controlEsperado)) {
            // Si el control es una letra
            if ($controlEsperado !== $letrasControl[$control]) {
                $fail('El dígito de control del CIF es incorrecto.');
            }
        } else {
            // Si el control es un número
            if ((string) $control !== $controlEsperado) {
                $fail('El dígito de control del CIF es incorrecto.');
            }
        }

    }
}
