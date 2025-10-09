<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

/**
 * Valida la actualización de usuarios garantizando:
 * - Correos únicos y válidos.
 * - Contraseñas seguras al modificar.
 * - Control de roles: los usuarios no pueden degradarse ni cambiar su propio rol.
 */
class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string', 'max:255'],
            'email'    => ['sometimes', 'email', 'unique:users,email,'.$this->route('id')],
            'password' => [
                'sometimes',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/',
            ],
            'role_id' => ['sometimes', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email'    => 'Debes ingresar un correo válido.',
            'email.unique'   => 'Este correo ya está registrado por otro usuario.',
            'password.min'   => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'Debe incluir al menos una mayúscula, una minúscula y un número.',
            'role_id.exists' => 'El rol asignado no existe.',
        ];
    }

    /**
     * Validaciones personalizadas de negocio.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $authUser = Auth::user();

            // Evitar que un usuario cambie su propio rol si no es super_admin
            if ($this->filled('role_id') && $authUser) {
                if ($authUser->id === (int) $this->route('id') && $authUser->role->nombre !== 'super_admin') {
                    $validator->errors()->add('role_id', 'No puedes modificar tu propio rol.');
                }

                // Solo un super_admin puede cambiar roles de otros usuarios
                if ($authUser->role->nombre !== 'super_admin') {
                    $validator->errors()->add('role_id', 'Solo un super_admin puede modificar roles.');
                }
            }

            // Validar que el correo nuevo no sea igual al actual
            if ($this->filled('email')) {
                $user = User::find($this->route('id'));
                if ($user && $user->email === $this->email) {
                    $validator->errors()->add('email', 'El correo ingresado es el mismo que ya tienes registrado.');
                }
            }
        });
    }
}
