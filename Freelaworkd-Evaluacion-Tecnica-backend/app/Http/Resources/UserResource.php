<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * UserResource
 *
 * Formatea la respuesta JSON del modelo User.
 * Incluye relaciones condicionales (rol y habilidades)
 * solo cuando han sido cargadas explícitamente mediante `load()`
 * para evitar sobrecarga innecesaria en las consultas.
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,

            // Relación con el rol (clave estandarizada 'role')
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id'          => $this->role->id,
                    'nombre'      => $this->role->nombre,
                    'descripcion' => $this->role->descripcion,
                ];
            }),

            // Relación con las habilidades
            'habilidades' => $this->whenLoaded('habilidades', function () {
                return $this->habilidades->map(function ($habilidad) {
                    return [
                        'id'     => $habilidad->id,
                        'nombre' => $habilidad->nombre,
                        'nivel'  => $habilidad->nivel,
                    ];
                });
            }),
        ];
    }
}
