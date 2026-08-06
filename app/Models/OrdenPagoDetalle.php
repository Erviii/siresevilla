<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenPagoDetalle extends Model
{
    use HasFactory;

    protected $table = 'ordenes_pago_detalles';

    protected $fillable = [
        'orden_pago_id',
        'orden',
        'codigo_servicio',
        'descripcion',
        'valor',
    ];

    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class, 'orden_pago_id');
    }
}