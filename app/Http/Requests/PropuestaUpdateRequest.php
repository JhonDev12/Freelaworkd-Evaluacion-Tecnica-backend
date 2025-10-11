<?php

namespace App\Http\Requests;

use App\Models\Proyecto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

/**
 * PropuestaUpdateRequest
 *
 * Valida la actualización de propuestas, garantizando coherencia
 * y reglas de negocio sólidas:
 * - No se permiten fechas pasadas.
 * - El tiempo estimado debe ser realista.
 * - Evita modificaciones sobre proyectos propios.
 * - Valida coherencia entre tiempo estimado y fecha de entrega.
 */
class PropuestaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descripcion'     => 'sometimes|required|string|max:255',
            'presupuesto'     => 'sometimes|required|numeric|min:1',
            'tiempo_estimado' => 'sometimes|required|integer|min:1',
            'proyecto_id'     => 'sometimes|exists:proyectos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_entrega.after' => 'La fecha de entrega debe ser una fecha futura.',
            'tiempo_estimado.max' => 'El tiempo estimado no puede superar un año.',
        ];
    }

    /**
     * Validaciones adicionales basadas en lógica de negocio.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // 🔹 Validar coherencia entre tiempo estimado y fecha de entrega
            if ($this->filled('fecha_entrega') && $this->filled('tiempo_estimado')) {
                $dias = now()->diffInDays($this->fecha_entrega, false);
                if ($dias < $this->tiempo_estimado) {
                    $validator->errors()->add(
                        'fecha_entrega',
                        'La fecha de entrega no coincide con el tiempo estimado proporcionado.'
                    );
                }
            }

            // 🔹 Validar que el usuario autenticado no modifique propuestas de sus propios proyectos
            $propuesta = $this->route('propuesta'); // Laravel resolverá el modelo por route binding
            if ($propuesta && $propuesta->proyecto) {
                $proyecto = Proyecto::find($propuesta->proyecto_id);
                if ($proyecto && $proyecto->user_id === Auth::id()) {
                    $validator->errors()->add(
                        'proyecto_id',
                        'No puede modificar propuestas asociadas a sus propios proyectos.'
                    );
                }
            }
        });
    }
}
