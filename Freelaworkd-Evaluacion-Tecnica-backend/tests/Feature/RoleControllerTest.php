<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crea un usuario con rol 'super_admin', lo autentica con Sanctum
     * y verifica que puede crear y listar roles.
     *
     * Nota: la asignación de rol al usuario se intenta de forma defensiva:
     * si el modelo User tiene la relación role() se usa ->role()->associate(),
     * si no, se intenta escribir role_id directamente.
     */
    /** @test */
    public function puede_crear_y_listar_roles()
    {
      
        $superRole = Role::factory()->create(['nombre' => 'super_admin']);

        $user = User::factory()->create();


        if (method_exists($user, 'role')) {
            $user->role()->associate($superRole);
            $user->save();
        } else {

            if (array_key_exists('role_id', $user->getAttributes())) {
                $user->role_id = $superRole->id;
                $user->save();
            }
        }


        Sanctum::actingAs($user, ['*']);

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

    /**
     * Crea un rol por factory, autentica un usuario super_admin
     * y prueba actualización y eliminación del rol.
     */
    /** @test */
    public function puede_actualizar_y_eliminar_un_rol()
    {
        $role = Role::factory()->create(['nombre' => 'Cliente']);

        $superRole = Role::factory()->create(['nombre' => 'super_admin']);
        $user = User::factory()->create();

        if (method_exists($user, 'role')) {
            $user->role()->associate($superRole);
            $user->save();
        } else {
            if (array_key_exists('role_id', $user->getAttributes())) {
                $user->role_id = $superRole->id;
                $user->save();
            }
        }

        Sanctum::actingAs($user, ['*']);


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
