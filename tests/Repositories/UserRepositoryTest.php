<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(UserRepository::class);
    }

    /** @test */
    public function puede_crear_y_listar_usuarios()
    {
        $this->repo->crear([
            'name'     => 'Tester',
            'email'    => 'tester@example.com',
            'password' => bcrypt('123456'),
        ]);

        $this->assertCount(1, $this->repo->obtenerTodos());
    }

    /** @test */
    public function puede_eliminar_usuario()
    {
        $user = User::factory()->create();
        $this->repo->eliminar($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
