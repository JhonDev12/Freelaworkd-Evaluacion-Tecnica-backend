<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\PropuestaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas del backend expuestas como API RESTful.
| Protegidas por Sanctum y estructuradas según recursos.
|
*/

// 🔐 Autenticación
Route::prefix('auth')->group(function () {
    Route::post('registro', [AuthController::class, 'registro']);
    Route::post('login', [AuthController::class, 'login']);
});

// 🔒 Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // CRUD de proyectos
    Route::apiResource('proyectos', ProyectoController::class);

    // CRUD de propuestas (relación con proyectos)
    // Route::apiResource('propuestas', PropuestaControlleer::class);

    // Cierre de sesión
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Fallback para rutas inexistentes
Route::fallback(function () {
    return response()->json([
        'mensaje' => 'Ruta no encontrada.',
    ], 404);
});
