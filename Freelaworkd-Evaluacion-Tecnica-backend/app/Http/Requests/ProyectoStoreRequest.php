<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Valida los datos al crear un nuevo proyecto.
 */
class ProyectoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'nullable|in:abierto,en progreso,finalizado',
        ];
    }
}
