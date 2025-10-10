<?php

namespace Tests\Unit;

use App\Models\Propuesta;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_generar_instancias_de_todos_los_factories()
    {

        $usuario  = User::factory()->create();
        $proyecto = Proyecto::factory()->create();

        $propuesta = Propuesta::factory()->create([
            'usuario_id'  => $usuario->id,
            'proyecto_id' => $proyecto->id,
        ]);

        $this->assertDatabaseHas('propuestas', [
            'id'          => $propuesta->id,
            'usuario_id'  => $usuario->id,
            'proyecto_id' => $proyecto->id,
        ]);
    }
}
