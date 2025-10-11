<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\ProyectoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProyectoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProyectoService $proyectoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->proyectoService = app(ProyectoService::class);
    }

    /** @test */
    public function crea_proyecto_exitosamente()
    {
        $user = User::factory()->create();

        $proyecto = $this->proyectoService->crear([
            'titulo'      => 'App Freelaworkd',
            'descripcion' => 'Proyecto de prueba unitaria',
            'presupuesto' => 1500,
            'estado'      => 'abierto',
        ], $user->id);

        $this->assertDatabaseHas('proyectos', ['titulo' => 'App Freelaworkd']);
    }
}
