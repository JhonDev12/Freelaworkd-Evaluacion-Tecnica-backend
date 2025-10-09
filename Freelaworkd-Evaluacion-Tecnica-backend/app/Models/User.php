<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modelo Eloquent que representa a un usuario dentro del sistema Freelaworkd.
 *
 * Implementa autenticación mediante Laravel Sanctum y gestiona
 * la información principal de cada cuenta registrada (cliente o freelancer).
 *
 * Características:
 * - Usa hashing automático de contraseñas a través del cast 'hashed'.
 * - Incluye notificaciones y tokens de API para autenticación segura.
 * - Define relaciones con roles, proyectos, propuestas y habilidades.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos que pueden asignarse masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Campos ocultos en las respuestas JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión automática de tipos de datos y hashing de contraseña.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role_id'           => 'integer',
        ];
    }

    /**
     * Relación con el modelo Role.
     *
     * Cada usuario pertenece a un rol que define su nivel de permisos
     * (por ejemplo: administrador, cliente o freelancer).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación muchos a muchos con el modelo Habilidad.
     *
     * Un usuario puede tener múltiples habilidades, y cada habilidad
     * puede pertenecer a varios usuarios. Relación gestionada por
     * la tabla pivote `user_habilidad`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function habilidades()
    {
        return $this->belongsToMany(Habilidad::class, 'user_habilidad');
    }

    /**
     * Relación uno a muchos con el modelo Proyecto.
     *
     * Un usuario puede ser propietario de varios proyectos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'usuario_id');
    }

    /**
     * Relación uno a muchos con el modelo Propuesta.
     *
     * Un usuario (freelancer) puede enviar varias propuestas
     * a diferentes proyectos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function propuestas()
    {
        return $this->hasMany(Propuesta::class, 'usuario_id');
    }
}
