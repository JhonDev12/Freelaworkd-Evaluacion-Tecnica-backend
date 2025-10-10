<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HabilidadApiTest extends TestCase
{
    /** @test */
    public function usuario_autenticado_puede_crear_habilidad()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/habilidades', [
            'nombre' => 'Vue.js',
            'nivel'  => 'intermedio',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'nombre']]);
    }
}
