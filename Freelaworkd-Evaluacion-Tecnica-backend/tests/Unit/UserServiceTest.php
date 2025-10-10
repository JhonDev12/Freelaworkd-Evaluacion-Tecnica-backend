<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    /** @test */
    public function test_crea_usuario_con_password_encriptado()
    {
        $role = Role::factory()->create(['id' => 3, 'nombre' => 'user']);

        $user = $this->userService->crear([
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'secret123',
        ]);

        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    /** @test */
    public function lanza_excepcion_si_usuario_no_existe()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->userService->obtenerPorId(999);
    }
}
