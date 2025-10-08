<?php

namespace App\Repositories;

use App\Models\Propuesta;

class PropuestaRepository
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
