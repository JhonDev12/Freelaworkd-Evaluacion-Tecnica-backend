<?php

namespace Tests\Unit\fullTest;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProyectoControllerFullTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_crud_completo_proyectos()
    {
       
        $user = User::factory()->createOne();
        Sanctum::actingAs($user);


        $createResponse = $this->postJson('/api/proyectos', [
            'titulo'      => 'Nuevo Proyecto',
            'descripcion' => 'Descripción del proyecto',
            'estado'      => 'abierto',
        ]);

        $createResponse->assertStatus(201);
        $proyectoId = $createResponse->json('data.id');


        $listResponse = $this->getJson('/api/proyectos');
        $listResponse->assertStatus(200)->assertJsonStructure(['data']);


        $updateResponse = $this->putJson("/api/proyectos/{$proyectoId}", [
            'titulo' => 'Proyecto Actualizado',
        ]);

        $updateResponse->assertStatus(200)->assertJsonFragment([
            'titulo' => 'Proyecto Actualizado',
        ]);


        $deleteResponse = $this->deleteJson("/api/proyectos/{$proyectoId}");
        $deleteResponse->assertStatus(204);
    }
}
