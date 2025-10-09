<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Controlador de Roles
 *
 * Gestiona el ciclo de vida completo de los roles del sistema.
 * Aplica validaciones con Form Requests y formatea las respuestas
 * mediante API Resources, manteniendo consistencia y trazabilidad.
 */
class RoleController extends Controller
{
    public function __construct(private RoleService $roleService) {}

    public function index(): JsonResponse
    {
        $roles = $this->roleService->listar();
        return response()->json(RoleResource::collection($roles));
    }

    public function store(RoleStoreRequest $request): JsonResponse
    {
        try {
            $rol = $this->roleService->crear($request->validated());
            return (new RoleResource($rol))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Error al crear rol', ['error' => $e->getMessage()]);
            return response()->json(['mensaje' => 'Error al crear rol.'], 500);
        }
    }

    public function show(int $id): RoleResource
    {
        $rol = $this->roleService->obtenerPorId($id);
        return new RoleResource($rol);
    }

    public function update(RoleUpdateRequest $request, int $id): RoleResource
    {
        $rol = $this->roleService->actualizar($id, $request->validated());
        return new RoleResource($rol);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->roleService->eliminar($id);
        return response()->json(['mensaje' => 'Rol eliminado correctamente.'], 204);
    }
}
