<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aranceles_rangos', function (Blueprint $table) {
            $table->id();
            $table->integer('categoria'); // 1, 2, 3...
            $table->decimal('valor_inicial', 12, 2);
            $table->decimal('valor_final', 12, 2)->nullable(); // NULL para 'de X en adelante'
            $table->decimal('derecho_inscripcion', 10, 2)->default(0.00);
            $table->decimal('porcentaje_gastos_generales', 5, 2)->default(70.00); // 70% por defecto
            $table->decimal('porcentaje_exceso', 5, 3)->default(0.000); // 0.5% (0.005) para exceso
            $table->decimal('valor_total_calculado', 10, 2)->default(0.00); // Suma final o base fija
            $table->decimal('tope_maximo', 10, 2)->nullable(); // Ej: 500.00
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aranceles_rangos');
    }
};