<?php

namespace App\Http\Controllers;

use App\Http\Requests\HabilidadStoreRequest;
use App\Http\Requests\HabilidadUpdateRequest;
use App\Http\Resources\HabilidadResource;
use App\Services\HabilidadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Controlador RESTful del módulo de Habilidades.
 *
 * Gestiona las operaciones CRUD del dominio, aplicando los principios de arquitectura limpia:
 * - La capa de presentación (controlador) permanece libre de lógica de negocio.
 * - La lógica se delega al servicio, que implementa validaciones y reglas del dominio.
 * - Las respuestas se normalizan mediante API Resources para mantener consistencia en toda la API.
 *
 * Seguridad:
 * - Todas las rutas asociadas están protegidas con autenticación Sanctum.
 * - Los datos de entrada se validan mediante Form Requests, garantizando integridad y sanitización.
 *
 * Este diseño favorece la mantenibilidad, trazabilidad y separación de responsabilidades (SRP).
 */
class HabilidadController extends Controller
{
    public function __construct(private HabilidadService $habilidadService) {}

    public function index(): JsonResponse
    {
        $habilidades = $this->habilidadService->listar();

        return response()->json(HabilidadResource::collection($habilidades));
    }

    public function store(HabilidadStoreRequest $request): JsonResponse
    {
        try {
            $habilidad = $this->habilidadService->crear($request->validated());

            return (new HabilidadResource($habilidad))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Error al crear habilidad', ['error' => $e->getMessage()]);

            return response()->json(['mensaje' => 'Error interno al crear la habilidad.'], 500);
        }
    }

    public function show(int $id): HabilidadResource
    {
        return new HabilidadResource($this->habilidadService->obtenerPorId($id));
    }

    public function update(HabilidadUpdateRequest $request, int $id): HabilidadResource
    {
        $habilidad = $this->habilidadService->actualizar($id, $request->validated());

        return new HabilidadResource($habilidad);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->habilidadService->eliminar($id);

        return response()->json(['mensaje' => 'Habilidad eliminada correctamente.'], 204);
    }
}
