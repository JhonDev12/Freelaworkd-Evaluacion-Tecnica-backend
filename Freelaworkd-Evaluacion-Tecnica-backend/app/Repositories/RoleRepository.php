<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * RoleRepository
 *
 * Implementa las operaciones CRUD sobre la tabla roles.
 * Encapsula el acceso a Eloquent para mantener la capa de datos desacoplada.
 */
class RoleRepository implements RoleRepositoryInterface
{
    public function obtenerTodos()
    {
        return Role::all();
    }

    public function crear(array $data): Role
    {
        return Role::create($data);
    }

    public function obtenerPorId(int $id): ?Role
    {
        $rol = Role::find($id);
        if (!$rol) {
            throw new ModelNotFoundException("Rol no encontrado con ID {$id}");
        }
        return $rol;
    }

    public function actualizar(int $id, array $data): Role
    {
        $rol = $this->obtenerPorId($id);
        $rol->update($data);
        return $rol;
    }

    public function eliminar(int $id): void
    {
        $rol = $this->obtenerPorId($id);
        $rol->delete();
    }
}
