<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoStoreRequest;
use App\Http\Requests\ProyectoUpdateRequest;
use App\Http\Resources\ProyectoResource;
use App\Services\ProyectoService;
use Illuminate\Http\JsonResponse;

/**
 * ==========================================================================
 * Controlador de Proyectos
 * ==========================================================================
 * Gestiona las operaciones CRUD del módulo de proyectos siguiendo el patrón
 * Service-Controller. Cada acción delega la lógica de negocio al 
 * ProyectoService, asegurando una arquitectura limpia y mantenible.
 *
 * Características:
 * - Validaciones centralizadas mediante Form Requests.
 * - Respuestas estandarizadas con API Resources.
 * - Integración con autenticación Sanctum para asociar proyectos al usuario.
 *
 * Principios aplicados:
 * - Separation of Concerns: el controlador solo coordina el flujo HTTP.
 * - Dependency Injection: el servicio se inyecta automáticamente.
 * - RESTful design: endpoints consistentes y predecibles.
 *
 * Resultado:
 * Controlador liviano, seguro y alineado con las mejores prácticas de Laravel.
 */
class ProyectoController extends Controller
{
    public function __construct(private ProyectoService $proyectoService) {}

    public function index()
    {
        $proyectos = $this->proyectoService->obtenerTodos();
        return ProyectoResource::collection($proyectos);
    }

    public function store(ProyectoStoreRequest $request): JsonResponse
    {
        $proyecto = $this->proyectoService->crear(
            $request->validated(),
            $request->user()->id
        );

        return (new ProyectoResource($proyecto))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id)
    {
        $proyecto = $this->proyectoService->obtenerPorId($id);
        return new ProyectoResource($proyecto);
    }

    public function update(ProyectoUpdateRequest $request, int $id)
    {
        $proyecto = $this->proyectoService->actualizar(
            $id,
            $request->validated()
        );

        return new ProyectoResource($proyecto);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->proyectoService->eliminar($id);

        return response()->json(
            ['mensaje' => 'Proyecto eliminado correctamente.'],
            204
        );
    }
}
