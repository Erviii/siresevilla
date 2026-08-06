<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ArancelService
{
    public static function calcular($codigoServicio, $cuantia = 0, $cantidadPredios = 1, $tipoExoneracion = 'NINGUNA')
    {
        $servicio = DB::table('aranceles_servicios')->where('codigo', $codigoServicio)->first();

        if (!$servicio) {
            return ['subtotal' => 0.00, 'descuento' => 0.00, 'total' => 0.00];
        }

        $subtotal = 0.00;

        switch ($servicio->tipo_calculo) {
            case 'TABLA_PROGRESIVA':
                $subtotal = self::calcularSegunTablaRangos($cuantia);
                break;

            case 'POR_PREDIO':
                $subtotal = $servicio->valor_fijo * max(1, $cantidadPredios);
                break;

            case 'VALOR_FIJO':
                $subtotal = $servicio->valor_fijo;
                break;

            case 'GRATUITO':
                $subtotal = 0.00;
                break;
        }

        // Aplicar exoneración del 50% si el servicio la permite y el usuario califica (Arts. 52 y 53)
        $descuento = 0.00;
        if ($servicio->permite_exoneracion && in_array($tipoExoneracion, ['TERCERA_EDAD', 'DISCAPACIDAD']) && $subtotal > 0) {
            $descuento = $subtotal * 0.50;
        }

        $total = $subtotal - $descuento;

        return [
            'subtotal' => round($subtotal, 2),
            'descuento' => round($descuento, 2),
            'total' => round($total, 2),
        ];
    }

    private static function calcularSegunTablaRangos($cuantia)
    {
        // Buscar el rango donde cae la cuantía
        $rango = DB::table('aranceles_rangos')
            ->where('valor_inicial', '<=', $cuantia)
            ->where(function ($query) use ($cuantia) {
                $query->where('valor_final', '>=', $cuantia)
                      ->orWhereNull('valor_final');
            })
            ->first();

        if (!$rango) {
            return 0.00;
        }

        // Caso de Categoría 1 a 9 (Valores fijos precalculados en la tabla)
        if ($cuantia <= 10000) {
            return $rango->valor_total_calculado;
        }

        // Caso Categoría 10 (De 10.000,01 en adelante)
        $exceso = $cuantia - 10000;
        $derechoInscripcion = $rango->derecho_inscripcion + ($exceso * ($rango->porcentaje_exceso / 100));
        $gastosGenerales = $derechoInscripcion * ($rango->porcentaje_gastos_generales / 100);
        $totalCalculado = $derechoInscripcion + $gastosGenerales;

        // Respetar tope máximo fijado en la tabla (Art. 33 = $500.00)
        if ($rango->tope_maximo && $totalCalculado > $rango->tope_maximo) {
            return $rango->tope_maximo;
        }

        return $totalCalculado;
    }
}