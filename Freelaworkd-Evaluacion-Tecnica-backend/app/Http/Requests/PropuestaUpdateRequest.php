<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los datos al actualizar una propuesta existente.
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
            'descripcion'     => ['sometimes', 'string', 'max:1000'],
            'presupuesto'     => ['sometimes', 'numeric', 'min:1'],
            'tiempo_estimado' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
