<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validar la asignación de habilidades a un usuario.
 *
 * Aplica validaciones antes de que el controlador procese la lógica,
 * asegurando que se envíe al menos una habilidad válida y existente.
 *
 * Cumple con el principio de validación temprana (fail fast),
 * evitando operaciones innecesarias en el flujo de negocio.
 */
class UserAsignarHabilidadesRequest extends FormRequest
{
    /**
     * Autoriza el uso de esta request.
     *
     * Puede integrarse con policies en el futuro
     * para verificar permisos específicos del usuario autenticado.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para asignar habilidades.
     *
     * - Debe recibirse un arreglo de IDs de habilidades.
     * - Cada ID debe existir en la tabla `habilidades`.
     */
    public function rules(): array
    {
        return [
            'habilidades'   => ['required', 'array', 'min:1'],
            'habilidades.*' => ['integer', 'exists:habilidades,id'],
        ];
    }

    /**
     * Mensajes personalizados para los errores de validación.
     */
    public function messages(): array
    {
        return [
            'habilidades.required' => 'Debe proporcionar al menos una habilidad.',
            'habilidades.array'    => 'El formato de habilidades debe ser un arreglo.',
            'habilidades.min'      => 'Debe asignar al menos una habilidad.',
            'habilidades.*.exists' => 'Una o más habilidades no existen en el sistema.',
        ];
    }
}
