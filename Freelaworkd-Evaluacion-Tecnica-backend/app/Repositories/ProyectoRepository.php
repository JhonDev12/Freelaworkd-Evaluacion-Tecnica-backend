<?php

namespace App\Repositories;

use App\Models\Proyecto;

class ProyectoRepository
{
    public function obtenerTodos()
    {
        return Proyecto::with('usuario')->latest()->get();
    }

    public function crear(array $datos)
    {
        return Proyecto::create($datos);
    }

    public function obtenerPorId(int $id)
    {
        return Proyecto::with('usuario')->find($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($datos);
        return $proyecto;
    }

    public function eliminar(int $id): void
    {
        Proyecto::findOrFail($id)->delete();
    }
}
