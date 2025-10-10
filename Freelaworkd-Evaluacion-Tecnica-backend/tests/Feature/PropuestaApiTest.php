<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PropuestaApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_usuario_autenticado_puede_crear_propuesta()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $proyecto = Proyecto::factory()->create();

        $response = $this->postJson('/api/propuestas', [
            'descripcion'     => 'Propuesta desde test',
            'presupuesto'     => 1500,
            'tiempo_estimado' => 14, 
            'proyecto_id'     => $proyecto->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data']);
    }
}
