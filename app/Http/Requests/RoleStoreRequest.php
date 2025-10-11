<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * RoleStoreRequest
 *
 * Define las reglas de validación para la creación de roles en el sistema.
 * Esta clase garantiza que los datos ingresados cumplan con los requisitos
 * antes de llegar a la capa de servicio.
 *
 * Se usa junto con el middleware `auth:sanctum` y la verificación de permisos
 * en el controlador (solo `super_admin` puede crear roles).
 */
class RoleStoreRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para ejecutar esta solicitud.
     *
     * En este caso se devuelve true, ya que la verificación de permisos
     * se maneja directamente en el controlador.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación aplicadas al crear un nuevo rol.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:50|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
        ];
    }

    /**
     * Mensajes personalizados para los errores de validación.
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
