<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialRegistrador extends Model
{
    use HasFactory;

    protected $table = 'historial_registradores';

    protected $fillable = [
        'nombre_completo',
        'titulo_profesional',
        'fecha_inicio',
        'fecha_fin',
        'es_actual',
        'accion_designacion',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'es_actual' => 'boolean',
    ];

    // Scope para obtener fácilmente el registrador actual
    public function scopeActual($query)
    {
        return $query->where('es_actual', true);
    }
}