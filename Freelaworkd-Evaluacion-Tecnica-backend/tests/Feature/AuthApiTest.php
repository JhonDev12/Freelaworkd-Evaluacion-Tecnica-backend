<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_registrar_usuario()
    {
        $role = Role::factory()->create(['id' => 3]);

        $response = $this->postJson('/api/auth/registro', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role_id'               => $role->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'mensaje',
                'data' => ['user', 'token'],
            ]);
    }

    /** @test */
    public function puede_iniciar_sesion()
    {
        $role = Role::factory()->create(['id' => 3]);

        $user = User::factory()->create([
            'email'    => 'john@example.com',
            'password' => Hash::make('password123'),
            'role_id'  => $role->id,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'mensaje',
                'data' => ['user', 'token'],
            ]);
    }
}
