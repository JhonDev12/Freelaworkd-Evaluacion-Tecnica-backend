<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent que representa una Habilidad dentro del sistema.
 *
 * Este modelo forma parte del dominio de usuarios, permitiendo asociar
 * múltiples habilidades a diferentes cuentas mediante una relación
 * muchos a muchos.
 *
 * Principales responsabilidades:
 * - Definir los atributos masivos permitidos (`$fillable`).
 * - Establecer las relaciones con otros modelos (en este caso, `User`).
 * - Proveer una capa de abstracción entre la base de datos y la lógica de negocio.
 */
class Habilidad extends Model
{
    use HasFactory;

    /**
     * Campos que pueden asignarse de forma masiva.
     *
     * @var array<int, string>
     */
    protected $table = 'habilidades';

    protected $fillable = ['nombre', 'descripcion', 'nivel'];

    /**
     * Relación muchos a muchos con el modelo User.
     *
     * Una habilidad puede pertenecer a varios usuarios,
     * y un usuario puede tener múltiples habilidades.
     *
     * Tabla pivote: `user_habilidad`
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_habilidad');
    }
}
