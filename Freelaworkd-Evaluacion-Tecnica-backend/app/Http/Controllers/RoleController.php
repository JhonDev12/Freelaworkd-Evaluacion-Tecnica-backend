<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ==========================================================================
 * Controlador de Roles
 * ==========================================================================
 * Gestiona las operaciones CRUD del módulo de roles.
 * Aplica principios de arquitectura limpia: separación de responsabilidades,
 * manejo estructurado de errores y respuestas consistentes con JSON API.
 *
 * Patrón aplicado:
 * - Service Layer (RoleService)
 * - Resource Pattern (RoleResource)
 */
class RoleController extends Controller
{
    public function __construct(private RoleService $roleService) {}

    /**
     * Listar todos los roles disponibles.
     */
    public function index(): JsonResponse
    {
        $roles = $this->roleService->listar();

        return response()->json(RoleResource::collection($roles));
    }

    /**
     * Crear un nuevo rol.
     */
    public function store(RoleStoreRequest $request): JsonResponse
    {
        try {
            $rol = $this->roleService->crear($request->validated());

            return (new RoleResource($rol))
                ->additional(['mensaje' => 'Rol creado correctamente.'])
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Error al crear rol', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error al crear rol.'
            ], 500);
        }
    }

    /**
     * Mostrar un rol por ID.
     */
    public function show(int $id): RoleResource
    {
        return new RoleResource($this->roleService->obtenerPorId($id));
    }

    /**
     * Actualizar un rol existente.
     */
    public function update(RoleUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $rol = $this->roleService->actualizar($id, $request->validated());

            return (new RoleResource($rol))
                ->additional(['mensaje' => 'Rol actualizado correctamente.'])
                ->response()
                ->setStatusCode(200);
        } catch (Throwable $e) {
            Log::error('Error al actualizar rol', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error al actualizar rol.'
            ], 500);
        }
    }

    /**
     * Eliminar un rol.
     *
     * Devuelve 204 (No Content) según las convenciones RESTful.
     */
    public function destroy(int $id): Response|JsonResponse
    {
        try {
            $this->roleService->eliminar($id);

            // ✅ Corrige el warning de tipo y pasa los tests (204 sin cuerpo)
            return response()->noContent();
        } catch (Throwable $e) {
            Log::error('Error al eliminar rol', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error al eliminar rol.'
            ], 500);
        }
    }
}
