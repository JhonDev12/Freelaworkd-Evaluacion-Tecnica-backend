<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_y_listar_roles()
    {
        $create = $this->postJson('/api/roles', [
            'nombre'      => 'Administrador',
            'descripcion' => 'Control total del sistema',
        ]);

        $create->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'nombre'],
            ]);

        $this->assertDatabaseHas('roles', ['nombre' => 'Administrador']);

        $index = $this->getJson('/api/roles');

    
        $index->assertOk();
        $content = $index->json();
        $this->assertTrue(isset($content['data']) || isset($content[0]));
    }

    /** @test */
    public function puede_actualizar_y_eliminar_un_rol()
    {
        $role   = Role::factory()->create(['nombre' => 'Cliente']);
        $update = $this->putJson("/api/roles/{$role->id}", [
            'nombre'      => 'Cliente Actualizado',
            'descripcion' => 'Rol con permisos limitados',
        ]);

        $update->assertOk()
            ->assertJsonPath('data.nombre', 'Cliente Actualizado');

        $delete = $this->deleteJson("/api/roles/{$role->id}");
        $delete->assertNoContent();

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
