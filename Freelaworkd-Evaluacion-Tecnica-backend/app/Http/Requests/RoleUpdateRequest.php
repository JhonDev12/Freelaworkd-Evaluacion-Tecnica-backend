<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * ==========================================================================
 * RoleUpdateRequest
 * ==========================================================================
 * Valida los datos enviados al actualizar un rol existente.
 * Garantiza la unicidad del nombre sin impedir que un rol conserve
 * su propio nombre durante la edición.
 *
 * Principios:
 * - Desacoplamiento de validación (SRP).
 * - Integridad de datos y consistencia RESTful.
 */
class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'nombre')->ignore($this->route('role')),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique'   => 'Ya existe un rol con este nombre.',
            'nombre.max'      => 'El nombre no puede exceder los 50 caracteres.',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.',
        ];
    }
}
