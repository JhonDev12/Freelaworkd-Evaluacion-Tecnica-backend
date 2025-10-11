<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProyectoSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            $this->command->warn('No hay usuarios, creando 5 usuarios de prueba...');
            User::factory(5)->create();
        }

        Proyecto::factory(20)->create();
    }
}
