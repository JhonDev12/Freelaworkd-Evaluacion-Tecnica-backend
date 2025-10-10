<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Controlador de Autenticación (AuthController)
 *
 * Normalizaciones:
 * - El método login devuelve { mensaje, data: { user, token } } (esperado por los tests).
 * - registro() mantiene { mensaje, data } como ya funciona.
 * - user() devuelve { user } y está protegido por auth:sanctum.
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
        } catch (Throwable $e) {
            Log::error('Error al registrar usuario', ['error' => $e->getMessage()]);

            return response()->json([
                'mensaje' => 'Error interno al registrar el usuario.',
            ], 500);
        }
    }


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
            return response()->json([ 'mensaje' => 'Error interno al procesar la solicitud de inicio de sesión.' ], 500);
        }
    }


    /**
     * Logout protegido (revoca token o limpia cookie)
     */
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

    /**
     * Devuelve el usuario autenticado.
     * Nota: esta ruta debe estar protegida con auth:sanctum en routes/api.php
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ], 200);
    }
}
