<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    public function listar()
    {
        return User::all();
    }

    public function crear(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = $data['role_id'] ?? 3; // rol 'user' por defecto
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

    /**
     * Lógica de negocio para asignar roles
     */
    public function asignarRol(User $usuarioAuth, int $id, int $roleId): array
    {
        try {
            if ($usuarioAuth->role->nombre !== 'super_admin') {
                return [
                    'success' => false,
                    'mensaje' => 'No autorizado para cambiar roles.',
                    'usuario' => null,
                    'status'  => 403,
                ];
            }

            $usuario = $this->obtenerPorId($id);
            $usuario->update(['role_id' => $roleId]);

            return [
                'success' => true,
                'mensaje' => 'Rol asignado correctamente.',
                'usuario' => $usuario->refresh(),
                'status'  => 200,
            ];
        } catch (\Throwable $e) {
            Log::error('Error al asignar rol', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'mensaje' => 'Error interno al asignar el rol.',
                'usuario' => null,
                'status'  => 500,
            ];
        }
    }
}
