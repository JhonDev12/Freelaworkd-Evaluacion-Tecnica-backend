<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent que representa una propuesta enviada por un freelancer.
 *
 * Cada propuesta pertenece a un usuario autenticado (autor)
 * y está asociada a un proyecto específico. Este modelo actúa como
 * una entidad de negocio que conecta al freelancer con una oportunidad
 * de trabajo dentro de la plataforma.
 *
 * Responsabilidades:
 * - Definir los atributos asignables de forma masiva.
 * - Establecer las relaciones con los modelos `User` y `Proyecto`.
 * - Proveer una capa de abstracción entre la base de datos y la lógica de aplicación.
 */
class Propuesta extends Model
{
    use HasFactory;

    /**
     * Campos que pueden asignarse de manera masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'proyecto_id',
        'usuario_id',
        'descripcion',
        'presupuesto',
        'tiempo_estimado',
    ];

    /**
     * Relación con el modelo User (autor de la propuesta).
     *
     * Cada propuesta pertenece a un usuario (freelancer) autenticado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el modelo Proyecto.
     *
     * Indica el proyecto al que pertenece la propuesta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

}
