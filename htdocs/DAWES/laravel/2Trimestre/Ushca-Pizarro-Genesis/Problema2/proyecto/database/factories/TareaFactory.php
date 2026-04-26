<?php

namespace Database\Factories;

use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tarea>
 */
class TareaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => \App\Models\Cliente::factory(),
            'operario_id' => \App\Models\User::factory(),
            'persona_contacto' => fake()->name(),
            'telefono_contacto' => '666666666',
            'descripcion' => fake()->text(20),
            'correo_contacto' => fake()->email(),
            'codigo_postal' => '28001',
            'provincia' => '28',
            'estado' => 'P',
        ];
    }
}
