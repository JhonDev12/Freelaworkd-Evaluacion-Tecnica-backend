<?php

namespace App\Repositories;

use App\Models\Proyecto;
use App\Repositories\Contracts\ProyectoRepositoryInterface;

class ProyectoRepository implements ProyectoRepositoryInterface
{
    public function obtenerTodos()
    {
        return Proyecto::all();
    }

    public function crear(array $datos)
    {
        return Proyecto::create($datos);
    }

    public function obtenerPorId(int $id)
    {
        return Proyecto::find($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($datos);
        return $proyecto;
    }

    public function eliminar(int $id): void
    {
        Proyecto::destroy($id);
    }
}
