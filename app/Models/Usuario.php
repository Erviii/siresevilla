<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // 1. Apuntamos a tu tabla real
    protected $table = 'sctnmusua'; 
    
    // 2. Definimos tu llave primaria
    protected $primaryKey = 'usualogin'; 
    
    // IMPORTANTE: Si USUACODUSU no es un número autoincremental, descomenta estas dos líneas:
    public $incrementing = false;
    protected $keyType = 'string';

    // 3. Desactivamos los timestamps (porque tu tabla antigua no tiene created_at ni updated_at)
    public $timestamps = false;

    // 4. Indicamos a Laravel cuál es la columna de la contraseña
    public function getAuthPassword()
    {
        // ⚠️ CAMBIA 'USUACLAVE' por el nombre real de la columna de la contraseña en tu base de datos
        return $this->usuapasswr; 
    }
}