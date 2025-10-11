<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Habilidad;
use Illuminate\Database\Seeder;

class UserHabilidadSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de la tabla pivote user_habilidad.
     */
    public function run(): void
    {
        $usuarios = User::all();
        $habilidades = Habilidad::all();

        if ($usuarios->isEmpty() || $habilidades->isEmpty()) {
            $this->command->warn(' No hay usuarios o habilidades disponibles. Ejecuta primero los seeders de usuarios y habilidades.');
            return;
        }

        foreach ($usuarios as $user) {
            $idsHabilidades = $habilidades->random(rand(1, 5))->pluck('id')->toArray();
            $user->habilidades()->syncWithoutDetaching($idsHabilidades);
        }

        $this->command->info('Habilidades asignadas correctamente a los usuarios.');
    }
}
