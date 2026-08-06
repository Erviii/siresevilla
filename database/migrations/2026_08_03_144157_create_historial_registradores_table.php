<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historial_registradores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->string('titulo_profesional')->nullable(); // Ej: Abg. / Dr.
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable(); // NULL indica que es el registrador VIGENTE/ACTUAL
            $table->boolean('es_actual')->default(true); // Flag para consultas rápidas
            $table->string('accion_designacion')->nullable(); // Ej: Nro. de Acción de Personal o Resolución
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_registradores');
    }
};