<?php

namespace App\Services;

use App\Repositories\ProyectoRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProyectoService
{
    protected $proyectoRepository;

    public function __construct(ProyectoRepository $proyectoRepository)
    {
        $this->proyectoRepository = $proyectoRepository;
    }

    public function obtenerTodos()
    {
        return $this->proyectoRepository->obtenerTodos();
    }

    public function crear(array $datos, int $userId)
    {
        $datos['user_id'] = $userId;
        return $this->proyectoRepository->crear($datos);
    }

    public function obtenerPorId(int $id)
    {
        $proyecto = $this->proyectoRepository->obtenerPorId($id);
        if (!$proyecto) {
            throw new ModelNotFoundException('Proyecto no encontrado.');
        }
        return $proyecto;
    }

    public function actualizar(int $id, array $datos)
    {
        return $this->proyectoRepository->actualizar($id, $datos);
    }

    public function eliminar(int $id): void
    {
        $this->proyectoRepository->eliminar($id);
    }
}
