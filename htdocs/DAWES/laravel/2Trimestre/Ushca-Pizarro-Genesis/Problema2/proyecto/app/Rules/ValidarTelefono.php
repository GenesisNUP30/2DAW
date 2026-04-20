<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @class ValidarTelefono
 * @brief Regla para la validación de números telefónicos.
 * * Admite formatos con prefijos (+), paréntesis, espacios, guiones y puntos,
 * pero valida que la cantidad de dígitos reales esté dentro de los estándares internacionales.
 */
class ValidarTelefono implements ValidationRule
{
    /**
     * @brief Ejecuta la validación del teléfono.
     * * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Extraer solo los números para validar la longitud real
        $soloDigitos = preg_replace('/[^0-9]/', '', $value);

        // Verificar que el número resultante tenga entre 9 y 15 dígitos
        if (strlen($soloDigitos) < 9) {
            $fail('El teléfono debe tener al menos 9 dígitos.');
        }

        if (strlen($soloDigitos) > 15) {
            $fail('El teléfono no puede tener más de 15 dígitos.');
        }

        // Verificar formato (solo caracteres permitidos)
        if (!preg_match('/^[\+()0-9\s\-.]+$/', $value)) {
            $fail('El teléfono contiene caracteres no permitidos. Solo se permiten números, +, (), -, . y espacios.');
        }
    }
}
