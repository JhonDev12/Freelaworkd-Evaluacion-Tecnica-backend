<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso JSON que define la representación pública de un rol del sistema.
 *
 * Este recurso permite exponer los datos del modelo `Role` de forma controlada
 * y consistente, evitando dependencias directas con la estructura interna de la base de datos.
 *
 * Principales objetivos:
 * - Estandarizar el formato de respuesta para la API.
 * - Garantizar que solo se expongan campos seguros y relevantes.
 * - Facilitar la mantenibilidad al desacoplar el modelo de la salida JSON.
 */
class RoleResource extends JsonResource
{
    /**
     * Transforma el modelo en una representación serializable para la API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
