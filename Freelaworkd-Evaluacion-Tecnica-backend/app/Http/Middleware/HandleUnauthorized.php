<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware global para manejo uniforme de errores de autorización.
 *
 * Intercepta respuestas 401 (no autenticado) y 403 (no autorizado)
 * devolviendo una respuesta JSON estructurada en lugar de redirecciones HTML.
 */
class HandleUnauthorized
{
    /**
     * Maneja las respuestas de autorización no válidas.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

    
        if ($response->getStatusCode() === 401) {
            return response()->json([
                'error' => 'No autenticado. Debes iniciar sesión para continuar.',
                'code' => 401,
            ], 401);
        }

        // Si la respuesta es 403 (sin permisos)
        if ($response->getStatusCode() === 403) {
            return response()->json([
                'error' => 'Acceso denegado. No tienes permisos para esta acción.',
                'code' => 403,
            ], 403);
        }

        return $response;
    }
}
