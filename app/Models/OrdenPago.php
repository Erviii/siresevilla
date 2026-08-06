<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenPago extends Model
{
    use HasFactory;

    protected $table = 'ordenes_pago';

    protected $fillable = [
        'numero_orden',
        'factura_nro',
        'nombre_solicitante',
        'cedula_solicitante',
        'correo_notificacion',
        'otorgado_por',
        'a_favor_de',
        'cuantia_texto',
        'cuantia_monto',
        'fecha_ingreso',
        'total',
        'estado',
        'observaciones',
        'devuelto_nombre',
        'devuelto_cedula',
        'devuelto_fecha',
        'elaborado_por',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'devuelto_fecha' => 'date',
        'total' => 'decimal:2',
    ];

    public function detalles()
    {
        return $this->hasMany(OrdenPagoDetalle::class, 'orden_pago_id');
    }

    public static function generarNumeroOrden()
    {
        $anioActual = date('Y');
        $ultimo = self::whereYear('created_at', $anioActual)->orderBy('id', 'desc')->first();
        $secuencia = $ultimo ? ((int) substr($ultimo->numero_orden, -5)) + 1 : 1;

        return 'OP-' . $anioActual . '-' . str_pad($secuencia, 5, '0', STR_PAD_LEFT);
    }
}