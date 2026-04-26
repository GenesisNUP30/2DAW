<?php

namespace Database\Factories;

use App\Models\Pais;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pais>
 */
class PaisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'iso2' => 'ES',
            'iso3' => 'ESP',
            'prefijo' => 34,
            'nombre' => 'España',
            'iso_moneda' => 'EUR'
        ];
    }
}
