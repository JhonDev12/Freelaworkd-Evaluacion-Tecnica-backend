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
    /**
     * Obtiene todos los usuarios con sus relaciones principales.
     */
    public function obtenerTodos()
    {
        // Incluimos tanto el rol como las habilidades
        return User::with(['role', 'habilidades'])->get();
    }

    /**
     * Crea un nuevo usuario.
     */
    public function crear(array $data): User
    {
        $usuario = User::create($data);

        // Carga relaciones para devolver un objeto completo
        return $usuario->load(['role', 'habilidades']);
    }

    /**
     * Busca un usuario por ID, incluyendo roles y habilidades.
     */
    public function obtenerPorId(int $id): User
    {
        return User::with(['role', 'habilidades'])->findOrFail($id);
    }

    /**
     * Actualiza un usuario y devuelve el modelo con relaciones actualizadas.
     */
    public function actualizar(int $id, array $data): User
    {
        $usuario = $this->obtenerPorId($id);
        $usuario->update($data);

        return $usuario->load(['role', 'habilidades']);
    }

    /**
     * Elimina un usuario.
     */
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

        return $usuario->load(['role', 'habilidades']);
    }
}
