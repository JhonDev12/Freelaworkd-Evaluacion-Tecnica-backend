<?php

namespace App\Repositories\Contracts;

use App\Models\Propuesta;
/**
 * ==========================================================================
 * Interfaz PropuestaRepositoryInterface
 * ==========================================================================
 * Define el contrato para la capa de persistencia del módulo de propuestas.
 * Asegura que todas las implementaciones del repositorio mantengan una 
 * estructura coherente para las operaciones CRUD, favoreciendo el 
 * desacoplamiento entre la lógica de negocio y el acceso a datos.
 *
 * Principios aplicados:
 * - Dependency Inversion (SOLID-D): la capa de dominio depende de abstracciones.
 * - Repository Pattern: separa la lógica de persistencia del resto del sistema.
 *
 * Resultado:
 * Un código extensible, testable y alineado con los estándares de arquitectura 
 * limpia (Clean Architecture / DDD).
 */

interface PropuestaRepositoryInterface
{
    public function obtenerTodas();
    public function crear(array $data): Propuesta;
    public function obtenerPorId(int $id): ?Propuesta;
    public function actualizar(int $id, array $data): Propuesta;
    public function eliminar(int $id): void;
}
