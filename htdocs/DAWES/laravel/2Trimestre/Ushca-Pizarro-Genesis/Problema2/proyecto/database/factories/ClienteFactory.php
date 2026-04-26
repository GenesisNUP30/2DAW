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
            'cif' => fake()->unique()->bothify('########?'),
            'nombre' => fake()->name(),
            'telefono' => fake()->phoneNumber(),
            'correo' => $this->faker->email(),
            'cuenta_corriente' => $this->faker->iban(),
            'pais' => 'ES',
            'moneda' => 'EUR',
            'importe_cuota_mensual' => 100,
            'fecha_alta' => now(),
        ];
    }
}
