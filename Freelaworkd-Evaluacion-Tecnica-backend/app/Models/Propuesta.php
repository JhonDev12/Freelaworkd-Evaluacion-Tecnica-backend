<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Propuesta
 *
 * Representa la propuesta que un freelancer envía a un proyecto.
 * Cada propuesta pertenece a un usuario autenticado y a un proyecto.
 */
class Propuesta extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyecto_id',
        'usuario_id',
        'descripcion',
        'presupuesto',
        'tiempo_estimado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
