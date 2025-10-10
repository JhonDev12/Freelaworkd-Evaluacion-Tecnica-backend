<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    /** @test */
    public function usuario_autenticado_puede_listar_usuarios()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/usuarios');
        $response->assertStatus(200);
    }
}
