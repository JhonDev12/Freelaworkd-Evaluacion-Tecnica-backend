<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

/**
 * Valida la actualización de habilidades existentes.
 *
 * Asegura:
 * - Que el nombre actualizado siga siendo único.
 * - Que no se dupliquen variantes parecidas.
 * - Que se mantenga la consistencia en el catálogo de habilidades.
 */
class HabilidadUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'sometimes',
                'string',
                'max:100',
                'unique:habilidades,nombre,'.$this->route('id'),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'nivel'       => ['nullable', 'in:básico,intermedio,avanzado'],
        ];
    }

    /**
     * Validaciones adicionales:
     * - Previene exceso de habilidades con nombres similares.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->filled('nombre')) {
                $nombre = Str::lower($this->input('nombre'));

                $similares = \App\Models\Habilidad::query()
                    ->whereRaw('LOWER(nombre) LIKE ?', ["%{$nombre}%"])
                    ->where('id', '!=', $this->route('id'))
                    ->count();

                if ($similares >= 3) {
                    $validator->errors()->add(
                        'nombre',
                        'El nombre de habilidad es demasiado similar a otros ya existentes. Ajusta el nombre para diferenciarla.'
                    );
                }
            }
        });
    }
}
