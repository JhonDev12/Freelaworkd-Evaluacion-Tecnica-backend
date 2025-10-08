<?php

namespace App\Services;

use App\Repositories\Contracts\PropuestaRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PropuestaService
{
    public function __construct(private PropuestaRepositoryInterface $repository) {}

    public function listar()
    {
        return $this->repository->obtenerTodas();
    }

    public function crear(array $data, int $userId)
    {
        $data['usuario_id'] = $userId;
        return $this->repository->crear($data);
    }

    public function obtener(int $id)
    {
        $propuesta = $this->repository->obtenerPorId($id);
        if (!$propuesta) {
            throw new ModelNotFoundException('Propuesta no encontrada.');
        }
        return $propuesta;
    }

    public function actualizar(int $id, array $data)
    {
        return $this->repository->actualizar($id, $data);
    }

    public function eliminar(int $id): void
    {
        $this->repository->eliminar($id);
    }
}
