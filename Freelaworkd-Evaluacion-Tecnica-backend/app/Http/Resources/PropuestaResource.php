<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Formatea la salida JSON de una propuesta.
 */
class PropuestaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'descripcion'     => $this->descripcion,
            'presupuesto'     => $this->presupuesto,
            'tiempo_estimado' => $this->tiempo_estimado,
            'proyecto'        => $this->proyecto?->titulo ?? 'Proyecto eliminado',
            'usuario'         => [
                'id'   => $this->usuario->id,
                'name' => $this->usuario->name,
                'email'=> $this->usuario->email,
            ],
            'fecha_creacion'  => $this->created_at->toDateTimeString(),
            'fecha_actualizacion' => $this->updated_at->toDateTimeString(),
        ];
    }
}
