<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropuestaStoreRequest;
use App\Http\Requests\PropuestaUpdateRequest;
use App\Http\Resources\PropuestaResource;
use App\Services\PropuestaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ==========================================================================
 * CONTROLADOR: PropuestaController
 * ==========================================================================
 *
 * Contexto:
 * ----------
 * Este controlador define el punto de entrada HTTP para el módulo de
 * *propuestas* dentro de la plataforma Freelaworkd. 
 * Está diseñado bajo los principios de **arquitectura limpia** y **separación
 * de responsabilidades**, actuando únicamente como mediador entre la capa
 * de transporte (HTTP) y la capa de negocio (Service Layer).
 *
 * Responsabilidades clave:
 * ------------------------
 * - Exponer una API RESTful protegida con autenticación Sanctum.
 * - Validar las solicitudes a través de *Form Requests* dedicados.
 * - Delegar la lógica empresarial al servicio `PropuestaService`.
 * - Retornar respuestas consistentes mediante `PropuestaResource`.
 * - Capturar y registrar errores de ejecución sin filtrar excepciones
 *   crudas al cliente.
 *
 * Principios aplicados:
 * ---------------------
 *  *Single Responsibility* → cada método gestiona una sola acción REST.  
 *  *Dependency Injection* → el servicio se inyecta explícitamente en el constructor.  
 *  *Consistency First* → las respuestas mantienen el mismo formato y estructura JSON.  
 *  *Fail Fast* → validaciones previas antes de interactuar con la capa de datos.  
 *  *Observabilidad* → registro estructurado de errores críticos con contexto útil.  
 *
 * Formato de respuesta estándar:
 * ------------------------------
 * {
 *   "mensaje": "string",
 *   "data": { ... },       // opcional
 *   "status": int          // opcional
 * }
 */
class PropuestaController extends Controller
{
    
    public function __construct(private PropuestaService $propuestaService) {}

   
    public function index(): JsonResponse
    {
        $propuestas = $this->propuestaService->listar();

        return response()->json(
            PropuestaResource::collection($propuestas),
            200
        );
    }

   
    public function store(PropuestaStoreRequest $request): JsonResponse
    {
        try {
            $propuesta = $this->propuestaService->crear(
                $request->validated(),
                $request->user()->id
            );

            return (new PropuestaResource($propuesta))
                ->response()
                ->setStatusCode(201);

        } catch (Throwable $e) {
            Log::error('Error al crear propuesta', [
                'error' => $e->getMessage(),
                'usuario' => $request->user()->id ?? null,
            ]);

            return response()->json([
                'mensaje' => 'Error interno al crear la propuesta.',
            ], 500);
        }
    }

   
    public function show(int $id): PropuestaResource
    {
        $propuesta = $this->propuestaService->obtener($id);
        return new PropuestaResource($propuesta);
    }

    
    public function update(PropuestaUpdateRequest $request, int $id): PropuestaResource
    {
        $propuesta = $this->propuestaService->actualizar(
            $id,
            $request->validated()
        );

        return new PropuestaResource($propuesta);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->propuestaService->eliminar($id);

        Log::info('Propuesta eliminada correctamente', ['id' => $id]);

        return response()->json([
            'mensaje' => 'Propuesta eliminada correctamente.',
        ], 204);
    }
}
