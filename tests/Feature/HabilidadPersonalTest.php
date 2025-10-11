<?php

namespace Tests\Feature;

use App\Models\Habilidad;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HabilidadPersonalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_puede_agregar_habilidad_personal()
    {
        $rol  = Role::factory()->create(['nombre' => 'user']);
        $user = User::factory()->create(['role_id' => $rol->id]);
        Sanctum::actingAs($user);

        $habilidad = Habilidad::factory()->create(['nombre' => 'Vue.js']);

        $this->patchJson("/api/usuarios/{$user->id}/habilidades", [
            'habilidades' => [$habilidad->id],
        ])->assertStatus(200);

        $this->assertDatabaseHas('user_habilidad', [
            'user_id'      => $user->id,
            'habilidad_id' => $habilidad->id,
        ]);
    }
}
