<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nombre' => 'super_admin', 'descripcion' => 'Control total del sistema'],
            ['nombre' => 'admin', 'descripcion' => 'Gestión de usuarios y proyectos'],
            ['nombre' => 'user', 'descripcion' => 'Usuario estándar del sistema'],
        ]);
    }
}
