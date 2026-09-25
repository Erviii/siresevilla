<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('sctncmovi', function (Blueprint $table) {
        // Ajusta el 'after' según la columna donde quieras que aparezcan
        $table->string('tip_doc', 50)->nullable()->after('id'); 
        $table->date('fec_doc')->nullable()->after('tip_doc');
    });
}

public function down()
{
    Schema::table('sctncmovi', function (Blueprint $table) {
        $table->dropColumn(['tip_doc', 'fec_doc']);
    });
}
};
