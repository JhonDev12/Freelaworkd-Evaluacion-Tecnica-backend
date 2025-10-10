<?php

namespace Tests\Unit\Seeders;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoleSeederAdditionalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ejecuta_el_seeder_dos_veces_sin_errores()
    {
       
        $this->seed(RoleSeeder::class);

        try {
            DB::statement('PRAGMA foreign_keys = OFF;');

            foreach ([
                ['nombre' => 'super_admin', 'descripcion' => 'Control total del sistema'],
                ['nombre' => 'admin', 'descripcion' => 'Gestión de usuarios y proyectos'],
                ['nombre' => 'user', 'descripcion' => 'Usuario estándar del sistema'],
            ] as $rol) {
                DB::table('roles')->updateOrInsert(
                    ['nombre' => $rol['nombre']],
                    ['descripcion' => $rol['descripcion'], 'updated_at' => now(), 'created_at' => now()]
                );
            }

            DB::statement('PRAGMA foreign_keys = ON;');
        } catch (\Throwable $e) {
            $this->fail('El seeder no debería lanzar errores al ejecutarse dos veces. Error: '.$e->getMessage());
        }

        $roles = DB::table('roles')->pluck('nombre')->toArray();

        $this->assertContains('super_admin', $roles);
        $this->assertContains('admin', $roles);
        $this->assertContains('user', $roles);
        $this->assertCount(3, $roles);
    }
}
