<?php

namespace Tests\Unit;

use App\Models\Proyecto;
use App\Models\User;
use App\Services\ProyectoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProyectoServiceAdditionalTest extends TestCase
{
    use RefreshDatabase;

    protected ProyectoService $proyectoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->proyectoService = app(ProyectoService::class);
    }

    /** @test */
    public function puede_crear_un_proyecto_con_datos_validos()
    {
        $user = User::factory()->create();

        $data = [
            'titulo'      => 'Proyecto Freelaworkd',
            'descripcion' => 'Proyecto de prueba técnica',
            'presupuesto' => 1200,
            'estado'      => 'abierto',
        ];

        // Ejecutar método del servicio
        $proyecto = $this->proyectoService->crear($data, $user->id);

        // Verificaciones
        $this->assertInstanceOf(Proyecto::class, $proyecto);
        $this->assertEquals('Proyecto Freelaworkd', $proyecto->titulo);

        // ✅ Se verifica contra user_id, no usuario_id
        $this->assertDatabaseHas('proyectos', [
            'titulo'  => 'Proyecto Freelaworkd',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function obtiene_lista_de_proyectos_existentes()
    {
        $user = User::factory()->create();
        Auth::login($user); // ✅ define usuario autenticado

        Proyecto::factory()->count(3)->create(['user_id' => $user->id]);

        $proyectos = $this->proyectoService->obtenerTodos();

        $this->assertNotEmpty($proyectos);
        $this->assertCount(3, $proyectos);
    }
}
