<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProyectoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'presupuesto' => $this->presupuesto,
            'estado' => $this->estado,
            'usuario' => [
                'id' => $this->usuario->id ?? null,
                'nombre' => $this->usuario->nombre_completo ?? null,
            ],
            'creado_en' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
