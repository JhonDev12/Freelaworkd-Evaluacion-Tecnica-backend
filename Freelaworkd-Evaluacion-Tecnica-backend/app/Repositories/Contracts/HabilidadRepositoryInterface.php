<?php

namespace App\Repositories\Contracts;

use App\Models\Habilidad;

/**
 * Interfaz que define el contrato del repositorio de Habilidades.
 *
 * Establece las operaciones CRUD que deben implementarse en la capa de
 * persistencia de datos, siguiendo el principio de inversión de dependencias (DIP)
 * del patrón Repository.
 *
 * Beneficios:
 * - Desacopla la lógica de negocio del acceso a la base de datos.
 * - Facilita el mantenimiento, pruebas unitarias y sustitución del origen de datos.
 */
interface HabilidadRepositoryInterface
{
    /**
     * Obtiene todas las habilidades registradas.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Habilidad>
     */
    public function obtenerTodas();

    /**
     * Crea una nueva habilidad con los datos proporcionados.
     *
     * @param  array<string, mixed>  $data
     */
    public function crear(array $data): Habilidad;

    /**
     * Recupera una habilidad específica por su ID.
     */
    public function obtenerPorId(int $id): ?Habilidad;

    /**
     * Actualiza una habilidad existente con nuevos datos.
     *
     * @param  array<string, mixed>  $data
     */
    public function actualizar(int $id, array $data): Habilidad;

    /**
     * Elimina una habilidad de forma permanente.
     */
    public function eliminar(int $id): void;
}
