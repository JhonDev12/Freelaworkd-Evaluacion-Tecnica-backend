<?php

namespace App\Repositories;

use App\Models\Propuesta;
use App\Repositories\Contracts\PropuestaRepositoryInterface;

/**
 * Repositorio de propuestas.
 *
 * Encapsula el acceso a la capa de persistencia del modelo Propuesta.
 * Se encarga de las operaciones CRUD principales y del manejo de relaciones.
 * 
 * Este diseño promueve el desacoplamiento y facilita el testing.
 */
class PropuestaRepository implements PropuestaRepositoryInterface
{
    public function obtenerTodas()
    {
        return Propuesta::with(['usuario', 'proyecto'])->latest()->get();
    }

    public function crear(array $data): Propuesta
    {
        return Propuesta::create($data);
    }

    public function obtenerPorId(int $id): ?Propuesta
    {
        return Propuesta::with(['usuario', 'proyecto'])->find($id);
    }

    public function actualizar(int $id, array $data): Propuesta
    {
        $propuesta = Propuesta::findOrFail($id);
        $propuesta->update($data);
        return $propuesta;
    }

    public function eliminar(int $id): void
    {
        Propuesta::findOrFail($id)->delete();
    }
}
