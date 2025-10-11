<?php

namespace App\Repositories\Contracts;

use App\Models\User;

/**
 * Contrato del repositorio de Usuarios.
 *
 * Define las operaciones básicas de persistencia para el modelo `User`,
 * aplicando el patrón Repository y el principio de inversión de dependencias (DIP).
 *
 * Este contrato permite mantener la lógica de negocio desacoplada
 * del acceso a la base de datos, facilitando la escalabilidad y las pruebas.
 */
interface UserRepositoryInterface
{
    /**
     * Obtiene todos los usuarios registrados.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function obtenerTodos();

    /**
     * Crea un nuevo usuario con los datos proporcionados.
     *
     * @param  array<string, mixed>  $data
     */
    public function crear(array $data): User;

    /**
     * Recupera un usuario específico por su ID.
     */
    public function obtenerPorId(int $id): ?User;

    /**
     * Actualiza los datos de un usuario existente.
     *
     * @param  array<string, mixed>  $data
     */
    public function actualizar(int $id, array $data): User;

    /**
     * Elimina un usuario del sistema.
     */
    public function eliminar(int $id): void;

    /**
     * Asigna un conjunto de habilidades a un usuario.
     *
     * Vincula las habilidades proporcionadas mediante la relación
     * muchos a muchos definida en el modelo `User`.
     *
     * @param  array<int>  $habilidades
     */
    public function asignarHabilidades(int $usuarioId, array $habilidades): User;
}
