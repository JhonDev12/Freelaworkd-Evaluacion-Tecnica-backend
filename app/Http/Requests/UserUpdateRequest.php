<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Valida la actualización de un usuario existente.
 *
 * Corrige el problema de validación del correo al ignorar correctamente
 * el usuario actual basado en el parámetro de ruta "usuario".
 *
 * Principios aplicados:
 * - Single Responsibility: valida únicamente actualización.
 * - Fail Fast: evita validaciones redundantes y errores lógicos.
 * - Compatible con tests de la evaluación técnica.
 */
class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Detectar el ID correctamente según el nombre del parámetro de ruta
        $usuarioId = $this->route('usuario') ?? $this->route('id') ?? $this->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($usuarioId),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/',
            ],
            'role_id' => ['nullable', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'Este correo ya está registrado por otro usuario.',
            'email.email'        => 'Debes ingresar un correo válido.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex'     => 'Debe incluir mayúsculas, minúsculas y números.',
            'role_id.exists'     => 'El rol indicado no existe.',
        ];
    }

    /**
     * Validaciones personalizadas adicionales.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $usuarioId = $this->route('usuario') ?? $this->route('id') ?? $this->id;

            // Evitar duplicación de nombre
            if (User::where('name', $this->name)
                ->where('id', '!=', $usuarioId)
                ->exists()) {
                $validator->errors()->add('name', 'Ya existe un usuario con este nombre.');
            }

            // Solo super_admin puede asignar roles altos
            if ($this->filled('role_id') && Auth::check()) {
                $auth = Auth::user();
                if ($auth && $auth->role && $auth->role->nombre !== 'super_admin') {
                    $validator->errors()->add(
                        'role_id',
                        'Solo un super_admin puede modificar roles administrativos.'
                    );
                }
            }
        });
    }
}
