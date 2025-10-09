<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|required|string|max:50|unique:roles,nombre,' . $this->route('id'),
            'descripcion' => 'nullable|string|max:255',
        ];
    }
}
