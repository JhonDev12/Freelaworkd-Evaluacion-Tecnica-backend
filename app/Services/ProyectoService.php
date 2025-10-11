<?php

namespace App\Services;

use App\Models\Proyecto;
use App\Repositories\ProyectoRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio de gestión de proyectos.
 *
 * Encapsula la lógica de negocio relacionada con los proyectos,
 * separando las operaciones del controlador y delegando el acceso
 * a datos al repositorio.
 *
 * Responsabilidades:
 * - Gestionar el ciclo de vida de los proyectos (CRUD).
 * - Validar la existencia de registros antes de operaciones críticas.
 * - Asociar automáticamente el proyecto al usuario autenticado.
 * - Filtrar resultados según permisos y propiedad.
 */
class ProyectoService
{
    protected $proyectoRepository;

    public function __construct(ProyectoRepository $proyectoRepository)
    {
        $this->proyectoRepository = $proyectoRepository;
    }

    /**
     * Obtiene los proyectos accesibles al usuario autenticado.
     */
    public function obtenerTodos()
    {
        $usuario = Auth::user();

        // Admin y Superadmin ven todo
        if ($usuario && in_array($usuario->role?->nombre, ['admin', 'superadmin'])) {
            return $this->proyectoRepository->obtenerTodos();
        }

        // Los demás solo los suyos
        return Proyecto::where('user_id', $usuario->id)->get();
    }

    /**
     * Crea un nuevo proyecto asociado al usuario autenticado.
     */
    public function crear(array $datos, int $userId)
    {
        $datos['user_id'] = $userId;
        return $this->proyectoRepository->crear($datos);
    }

    public function obtenerPorId(int $id)
    {
        $proyecto = $this->proyectoRepository->obtenerPorId($id);

        if (! $proyecto) {
            throw new ModelNotFoundException('Proyecto no encontrado.');
        }

        $usuario = Auth::user();
        if (!in_array($usuario->role?->nombre, ['admin', 'superadmin']) &&
            $proyecto->user_id !== $usuario->id) {
            throw new ModelNotFoundException('No autorizado para ver este proyecto.');
        }

        return $proyecto;
    }

    public function actualizar(int $id, array $datos)
    {
        $proyecto = $this->proyectoRepository->obtenerPorId($id);

        if (! $proyecto) {
            throw new ModelNotFoundException('Proyecto no encontrado.');
        }

        $usuario = Auth::user();
        if (!in_array($usuario->role?->nombre, ['admin', 'superadmin']) &&
            $proyecto->user_id !== $usuario->id) {
            throw new ModelNotFoundException('No autorizado para actualizar este proyecto.');
        }

        return $this->proyectoRepository->actualizar($id, $datos);
    }

    public function eliminar(int $id): void
    {
        $proyecto = $this->proyectoRepository->obtenerPorId($id);

        if (! $proyecto) {
            throw new ModelNotFoundException('Proyecto no encontrado.');
        }

        $usuario = Auth::user();
        if (!in_array($usuario->role?->nombre, ['admin', 'superadmin']) &&
            $proyecto->user_id !== $usuario->id) {
            throw new ModelNotFoundException('No autorizado para eliminar este proyecto.');
        }

        $this->proyectoRepository->eliminar($id);
    }
}
