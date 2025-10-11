<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders principales del sistema.
     */
    public function run(): void
    {

        $this->call(RoleSeeder::class);


        $roles = Role::pluck('id', 'nombre');

        User::factory(4)->create([
            'role_id'  => $roles['super_admin'] ?? 1,
            'password' => Hash::make('12345678'),
        ]);

        User::factory(4)->create([
            'role_id'  => $roles['admin'] ?? 2,
            'password' => Hash::make('12345678'),
        ]);

        User::factory(4)->create([
            'role_id'  => $roles['user'] ?? 3,
            'password' => Hash::make('12345678'),
        ]);

        // Usuario principal
        User::create([
            'name'     => 'Jhon Dev',
            'email'    => 'jhon@example.com',
            'password' => Hash::make('12345678'),
            'role_id'  => $roles['super_admin'] ?? 1,
        ]);

        $this->call([
            HabilidadSeeder::class,
            ProyectoSeeder::class,
            PropuestaSeeder::class,
            UserHabilidadSeeder::class,
        ]);

       
        $this->command->info('Base de datos inicializada con usuarios, roles, habilidades, proyectos y propuestas.');
    }
}
