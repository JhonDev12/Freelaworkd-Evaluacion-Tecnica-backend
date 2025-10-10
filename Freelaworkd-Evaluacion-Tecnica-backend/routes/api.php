<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropuestaController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * ==========================================================================
 * Freelaworkd API Routes
 * ==========================================================================
 *
 * Archivo: routes/api.php
 *
 * - Todas las respuestas del API deben ser JSON.
 * - La autenticación se gestiona con Laravel Sanctum.
 * - Rutas públicas: /api/auth/registro, /api/auth/login
 * - Rutas protegidas: están bajo middleware 'auth:sanctum'
 *
 * Recomendaciones de seguridad:
 * - Limitar mutaciones sensibles (roles) mediante Gates/Policies o un
 *   middleware que valide role:super_admin.
 * - Asegurar CORS + supports_credentials = true y SANCTUM_STATEFUL_DOMAINS
 *   configurado en .env para permitir cookie flow.
 */

/* --------------------------------------------------------------------------
 * Módulo: Autenticación
 * - Public: registro, login
 * - Protegidas: logout, user (devuelve usuario actual)
 * -------------------------------------------------------------------------- */
Route::prefix('auth')->group(function () {

    // Public endpoints
    Route::post('registro', [AuthController::class, 'registro'])
        ->name('auth.registro');

    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login');

    // Protected endpoints: requieren token / sesión Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        // Retorna el usuario autenticado (útil para el SPA)
        Route::get('user', [AuthController::class, 'user'])
            ->name('auth.user');

        // Cierre de sesión (revoca token o limpia cookie)
        Route::post('logout', [AuthController::class, 'logout'])
            ->name('auth.logout');
    });
});

/* --------------------------------------------------------------------------
 * Rutas protegidas por auth:sanctum (API principal)
 * - Aquí colocamos todos los recursos que deben ser accedidos solo por
 *   usuarios autenticados.
 * -------------------------------------------------------------------------- */
Route::middleware('auth:sanctum')->group(function () {

    // Proyectos (CRUD completo) ---------------------------------------------
    Route::apiResource('proyectos', ProyectoController::class)
        ->names([
            'index'   => 'proyectos.index',
            'store'   => 'proyectos.store',
            'show'    => 'proyectos.show',
            'update'  => 'proyectos.update',
            'destroy' => 'proyectos.destroy',
        ]);

    // Propuestas (CRUD completo) --------------------------------------------
    Route::apiResource('propuestas', PropuestaController::class)
        ->names([
            'index'   => 'propuestas.index',
            'store'   => 'propuestas.store',
            'show'    => 'propuestas.show',
            'update'  => 'propuestas.update',
            'destroy' => 'propuestas.destroy',
        ]);

    // Usuarios (CRUD) -------------------------------------------------------
    Route::apiResource('usuarios', UserController::class)
        ->names([
            'index'   => 'usuarios.index',
            'store'   => 'usuarios.store',
            'show'    => 'usuarios.show',
            'update'  => 'usuarios.update',
            'destroy' => 'usuarios.destroy',
        ]);

    // Asignar rol a usuario (solo admins con permiso) -----------------------
    // Nota: aquí deberías validar que el que llama tiene permiso (Gate/Policy
    // o middleware role:super_admin).
    Route::patch('usuarios/{id}/rol', [UserController::class, 'asignarRol'])
        ->name('usuarios.asignarRol');

    // Asignar/Hacer PATCH de habilidades a usuario --------------------------
    Route::patch('usuarios/{id}/habilidades', [UserController::class, 'asignarHabilidades'])
        ->name('usuarios.asignarHabilidades');

    // Roles (CRUD) ---------------------------------------------------------
    // IMPORTANTE: además de auth:sanctum, restringir con Gate/Policy o
    // middleware específico que permita solo a super_admin manipular roles.
    Route::apiResource('roles', RoleController::class)
        ->names([
            'index'   => 'roles.index',
            'store'   => 'roles.store',
            'show'    => 'roles.show',
            'update'  => 'roles.update',
            'destroy' => 'roles.destroy',
        ]);

    // Habilidades (CRUD) ---------------------------------------------------
    Route::apiResource('habilidades', \App\Http\Controllers\HabilidadController::class)
        ->names([
            'index'   => 'habilidades.index',
            'store'   => 'habilidades.store',
            'show'    => 'habilidades.show',
            'update'  => 'habilidades.update',
            'destroy' => 'habilidades.destroy',
        ]);
});

/* --------------------------------------------------------------------------
 * Fallback global
 * - Responde JSON para rutas no definidas (evita HTML).
 * -------------------------------------------------------------------------- */
Route::fallback(function () {
    return response()->json([
        'mensaje' => 'Ruta no encontrada.',
    ], 404);
});
