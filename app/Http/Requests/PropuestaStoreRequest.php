<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * PropuestaStoreRequest
 *
 * Valida la creación de propuestas, garantizando coherencia entre el proyecto,
 * el presupuesto, las fechas y los tiempos estimados.
 *
 * Incluye validaciones personalizadas para reforzar la lógica del negocio:
 * - La fecha de entrega debe ser futura.
 * - El presupuesto no puede exceder el máximo del proyecto (si aplica).
 * - El tiempo estimado debe ser realista según el tipo de proyecto.
 */
class PropuestaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyecto_id'     => ['required', 'exists:proyectos,id'],
            'descripcion'     => ['required', 'string', 'max:1000'],
            'presupuesto'     => ['required', 'numeric', 'min:1'],
            'tiempo_estimado' => ['required', 'integer', 'min:1', 'max:365'],
            'fecha_entrega'   => ['nullable', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'proyecto_id.required' => 'Debe asociar la propuesta a un proyecto válido.',
            'proyecto_id.exists'   => 'El proyecto indicado no existe.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'presupuesto.min'      => 'El presupuesto debe ser mayor a cero.',
            'tiempo_estimado.min'  => 'El tiempo estimado debe ser al menos 1 día.',
            'tiempo_estimado.max'  => 'El tiempo estimado no puede superar 1 año.',
            'fecha_entrega.after'  => 'La fecha de entrega debe ser una fecha futura.',
        ];
    }

    /**
     * Validaciones después de aplicar las reglas base.
     * Aquí se pueden hacer verificaciones de negocio cruzadas.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Validar coherencia entre tiempo_estimado y fecha_entrega
            if ($this->filled('fecha_entrega')) {
                $dias = now()->diffInDays($this->fecha_entrega, false);
                if ($dias < $this->tiempo_estimado) {
                    $validator->errors()->add(
                        'fecha_entrega',
                        'La fecha de entrega no coincide con el tiempo estimado; revise los plazos.'
                    );
                }
            }

            //  Validar que el presupuesto no exceda el máximo del proyecto
            $proyecto = \App\Models\Proyecto::find($this->proyecto_id);
            if ($proyecto && isset($proyecto->presupuesto_max) && $this->presupuesto > $proyecto->presupuesto_max) {
                $validator->errors()->add(
                    'presupuesto',
                    'El presupuesto propuesto excede el máximo permitido para este proyecto.'
                );
            }
        });
    }
}
