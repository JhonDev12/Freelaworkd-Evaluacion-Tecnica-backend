<?php

namespace Tests\Unit\fullTest;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PropuestaControllerFullTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_crear_y_ver_propuesta()
    {
        $user = User::factory()->createOne();
        Sanctum::actingAs($user);

      
        $proyecto = Proyecto::factory()->create(['user_id' => $user->id]);

        $createResponse = $this->postJson('/api/propuestas', [
            'descripcion'     => 'Detalles de la propuesta',
            'presupuesto'     => 1500.50,
            'tiempo_estimado' => 10,
            'proyecto_id'     => $proyecto->id,
        ]);

        $createResponse->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'descripcion',
                    'presupuesto',
                    'tiempo_estimado',

                ],
            ]);

        $listResponse = $this->getJson('/api/propuestas');
        $listResponse->assertStatus(200);
    }
}
