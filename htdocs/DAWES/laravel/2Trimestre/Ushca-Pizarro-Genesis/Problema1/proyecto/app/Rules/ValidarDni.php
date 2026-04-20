<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @class ValidarDni
 * @brief Regla para validar el Documento Nacional de Identidad (DNI/NIF) en España.
 * * Realiza una comprobación matemática basada en el módulo 23 para asegurar 
 * que la letra de control corresponde al número de identificación proporcionado.
 */
class ValidarDni implements ValidationRule
{
    /**
     * @brief Valida el formato y la letra del DNI.
     * * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dni = strtoupper(trim($value));

        // Verificar formato NIF: 8 dígitos + letra
        if (!preg_match('/^[0-9]{8}[A-Z]$/', $dni)) {
            $fail('El DNI debe tener 8 dígitos seguidos de una letra.');
            return;
        }

        // Validar letra de control
        $letrasNif = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $numero = substr($dni, 0, 8);
        $letra = substr($dni, -1);
        if ($letra !== $letrasNif[$numero % 23]) {
            $fail('La letra del DNI es incorrecta.');
        }
    }
}
