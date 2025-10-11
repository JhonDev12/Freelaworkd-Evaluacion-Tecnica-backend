<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthServiceAdditionalTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = app(AuthService::class);
    }

    /** @test */
    public function lanza_excepcion_si_el_usuario_no_existe()
    {
        $this->expectException(ValidationException::class);

        $this->authService->login([
            'email'    => 'noexiste@example.com',
            'password' => 'password123',
        ]);
    }

    /** @test */
    public function lanza_excepcion_si_la_contrasena_es_incorrecta()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correcta123'),
        ]);

        $this->expectException(ValidationException::class);

        $this->authService->login([
            'email'    => $user->email,
            'password' => 'incorrecta123',
        ]);
    }

    /** @test */
    public function devuelve_token_si_el_login_es_valido()
    {
        $user = User::factory()->create([
            'password' => Hash::make('clave1234'),
        ]);

        $data = $this->authService->login([
            'email'    => $user->email,
            'password' => 'clave1234',
        ]);

        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('token', $data);
        $this->assertEquals($user->email, $data['user']->email);
    }
}
