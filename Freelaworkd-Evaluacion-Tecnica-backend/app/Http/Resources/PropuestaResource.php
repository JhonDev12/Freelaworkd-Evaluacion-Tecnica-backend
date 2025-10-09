<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso JSON responsable de transformar la entidad `Propuesta`
 * en una representación clara y segura para el cliente.
 *
 * Su propósito es desacoplar el modelo interno de la base de datos
 * de la estructura expuesta públicamente, garantizando consistencia
 * en el formato de respuesta de la API.
 *
 * Consideraciones:
 * - Incluye relaciones relevantes (usuario, proyecto) en formato resumido.
 * - Evita exponer datos sensibles del usuario.
 * - Maneja relaciones eliminadas con valores por defecto.
 */
class PropuestaResource extends JsonResource
{
    /**
     * Transforma la instancia del modelo en una estructura JSON serializable.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'descripcion'     => $this->descripcion,
            'presupuesto'     => $this->presupuesto,
            'tiempo_estimado' => $this->tiempo_estimado,

            // Muestra el título del proyecto o indica si fue eliminado
            'proyecto' => $this->proyecto?->titulo ?? 'Proyecto eliminado',
            'usuario'  => [
                'id'    => $this->usuario->id,
                'name'  => $this->usuario->name,
                'email' => $this->usuario->email,
            ],

            'fecha_creacion'      => $this->created_at?->toDateTimeString(),
            'fecha_actualizacion' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
