<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

/**
 * Capa de servicio para la gestión de usuarios.
 * Encapsula la lógica de negocio y manipulación segura de contraseñas.
 */
class UserService
{
    public function listar()
    {
        return User::all();
    }

    public function crear(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function obtenerPorId(int $id): User
    {
        $usuario = User::find($id);
        if (!$usuario) {
            throw new ModelNotFoundException('Usuario no encontrado.');
        }
        return $usuario;
    }

    public function actualizar(int $id, array $data): User
    {
        $usuario = $this->obtenerPorId($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $usuario->update($data);
        return $usuario;
    }

    public function eliminar(int $id): void
    {
        $usuario = $this->obtenerPorId($id);
        $usuario->delete();
    }
}
