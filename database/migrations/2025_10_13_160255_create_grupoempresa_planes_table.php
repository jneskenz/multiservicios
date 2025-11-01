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
        Schema::create('grupoempresa_planes', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('grupoempresa_id')->constrained('grupo_empresas')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('planes')->onDelete('cascade');

            // Fechas del ciclo
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_renovacion')->nullable();

            // Estado 1 o 2 de la suscripción
            $table->unsignedTinyInteger('estado')->default(1);  // 1=activo, 2=expirado, 3=suspendido, 4=cancelado

            // Tipo de pago o modalidad
            $table->enum('tipo_cobro', ['mensual', 'anual'])->default('mensual');

            // Datos financieros
            $table->decimal('monto', 10, 2)->nullable();
            $table->string('moneda', 10)->default('PEN');
            $table->string('referencia_pago')->nullable();

            // Flags de control
            $table->boolean('renovacion_automatica')->default(true);
            $table->boolean('notificado_expiracion')->default(false);

            // Control
            $table->foreignId('asignado_por')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupoempresa_planes');
    }
};
