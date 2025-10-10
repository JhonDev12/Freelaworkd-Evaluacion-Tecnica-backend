<?php

namespace Database\Factories;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropuestaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'descripcion'     => $this->faker->sentence(),
            'presupuesto'     => $this->faker->randomFloat(2, 1000, 10000),
            'tiempo_estimado' => $this->faker->numberBetween(5, 90),
            'usuario_id'      => User::factory(),
            'proyecto_id'     => Proyecto::factory(),
        ];
    }
}
