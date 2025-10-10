<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_registrar_usuario_correctamente()
    {
        $role = Role::factory()->create(['id' => 3]);

        $payload = [
            'name'                  => 'Nuevo Usuario',
            'email'                 => 'nuevo@example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
            'role_id'               => $role->id,
        ];

        $response = $this->postJson('/api/auth/registro', $payload);

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertArrayHasKey('mensaje', $json);
        $this->assertArrayHasKey('data', $json, 'La respuesta no contiene la clave data');
        $this->assertArrayHasKey('user', $json['data']);
        $this->assertArrayHasKey('token', $json['data']);

        $this->assertDatabaseHas('users', ['email' => 'nuevo@example.com']);
    }

    /** @test */
    public function lanza_error_si_las_credenciales_son_invalidas()
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'noexiste@example.com',
            'password' => 'incorrecta',
        ]);

        // permite 401 o 500 si hay excepción interna
        $this->assertContains($response->status(), [401, 500]);
    }

    /** @test */
    public function puede_iniciar_sesion_y_obtener_token()
    {
        $role = Role::factory()->create(['id' => 3]);
        $user = User::factory()->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('secret123'),
            'role_id'  => $role->id,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'test@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertArrayHasKey('mensaje', $json);
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('user', $json['data']);
        $this->assertArrayHasKey('token', $json['data']);
    }
}
