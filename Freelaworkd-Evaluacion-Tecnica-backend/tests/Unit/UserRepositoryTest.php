<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_y_obtener_usuario()
    {

        $rol = Role::factory()->create([
            'nombre' => 'Freelancer',
        ]);

        $repo = new UserRepository;
        $user = $repo->crear([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('secret'),
            'role_id'  => $rol->id,
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $found = $repo->obtenerPorId($user->id);
        $this->assertEquals($user->email, $found->email);
    }
}
