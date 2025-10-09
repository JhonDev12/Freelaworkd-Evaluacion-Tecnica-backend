<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Proyecto
 *
 * Representa un proyecto en el sistema Freelaworkd.
 * Cada proyecto tiene un usuario autenticado como propietario.
 */
class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'presupuesto',
        'estado',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
