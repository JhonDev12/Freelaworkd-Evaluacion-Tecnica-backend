<?php

namespace App\Repositories\Contracts;

use App\Models\Role;

/**
 * Contrato del repositorio de Roles.
 *
 * Define las operaciones que deben implementarse para gestionar los roles
 * dentro del sistema, aplicando el principio de inversión de dependencias (DIP)
 * del patrón Repository.
 *
 * Este contrato permite desacoplar la lógica de negocio del acceso a datos,
 * facilitando el mantenimiento, pruebas unitarias y futuras ampliaciones.
 */
interface RoleRepositoryInterface
{
    /**
     * Obtiene todos los roles disponibles en el sistema.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Role>
     */
    public function obtenerTodos();

    /**
     * Crea un nuevo rol con los datos proporcionados.
     *
     * @param  array<string, mixed>  $data
     */
    public function crear(array $data): Role;

    /**
     * Busca un rol específico por su ID.
     */
    public function obtenerPorId(int $id): ?Role;

    /**
     * Actualiza un rol existente con los datos dados.
     *
     * @param  array<string, mixed>  $data
     */
    public function actualizar(int $id, array $data): Role;

    /**
     * Elimina un rol del sistema por su ID.
     */
    public function eliminar(int $id): void;
}
