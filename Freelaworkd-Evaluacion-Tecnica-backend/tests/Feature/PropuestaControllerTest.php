<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PropuestaControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_crear_propuesta()
    {
        $rol      = Role::factory()->create();
        $user     = User::factory()->create(['role_id' => $rol->id]);
        $proyecto = Proyecto::factory()->create(['usuario_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/propuestas', [
            'descripcion'     => 'Propuesta sólida',
            'presupuesto'     => 1200,
            'tiempo_estimado' => 30,
            'proyecto_id'     => $proyecto->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'descripcion']]);

        $this->assertDatabaseHas('propuestas', ['descripcion' => 'Propuesta sólida']);
    }

    /** @test */
    public function valida_campos_requeridos_al_crear_propuesta()
    {
        $rol  = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $rol->id]);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/propuestas', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descripcion', 'presupuesto', 'tiempo_estimado']);
    }
}
