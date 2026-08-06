<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aranceles_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Ej: TABLA_CUANTIA, HIPOTECA_GEN, HIPOTECA_BIESS, CERTIFICADO
            $table->string('nombre'); // Ej: Hipoteca Abierta
            $table->string('articulo_ordenanza')->nullable(); // Ej: Art. 34
            $table->enum('tipo_calculo', ['TABLA_PROGRESIVA', 'VALOR_FIJO', 'POR_PREDIO', 'GRATUITO']);
            $table->decimal('valor_fijo', 10, 2)->default(0.00); // Para valores fijos o base por predio
            $table->boolean('permite_exoneracion')->default(true); // Si aplica 50% Tercera Edad / Discapacidad
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aranceles_servicios');
    }
};