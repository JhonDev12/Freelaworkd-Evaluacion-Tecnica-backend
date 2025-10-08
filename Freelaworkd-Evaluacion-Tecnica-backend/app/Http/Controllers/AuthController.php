<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

/**
 * Controlador responsable de la autenticación de usuarios.
 * 
 * Expone los endpoints de registro, inicio y cierre de sesión,
 * delegando la lógica de negocio al servicio de autenticación.
 */
class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    /**
     * Registra un nuevo usuario.
     */
    public function registro(AuthRegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());

        return response()->json([
            'mensaje' => 'Usuario registrado correctamente.',
            'data' => $data,
        ], 201);
    }

    /**
     * Inicia sesión y genera un token de acceso.
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return response()->json([
            'mensaje' => 'Inicio de sesión exitoso.',
            'data' => $data,
        ]);
    }

    /**
     * Cierra la sesión actual invalidando el token.
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente.',
        ]);
    }
}
