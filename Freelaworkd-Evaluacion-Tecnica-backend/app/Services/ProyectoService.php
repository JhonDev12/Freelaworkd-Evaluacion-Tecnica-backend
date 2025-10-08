<?php

namespace App\Services;

use App\Repositories\ProyectoRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
 *
 * Esta capa permite mantener un código más limpio, testable
 * y desacoplado del framework, siguiendo el principio de 
 * separación de responsabilidades (SoC) y la arquitectura en capas.
 */

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
        $datos['usuario_id'] = $userId;
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
