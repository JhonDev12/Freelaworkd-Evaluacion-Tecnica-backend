<?php

namespace App\Http\Requests;

use App\Models\Proyecto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

/**
 * ProyectoStoreRequest
 *
 * Valida la creación de nuevos proyectos, asegurando coherencia
 * y cumplimiento de reglas de negocio:
 * - Evita duplicados por título del mismo usuario.
 * - Controla presupuestos mínimos y realistas.
 * - Impide fechas pasadas o estados inválidos.
 * - Normaliza el flujo inicial del proyecto.
 */
class ProyectoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['required', 'string', 'max:2000'],
            'presupuesto'  => ['nullable', 'numeric', 'min:100'],
            'estado'       => ['nullable', 'in:abierto,en progreso,finalizado'],
            'fecha_inicio' => ['nullable', 'date', 'after_or_equal:today'],
            'fecha_fin'    => ['nullable', 'date', 'after:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required'             => 'El título del proyecto es obligatorio.',
            'titulo.max'                  => 'El título no puede superar los 255 caracteres.',
            'descripcion.required'        => 'La descripción es obligatoria.',
            'presupuesto.min'             => 'El presupuesto mínimo permitido es de $100.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
            'fecha_fin.after'             => 'La fecha de finalización debe ser posterior a la de inicio.',
        ];
    }

    /**
     * Validaciones adicionales basadas en la lógica del negocio.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $usuario = Auth::user();

            if ($usuario && Proyecto::where('titulo', $this->titulo)->where('user_id', $usuario->id)->exists()) {
                $validator->errors()->add(
                    'titulo',
                    'Ya existe un proyecto con este título para tu cuenta.'
                );
            }
            if ($this->filled('fecha_inicio') && $this->filled('fecha_fin')) {
                $dias = now()->parse($this->fecha_inicio)->diffInDays($this->fecha_fin, false);
                if ($dias < 1) {
                    $validator->errors()->add(
                        'fecha_fin',
                        'La fecha de finalización debe ser posterior a la de inicio.'
                    );
                }
            }
            if ($this->filled('presupuesto') && $this->presupuesto < 100) {
                $validator->errors()->add(
                    'presupuesto',
                    'El presupuesto indicado es demasiado bajo para crear un proyecto válido.'
                );
            }
        });
    }
}
