<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProyectoFactory extends Factory
{
    public function definition(): array
    {
        $estados = ['abierto', 'en progreso', 'finalizado'];

        return [
            'titulo' => ucfirst($this->faker->sentence(3)),
            'descripcion' => $this->faker->paragraph(3),
            'presupuesto' => $this->faker->randomFloat(2, 1000, 10000),
            'estado' => $this->faker->randomElement($estados),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
