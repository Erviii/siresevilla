<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordenes_pago', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orden')->unique(); // Ej: OP-2026-00001
            $table->string('factura_nro')->nullable(); // Número de factura emitido por Tesorería

            // Datos del Solicitante
            $table->string('nombre_solicitante');
            $table->string('cedula_solicitante', 13);
            $table->string('correo_notificacion')->nullable();
            
            // Datos del Acto / Contrato
            $table->string('otorgado_por'); // Ej: Notaría Nro. 1, Juez de Coactivas, etc.
            $table->string('a_favor_de');   // Beneficiario / Comprador
            $table->string('cuantia_texto')->default('INDETERMINADA'); // "INDETERMINADA" o monto en formato texto
            $table->decimal('cuantia_monto', 12, 2)->default(0.00);   // Valor numérico para cálculos
            $table->date('fecha_ingreso');

            // Totales
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['PENDIENTE', 'PAGADO', 'DEVUELTO', 'ANULADO'])->default('PENDIENTE');

            // Sección de Observaciones y Devolución
            $table->text('observaciones')->nullable();
            $table->string('devuelto_nombre')->nullable();
            $table->string('devuelto_cedula')->nullable();
            $table->date('devuelto_fecha')->nullable();

            // Auditoría
            $table->string('elaborado_por'); // Nombre del funcionario que crea la orden
            $table->timestamps();
        });

        // Tabla detalle para soportar varios actos/rubros en una misma orden de pago
        Schema::create('ordenes_pago_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_pago_id')->constrained('ordenes_pago')->onDelete('cascade');
            $table->integer('orden'); // 1, 2, 3...
            $table->string('codigo_servicio')->nullable();
            $table->string('descripcion'); // Ej: PROHIBICION DE ENAJENAR
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordenes_pago_detalles');
        Schema::dropIfExists('ordenes_pago');
    }
};