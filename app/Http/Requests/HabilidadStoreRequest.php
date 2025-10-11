<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

/**
 * Valida la creación de nuevas habilidades.
 *
 * Garantiza:
 * - Datos consistentes y sanitizados.
 * - Evita duplicados exactos o similares (p. ej., "Laravel" y "Laravel Avanzado").
 * - Limita la cantidad de habilidades parecidas registradas.
 */
class HabilidadStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación base.
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                'unique:habilidades,nombre',
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'nivel'       => ['nullable', 'in:básico,intermedio,avanzado'],
        ];
    }

    /**
     * Validaciones adicionales después de las reglas básicas.
     *
     * - Evita nombres demasiado similares a otros ya existentes.
     * - Limita a un máximo de 3 habilidades con coincidencia parcial.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $nombre    = Str::lower($this->input('nombre'));
            $similares = \App\Models\Habilidad::query()
                ->whereRaw('LOWER(nombre) LIKE ?', ["%{$nombre}%"])
                ->count();

            if ($similares >= 3) {
                $validator->errors()->add(
                    'nombre',
                    'Ya existen demasiadas habilidades similares registradas. Por favor usa un nombre más específico.'
                );
            }
        });
    }
}
