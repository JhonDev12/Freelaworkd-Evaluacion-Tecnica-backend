<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent que representa un proyecto dentro del sistema Freelaworkd.
 *
 * Un proyecto es creado por un usuario (propietario) y puede recibir
 * múltiples propuestas de freelancers interesados. Este modelo
 * encapsula los datos y relaciones fundamentales del ciclo de vida
 * de un proyecto dentro de la plataforma.
 *
 * Responsabilidades:
 * - Definir los atributos asignables de forma masiva.
 * - Establecer la relación con el usuario propietario.
 * - Proveer la capa de abstracción entre la base de datos y la lógica de negocio.
 */
class Proyecto extends Model
{
    use HasFactory;

    /**
     * Campos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'presupuesto',
        'estado',
        'user_id',
    ];

    /**
     * Relación con el modelo User.
     *
     * Cada proyecto pertenece a un usuario (propietario o cliente)
     * que lo crea dentro del sistema.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
