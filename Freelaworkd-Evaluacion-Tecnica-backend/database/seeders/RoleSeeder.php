<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Registra los roles base del sistema.
     */
    public function run(): void
    {
        $roles = [
            [
                'id'          => 1,
                'nombre'      => 'super_admin',
                'descripcion' => 'Control total del sistema',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'          => 2,
                'nombre'      => 'admin',
                'descripcion' => 'Gestión de usuarios y proyectos',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'          => 3,
                'nombre'      => 'user',
                'descripcion' => 'Usuario estándar del sistema',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
