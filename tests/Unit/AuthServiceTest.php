<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_registrar_usuario_y_generar_token()
    {
        $role = Role::factory()->create(['id' => 3]);

        $service = app(AuthService::class);

        $data = $service->register([
            'name'     => 'Nuevo Usuario',
            'email'    => 'nuevo@example.com',
            'password' => 'secret123',
            'role_id'  => $role->id,
        ]);

        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertDatabaseHas('users', ['email' => 'nuevo@example.com']);
    }

    /** @test */
    public function lanza_error_con_credenciales_invalidas_en_login()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $service = app(AuthService::class);

        $service->login([
            'email'    => 'no@existe.com',
            'password' => 'incorrecta',
        ]);
    }
}
