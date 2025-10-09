<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los datos al crear una nueva propuesta.
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
            'proyecto_id'    => ['required', 'exists:proyectos,id'],
            'descripcion'    => ['required', 'string', 'max:1000'],
            'presupuesto'    => ['required', 'numeric', 'min:1'],
            'tiempo_estimado'=> ['required', 'integer', 'min:1'],
        ];
    }
}
