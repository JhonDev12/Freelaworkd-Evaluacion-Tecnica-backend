<?php

namespace Tests\Feature\Errors;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function devuelve_respuesta_json_en_error_http()
    {
        // Intentamos acceder a una ruta inexistente
        $response = $this->getJson('/api/ruta-invalida');

        $response->assertStatus(404);

        // Verificamos que devuelve un JSON y contenga alguna de las claves estándar
        $json = $response->json();

        $this->assertIsArray($json, 'La respuesta no es JSON');

        $this->assertTrue(
            isset($json['message']) || isset($json['error']) || isset($json['mensaje']),
            'La respuesta JSON no contiene ninguna de las claves esperadas (message, error, mensaje)'
        );
    }
}
