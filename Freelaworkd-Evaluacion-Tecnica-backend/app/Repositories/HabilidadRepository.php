<?php

namespace App\Repositories;

use App\Models\Habilidad;
use App\Repositories\Contracts\HabilidadRepositoryInterface;

/**
 * Implementación del repositorio de Habilidades.
 *
 * Esta clase actúa como capa de acceso a datos, utilizando Eloquent
 * para ejecutar las operaciones CRUD definidas en la interfaz.
 * Su función es mantener la lógica de persistencia desacoplada del servicio,
 * siguiendo el patrón Repository.
 */
class HabilidadRepository implements HabilidadRepositoryInterface
{
    /**
     * Devuelve todas las habilidades registradas.
     */
    public function obtenerTodas()
    {
        return Habilidad::all();
    }

    /**
     * Crea una nueva habilidad con los datos proporcionados.
     */
    public function crear(array $data): Habilidad
    {
        return Habilidad::create($data);
    }

    /**
     * Busca una habilidad por su ID o lanza una excepción si no existe.
     */
    public function obtenerPorId(int $id): ?Habilidad
    {
        return Habilidad::findOrFail($id);
    }

    /**
     * Actualiza una habilidad existente con los datos dados.
     */
    public function actualizar(int $id, array $data): Habilidad
    {
        $habilidad = Habilidad::findOrFail($id);
        $habilidad->update($data);

        return $habilidad;
    }

    /**
     * Elimina una habilidad de forma permanente.
     */
    public function eliminar(int $id): void
    {
        Habilidad::destroy($id);
    }
}
