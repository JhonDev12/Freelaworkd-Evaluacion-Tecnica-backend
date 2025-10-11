<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso JSON que define la representación pública de una habilidad.
 *
 * Su objetivo es transformar el modelo interno `Habilidad` en una respuesta
 * estandarizada y segura para la API. De esta manera se desacopla la
 * estructura interna de la base de datos del formato expuesto al cliente.
 *
 * Ventajas:
 * - Controla qué atributos se exponen (evita fugas de información).
 * - Permite modificar la estructura de salida sin afectar el modelo.
 * - Facilita la consistencia de formato entre endpoints relacionados.
 */
class HabilidadResource extends JsonResource
{
    /**
     * Transforma la instancia del modelo en una estructura JSON serializable.
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
            'nivel'       => $this->nivel,
            'created_at'  => $this->created_at?->toDateTimeString(),
        ];
    }
}
