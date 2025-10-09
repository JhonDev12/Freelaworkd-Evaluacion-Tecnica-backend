<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * AuthRegisterRequest
 *
 * Maneja la validación de datos para el registro de nuevos usuarios.
 * Garantiza integridad y consistencia antes de delegar al servicio de autenticación.
 *
 * Validaciones principales:
 * - `name`: requerido, texto y con longitud máxima de 100 caracteres.
 * - `email`: formato válido y único en la tabla `users`.
 * - `password`: mínimo de 8 caracteres y confirmación obligatoria.
 *
 * Beneficios:
 * - Centraliza las reglas de validación fuera del controlador.
 * - Devuelve respuestas automáticas (422) con mensajes personalizados.
 * - Mejora la mantenibilidad y desacopla la capa de presentación de la lógica.
 */
class AuthRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'Este correo electrónico ya está registrado.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
