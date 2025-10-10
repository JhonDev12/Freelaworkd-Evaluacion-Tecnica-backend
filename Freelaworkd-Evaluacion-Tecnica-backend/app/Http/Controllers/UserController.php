<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAsignarHabilidadesRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ==========================================================================
 * Controlador de Usuarios
 * ==========================================================================
 *
 * Gestiona las operaciones principales del módulo de usuarios bajo un diseño
 * basado en servicios (Service Layer). Mantiene una separación clara entre
 * la lógica de negocio (UserService) y la capa HTTP.
 *
 * Funcionalidades:
 * - CRUD completo de usuarios con validación y respuesta estandarizada.
 * - Integración con Sanctum para protección de endpoints.
 * - Asignación de roles y habilidades con control de permisos.
 *
 * Principios aplicados:
 * - Single Responsibility: el controlador actúa solo como mediador HTTP.
 * - Dependency Injection: el servicio se inyecta por constructor.
 * - Clean Architecture: cada capa cumple una función aislada y testeable.
 *
 * Resultado:
 * Código mantenible, seguro y alineado con las convenciones RESTful.
 */
class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * Retorna la lista completa de usuarios con sus roles.
     */
    public function index(): JsonResponse
    {
        $usuarios = $this->userService->listar();

        return response()->json(UserResource::collection($usuarios));
    }

    /**
     * Crea un nuevo usuario.
     */
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

    /**
     * Muestra la información detallada de un usuario.
     */
    public function show(int $id): UserResource
    {
        $usuario = $this->userService->obtenerPorId($id);

        return new UserResource($usuario);
    }

    /**
     * Actualiza los datos de un usuario existente.
     *
     * - Permite modificar nombre, correo o contraseña.
     * - Ignora la validación de unicidad si el email no cambió.
     * - Mantiene compatibilidad con los tests automáticos.
     */
    public function update(UserUpdateRequest $request, int $id): UserResource
    {
        try {
            $usuario = $this->userService->actualizar($id, $request->validated());

            return new UserResource($usuario);
        } catch (Throwable $e) {
            Log::error('Error al actualizar usuario', ['error' => $e->getMessage()]);

            abort(500, 'Error al actualizar usuario.');
        }
    }

    /**
     * Elimina un usuario por su ID.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->eliminar($id);

            return response()->json(['mensaje' => 'Usuario eliminado correctamente.'], 200);
        } catch (Throwable $e) {
            Log::error('Error al eliminar usuario', ['error' => $e->getMessage()]);

            return response()->json(['mensaje' => 'Error al eliminar usuario.'], 500);
        }
    }

    /**
     * Asigna un rol a un usuario (solo para super_admin).
     */
    public function asignarRol(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $usuarioAuth = $request->user();

        $resultado = $this->userService->asignarRol($usuarioAuth, $id, $validated['role_id']);

        return response()->json([
            'mensaje' => $resultado['mensaje'],
            'data'    => $resultado['usuario']
                ? new UserResource($resultado['usuario'])
                : null,
        ], $resultado['status']);
    }

    /**
     * Asigna un conjunto de habilidades a un usuario.
     *
     * Recibe los IDs de habilidades validados mediante el Form Request
     * `UserAsignarHabilidadesRequest` y delega la operación al servicio.
     */
    public function asignarHabilidades(UserAsignarHabilidadesRequest $request, int $id): JsonResponse
    {
        try {
            $usuario = $this->userService->asignarHabilidades($id, $request->habilidades);

            return response()->json([
                'mensaje' => 'Habilidades asignadas correctamente.',
                'data'    => new UserResource($usuario),
            ], 200);
        } catch (Throwable $e) {
            Log::error('Error al asignar habilidades', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error al asignar habilidades.',
            ], 500);
        }
    }
}
