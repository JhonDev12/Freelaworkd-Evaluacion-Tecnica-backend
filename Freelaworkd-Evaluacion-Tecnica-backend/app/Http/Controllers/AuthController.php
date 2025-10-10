<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ==========================================================================
 * Controlador de Autenticación (AuthController)
 * ==========================================================================
 *
 * Este controlador orquesta el flujo completo de autenticación de usuarios:
 * registro, inicio de sesión y cierre de sesión seguro mediante tokens
 * personales generados con Laravel Sanctum.
 *
 * ➤ Patrón aplicado: Controller → Service
 *    - El controlador solo coordina solicitudes y respuestas HTTP.
 *    - La lógica de negocio se delega a la capa de servicios (AuthService).
 *
 * ➤ Estilo de respuesta:
 *    - Todas las respuestas se entregan en formato JSON consistente.
 *    - Se incluyen mensajes claros y estados HTTP apropiados.
 *
 * ➤ Errores y observabilidad:
 *    - Captura y logueo de excepciones con contexto.
 *    - Mensajes de error controlados para clientes de API (no técnicos).
 */
class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function registro(AuthRegisterRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->register($request->validated());

            return response()->json([
                'mensaje' => 'Usuario registrado correctamente.',
                'data'    => $data,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error al registrar usuario', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error interno al registrar el usuario.',
            ], 500);
        }
    }

    /**
     * Inicia sesión y genera un token de acceso.
     *
     * - Valida las credenciales mediante AuthService.
     * - Si son correctas, devuelve un token junto con los datos del usuario.
     * - Si las credenciales son inválidas, devuelve un error 401.
     * - Si ocurre un error interno, registra el evento en el log.
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->login($request->validated());

            return response()->json([
                'mensaje' => 'Inicio de sesión exitoso.',
                'data'    => $data,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'mensaje' => $e->getMessage() ?: 'Credenciales incorrectas.',
                'errores' => $e->errors(),
            ], 401);
        } catch (\Throwable $e) {
            Log::error('Error al iniciar sesión', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error interno al procesar la solicitud de inicio de sesión.',
            ], 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'mensaje' => 'Su sesión ha sido cerrada exitosamente.',
                'status'  => 200,
            ], 200);

        } catch (Throwable $e) {
            Log::error('Error al cerrar sesión', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error al cerrar sesión.',
                'status'  => 500,
            ], 500);
        }
    }
}
