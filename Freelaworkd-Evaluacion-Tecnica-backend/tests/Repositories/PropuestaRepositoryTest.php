<?php

namespace Tests\Repositories;

use App\Models\Proyecto;
use App\Models\Role;
use App\Models\User;
use App\Repositories\ProyectoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProyectoRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_y_obtener_proyecto()
    {
        $rol  = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $rol->id]);

        $repo     = new ProyectoRepository;
        $proyecto = $repo->crear([
            'titulo'      => 'Proyecto Freelaworkd',
            'descripcion' => 'Proyecto de prueba',
            'presupuesto' => 1500,
            'estado'      => 'abierto',
            'usuario_id'  => $user->id,
        ]);

        $this->assertInstanceOf(Proyecto::class, $proyecto);
        $this->assertDatabaseHas('proyectos', ['titulo' => 'Proyecto Freelaworkd']);
    }
}
