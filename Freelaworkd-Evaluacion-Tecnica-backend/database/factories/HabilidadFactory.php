<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HabilidadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'      => $this->faker->unique()->word(),
            'descripcion' => $this->faker->sentence(),
            'nivel'       => $this->faker->randomElement(['básico', 'intermedio', 'avanzado']),
        ];
    }
}
