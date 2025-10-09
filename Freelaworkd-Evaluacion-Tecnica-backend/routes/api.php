<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\PropuestaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

/**
 * ==========================================================================
 * Freelaworkd API Routes ///////////////////////////////////////////////////
 * ==========================================================================
 *
 * Descripción general:
 * --------------------
 * Este archivo define los endpoints principales expuestos por la API.
 * Implementa una arquitectura RESTful robusta, donde la autenticación
 * se gestiona mediante Laravel Sanctum para controlar el acceso a los
 * recursos protegidos.
 *
 * Principios de diseño:
 * ---------------------
 * - Consistencia → rutas predecibles y versionables.
 * - Seguridad → middleware explícito para control de acceso.
 * - Escalabilidad → estructura modular por dominio funcional.
 * - Uniformidad → todas las respuestas se devuelven en formato JSON.
 *
 * Estructura base:
 * ----------------
 * /api/auth/...       → Módulo de autenticación de usuarios
 * /api/proyectos/...  → Módulo de gestión de proyectos
 * /api/propuestas/... → Módulo de gestión de propuestas
 */

// ==========================================================================
// Módulo: Autenticación de usuarios ///////////////////////////////////////
// ==========================================================================
// Define los endpoints públicos y privados para el ciclo de autenticación.
// Incluye registro, login y cierre de sesión protegido.
Route::prefix('auth')->group(function () {

    // ----------------------------------------------------------------------
    // Endpoints públicos /////////////////////////////////////////////////////
    // ----------------------------------------------------------------------
    Route::post('registro', [AuthController::class, 'registro'])
        ->name('auth.registro');

    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login');

    // ----------------------------------------------------------------------
    // Endpoints protegidos (requieren token Sanctum) ///////////////////////
    // ----------------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Cierre de sesión (revoca el token actual del usuario autenticado)
        Route::post('logout', [AuthController::class, 'logout'])
            ->name('auth.logout');
    });
});

// ==========================================================================
// Módulo: Proyectos /////////////////////////////////////////////////////////
// ==========================================================================
// CRUD protegido por Sanctum. Cada recurso representa un proyecto
// asociado a un usuario autenticado.
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('proyectos', ProyectoController::class)
        ->names([
            'index'   => 'proyectos.index',
            'store'   => 'proyectos.store',
            'show'    => 'proyectos.show',
            'update'  => 'proyectos.update',
            'destroy' => 'proyectos.destroy',
        ]);
});

// ==========================================================================
// Módulo: Propuestas ///////////////////////////////////////////////////////
// ==========================================================================
// CRUD completo para la gestión de propuestas enviadas por freelancers
// a proyectos. Requiere autenticación mediante Sanctum.
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('propuestas', PropuestaController::class)
        ->names([
            'index'   => 'propuestas.index',
            'store'   => 'propuestas.store',
            'show'    => 'propuestas.show',
            'update'  => 'propuestas.update',
            'destroy' => 'propuestas.destroy',
        ]);
});


/**
 * --------------------------------------------------------------------------
 * Rutas protegidas - Módulo de Usuarios ///////////////////////////////////
 * --------------------------------------------------------------------------
 * Agrupa los endpoints del CRUD de usuarios bajo autenticación Sanctum.
 * Solo los usuarios con un token válido pueden acceder a estas rutas.
 * Apunta al controlador UserController, que gestiona las operaciones
 * de listar, crear, consultar, actualizar y eliminar registros.
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('usuarios',UserController::class)
        ->names([
            'index'   => 'usuarios.index',
            'store'   => 'usuarios.store',
            'show'    => 'usuarios.show',
            'update'  => 'usuarios.update',
            'destroy' => 'usuarios.destroy',
        ]);
         // Ruta específica para asignar rol
    Route::patch('usuarios/{id}/rol', [UserController::class, 'asignarRol'])
    ->name('usuarios.asignarRol');
});

/**
 * --------------------------------------------------------------------------
 * Rutas protegidas — Módulo de Roles ///////////////////////////////////////
 * --------------------------------------------------------------------------
 * CRUD RESTful para la gestión de roles del sistema.
 * Solo accesible mediante autenticación Sanctum.
 */
Route::apiResource('roles', RoleController::class)
    ->names([
        'index'   => 'roles.index',
        'store'   => 'roles.store',
        'show'    => 'roles.show',
        'update'  => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);


// ==========================================================================
// Fallback global para rutas no definidas /////////////////////////////////
// ==========================================================================
// Garantiza que las rutas inexistentes respondan con un JSON estructurado,
// evitando respuestas HTML en clientes SPA o móviles.
Route::fallback(function () {
    return response()->json([
        'mensaje' => 'Ruta no encontrada.',
    ], 404);
});
