<?php

namespace App\Repositories;

use App\Models\Proyecto;
use App\Repositories\Contracts\ProyectoRepositoryInterface;

/**
 * Implementación del repositorio de Proyectos.
 *
 * Gestiona las operaciones CRUD sobre el modelo `Proyecto`
 * utilizando Eloquent como capa de persistencia.
 *
 * Este repositorio cumple el contrato definido por
 * `ProyectoRepositoryInterface`, promoviendo el principio de
 * inversión de dependencias (DIP) y el desacoplamiento entre
 * la lógica de negocio y la base de datos.
 */
class ProyectoRepository implements ProyectoRepositoryInterface
{
    /**
     * Obtiene todos los proyectos registrados.
     */
    public function obtenerTodos()
    {
        return Proyecto::all();
    }

    /**
     * Crea un nuevo proyecto con los datos proporcionados.
     */
    public function crear(array $datos)
    {
        return Proyecto::create($datos);
    }

    /**
     * Recupera un proyecto específico por su ID.
     *
     * Retorna null si no existe.
     */
    public function obtenerPorId(int $id)
    {
        return Proyecto::find($id);
    }

    /**
     * Actualiza un proyecto existente.
     */
    public function actualizar(int $id, array $datos)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($datos);

        return $proyecto;
    }

    /**
     * Elimina un proyecto por su ID.
     */
    public function eliminar(int $id): void
    {
        Proyecto::destroy($id);
    }
}
