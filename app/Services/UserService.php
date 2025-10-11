<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Servicio del dominio Usuario.
 *
 * Centraliza la lógica de negocio asociada a los usuarios:
 * creación, actualización, eliminación, asignación de roles y habilidades.
 * Mantiene el principio de separación de responsabilidades (SRP)
 * delegando la persistencia al repositorio correspondiente.
 */
class UserService
{
    /**
     * Inyección del repositorio de usuarios.
     */
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function listar()
    {
        return $this->userRepository->obtenerTodos();
    }

    public function crear(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['role_id']  = $data['role_id'] ?? 3; // rol 'user' por defecto

        return $this->userRepository->crear($data);
    }

    public function obtenerPorId(int $id): User
    {
        $usuario = $this->userRepository->obtenerPorId($id);

        if (! $usuario) {
            throw new ModelNotFoundException('Usuario no encontrado.');
        }

        return $usuario;
    }

    public function actualizar(int $id, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->actualizar($id, $data);
    }

    public function eliminar(int $id): void
    {
        $this->userRepository->eliminar($id);
    }

    /**
     * Asigna un conjunto de habilidades a un usuario.
     *
     * Valida que se reciba al menos una habilidad válida y
     * delega la sincronización al repositorio de usuarios.
     *
     *
     * @throws \InvalidArgumentException
     */
    public function asignarHabilidades(int $usuarioId, array $habilidades): User
    {
        if (empty($habilidades)) {
            throw new \InvalidArgumentException('Debe proporcionar al menos una habilidad válida.');
        }

        return $this->userRepository->asignarHabilidades($usuarioId, $habilidades);
    }

    /**
     * Asigna un rol a un usuario con validación de permisos.
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

            $usuario = $this->userRepository->obtenerPorId($id);
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
