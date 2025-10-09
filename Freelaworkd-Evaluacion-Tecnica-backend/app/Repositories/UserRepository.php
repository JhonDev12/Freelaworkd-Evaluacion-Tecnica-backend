<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    public function obtenerTodos()
    {
        return User::with('role')->get();
    }

    public function crear(array $data): User
    {
        return User::create($data);
    }

    public function obtenerPorId(int $id): ?User
    {
        $usuario = User::with('role')->find($id);
        if (!$usuario) {
            throw new ModelNotFoundException('Usuario no encontrado.');
        }
        return $usuario;
    }

    public function actualizar(int $id, array $data): User
    {
        $usuario = $this->obtenerPorId($id);
        $usuario->update($data);
        return $usuario;
    }

    public function eliminar(int $id): void
    {
        $usuario = $this->obtenerPorId($id);
        $usuario->delete();
    }
}
