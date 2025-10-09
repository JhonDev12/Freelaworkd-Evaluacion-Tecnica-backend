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

        $this->call(RoleSeeder::class);


        $roles = Role::pluck('id', 'nombre');


        User::factory(4)->create([
            'role_id' => $roles['super_admin'],
            'password' => Hash::make('12345678'),
        ]);


        User::factory(4)->create([
            'role_id' => $roles['admin'],
            'password' => Hash::make('12345678'),
        ]);


        User::factory(4)->create([
            'role_id' => $roles['user'],
            'password' => Hash::make('12345678'),
        ]);


        User::create([
            'name' => 'Jhon Dev',
            'email' => 'jhon@example.com',
            'password' => Hash::make('12345678'),
            'role_id' => $roles['super_admin'],
        ]);
    }
}
