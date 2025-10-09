<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders principales del sistema.
     */
    public function run(): void
    {
        // Ejecutar los seeders de roles
        $this->call(RoleSeeder::class);

        // Obtener los roles desde la base de datos
        $roles = Role::pluck('id', 'nombre');

        // Crear 4 super_admin
        User::factory(4)->create([
            'role_id' => $roles['super_admin'],
            'password' => Hash::make('12345678'),
        ]);

        // Crear 4 admin
        User::factory(4)->create([
            'role_id' => $roles['admin'],
            'password' => Hash::make('12345678'),
        ]);

        // Crear 4 usuarios normales
        User::factory(4)->create([
            'role_id' => $roles['user'],
            'password' => Hash::make('12345678'),
        ]);

        // ✅ Opcional: Usuario principal para pruebas manuales
        User::create([
            'name' => 'Jhon Dev',
            'email' => 'jhon@example.com',
            'password' => Hash::make('12345678'),
            'role_id' => $roles['super_admin'],
        ]);
    }
}
