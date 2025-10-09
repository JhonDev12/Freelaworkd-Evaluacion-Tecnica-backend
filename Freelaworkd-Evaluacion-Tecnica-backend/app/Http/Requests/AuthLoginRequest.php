<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * AuthLoginRequest
 *
 * Valida las credenciales de inicio de sesión antes de procesarlas.
 * Asegura que los datos enviados sean coherentes y cumplan las reglas básicas
 * de formato y longitud, previniendo solicitudes mal formadas o incompletas.
 *
 * Validaciones principales:
 * - `email`: requerido, con formato válido y longitud máxima.
 * - `password`: requerido, con longitud mínima de seguridad.
 */
class AuthLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email|max:150',
            'password' => 'required|string|min:8|max:64',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Debe ingresar un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
