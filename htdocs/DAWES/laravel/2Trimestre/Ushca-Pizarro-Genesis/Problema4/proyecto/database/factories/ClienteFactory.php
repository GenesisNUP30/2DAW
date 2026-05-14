<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cif' => $this->faker->unique()->bothify('?########'),
            'nombre' => $this->faker->company(),
            'telefono' => $this->faker->phoneNumber(),
            'correo' => $this->faker->unique()->safeEmail(),
            'cuenta_corriente' => 'ES' . $this->faker->numerify('####################'),
            'pais' => 'ES',
            'moneda' => 'EUR',
            'importe_cuota_mensual' => $this->faker->randomFloat(2, 100, 1000),
            'fecha_alta' => now(),
        ];
    }
}
