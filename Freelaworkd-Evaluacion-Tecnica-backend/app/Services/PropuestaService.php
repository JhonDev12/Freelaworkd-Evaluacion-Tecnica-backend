<?php

namespace App\Services;

use App\Repositories\PropuestaRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Servicio de Propuestas
 *
 * Encapsula la lógica de negocio para gestionar propuestas:
 * creación, consulta, actualización y eliminación.
 */
class PropuestaService
{
    public function __construct(private PropuestaRepository $propuestaRepository) {}

    public function listar()
    {
        return $this->propuestaRepository->obtenerTodas();
    }

    public function crear(array $data, int $usuarioId)
    {
        $data['usuario_id'] = $usuarioId;
        return $this->propuestaRepository->crear($data);
    }

    public function obtener(int $id)
    {
        $propuesta = $this->propuestaRepository->obtenerPorId($id);
        if (! $propuesta) {
            throw new ModelNotFoundException('Propuesta no encontrada.');
        }
        return $propuesta;
    }

    public function actualizar(int $id, array $data)
    {
        return $this->propuestaRepository->actualizar($id, $data);
    }

    public function eliminar(int $id): void
    {
        $this->propuestaRepository->eliminar($id);
    }
}
