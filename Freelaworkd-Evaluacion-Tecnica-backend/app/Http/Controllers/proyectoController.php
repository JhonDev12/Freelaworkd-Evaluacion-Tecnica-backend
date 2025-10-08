<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoStoreRequest;
use App\Http\Requests\ProyectoUpdateRequest;
use App\Http\Resources\ProyectoResource;
use App\Services\ProyectoService;
use Illuminate\Http\JsonResponse;

/**
 * Controlador de Proyectos.
 * 
 * Gestiona las operaciones CRUD mediante la capa de servicios.
 * Aplica validaciones con Form Requests y formatea las respuestas
 * con API Resources para mantener consistencia en la API.
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
