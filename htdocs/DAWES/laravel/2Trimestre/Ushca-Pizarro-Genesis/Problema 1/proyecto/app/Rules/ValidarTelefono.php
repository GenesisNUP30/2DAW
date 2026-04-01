<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarTelefono implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
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
