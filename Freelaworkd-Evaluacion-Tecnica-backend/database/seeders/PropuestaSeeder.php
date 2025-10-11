<?php

namespace Database\Seeders;

use App\Models\Propuesta;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropuestaSeeder extends Seeder
{
    public function run(): void
    {
        if (Proyecto::count() === 0) {
            $this->command->warn('No hay proyectos, creando 5 proyectos de prueba...');
            Proyecto::factory(5)->create();
        }

        if (User::count() === 0) {
            $this->command->warn('No hay usuarios, creando 5 usuarios de prueba...');
            User::factory(5)->create();
        }

        Propuesta::factory(20)->create();
    }
}
