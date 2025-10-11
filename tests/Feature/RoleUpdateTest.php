<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ==========================================================================
 * RoleUpdateTest
 * ==========================================================================
 * Verifica la actualización de roles con autenticación y validaciones
 * completas. Garantiza la integridad de datos, la unicidad de nombres
 * y el acceso autorizado a los endpoints protegidos.
 *
 * Principios:
 * - AAA (Arrange, Act, Assert)
 * - Validación real con middleware de autenticación activo.
 */
class RoleUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un rol super_admin y asignarlo al usuario autenticado
        $rolSuper = Role::factory()->create(['nombre' => 'super_admin']);
        $this->admin = User::factory()->create(['role_id' => $rolSuper->id]);
    }

    /** @test */
    public function puede_actualizar_un_rol_correctamente()
    {
        $rol = Role::factory()->create(['nombre' => 'admin']);

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/roles/{$rol->id}", [
            'nombre' => 'administrador',
            'descripcion' => 'Rol actualizado correctamente',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['mensaje' => 'Rol actualizado correctamente.']);

        $this->assertDatabaseHas('roles', [
            'id' => $rol->id,
            'nombre' => 'administrador',
        ]);
    }

    /** @test */
    public function no_puede_duplicar_el_nombre_de_otro_rol()
    {
        Role::factory()->create(['nombre' => 'admin']);
        $rol2 = Role::factory()->create(['nombre' => 'editor']);

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/roles/{$rol2->id}", [
            'nombre' => 'admin',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre']);
    }

    /** @test */
    public function puede_guardar_el_mismo_nombre_sin_error()
    {
        $rol = Role::factory()->create(['nombre' => 'moderador']);

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/roles/{$rol->id}", [
            'nombre' => 'moderador',
            'descripcion' => 'sin cambios',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', ['nombre' => 'moderador']);
    }

    /** @test */
    public function valida_que_el_nombre_sea_requerido()
    {
        $rol = Role::factory()->create();

        $response = $this->actingAs($this->admin, 'sanctum')->putJson("/api/roles/{$rol->id}", [
            'nombre' => '',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre']);
    }
}
