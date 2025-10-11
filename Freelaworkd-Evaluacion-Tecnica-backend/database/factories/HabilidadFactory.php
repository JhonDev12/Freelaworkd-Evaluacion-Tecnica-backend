<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HabilidadFactory extends Factory
{
    public function definition(): array
    {
        $niveles = ['básico', 'intermedio', 'avanzado'];

        return [
            'nombre' => ucfirst($this->faker->unique()->word()),
            'descripcion' => $this->faker->sentence(8),
            'nivel' => $this->faker->randomElement($niveles),
        ];
    }
}
