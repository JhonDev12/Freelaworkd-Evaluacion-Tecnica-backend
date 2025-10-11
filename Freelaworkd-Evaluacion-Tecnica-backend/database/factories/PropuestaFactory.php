<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropuestaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'proyecto_id' => Proyecto::inRandomOrder()->first()?->id ?? Proyecto::factory(),
            'usuario_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'descripcion' => $this->faker->paragraph(2),
            'presupuesto' => $this->faker->randomFloat(2, 500, 5000),
            'tiempo_estimado' => $this->faker->numberBetween(5, 60), // días
        ];
    }
}
