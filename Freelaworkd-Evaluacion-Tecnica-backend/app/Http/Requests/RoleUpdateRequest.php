<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * RoleUpdateRequest
 *
 * Valida los datos al actualizar un rol existente.
 * Incluye reglas de negocio adicionales para garantizar la
 * integridad de los nombres de roles dentro del sistema.
 */
class RoleUpdateRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     *
     * La verificación de permisos (por ejemplo, solo `super_admin`)
     * se maneja a nivel de controlador.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                'unique:roles,nombre,'.$this->route('id'),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
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
