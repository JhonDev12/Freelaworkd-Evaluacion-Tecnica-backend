<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

/**
 * Valida la creación de un nuevo usuario.
 * Aplica validaciones de unicidad, seguridad y control de permisos.
 */
class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required',
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
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'Debes ingresar un correo válido.',
            'email.unique'      => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'Debe tener al menos 8 caracteres.',
            'password.regex'    => 'Debe incluir mayúsculas, minúsculas y números.',
            'role_id.exists'    => 'El rol indicado no existe.',
        ];
    }

    /**
     * Validaciones personalizadas posteriores.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (User::where('name', $this->name)->exists()) {
                $validator->errors()->add('name', 'Ya existe un usuario con este nombre.');
            }

            if ($this->filled('role_id') && Auth::check()) {
                $user = Auth::user();

                if ($user && $user->role && $user->role->nombre !== 'super_admin') {
                    $validator->errors()->add(
                        'role_id',
                        'Solo un super_admin puede asignar roles administrativos.'
                    );
                }
            }
        });
    }
}
