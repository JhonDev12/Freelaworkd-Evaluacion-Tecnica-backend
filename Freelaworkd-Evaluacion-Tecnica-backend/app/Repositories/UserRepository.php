<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

/**
 * Implementación del repositorio de usuarios.
 *
 * Encapsula la interacción con el modelo User, aplicando
 * carga de relaciones y operaciones sobre sus asociaciones.
 * Forma parte de la capa de infraestructura del dominio.
 */
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

    /**
     * Busca un usuario por ID, incluyendo sus roles y habilidades.
     * Lanza ModelNotFoundException si no existe.
     */
    public function obtenerPorId(int $id): User
    {
        return User::with('role', 'habilidades')->findOrFail($id);
    }

    public function actualizar(int $id, array $data): User
    {
        $usuario = $this->obtenerPorId($id);
        $usuario->update($data);

        return $usuario;
    }

    public function eliminar(int $id): void
    {
        $this->obtenerPorId($id)->delete();
    }

    /**
     * Sincroniza las habilidades asignadas a un usuario.
     * Reemplaza las anteriores por las nuevas (operación sync).
     */
    public function asignarHabilidades(int $usuarioId, array $habilidades): User
    {
        $usuario = $this->obtenerPorId($usuarioId);
        $usuario->habilidades()->sync($habilidades);

        return $usuario->load('habilidades');
    }
}
