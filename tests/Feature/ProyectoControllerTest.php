<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProyectoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_crear_proyecto()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        Sanctum::actingAs($user);

        $payload = [
            'titulo'      => 'App Freelaworkd',
            'descripcion' => 'Proyecto API completo',
            'presupuesto' => 2000,
            'estado'      => 'abierto',
        ];

        $response = $this->postJson('/api/proyectos', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'titulo', 'descripcion']]);

        $this->assertDatabaseHas('proyectos', ['titulo' => 'App Freelaworkd']);
    }

    /** @test */
    public function puede_listar_proyectos()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        Proyecto::factory()->count(3)->create(['usuario_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/proyectos');

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function puede_actualizar_y_eliminar_proyecto()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create(['role_id' => $role->id]);
        Sanctum::actingAs($user);

        $proyecto = Proyecto::factory()->create(['usuario_id' => $user->id]);

        $update = $this->putJson("/api/proyectos/{$proyecto->id}", [
            'titulo'      => 'Actualizado Freelaworkd',
            'descripcion' => 'Edición de prueba',
            'presupuesto' => 2500,
            'estado'      => 'en progreso',
        ]);

        $update->assertOk()
            ->assertJsonPath('data.titulo', 'Actualizado Freelaworkd');

        $delete = $this->deleteJson("/api/proyectos/{$proyecto->id}");
        $delete->assertNoContent();

        $this->assertDatabaseMissing('proyectos', ['id' => $proyecto->id]);
    }
}
