<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProyectoFactory extends Factory
{
    protected $model = Proyecto::class;

    public function definition(): array
    {
        return [
            'titulo'      => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph(),
            'presupuesto' => $this->faker->numberBetween(500, 5000),
            'estado'      => $this->faker->randomElement(['abierto', 'en progreso', 'finalizado']),
            'usuario_id'  => User::factory(),
        ];
    }
}
