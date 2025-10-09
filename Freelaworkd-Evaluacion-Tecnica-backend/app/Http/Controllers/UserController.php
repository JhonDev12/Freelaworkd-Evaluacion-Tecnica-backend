<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ==========================================================================
 * Controlador de Usuarios
 * ==========================================================================
 * 
 * Gestiona el ciclo de vida completo de los usuarios en el sistema.
 * Permite CRUD sobre la tabla `users`, garantizando seguridad mediante
 * autenticación Sanctum y validación estricta por Request classes.
 */
class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(): JsonResponse
    {
        $usuarios = $this->userService->listar();
        return response()->json(UserResource::collection($usuarios));
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $usuario = $this->userService->crear($request->validated());
            return (new UserResource($usuario))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Error al crear usuario', ['error' => $e->getMessage()]);
            return response()->json(['mensaje' => 'Error al crear usuario.'], 500);
        }
    }

    public function show(int $id): UserResource
    {
        $usuario = $this->userService->obtenerPorId($id);
        return new UserResource($usuario);
    }

    public function update(UserUpdateRequest $request, int $id): UserResource
    {
        $usuario = $this->userService->actualizar($id, $request->validated());
        return new UserResource($usuario);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->eliminar($id);
        return response()->json(['mensaje' => 'Usuario eliminado correctamente.'], 204);
    }
    
}
