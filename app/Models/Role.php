<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Eloquent que representa un rol dentro del sistema.
 *
 * Los roles definen los niveles de acceso y permisos que puede tener un usuario
 * dentro de la aplicación (por ejemplo, administrador, cliente o freelancer).
 *
 * Responsabilidades:
 * - Gestionar la persistencia de los roles del sistema.
 * - Definir la relación uno a muchos con los usuarios.
 * - Servir como punto central para la asignación de privilegios.
 */
class Role extends Model
{
    use HasFactory;

    /**
     * Atributos que pueden asignarse masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Relación uno a muchos con el modelo User.
     *
     * Un rol puede estar asociado a múltiples usuarios dentro del sistema.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
