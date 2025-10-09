<?php

namespace App\Repositories\Contracts;

use App\Models\Role;

interface RoleRepositoryInterface
{
    /**
     * Obtiene todos los roles disponibles en el sistema.
     */
    public function obtenerTodos();
    public function crear(array $data): Role;
    public function obtenerPorId(int $id): ?Role;
    public function actualizar(int $id, array $data): Role;
    public function eliminar(int $id): void;
}
