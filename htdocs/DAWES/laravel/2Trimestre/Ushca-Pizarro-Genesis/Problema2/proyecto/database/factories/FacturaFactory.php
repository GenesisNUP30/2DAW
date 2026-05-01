<?php

namespace Database\Factories;

use App\Models\Factura;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Factura>
 */
class FacturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cuota_id' => \App\Models\Cuota::factory(),
            'numero_factura' => 'F-' . $this->faker->unique()->numerify('######'),
            'cliente_nombre' => $this->faker->name(),
            'cliente_cif' => $this->faker->bothify('ES#########'),
            'concepto' => $this->faker->sentence(3),
            'importe' => $this->faker->randomFloat(2, 50, 2000),
            'moneda' => 'EUR',
            'enviada' => false,
            'ruta_pdf' => null,
        ];
    }
}
