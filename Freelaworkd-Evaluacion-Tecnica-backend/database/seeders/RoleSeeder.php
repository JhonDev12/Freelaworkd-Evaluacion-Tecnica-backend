<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Registra los roles base del sistema.
     */
    public function run(): void
    {
        Role::insert([
            [
                'nombre' => 'super_admin',
                'descripcion' => 'Control total del sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'admin',
                'descripcion' => 'Gestión de usuarios y proyectos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'user',
                'descripcion' => 'Usuario estándar del sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
