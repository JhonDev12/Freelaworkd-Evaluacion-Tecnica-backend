<?php

namespace Tests\Feature;

use App\Models\Habilidad;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HabilidadPersonalFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_asignar_listar_y_eliminar_habilidades_personales(): void
    {
        $rol = Role::factory()->create(['nombre' => 'user']);
        $user = User::factory()->create(['role_id' => $rol->id]);
        $habilidades = Habilidad::factory()->count(3)->create();

        Sanctum::actingAs($user);

        // ✅ Asignar habilidades
        $asignar = $this->patchJson("/api/usuarios/{$user->id}/habilidades", [
            'habilidades' => $habilidades->pluck('id')->toArray(),
        ]);

        $asignar->assertStatus(200)
            ->assertJsonFragment([
                'mensaje' => 'Habilidades asignadas correctamente.',
            ]);

        foreach ($habilidades as $hab) {
            $this->assertDatabaseHas('user_habilidad', [
                'user_id' => $user->id,
                'habilidad_id' => $hab->id,
            ]);
        }

        // ✅ Verificar que aparecen en el perfil
        $perfil = $this->getJson("/api/usuarios/{$user->id}");
        $perfil->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'habilidades' => [
                        ['id', 'nombre']
                    ]
                ]
            ]);

        // ✅ Intentar eliminar TODAS (debe fallar con 422 según tu validación)
        $quitar = $this->patchJson("/api/usuarios/{$user->id}/habilidades", [
            'habilidades' => [],
        ]);

        $quitar->assertStatus(422)
            ->assertJsonValidationErrors(['habilidades'])
            ->assertJsonFragment([
                'message' => 'Debe proporcionar al menos una habilidad.',
            ]);
    }
}
