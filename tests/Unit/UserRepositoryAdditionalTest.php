<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase; // implementación concreta enlazada en tu ServiceProvider

class UserRepositoryAdditionalTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(UserRepository::class);
    }

    /** @test */
    public function puede_actualizar_un_usuario_existente()
    {
        $user = User::factory()->create(['name' => 'John']);

        $this->repo->actualizar($user->id, ['name' => 'Jhon Updated']);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'name' => 'Jhon Updated',
        ]);
    }

    /** @test */
    public function puede_eliminar_un_usuario()
    {
        $user = User::factory()->create();

        $this->repo->eliminar($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
