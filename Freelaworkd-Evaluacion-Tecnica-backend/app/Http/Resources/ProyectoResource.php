<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso de transformación para el modelo Proyecto.
 *
 * Define la estructura del JSON expuesto por la API
 * y controla qué relaciones se incluyen según la carga previa.
 *
 * El uso de whenLoaded() evita consultas adicionales,
 * permitiendo respuestas eficientes y adaptables según el contexto.
 */
class ProyectoResource extends JsonResource
{
    /**
     * Transforma el proyecto en una representación JSON.
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'titulo'      => $this->titulo,
            'descripcion' => $this->descripcion,
            'presupuesto' => $this->presupuesto,
            'estado'      => $this->estado,

            // Relación usuario (solo si fue cargada)
            'usuario' => $this->whenLoaded('usuario', fn () => [
                'id'     => $this->usuario->id,
                'nombre' => $this->usuario->nombre_completo ?? $this->usuario->name,
            ]),

            // Relación equipo y sus miembros
            'equipo' => $this->whenLoaded('equipo', fn () => [
                'id'       => $this->equipo->id,
                'nombre'   => $this->equipo->nombre,
                'miembros' => $this->equipo->miembros->map(fn ($m) => [
                    'id'     => $m->id,
                    'nombre' => $m->nombre,
                ]),
            ]),

            // Relación propuestas del proyecto
            'propuestas' => $this->whenLoaded('propuestas', fn () => $this->propuestas->map(fn ($p) => [
                'id'          => $p->id,
                'descripcion' => $p->descripcion,
                'estado'      => $p->estado,
                'monto'       => $p->monto,
            ])
            ),

            'creado_en' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
