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
        Schema::table('sctncmovi', function (Blueprint $table) {
            // Campo de texto de 150 caracteres (nullable evita errores si ya hay registros)
            $table->string('canton_notaria', 150)->nullable()->after('id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sctncmovi', function (Blueprint $table) {
            $table->dropColumn('canton_notaria');
        });
    }
};
