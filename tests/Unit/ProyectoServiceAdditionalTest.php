<?php

namespace Tests\Unit;

use App\Models\Proyecto;
use App\Models\User;
use App\Services\ProyectoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        // Crear usuario para asociar el proyecto
        $user = User::factory()->create();

        // Datos válidos
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

        $this->assertDatabaseHas('proyectos', [
            'titulo'     => 'Proyecto Freelaworkd',
            'usuario_id' => $user->id,
        ]);
    }

    /** @test */
    public function obtiene_lista_de_proyectos_existentes()
    {
        // Crear proyectos en base de datos
        Proyecto::factory()->count(3)->create();

        // Obtenerlos mediante el servicio
        $proyectos = $this->proyectoService->obtenerTodos();

        // Verificaciones
        $this->assertNotEmpty($proyectos);
        $this->assertCount(3, $proyectos);
    }
}
