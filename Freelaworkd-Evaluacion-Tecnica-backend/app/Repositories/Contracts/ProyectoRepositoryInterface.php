<?php

namespace App\Repositories\Contracts;
/**
 * ==========================================================================
 * Interfaz ProyectoRepositoryInterface
 * ==========================================================================
 * Define el contrato para la capa de persistencia del módulo de proyectos.
 * Establece los métodos esenciales para realizar operaciones CRUD sobre la
 * entidad Proyecto, garantizando una arquitectura desacoplada y coherente
 * entre las capas de dominio y de acceso a datos.
 *
 * Principios aplicados:
 * - Dependency Inversion (SOLID-D): los servicios dependen de interfaces, no de implementaciones.
 * - Repository Pattern: permite cambiar la fuente de datos sin alterar la lógica de negocio.
 *
 * Resultado:
 * Estructura predecible, mantenible y alineada con las buenas prácticas de
 * arquitectura limpia (Clean Architecture / DDD).
 */

interface ProyectoRepositoryInterface
{
    public function obtenerTodos();
    public function crear(array $datos);
    public function obtenerPorId(int $id);
    public function actualizar(int $id, array $datos);
    public function eliminar(int $id): void;
}
