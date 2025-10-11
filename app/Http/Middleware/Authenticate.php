<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Evita que Laravel intente redirigir a una ruta "login".
     * En APIs REST solo devolvemos JSON 401.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            abort(response()->json([
                'error' => 'No autenticado. Se requiere token válido.',
                'code'  => 401,
            ], 401));
        }

        return null;
    }
}
