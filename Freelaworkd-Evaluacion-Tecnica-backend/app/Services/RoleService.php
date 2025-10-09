<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * RoleService
 *
 * Contiene la lógica de negocio asociada a los roles.
 * Actúa como intermediario entre el controlador y el repositorio,
 * garantizando validaciones, consistencia y desacoplamiento.
 */
class RoleService
{
    public function __construct(private RoleRepositoryInterface $roleRepository) {}

    public function listar()
    {
        return $this->roleRepository->obtenerTodos();
    }

    public function crear(array $data): Role
    {
        return $this->roleRepository->crear($data);
    }

    public function obtenerPorId(int $id): Role
    {
        return $this->roleRepository->obtenerPorId($id);
    }

    public function actualizar(int $id, array $data): Role
    {
        return $this->roleRepository->actualizar($id, $data);
    }

    public function eliminar(int $id): void
    {
        $this->roleRepository->eliminar($id);
    }
}
