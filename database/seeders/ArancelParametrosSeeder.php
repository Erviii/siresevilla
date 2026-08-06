<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArancelParametrosSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar registros previos si los hay
        DB::table('aranceles_rangos')->truncate();
        DB::table('aranceles_servicios')->truncate();

        // 1. Cargar la Tabla Progresiva del Art. 33 (Estructura uniforme para PostgreSQL)
        DB::table('aranceles_rangos')->insert([
            ['categoria' => 1, 'valor_inicial' => 1.00, 'valor_final' => 800.00, 'derecho_inscripcion' => 37.00, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 62.90, 'tope_maximo' => 500.00],
            ['categoria' => 2, 'valor_inicial' => 801.00, 'valor_final' => 1200.00, 'derecho_inscripcion' => 44.25, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 75.23, 'tope_maximo' => 500.00],
            ['categoria' => 3, 'valor_inicial' => 1201.00, 'valor_final' => 1600.00, 'derecho_inscripcion' => 58.90, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 100.13, 'tope_maximo' => 500.00],
            ['categoria' => 4, 'valor_inicial' => 1601.00, 'valor_final' => 2000.00, 'derecho_inscripcion' => 74.55, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 126.74, 'tope_maximo' => 500.00],
            ['categoria' => 5, 'valor_inicial' => 2001.00, 'valor_final' => 2400.00, 'derecho_inscripcion' => 80.00, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 136.00, 'tope_maximo' => 500.00],
            ['categoria' => 6, 'valor_inicial' => 2401.00, 'valor_final' => 2800.00, 'derecho_inscripcion' => 85.00, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 144.50, 'tope_maximo' => 500.00],
            ['categoria' => 7, 'valor_inicial' => 2801.00, 'valor_final' => 3200.00, 'derecho_inscripcion' => 90.00, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 153.00, 'tope_maximo' => 500.00],
            ['categoria' => 8, 'valor_inicial' => 3201.00, 'valor_final' => 3600.00, 'derecho_inscripcion' => 95.00, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 161.50, 'tope_maximo' => 500.00],
            ['categoria' => 9, 'valor_inicial' => 3601.00, 'valor_final' => 10000.00, 'derecho_inscripcion' => 100.00, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.00, 'valor_total_calculado' => 170.00, 'tope_maximo' => 500.00],
            ['categoria' => 10, 'valor_inicial' => 10000.01, 'valor_final' => null, 'derecho_inscripcion' => 100.00, 'porcentaje_gastos_generales' => 70.00, 'porcentaje_exceso' => 0.50, 'valor_total_calculado' => 170.00, 'tope_maximo' => 500.00],
        ]);

        // 2. Cargar Catálogo de Servicios / Actos Registrales
        DB::table('aranceles_servicios')->insert([
            ['codigo' => 'TABLA_CUANTIA', 'nombre' => 'Transferencia de Dominio / Compraventa / Adjudicación', 'articulo_ordenanza' => 'Art. 33', 'tipo_calculo' => 'TABLA_PROGRESIVA', 'valor_fijo' => 0.00, 'permite_exoneracion' => true],
            ['codigo' => 'HIPOTECA_GEN', 'nombre' => 'Inscripción de Hipoteca General (por predio)', 'articulo_ordenanza' => 'Art. 34', 'tipo_calculo' => 'POR_PREDIO', 'valor_fijo' => 100.00, 'permite_exoneracion' => true],
            ['codigo' => 'HIPOTECA_BIESS', 'nombre' => 'Inscripción de Hipoteca BIESS / BanEcuador / BEV (por predio)', 'articulo_ordenanza' => 'Art. 34', 'tipo_calculo' => 'POR_PREDIO', 'valor_fijo' => 50.00, 'permite_exoneracion' => true],
            ['codigo' => 'PROP_HORIZONTAL', 'nombre' => 'Declaratoria de Propiedad Horizontal', 'articulo_ordenanza' => 'Art. 35', 'tipo_calculo' => 'VALOR_FIJO', 'valor_fijo' => 34.00, 'permite_exoneracion' => false],
            ['codigo' => 'PATRIMONIO_TESTAMENTO', 'nombre' => 'Inscripción / Cancelación Patrimonio Familiar o Testamento', 'articulo_ordenanza' => 'Art. 36', 'tipo_calculo' => 'VALOR_FIJO', 'valor_fijo' => 14.00, 'permite_exoneracion' => false],
            ['codigo' => 'REINSCRIPCION_MORONA', 'nombre' => 'Inscripción de predio anteriormente en Cantón Morona', 'articulo_ordenanza' => 'Art. 37', 'tipo_calculo' => 'VALOR_FIJO', 'valor_fijo' => 20.00, 'permite_exoneracion' => false],
            ['codigo' => 'SUBDIVISION_UNIF', 'nombre' => 'Subdivisiones / Unificaciones (por lote/predio)', 'articulo_ordenanza' => 'Art. 39', 'tipo_calculo' => 'POR_PREDIO', 'valor_fijo' => 10.00, 'permite_exoneracion' => false],
            ['codigo' => 'CERTIFICADO_GRAVAMEN', 'nombre' => 'Certificado de Gravámenes / Bienes Raíces (por bien)', 'articulo_ordenanza' => 'Art. 40-3', 'tipo_calculo' => 'POR_PREDIO', 'valor_fijo' => 10.00, 'permite_exoneracion' => false],
            ['codigo' => 'PROHIBICION_EMBARGO', 'nombre' => 'Prohibiciones de Enajenar / Embargos / Medidas Cautelares', 'articulo_ordenanza' => 'Art. 40-2', 'tipo_calculo' => 'POR_PREDIO', 'valor_fijo' => 10.00, 'permite_exoneracion' => false],
            ['codigo' => 'POSESION_EFECTIVA', 'nombre' => 'Inscripción Posesión Efectiva (por inmueble)', 'articulo_ordenanza' => 'Art. 40-4', 'tipo_calculo' => 'POR_PREDIO', 'valor_fijo' => 10.00, 'permite_exoneracion' => false],
            ['codigo' => 'CUANTIA_INDETERMINADA', 'nombre' => 'Aclaraciones / Rectificaciones / Cuantía Indeterminada', 'articulo_ordenanza' => 'Art. 46', 'tipo_calculo' => 'POR_PREDIO', 'valor_fijo' => 100.00, 'permite_exoneracion' => true],
            ['codigo' => 'GRATUITO_MAG', 'nombre' => 'Adjudicaciones / Providencias MAG / Subsecretaría de Tierras', 'articulo_ordenanza' => 'Art. 47', 'tipo_calculo' => 'GRATUITO', 'valor_fijo' => 0.00, 'permite_exoneracion' => false],
        ]);
    }
}