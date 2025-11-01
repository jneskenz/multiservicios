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
        Schema::create('planes', function (Blueprint $table) {
            $table->id();

            // Identificación básica
            $table->string('nombre');
            $table->string('slug')->unique();

            // Descripción y detalles visibles
            $table->text('descripcion')->nullable();

            // Configuración del plan
            $table->decimal('precio_mensual', 10, 2)->default(0);
            $table->decimal('precio_anual', 10, 2)->nullable();
            $table->integer('duracion_dias')->default(30); // duración por defecto (mensual)

            // Límites de uso
            $table->integer('limite_usuarios')->nullable(); // null = ilimitado
            $table->integer('limite_sucursales')->nullable();
            $table->integer('limite_modulos')->nullable();
            $table->integer('limite_almacenamiento_mb')->nullable();

            // Módulos incluidos (json)
            $table->json('modulos_incluidos')->nullable();

            // Estado del plan
            $table->unsignedTinyInteger('estado')->default(1); // 1=activo, 2=inactivo, 3=eliminado

            // Control y trazabilidad
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
