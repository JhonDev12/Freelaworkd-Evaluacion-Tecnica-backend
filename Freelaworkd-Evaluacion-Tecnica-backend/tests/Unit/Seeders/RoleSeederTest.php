<?php

namespace Tests\Unit\Seeders;

use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Tests\TestCase;

class RoleSeederTest extends TestCase
{
    /** @test */
    public function ejecuta_el_seeder_correctamente()
    {
        $this->seed(RoleSeeder::class);
        $this->assertGreaterThan(0, Role::count());
    }
}
