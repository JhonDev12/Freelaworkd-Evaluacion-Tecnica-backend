<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_listar_roles()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/roles');
        $response->assertStatus(200);
    }

    /** @test */
    public function usuario_autenticado_puede_crear_rol()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/roles', [
            'nombre'      => 'Gestor',
            'descripcion' => 'Rol para gestión de proyectos.',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'nombre']]);
    }
}
