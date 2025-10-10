<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProyectoApiTest extends TestCase
{
    public function test_usuario_autenticado_para_crear_propuesta()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $proyecto = Proyecto::factory()->create();

        $response = $this->postJson('/api/proyectos', [
            'titulo'          => 'Proyecto Freelaworkd',
            'descripcion'     => 'Proyecto de prueba',
            'presupuesto'     => 2000,
            'estado'          => 'abierto',
            'tiempo_estimado' => 14,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data']);
    }
}
