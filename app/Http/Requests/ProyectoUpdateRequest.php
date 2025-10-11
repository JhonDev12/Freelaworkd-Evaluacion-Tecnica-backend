<?php

namespace App\Http\Requests;

use App\Models\Proyecto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

/**
 * ProyectoUpdateRequest
 *
 * Valida la actualización de un proyecto existente, aplicando reglas
 * avanzadas para mantener la coherencia e integridad del sistema:
 * - Evita títulos duplicados por usuario.
 * - Verifica coherencia entre fechas.
 * - Controla presupuestos mínimos.
 * - Impide cerrar proyectos sin presupuesto o descripción.
 */
class ProyectoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'       => ['sometimes', 'string', 'max:255'],
            'descripcion'  => ['sometimes', 'string', 'max:2000'],
            'presupuesto'  => ['sometimes', 'numeric', 'min:100'],
            'estado'       => ['sometimes', 'in:abierto,en progreso,finalizado'],
            'fecha_inicio' => ['nullable', 'date', 'after_or_equal:today'],
            'fecha_fin'    => ['nullable', 'date', 'after:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'presupuesto.min'             => 'El presupuesto mínimo permitido es de $100.',
            'estado.in'                   => 'El estado debe ser: abierto, en progreso o finalizado.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
            'fecha_fin.after'             => 'La fecha de finalización debe ser posterior a la de inicio.',
        ];
    }

    /**
     * Validaciones adicionales según reglas de negocio.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            $usuario = Auth::user();

            // Evitar duplicado de título en proyectos del mismo usuario
            if ($this->filled('titulo') && $usuario) {
                $proyectoExistente = Proyecto::where('titulo', $this->titulo)
                    ->where('user_id', $usuario->id)
                    ->where('id', '!=', $this->route('proyecto'))
                    ->exists();

                if ($proyectoExistente) {
                    $validator->errors()->add(
                        'titulo',
                        'Ya tienes otro proyecto con este mismo título.'
                    );
                }
            }

            // Validar coherencia entre fechas
            if ($this->filled('fecha_inicio') && $this->filled('fecha_fin')) {
                $dias = now()->parse($this->fecha_inicio)->diffInDays($this->fecha_fin, false);
                if ($dias < 1) {
                    $validator->errors()->add(
                        'fecha_fin',
                        'La fecha de finalización debe ser posterior a la de inicio.'
                    );
                }
            }

            //  Impedir marcar como "finalizado" si falta información crítica
            if ($this->estado === 'finalizado') {
                if (empty($this->descripcion) || empty($this->presupuesto)) {
                    $validator->errors()->add(
                        'estado',
                        'No se puede marcar como finalizado un proyecto sin descripción ni presupuesto definido.'
                    );
                }
            }
        });
    }
}
