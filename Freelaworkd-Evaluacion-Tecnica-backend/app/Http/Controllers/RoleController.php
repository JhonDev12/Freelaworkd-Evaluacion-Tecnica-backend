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
 * Controlador responsable de la gestión de roles del sistema.
 * Implementa operaciones CRUD siguiendo las convenciones REST y
 * delega la lógica de negocio al servicio correspondiente.
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

            return (new RoleResource($rol))->response()->setStatusCode(201);
        } catch (Throwable $e) {
            // Se registra el error sin exponer detalles internos al cliente
            Log::error('Error al crear rol', ['error' => $e->getMessage()]);

            return response()->json(['mensaje' => 'Error al crear rol.'], 500);
        }
    }

    public function show(int $id): RoleResource
    {
        return new RoleResource($this->roleService->obtenerPorId($id));
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
