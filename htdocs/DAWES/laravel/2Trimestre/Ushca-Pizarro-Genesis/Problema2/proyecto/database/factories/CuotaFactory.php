<?php

namespace Database\Factories;

use App\Models\Cuota;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class CuotaFactory extends Factory
{
    protected $model = Cuota::class;

    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'concepto' => 'Cuota ' . $this->faker->monthName(),
            'fecha_emision' => $this->faker->date(),
            'importe' => $this->faker->randomFloat(2, 100, 1000),
            'pagada' => $this->faker->boolean(30),
            'fecha_pago' => $this->faker->optional()->date(),
            'tipo' => $this->faker->randomElement(['mensual', 'excepcional']),
            'notas' => $this->faker->optional()->sentence(),
            'deleted_at' => null,
        ];
    }
}