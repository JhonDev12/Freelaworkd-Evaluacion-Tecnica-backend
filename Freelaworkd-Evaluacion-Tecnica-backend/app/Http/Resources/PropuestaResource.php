<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso: PropuestaResource
 * --------------------------
 * Estructura uniforme de salida para el modelo Propuesta.
 * Incluye relaciones de usuario y proyecto para integraciones SPA.
 */
class PropuestaResource extends JsonResource
{
    /**
     * Transforma el recurso en un array para respuesta JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'descripcion'     => $this->descripcion,
            'presupuesto'     => $this->presupuesto,
            'tiempo_estimado' => $this->tiempo_estimado,
            'created_at'      => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'      => $this->updated_at?->format('Y-m-d H:i:s'),

            // --- Relaciones ---
            'proyecto' => $this->whenLoaded('proyecto', function () {
                return [
                    'id'     => $this->proyecto->id,
                    'titulo' => $this->proyecto->titulo,
                ];
            }),

            'usuario' => $this->whenLoaded('usuario', function () {
                return [
                    'id'   => $this->usuario->id,
                    'name' => $this->usuario->name,
                    'email' => $this->usuario->email,
                ];
            }),
        ];
    }
}
