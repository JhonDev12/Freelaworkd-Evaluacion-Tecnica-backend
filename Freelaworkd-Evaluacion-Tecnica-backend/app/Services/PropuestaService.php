<?php

namespace App\Services;

use App\Models\Propuesta;
use App\Repositories\Contracts\PropuestaRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio del dominio Propuesta.
 *
 * Coordina la lógica de negocio asociada a las propuestas enviadas
 * por los usuarios, delegando la persistencia al repositorio.
 *
 * También filtra y valida el acceso a las propuestas
 * según el rol o propiedad del usuario autenticado.
 */
class PropuestaService
{
    public function __construct(private PropuestaRepositoryInterface $repository) {}

    /**
     * Lista las propuestas accesibles según el usuario autenticado.
     */
    public function listar()
    {
        $usuario = Auth::user();

        if ($usuario && in_array($usuario->role?->nombre, ['admin', 'superadmin'])) {
            return $this->repository->obtenerTodas();
        }

        // Si no es admin, obtiene solo las suyas
        return Propuesta::where('usuario_id', $usuario->id)->with(['usuario', 'proyecto'])->get();
    }

    public function crear(array $data, int $userId)
    {
        $data['usuario_id'] = $userId;
        return $this->repository->crear($data);
    }

    public function obtener(int $id)
    {
        $propuesta = $this->repository->obtenerPorId($id);

        if (! $propuesta) {
            throw new ModelNotFoundException('Propuesta no encontrada.');
        }

        $usuario = Auth::user();
        if (!in_array($usuario->role?->nombre, ['admin', 'superadmin']) &&
            $propuesta->usuario_id !== $usuario->id) {
            throw new ModelNotFoundException('No autorizado para ver esta propuesta.');
        }

        return $propuesta;
    }

    public function actualizar(int $id, array $data)
    {
        $propuesta = $this->repository->obtenerPorId($id);
        if (! $propuesta) {
            throw new ModelNotFoundException('Propuesta no encontrada.');
        }

        $usuario = Auth::user();
        if (!in_array($usuario->role?->nombre, ['admin', 'superadmin']) &&
            $propuesta->usuario_id !== $usuario->id) {
            throw new ModelNotFoundException('No autorizado para actualizar esta propuesta.');
        }

        return $this->repository->actualizar($id, $data);
    }

    public function eliminar(int $id): void
    {
        $propuesta = $this->repository->obtenerPorId($id);

        if (! $propuesta) {
            throw new ModelNotFoundException('Propuesta no encontrada.');
        }

        $usuario = Auth::user();
        if (!in_array($usuario->role?->nombre, ['admin', 'superadmin']) &&
            $propuesta->usuario_id !== $usuario->id) {
            throw new ModelNotFoundException('No autorizado para eliminar esta propuesta.');
        }

        $this->repository->eliminar($id);
    }
}
