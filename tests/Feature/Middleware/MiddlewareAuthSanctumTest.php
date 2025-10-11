<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MiddlewareAuthSanctumTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function impide_acceso_a_ruta_protegida_sin_token()
    {
        $response = $this->getJson('/api/proyectos');
        $response->assertStatus(401);
    }

    /** @test */
    public function permite_acceso_con_token_valido()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/proyectos');
        $response->assertStatus(200);
    }
}
