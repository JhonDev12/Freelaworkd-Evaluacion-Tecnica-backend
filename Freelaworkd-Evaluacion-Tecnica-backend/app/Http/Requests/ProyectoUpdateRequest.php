<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Valida los datos al actualizar un nuevo proyecto.
 */
class ProyectoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'nullable|in:abierto,en progreso,finalizado',
        ];
    }
}
